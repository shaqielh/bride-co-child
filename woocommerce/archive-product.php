<?php
/**
 * The Template for displaying product archives
 * Clean version - stock rules handled by plugin
 * 
 * @package Bride-co-child
 * @version 8.6.0
 */

get_header('shop'); 

// Get current category info
$current_category_slug = '';
$current_category_name = '';
if (is_product_category()) {
    global $wp_query;
    $current_category = $wp_query->get_queried_object();
    $current_category_slug = $current_category->slug;
    $current_category_name = $current_category->name;
}

// Determine hide settings for Eurosuit
$hide_brand = is_eurosuit_page() ? 'bride-co' : 'eurosuit';
$hide_technical = is_eurosuit_page() ? 'technical-spec' : '';

// Get current sort order
$current_orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';
?>
<style>
/* === SALE PRICE COLOR FIX === */
.price ins, 
.woocommerce-Price-amount.amount ins,
.product-pricing ins,
span.woocommerce-Price-amount.amount ins {
  color: #c1272d !important; /* Deep red for sale price */
  font-weight: 700 !important;
  text-decoration: none !important;
}

/* Cross out the regular (old) price */
.price del, 
.woocommerce-Price-amount.amount del,
.product-pricing del,
span.woocommerce-Price-amount.amount del {
  color: #777 !important;
  opacity: 0.8;
  text-decoration: line-through;
  margin-left: 5px;
}

</style>
<!-- Meta tags for JavaScript -->
<meta name="current-category" content="<?php echo esc_attr($current_category_slug); ?>">
<meta name="current-orderby" content="<?php echo esc_attr($current_orderby ?: 'date'); ?>">

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <h4>Filter<?php echo !empty($current_category_name) ? ' ' . esc_html($current_category_name) : ''; ?></h4>
        <a href="javascript:void(0)" id="filterCategory1" class="filter-toggle">
            Hide Filter 
            <img title="Show Filter" src="<?php echo get_stylesheet_directory_uri() . '/assets/imgs/filter.png'; ?>" alt="Filter Icon">
        </a>  
    </div>
    
    <!-- Filter form -->
    <form id="product-filters" method="GET" action="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">
        <ul class="sidebar-menu">
            <?php
            // Get products for current view
            $products_args = array(
                'status' => 'publish',
                'limit' => -1,
                'category' => $current_category ? array($current_category->slug) : array(),
            );
            
            // Apply ordering
            switch ($current_orderby) {
                case 'menu_order':
                    $products_args['orderby'] = 'menu_order';
                    $products_args['order'] = 'ASC';
                    break;
                case 'price':
                    $products_args['orderby'] = 'price';
                    $products_args['order'] = 'ASC';
                    break;
                case 'price-desc':
                    $products_args['orderby'] = 'price';
                    $products_args['order'] = 'DESC';
                    break;
                case 'date':
                default:
                    $products_args['orderby'] = 'date';
                    $products_args['order'] = 'DESC';
                    break;
            }
            
            $products = wc_get_products($products_args);
            
            // Calculate price range
            $products_min_price = PHP_INT_MAX;
            $products_max_price = 0;
            
            foreach ($products as $product) {
                $price = wc_get_price_to_display($product);
                if ($price > 0) {
                    $products_min_price = min($products_min_price, $price);
                    $products_max_price = max($products_max_price, $price);
                }
            }
            
            // Set defaults
            if ($products_min_price === PHP_INT_MAX) {
                $products_min_price = 0;
            }
            
            if ($products_max_price === 0) {
                $products_max_price = 10000;
            }
            
            // Round values
            $products_min_price = floor($products_min_price / 100) * 100;
            $products_max_price = ceil($products_max_price * 1.1 / 100) * 100;
            
            $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : $products_min_price;
            $max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : $products_max_price;
            
            // Output meta for JS
            echo '<meta name="price-min" content="' . esc_attr($products_min_price) . '">';
            echo '<meta name="price-max" content="' . esc_attr($products_max_price) . '">';
            
            // Price Range Filter
            ?>
            <li class="price-range-filter dropdown-filter active">
                <div class="dropdown-header">Price Range <span class="dropdown-icon">▾</span></div>
                <ul class="dropdown-content">
                    <li class="price-range-inputs">
                        <div class="price-input-container">
                            <label>Min Price 
                                <input type="number" id="min-price" name="min_price" 
                                       value="<?php echo esc_attr($min_price); ?>" 
                                       min="<?php echo esc_attr($products_min_price); ?>" 
                                       max="<?php echo esc_attr($products_max_price); ?>" step="100">
                            </label>
                            <label>Max Price 
                                <input type="number" id="max-price" name="max_price" 
                                       value="<?php echo esc_attr($max_price); ?>" 
                                       min="<?php echo esc_attr($products_min_price); ?>" 
                                       max="<?php echo esc_attr($products_max_price); ?>" step="100">
                            </label>
                        </div>
                        <div id="price-range-slider"></div>
                        <div id="price-range-display" class="price-range-display">
                            R<?php echo esc_html($min_price); ?> - R<?php echo esc_html($max_price); ?>
                        </div>
                    </li>
                </ul>
            </li>
            
            <?php
            // Attribute filters
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            
            if (!empty($attribute_taxonomies)) {
                foreach ($attribute_taxonomies as $attribute) {
                    // Skip hidden attributes
                    if ($attribute->attribute_name === $hide_technical || 
                        ($attribute->attribute_name === 'brand' && is_eurosuit_page())) {
                        continue;
                    }
                    
                    $attribute_name = wc_attribute_taxonomy_name($attribute->attribute_name);
                    $terms = get_terms(array(
                        'taxonomy' => $attribute_name,
                        'hide_empty' => true,
                    ));
                    
                    if (!empty($terms) && !is_wp_error($terms)) {
                        $current_filters = isset($_GET['filter_' . $attribute->attribute_name]) ?
                                           explode(',', $_GET['filter_' . $attribute->attribute_name]) :
                                           array();
                        ?>
                        <li class="dropdown-filter">
                            <div class="dropdown-header">
                                <?php echo esc_html($attribute->attribute_label); ?> 
                                <span class="dropdown-icon">▾</span>
                            </div>
                            <ul class="dropdown-content">
                                <?php foreach ($terms as $term) : 
                                    if ($hide_brand === $term->slug || 'viola-chan-premium' === $term->slug) {
                                        continue;
                                    }
                                    $is_checked = in_array($term->slug, $current_filters);
                                ?>
                                <li class="filter-checkbox-item">
                                    <label>
                                        <input type="checkbox" 
                                               name="filter_<?php echo esc_attr($attribute->attribute_name); ?>[]" 
                                               value="<?php echo esc_attr($term->slug); ?>"
                                               <?php checked($is_checked); ?> 
                                               class="filter-checkbox ajax-filter">
                                        <?php echo esc_html($term->name); ?>
                                    </label>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <?php
                    }
                }
            }
            ?>
        </ul>
    </form>
