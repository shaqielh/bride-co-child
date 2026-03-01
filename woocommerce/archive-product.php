<?php
/**
 * The Template for displaying product archives
 * Clean version - stock rules handled by plugin
 * 
 * @package Bride-co-child
 * @version 8.7.0
 */

get_header('shop'); 

// Get current category info
$current_category_slug = '';
$current_category_name = '';
$is_bridal_category = false;

if (is_product_category()) {
    global $wp_query;
    $current_category = $wp_query->get_queried_object();
    $current_category_slug = $current_category->slug;
    $current_category_name = $current_category->name;
    
    // Check if we're in the bridal category (direct only, not child categories)
    $is_bridal_category = ($current_category_slug === 'bridal');
}

// Determine hide settings for Eurosuit
$hide_brand = is_eurosuit_page() ? 'bride-co' : 'eurosuit';
$hide_technical = is_eurosuit_page() ? 'technical-spec' : '';

// Get current sort order
$current_orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';

// Threads of Heritage icon
$threads_icon_url = 'https://brideandco.co.za/wp-content/uploads/2026/03/threads-icon.svg';
$threads_page_url = home_url('/threads-of-heritage/');
?>

<style>
/* === SALE PRICE === */
.price ins, 
.woocommerce-Price-amount.amount ins,
.product-pricing ins,
span.woocommerce-Price-amount.amount ins {
    color: #c1272d !important;
    font-weight: 700 !important;
    text-decoration: none !important;
}

.price del, 
.woocommerce-Price-amount.amount del,
.product-pricing del,
span.woocommerce-Price-amount.amount del {
    color: #777 !important;
    opacity: 0.8;
    text-decoration: line-through;
    margin-left: 5px;
}

/* === LAYOUT === */
.filterdesign {
    display: inline-block;
    width: 100%;
}

.filterdesign h1 { float: left; }
.filterdesign a { float: right; }
.filterdesign img,
.sidebar-logo img { width: 30px; }
.sidebar-logo a { float: right; }

/* === PRODUCT CARD === */
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
    object-fit: cover;
    object-position: top;
    transition: opacity 0.3s ease;
}

.hover-image {
    opacity: 0;
    height: 100% !important;
}

.product-card:hover .hover-image { opacity: 1; }
.product-card:hover .product-image { opacity: 0; }

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

.product-card:hover .add-to-cart-btn { opacity: 1; }

.col-md-4 {
    margin-bottom: 30px;
    height: auto;
    display: flex;
}

.product-card-link {
    display: block;
    height: 100%;
    width: 100%;
}

/* Product info */
.product-card .product-title-with-spec,
.product-card h5.mt-3.fw-bold,
.product-card .tech-spec-title,
.product-card .product-description,
.product-card .product-brand,
.product-card .product-pricing {
    padding: 0 5px;
    overflow: hidden;
}

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

h5.mt-3.fw-bold {
    margin-top: 1rem;
    font-size: 1.1rem;
}

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
    height: 2.8rem;
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

/* === THREADS OF HERITAGE CALLOUT === */
.threads-callout {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 6px 5px;
    margin-top: 4px;
}

.threads-callout-icon {
    width: 30px;
    height: 30px;
    flex-shrink: 0;
}

.threads-callout-text {
    font-family: "Poppins", sans-serif;
    font-size: 11px;
    color: #999;
    font-weight: 400;
}

/* === LABELS === */
.label.new-label {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 2;
    background: #000;
    color: #fff;
    padding: 4px 10px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1px;
    width:50px;
}

