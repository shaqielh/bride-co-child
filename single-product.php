<?php
/**
 * Enhanced Single Product Template with Dynamic Variations
 * 
 * @version 1.7.0
 * @package Bride-co-child
 */

if (!defined('ABSPATH')) exit;

get_header('shop');

// Color name to hex mapping
function get_color_hex_code($color_name, $sku = '') {
    if (!empty($sku)) {
        $sku_color_map = array(
            'SO01548' => '#2D2E3E', 'SO01549' => '#16474A', 'SO01550' => '#0E0E0C',
            'SO01553' => '#0E0E0C', 'SO01558' => '#0E0E0C', 'SO01557' => '#0E0E0C',
            'SO01547' => '#0E0E0C', 'SO01551' => '#0E0E0C', 'SO01503' => '#0E0E0C',
            'SO01490' => '#0E0E0C', 'SO01476' => '#0E0E0C', 'SO01443' => '#0E0E0C',
            'SO01360' => '#0E0E0C',
        );
        foreach ($sku_color_map as $prefix => $hex) {
            if (strpos($sku, $prefix) === 0) return $hex;
        }
    }
    
    $color_map = array(
        'apple' => '#99062a', 'ballet' => '#ebb8af', 'beige' => '#D4C3B9',
        'biscotti' => '#CFB6A3', 'black' => '#171513', 'black / silver' => '#000000',
        'black / white' => '#333333', 'blue' => '#3c6c92', 'blush' => '#e0cbc8',
        'bouquet' => '#e6addd', 'brown' => '#916f50', 'burgundy' => '#a81134',
        'charcoal' => '#2e2c2e', 'chianti' => '#a85760', 'chocolate' => '#473b3b',
        'cinnamon' => '#933b2b', 'coral' => '#fc3558', 'cream' => '#FFFDD0',
        'dark brown' => '#544131', 'dusty sage' => '#beb86c', 'ecru' => '#BCAEA2',
        'emerald' => '#2c5957', 'eucalyptus' => '#9b9777', 'forest green' => '#9cafa2',
        'fuchsia' => '#e32d58', 'gem' => '#205052', 'gold' => '#FFD700',
        'gray' => '#91A1B0', 'green' => '#81711e', 'grey' => '#91A1B0',
        'horizon' => '#00539f', 'hot pink' => '#e90d88', 'indigo' => '#4B0082',
        'iris' => '#d8cfe7', 'ivory' => '#ddd3c3', 'juniper' => '#205241',
        'lake' => '#769cb7', 'latte tulip' => '#a27c6e', 'lavender' => '#ab9ac4',
        'light blue' => '#c1c8e2', 'marine' => '#1c2033', 'maroon' => '#800000',
        'martini olive' => '#4c522f', 'midnight' => '#2D2E3E', 'mint' => '#c4e9d7',
        'multi' => '#d8edeb', 'navy' => '#1D3253', 'oatmeal' => '#cdc3a7',
        'orange' => '#FFA500', 'pale yellow' => '#f2db8d', 'pastel pink' => '#f4d4d5',
        'petal' => '#ebb7a6', 'pink' => '#e4cbcf', 'pistachio' => '#d1dbab',
        'plum' => '#491f44', 'punch' => '#f52b56', 'purple' => '#800080',
        'quartz' => '#c69791', 'red' => '#FF0000', 'rose pink' => '#e68597',
        'sage' => '#aaaa8c', 'sand' => '#bf8f79', 'sienna' => '#dd4917',
        'silver' => '#C0C0C0', 'sndbqtflrl' => '#bf8f79', 'sndvineflrl' => '#bf8f79',
        'sow' => '#000000', 'steel blue' => '#6e7a8b', 'tawny' => '#773f1e',
        'teal' => '#008080', 'terracotta' => '#eba17f', 'white' => '#FFFFFF',
        'whtbqtflrl' => '#e7e7e7', 'wine' => '#571A25', 'yellow' => '#FFFF00',
    );
    
    $lower = strtolower(trim($color_name));
    if (isset($color_map[$lower])) return $color_map[$lower];
    if (preg_match('/#([a-fA-F0-9]{3}){1,2}\b/', $color_name)) return $color_name;
    return '#DDDDDD';
}

// Size guide + Threads of Heritage buttons
function render_enhanced_size_guide_button() {
    global $product;
    
    $measuring_guide_url = '/fitting-guide';
    $is_bridal = false;
    
    if ($product) {
        // Check brand for eurosuit
        $terms = get_the_terms($product->get_id(), 'pa_brand');
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                if (stripos($term->name, 'euro suit') !== false || stripos($term->name, 'eurosuit') !== false) {
                    $measuring_guide_url = 'https://stage.brideandco.co.za/euro-suit-fitting-guide/';
                    break;
                }
            }
        }
        
        // Check if bridal product
        $cats = wp_get_post_terms($product->get_id(), 'product_cat');
        if (!empty($cats) && !is_wp_error($cats)) {
            foreach ($cats as $cat) {
                if ($cat->slug === 'bridal') { $is_bridal = true; break; }
                $ancestors = get_ancestors($cat->term_id, 'product_cat');
                foreach ($ancestors as $anc_id) {
                    $anc = get_term($anc_id, 'product_cat');
                    if ($anc && $anc->slug === 'bridal') { $is_bridal = true; break 2; }
                }
            }
        }
    }
    
    $threads_icon = 'https://brideandco.co.za/wp-content/uploads/2026/03/threads-icon.svg';
    $threads_url = home_url('/threads-of-heritage/');
    ?>
    <div class="size-guide">
        <div class="size-guide-links">
            <i class="fa-solid fa-ruler"></i> 
            <button onclick="openSizeGuideModal()" style="margin-right: 10px;">Size Guide</button>
            <a href="<?php echo esc_url($measuring_guide_url); ?>" target="_blank">How to Measure</a>
        </div>
        <?php if ($is_bridal) : ?>
        <a href="<?php echo esc_url($threads_url); ?>" class="threads-heritage-link" target="_blank">
            <img src="<?php echo esc_url($threads_icon); ?>" alt="Threads of Heritage" class="threads-heritage-icon">
            <span>Threads of Heritage</span>
        </a>
        <?php endif; ?>
    </div>
    <?php
}

