<?php
/**
 * Bride&Co Stock Rules
 * 
 * RULE 1 (Non-Bridal): Stock <= 2 → grey out all sizes, hide Add to Cart, product stays visible
 * RULE 2 (Bridal):      Stock = 0  → hide product entirely from shop/category pages
 * RULE 3:               Stock = 0  → hide product everywhere (already handled by WooCommerce out-of-stock visibility)
 *
 * Add this to your child theme's functions.php:
 *   require_once get_stylesheet_directory() . '/inc/stock-rules.php';
 */

if (!defined('ABSPATH')) exit;

// ─────────────────────────────────────────────
// HELPER: Check if a product belongs to any bridal category
// ─────────────────────────────────────────────
function brideco_is_bridal_product($product_id) {
    // All bridal category slugs — add more if needed
    $bridal_slugs = array(
        'bridal',
        'wedding-dresses',
        'wedding-dresses-of-the-week',
        'bridal-accessories',
        'bridal-gowns',
    );

    $terms = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'slugs'));
    if (is_wp_error($terms)) return false;

    // Also check parent categories
    $all_slugs = array();
    $term_objects = wp_get_post_terms($product_id, 'product_cat');
    if (!is_wp_error($term_objects)) {
        foreach ($term_objects as $term) {
            $all_slugs[] = $term->slug;
            // Walk up ancestors
            $ancestors = get_ancestors($term->term_id, 'product_cat');
            foreach ($ancestors as $ancestor_id) {
                $ancestor = get_term($ancestor_id, 'product_cat');
                if ($ancestor && !is_wp_error($ancestor)) {
                    $all_slugs[] = $ancestor->slug;
                }
            }
        }
    }

    return !empty(array_intersect($bridal_slugs, $all_slugs));
}

// ─────────────────────────────────────────────
// HELPER: Get effective stock for a product
// For variable products, returns the MAX stock across all variations
// ─────────────────────────────────────────────
function brideco_get_effective_stock($product) {
    if ($product->is_type('variable')) {
        $max_stock = 0;
        $children = $product->get_children();
        foreach ($children as $child_id) {
            $variation = wc_get_product($child_id);
            if ($variation) {
                $qty = $variation->get_stock_quantity();
                if ($qty === null) return 999; // Not managing stock = unlimited
                $max_stock = max($max_stock, (int)$qty);
            }
        }
        return $max_stock;
    }

    $qty = $product->get_stock_quantity();
    return ($qty === null) ? 999 : (int)$qty;
}

// ─────────────────────────────────────────────
// HELPER: Get the stock threshold for a product
// Bridal = 0 (hide at 0), Non-bridal = 2 (grey out at <=2)
// ─────────────────────────────────────────────
function brideco_get_stock_threshold($product_id) {
    return brideco_is_bridal_product($product_id) ? 0 : 2;
}

// ─────────────────────────────────────────────
// RULE 2: Hide bridal products with 0 stock from queries
// ─────────────────────────────────────────────
// WooCommerce already hides out-of-stock products if the setting is enabled.
// But to be safe, we also filter bridal products with 0 stock explicitly.
add_action('woocommerce_product_query', 'brideco_hide_zero_stock_bridal', 20);
function brideco_hide_zero_stock_bridal($query) {
    if (is_admin()) return;

    // Get all bridal products with 0 stock to exclude
    // This runs on archive/shop pages only
    $exclude_ids = get_transient('brideco_hidden_bridal_ids');
    
    if ($exclude_ids === false) {
        $exclude_ids = array();
        
        $bridal_args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => array('bridal', 'wedding-dresses', 'wedding-dresses-of-the-week', 'bridal-accessories', 'bridal-gowns'),
                    'operator' => 'IN',
                ),
            ),
            'meta_query'     => array(
                array(
                    'key'     => '_stock_status',
                    'value'   => 'outofstock',
                    'compare' => '=',
                ),
            ),
        );
        
        $bridal_query = new WP_Query($bridal_args);
        if ($bridal_query->have_posts()) {
            $exclude_ids = $bridal_query->posts;
        }
        wp_reset_postdata();
        
        // Cache for 5 minutes
        set_transient('brideco_hidden_bridal_ids', $exclude_ids, 5 * MINUTE_IN_SECONDS);
    }

    if (!empty($exclude_ids)) {
        $existing = $query->get('post__not_in');
        if (!is_array($existing)) $existing = array();
        $query->set('post__not_in', array_merge($existing, $exclude_ids));
    }
}