/* === SPECIAL VALUE BADGE === */
.label.special-value-label {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 2;
    background: #c1272d;
    color: #fff;
    padding: 4px 10px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.discount-box {
    display: inline-block;
    background: #c1272d;
    color: #fff;
    padding: 2px 8px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 3px;
    margin: 4px 5px;
}

/* === SIDEBAR & FILTERS === */
.sidebar-menu .dropdown-filter .dropdown-content {
    display: none;
    padding-left: 15px;
}

.sidebar-menu .dropdown-filter.active .dropdown-content { display: block; }

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

.dropdown-content li { padding: 5px 0; }

.dropdown-content {
    margin-left: 0;
    margin-right: 10px;
}

.dropdown-icon {
    float: right;
    margin-right: 10px;
}

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

.filter-checkbox { margin-right: 8px; cursor: pointer; }

/* === SORT DROPDOWN === */
.sort-dropdown-content {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    right: 0;
}

.sort-dropdown.active .sort-dropdown-content { display: block; }

/* === ACTIVE FILTERS === */
.active-filters {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    margin: 10px 0 20px;
    padding: 10px;
    background-color: #f9f9f9;
    border-radius: 4px;
}

.active-filters-title { font-weight: bold; margin-right: 10px; }

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

/* === PRICE SLIDER === */
.price-range-filter { margin-bottom: 20px; }
.price-range-inputs { list-style: none; margin-bottom: 15px; }

.price-input-container {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.price-input-container label {
    font-size: 14px;
    display: flex;
    flex-direction: column;
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
    margin: 30px 10px 40px;
    height: 6px;
    background: #ddd;
    border-radius: 3px;
    position: relative;
    width: 100%;
    min-width: 200px;
}

.noUi-connect { background: #333; }

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
.noUi-handle::after { display: none; }

.price-range-display {
    text-align: center;
    font-size: 14px;
    color: #666;
    margin-top: 10px;
}

/* === LOADING === */
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

/* === PAGINATION === */
.woocommerce-pagination { margin: 2rem 0; text-align: center; }

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

/* === RESPONSIVE === */
@media (max-width: 767px) {
    .image-container { padding-bottom: 120%; }
    
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
    
    .threads-callout {
        padding: 6px 8px;
        gap: 6px;
    }
    
    .threads-callout-icon {
        width: 30px;
        height: 18px;
    }
    
    .threads-callout-text {
        font-size: 10px;
    }
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
    
    <form id="product-filters" method="GET" action="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">
        <ul class="sidebar-menu">
            <?php
            // Get products for current view
            $products_args = array(
                'status' => 'publish',
                'limit'  => -1,
                'category' => $current_category ? array($current_category->slug) : array(),
            );
            
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
            
            if ($products_min_price === PHP_INT_MAX) $products_min_price = 0;
            if ($products_max_price === 0) $products_max_price = 10000;
            
            $products_min_price = floor($products_min_price / 100) * 100;
            $products_max_price = ceil($products_max_price * 1.1 / 100) * 100;
            
            $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : $products_min_price;
            $max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : $products_max_price;
            
            echo '<meta name="price-min" content="' . esc_attr($products_min_price) . '">';
            echo '<meta name="price-max" content="' . esc_attr($products_max_price) . '">';
            ?>
            
            <!-- Price Range Filter -->
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
                    if ($attribute->attribute_name === $hide_technical || 
                        ($attribute->attribute_name === 'brand' && is_eurosuit_page())) {
                        continue;
                    }
                    
                    $attribute_name = wc_attribute_taxonomy_name($attribute->attribute_name);
                    $terms = get_terms(array(
                        'taxonomy'   => $attribute_name,
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
                                    if ($hide_brand === $term->slug || 'viola-chan-premium' === $term->slug) continue;
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
    <!-- Header -->
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
                $sort_labels = array(
                    'menu_order' => 'Most Popular',
                    'price'      => 'Price low to high',
                    'price-desc' => 'Price high to low',
                    'date'       => 'Newest',
                );
                echo esc_html($sort_labels[$current_orderby] ?? 'Newest'); 
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
    
    <!-- Active filters -->
    <div id="active-filters-container">
        <?php
        $has_active_filters = false;
        foreach ($_GET as $key => $value) {
            if (strpos($key, 'filter_') === 0 && !empty($value)) {
                $has_active_filters = true;
                break;
            }
        }
        
        if ($has_active_filters) {
            echo '<div class="active-filters">';
            echo '<span class="active-filters-title">Active Filters:</span>';
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
                    
                    if (!is_a($product, 'WC_Product')) continue;
                    
                    $product_id = $product->get_id();
                    $regular_price = wc_get_price_to_display($product, array('price' => $product->get_regular_price()));
                    $is_on_sale = $product->is_on_sale();
                    $discount_percentage = 0;
                    
                    if ($is_on_sale && $regular_price > 0) {
                        $sale_price = wc_get_price_to_display($product, array('price' => $product->get_sale_price()));
                        $discount_percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
                    }
                    
                    // Gallery hover image
                    $attachment_ids = $product->get_gallery_image_ids();
                    $hover_image = !empty($attachment_ids) ? wp_get_attachment_image_url($attachment_ids[0], 'large') : '';
                    
                    $brand = get_the_title();
                    $product_description = get_the_content();
                    
                    // Technical spec
                    $dress_name = '';
                    $tech_spec_terms = get_the_terms($product_id, 'pa_technical-spec');
                    if (!empty($tech_spec_terms) && !is_wp_error($tech_spec_terms)) {
                        $dress_name = $tech_spec_terms[0]->name;
                    }
                    
                    // Brand attribute
                    $product_brand = '';
                    $brand_terms = get_the_terms($product_id, 'pa_brand');
                    if (!empty($brand_terms) && !is_wp_error($brand_terms)) {
                        $product_brand = $brand_terms[0]->name;
                    }
                    
                    $is_new = function_exists('is_product_new') ? is_product_new($product_id) : false;
                    $is_eurosuite = function_exists('is_eurosuit_page') ? is_eurosuit_page() : false;
                    $newlabel_style = $is_eurosuite ? 'new-badge-euro' : '';
                    
                    // Special Value badge - check for 'special-value' product tag
                    $has_special_value = has_term('special-value', 'product_tag', $product_id);
                    
                    // Threads of Heritage - bridal category only (direct match, not child categories)
                    $show_threads = false;
                    if ($current_category_slug === 'bridal') {
                        $show_threads = true;
                    } else {
                        $product_cats = get_the_terms($product_id, 'product_cat');
                        if (!empty($product_cats) && !is_wp_error($product_cats)) {
                            foreach ($product_cats as $cat) {
                                if ($cat->slug === 'bridal') {
                                    $show_threads = true;
                                    break;
                                }
                            }
                        }
                    }
                    ?>
                    
                    <div class="col-md-4">
                        <a href="<?php echo esc_url(get_permalink()); ?>" class="product-card-link">
                            <div class="product-card">
                                <div class="image-container">
                                    <?php if ($is_new) : ?>
                                        <span class="label new-label <?php echo $newlabel_style; ?>">NEW</span>
                                    <?php endif; ?>
                                    
                                    <?php if ($has_special_value) : ?>
                                        <span class="label special-value-label">Special Value</span>
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
                                
                                <?php if ($show_threads) : ?>
                                <div class="threads-callout">
                                    <img src="<?php echo esc_url($threads_icon_url); ?>" alt="Threads of Heritage" class="threads-callout-icon">
                                    <span class="threads-callout-text">Threads of Heritage available</span>
                                </div>
                                <?php endif; ?>
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

<script>
var wc_ajax_object = {
    ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>',
    nonce: '<?php echo wp_create_nonce('product_filter_nonce'); ?>'
};

// Track price changes
let userHasChangedPrice = false;
let originalMinPrice = null;
let originalMaxPrice = null;

document.addEventListener('DOMContentLoaded', function() {
    const minInput = document.getElementById('min-price');
    const maxInput = document.getElementById('max-price');
    const slider = document.getElementById('price-range-slider');
    let sliderInstance = null;

    if (minInput && maxInput) {
        originalMinPrice = parseInt(minInput.value);
        originalMaxPrice = parseInt(maxInput.value);
    }

    // Sort button init
    function initializeSortButton() {
        const sortButton = document.querySelector('.sort-dropdown-button');
        if (!sortButton) return;
        
        const orderByMeta = document.querySelector('meta[name="current-orderby"]');
        const currentOrderBy = orderByMeta ? orderByMeta.getAttribute('content') : 'date';
        
        const labels = {
            'menu_order': 'Most Popular',
            'price': 'Price low to high',
            'price-desc': 'Price high to low',
            'date': 'Newest'
        };
        
        sortButton.innerHTML = (labels[currentOrderBy] || 'Newest') + '<span>▾</span>';
    }

    // Price slider
    function initializePriceSlider() {
        if (!slider || sliderInstance) return;

        const priceMinMeta = document.querySelector('meta[name="price-min"]');
        const priceMaxMeta = document.querySelector('meta[name="price-max"]');
        const priceMin = priceMinMeta ? parseInt(priceMinMeta.getAttribute('content')) : 0;
        const priceMax = priceMaxMeta ? parseInt(priceMaxMeta.getAttribute('content')) : 10000;
        const minVal = Math.max(priceMin, parseInt(minInput.value) || priceMin);
        const maxVal = Math.min(priceMax, parseInt(maxInput.value) || priceMax);

        const sidebarEl = document.getElementById('sidebar');
        const isVisible = sidebarEl && window.getComputedStyle(sidebarEl).display !== 'none';
        if (!isVisible && window.innerWidth < 700) return;

        try {
            noUiSlider.create(slider, {
                start: [minVal, maxVal],
                connect: true,
                step: Math.max(100, Math.floor((priceMax - priceMin) / 20)),
                range: { min: priceMin, max: priceMax },
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
                            if (value >= 1000000) return (value / 1000000).toFixed(1).replace(/\.0$/, '') + 'm';
                            if (value >= 1000) return (value / 1000).toFixed(1).replace(/\.0$/, '') + 'k';
                            return 'R' + Math.round(value);
                        },
                        from: value => value
                    }
                }
            });

            sliderInstance = slider.noUiSlider;

            sliderInstance.on('update', function(values) {
                minInput.value = Math.round(values[0]);
                maxInput.value = Math.round(values[1]);
                const display = document.getElementById('price-range-display');
                if (display) display.textContent = 'R' + Math.round(values[0]) + ' - R' + Math.round(values[1]);
            });

            let timer;
            sliderInstance.on('change', function() {
                userHasChangedPrice = true;
                clearTimeout(timer);
                timer = setTimeout(function() {
                    executeFilter();
                    if (window.innerWidth < 700) {
                        const fp = document.getElementById('sidebar');
                        if (fp) { fp.style.display = 'none'; destroySlider(); }
                    }
                }, 500);
            });
        } catch (error) {
            console.error('Error initializing price slider:', error);
        }
    }

    function destroySlider() {
        if (sliderInstance) { sliderInstance.destroy(); sliderInstance = null; }
    }

    // Init slider on desktop
    if (window.innerWidth >= 700) {
        if (typeof noUiSlider !== 'undefined') initializePriceSlider();
        else setTimeout(() => initializePriceSlider(), 100);
    }

    // Mobile filter toggle
    const toggleButton = document.getElementById('filterCategory');
    const toggleButton1 = document.getElementById('filterCategory1');
    const filterPanel = document.getElementById('sidebar');

    if (window.innerWidth < 700) {
        if (filterPanel) filterPanel.style.display = 'none';
        if (toggleButton) toggleButton.style.display = 'block';

        function handleToggle() {
            const isOpen = filterPanel.style.display === 'block';
            if (isOpen) {
                filterPanel.style.display = 'none';
                destroySlider();
            } else {
                filterPanel.style.display = 'block';
                setTimeout(() => {
                    if (typeof noUiSlider !== 'undefined') initializePriceSlider();
                }, 150);
            }
        }

        if (toggleButton) toggleButton.addEventListener('click', handleToggle);
        if (toggleButton1) toggleButton1.addEventListener('click', handleToggle);
    } else {
        if (document.getElementById('filterCategory1')) document.getElementById('filterCategory1').style.display = 'none';
        if (document.getElementById('filterCategory')) document.getElementById('filterCategory').style.display = 'none';
    }

    // Manual price input
    if (minInput && maxInput) {
        function handlePriceInput(input, isMin) {
            input.addEventListener('change', function() {
                userHasChangedPrice = true;
                const pMin = parseInt(document.querySelector('meta[name="price-min"]')?.getAttribute('content')) || 0;
                const pMax = parseInt(document.querySelector('meta[name="price-max"]')?.getAttribute('content')) || 10000;
                const val = Math.max(pMin, Math.min(pMax, parseInt(this.value) || (isMin ? pMin : pMax)));
                this.value = val;
                if (sliderInstance) sliderInstance.set(isMin ? [val, null] : [null, val]);
                executeFilter();
            });
        }
        handlePriceInput(minInput, true);
        handlePriceInput(maxInput, false);
    }

    // Dropdown toggles
    document.querySelectorAll('.dropdown-header').forEach(header => {
        header.addEventListener('click', function() {
            this.parentElement.classList.toggle('active');
            const icon = this.querySelector('.dropdown-icon');
            if (icon) icon.textContent = this.parentElement.classList.contains('active') ? '▴' : '▾';
        });
    });

    // Checkbox filters
    document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            executeFilter();
            if (window.innerWidth < 700 && filterPanel) filterPanel.style.display = 'none';
        });
    });

    // Sort dropdown
    const sortDropdown = document.querySelector('.sort-dropdown');
    if (sortDropdown) {
        const sortButton = sortDropdown.querySelector('.sort-dropdown-button');
        const sortOptions = sortDropdown.querySelectorAll('.sort-dropdown-content a');
        
        sortButton.addEventListener('click', () => sortDropdown.classList.toggle('active'));
        
        sortOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                let orderBy = this.getAttribute('data-orderby');
                if (!orderBy) {
                    const match = this.getAttribute('href').match(/orderby=([^&]*)/);
                    if (match) orderBy = match[1];
                }
                sortButton.innerHTML = this.textContent + '<span>▾</span>';
                sortDropdown.classList.remove('active');
                const meta = document.querySelector('meta[name="current-orderby"]');
                if (meta) meta.setAttribute('content', orderBy);
                executeSort(orderBy);
            });
        });
        
        document.addEventListener('click', function(e) {
            if (!sortDropdown.contains(e.target)) sortDropdown.classList.remove('active');
        });
    }

    // Remove individual filter
    document.addEventListener('click', function(e) {
        if (!e.target.classList.contains('remove-filter')) return;
        e.preventDefault();
        
        const type = e.target.getAttribute('data-type');
        const value = e.target.getAttribute('data-value');
        
        if (type === 'price_range') {
            const pMin = parseInt(document.querySelector('meta[name="price-min"]')?.getAttribute('content')) || 0;
            const pMax = parseInt(document.querySelector('meta[name="price-max"]')?.getAttribute('content')) || 10000;
            document.getElementById('min-price').value = pMin;
            document.getElementById('max-price').value = pMax;
            if (sliderInstance) sliderInstance.set([pMin, pMax]);
            userHasChangedPrice = true;
        } else {
            document.querySelectorAll(`input[name="filter_${type}[]"]`).forEach(cb => {
                if (cb.value === value) cb.checked = false;
            });
        }
        executeFilter();
    });

    // Clear all filters
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('clear-all-filters')) {
            e.preventDefault();
            window.location.href = e.target.getAttribute('href');
        }
    });

    initPaginationHandlers();
    initializeSortButton();

    // === AJAX HELPERS ===
    
    function gatherFilters() {
        const attributes = {};
        document.querySelectorAll('.filter-checkbox:checked').forEach(cb => {
            const name = cb.name.replace('filter_', '').replace('[]', '');
            if (!attributes[name]) attributes[name] = [];
            attributes[name].push(cb.value);
        });
        Object.keys(attributes).forEach(key => { attributes[key] = attributes[key].join(','); });
        return attributes;
    }
    
    function buildFormData(attributes, orderBy, page) {
        const formData = new FormData();
        formData.append('action', 'filter_products');
        formData.append('nonce', wc_ajax_object.nonce);
        formData.append('attributes', JSON.stringify(attributes));
        
        if (userHasChangedPrice) {
            formData.append('min_price', document.getElementById('min-price').value);
            formData.append('max_price', document.getElementById('max-price').value);
        }
        
        formData.append('paged', page);
        formData.append('orderby', orderBy);
        
        const catMeta = document.querySelector('meta[name="current-category"]');
        if (catMeta) formData.append('current_category', catMeta.getAttribute('content'));
        
        return formData;
    }
    
    function showLoading(type) {
        if (type === 'full') {
            document.querySelector('.loading-overlay').style.display = 'block';
            document.querySelector('.loading-spinner').style.display = 'block';
        } else {
            document.querySelector('.loading-pagination').style.display = 'block';
        }
    }
    
    function hideLoading() {
        document.querySelector('.loading-overlay').style.display = 'none';
        document.querySelector('.loading-spinner').style.display = 'none';
        document.querySelector('.loading-pagination').style.display = 'none';
    }
    
    function handleResponse(data, orderBy, page, updateFilters) {
        if (!data.success) { console.error('AJAX error'); return; }
        
        document.getElementById('products-container').innerHTML = data.html;
        if (updateFilters) document.getElementById('active-filters-container').innerHTML = data.active_filters;
        
        if (data.pagination) {
            document.getElementById('pagination-container').innerHTML = data.pagination;
            initPaginationHandlers();
        }
        
        updateBrowserURL(gatherFilters(), userHasChangedPrice, orderBy, page);
        
        if (page > 1) {
            document.getElementById('products-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Sort
    function executeSort(orderBy) {
        showLoading('full');
        const attributes = gatherFilters();
        
        fetch(wc_ajax_object.ajax_url, { method: 'POST', body: buildFormData(attributes, orderBy, 1) })
            .then(r => r.json())
            .then(data => handleResponse(data, orderBy, 1, true))
            .catch(err => console.error('Sort failed:', err))
            .finally(hideLoading);
    }

    // Pagination
    function executePagination(page) {
        showLoading('pagination');
        const attributes = gatherFilters();
        const orderBy = document.querySelector('meta[name="current-orderby"]')?.getAttribute('content') || 'date';
        
        fetch(wc_ajax_object.ajax_url, { method: 'POST', body: buildFormData(attributes, orderBy, page) })
            .then(r => r.json())
            .then(data => handleResponse(data, orderBy, page, false))
            .catch(err => console.error('Pagination failed:', err))
            .finally(hideLoading);
    }

    // Filter
    function executeFilter(resetPage = true) {
        showLoading('full');
        const page = resetPage ? 1 : (getCurrentPage() || 1);
        const attributes = gatherFilters();
        const orderBy = document.querySelector('meta[name="current-orderby"]')?.getAttribute('content') || 'date';
        
        fetch(wc_ajax_object.ajax_url, { method: 'POST', body: buildFormData(attributes, orderBy, page) })
            .then(r => r.json())
            .then(data => handleResponse(data, orderBy, page, true))
            .catch(err => console.error('Filter failed:', err))
            .finally(hideLoading);
    }

    function getCurrentPage() {
        const el = document.querySelector('.page-numbers.current');
        return el ? parseInt(el.textContent) : 1;
    }

    function initPaginationHandlers() {
        document.querySelectorAll('.woocommerce-pagination a.page-numbers').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                let page = this.getAttribute('data-page');
                if (!page) {
                    const href = this.getAttribute('href');
                    const m = href.match(/page\/(\d+)/) || href.match(/paged=(\d+)/);
                    if (m) page = parseInt(m[1]);
                }
                executePagination(parseInt(page) || 1);
            });
        });
    }

    function updateBrowserURL(attributes, priceChanged, orderBy, page) {
        const urlParams = new URLSearchParams(window.location.search);
        
        // Clear existing params
        Array.from(urlParams.keys()).forEach(key => {
            if (key.startsWith('filter_') || ['min_price', 'max_price', 'orderby', 'paged'].includes(key)) {
                urlParams.delete(key);
            }
        });
        
        Object.keys(attributes).forEach(key => {
            if (attributes[key]) urlParams.set('filter_' + key, attributes[key]);
        });
        
        if (priceChanged) {
            const minP = document.getElementById('min-price').value;
            const maxP = document.getElementById('max-price').value;
            const pMin = parseInt(document.querySelector('meta[name="price-min"]')?.getAttribute('content')) || 0;
            const pMax = parseInt(document.querySelector('meta[name="price-max"]')?.getAttribute('content')) || 10000;
            if (parseInt(minP) > pMin) urlParams.set('min_price', minP);
            if (parseInt(maxP) < pMax) urlParams.set('max_price', maxP);
        }
        
        if (orderBy && orderBy !== 'date') urlParams.set('orderby', orderBy);
        if (page > 1) urlParams.set('paged', page);
        
        const newURL = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
        window.history.pushState({ path: newURL }, '', newURL);
    }
});
</script>

<?php get_footer('shop'); ?>

<!-- Pagination: force same-tab navigation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var container = document.querySelector('.woocommerce-pagination, nav.woocommerce-pagination, .pagination');
    if (!container) return;
    container.querySelectorAll('a[href]').forEach(function(a) {
        a.removeAttribute('target');
        a.removeAttribute('rel');
    });
});

document.addEventListener('click', function(ev) {
    var a = ev.target.closest('.woocommerce-pagination a, nav.woocommerce-pagination a, .pagination a');
    if (!a) return;
    if (a.getAttribute('aria-current') === 'page' || 
        (a.parentElement && a.parentElement.classList.contains('current')) ||
        a.classList.contains('disabled') || 
        a.getAttribute('aria-disabled') === 'true') return;
    var href = a.getAttribute('href');
    if (!href) return;
    ev.preventDefault();
    window.location.href = href;
}, true);
</script>