</div>

<!-- Loading indicators -->
<div class="loading-overlay"></div>
<div class="loading-spinner"></div>

<!-- Main Content -->
<div class="main-content">
    <!-- Header with title and sort -->
    <div class="catalog-header">
        <div class="filterdesign">
            <h1 class="catalog-title"><?php woocommerce_page_title(); ?></h1>
            <a href="javascript:void(0)" id="filterCategory" class="filter-toggle">
                Show Filter 
                <img title="Show Filter" src="<?php echo get_stylesheet_directory_uri() . '/assets/imgs/filter.png'; ?>" alt="Filter Icon">
            </a> 
        </div>
        
        <div class="sort-dropdown">
            <div class="sort-dropdown-button">
                <?php 
                $orderby_text = 'Newest';
                switch ($current_orderby) {
                    case 'menu_order':
                        $orderby_text = 'Most Popular';
                        break;
                    case 'price':
                        $orderby_text = 'Price low to high';
                        break;
                    case 'price-desc':
                        $orderby_text = 'Price high to low';
                        break;
                    case 'date':
                    default:
                        $orderby_text = 'Newest';
                        break;
                }
                echo esc_html($orderby_text); 
                ?><span>▾</span>
            </div>
            <div class="sort-dropdown-content">
                <a href="#" class="ajax-sort" data-orderby="date">Newest</a>
                <a href="#" class="ajax-sort" data-orderby="menu_order">Most Popular</a>
                <a href="#" class="ajax-sort" data-orderby="price">Price low to high</a>
                <a href="#" class="ajax-sort" data-orderby="price-desc">Price high to low</a>
            </div>
        </div>
    </div>
    
    <!-- Active filters display -->
    <div id="active-filters-container">
        <?php
        // Display active filters logic (simplified)
        $has_active_filters = false;
        $active_filters = array();
        
        foreach ($_GET as $key => $value) {
            if (strpos($key, 'filter_') === 0 && !empty($value)) {
                $has_active_filters = true;
                // Build active filters array...
            }
        }
        
        if ($has_active_filters) {
            echo '<div class="active-filters">';
            echo '<span class="active-filters-title">Active Filters:</span>';
            // Display filter tags...
            echo '<a href="' . esc_url(get_term_link(get_queried_object_id(), 'product_cat')) . '" class="clear-all-filters">Clear All</a>';
            echo '</div>';
        }
        ?>
    </div>
    
    <!-- Product Grid -->
    <section class="container my-5">
        <div class="row g-4" id="products-container">
            <?php
            if (woocommerce_product_loop()) {
                wc_set_loop_prop('per_page', 9);
                
                // Remove default WooCommerce hooks
                remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
                remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
                remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
                remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
                remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
                remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
                remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
                remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
                
                while (have_posts()) {
                    the_post();
                    global $product;
                    
                    if (!is_a($product, 'WC_Product')) {
                        continue;
                    }
                    
                    // Get product data
                    $product_id = $product->get_id();
                    $regular_price = wc_get_price_to_display($product, array('price' => $product->get_regular_price()));
                    $is_on_sale = $product->is_on_sale();
                    $discount_percentage = 0;
                    
                    if ($is_on_sale && $regular_price > 0) {
                        $sale_price = wc_get_price_to_display($product, array('price' => $product->get_sale_price()));
                        $discount_percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
                    }
                    
                    // Gallery images
                    $attachment_ids = $product->get_gallery_image_ids();
                    $hover_image = '';
                    if (!empty($attachment_ids)) {
                        $hover_image = wp_get_attachment_image_url($attachment_ids[0], 'large');
                    }
                    
                    // Product attributes
                    $brand = get_the_title();
                    $product_description = get_the_content();
                    
                    // Technical specification
                    $dress_name = '';
                    $tech_spec_terms = get_the_terms($product_id, 'pa_technical-spec');
                    if (!empty($tech_spec_terms) && !is_wp_error($tech_spec_terms)) {
                        $dress_name = $tech_spec_terms[0]->name;
                    }
                    
                    // Brand
                    $product_brand = '';
                    $brand_terms = get_the_terms($product_id, 'pa_brand');
                    if (!empty($brand_terms) && !is_wp_error($brand_terms)) {
                        $product_brand = $brand_terms[0]->name;
                    }
                    
                    $is_new = function_exists('is_product_new') ? is_product_new($product->get_id()) : false;
                    $is_eurosuite = function_exists('is_eurosuit_page') ? is_eurosuit_page() : false;
                    $newlabel_style = $is_eurosuite ? 'new-badge-euro' : '';
                    ?>
                    
                    <div class="col-md-4">
                        <a href="<?php echo esc_url(get_permalink()); ?>" class="product-card-link">
                            <div class="product-card">
                                <div class="image-container">
                                    <?php if ($is_new) : ?>
                                        <span class="label new-label <?php echo $newlabel_style; ?>">NEW</span>
                                    <?php endif; ?>
                                    
                                    <?php if (has_post_thumbnail()) : ?>
                                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>" 
                                             class="product-image" alt="<?php echo esc_attr($brand); ?>" />
                                    <?php else : ?>
                                        <img src="<?php echo wc_placeholder_img_src(); ?>" 
                                             class="product-image" alt="<?php echo esc_attr($brand); ?>" />
                                    <?php endif; ?>
                                    
                                    <?php if ($hover_image) : ?>
                                        <img src="<?php echo esc_url($hover_image); ?>" 
                                             class="hover-image" alt="<?php echo esc_attr($brand); ?> Hover" />
                                    <?php elseif (has_post_thumbnail()) : ?>
                                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>" 
                                             class="hover-image" alt="<?php echo esc_attr($brand); ?> Hover" />
                                    <?php endif; ?>
                                    
                                    <span class="add-to-cart-btn">SHOP NOW</span>
                                </div>
                                
                                <?php if (!empty($dress_name)) : ?>
                                    <h4 class="mt-3 tech-spec-title"><?php echo esc_html($dress_name); ?></h4>
                                    <h5 class="product-title-with-spec"><?php echo esc_html($brand); ?></h5>
                                <?php else : ?>
                                    <h5 class="mt-3 fw-bold"><?php echo esc_html($brand); ?></h5>
                                <?php endif; ?>
                                
                                <?php if (!empty($product_description)) : ?>
                                    <p class="product-description"><?php echo wp_trim_words($product_description, 15); ?></p>
                                <?php endif; ?>
                                
                                <?php if ($is_on_sale && $discount_percentage > 0) : ?>
                                    <span class="discount-box"><?php echo esc_html($discount_percentage); ?>%</span>
                                <?php endif; ?>
                                
                                <?php if (!empty($product_brand)) : ?>
                                    <p class="product-brand"><?php echo esc_html($product_brand); ?></p>
                                <?php endif; ?>
                                
                                <p class="product-pricing">
                                    <?php echo $product->get_price_html(); ?>
                                </p>
                            </div>
                        </a>
                    </div>
                    <?php
                }
                
                // Restore hooks
                add_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
                add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
                add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
                add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
                add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
                add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
                add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
                add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
            } else {
                do_action('woocommerce_no_products_found');
            }
            ?>
        </div>
        
        <!-- Pagination -->
        <div id="pagination-container" class="woocommerce-pagination">
            <?php
            if (woocommerce_product_loop() && wc_get_loop_prop('total') > wc_get_loop_prop('per_page')) {
                woocommerce_pagination();
            }
            ?>
        </div>
        
        <div class="loading-pagination"></div>
    </section>