while (have_posts()) : the_post();
    global $product;
    
    // Product data
    $attachment_ids = $product->get_gallery_image_ids();
    $main_image_id = $product->get_image_id();
    $main_image_url = wp_get_attachment_url($main_image_id);
    $fallback_image = 'https://stage.brideandco.co.za/wp-content/uploads/2022/05/cropped-BrideCo-Logo.png';
    
    $is_new = function_exists('is_product_new') ? is_product_new($product->get_id()) : false;
    $is_eurosuite = function_exists('is_eurosuit_page') ? is_eurosuit_page() : false;
    $newlabel_style = $is_eurosuite ? 'new-badge-euro' : '';
    
    // Brand
    $brand = '';
    $brand_terms = get_the_terms($product->get_id(), 'pa_brand');
    if (!empty($brand_terms) && !is_wp_error($brand_terms)) {
        $brand = $brand_terms[0]->name;
    }
    
    // Price
    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();
    $is_on_sale = $product->is_on_sale();
    $discount_percentage = 0;
    if ($is_on_sale && $regular_price > 0) {
        $discount_percentage = round(100 - (($sale_price / $regular_price) * 100));
    }
    
    // Hire check
    $categories = wp_get_post_terms($product->get_id(), 'product_cat');
    $is_hire = false;
    foreach ($categories as $term) {
        if ($term->name === 'Hire') { $is_hire = true; break; }
    }
    
    // Technical spec
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
    
    // Variation data
    $available_variations = array();
    if ($product->is_type('variable')) {
        foreach ($product->get_available_variations() as $variation) {
            $variation_obj = wc_get_product($variation['variation_id']);
            $variation['stock_quantity'] = $variation_obj->get_stock_quantity();
            $variation['is_in_stock'] = $variation_obj->is_in_stock();
            $available_variations[] = $variation;
        }
    }
    
    $can_purchase = $product->is_in_stock() && $product->is_purchasable();
    $product_sku = $product->get_sku();
