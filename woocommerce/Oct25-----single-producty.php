<?php
/**
 * Enhanced Single Product Template with Dynamic Variations and Stock Rules
 * 
 * @package Bride-co-child
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header('shop');

// Helper function to check if product is in bridal category
function is_bridal_product($product_id) {
    $bridal_categories = array('bridal', 'bridal-gowns', 'wedding-dresses', 'bridal-accessories');
    $product_categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'slugs'));
    
    foreach ($product_categories as $cat_slug) {
        foreach ($bridal_categories as $bridal_cat) {
            if (stripos($cat_slug, $bridal_cat) !== false) {
                return true;
            }
        }
    }
    return false;
}

// Helper function to check effective stock status
function get_effective_stock_status($product) {
    $is_bridal = is_bridal_product($product->get_id());
    $stock_quantity = $product->get_stock_quantity();
    $manages_stock = $product->managing_stock();
    
    // Only apply stock rules if stock is being managed
    if (!$manages_stock) {
        return 'available';
    }
    
    // For bridal products: hide if stock is exactly 0
    if ($is_bridal && $stock_quantity !== null && $stock_quantity == 0) {
        return 'hidden';
    }
    
    // For non-bridal products: disable purchase if stock is 2 or less
    if (!$is_bridal && $stock_quantity !== null && $stock_quantity <= 2 && $stock_quantity >= 0) {
        return 'view_only';
    }
    
    // If product is out of stock according to WooCommerce
    if (!$product->is_in_stock()) {
        return 'view_only';
    }
    
    return 'available';
}

// Function to convert color names to hex codes
function get_color_hex_code($color_name, $sku = '') {
    // First check if this is a product with a SKU-based color
    if (!empty($sku)) {
        // SKU-based color mapping
        $sku_color_map = array(
            'SO01548' => '#2D2E3E', // Midnight
            'SO01549' => '#16474A', // Gem
            'SO01550' => '#0E0E0C', // Black
            'SO01553' => '#0E0E0C', // Black
            'SO01558' => '#0E0E0C', // Black
            'SO01557' => '#0E0E0C', // Black
            'SO01547' => '#0E0E0C', // Black
            'SO01551' => '#0E0E0C', // Black
            'SO01503' => '#0E0E0C', // Black
            'SO01490' => '#0E0E0C', // Black
            'SO01476' => '#0E0E0C', // Black
            'SO01443' => '#0E0E0C', // Black
            'SO01360' => '#0E0E0C', // Black
        );
        
        // Find if the SKU is in our mapping
        foreach ($sku_color_map as $sku_prefix => $hex_code) {
            if (strpos($sku, $sku_prefix) === 0) {
                return $hex_code;
            }
        }
    }
    
    // Custom shop color mappings with exact brand colors
    $color_map = array(
        // Store-specific color palette
        'biscotti' => '#CFB6A3',
        'black' => '#0E0E0C',
        'midnight' => '#2D2E3E',
        'charcoal' => '#3C474E',
        'chianti' => '#A4545B',
        'cinnamon' => '#943B2B',
        'dusty sage' => '#B4C0AC',
        'ecru' => '#BCAEA2',
        'forest green' => '#1F5154',
        'gem' => '#16474A',
        'grey' => '#91A1B0',
        'juniper' => '#0B546B',
        'marine' => '#1E2734',
        'navy' => '#1D3253',
        'quartz' => '#C69892',
        'burgundy' => '#53263C',
        'steel blue' => '#B5CCE9',
        'beige' => '#D4C3B9',
        'wine' => '#571A25',
        
        // Standard color fallbacks
        'red' => '#FF0000',
        'blue' => '#B5CCE9',
        'green' => '#008000',
        'white' => '#FFFFFF',
        'yellow' => '#FFFF00',
        'purple' => '#800080',
        'pink' => '#FFC0CB',
        'orange' => '#FFA500',
        'gray' => '#91A1B0',
        'brown' => '#A52A2A',
        'silver' => '#C0C0C0',
        'gold' => '#FFD700',
        'ivory' => '#FFFFF0',
        'cream' => '#FFFDD0',
        'teal' => '#008080',
        'sage' => '#B4C0AC',
        'maroon' => '#800000',
        'indigo' => '#4B0082',
        'sow' => '#000000',
    );
    
    // Convert color name to lowercase for case-insensitive matching
    $color_name_lower = strtolower(trim($color_name));
    
    // Check for color in the map
    if (isset($color_map[$color_name_lower])) {
        return $color_map[$color_name_lower];
    }
    
    // Check if the color name already contains a hex code
    if (preg_match('/#([a-fA-F0-9]{3}){1,2}\b/', $color_name)) {
        return $color_name;
    }
    
    // Check if this is a product SKU being used as a color name
    if (preg_match('/^SO\d+$/', $color_name)) {
        // Find if the color name is a SKU in our mapping
        $sku_color_map = array(
            'SO01548' => '#2D2E3E', // Midnight
            'SO01549' => '#16474A', // Gem
            'SO01550' => '#0E0E0C', // Black
        );
        
        foreach ($sku_color_map as $sku_prefix => $hex_code) {
            if (strpos($color_name, $sku_prefix) === 0) {
                return $hex_code;
            }
        }
    }
    
    // Default fallback color (light grey)
    return '#DDDDDD';
}

// Enhanced size guide button with modal
function render_enhanced_size_guide_button() {
    global $product;
    
    $measuring_guide_url = '/fitting-guide';
    
    if ($product) {
        $terms = get_the_terms($product->get_id(), 'pa_brand');
        
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                if (stripos($term->name, 'euro suit') !== false || stripos($term->name, 'eurosuit') !== false) {
                    $measuring_guide_url = 'https://stage.brideandco.co.za/euro-suit-fitting-guide/';
                    break;
                }
            }
        }
    }
    ?>
    <div class="size-guide">
        <i class="fa-solid fa-ruler"></i> 
        <button onclick="openSizeGuideModal()" style="margin-right: 10px;">Size Guide</button>
        <a href="<?php echo esc_url($measuring_guide_url); ?>" target="_blank">How to Measure</a>
    </div>
    <?php
}

// Main product loop
while (have_posts()) : the_post();
    global $product;
    
    // Check if product should be visible (bridal stock rule)
    $stock_status = get_effective_stock_status($product);
    if ($stock_status === 'hidden') {
        // Redirect to shop page if bridal product is out of stock
        wp_redirect(get_permalink(wc_get_page_id('shop')));
        exit;
    }
    
    // Get product data
    $attachment_ids = $product->get_gallery_image_ids();
    $main_image_id = $product->get_image_id();
    $main_image_url = wp_get_attachment_url($main_image_id);
    $fallback_image = 'https://stage.brideandco.co.za/wp-content/uploads/2022/05/cropped-BrideCo-Logo.png';
    
    $is_new = function_exists('is_product_new') ? is_product_new($product->get_id()) : false;
    $is_eurosuite = function_exists('is_eurosuit_page') ? is_eurosuit_page() : false;
    $newlabel_style = $is_eurosuite ? 'new-badge-euro' : '';
    
    // Get brand
    $brand = '';
    $brand_terms = get_the_terms($product->get_id(), 'pa_brand');
    if (!empty($brand_terms) && !is_wp_error($brand_terms)) {
        $brand = $brand_terms[0]->name;
    }
    
    // Get price info
    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();
    $is_on_sale = $product->is_on_sale();
    $discount_percentage = 0;
    
    if ($is_on_sale && $regular_price > 0) {
        $discount_percentage = round(100 - (($sale_price / $regular_price) * 100));
    }
    
    // Check if hire category
    $categories = wp_get_post_terms($product->get_id(), 'product_cat');
    $is_hire = false;
    foreach ($categories as $term) {
        if ($term->name === 'Hire') {
            $is_hire = true;
            break;
        }
    }
    
    // Get technical specification
    $tech_spec = '';
    $tech_spec_terms = get_the_terms($product->get_id(), 'pa_technical-spec');
    if (!empty($tech_spec_terms) && !is_wp_error($tech_spec_terms)) {
        $tech_spec = $tech_spec_terms[0]->name;
    }
    
    if (empty($tech_spec)) {
        $meta_dress_name = get_post_meta($product->get_id(), 'meta_dress_name', true);
        if (!empty($meta_dress_name) && $meta_dress_name !== '{{product.meta_dress name}}') {
            $tech_spec = $meta_dress_name;
        }
    }
    
    // Enhanced variation data with stock info
    $available_variations = array();
    $has_purchasable_variations = false;
    if ($product->is_type('variable')) {
        $variations = $product->get_available_variations();
        foreach ($variations as $variation) {
            $variation_obj = wc_get_product($variation['variation_id']);
            $variation['stock_quantity'] = $variation_obj->get_stock_quantity();
            $variation['effective_stock_status'] = get_effective_stock_status($variation_obj);
            
            // Check if this variation can be purchased
            // Be more lenient - if stock is not managed or status is available, allow purchase
            if ($variation_obj->is_in_stock() && $variation['is_purchasable']) {
                if ($variation['effective_stock_status'] === 'available') {
                    $has_purchasable_variations = true;
                }
            }
            
            $available_variations[] = $variation;
        }
    }
    
    // Check if product is in bridal category
    $is_bridal = is_bridal_product($product->get_id());
    
    // Get current stock quantity for display
    $stock_quantity = $product->get_stock_quantity();
    $can_purchase = true; // Start with true by default
    
    // For simple products
    if (!$product->is_type('variable')) {
        // Only restrict if we have explicit stock rules
        if ($stock_status === 'view_only' || $stock_status === 'hidden') {
            $can_purchase = false;
        } elseif (!$product->is_in_stock()) {
            $can_purchase = false;
        }
    } else {
        // For variable products, check if any variation can be purchased
        $can_purchase = $has_purchasable_variations;
    }
    
    // Get product SKU for color mapping
    $product_sku = $product->get_sku();
?>

<style>
.related-products .image-container{
height: 360px!important;
}
</style>

<div class="container">
    <div class="breadcrumb">
        <?php woocommerce_breadcrumb(); ?>
    </div>
    
    <div class="product-container">
        <!-- Product Gallery -->
        <div class="product-gallery clearfix">
            <div class="thumbnail-sidebar">
                <?php 
                if ($main_image_url) {
                    echo '<div class="thumbnail active">';
                    echo '<img src="' . esc_url($main_image_url) . '" alt="' . esc_attr(get_the_title()) . '">';
                    echo '</div>';
                }
                
                foreach ($attachment_ids as $attachment_id) {
                    $image_url = wp_get_attachment_url($attachment_id);
                    if ($image_url) {
                        echo '<div class="thumbnail">';
                        echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '">';
                        echo '</div>';
                    }
                }
                
                if (!$main_image_url && empty($attachment_ids)) {
                    echo '<div class="thumbnail active">';
                    echo '<img src="' . esc_url($fallback_image) . '" alt="' . esc_attr(get_the_title()) . '">';
                    echo '</div>';
                }
                ?>
            </div>
            
            <div class="main-image-container">
                <?php if ($is_new) : ?>
                <div class="new-badge <?php echo $newlabel_style; ?>">NEW</div>
                <?php endif; ?>
                
                <button class="add-to-favorites">
                    <i class="fa-regular fa-heart"></i>
                </button>
                
                <div class="main-image">
                    <img src="<?php echo esc_url($main_image_url ? $main_image_url : $fallback_image); ?>" 
                         alt="<?php echo esc_attr(get_the_title()); ?>">
                </div>
                
                <div class="navigation-arrows">
                    <div class="nav-arrow prev"><i class="fa-solid fa-chevron-left"></i></div>
                    <div class="nav-arrow next"><i class="fa-solid fa-chevron-right"></i></div>
                </div>
            </div>
        </div>
        
        <!-- Product Info Section -->
        <div class="product-info">
            <?php if ($brand) : ?>
            <div class="brand-name"><?php echo esc_html($brand); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($tech_spec)) : ?>
            <div class="tech-spec-title"><?php echo esc_html($tech_spec); ?></div>
            <?php endif; ?>
            
            <h1 class="product-title"><?php the_title(); ?></h1>
            
            <div class="product-short-description">
                <?php echo apply_filters('woocommerce_short_description', $post->post_excerpt); ?>
            </div>
            
            <!-- Enhanced Stock Status Display -->
            <div class="stock-status <?php echo !$can_purchase ? 'low-stock' : ''; ?>">
                <i class="fa-solid fa-circle"></i> 
                <?php 
                if (!$can_purchase && !$is_bridal) {
                    echo 'Limited availability - View only';
                } elseif ($product->get_stock_status() === 'instock') {
                    echo 'In stock';
                } elseif ($product->get_stock_status() === 'outofstock') {
                    echo 'Out of stock';
                } else {
                    echo 'Limited stock';
                }
                ?>
            </div>
            
            <?php if ($product->get_sku()) : ?>
            <div class="product-code">Product Code: <?php echo esc_html($product->get_sku()); ?></div>
            <?php endif; ?>
            
            <!-- Price Display -->
            <div class="price-container">
                <?php if ($is_on_sale && $discount_percentage > 0) : ?>
                    <div class="discount-badge"><?php echo esc_html($discount_percentage); ?>%</div>
                <?php endif; ?>
                
                <div class="price-box">
                    <?php if ($is_on_sale && $product->get_regular_price()) : ?>
                        <div class="old-price"><?php echo wc_price($product->get_regular_price()); ?></div>
                        <div class="current-price"><?php echo wc_price($product->get_sale_price()); ?></div>
                    <?php elseif (!$is_hire) : ?>
                        <div class="current-price"><?php echo $product->get_price_html(); ?></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Enhanced Variation Display -->
            <?php
            if ($product->is_type('variable')) :
                $attributes = $product->get_variation_attributes();
                
                // Create PHP color mapping for JavaScript
                $color_mappings = array();
                
                foreach ($attributes as $attribute_name => $options) :
                    $attribute_label = wc_attribute_label($attribute_name);
                    $attribute_id = sanitize_title($attribute_name);
                    $selected_value = isset($_REQUEST['attribute_' . $attribute_id]) ? 
                                     wc_clean(wp_unslash($_REQUEST['attribute_' . $attribute_id])) : 
                                     $product->get_variation_default_attribute($attribute_name);
                    
                    $full_attribute_name = 'attribute_' . $attribute_id;
                    
                    // Detect attribute type
                    $is_color = stripos($attribute_label, 'color') !== false || 
                               stripos($attribute_label, 'colour') !== false ||
                               stripos($attribute_name, 'color') !== false ||
                               stripos($attribute_name, 'colour') !== false;
                    $is_size = stripos($attribute_label, 'size') !== false || 
                              stripos($attribute_name, 'size') !== false;
                    
                    $attr_class = 'attribute-selection';
                    $option_class = 'attribute-option';
                    
                    if ($is_color) {
                        $attr_class = 'color-selection';
                        $option_class = 'color-option';
                        
                        // Build color mappings for this attribute
                        foreach ($options as $option) {
                            $color_mappings[$option] = get_color_hex_code($option, $product_sku);
                        }
                    } elseif ($is_size) {
                        $attr_class = 'size-selection';
                        $option_class = 'size-option';
                    }
            ?>
            <div class="<?php echo esc_attr($attr_class); ?>" 
                 data-attribute="<?php echo esc_attr($full_attribute_name); ?>"
                 data-attribute-type="<?php echo $is_color ? 'color' : ($is_size ? 'size' : 'other'); ?>">
                <div class="attribute-title"><?php echo esc_html($attribute_label); ?></div>
                <div class="attribute-options">
                    <?php 
                    if ($is_size) {
                        // Sort sizes numerically if possible
                        sort($options, SORT_NUMERIC);
                    }
                    
                    foreach ($options as $option) : 
                        $is_selected = ($selected_value === $option);
                    ?>
                        <div class="<?php echo esc_attr($option_class . ' ' . ($is_selected ? 'active' : '')); ?>" 
                             data-value="<?php echo esc_attr($option); ?>"
                             data-attribute="<?php echo esc_attr($full_attribute_name); ?>"
                             data-attribute-type="<?php echo $is_color ? 'color' : ($is_size ? 'size' : 'other'); ?>"
                             title="<?php echo esc_attr($option); ?>">
                            <?php 
                            if ($is_color) {
                                // Get actual color hex from mapping
                                $color_hex = get_color_hex_code($option, $product_sku);
                                ?>
                                <span class="color-swatch" style="background-color: <?php echo esc_attr($color_hex); ?>"></span>
                                <span class="color-name"><?php echo esc_html($option); ?></span>
                                <?php
                            } else {
                                echo esc_html($option);
                            }
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php 
                endforeach;
            ?>
            
            <!-- Clear Selection Button -->
            <div class="clear-selection-wrapper" style="margin-top: 15px;">
                <button type="button" class="btn-clear-selection" id="clearSelectionBtn" style="display: none;">
                    <i class="fa-solid fa-undo"></i> Clear Selection
                </button>
            </div>
            <?php
            endif;
            ?>
            
            <div class="shipping-info">
                Orders placed now will be shipped within 3-7 business days.
            </div>
            
            <?php if (has_term('Hire', 'product_cat')) : ?>
            <div class="shipping-info">
                Hire products only available in-store.
            </div>
            <?php endif; ?>
            
            <?php render_enhanced_size_guide_button(); ?>
            
            <!-- Action Buttons with Stock Rules -->
            <div class="action-buttons">
                <div class="quantity-container" <?php echo !$can_purchase ? 'style="display:none;"' : ''; ?>>
                    <button class="quantity-btn minus">-</button>
                    <input type="text" class="quantity-input" value="1" name="quantity">
                    <button class="quantity-btn plus">+</button>
                </div>
                
                <?php if (!$is_hire) : ?>
                    <?php if ($product->is_type('variable')) : ?>
                        <button class="add-to-cart" id="custom-add-to-cart-btn" <?php echo !$can_purchase ? 'style="display:none;"' : ''; ?>>
                            ADD TO CART
                        </button>
                    <?php else : ?>
                        <?php if ($can_purchase) : ?>
                            <button class="add-to-cart" onclick="addToCartSimpleProduct(<?php echo esc_attr($product->get_id()); ?>, jQuery('.quantity-input').val())">
                                ADD TO CART
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php if (!$can_purchase) : ?>
                    <div class="view-only-notice">
                        <i class="fa-solid fa-info-circle"></i>
                        This product is for viewing only. Please visit our store for availability.
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <a href="/book-your-free-fitting">
                    <button class="appointment-btn">BOOK APPOINTMENT</button>
                </a>
                
                <div class="add-to-cart wishlist-btn">
                    <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
                </div>
            </div>
            
            <!-- Hidden WooCommerce form -->
            <div style="display: none;" class="woo-cart-form-wrapper">
                <?php woocommerce_template_single_add_to_cart(); ?>
            </div>
            
            <!-- Shipping Features -->
            <div class="shipping-features">
                <div class="shipping-feature">
                    <img class="custom-icon" src="/wp-content/themes/bride-co-child/assets/imgs/truck-regular-full.svg"/>
                    <div>Nationwide Shipping</div>
                </div>
                <div class="shipping-feature">
                    <i class="fa-regular fa-clock"></i>
                    <div>3-7 Day Delivery</div>
                </div>
                <div class="shipping-feature">
                     <img class="custom-icon" src="/wp-content/themes/bride-co-child/assets/imgs/headset-solid-full.svg"/>
                    <div>Customer Service Support</div>
                </div>
            </div>
            
            <!-- Product Details Accordion -->
            <div class="product-details">
                <h3>
                    Product Description
                    <i class="fa-solid fa-minus"></i>
                </h3>
                <div class="product-details-content">
                    <?php the_content(); ?>
                </div>
            </div>
            
            <div class="returns-container">
                <h3>
                    Returns and Exchanges
                    <i class="fa-solid fa-plus"></i>
                </h3>
            </div>
        </div>
    </div>
    
    <!-- Related Products Section (keeping your existing code) -->
    <div class="related-products">
        <h2>Recently Viewed</h2>
        <section class="container my-5">
            <div class="row g-4">
                <?php
                // Your existing recently viewed products code stays the same
                $viewed_products = !empty($_COOKIE['woocommerce_recently_viewed']) ? (array) explode('|', $_COOKIE['woocommerce_recently_viewed']) : array();
                $viewed_products = array_diff($viewed_products, array($product->get_id()));
                $viewed_products = array_reverse(array_slice($viewed_products, -4));

                if (!empty($viewed_products)) {
                    $args = array(
                        'post_type'      => 'product',
                        'post_status'    => 'publish',
                        'post__in'       => $viewed_products,
                        'orderby'        => 'post__in',
                        'posts_per_page' => 4,
                    );
                } else {
                    $product_cats = wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'ids'));
                    $args = array(
                        'post_type'      => 'product',
                        'post_status'    => 'publish',
                        'post__not_in'   => array($product->get_id()),
                        'posts_per_page' => 4,
                        'orderby'        => 'rand',
                        'tax_query'      => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field'    => 'id',
                                'terms'    => $product_cats,
                                'operator' => 'IN',
                            ),
                        ),
                    );
                }

                $related_query = new WP_Query($args);
                
                if ($related_query->have_posts()) {
                    while ($related_query->have_posts()) {
                        $related_query->the_post();
                        // Your existing product card code stays the same
                        get_template_part('template-parts/product', 'card');
                    }
                    wp_reset_postdata();
                }
                ?>
            </div>
        </section>
    </div>
    
    <hr>
    <?php bride_co_render_accessories_child_categories(); ?>
</div>

<!-- Size Guide Modal -->
<?php
$categories = wp_get_post_terms($product->get_id(), 'product_cat');
$image_url = '';
if (!empty($categories) && !is_wp_error($categories)) {
    $category_id = $categories[0]->term_id;
    $size_guide_image = get_field('size_guide', 'product_cat_' . $category_id);
    if ($size_guide_image) {
        $image_url = $size_guide_image['url'];
    }
}
?>

<div id="sizeGuideModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeSizeGuideModal()">&times;</span>
        <h2>Size Guide</h2>
        <?php if ($image_url): ?>
            <img src="<?php echo esc_url($image_url); ?>" alt="Size Guide">
        <?php else: ?>
            <p>No size guide available for this product category.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Enhanced JavaScript with Stock Rules and Color Mapping -->
<script type="text/javascript">
var productVariations = <?php echo json_encode($available_variations); ?>;
var isProductBridal = <?php echo $is_bridal ? 'true' : 'false'; ?>;
var productStockQuantity = <?php echo $stock_quantity !== null ? $stock_quantity : 'null'; ?>;
var colorMappings = <?php echo json_encode(isset($color_mappings) ? $color_mappings : array()); ?>;

jQuery(document).ready(function($) {
    var selectedAttributes = {};
    
    // Enhanced debug function with clear stock states
    window.debugProductStock = function() {
        console.log('%c╔══════════════════════════════════════════════════════════╗', 'color: #28a745; font-weight: bold;');
        console.log('%c║          PRODUCT STOCK ANALYSIS DASHBOARD                ║', 'color: #28a745; font-weight: bold;');
        console.log('%c╚══════════════════════════════════════════════════════════╝', 'color: #28a745; font-weight: bold;');
        
        console.log('%c📊 STOCK THRESHOLDS:', 'color: #007bff; font-weight: bold;');
        console.log('   Non-Bridal Products:');
        console.log('   • ≤ 2 items: CANNOT PURCHASE (view only, in-store only)');
        console.log('   • > 2 items: CAN PURCHASE');
        console.log('   Bridal Products:');
        console.log('   • 0 items: HIDDEN (redirected to shop)');
        console.log('   • > 0 items: Follow standard rules\n');
        
        const is_bridal = isProductBridal;
        console.log(`%c🎀 Product Type: ${is_bridal ? 'BRIDAL' : 'NON-BRIDAL'}`, 'color: #8e44ad; font-weight: bold;');
        console.log(`📦 Base Product Stock: ${productStockQuantity !== null ? productStockQuantity : 'Not tracked'}\n`);
        
        if (productVariations && productVariations.length > 0) {
            console.log('%c📋 VARIATION DETAILS:', 'color: #17a2b8; font-weight: bold;');
            
            let summary = {
                total: productVariations.length,
                purchasable: 0,
                viewOnly: 0,
                hidden: 0,
                outOfStock: 0
            };
            
            productVariations.forEach((variation, index) => {
                let status = variation.effective_stock_status || 'available';
                let stockQty = variation.stock_quantity;
                let canPurchase = (status === 'available' && variation.is_in_stock);
                
                let statusEmoji = '❓';
                let statusText = 'Unknown';
                let statusColor = 'color: #6c757d;';
                
                if (status === 'hidden') {
                    statusEmoji = '👁️‍🗨️';
                    statusText = `HIDDEN (Bridal 0 stock)`;
                    statusColor = 'color: #6c757d;';
                    summary.hidden++;
                } else if (status === 'view_only') {
                    statusEmoji = '🔒';
                    statusText = `VIEW ONLY (Stock: ${stockQty} ≤ 2)`;
                    statusColor = 'color: #ffc107;';
                    summary.viewOnly++;
                } else if (!variation.is_in_stock) {
                    statusEmoji = '❌';
                    statusText = `OUT OF STOCK`;
                    statusColor = 'color: #dc3545;';
                    summary.outOfStock++;
                } else if (canPurchase) {
                    statusEmoji = '✅';
                    statusText = `PURCHASABLE (Stock: ${stockQty})`;
                    statusColor = 'color: #28a745;';
                    summary.purchasable++;
                }
                
                console.log(`%c${statusEmoji} Variation #${index + 1}: ${statusText}`, statusColor);
                
                // Show attributes
                let attrs = [];
                Object.keys(variation.attributes).forEach(key => {
                    let value = variation.attributes[key];
                    if (value) {
                        attrs.push(`${key.replace('attribute_pa_', '').replace('attribute_', '')}: ${value}`);
                    }
                });
                console.log(`   Attributes: ${attrs.join(', ')}`);
                console.log(`   Stock Quantity: ${stockQty !== null ? stockQty : 'Not tracked'}`);
                console.log(`   Can Purchase: ${canPurchase ? 'YES' : 'NO'}`);
                console.log('');
            });
            
            console.log('%c╔══════════════════════════════════════════════════════════╗', 'color: #007bff; font-weight: bold;');
            console.log('%c║                      SUMMARY                              ║', 'color: #007bff; font-weight: bold;');
            console.log('%c╚══════════════════════════════════════════════════════════╝', 'color: #007bff; font-weight: bold;');
            console.log(`Total Variations: ${summary.total}`);
            console.log(`✅ Purchasable: ${summary.purchasable}`);
            console.log(`🔒 View Only (≤2 stock): ${summary.viewOnly}`);
            console.log(`❌ Out of Stock: ${summary.outOfStock}`);
            console.log(`👁️‍🗨️ Hidden (Bridal 0): ${summary.hidden}`);
            
            let shouldShowAddToCart = summary.purchasable > 0;
            console.log(`\n${shouldShowAddToCart ? '✅' : '❌'} Add to Cart Button Should Be: ${shouldShowAddToCart ? 'VISIBLE' : 'HIDDEN'}`);
        }
    };
    
    // Enhanced variation management with stock rules
    function initDynamicVariations() {
        // Build comprehensive variation map with stock info
        var variationMap = {};
        var stockMap = {};
        
        if (typeof productVariations !== 'undefined' && productVariations.length > 0) {
            productVariations.forEach(function(variation) {
                // Check stock rules
                var effectiveStatus = variation.effective_stock_status || 'available';
                var canPurchase = (effectiveStatus === 'available' && variation.is_in_stock);
                
                // Store stock info
                if (!stockMap[variation.variation_id]) {
                    stockMap[variation.variation_id] = {
                        quantity: variation.stock_quantity,
                        canPurchase: canPurchase,
                        status: effectiveStatus
                    };
                }
                
                // Build variation availability map
                Object.keys(variation.attributes).forEach(function(attrKey) {
                    if (!variationMap[attrKey]) {
                        variationMap[attrKey] = {};
                    }
                    
                    var attrValue = variation.attributes[attrKey];
                    
                    if (!variationMap[attrKey][attrValue]) {
                        variationMap[attrKey][attrValue] = {
                            variations: [],
                            relatedAttributes: {}
                        };
                    }
                    
                    variationMap[attrKey][attrValue].variations.push(variation.variation_id);
                    
                    // Map related attributes with their availability
                    Object.keys(variation.attributes).forEach(function(otherAttrKey) {
                        if (otherAttrKey !== attrKey) {
                            if (!variationMap[attrKey][attrValue].relatedAttributes[otherAttrKey]) {
                                variationMap[attrKey][attrValue].relatedAttributes[otherAttrKey] = {};
                            }
                            
                            var otherValue = variation.attributes[otherAttrKey];
                            if (otherValue) {
                                if (!variationMap[attrKey][attrValue].relatedAttributes[otherAttrKey][otherValue]) {
                                    variationMap[attrKey][attrValue].relatedAttributes[otherAttrKey][otherValue] = {
                                        variationIds: [],
                                        canPurchase: false
                                    };
                                }
                                
                                variationMap[attrKey][attrValue].relatedAttributes[otherAttrKey][otherValue].variationIds.push(variation.variation_id);
                                
                                // Update purchase status
                                if (canPurchase) {
                                    variationMap[attrKey][attrValue].relatedAttributes[otherAttrKey][otherValue].canPurchase = true;
                                }
                            }
                        }
                    });
                });
            });
        }
        
        return {variationMap: variationMap, stockMap: stockMap};
    }
    
    var {variationMap, stockMap} = initDynamicVariations();
    
    // Update available options based on selection with stock rules
    function updateAvailableOptions(selectedAttr, selectedValue) {
        if (!variationMap[selectedAttr] || !variationMap[selectedAttr][selectedValue]) {
            return;
        }
        
        var availableOptions = variationMap[selectedAttr][selectedValue].relatedAttributes;
        
        // Update all other attribute options
        $('.attribute-option, .color-option, .size-option').each(function() {
            var $option = $(this);
            var optionAttr = $option.data('attribute');
            var optionValue = $option.data('value');
            var optionType = $option.data('attribute-type');
            
            // Skip the currently selected attribute type
            if (optionAttr === selectedAttr) {
                return;
            }
            
            // Check availability and stock status
            var isAvailable = false;
            var canPurchase = false;
            
            if (availableOptions[optionAttr] && availableOptions[optionAttr][optionValue]) {
                isAvailable = true;
                canPurchase = availableOptions[optionAttr][optionValue].canPurchase;
            }
            
            // Apply appropriate styling based on availability and stock
            if (!isAvailable) {
                // Not available at all
                $option.addClass('disabled-option unavailable')
                       .removeClass('low-stock')
                       .attr('title', 'Not available with selected ' + getAttributeLabel(selectedAttr));
                       
                if (optionType === 'color') {
                    // Add line through for colors
                    $option.addClass('line-through');
                } else if (optionType === 'size') {
                    // Grey out sizes
                    $option.addClass('greyed-out');
                }
            } else if (!canPurchase) {
                // Available but low stock (stock <= 2 for non-bridal)
                $option.addClass('disabled-option low-stock')
                       .removeClass('unavailable')
                       .attr('title', 'Limited availability - View only');
                       
                if (optionType === 'size') {
                    $option.addClass('greyed-out');
                }
            } else {
                // Fully available
                $option.removeClass('disabled-option unavailable low-stock greyed-out line-through')
                       .attr('title', optionValue);
            }
        });
    }
    
    // Clear selection functionality
    $('#clearSelectionBtn').on('click', function() {
        // Clear all selections
        $('.attribute-option, .color-option, .size-option').removeClass('active');
        
        // Clear the selectedAttributes object
        selectedAttributes = {};
        
        // Reset WooCommerce form
        $('select[name^="attribute_"]').val('').trigger('change');
        
        // Hide clear button
        $(this).fadeOut();
        
        // Reset to original state
        $('.attribute-option, .color-option, .size-option').removeClass('disabled-option unavailable low-stock greyed-out line-through');
        
        // Check if we should show/hide add to cart
        checkPurchaseAvailability();
    });
    
    // Handle option clicks
    $('.color-option, .size-option, .attribute-option').on('click', function(e) {
        e.preventDefault();
        
        var $this = $(this);
        
        // Don't allow selection of disabled options
        if ($this.hasClass('disabled-option')) {
            return false;
        }
        
        var value = $this.data('value');
        var attributeName = $this.data('attribute');
        
        // Remove active class from siblings and add to selected
        $this.siblings().removeClass('active');
        $this.addClass('active');
        
        // Show clear button
        $('#clearSelectionBtn').fadeIn();
        
        // Update selected attributes
        selectedAttributes[attributeName] = value;
        
        // Update hidden WooCommerce form
        var select = $('select[name="' + attributeName + '"]');
        if (select.length) {
            select.val(value).trigger('change');
        }
        
        // Update available options
        updateAvailableOptions(attributeName, value);
        
        // Check if selected combination can be purchased
        checkPurchaseAvailability();
    });
    
    // Check if current selection can be purchased
    function checkPurchaseAvailability() {
        var canPurchase = true; // Start with true by default
        var stockMessage = '';
        
        // Find matching variation
        var matchingVariation = null;
        if (productVariations && productVariations.length > 0) {
            // Check if all required attributes are selected
            var requiredAttributesCount = $('.variation-group').length;
            var selectedAttributesCount = Object.keys(selectedAttributes).length;
            
            if (selectedAttributesCount === requiredAttributesCount) {
                // All attributes selected, find matching variation
                productVariations.forEach(function(variation) {
                    var matches = true;
                    Object.keys(selectedAttributes).forEach(function(attrKey) {
                        if (variation.attributes[attrKey] !== selectedAttributes[attrKey]) {
                            matches = false;
                        }
                    });
                    if (matches) {
                        matchingVariation = variation;
                    }
                });
                
                if (matchingVariation) {
                    // Only block purchase if explicitly restricted
                    if (matchingVariation.effective_stock_status === 'view_only') {
                        canPurchase = false;
                        stockMessage = 'Limited stock (≤2) - Available in-store only';
                    } else if (matchingVariation.effective_stock_status === 'hidden') {
                        canPurchase = false;
                        stockMessage = 'This item is not available';
                    } else if (!matchingVariation.is_in_stock) {
                        canPurchase = false;
                        stockMessage = 'Out of stock';
                    } else {
                        // Show stock warning if low but still purchasable
                        if (matchingVariation.stock_quantity && matchingVariation.stock_quantity <= 5) {
                            stockMessage = `Only ${matchingVariation.stock_quantity} left in stock`;
                            // Still can purchase, just showing warning
                        }
                    }
                }
            }
        }
        
        // Update UI
        if (canPurchase) {
            $('#custom-add-to-cart-btn').show();
            $('.quantity-container').show();
            $('.view-only-notice').hide();
            
            // Show stock warning if exists
            if (stockMessage) {
                if (!$('.stock-warning').length) {
                    $('.action-buttons').prepend(`
                        <div class="stock-warning">
                            <i class="fa-solid fa-exclamation-triangle"></i>
                            ${stockMessage}
                        </div>
                    `);
                } else {
                    $('.stock-warning').html(`
                        <i class="fa-solid fa-exclamation-triangle"></i>
                        ${stockMessage}
                    `).show();
                }
            } else {
                $('.stock-warning').hide();
            }
        } else {
            $('#custom-add-to-cart-btn').hide();
            $('.quantity-container').hide();
            if (!$('.view-only-notice').length) {
                $('.action-buttons').prepend(`
                    <div class="view-only-notice">
                        <i class="fa-solid fa-info-circle"></i>
                        ${stockMessage || 'This product is for viewing only. Please visit our store for availability.'}
                    </div>
                `);
            } else {
                $('.view-only-notice').html(`
                    <i class="fa-solid fa-info-circle"></i>
                    ${stockMessage || 'This product is for viewing only. Please visit our store for availability.'}
                `).show();
            }
        }
    }
    
    // Initialize with any pre-selected options
    $('.attribute-option.active, .color-option.active, .size-option.active').each(function() {
        var $this = $(this);
        selectedAttributes[$this.data('attribute')] = $this.data('value');
    });
    
    // Trigger initial update if we have selections
    if (Object.keys(selectedAttributes).length > 0) {
        Object.keys(selectedAttributes).forEach(function(attr) {
            updateAvailableOptions(attr, selectedAttributes[attr]);
        });
        checkPurchaseAvailability();
    }
    
    // Handle add to cart for variable products
    $('#custom-add-to-cart-btn').on('click', function(e) {
        e.preventDefault();
        
        // Validate all variations are selected
        var allSelected = true;
        var missingAttributes = [];
        
        $('form.variations_form').find('select[name^="attribute_"]').each(function() {
            if ($(this).val() === '') {
                allSelected = false;
                var attrName = $(this).attr('name').replace('attribute_pa_', '').replace('attribute_', '').replace(/-/g, ' ');
                missingAttributes.push(attrName);
            }
        });
        
        if (!allSelected) {
            alert('Please select: ' + missingAttributes.join(', '));
            return;
        }
        
        // Set quantity and submit
        var quantity = $('.quantity-input').val();
        $('form.cart .quantity input').val(quantity);
        $('form.cart .single_add_to_cart_button').click();
    });
    
    // Quantity controls
    $('.quantity-btn.minus').on('click', function() {
        var input = $('.quantity-input');
        var val = parseInt(input.val());
        if (val > 1) {
            input.val(val - 1);
            $('form.cart .quantity input').val(val - 1);
        }
    });
    
    $('.quantity-btn.plus').on('click', function() {
        var input = $('.quantity-input');
        var val = parseInt(input.val());
        var max = parseInt(input.attr('max'));
        
        if (!max || val < max) {
            input.val(val + 1);
            $('form.cart .quantity input').val(val + 1);
        } else {
            alert('Maximum quantity reached');
        }
    });
    
    // Product image gallery
    $('.thumbnail').on('click', function() {
        $('.thumbnail').removeClass('active');
        $(this).addClass('active');
        $('.main-image img').attr('src', $(this).find('img').attr('src'));
    });
    
    // Navigation arrows
    $('.nav-arrow.prev').on('click', function() {
        var active = $('.thumbnail.active');
        var prev = active.prev('.thumbnail');
        if (prev.length) {
            active.removeClass('active');
            prev.addClass('active');
            $('.main-image img').attr('src', prev.find('img').attr('src'));
        }
    });
    
    $('.nav-arrow.next').on('click', function() {
        var active = $('.thumbnail.active');
        var next = active.next('.thumbnail');
        if (next.length) {
            active.removeClass('active');
            next.addClass('active');
            $('.main-image img').attr('src', next.find('img').attr('src'));
        }
    });
    
    // Accordion functionality
    $('.product-details h3').on('click', function() {
        var content = $(this).next('.product-details-content');
        var icon = $(this).find('i');
        
        if (content.is(':visible')) {
            content.slideUp();
            icon.removeClass('fa-minus').addClass('fa-plus');
        } else {
            content.slideDown();
            icon.removeClass('fa-plus').addClass('fa-minus');
        }
    });
    
    $('.returns-container h3').on('click', function() {
        var content = $(this).next('.returns-content');
        var icon = $(this).find('i');
        
        if (!content.length) {
            content = $('<div class="returns-content">').html(`
                <p>When purchasing from Complete Bride & Co, products in the discount category that don't involve alterations like hemming and resizing, can be exchanged. For this, you need to make an appointment 3-7 days in advance.</p>
                <p>Returns and exchanges are not available for altered items.</p>
            `);
            $(this).after(content);
            icon.removeClass('fa-plus').addClass('fa-minus');
        } else {
            content.toggle();
            icon.toggleClass('fa-plus fa-minus');
        }
    });
    
    // Favorites button
    $('.add-to-favorites').on('click', function() {
        var icon = $(this).find('i');
        icon.toggleClass('fa-regular fa-solid');
        if (icon.hasClass('fa-solid')) {
            icon.css('color', '#d9304f');
        } else {
            icon.css('color', '#333');
        }
    });
    
    // Run debug on page load
    $(window).on('load', function() {
        // Run debug in console
        if (window.location.hostname === 'localhost' || 
            window.location.hostname === '127.0.0.1' ||
            window.location.search.includes('debug=true')) {
            console.log('%c🔍 Stock Debug Mode Active - Type debugProductStock() anytime', 'color: #28a745;');
            debugProductStock();
        }
    });
});

// Helper function for attribute labels
function getAttributeLabel(attrName) {
    var label = attrName.replace('attribute_pa_', '').replace('attribute_', '').replace(/-/g, ' ');
    return label.charAt(0).toUpperCase() + label.slice(1);
}

// Simple product add to cart
function addToCartSimpleProduct(product_id, quantity) {
    jQuery.ajax({
        type: 'POST',
        url: wc_add_to_cart_params.ajax_url,
        data: {
            action: 'woocommerce_add_to_cart',
            product_id: product_id,
            quantity: quantity
        },
        success: function(response) {
            if (response.error) {
                alert(response.error_message);
            } else {
                alert('Product added to cart!');
                jQuery(document.body).trigger('wc_fragment_refresh');
            }
        }
    });
}

// Size guide modal functions
function openSizeGuideModal() {
    document.getElementById('sizeGuideModal').style.display = 'block';
}

function closeSizeGuideModal() {
    document.getElementById('sizeGuideModal').style.display = 'none';
}

window.onclick = function(event) {
    var modal = document.getElementById('sizeGuideModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

<!-- Enhanced CSS with Stock Rules Styling -->
<style>
/* Your existing CSS stays the same, just adding these new styles */