</div>

<!-- Keep all your existing styles and JavaScript as-is (remove only stock-related code) -->
<style>
/* Your existing CSS here - remove only stock-related styles */
</style>

<script>
// Your existing JavaScript here - remove only stock-checking code
// Keep all filter, sort, and pagination functionality
var wc_ajax_object = {
    ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>',
    nonce: '<?php echo wp_create_nonce('product_filter_nonce'); ?>'
};

// Keep your existing filter/sort JavaScript unchanged
</script>



<style>
  /* Price Range Slider Styles */
  .price-range-filter {
    margin-bottom: 20px;
  }
  
  .price-range-inputs {
    list-style: none;
    margin-bottom: 15px;
  }
  
  .price-input-container {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
  }
  
  .price-input-container label {
    font-size: 14px;
    display: flex;
    flex-direction: column;
    width: 48%;
    width: 100%;
  }
  
  .price-input-container input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-top: 5px;
  }
  
  #price-range-slider {
    margin: 41px 10px 65px;
    height: 6px;
  }
  
  /* noUiSlider Customization */
  .noUi-connect {
    background: #333;
  }

  .noUi-handle {
    border-radius: 50%;
    background-color: #fff;
    box-shadow: 0 0 5px rgba(0,0,0,0.2);
    width: 18px !important;
    height: 18px !important;
    right: -9px !important;
    top: -7px !important;
  }

  .noUi-handle::before,
  .noUi-handle::after {
    display: none;
  }

  .price-range-display {
    text-align: center;
    font-size: 14px;
    color: #666;
    margin-top: 10px;
  }
  
  .dropdown-content{
    margin-left:0;
    margin-right:10px;
  }

#price-range-slider {
  margin: 30px 10px 40px;
  height: 6px;
  background: #ddd;
  border-radius: 3px;
  position: relative;
  width: 100%;
  min-width: 200px;
}