?>

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
                    echo '<div class="thumbnail active"><img src="' . esc_url($main_image_url) . '" alt="' . esc_attr(get_the_title()) . '"></div>';
                }
                foreach ($attachment_ids as $attachment_id) {
                    $image_url = wp_get_attachment_url($attachment_id);
                    if ($image_url) {
                        echo '<div class="thumbnail"><img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '"></div>';
                    }
                }
                if (!$main_image_url && empty($attachment_ids)) {
                    echo '<div class="thumbnail active"><img src="' . esc_url($fallback_image) . '" alt="' . esc_attr(get_the_title()) . '"></div>';
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
                    <img src="<?php echo esc_url($main_image_url ?: $fallback_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                </div>
                
                <div class="navigation-arrows">
                    <div class="nav-arrow prev"><i class="fa-solid fa-chevron-left"></i></div>
                    <div class="nav-arrow next"><i class="fa-solid fa-chevron-right"></i></div>
                </div>

                <div class="social-share-wrapper">
                    <div class="share-title">SHARE THIS PRODUCT</div>
                    <div class="social-share-buttons">
                        <button class="share-btn facebook" onclick="shareOnFacebook()" title="Share on Facebook"><i class="fab fa-facebook-f"></i></button>
                        <button class="share-btn email" onclick="shareViaEmail()" title="Share via Email"><i class="fas fa-envelope"></i></button>
                        <button class="share-btn copy-link" onclick="copyProductLink()" title="Copy Link"><i class="fas fa-link"></i></button>
                        <button class="share-btn whatsapp" onclick="shareOnWhatsApp()" title="Share on WhatsApp"><i class="fab fa-whatsapp"></i></button>
                    </div>
                    <div class="copy-success-message" id="copySuccessMessage">Link copied to clipboard!</div>
                </div>
            </div>
        </div>
        
        <!-- Product Info -->
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
            
            <div class="stock-status">
                <i class="fa-solid fa-circle"></i> 
                <?php echo $product->is_in_stock() ? 'In stock' : 'Out of stock'; ?>
            </div>
            
            <?php if ($product->get_sku()) : ?>
            <div class="product-code">Product Code: <?php echo esc_html($product->get_sku()); ?></div>
            <?php endif; ?>
            
            <!-- Price -->
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
            
            <!-- Variations -->
            <?php
            if ($product->is_type('variable')) :
                $attributes = $product->get_variation_attributes();
                
                // Reorder: colour first, then size, then others
                $ordered = array();
                $color_key = $size_key = null;
                $other_keys = array();
                
                foreach ($attributes as $name => $opts) {
                    $lower = strtolower($name);
                    if (strpos($lower, 'colour') !== false || strpos($lower, 'color') !== false) $color_key = $name;
                    elseif (strpos($lower, 'size') !== false) $size_key = $name;
                    else $other_keys[] = $name;
                }
                
                if ($color_key !== null) $ordered[$color_key] = $attributes[$color_key];
                if ($size_key !== null) $ordered[$size_key] = $attributes[$size_key];
                foreach ($other_keys as $k) $ordered[$k] = $attributes[$k];
                $attributes = $ordered;
                
                $color_mappings = array();
                
                foreach ($attributes as $attribute_name => $options) :
                    $attribute_label = wc_attribute_label($attribute_name);
                    $attribute_id = sanitize_title($attribute_name);
                    $selected_value = isset($_REQUEST['attribute_' . $attribute_id]) ? 
                                     wc_clean(wp_unslash($_REQUEST['attribute_' . $attribute_id])) : 
                                     $product->get_variation_default_attribute($attribute_name);
                    $full_attribute_name = 'attribute_' . $attribute_id;
                    
                    $is_color = (
                        stripos($attribute_label, 'color') !== false || 
                        stripos($attribute_label, 'colour') !== false ||
                        stripos($attribute_name, 'color') !== false ||
                        stripos($attribute_name, 'colour') !== false ||
                        in_array($attribute_name, array('pa_colour', 'pa_color')) ||
                        in_array($attribute_id, array('pa_colour', 'pa_color'))
                    );
                    
                    $is_size = stripos($attribute_label, 'size') !== false || stripos($attribute_name, 'size') !== false;
                    
                    if ($is_color) {
                        $attr_class = 'color-selection';
                        $option_class = 'color-option';
                        foreach ($options as $option) {
                            $color_mappings[$option] = get_color_hex_code($option, $product_sku);
                        }
                    } elseif ($is_size) {
                        $attr_class = 'size-selection';
                        $option_class = 'size-option';
                    } else {
                        $attr_class = 'attribute-selection';
                        $option_class = 'attribute-option';
                    }
            ?>
            <div class="<?php echo esc_attr($attr_class); ?>" 
                 data-attribute="<?php echo esc_attr($full_attribute_name); ?>"
                 data-attribute-type="<?php echo $is_color ? 'color' : ($is_size ? 'size' : 'other'); ?>">
                <div class="attribute-title"><?php echo esc_html($attribute_label); ?></div>
                <div class="attribute-options">
                    <?php 
                    if ($is_size) sort($options, SORT_NUMERIC);
                    foreach ($options as $option) :
                        $is_selected = ($selected_value === $option);
                    ?>
                    <div class="<?php echo esc_attr($option_class . ($is_selected ? ' active' : '')); ?>" 
                         data-value="<?php echo esc_attr($option); ?>"
                         data-attribute="<?php echo esc_attr($full_attribute_name); ?>"
                         data-attribute-type="<?php echo $is_color ? 'color' : ($is_size ? 'size' : 'other'); ?>"
                         title="<?php echo esc_attr($option); ?>">
                        <?php if ($is_color) : ?>
                            <span class="color-swatch" style="background-color: <?php echo esc_attr(get_color_hex_code($option, $product_sku)); ?>"></span>
                            <span class="color-name"><?php echo esc_html($option); ?></span>
                        <?php else : ?>
                            <?php echo esc_html($option); ?>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
            
            <div class="clear-selection-wrapper">
                <button type="button" class="btn-clear-selection" id="clearSelectionBtn" style="display: none;">
                    <i class="fa-solid fa-undo"></i> Clear Selection
                </button>
            </div>
            <?php endif; ?>
            
            <div class="shipping-info">Orders placed now will be shipped within 3-7 business days.</div>
            
            <?php if (has_term('Hire', 'product_cat')) : ?>
            <div class="shipping-info">Hire products only available in-store.</div>
            <?php endif; ?>
            
            <?php render_enhanced_size_guide_button(); ?>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <div class="quantity-container" <?php echo !$can_purchase ? 'style="display:none;"' : ''; ?>>
                    <button class="quantity-btn minus">-</button>
                    <input type="text" class="quantity-input" value="1" name="quantity">
                    <button class="quantity-btn plus">+</button>
                </div>
                
                <?php if (!$is_hire) : ?>
                    <?php if ($product->is_type('variable')) : ?>
                        <button class="add-to-cart" id="custom-add-to-cart-btn" <?php echo !$can_purchase ? 'style="display:none;"' : ''; ?>>ADD TO CART</button>
                    <?php elseif ($can_purchase) : ?>
                        <button class="add-to-cart" onclick="addToCartSimpleProduct(<?php echo esc_attr($product->get_id()); ?>, jQuery('.quantity-input').val())">ADD TO CART</button>
                    <?php endif; ?>
                    
                    <?php if (!$can_purchase) : ?>
                    <div class="view-only-notice">
                        <i class="fa-solid fa-info-circle"></i> This product is currently out of stock.
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
            
            <!-- Accordion -->
            <div class="product-details">
                <h3>Product Description <i class="fa-solid fa-minus"></i></h3>
                <div class="product-details-content"><?php the_content(); ?></div>
            </div>
            
            <div class="returns-container">
                <h3>Returns and Exchanges <i class="fa-solid fa-plus"></i></h3>
            </div>
        </div>
    </div>
    
    <!-- Recently Viewed / Related -->
    <div class="related-products">
        <h2>Recently Viewed</h2>
        <section class="container my-5">
            <div class="row g-4">
                <?php
                $viewed_products = !empty($_COOKIE['woocommerce_recently_viewed']) ? (array) explode('|', $_COOKIE['woocommerce_recently_viewed']) : array();
                $viewed_products = array_diff($viewed_products, array($product->get_id()));
                $viewed_products = array_reverse(array_slice($viewed_products, -4));

                if (!empty($viewed_products)) {
                    $args = array(
                        'post_type' => 'product', 'post_status' => 'publish',
                        'post__in' => $viewed_products, 'orderby' => 'post__in', 'posts_per_page' => 4,
                    );
                } else {
                    $product_cats = wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'ids'));
                    $args = array(
                        'post_type' => 'product', 'post_status' => 'publish',
                        'post__not_in' => array($product->get_id()), 'posts_per_page' => 4, 'orderby' => 'rand',
                        'tax_query' => array(array('taxonomy' => 'product_cat', 'field' => 'id', 'terms' => $product_cats, 'operator' => 'IN')),
                    );
                }

                $related_query = new WP_Query($args);
                
                if (!$related_query->have_posts()) {
                    $args = array(
                        'post_type' => 'product', 'post_status' => 'publish',
                        'post__not_in' => array($product->get_id()), 'posts_per_page' => 4, 'orderby' => 'rand',
                    );
                    $related_query = new WP_Query($args);
                }
                
                if ($related_query->have_posts()) :
                    while ($related_query->have_posts()) : $related_query->the_post();
                        $rel = wc_get_product(get_the_ID());
                        if (!is_a($rel, 'WC_Product')) continue;
                        
                        $rel_id = $rel->get_id();
                        $rel_regular = $rel->get_regular_price();
                        $rel_sale = $rel->get_sale_price();
                        $rel_on_sale = $rel->is_on_sale();
                        $rel_discount = ($rel_on_sale && $rel_regular > 0) ? round((($rel_regular - $rel_sale) / $rel_regular) * 100) : 0;
                        
                        $rel_gallery = $rel->get_gallery_image_ids();
                        $rel_hover = !empty($rel_gallery) ? wp_get_attachment_image_url($rel_gallery[0], 'large') : '';
                        $rel_in_stock = $rel->is_in_stock();
                        $rel_is_new = function_exists('is_product_new') ? is_product_new($rel_id) : false;
                        $rel_title = get_the_title();
                        
                        $rel_brand = '';
                        $rel_brand_terms = get_the_terms($rel_id, 'pa_brand');
                        if (!empty($rel_brand_terms) && !is_wp_error($rel_brand_terms)) $rel_brand = $rel_brand_terms[0]->name;
                        
                        $rel_dress = '';
                        $rel_tech = get_the_terms($rel_id, 'pa_technical-spec');
                        if (!empty($rel_tech) && !is_wp_error($rel_tech)) $rel_dress = $rel_tech[0]->name;
                        if (empty($rel_dress)) {
                            $meta = get_post_meta($rel_id, 'meta_dress_name', true);
                            if (!empty($meta) && $meta !== '{{product.meta_dress name}}') $rel_dress = $meta;
                        }
                ?>
                <div class="col-md-3">
                    <a href="<?php echo esc_url(get_permalink()); ?>" class="product-card-link">
                        <div class="product-card">
                            <div class="image-container">
                                <?php if ($rel_is_new) : ?>
                                <div class="label new-label <?php echo $newlabel_style; ?>">NEW</div>
                                <?php endif; ?>
                                <?php if (!$rel_in_stock) : ?>
                                <span class="label sold-out-label" style="right: 10px">Sold Out</span>
                                <?php endif; ?>
                                
                                <?php if (has_post_thumbnail()) : ?>
                                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>" class="product-image" alt="<?php echo esc_attr($rel_title); ?>" />
                                <?php else : ?>
                                    <img src="<?php echo wc_placeholder_img_src(); ?>" class="product-image" alt="<?php echo esc_attr($rel_title); ?>" />
                                <?php endif; ?>
                                
                                <?php if ($rel_hover) : ?>
                                    <img src="<?php echo esc_url($rel_hover); ?>" class="hover-image" alt="<?php echo esc_attr($rel_title); ?> Hover" />
                                <?php elseif (has_post_thumbnail()) : ?>
                                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>" class="hover-image" alt="<?php echo esc_attr($rel_title); ?> Hover" />
                                <?php endif; ?>
                                
                                <span class="add-to-cart-btn">SHOP NOW</span>
                            </div>
                            
                            <?php if (!empty($rel_dress)) : ?>
                                <h4 class="mt-3 tech-spec-title"><?php echo esc_html($rel_dress); ?></h4>
                                <h5 class="product-title-with-spec"><?php echo esc_html($rel_title); ?></h5>
                            <?php else : ?>
                                <h5 class="mt-3 fw-bold"><?php echo esc_html($rel_title); ?></h5>
                            <?php endif; ?>
                            
                            <p class="product-description"><?php echo wp_trim_words($rel->get_short_description(), 10, '...'); ?></p>
                            
                            <?php if ($rel_on_sale && $rel_discount > 0) : ?>
                                <span class="discount-box"><?php echo esc_html($rel_discount); ?>%</span>
                            <?php endif; ?>
                            
                            <?php if (!empty($rel_brand)) : ?>
                                <p class="product-brand"><?php echo esc_html($rel_brand); ?></p>
                            <?php endif; ?>
                            
                            <p class="product-pricing"><?php echo $rel->get_price_html(); ?></p>
                        </div>
                    </a>
                </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </section>
    </div>
    
    <hr>
    <?php bride_co_render_accessories_child_categories(); ?>