/* Stock Warning */
.stock-warning {
    background: #DDCDBF;
    border: 1px solid #808080;
    color: #808080;
    padding: 10px 15px;
    border-radius: 5px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
}

.stock-warning i {
    color: #808080;
}

/* Clear Selection Button */
.btn-clear-selection {
    background: #f5f5f5;
    border: 1px solid #ddd;
    color: #666;
    padding: 8px 20px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s;
    border-radius: 3px;
}

.btn-clear-selection:hover {
    background: #e5e5e5;
    border-color: #999;
    color: #333;
}

.clear-selection-wrapper {
    margin-top: 15px;
}

/* Rest of your existing CSS continues below... */

/* Base Styles */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.product-container {
    display: flex;
    gap: 30px;
    margin-bottom: 50px;
}

.product-gallery {
    flex: 0 0 50%;
    display: flex;
    gap: 15px;
}

.thumbnail-sidebar {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 80px;
}

.thumbnail {
    cursor: pointer;
    border: 2px solid transparent;
    transition: border-color 0.3s;
}

.thumbnail.active,
.thumbnail:hover {
    border-color: #000;
}

.thumbnail img {
    width: 100%;
    height: auto;
}

.main-image-container {
    flex: 1;
    position: relative;
}

.main-image img {
    width: 100%;
    height: auto;
}