@media (max-width: 700px) {
  #price-range-slider {
    margin-top: 30px;
    margin-bottom: 40px;
    height: 6px;
  }

  .price-input-container input {
    pointer-events: auto;
    background-color: #fff;
    color: #000;
  }
}


  /* Product Card - Image Containment Fix */
  .product-card {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
    transition: all 0.3s ease;
  }

  .image-container {
    position: relative;
    width: 100%;
    overflow: hidden;
    background-color: #f8f8f8;
  }

  .product-image,
  .hover-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover; /* Changed from contain to cover */
    transition: opacity 0.3s ease;
	object-position: top;
  }

  .hover-image {
    opacity: 0;
	height: 100% !important;
  }

  .product-card:hover .hover-image {
    opacity: 1;
  }

  .product-card:hover .product-image {
    opacity: 0;
  }

  /* Shop Now button */
  .add-to-cart-btn {
    position: absolute;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(255, 255, 255, 0.9);
    color: #000;
    padding: 8px 16px;
    font-size: 12px;
    font-weight: bold;
    letter-spacing: 1px;
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .product-card:hover .add-to-cart-btn {
    opacity: 1;
  }

  /* Ensure product cards have consistent height */
  .col-md-4 {
    margin-bottom: 30px;
    height: auto;
    display: flex;
  }

  /* Make product cards take full height of their container */
  .product-card-link {
    display: block;
    height: 100%;
    width: 100%;
  }

  /* Product info section */
  .product-card .product-title-with-spec,
  .product-card h5.mt-3.fw-bold,
  .product-card .tech-spec-title,
  .product-card .product-description,
  .product-card .product-brand,
  .product-card .product-pricing {
    padding: 0 5px;
    overflow: hidden;
  }

  /* Fixed heights for text elements for more consistency */
  .product-card .tech-spec-title,
  .product-card h5.mt-3.fw-bold {
    height: 1.5rem;
    margin-bottom: 0.5rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .product-card .product-description {
    font-size: 0.9rem;
    line-height: 1.4;
    color: #555;
    margin-bottom: 0.5rem;
    height: 2.8rem; /* Exactly 2 lines at 1.4 line height */
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .product-card .product-brand {
    height: 1.2rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .product-card .product-pricing {
    height: 1.5rem;
	display: contents;
  }

  /* Responsive adjustments */
  @media (max-width: 767px) {
    .image-container {
      padding-bottom: 120%;
    }
  }
  
  /* Loading spinner for AJAX */
  .loading-spinner {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000;
  }

  .loading-spinner:after {
    content: " ";
    display: block;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 6px solid #333;
    border-color: #333 transparent #333 transparent;
    animation: spinner 1.2s linear infinite;
  }

  @keyframes spinner {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  /* Overlay for when loading */
  .loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(255, 255, 255, 0.7);
    z-index: 999;
  }
  
  /* Sidebar Dropdown Styles */
  .sidebar-menu .dropdown-filter .dropdown-content {
    display: none;
    padding-left: 15px;
  }

  .sidebar-menu .dropdown-filter.active .dropdown-content {
    display: block;
  }

  /* Sort Dropdown Styles */
  .sort-dropdown-content {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    right: 0;
  }

  .sort-dropdown.active .sort-dropdown-content {
    display: block;
  }

  /* Checkbox styles */
  .filter-checkbox-item {
    margin: 8px 0;
    display: flex;
    align-items: center;
  }

  .filter-checkbox-item label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-weight: normal;
    margin-bottom: 0;
    font-size: 14px;
  }

  .filter-checkbox {
    margin-right: 8px;
    cursor: pointer;
  }

  /* Active filters */
  .active-filters {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    margin: 10px 0 20px;
    padding: 10px;
    background-color: #f9f9f9;
    border-radius: 4px;
  }

  .active-filters-title {
    font-weight: bold;
    margin-right: 10px;
  }

  .filter-tag {
    display: inline-flex;
    align-items: center;
    background-color: #eaeaea;
    padding: 4px 8px;
    border-radius: 3px;
    margin: 4px;
    font-size: 13px;
  }

  .remove-filter {
    margin-left: 6px;
    font-weight: bold;
    color: #666;
    text-decoration: none;
  }

  .clear-all-filters {
    margin-left: auto;
    color: #666;
    text-decoration: underline;
    font-size: 13px;
  }

  /* Additional styling for better appearance */
  .dropdown-header {
    cursor: pointer;
    padding: 8px 0;
    font-weight: normal;
  }

  .dropdown-filter {
    margin-bottom: 10px;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
  }

  .dropdown-content li {
    padding: 5px 0;
  }

  .dropdown-icon {
    float: right;
    margin-right: 10px;
  }

  /* Add styles for technical spec title and product title */
  .tech-spec-title {
    color: #666;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.2rem;
    margin-top: 1rem;
  }

  .product-title-with-spec {
    font-size: 0.95rem;
    font-weight: 500;
    margin-top: 0.2rem;
    margin-bottom: 0.5rem;
  }

  /* Original title style when no tech spec is present */
  h5.mt-3.fw-bold {
    margin-top: 1rem;
    font-size: 1.1rem;
  }
  .filterdesign
{
  display:inline-block;
  width: 100%;
}
.filterdesign h1 {float:left}
.filterdesign a {float:right}
.filterdesign img {width:30px;}
.sidebar-logo img {width:30px;}
.sidebar-logo a {float:right}

/* Pagination Styles */
.woocommerce-pagination {
  margin: 2rem 0;
  text-align: center;
}

.woocommerce-pagination ul.page-numbers {
  display: inline-flex;
  list-style: none;
  padding: 0;
  margin: 0;
  border: none;
}

.woocommerce-pagination ul.page-numbers li {
  margin: 0 4px;
  border: none;
}

.woocommerce-pagination ul.page-numbers li .page-numbers {
  display: inline-block;
  padding: 8px 14px;
  border: 1px solid #ddd;
  text-decoration: none;
  color: #333;
  border-radius: 3px;
  transition: all 0.3s ease;
}

.woocommerce-pagination ul.page-numbers li .page-numbers.current {
  background-color: #333;
  color: #fff;
  border-color: #333;
}

.woocommerce-pagination ul.page-numbers li .page-numbers:hover:not(.current) {
  background-color: #f5f5f5;
}

.woocommerce-pagination ul.page-numbers li .page-numbers.next,
.woocommerce-pagination ul.page-numbers li .page-numbers.prev {
  font-weight: bold;
  display: none;
}

/* Loading indicator for pagination */
.loading-pagination {
  margin: 0 auto;
  text-align: center;
  display: none;
}

.loading-pagination:after {
  content: " ";
  display: inline-block;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  border: 4px solid #333;
  border-color: #333 transparent #333 transparent;
  animation: spinner 1.2s linear infinite;
}
</style>

<script>
// AJAX URL and nonce already defined above - removed duplicate

// Track if user has manually changed price values
let userHasChangedPrice = false;
let originalMinPrice = null;
let originalMaxPrice = null;

// Sidebar and Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
  // Price Range Slider Initialization
  const minInput = document.getElementById('min-price');
  const maxInput = document.getElementById('max-price');
  const slider = document.getElementById('price-range-slider');
  let sliderInstance = null;

  // Store original price values
  if (minInput && maxInput) {
    originalMinPrice = parseInt(minInput.value);
    originalMaxPrice = parseInt(maxInput.value);
  }
  
  // Function to initialize sort button with correct text
  function initializeSortButton() {
    const sortButton = document.querySelector('.sort-dropdown-button');
    if (!sortButton) return;
    
    const orderByMeta = document.querySelector('meta[name="current-orderby"]');
    const currentOrderBy = orderByMeta ? orderByMeta.getAttribute('content') : 'date';
    
    let buttonText = 'Newest'; // Default
    switch (currentOrderBy) {
      case 'menu_order':
        buttonText = 'Most Popular';
        break;
      case 'price':
        buttonText = 'Price low to high';
        break;
      case 'price-desc':
        buttonText = 'Price high to low';
        break;
      case 'date':
      default:
        buttonText = 'Newest';
        break;
    }
    
    sortButton.innerHTML = buttonText + '<span>▾</span>';
  }

  function initializePriceSlider() {
    if (!slider || sliderInstance) return;

    // Get dynamic price range from meta tags
    const priceMinMeta = document.querySelector('meta[name="price-min"]');
    const priceMaxMeta = document.querySelector('meta[name="price-max"]');
    
    const priceMin = priceMinMeta ? parseInt(priceMinMeta.getAttribute('content')) : 0;
    const priceMax = priceMaxMeta ? parseInt(priceMaxMeta.getAttribute('content')) : 10000;
    
    // Use user-selected values or fall back to the calculated range
    const minVal = Math.max(priceMin, parseInt(minInput.value) || priceMin);
    const maxVal = Math.min(priceMax, parseInt(maxInput.value) || priceMax);

    // Check if slider container is visible
    const sidebar = document.getElementById('sidebar');
    const isVisible = sidebar && window.getComputedStyle(sidebar).display !== 'none';
    
    // Only initialize if slider is visible or we're on desktop
    if (!isVisible && window.innerWidth < 700) {
      return;
    }

    try {
      noUiSlider.create(slider, {
        start: [minVal, maxVal],
        connect: true,
        step: Math.max(100, Math.floor((priceMax - priceMin) / 20)),
        range: {
          min: priceMin,
          max: priceMax
        },
        format: {
          to: value => Math.round(value),
          from: value => value
        },
        tooltips: true,
        pips: {
          mode: 'positions',
          values: [0, 25, 50, 75, 100],
          density: 5,
          format: {
            to: value => {
              if (value >= 1000000) {
                return (value / 1000000).toFixed(1).replace(/\.0$/, '') + 'm';
              } else if (value >= 1000) {
                return (value / 1000).toFixed(1).replace(/\.0$/, '') + 'k';
              } else {
                return 'R' + Math.round(value);
              }
            },
            from: value => value
          }
        }
      });

      sliderInstance = slider.noUiSlider;

      sliderInstance.on('update', function (values, handle) {
        minInput.value = Math.round(values[0]);
        maxInput.value = Math.round(values[1]);
        
        // Update visible price range display
        const priceRangeDisplay = document.getElementById('price-range-display');
        if (priceRangeDisplay) {
          priceRangeDisplay.textContent = 'R' + Math.round(values[0]) + ' - R' + Math.round(values[1]);
        }
      });

// Execute filter on slider change (with debounce) - MARK AS USER CHANGED
let timer;
sliderInstance.on('change', function() {
  userHasChangedPrice = true; // Mark that user has changed price
  clearTimeout(timer);

  timer = setTimeout(function() {
    executeFilter();

    // ✅ AUTO CLOSE SIDEBAR ON MOBILE AFTER SLIDER USE
    if (window.innerWidth < 700) {
      const filterPanel = document.getElementById('sidebar');
      if (filterPanel) {
        filterPanel.style.display = 'none';
        destroySlider(); // optional but recommended (matches your toggle behavior)
      }
    }

  }, 500);
});


    } catch (error) {
      console.error('Error initializing price slider:', error);
    }
  }

  function destroySlider() {
    if (sliderInstance) {
      sliderInstance.destroy();
      sliderInstance = null;
    }
  }

  // Initialize on desktop or when sidebar is visible
  if (window.innerWidth >= 700) {
    // Desktop - initialize immediately
    if (typeof noUiSlider !== 'undefined') {
      initializePriceSlider();
    } else {
      // Wait for noUiSlider to load
      setTimeout(() => initializePriceSlider(), 100);
    }
  }

  // Mobile filter toggle functionality
  const toggleButton = document.getElementById('filterCategory');
  const toggleButton1 = document.getElementById('filterCategory1');
  const filterPanel = document.getElementById('sidebar');

  // Mobile setup
  if (window.innerWidth < 700) {
    if (filterPanel) {
      filterPanel.style.display = 'none';
    }
    if (toggleButton && toggleButton1) {
      toggleButton.style.display = 'block';
    }

    // Toggle functionality for mobile
    function handleToggle() {
      const isOpen = filterPanel.style.display === 'block';
      
      if (isOpen) {
        // Hiding the filter
        filterPanel.style.display = 'none';
        destroySlider();
      } else {
        // Showing the filter
        filterPanel.style.display = 'block';
        // Initialize slider after a short delay to ensure visibility
        setTimeout(() => {
          if (typeof noUiSlider !== 'undefined') {
            initializePriceSlider();
          }
        }, 150);
      }
    }

    if (toggleButton) {
      toggleButton.addEventListener('click', handleToggle);
    }
    if (toggleButton1) {
      toggleButton1.addEventListener('click', handleToggle);
    }
  } else {
    // Desktop - hide toggle buttons
    if (document.getElementById('filterCategory1')) {
      document.getElementById('filterCategory1').style.display = 'none';
    }
    if (document.getElementById('filterCategory')) {
      document.getElementById('filterCategory').style.display = 'none';
    }
  }

  // Allow manual input updates (with proper slider handling) - MARK AS USER CHANGED
  if (minInput && maxInput) {
    minInput.addEventListener('change', function () {
      userHasChangedPrice = true; // Mark that user has changed price
      const priceMinMeta = document.querySelector('meta[name="price-min"]');
      const priceMaxMeta = document.querySelector('meta[name="price-max"]');
      const priceMin = priceMinMeta ? parseInt(priceMinMeta.getAttribute('content')) : 0;
      const priceMax = priceMaxMeta ? parseInt(priceMaxMeta.getAttribute('content')) : 10000;
      
      const val = Math.max(priceMin, Math.min(priceMax, parseInt(this.value) || priceMin));
      this.value = val;
      if (sliderInstance) {
        sliderInstance.set([val, null]);
      }
      executeFilter();
    });

    maxInput.addEventListener('change', function () {
      userHasChangedPrice = true; // Mark that user has changed price
      const priceMinMeta = document.querySelector('meta[name="price-min"]');
      const priceMaxMeta = document.querySelector('meta[name="price-max"]');
      const priceMin = priceMinMeta ? parseInt(priceMinMeta.getAttribute('content')) : 0;
      const priceMax = priceMaxMeta ? parseInt(priceMaxMeta.getAttribute('content')) : 10000;
      
      const val = Math.max(priceMin, Math.min(priceMax, parseInt(this.value) || priceMax));
      this.value = val;
      if (sliderInstance) {
        sliderInstance.set([null, val]);
      }
      executeFilter();
    });
  }

  // Dropdown Functionality for Sidebar Filters
  const dropdownHeaders = document.querySelectorAll('.dropdown-header');
  
  dropdownHeaders.forEach(header => {
    header.addEventListener('click', function() {
      // Toggle the active class on the parent li
      this.parentElement.classList.toggle('active');
      
      // Toggle the dropdown icon
      const icon = this.querySelector('.dropdown-icon');
      if (icon) {
        icon.textContent = this.parentElement.classList.contains('active') ? '▴' : '▾';
      }
    });
  });

  // Checkbox Filter Functionality - Update to trigger AJAX instantly
 const filterCheckboxes = document.querySelectorAll('.filter-checkbox');

filterCheckboxes.forEach(checkbox => {
  checkbox.addEventListener('change', function() {

    executeFilter();

    // 👉 AUTO CLOSE SIDEBAR ON MOBILE
    if (window.innerWidth < 700) {
      const filterPanel = document.getElementById('sidebar');
      if (filterPanel) {
        filterPanel.style.display = 'none';
      }
    }

  });
});

  
  // Sort dropdown functionality
  const sortDropdown = document.querySelector('.sort-dropdown');
  if (sortDropdown) {
    const sortButton = sortDropdown.querySelector('.sort-dropdown-button');
    const sortOptions = sortDropdown.querySelectorAll('.sort-dropdown-content a');
    
    // Toggle dropdown
    sortButton.addEventListener('click', function() {
      sortDropdown.classList.toggle('active');
    });
    
    // Handle sort option selection - SEPARATE FUNCTION FOR SORTING
    sortOptions.forEach(option => {
      option.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Get orderby value
        let orderBy = this.getAttribute('data-orderby');
        if (!orderBy) {
          const href = this.getAttribute('href');
          const match = href.match(/orderby=([^&]*)/);
          if (match) {
            orderBy = match[1];
          }
        }
        
        // Update sort button text
        sortButton.innerHTML = this.textContent + '<span>▾</span>';
        sortDropdown.classList.remove('active');
        
        // Update the current-orderby meta tag
        const orderByMeta = document.querySelector('meta[name="current-orderby"]');
        if (orderByMeta) {
          orderByMeta.setAttribute('content', orderBy);
        }
        
        // Use sorting function instead of general filter
        executeSort(orderBy);
      });
    });
    
    // Close sort dropdown when clicking elsewhere
    document.addEventListener('click', function(e) {
      if (!sortDropdown.contains(e.target)) {
        sortDropdown.classList.remove('active');
      }
    });
  }
  
  // Handle active filter removal
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-filter')) {
      e.preventDefault();
      
      const type = e.target.getAttribute('data-type');
      const value = e.target.getAttribute('data-value');
      
      if (type === 'price_range') {
        // Reset price inputs to original values
        const priceMinMeta = document.querySelector('meta[name="price-min"]');
        const priceMaxMeta = document.querySelector('meta[name="price-max"]');
        
        const priceMin = priceMinMeta ? parseInt(priceMinMeta.getAttribute('content')) : 0;
        const priceMax = priceMaxMeta ? parseInt(priceMaxMeta.getAttribute('content')) : 10000;
        
        // Reset price inputs
        document.getElementById('min-price').value = priceMin;
        document.getElementById('max-price').value = priceMax;
        if (sliderInstance) {
          sliderInstance.set([priceMin, priceMax]);
        }
        
        // Mark as user changed (removing filter counts as change)
        userHasChangedPrice = true;
      } else {
        // Find and uncheck the corresponding checkbox
        const checkboxSelector = `input[name="filter_${type}[]"]`;
        const checkboxes = document.querySelectorAll(checkboxSelector);
        checkboxes.forEach(checkbox => {
          if (checkbox.value === value) {
            checkbox.checked = false;
          }
        });
      }
      
      // Run filter
      executeFilter();
    }
  });
  
  // Clear all filters - IMPROVED
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('clear-all-filters')) {
      e.preventDefault();
      
      // Just navigate to the URL instead of trying to clear via AJAX
      window.location.href = e.target.getAttribute('href');
    }
  });
  
  // Initialize pagination click handlers
  initPaginationHandlers();
  
  // Initialize sort button text to show current sort
  initializeSortButton();
  
  // SEPARATE FUNCTION FOR SORTING ONLY - NO FILTERS APPLIED
  function executeSort(orderBy) {
    // Show loading state
    document.querySelector('.loading-overlay').style.display = 'block';
    document.querySelector('.loading-spinner').style.display = 'block';
    
    // Gather CURRENT filter data (don't apply new filters, just get current state)
    const attributes = {};
    const checkboxes = document.querySelectorAll('.filter-checkbox:checked');
    
    checkboxes.forEach(checkbox => {
      const name = checkbox.name.replace('filter_', '').replace('[]', '');
      if (!attributes[name]) {
        attributes[name] = [];
      }
      attributes[name].push(checkbox.value);
    });
    
    // Convert arrays to comma-separated strings
    Object.keys(attributes).forEach(key => {
      attributes[key] = attributes[key].join(',');
    });
    
    // Prepare form data for AJAX
    const formData = new FormData();
    formData.append('action', 'filter_products');
    formData.append('nonce', wc_ajax_object.nonce);
    formData.append('attributes', JSON.stringify(attributes));
    
    // ONLY send price data if user has changed it
    if (userHasChangedPrice) {
      formData.append('min_price', document.getElementById('min-price').value);
      formData.append('max_price', document.getElementById('max-price').value);
    }
    // If user hasn't changed price, don't send price data at all
    
    formData.append('paged', 1); // Reset to page 1 when sorting
    formData.append('orderby', orderBy);
    
    // Get current category
    const currentCategoryMeta = document.querySelector('meta[name="current-category"]');
    if (currentCategoryMeta) {
      formData.append('current_category', currentCategoryMeta.getAttribute('content'));
    }
    
    // Make AJAX request
    fetch(wc_ajax_object.ajax_url, {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Update products container
        document.getElementById('products-container').innerHTML = data.html;
        
        // Update active filters
        document.getElementById('active-filters-container').innerHTML = data.active_filters;
        
        // Update pagination
        if (data.pagination) {
          document.getElementById('pagination-container').innerHTML = data.pagination;
          // Re-initialize pagination handlers
          initPaginationHandlers();
        }
        
        // Update URL for browser history
        updateBrowserURL(attributes, userHasChangedPrice, orderBy, 1);
      } else {
        console.error('Error sorting products');
      }
    })
    .catch(error => {
      console.error('AJAX request failed:', error);
    })
    .finally(() => {
      // Hide loading state
      document.querySelector('.loading-overlay').style.display = 'none';
      document.querySelector('.loading-spinner').style.display = 'none';
    });
  }
  
  // SEPARATE FUNCTION FOR PAGINATION - DON'T APPLY FILTERS
  function executePagination(page) {
    // Show loading state
    document.querySelector('.loading-pagination').style.display = 'block';
    
    // Gather current filter data (don't change filters, just get current state)
    const attributes = {};
    const checkboxes = document.querySelectorAll('.filter-checkbox:checked');
    
    checkboxes.forEach(checkbox => {
      const name = checkbox.name.replace('filter_', '').replace('[]', '');
      if (!attributes[name]) {
        attributes[name] = [];
      }
      attributes[name].push(checkbox.value);
    });
    
    // Convert arrays to comma-separated strings
    Object.keys(attributes).forEach(key => {
      attributes[key] = attributes[key].join(',');
    });
    
    // Get current orderby
    const orderByMeta = document.querySelector('meta[name="current-orderby"]');
    const currentOrderBy = orderByMeta ? orderByMeta.getAttribute('content') : 'date';
    
    // Prepare form data for AJAX
    const formData = new FormData();
    formData.append('action', 'filter_products');
    formData.append('nonce', wc_ajax_object.nonce);
    formData.append('attributes', JSON.stringify(attributes));
    
    // ONLY send price data if user has changed it
    if (userHasChangedPrice) {
      formData.append('min_price', document.getElementById('min-price').value);
      formData.append('max_price', document.getElementById('max-price').value);
    }
    
    formData.append('paged', page);
    formData.append('orderby', currentOrderBy);
    
    // Get current category
    const currentCategoryMeta = document.querySelector('meta[name="current-category"]');
    if (currentCategoryMeta) {
      formData.append('current_category', currentCategoryMeta.getAttribute('content'));
    }
    
    // Make AJAX request
    fetch(wc_ajax_object.ajax_url, {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Update products container
        document.getElementById('products-container').innerHTML = data.html;
        
        // Update pagination
        if (data.pagination) {
          document.getElementById('pagination-container').innerHTML = data.pagination;
          // Re-initialize pagination handlers
          initPaginationHandlers();
        }
        
        // Update URL for browser history
        updateBrowserURL(attributes, userHasChangedPrice, currentOrderBy, page);
        
        // Scroll to top of products container
        document.getElementById('products-container').scrollIntoView({ 
          behavior: 'smooth', 
          block: 'start' 
        });
      } else {
        console.error('Error in pagination');
      }
    })
    .catch(error => {
      console.error('AJAX request failed:', error);
    })
    .finally(() => {
      // Hide loading state
      document.querySelector('.loading-pagination').style.display = 'none';
    });
  }
  
  // Main filter execution function - RESET TO PAGE 1
  function executeFilter(resetPage = true) {
    // Show loading state
    document.querySelector('.loading-overlay').style.display = 'block';
    document.querySelector('.loading-spinner').style.display = 'block';
    
    // Always reset to page 1 when filtering (except for pagination-only requests)
    const page = resetPage ? 1 : (getCurrentPage() || 1);
    
    // Gather filter data
    const attributes = {};
    const checkboxes = document.querySelectorAll('.filter-checkbox:checked');
    
    checkboxes.forEach(checkbox => {
      const name = checkbox.name.replace('filter_', '').replace('[]', '');
      if (!attributes[name]) {
        attributes[name] = [];
      }
      attributes[name].push(checkbox.value);
    });
    
    // Convert arrays to comma-separated strings
    Object.keys(attributes).forEach(key => {
      attributes[key] = attributes[key].join(',');
    });
    
    // Prepare form data for AJAX
    const formData = new FormData();
    formData.append('action', 'filter_products');
    formData.append('nonce', wc_ajax_object.nonce);
    formData.append('attributes', JSON.stringify(attributes));
    
    // ONLY send price data if user has changed it
    if (userHasChangedPrice) {
      formData.append('min_price', document.getElementById('min-price').value);
      formData.append('max_price', document.getElementById('max-price').value);
    }
    
    formData.append('paged', page);
    
    // Get current category
    const currentCategoryMeta = document.querySelector('meta[name="current-category"]');
    if (currentCategoryMeta) {
      formData.append('current_category', currentCategoryMeta.getAttribute('content'));
    }
    
    // Get the current orderby
    let currentOrderBy = null;
    const orderByMeta = document.querySelector('meta[name="current-orderby"]');
    if (orderByMeta) {
      currentOrderBy = orderByMeta.getAttribute('content');
    } else {
      const urlParams = new URLSearchParams(window.location.search);
      currentOrderBy = urlParams.get('orderby') || 'date';
    }
    
    // Always send the orderby parameter
    formData.append('orderby', currentOrderBy);
    
    // Make AJAX request
    fetch(wc_ajax_object.ajax_url, {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Update products container
        document.getElementById('products-container').innerHTML = data.html;
        
        // Update active filters
        document.getElementById('active-filters-container').innerHTML = data.active_filters;
        
        // Update pagination
        if (data.pagination) {
          document.getElementById('pagination-container').innerHTML = data.pagination;
          // Re-initialize pagination handlers
          initPaginationHandlers();
        }
        
        // Update URL for browser history
        updateBrowserURL(attributes, userHasChangedPrice, currentOrderBy, page);
        
        // Scroll to top of products container if not on page 1
        if (page > 1) {
          document.getElementById('products-container').scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
          });
        }
      } else {
        console.error('Error filtering products');
      }
    })
    .catch(error => {
      console.error('AJAX request failed:', error);
    })
    .finally(() => {
      // Hide loading state
      document.querySelector('.loading-overlay').style.display = 'none';
      document.querySelector('.loading-spinner').style.display = 'none';
    });
  }
  
  // Helper function to get current page number
  function getCurrentPage() {
    const currentPageElement = document.querySelector('.page-numbers.current');
    return currentPageElement ? parseInt(currentPageElement.textContent) : 1;
  }
  
  // Function to initialize pagination handlers - UPDATED
  function initPaginationHandlers() {
    const paginationLinks = document.querySelectorAll('.woocommerce-pagination a.page-numbers');
    
    paginationLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Get page number from data attribute or href
        let page = this.getAttribute('data-page');
        
        if (!page) {
          const href = this.getAttribute('href');
          const pageMatch = href.match(/page\/(\d+)/);
          if (pageMatch) {
            page = parseInt(pageMatch[1]);
          } else {
            const pagedMatch = href.match(/paged=(\d+)/);
            if (pagedMatch) {
              page = parseInt(pagedMatch[1]);
            }
          }
        }
        
        page = parseInt(page) || 1;
        
        // Use pagination function instead of filter function
        executePagination(page);
      });
    });
  }
  
  // Helper function to update browser URL without page reload
  function updateBrowserURL(attributes, priceChanged, orderBy, page) {
    const urlParams = new URLSearchParams(window.location.search);
    
    // Remove existing filter parameters
    Array.from(urlParams.keys()).forEach(key => {
      if (key.startsWith('filter_') || key === 'min_price' || key === 'max_price' || key === 'orderby' || key === 'paged') {
        urlParams.delete(key);
      }
    });
    
    // Add attributes
    Object.keys(attributes).forEach(key => {
      if (attributes[key]) {
        urlParams.set('filter_' + key, attributes[key]);
      }
    });
    
    // ONLY add price range if user has changed it
    if (priceChanged) {
      const minPrice = document.getElementById('min-price').value;
      const maxPrice = document.getElementById('max-price').value;
      
      const priceMinMeta = document.querySelector('meta[name="price-min"]');
      const priceMaxMeta = document.querySelector('meta[name="price-max"]');
      
      const priceMin = priceMinMeta ? parseInt(priceMinMeta.getAttribute('content')) : 0;
      const priceMax = priceMaxMeta ? parseInt(priceMaxMeta.getAttribute('content')) : 10000;
      
      // Add price range if different from default
      if (parseInt(minPrice) > priceMin) urlParams.set('min_price', minPrice);
      if (parseInt(maxPrice) < priceMax) urlParams.set('max_price', maxPrice);
    }
    
    // Add sort order
    if (orderBy && orderBy !== 'date') urlParams.set('orderby', orderBy);
    
    // Add page number if greater than 1
    if (page > 1) urlParams.set('paged', page);
    
    // Update URL
    const newURL = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
    window.history.pushState({path: newURL}, '', newURL);
  }
});
</script>


<?php get_footer('shop'); ?>

<!-- SNW2 pagination: force SAME-TAB navigation -->
<script>
document.addEventListener('DOMContentLoaded',function(){
  var container = document.querySelector('.woocommerce-pagination, nav.woocommerce-pagination, .pagination');
  if (!container) return;
  container.querySelectorAll('a[href]').forEach(function(a){
    // Ensure same-tab behavior
    if (a.hasAttribute('target')) a.removeAttribute('target');
    if (a.hasAttribute('rel')) a.removeAttribute('rel');
  });
});
document.addEventListener('click', function(ev){
  var a = ev.target.closest('.woocommerce-pagination a, nav.woocommerce-pagination a, .pagination a');
  if (!a) return;
  var isCurrent = a.getAttribute('aria-current') === 'page' || (a.parentElement && a.parentElement.classList && a.parentElement.classList.contains('current'));
  var isDisabled = a.classList.contains('disabled') || a.getAttribute('aria-disabled') === 'true';
  if (isCurrent || isDisabled) return;
  var href = a.getAttribute('href');
  if (!href) return;
  ev.preventDefault();
  // Navigate in the SAME tab
  window.location.href = href;
}, true);

</script>