</div>

<!-- Size Guide Modal -->
<?php
$sg_cats = wp_get_post_terms($product->get_id(), 'product_cat');
$sg_image_url = '';
if (!empty($sg_cats) && !is_wp_error($sg_cats)) {
    $sg_image = get_field('size_guide', 'product_cat_' . $sg_cats[0]->term_id);
    if ($sg_image) $sg_image_url = $sg_image['url'];
}
?>
<div id="sizeGuideModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeSizeGuideModal()">&times;</span>
        <h2>Size Guide</h2>
        <?php if ($sg_image_url) : ?>
            <img src="<?php echo esc_url($sg_image_url); ?>" alt="Size Guide">
        <?php else : ?>
            <p>No size guide available for this product category.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Instagram Modal -->
<div id="instagramModal" class="instagram-modal">
    <div class="instagram-modal-content">
        <span class="instagram-close" onclick="closeInstagramModal()">&times;</span>
        <h3>Share on Instagram</h3>
        <p>Copy the link below and share it in your Instagram story or post:</p>
        <div class="instagram-link-display">
            <input type="text" id="instagramLinkInput" value="<?php echo esc_url(get_permalink()); ?>" readonly>
        </div>
        <button class="instagram-copy-btn" onclick="copyForInstagram()"><i class="fas fa-copy"></i> Copy Link</button>
    </div>