.new-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #d9304f;
    color: white;
    padding: 5px 10px;
    font-size: 12px;
    font-weight: bold;
    z-index: 10;
}

.add-to-favorites {
    position: absolute;
    top: 10px;
    right: 10px;
    background: white;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    z-index: 10;
}

.navigation-arrows {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 100%;
    display: flex;
    justify-content: space-between;
    padding: 0 10px;
}

.nav-arrow {
    background: white;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* Product Info Styles */
.product-info {
    flex: 0 0 45%;
}

.brand-name {
    color: #666;
    font-size: 14px;
    margin-bottom: 5px;
}

.tech-spec-title {
    color: #666;
    font-size: 16px;
    margin-bottom: 5px;
}

.product-title {
    font-size: 28px;
    margin-bottom: 15px;
}

.product-short-description {
    color: #666;
    margin-bottom: 15px;
    line-height: 1.6;
}

/* Stock Status Styles */
.stock-status {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 10px;
    font-weight: bold;
}

.stock-status.low-stock {
    color: #ff6b6b;
}

.stock-status i {
    font-size: 8px;
    color: #28a745;
}

.stock-status.low-stock i {
    color: #ff6b6b;
}

/* Price Styles */
.price-container {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.discount-badge {
    background: #d9304f;
    color: white;
    padding: 5px 10px;
    font-size: 14px;
    font-weight: bold;
}

.old-price {
    text-decoration: line-through;
    color: #999;
    font-size: 18px;
}

.current-price {
    font-size: 24px;
    font-weight: bold;
    color: #000;
}

/* Variation Selection Styles */
.attribute-selection,
.color-selection,
.size-selection {
    margin-bottom: 20px;
}

.attribute-title {
    font-weight: bold;
    margin-bottom: 10px;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 1px;
}

.attribute-options {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

/* Size Option Styles */
.size-option {
    padding: 8px 16px;
    border: 1px solid #ddd;
    cursor: pointer;
    transition: all 0.3s;
    min-width: 50px;
    text-align: center;
    background: white;
}

.size-option:hover:not(.disabled-option) {
    border-color: #000;
}

.size-option.active {
    background: #000;
    color: white;
    border-color: #000;
}

.size-option.greyed-out {
    background: #f5f5f5;
    color: #999;
    border-color: #ddd;
    cursor: not-allowed;
    opacity: 0.6;
}

.size-option.disabled-option {
    pointer-events: none;
}

/* Color Option Styles */
.color-option {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    border: 2px solid #ddd;
    cursor: pointer;
    position: relative;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.color-option:hover:not(.disabled-option) {
    transform: scale(1.1);
    border-color: #000;
}

.color-option.active {
    border-color: #000;
    box-shadow: 0 0 0 2px #fff, 0 0 0 4px #000;
}

.color-option .color-swatch {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: block;
}

/* Color name tooltip */
.color-option .color-name {
    position: absolute;
    bottom: -30px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 11px;
    white-space: nowrap;
    opacity: 0;
    transition: opacity 0.3s;
    pointer-events: none;
    z-index: 10;
    text-transform: capitalize;
}

.color-option:hover .color-name {
    opacity: 1;
}

/* Special styling for white and light colors */
.color-option .color-swatch[style*="#FFFFFF"],
.color-option .color-swatch[style*="#FFFFF0"],
.color-option .color-swatch[style*="#FFFDD0"] {
    box-shadow: inset 0 0 0 1px rgba(0,0,0,0.1);
}

/* Special styling for black colors */
.color-option .color-swatch[style*="#0E0E0C"],
.color-option .color-swatch[style*="#000000"] {
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.1);
}

.color-option.line-through::after {
    content: "";
    position: absolute;
    top: 50%;
    left: -2px;
    right: -2px;
    height: 2px;
    background: #ff0000;
    transform: rotate(-45deg);
}

.color-option.disabled-option {
    cursor: not-allowed;
    opacity: 0.6;
}

/* View Only Notice */
.view-only-notice {
background: #DDCDBF!important;
    border: 1px solid #808080!important;
    color: #808080!important;
    padding: 12px 20px!important;
    border-radius: 5px!important;
    margin: 15px 0!important;
    display: flex!important
;
    align-items: center;
    gap: 10px;
}

.view-only-notice i {
    font-size: 18px;
}

/* Action Buttons */
.action-buttons {
    margin: 30px 0;
}

.quantity-container {
    display: flex;
    align-items: center;
    gap: 0;
    margin-bottom: 15px;
    width: fit-content;
}

.quantity-btn {
    width: 40px;
    height: 40px;
    border: 1px solid #ddd;
    background: white;
    cursor: pointer;
    font-size: 18px;
    transition: all 0.3s;
}

.quantity-btn:hover {
    background: #f5f5f5;
}

.quantity-input {
    width: 60px;
    height: 40px;
    border: 1px solid #ddd;
    border-left: none;
    border-right: none;
    text-align: center;
    font-size: 16px;
}

.add-to-cart {
    background: #000;
    color: white;
    padding: 15px 40px;
    border: none;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
    margin-right: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.add-to-cart:hover {
    background: #333;
}

.appointment-btn {
    background: white;
    color: #000;
    padding: 15px 40px;
    border: 2px solid #000;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.appointment-btn:hover {
    background: #000;
    color: white;
}

/* Shipping Features */
.shipping-features {
    display: flex;
    gap: 30px;
    margin: 30px 0;
    padding: 20px 0;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
}

.shipping-feature {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
}

/* Accordion Styles */
.product-details h3,
.returns-container h3 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
    font-size: 16px;
}

.product-details-content,
.returns-content {
    padding: 15px 0;
    line-height: 1.6;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 30px;
    width: 80%;
    max-width: 800px;
    border-radius: 10px;
    position: relative;
}

.modal-content .close {
    position: absolute;
    right: 20px;
    top: 20px;
    font-size: 30px;
    cursor: pointer;
    color: #999;
}

.modal-content img {
    width: 100%;
    height: auto;
    margin-top: 20px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .product-container {
        flex-direction: column;
    }
    
    .product-gallery,
    .product-info {
        flex: 1;
        width: 100%;
    }
    
    .thumbnail-sidebar {
        flex-direction: row;
        width: 100%;
        overflow-x: auto;
    }
    
    .thumbnail {
        min-width: 60px;
    }
    
    .shipping-features {
        flex-direction: column;
        gap: 15px;
    }
    
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .add-to-cart,
    .appointment-btn {
        width: 100%;
    }
    
    .color-option .color-name {
        bottom: -35px;
    }
}
</style>

<?php
endwhile; // end of the loop

get_footer('shop');
?>