// Clear the transient when stock changes
add_action('woocommerce_product_set_stock', 'brideco_clear_bridal_cache');
add_action('woocommerce_variation_set_stock', 'brideco_clear_bridal_cache');
function brideco_clear_bridal_cache($product) {
    delete_transient('brideco_hidden_bridal_ids');
}

// ─────────────────────────────────────────────
// RULE 1: Non-bridal products — check if should be "locked" (stock <= 2)
// This function is used in the single product template
// ─────────────────────────────────────────────
function brideco_is_product_locked($product) {
    $product_id = $product->get_id();
    
    // Bridal products don't get "locked" — they get hidden at 0 instead
    if (brideco_is_bridal_product($product_id)) {
        return false;
    }

    $stock = brideco_get_effective_stock($product);
    return ($stock <= 2);
}

// ─────────────────────────────────────────────
// RULE 1: For variable products, check per-variation stock
// Returns array of variation_ids that are locked (stock <= 2)
// ─────────────────────────────────────────────
function brideco_get_locked_variations($product) {
    $locked = array();
    
    if (!$product->is_type('variable')) return $locked;
    if (brideco_is_bridal_product($product->get_id())) return $locked;

    foreach ($product->get_children() as $child_id) {
        $variation = wc_get_product($child_id);
        if (!$variation) continue;
        
        $qty = $variation->get_stock_quantity();
        // If stock is managed and <= 2, lock it
        if ($qty !== null && (int)$qty <= 2) {
            $locked[] = $child_id;
        }
    }
    
    return $locked;
}