</div>

<style>
/* === BASE LAYOUT === */
.container { max-width: 1200px; margin: 0 auto; padding: 20px; }
.product-container { display: flex; gap: 30px; margin-bottom: 50px; }
.product-gallery { flex: 0 0 50%; display: inline-block; vertical-align: top; }
.product-info { flex: 1; }

/* === GALLERY === */
.thumbnail-sidebar { display: flex; flex-direction: column; gap: 10px; width: 80px; float: left; }
.thumbnail { cursor: pointer; border: 2px solid transparent; transition: border-color 0.3s; }
.thumbnail.active, .thumbnail:hover { border-color: #000; }
.thumbnail img { width: 100%; height: auto; }
.main-image-container { flex: 1; position: relative; overflow: hidden; margin-left: 95px; }
.main-image img { width: 100%; height: auto; }

.new-badge { display: block; position: absolute; z-index: 1; background: #ddcdbf; padding: 8px 15px; font-weight: 600; }
.add-to-favorites { position: absolute; top: 10px; left: 10px; background: white; border: none; border-radius: 50%; width: 40px; height: 48px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.1); z-index: 10; }
.navigation-arrows { position: absolute; top: 50%; transform: translateY(-50%); width: 100%; display: none; justify-content: space-between; padding: 0 10px; }
.nav-arrow { background: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }

/* === PRODUCT INFO === */
.brand-name { color: #666; font-size: 14px; margin-bottom: 5px; }
.tech-spec-title { color: #666; font-size: 16px; margin-bottom: 5px; }
.product-title { font-size: 28px; margin-bottom: 15px; }
.product-short-description { color: #666; margin-bottom: 15px; line-height: 1.6; }
.product-code { color: #999; font-size: 13px; margin-bottom: 10px; }

.stock-status { display: flex; align-items: center; gap: 5px; margin-bottom: 10px; font-weight: bold; }
.stock-status i { font-size: 8px; color: #28a745; }

/* === PRICE === */
.price-container { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
.discount-badge { background: #d9304f; color: white; padding: 5px 10px; font-size: 14px; font-weight: bold; }
.old-price { text-decoration: line-through; color: #999; font-size: 18px; }
.current-price { font-size: 24px; font-weight: bold; color: #000 !important; }
mark, ins { color: #c1272d !important; }

/* === VARIATIONS === */
.attribute-selection, .color-selection, .size-selection { margin-bottom: 20px; }
.attribute-title { font-weight: bold; margin-bottom: 10px; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; }
.attribute-options { display: flex; flex-wrap: wrap; gap: 10px; }

.size-option { padding: 8px 16px; border: 1px solid #ddd; cursor: pointer; transition: all 0.3s; min-width: 50px; text-align: center; background: white; }
.size-option:hover:not(.disabled-option) { border-color: #000; }
.size-option.active { background: #000; color: white; border-color: #000; }
.size-option.greyed-out { background: #f5f5f5; color: #999; border-color: #ddd; cursor: not-allowed; opacity: 0.6; }
.size-option.disabled-option { pointer-events: none; }

.color-option { width: 45px; height: 45px; border-radius: 50%; border: 2px solid #ddd; cursor: pointer; position: relative; transition: all 0.3s; display: flex; align-items: center; justify-content: center; overflow: hidden; }
.color-option:hover:not(.disabled-option) { transform: scale(1.1); border-color: #000; }
.color-option.active { border-color: #000; box-shadow: 0 0 0 2px #fff, 0 0 0 4px #000; }
.color-option .color-swatch { width: 35px; height: 35px; border-radius: 50%; display: block; }
.color-option .color-name { position: absolute; bottom: -30px; left: 50%; transform: translateX(-50%); background: rgba(0,0,0,0.8); color: white; padding: 3px 8px; border-radius: 3px; font-size: 11px; white-space: nowrap; opacity: 0; transition: opacity 0.3s; pointer-events: none; z-index: 10; text-transform: capitalize; }
.color-option:hover .color-name { opacity: 1; }
.color-option .color-swatch[style*="#FFFFFF"], .color-option .color-swatch[style*="#FFFDD0"], .color-option .color-swatch[style*="#e7e7e7"], .color-option .color-swatch[style*="#ddd3c3"] { box-shadow: inset 0 0 0 1px rgba(0,0,0,0.1); }
.color-option .color-swatch[style*="#0E0E0C"], .color-option .color-swatch[style*="#000000"], .color-option .color-swatch[style*="#171513"] { box-shadow: inset 0 0 0 1px rgba(255,255,255,0.1); }
.color-option.line-through::after { content: ""; position: absolute; top: 50%; left: -2px; right: -2px; height: 2px; background: #ff0000; transform: rotate(-45deg); }
.color-option.disabled-option { cursor: not-allowed; opacity: 0.6; }

.btn-clear-selection { background: white; color: #000; padding: 15px 40px; border: 2px solid #000; font-family: inherit; cursor: pointer; }
.btn-clear-selection:hover { background: #000; color: #fff; }

/* === SIZE GUIDE & THREADS OF HERITAGE === */
.size-guide {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin: 15px 0;
    padding: 12px 0;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
}

.size-guide-links {
    display: flex;
    align-items: center;
    gap: 5px;
}

.size-guide-links button,
.size-guide-links a {
    background: none;
    border: none;
    color: #333;
    font-size: 14px;
    cursor: pointer;
    text-decoration: underline;
    font-family: inherit;
}

.size-guide-links button:hover,
.size-guide-links a:hover {
    color: #000;
}

.threads-heritage-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    color: #333;
    font-size: 13px;
    font-weight: 500;
    padding: 6px 12px;
    border: 1px solid #d9c4b3;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.threads-heritage-link:hover {
    background-color: #faf6f1;
    border-color: #b19176;
    color: #000;
}

.threads-heritage-icon {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
}

/* === ACTION BUTTONS === */
.action-buttons { margin: 30px 0; }
.quantity-container { display: flex; align-items: center; gap: 0; margin-bottom: 15px; width: fit-content; }
.quantity-btn { width: 40px; height: 40px; border: 1px solid #ddd; background: white; cursor: pointer; font-size: 18px; transition: all 0.3s; }
.quantity-btn:hover { background: #f5f5f5; }
.quantity-input { width: 60px; height: 40px; border: 1px solid #ddd; border-left: none; border-right: none; text-align: center; font-size: 16px; }

.add-to-cart { background: #000; color: white; padding: 15px 40px; border: none; font-weight: bold; cursor: pointer; transition: all 0.3s; margin-right: 10px; text-transform: uppercase; letter-spacing: 1px; }
.add-to-cart:hover { background: #333; }
.appointment-btn { background: white; color: #000; padding: 15px 40px; border: 2px solid #000; font-weight: bold; cursor: pointer; transition: all 0.3s; text-transform: uppercase; letter-spacing: 1px; }
.appointment-btn:hover { background: #000; color: white; }

.view-only-notice { background: #fff3cd; border: 1px solid #ffc107; color: #856404; padding: 12px 20px; border-radius: 5px; margin: 15px 0; display: flex; align-items: center; gap: 10px; }

/* === SHIPPING FEATURES === */
.shipping-features { display: flex; gap: 30px; margin: 30px 0; padding: 20px 0; border-top: 1px solid #eee; border-bottom: 1px solid #eee; }
.shipping-feature { display: flex; align-items: center; gap: 10px; font-size: 14px; }
.shipping-info { color: #666; font-size: 13px; margin-bottom: 8px; }

/* === ACCORDION === */
.product-details h3, .returns-container h3 { display: flex; justify-content: space-between; align-items: center; cursor: pointer; padding: 15px 0; border-bottom: 1px solid #eee; font-size: 16px; }
.product-details-content, .returns-content { padding: 15px 0; line-height: 1.6; }

/* === RELATED PRODUCTS === */
.related-products .image-container { height: 360px !important; }

/* === SOCIAL SHARE === */
.social-share-wrapper { margin-top: 20px; padding-top: 35px; text-align: center; width: 100%; clear: both; }
.share-title { font-size: 13px; font-weight: 600; color: #b19176; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px; }
.social-share-buttons { display: flex; gap: 12px; justify-content: center; align-items: center; flex-wrap: wrap; }
.share-btn { width: 40px; height: 40px; border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; color: white; font-size: 18px; position: relative; overflow: hidden; background: #DDCDBF; }
.share-btn:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); background: #b19176; }
.share-btn.copy-link.copied { background: #28a745; }
.share-btn i { font-size: 18px; line-height: 1; align-self: baseline; }
.share-btn::before { content: attr(title); position: absolute; bottom: 120%; left: 50%; transform: translateX(-50%); background: #333; color: white; padding: 5px 10px; border-radius: 5px; font-size: 12px; white-space: nowrap; opacity: 0; pointer-events: none; transition: opacity 0.3s ease; z-index: 1000; }
.share-btn:hover::before { opacity: 1; }

.copy-success-message { display: none; position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); background: #28a745; color: white; padding: 12px 24px; border-radius: 25px; box-shadow: 0 4px 12px rgba(40,167,69,0.3); z-index: 10000; font-size: 14px; font-weight: 500; animation: slideUp 0.3s ease; }

/* === INSTAGRAM MODAL === */
.instagram-modal { display: none; position: fixed; z-index: 10001; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); }
.instagram-modal-content { background: #fefefe; margin: 10% auto; padding: 30px; border-radius: 15px; width: 90%; max-width: 500px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
.instagram-close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
.instagram-close:hover { color: #000; }
.instagram-copy-btn { background: #b19176; color: white; border: none; padding: 12px 24px; border-radius: 25px; cursor: pointer; font-size: 14px; font-weight: 600; margin: 20px 0 15px; width: 100%; }
.instagram-copy-btn.copied { background: #28a745; }
.instagram-link-display { background: #f8f9fa; padding: 10px; border-radius: 8px; margin-top: 10px; }
.instagram-link-display input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px; font-size: 13px; color: #666; background: white; }

/* === SIZE GUIDE MODAL === */
.modal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
.modal-content { background: white; margin: 5% auto; padding: 30px; width: 80%; max-width: 800px; border-radius: 10px; position: relative; }
.modal-content .close { position: absolute; right: 20px; top: 20px; font-size: 30px; cursor: pointer; color: #999; }
.modal-content img { width: 100%; height: auto; margin-top: 20px; }

/* === ANIMATIONS === */
@keyframes slideUp { from { bottom: -50px; opacity: 0; } to { bottom: 20px; opacity: 1; } }
@keyframes successPulse { 0% { transform: scale(1); } 50% { transform: scale(1.1); } 100% { transform: scale(1); } }

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .product-container { flex-direction: column; }
    .product-gallery, .product-info { flex: 1; width: 100%; }
    .thumbnail-sidebar { flex-direction: row; width: 100%; overflow-x: auto; float: none; }
    .thumbnail { min-width: 60px; }
    .main-image-container { margin-left: 0; }
    .shipping-features { flex-direction: column; gap: 15px; }
    .action-buttons { display: flex; flex-direction: column; gap: 10px; }
    .add-to-cart, .appointment-btn { width: 100%; }
    .color-option .color-name { bottom: -35px; }
    .social-share-wrapper { margin-top: 15px; padding-top: 12px; }
    .share-btn { width: 38px; height: 38px; font-size: 16px; }
    .size-guide { flex-direction: column; align-items: flex-start; }
    .threads-heritage-link { width: 100%; justify-content: center; }
}

@media (max-width: 480px) {
    .product-container { flex-direction: column; }
    .product-gallery, .product-info { width: 100%; }
}
</style>

<script type="text/javascript">
var productVariations = <?php echo json_encode($available_variations); ?>;
var colorMappings = <?php echo json_encode(isset($color_mappings) ? $color_mappings : array()); ?>;

// Share functions
const productTitle = "<?php echo esc_js(get_the_title()); ?>";
const productUrl = "<?php echo esc_url(get_permalink()); ?>";
const productImage = "<?php echo esc_url($main_image_url ?: $fallback_image); ?>";
const productPrice = "<?php echo esc_js(strip_tags($product->get_price_html())); ?>";
const productDescription = "<?php echo esc_js(wp_trim_words(strip_tags($product->get_short_description()), 20, '...')); ?>";
const productId = '<?php echo esc_js($product->get_id()); ?>';

function trackShare(method) {
    if (typeof gtag !== 'undefined') gtag('event', 'share', { method: method, content_type: 'product', content_id: productId });
}

function shareOnFacebook() {
    window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(productUrl), 'facebook-share', 'width=600,height=400');
    trackShare('facebook');
}

function shareViaEmail() {
    var subject = 'Check out this product: ' + productTitle;
    var body = 'I thought you might like this:\n\n' + productTitle + '\n' + productPrice + '\n\n' + productDescription + '\n\nView: ' + productUrl;
    window.location.href = 'mailto:?subject=' + encodeURIComponent(subject) + '&body=' + encodeURIComponent(body);
    trackShare('email');
}

function shareOnWhatsApp() {
    window.open('https://wa.me/?text=' + encodeURIComponent('Check out: ' + productTitle + ' - ' + productPrice + '\n' + productUrl), '_blank');
    trackShare('whatsapp');
}

function copyProductLink() {
    navigator.clipboard.writeText(productUrl).then(showCopySuccess).catch(function() {
        var t = document.createElement('input'); t.value = productUrl; document.body.appendChild(t); t.select();
        try { document.execCommand('copy'); showCopySuccess(); } catch(e) { alert('Copy failed: ' + productUrl); }
        document.body.removeChild(t);
    });
    trackShare('copy_link');
}

function showCopySuccess() {
    var msg = document.getElementById('copySuccessMessage'); msg.style.display = 'block';
    var btn = document.querySelector('.copy-link'); btn.innerHTML = '<i class="fas fa-check"></i>'; btn.classList.add('copied');
    setTimeout(function() { msg.style.display = 'none'; btn.innerHTML = '<i class="fas fa-link"></i>'; btn.classList.remove('copied'); }, 2000);
}

function closeInstagramModal() { document.getElementById('instagramModal').style.display = 'none'; }
function copyForInstagram() {
    var input = document.getElementById('instagramLinkInput'); input.select();
    navigator.clipboard.writeText(productUrl).then(function() {
        var btn = document.querySelector('.instagram-copy-btn'); btn.innerHTML = '<i class="fas fa-check"></i> Copied!'; btn.classList.add('copied');
        setTimeout(function() { btn.innerHTML = '<i class="fas fa-copy"></i> Copy Link'; btn.classList.remove('copied'); }, 2000);
    });
}

function openSizeGuideModal() { document.getElementById('sizeGuideModal').style.display = 'block'; }
function closeSizeGuideModal() { document.getElementById('sizeGuideModal').style.display = 'none'; }

window.addEventListener('click', function(e) {
    if (e.target === document.getElementById('sizeGuideModal')) closeSizeGuideModal();
    if (e.target === document.getElementById('instagramModal')) closeInstagramModal();
});
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') { closeSizeGuideModal(); closeInstagramModal(); } });

// Main product JS
jQuery(document).ready(function($) {
    var selectedAttributes = {};
    
    function initDynamicVariations() {
        var map = {};
        if (typeof productVariations === 'undefined' || !productVariations.length) return map;
        
        productVariations.forEach(function(v) {
            Object.keys(v.attributes).forEach(function(attrKey) {
                if (!map[attrKey]) map[attrKey] = {};
                var val = v.attributes[attrKey];
                if (!map[attrKey][val]) map[attrKey][val] = { variations: [], relatedAttributes: {} };
                map[attrKey][val].variations.push(v.variation_id);
                
                Object.keys(v.attributes).forEach(function(other) {
                    if (other === attrKey) return;
                    if (!map[attrKey][val].relatedAttributes[other]) map[attrKey][val].relatedAttributes[other] = {};
                    var otherVal = v.attributes[other];
                    if (!otherVal) return;
                    if (!map[attrKey][val].relatedAttributes[other][otherVal]) {
                        map[attrKey][val].relatedAttributes[other][otherVal] = { variationIds: [], available: false };
                    }
                    map[attrKey][val].relatedAttributes[other][otherVal].variationIds.push(v.variation_id);
                    if (v.is_in_stock) map[attrKey][val].relatedAttributes[other][otherVal].available = true;
                });
            });
        });
        return map;
    }
    
    var variationMap = initDynamicVariations();
    
    function updateAvailableOptions(selectedAttr, selectedValue) {
        if (!variationMap[selectedAttr] || !variationMap[selectedAttr][selectedValue]) return;
        var available = variationMap[selectedAttr][selectedValue].relatedAttributes;
        
        $('.attribute-option, .color-option, .size-option').each(function() {
            var $o = $(this), attr = $o.data('attribute'), val = $o.data('value'), type = $o.data('attribute-type');
            if (attr === selectedAttr) return;
            
            var isAvail = available[attr] && available[attr][val] && available[attr][val].available;
            if (!isAvail) {
                $o.addClass('disabled-option unavailable').attr('title', 'Not available');
                if (type === 'color') $o.addClass('line-through');
                else if (type === 'size') $o.addClass('greyed-out');
            } else {
                $o.removeClass('disabled-option unavailable greyed-out line-through').attr('title', val);
            }
        });
    }
    
    $('#clearSelectionBtn').on('click', function() {
        $('.attribute-option, .color-option, .size-option').removeClass('active disabled-option unavailable greyed-out line-through');
        selectedAttributes = {};
        $('select[name^="attribute_"]').val('').trigger('change');
        $(this).fadeOut();
    });
    
    $('.color-option, .size-option, .attribute-option').on('click', function(e) {
        e.preventDefault();
        var $t = $(this);
        if ($t.hasClass('disabled-option')) return false;
        
        $t.siblings().removeClass('active');
        $t.addClass('active');
        $('#clearSelectionBtn').fadeIn();
        
        var value = $t.data('value'), attr = $t.data('attribute');
        selectedAttributes[attr] = value;
        
        var sel = $('select[name="' + attr + '"]');
        if (sel.length) sel.val(value).trigger('change');
        updateAvailableOptions(attr, value);
    });
    
    // Init pre-selected
    $('.attribute-option.active, .color-option.active, .size-option.active').each(function() {
        selectedAttributes[$(this).data('attribute')] = $(this).data('value');
    });
    if (Object.keys(selectedAttributes).length > 0) {
        Object.keys(selectedAttributes).forEach(function(a) { updateAvailableOptions(a, selectedAttributes[a]); });
    }
    
    // Add to cart
    $('#custom-add-to-cart-btn').on('click', function(e) {
        e.preventDefault();
        var allSelected = true, missing = [];
        $('form.variations_form').find('select[name^="attribute_"]').each(function() {
            if ($(this).val() === '') {
                allSelected = false;
                missing.push($(this).attr('name').replace('attribute_pa_', '').replace('attribute_', '').replace(/-/g, ' '));
            }
        });
        if (!allSelected) { alert('Please select: ' + missing.join(', ')); return; }
        
        var qty = $('.quantity-input').val();
        $('form.cart .quantity input').val(qty);
        $('form.cart .single_add_to_cart_button').click();
    });
    
    // Quantity
    $('.quantity-btn.minus').on('click', function() { var i = $('.quantity-input'), v = parseInt(i.val()); if (v > 1) { i.val(v-1); $('form.cart .quantity input').val(v-1); } });
    $('.quantity-btn.plus').on('click', function() { var i = $('.quantity-input'), v = parseInt(i.val()); i.val(v+1); $('form.cart .quantity input').val(v+1); });
    
    // Gallery
    $('.thumbnail').on('click', function() { $('.thumbnail').removeClass('active'); $(this).addClass('active'); $('.main-image img').attr('src', $(this).find('img').attr('src')); });
    $('.nav-arrow.prev').on('click', function() { var a = $('.thumbnail.active'), p = a.prev('.thumbnail'); if (p.length) { a.removeClass('active'); p.addClass('active'); $('.main-image img').attr('src', p.find('img').attr('src')); } });
    $('.nav-arrow.next').on('click', function() { var a = $('.thumbnail.active'), n = a.next('.thumbnail'); if (n.length) { a.removeClass('active'); n.addClass('active'); $('.main-image img').attr('src', n.find('img').attr('src')); } });
    
    // Accordions
    $('.product-details h3').on('click', function() {
        var c = $(this).next('.product-details-content'), i = $(this).find('i');
        c.is(':visible') ? (c.slideUp(), i.removeClass('fa-minus').addClass('fa-plus')) : (c.slideDown(), i.removeClass('fa-plus').addClass('fa-minus'));
    });
    
    $('.returns-container h3').on('click', function() {
        var c = $(this).next('.returns-content'), i = $(this).find('i');
        if (!c.length) {
            c = $('<div class="returns-content">').html('<p>When purchasing from Complete Bride & Co, products in the discount category that don\'t involve alterations like hemming and resizing, can be exchanged. For this, you need to make an appointment 3-7 days in advance.</p><p>Returns and exchanges are not available for altered items.</p>');
            $(this).after(c);
            i.removeClass('fa-plus').addClass('fa-minus');
        } else {
            c.toggle();
            i.toggleClass('fa-plus fa-minus');
        }
    });
    
    // Favorites
    $('.add-to-favorites').on('click', function() {
        var i = $(this).find('i');
        i.toggleClass('fa-regular fa-solid');
        i.css('color', i.hasClass('fa-solid') ? '#d9304f' : '#333');
    });
});

function addToCartSimpleProduct(pid, qty) {
    jQuery.ajax({
        type: 'POST', url: wc_add_to_cart_params.ajax_url,
        data: { action: 'woocommerce_add_to_cart', product_id: pid, quantity: qty },
        success: function(r) { r.error ? alert(r.error_message) : (alert('Product added to cart!'), jQuery(document.body).trigger('wc_fragment_refresh')); }
    });
}
</script>

<?php
endwhile;
get_footer('shop');
?>