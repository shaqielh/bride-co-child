<?php
/**
 * Single Product Template
 * Clean version - stock rules handled by plugin
 * 
 * @package Bride-co-child
 * @version 2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header('shop');

// Function to convert color names to hex codes
function get_color_hex_code($color_name, $sku = '') {
    // SKU-based color mapping
    if (!empty($sku)) {
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
        
        foreach ($sku_color_map as $sku_prefix => $hex_code) {
            if (strpos($sku, $sku_prefix) === 0) {
                return $hex_code;
            }
        }
    }
    
    // Color name mapping
    $color_map = array(
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
    
    $color_name_lower = strtolower(trim($color_name));
    
    if (isset($color_map[$color_name_lower])) {
        return $color_map[$color_name_lower];
    }
    
    if (preg_match('/#([a-fA-F0-9]{3}){1,2}\b/', $color_name)) {
        return $color_name;
    }
    
    return '#DDDDDD'; // Default fallback
}

// Size guide button
function render_size_guide_button() {
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
    
    // Price info
    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();
    $is_on_sale = $product->is_on_sale();
    $discount_percentage = 0;
    
    if ($is_on_sale && $regular_price > 0) {
        $discount_percentage = round(100 - (($sale_price / $regular_price) * 100));
    }
    
    // Check if hire category
    $is_hire = has_term('Hire', 'product_cat');
    
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
    
    // Get variations
    $available_variations = array();
    if ($product->is_type('variable')) {
        $available_variations = $product->get_available_variations();
    }
    
    // Get product SKU
    $product_sku = $product->get_sku();
?>

<style>

    mark, insert{
        color:#c1272d !important
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
                    <img src="<?php echo esc_url($main_image_url ?: $fallback_image); ?>" 
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
            
            <!-- Stock Status -->
            <?php echo wc_get_stock_html($product); ?>
            
            <?php if ($product->get_sku()) : ?>
            <div class="product-code">Product Code: <?php echo esc_html($product->get_sku()); ?></div>
            <?php endif; ?>
            
            <!-- Price Display -->
            <div class="price-container">
                <?php if ($is_on_sale && $discount_percentage > 0) : ?>
                    <div class="discount-badge"><?php echo esc_html($discount_percentage); ?>%</div>
                <?php endif; ?>
                
                <div class="price-box">
                    <?php echo $product->get_price_html(); ?>
                </div>
            </div>
            
            <!-- Variations -->
            <?php if ($product->is_type('variable')) : ?>
                <?php
                $attributes = $product->get_variation_attributes();
                
                foreach ($attributes as $attribute_name => $options) :
                    $attribute_label = wc_attribute_label($attribute_name);
                    $attribute_id = sanitize_title($attribute_name);
                    $selected_value = isset($_REQUEST['attribute_' . $attribute_id]) ? 
                                     wc_clean(wp_unslash($_REQUEST['attribute_' . $attribute_id])) : 
                                     $product->get_variation_default_attribute($attribute_name);
                    
                    $full_attribute_name = 'attribute_' . $attribute_id;
                    
                    // Detect attribute type
                    $is_color = stripos($attribute_label, 'color') !== false || 
                               stripos($attribute_label, 'colour') !== false;
                    $is_size = stripos($attribute_label, 'size') !== false;
                    
                    $attr_class = 'attribute-selection';
                    $option_class = 'attribute-option';
                    
                    if ($is_color) {
                        $attr_class = 'color-selection';
                        $option_class = 'color-option';
                    } elseif ($is_size) {
                        $attr_class = 'size-selection';
                        $option_class = 'size-option';
                    }
                ?>
                <div class="<?php echo esc_attr($attr_class); ?>" 
                     data-attribute="<?php echo esc_attr($full_attribute_name); ?>">
                    <div class="attribute-title"><?php echo esc_html($attribute_label); ?></div>
                    <div class="attribute-options">
                        <?php 
                        if ($is_size) {
                            sort($options, SORT_NUMERIC);
                        }
                        
                        foreach ($options as $option) : 
                            $is_selected = ($selected_value === $option);
                        ?>
                            <div class="<?php echo esc_attr($option_class . ' ' . ($is_selected ? 'active' : '')); ?>" 
                                 data-value="<?php echo esc_attr($option); ?>"
                                 data-attribute="<?php echo esc_attr($full_attribute_name); ?>"
                                 title="<?php echo esc_attr($option); ?>">
                                <?php 
                                if ($is_color) {
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
                <?php endforeach; ?>
                
                <!-- Clear Selection Button -->
                <div class="clear-selection-wrapper">
                    <button type="button" class="btn-clear-selection" id="clearSelectionBtn" style="display: none;">
                        <i class="fa-solid fa-undo"></i> Clear Selection
                    </button>
                </div>
            <?php endif; ?>
            
            <div class="shipping-info">
                Orders placed now will be shipped within 3-7 business days.
            </div>
            
            <?php if ($is_hire) : ?>
            <div class="shipping-info">
                Hire products only available in-store.
            </div>
            <?php endif; ?>
            
            <?php render_size_guide_button(); ?>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <?php if (!$is_hire) : ?>
                    <!-- WooCommerce Default Add to Cart -->
                    <?php woocommerce_template_single_add_to_cart(); ?>
                <?php endif; ?>
                
                <a href="/book-your-free-fitting">
                    <button class="appointment-btn">BOOK APPOINTMENT</button>
                </a>
                
                <div class="add-to-cart wishlist-btn">
                    <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
                </div>
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
    
    <!-- Related Products Section -->
    <div class="related-products">
        <h2>Recently Viewed</h2>
        <section class="container my-5">
            <div class="row g-4">
                <?php
                $viewed_products = !empty($_COOKIE['woocommerce_recently_viewed']) ? 
                                  (array) explode('|', $_COOKIE['woocommerce_recently_viewed']) : array();
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

<!-- JavaScript (simplified without stock rules) -->
<script type="text/javascript">
var productVariations = <?php echo json_encode($available_variations); ?>;

jQuery(document).ready(function($) {
    var selectedAttributes = {};
    
    // Clear selection functionality
    $('#clearSelectionBtn').on('click', function() {
        $('.attribute-option, .color-option, .size-option').removeClass('active');
        selectedAttributes = {};
        $('select[name^="attribute_"]').val('').trigger('change');
        $(this).fadeOut();
    });
    
    // Handle option clicks
    $('.color-option, .size-option, .attribute-option').on('click', function(e) {
        e.preventDefault();
        
        var $this = $(this);
        var value = $this.data('value');
        var attributeName = $this.data('attribute');
        
        $this.siblings().removeClass('active');
        $this.addClass('active');
        
        $('#clearSelectionBtn').fadeIn();
        selectedAttributes[attributeName] = value;
        
        var select = $('select[name="' + attributeName + '"]');
        if (select.length) {
            select.val(value).trigger('change');
        }
    });
    
    // Gallery navigation
    $('.thumbnail').on('click', function() {
        $('.thumbnail').removeClass('active');
        $(this).addClass('active');
        $('.main-image img').attr('src', $(this).find('img').attr('src'));
    });
    
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
});

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

<!-- Keep your existing CSS (remove only stock-related styles) -->
<style>
/* Your existing CSS here without stock-related styles */
</style>

<?php
endwhile; // end of the loop

get_footer('shop');
?>