// ─────────────────────────────────────────────
// Pass stock rule data to JavaScript on single product pages
// ─────────────────────────────────────────────
add_action('wp_footer', 'brideco_stock_rules_js', 50);
function brideco_stock_rules_js() {
    if (!is_product()) return;
    
    global $product;
    if (!$product) return;

    $product_id  = $product->get_id();
    $is_bridal   = brideco_is_bridal_product($product_id);
    $threshold   = brideco_get_stock_threshold($product_id);
    $is_locked   = brideco_is_product_locked($product);
    
    // Build per-variation stock data
    $variation_stock = array();
    if ($product->is_type('variable')) {
        foreach ($product->get_children() as $child_id) {
            $variation = wc_get_product($child_id);
            if (!$variation) continue;
            
            $qty = $variation->get_stock_quantity();
            $attrs = $variation->get_attributes();
            
            $variation_stock[] = array(
                'id'         => $child_id,
                'stock'      => ($qty === null) ? 999 : (int)$qty,
                'in_stock'   => $variation->is_in_stock(),
                'attributes' => $attrs,
            );
        }
    }
    
    ?>
    <script type="text/javascript">
    (function($) {
        var STOCK_RULES = {
            productId:      <?php echo (int)$product_id; ?>,
            isBridal:       <?php echo $is_bridal ? 'true' : 'false'; ?>,
            threshold:      <?php echo (int)$threshold; ?>,
            isLocked:       <?php echo $is_locked ? 'true' : 'false'; ?>,
            variationStock: <?php echo json_encode($variation_stock); ?>
        };

        // Find matching variations for selected attributes
        function findMatchingVariations(selectedAttrs) {
            return STOCK_RULES.variationStock.filter(function(v) {
                var match = true;
                Object.keys(selectedAttrs).forEach(function(key) {
                    var val = selectedAttrs[key];
                    if (!val) return; // skip empty selections
                    var vKey = key.replace('attribute_', '');
                    if (v.attributes[vKey] && v.attributes[vKey] !== '' && v.attributes[vKey] !== val) {
                        match = false;
                    }
                });
                return match;
            });
        }

        // Check if a specific option value is purchasable given current selections
        function isOptionPurchasable(attrName, attrValue, currentSelections) {
            var testAttrs = $.extend({}, currentSelections);
            testAttrs[attrName] = attrValue;
            
            var matches = findMatchingVariations(testAttrs);
            // Option is purchasable if ANY matching variation has stock > threshold
            var purchasable = false;
            matches.forEach(function(v) {
                if (!STOCK_RULES.isBridal && v.stock > 2) {
                    purchasable = true;
                } else if (STOCK_RULES.isBridal && v.stock > 0 && v.in_stock) {
                    purchasable = true;
                } else if (v.stock > 2) {
                    // Default: purchasable if stock > 2 for non-bridal
                    purchasable = true;
                }
            });
            return purchasable;
        }

        // Apply stock rules to swatches
        function applyStockRules() {
            if (STOCK_RULES.isBridal) return; // Bridal handled by hiding

            // Collect current selections
            var currentSelections = {};
            $('.color-option.active, .size-option.active, .attribute-option.active').each(function() {
                currentSelections[$(this).data('attribute')] = $(this).data('value');
            });

            // For each swatch option, check if purchasable
            $('.color-option, .size-option, .attribute-option').each(function() {
                var $opt = $(this);
                var attrName = $opt.data('attribute');
                var attrValue = String($opt.data('value'));
                var attrType = $opt.data('attribute-type');

                // Find all variations that match this specific option
                var matchingVars = STOCK_RULES.variationStock.filter(function(v) {
                    var vKey = attrName.replace('attribute_', '');
                    return v.attributes[vKey] === attrValue || v.attributes[vKey] === '';
                });

                // Check if ALL matching variations are at or below threshold
                var allLocked = matchingVars.length > 0 && matchingVars.every(function(v) {
                    return v.stock <= 2;
                });

                if (allLocked) {
                    $opt.addClass('stock-locked disabled-option');
                    if (attrType === 'size') {
                        $opt.addClass('greyed-out');
                    } else if (attrType === 'color') {
                        $opt.addClass('line-through');
                    }
                    $opt.attr('title', 'Low stock - not available for purchase');
                }
            });

            // If ALL variations are locked, hide the add to cart button
            if (STOCK_RULES.isLocked) {
                $('#custom-add-to-cart-btn').hide();
                $('.quantity-container').hide();
                
                if (!$('.stock-locked-notice').length) {
                    $('.action-buttons').prepend(
                        '<div class="stock-locked-notice view-only-notice">' +
                        '<i class="fa-solid fa-info-circle"></i> ' +
                        'This product is currently not available for online purchase. ' +
                        'Please book an appointment to enquire in-store.' +
                        '</div>'
                    );
                }
            }
        }

        // Re-apply after variation selection changes
        $(document).on('click', '.color-option, .size-option, .attribute-option', function() {
            // Small delay to let the existing click handler run first
            setTimeout(applyStockRules, 100);
        });

        // Re-apply after clear selection
        $(document).on('click', '#clearSelectionBtn', function() {
            $('.stock-locked').removeClass('stock-locked');
            setTimeout(applyStockRules, 100);
        });

        // Initial apply
        $(document).ready(function() {
            applyStockRules();
        });

    })(jQuery);
    </script>
    <style>
        /* Stock locked swatch styling */
        .stock-locked.size-option {
            background: #f5f5f5 !important;
            color: #bbb !important;
            border-color: #ddd !important;
            cursor: not-allowed !important;
            pointer-events: none;
            text-decoration: line-through;
        }
        .stock-locked.color-option {
            opacity: 0.4 !important;
            cursor: not-allowed !important;
            pointer-events: none;
        }
        .stock-locked.color-option::after {
            content: "";
            position: absolute;
            top: 50%;
            left: -2px;
            right: -2px;
            height: 2px;
            background: #ff0000;
            transform: rotate(-45deg);
            z-index: 5;
        }
        .stock-locked-notice {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 12px 20px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
    <?php
}

// ─────────────────────────────────────────────
// ADMIN: Show stock rule indicator on product list
// ─────────────────────────────────────────────
add_filter('manage_product_posts_columns', 'brideco_add_stock_rule_column');
function brideco_add_stock_rule_column($columns) {
    $new_columns = array();
    foreach ($columns as $key => $val) {
        $new_columns[$key] = $val;
        if ($key === 'is_in_stock') {
            $new_columns['stock_rule'] = 'Stock Rule';
        }
    }
    return $new_columns;
}

add_action('manage_product_posts_custom_column', 'brideco_stock_rule_column_content', 10, 2);
function brideco_stock_rule_column_content($column, $post_id) {
    if ($column !== 'stock_rule') return;
    
    $product = wc_get_product($post_id);
    if (!$product) return;

    $is_bridal = brideco_is_bridal_product($post_id);
    $stock = brideco_get_effective_stock($product);
    
    if ($is_bridal) {
        echo '<span style="background:#e8d5f5;padding:2px 8px;border-radius:3px;font-size:11px;">BRIDAL</span>';
        if ($stock <= 0) {
            echo '<br><span style="color:red;font-size:11px;">⛔ Hidden (0 stock)</span>';
        } else {
            echo '<br><span style="color:green;font-size:11px;">✅ Visible (stock: ' . $stock . ')</span>';
        }
    } else {
        echo '<span style="background:#d5e8f5;padding:2px 8px;border-radius:3px;font-size:11px;">STANDARD</span>';
        if ($stock <= 2) {
            echo '<br><span style="color:orange;font-size:11px;">🔒 Locked (stock: ' . $stock . ')</span>';
        } else {
            echo '<br><span style="color:green;font-size:11px;">✅ Purchasable (stock: ' . $stock . ')</span>';
        }
    }
}

// Make the column sortable
add_filter('manage_edit-product_sortable_columns', 'brideco_stock_rule_sortable');
function brideco_stock_rule_sortable($columns) {
    $columns['stock_rule'] = 'stock_rule';
    return $columns;
}

// ─────────────────────────────────────────────
// PREVENT ADD TO CART for locked non-bridal products (server-side safety)
// ─────────────────────────────────────────────
add_filter('woocommerce_add_to_cart_validation', 'brideco_validate_stock_rule', 10, 3);
function brideco_validate_stock_rule($passed, $product_id, $quantity) {
    $product = wc_get_product($product_id);
    if (!$product) return $passed;

    // Skip bridal — they are just hidden, not locked
    if (brideco_is_bridal_product($product_id)) return $passed;

    $stock = brideco_get_effective_stock($product);
    if ($stock <= 2) {
        wc_add_notice('This product is currently not available for online purchase. Please book an appointment to enquire in-store.', 'error');
        return false;
    }

    return $passed;
}

// Also validate variation-level
add_filter('woocommerce_add_to_cart_validation', 'brideco_validate_variation_stock_rule', 11, 5);
function brideco_validate_variation_stock_rule($passed, $product_id, $quantity, $variation_id = 0, $variations = array()) {
    if (!$variation_id) return $passed;
    if (brideco_is_bridal_product($product_id)) return $passed;

    $variation = wc_get_product($variation_id);
    if (!$variation) return $passed;

    $qty = $variation->get_stock_quantity();
    if ($qty !== null && (int)$qty <= 2) {
        wc_add_notice('This size/colour combination is currently not available for online purchase. Please book an appointment.', 'error');
        return false;
    }

    return $passed;
}

// ─────────────────────────────────────────────
// FILTER AJAX handler — also apply stock rules
// ─────────────────────────────────────────────
add_filter('woocommerce_product_is_visible', 'brideco_filter_bridal_visibility', 10, 2);
function brideco_filter_bridal_visibility($visible, $product_id) {
    if (is_admin()) return $visible;
    
    // Only apply on shop/archive pages, not single product
    if (is_product()) return $visible;
    
    if (brideco_is_bridal_product($product_id)) {
        $product = wc_get_product($product_id);
        if ($product && !$product->is_in_stock()) {
            return false;
        }
    }
    
    return $visible;
}