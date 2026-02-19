<?php
/**
 * Template Name: New Arrivals
 */
get_header(); ?>

<!-- Enqueue additional styles if needed instead of using a separate <head> section -->
<link rel="stylesheet" href="/styles.css" />


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
    padding-bottom: 133%;
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
</style>

<?php
// Get the current product category for passing to AJAX
$current_category_slug = '';
$current_category_name = '';
if (is_product_category()) {
    global $wp_query;
    $current_category = $wp_query->get_queried_object();
    $current_category_slug = $current_category->slug;
    $current_category_name = $current_category->name;
}

if (is_eurosuit_page()) {
  $hide = 'bride-co';
}else{
  $hide = 'eurosuit';
}
?>

<!-- Add a meta tag with current category for JS to access -->
<meta name="current-category" content="<?php echo esc_attr($current_category_slug); ?>">

<!-- Archive Template Loaded -->
<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-logo">
        <h4>Filter<?php echo !empty($current_category_name) ? ' ' . esc_html($current_category_name) : ''; ?></h4>
    </div>
    
    <!-- Filter form with AJAX handling -->
    <form id="product-filters" method="GET" action="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">
    <ul class="sidebar-menu">
        <?php
        // Get the current product category
        $current_category = null;
        if (is_product_category()) {
            global $wp_query;
            $current_category = $wp_query->get_queried_object();
        }
        
        // Get products for the current view
        $products_args = array(
            'status' => 'publish',
            'limit' => -1,
            'category' => $current_category ? array($current_category->slug) : array(),
        );
        
        // Apply any existing filters to get the correct subset of products
        if (!empty($_GET)) {
            // Handle attribute filters
            foreach ($_GET as $key => $value) {
                if (strpos($key, 'filter_') === 0 && !empty($value)) {
                    $attr_name = str_replace('filter_', '', $key);
                    $filter_values = explode(',', $value);
                    
                    $products_args['tax_query'][] = array(
                        'taxonomy' => wc_attribute_taxonomy_name($attr_name),
                        'field'    => 'slug',
                        'terms'    => $filter_values,
                        'operator' => 'IN',
                    );
                }
            }
        }

        $products_args = array(
          'limit'      => -1, // get all and filter manually
          'orderby'    => 'date',
          'order'      => 'DESC',
          'status'     => 'publish',
      );
      
      $all_products = wc_get_products($products_args);
      
      // Manually filter products created in the last 30 days
      $products = array_filter($all_products, function($product) {
          $created = $product->get_date_created();
          if (!$created) return false;
      
          $now = new DateTime();
          $interval = $now->diff($created);
      
          return $interval->days <= 30;
      });
      //print_r($products);
      // Optionally slice down to 9 results
      $products = array_slice($products, 0, 9);

      
      
        // Calculate min and max prices from the filtered products
        $products_min_price = PHP_INT_MAX;
        $products_max_price = 0;
        
        foreach ($products as $product) {
            $price = wc_get_price_to_display($product);
            
            // Skip products with invalid prices
            if ($price <= 0) {
                continue;
            }
            
            // Update min price
            if ($price < $products_min_price) {
                $products_min_price = $price;
            }
            
            // Update max price
            if ($price > $products_max_price) {
                $products_max_price = $price;
            }
        }
        
        // Set fallback values if no valid prices found
        if ($products_min_price === PHP_INT_MAX) {
            $products_min_price = 0;
        }
        
        if ($products_max_price === 0) {
            $products_max_price = 10000; // Fallback max price
        }
        
        // Add some padding to the max price (10% extra)
        $products_max_price = ceil($products_max_price * 1.1);
        
        // Round values to make them more user-friendly
        $products_min_price = floor($products_min_price / 100) * 100;
        $products_max_price = ceil($products_max_price / 100) * 100;
        
        // Now use these values for your filter
        $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : $products_min_price;
        $max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : $products_max_price;
        
        // Make these values available to JavaScript
        echo '<meta name="price-min" content="' . esc_attr($products_min_price) . '">';
        echo '<meta name="price-max" content="' . esc_attr($products_max_price) . '">';
        
        // Price Range Filter - MOVED TO TOP
        echo '<li class="price-range-filter dropdown-filter active">';
        echo '<div class="dropdown-header">Price Range <span class="dropdown-icon">▾</span></div>';
        echo '<ul class="dropdown-content">';
        echo '<li class="price-range-inputs">';
        echo '<div class="price-input-container">';
        echo '<label>Min Price <input type="number" id="min-price" name="min_price" value="' . esc_attr($min_price) . '" min="' . esc_attr($products_min_price) . '" max="' . esc_attr($products_max_price) . '" step="100"></label>';
        echo '<label>Max Price <input type="number" id="max-price" name="max_price" value="' . esc_attr($max_price) . '" min="' . esc_attr($products_min_price) . '" max="' . esc_attr($products_max_price) . '" step="100"></label>';
        echo '</div>';
        echo '<div id="price-range-slider"></div>';
        echo '<div id="price-range-display" class="price-range-display">R' . esc_html($min_price) . ' - R' . esc_html($max_price) . '</div>';
        echo '</li>';
        echo '</ul>';
        echo '</li>';
        
        // Collect all attributes used by these products
        $used_attributes = array();
        foreach ($products as $product) {
            $product_attributes = $product->get_attributes();
            foreach ($product_attributes as $attribute_name => $attribute) {
                if ($attribute->is_taxonomy()) {
                    $taxonomy = str_replace('pa_', '', $attribute_name);
                    $used_attributes[$taxonomy] = true;
                }
            }
        }
        
        // Get attribute taxonomies
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        
        if (!empty($attribute_taxonomies)) {
            foreach ($attribute_taxonomies as $attribute) {
                // Skip if this attribute is not used by any displayed products
                if (!isset($used_attributes[$attribute->attribute_name])) {
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

                    echo '<li class="dropdown-filter">';
                    echo '<div class="dropdown-header">' . esc_html($attribute->attribute_label) . ' <span class="dropdown-icon">▾</span></div>';
                    echo '<ul class="dropdown-content">';

                    foreach ($terms as $term) {
                        $is_checked = in_array($term->slug, $current_filters);
                        if(($hide!==($term->slug)) && ( 'viola-chan-premium'!==($term->slug) ))
                        {
                        echo '<li class="filter-checkbox-item">';
                        echo '<label>';
                        echo '<input type="checkbox" name="filter_' . esc_attr($attribute->attribute_name) . '[]" value="' . esc_attr($term->slug) . '"';
                        checked($is_checked);
                        echo ' class="filter-checkbox ajax-filter"> ';
                        echo esc_html($term->name);
                        echo '</label>';
                        echo '</li>';
                        }
                    }

                    echo '</ul>';
                    echo '</li>';
                }
            }
        }
        ?>
    </ul>

    <!-- Hidden fields to preserve existing non-filter query parameters -->
    <?php
    // Add any existing query parameters that should be preserved
    foreach ($_GET as $key => $value) {
        // Skip filter params as they will be handled by our checkboxes
        if (strpos($key, 'filter_') !== 0 && !in_array($key, array('min_price', 'max_price'))) {
            echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '">';
        }
    }
    ?>
    </form>
</div>

<!-- Loading indicator -->
<div class="loading-overlay"></div>
<div class="loading-spinner"></div>

<!-- Main Content -->
<div class="main-content">
  <!-- Catalog Header with Title and Sort -->
  <div class="catalog-header">
  <h1 class="catalog-title">New Arrival</h1>
  <div class="sort-dropdown"> 
    <div class="sort-dropdown-button">
      <?php 
      $current_orderby = isset($_GET['orderby']) ? $_GET['orderby'] : 'menu_order';
      $orderby_text = 'Most Popular';
      
      switch ($current_orderby) {
          case 'date':
              $orderby_text = 'Newest';
              break;
          case 'price':
              $orderby_text = 'Price low to high';
              break;
          case 'price-desc':
              $orderby_text = 'Price high to low';
              break;
      }
      
      echo esc_html($orderby_text); 
      ?><span>▾</span>
    </div>
    <div class="sort-dropdown-content">
      <a href="#" class="ajax-sort" data-orderby="menu_order">
        <?php echo esc_html__('Most Popular', 'woocommerce'); ?>
      </a>
      <a href="#" class="ajax-sort" data-orderby="date">
        <?php echo esc_html__('Newest', 'woocommerce'); ?>
      </a>
      <a href="#" class="ajax-sort" data-orderby="price">
        <?php echo esc_html__('Price low to high', 'woocommerce'); ?>
      </a>
      <a href="#" class="ajax-sort" data-orderby="price-desc">
        <?php echo esc_html__('Price high to low', 'woocommerce'); ?>
      </a>
    </div>
  </div>
</div>

  <!-- Display active filters as tags -->
  <div id="active-filters-container">
  <?php
  $has_active_filters = false;
  $active_filters = array();
  
  // Check for active filters
  foreach ($_GET as $key => $value) {
      if (strpos($key, 'filter_') === 0 && !empty($value)) {
          $has_active_filters = true;
          $attr_name = str_replace('filter_', '', $key);
          
          // Get attribute label
          $attribute_label = '';
          foreach ($attribute_taxonomies as $attribute) {
              if ($attribute->attribute_name === $attr_name) {
                  $attribute_label = $attribute->attribute_label;
                  break;
              }
          }
          
          // Handle comma-separated values
          $filter_values = explode(',', $value);
          foreach ($filter_values as $filter_value) {
              // Get term name
              $term = get_term_by('slug', $filter_value, wc_attribute_taxonomy_name($attr_name));
              if ($term) {
                  $active_filters[] = array(
                      'attr_name' => $attr_name,
                      'attr_label' => $attribute_label,
                      'value' => $filter_value,
                      'label' => $term->name
                  );
              }
          }
      }
  }
  
  // Handle price range filter
  if (isset($_GET['min_price']) || isset($_GET['max_price'])) {
      $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : $products_min_price;
      $max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : $products_max_price;
      
      if ($min_price > $products_min_price || $max_price < $products_max_price) {
          $has_active_filters = true;
          $active_filters[] = array(
              'attr_name' => 'price_range',
              'attr_label' => 'Price',
              'value' => $min_price . '-' . $max_price,
              'label' => 'R' . $min_price . ' - R' . $max_price
          );
      }
  }
  
  // Display active filters if any
  if ($has_active_filters) {
      echo '<div class="active-filters">';
      echo '<span class="active-filters-title">Active Filters:</span>';
      
      foreach ($active_filters as $filter) {
          echo '<span class="filter-tag">';
          echo esc_html($filter['attr_label'] . ': ' . $filter['label']);
          echo '<a href="#" class="remove-filter" data-type="' . esc_attr($filter['attr_name']) . '" data-value="' . esc_attr($filter['value']) . '">×</a>';
          echo '</span>';
      }
      
      // Clear all filters link
      echo '<a href="#" class="clear-all-filters">Clear All</a>';
      echo '</div>';
  }
  ?>
  </div>
<?php
//print_r($all_products);
      // Manually filter products created in the last 30 days
      $products = array_filter($all_products, function($product) {
        $created = $product->get_date_created();
        if (!$created) return false;
    
        $now = new DateTime();
        $interval = $now->diff($created);
    return true;
       // return $interval->days <= 30;
    });
    
    // Optionally slice down to 9 results
   // $products = array_slice($products, 0, 9);
    //print_r($products);

?>
  <!-- Product Grid -->
  <section class="container my-5">
    <div class="row g-4" id="products-container">
    <?php
$is_tag = isset($_GET['is_tag']) ? ($_GET['is_tag']) : '';
if($is_tag=='bride')
$tag_slug = 'brideco'; // replace with your actual tag slug
else
$tag_slug = 'eurosuit';

$args = array(
  'post_type'      => 'product',
  'posts_per_page' => 200, // or any number
  'post_status'    => 'publish',
  'orderby'        => 'date',
  'order'          => 'DESC',
  'date_query'     => array(
      array(
          'after'     => date('Y-m-d', strtotime('-30 days')),
          'inclusive' => true,
      ),
  ),
  'tax_query'      => array(
  array(
      'taxonomy' => 'product_tag',
      'field'    => 'slug',
      'terms'    => $tag_slug,
  )
),

);

$loop = new WP_Query( $args );
//echo '<pre>' . $loop->request . '</pre>';exit;
/*
if ( $loop->have_posts() ) {
  while ( $loop->have_posts() ) {
      $loop->the_post();

      $product = wc_get_product( get_the_ID() );

      if ( $product ) {
          echo "<div>#### " . $product->get_id() . "</div>";
      }
  }
  wp_reset_postdata();
}
*/

$val=1;
if ($val) {
        // Set posts per page to 9
            
        if ( $loop->have_posts() ) {
          while ( $loop->have_posts() ) {
            $loop->the_post();
                global $product;
                //echo "####".$product;
                // Skip if not a valid product
                if (!is_a($product, 'WC_Product')) {
                    continue;
                }
                
                // Get product data
                $product_id = $product->get_id();
                $regular_price = wc_get_price_to_display($product, array('price' => $product->get_regular_price()));
                $is_on_sale = $product->is_on_sale();
                $discount_percentage = 0;
                
                // Ensure we have a valid regular price
                if (empty($regular_price)) {
                    $regular_price = wc_get_price_to_display($product);
                }
                
                // Get sale price if product is on sale
                if ($is_on_sale) {
                    $sale_price = wc_get_price_to_display($product, array('price' => $product->get_sale_price()));
                    if ($regular_price > 0) {
                        $discount_percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
                    }
                } else {
                    $sale_price = $regular_price;
                }
                
                // First gallery image as hover image
                $attachment_ids = $product->get_gallery_image_ids();
                $hover_image = '';
                if (!empty($attachment_ids)) {
                    $hover_image = wp_get_attachment_image_url($attachment_ids[0], 'large');
                }
                
                // Check if product is in stock
                $in_stock = $product->is_in_stock();
                
                // Skip products that are out of stock or have zero price
                if (!$in_stock || $regular_price <= 0) {
                    continue;
                }
               // echo "%%%%". $date_created = $product->get_date_created();
                // Get brand or product code
                $brand = get_the_title();
                
                // Get product description
                $product_description = get_the_content();
                
                // Get dress name from technical spec or meta field
                $dress_name = '';
                
                // Check technical specification attribute
                $tech_spec_terms = get_the_terms($product_id, 'pa_technical-spec');
                if (!empty($tech_spec_terms) && !is_wp_error($tech_spec_terms)) {
                    $dress_name = $tech_spec_terms[0]->name;
                }
                
                // If tech spec not available, check meta_dress_name
                if (empty($dress_name)) {
                    $meta_dress_name = get_post_meta($product_id, 'meta_dress_name', true);
                    // Only use meta_dress_name if it's not the placeholder
                    if (!empty($meta_dress_name) && $meta_dress_name !== '{{product.meta_dress name}}') {
                        $dress_name = $meta_dress_name;
                    }
                }
                
                // Get product brand from pa_brand taxonomy
                $product_brand = '';
                $brand_terms = get_the_terms($product_id, 'pa_brand');
                if (!empty($brand_terms) && !is_wp_error($brand_terms)) {
                    $product_brand = $brand_terms[0]->name;
                }
                if($product_brand=='Eurosuit')
                {
                  continue;
                }

                $is_new = function_exists('is_product_new') ? is_product_new($product_id) : false; 
                $is_eurosuite = function_exists('is_eurosuit_page') ? is_eurosuit_page() : false;    
                if($is_eurosuite)
                $newlabel_style ='new-badge-euro'; 
                ?>
                
                <div class="col-md-4">
                    <a href="<?php echo esc_url(get_permalink()); ?>" class="product-card-link">
                        <div class="product-card">
                            <div class="image-container">
                            <?php
                              if($is_new)
                              {
                                ?>
                                <span class="label new-label <?php echo $newlabel_style;?>">NEW</span>
                              <?php
                            }
                            ?>
                                
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
        }
        
        // Restore default hooks
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
</section>

<?php
// Create a nonce for security
$ajax_nonce = wp_create_nonce('product_filter_nonce');
?>

<script>
// Define AJAX URL and nonce for use in the JavaScript
var wc_ajax_object = {
    ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>',
    nonce: '<?php echo $ajax_nonce; ?>'
};

// Sidebar and Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
  // Price Range Slider Initialization
  const minInput = document.getElementById('min-price');
  const maxInput = document.getElementById('max-price');
  const slider = document.getElementById('price-range-slider');

  if (slider && typeof noUiSlider !== 'undefined') {
    // Get dynamic price range from meta tags
    const priceMinMeta = document.querySelector('meta[name="price-min"]');
    const priceMaxMeta = document.querySelector('meta[name="price-max"]');
    
    const priceMin = priceMinMeta ? parseInt(priceMinMeta.getAttribute('content')) : 0;
    const priceMax = priceMaxMeta ? parseInt(priceMaxMeta.getAttribute('content')) : 10000;
    
    // Use user-selected values or fall back to the calculated range
    const minVal = Math.max(priceMin, parseInt(minInput.value) || priceMin);
    const maxVal = Math.min(priceMax, parseInt(maxInput.value) || priceMax);

    noUiSlider.create(slider, {
      start: [minVal, maxVal],
      connect: true,
      step: Math.max(100, Math.floor((priceMax - priceMin) / 20)), // Dynamic step based on range
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
            // Adjust formatting based on the price range
            if (priceMax >= 10000) {
              return 'R' + Math.round(value / 1000) + 'k';
            } else {
              return 'R' + Math.round(value);
            }
          },
          from: value => value
        }
      }
    });

    slider.noUiSlider.on('update', function (values, handle) {
      minInput.value = Math.round(values[0]);
      maxInput.value = Math.round(values[1]);
      
      // Update visible price range display
      const priceRangeDisplay = document.getElementById('price-range-display');
      if (priceRangeDisplay) {
        priceRangeDisplay.textContent = 'R' + Math.round(values[0]) + ' - R' + Math.round(values[1]);
      }
    });

    // Execute filter on slider change (with debounce)
    let timer;
    slider.noUiSlider.on('change', function() {
      clearTimeout(timer);
      timer = setTimeout(function() {
        executeFilter();
      }, 500);
    });

    // Allow manual input updates
    minInput.addEventListener('change', function () {
      const val = Math.max(priceMin, Math.min(priceMax, parseInt(this.value) || priceMin));
      this.value = val;
      slider.noUiSlider.set([val, null]);
      executeFilter();
    });

    maxInput.addEventListener('change', function () {
      const val = Math.max(priceMin, Math.min(priceMax, parseInt(this.value) || priceMax));
      this.value = val;
      slider.noUiSlider.set([null, val]);
      executeFilter();
    });
  }

  // Sidebar Toggle Functionality
  const sidebarToggle = document.querySelector('.sidebar-toggle');
  const sidebar = document.querySelector('.sidebar');
  const sidebarOverlay = document.querySelector('.sidebar-overlay');
  
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function() {
      sidebar.classList.toggle('active');
      if (sidebarOverlay) {
        sidebarOverlay.classList.toggle('active');
      }
    });
  }
  
  // Close sidebar when clicking on the overlay
  if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', function() {
      sidebar.classList.remove('active');
      sidebarOverlay.classList.remove('active');
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
    
    // Handle sort option selection
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
        
        // Run filter with new sort
        executeFilter(orderBy);
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
        // Get price range values
        const priceMinMeta = document.querySelector('meta[name="price-min"]');
        const priceMaxMeta = document.querySelector('meta[name="price-max"]');
        
        const priceMin = priceMinMeta ? parseInt(priceMinMeta.getAttribute('content')) : 0;
        const priceMax = priceMaxMeta ? parseInt(priceMaxMeta.getAttribute('content')) : 10000;
        
        // Reset price inputs
        document.getElementById('min-price').value = priceMin;
        document.getElementById('max-price').value = priceMax;
        if (slider && typeof noUiSlider !== 'undefined') {
          slider.noUiSlider.set([priceMin, priceMax]);
        }
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
  
  // Clear all filters
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('clear-all-filters')) {
      e.preventDefault();
      
      // Uncheck all checkboxes
      document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
        checkbox.checked = false;
      });
      
      // Get price range values from meta tags
      const priceMinMeta = document.querySelector('meta[name="price-min"]');
      const priceMaxMeta = document.querySelector('meta[name="price-max"]');
      
      const priceMin = priceMinMeta ? parseInt(priceMinMeta.getAttribute('content')) : 0;
      const priceMax = priceMaxMeta ? parseInt(priceMaxMeta.getAttribute('content')) : 10000;
      
      // Reset price range
      document.getElementById('min-price').value = priceMin;
      document.getElementById('max-price').value = priceMax;
      if (slider && typeof noUiSlider !== 'undefined') {
        slider.noUiSlider.set([priceMin, priceMax]);
      }
      
      // Run filter
      executeFilter();
    }
  });
  
  // Main filter execution function
  function executeFilter(orderBy) {
    // Show loading state
    document.querySelector('.loading-overlay').style.display = 'block';
    document.querySelector('.loading-spinner').style.display = 'block';
    
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
    
    // Get price range
    const minPrice = document.getElementById('min-price').value;
    const maxPrice = document.getElementById('max-price').value;
    
    // Prepare form data for AJAX
    const formData = new FormData();
    formData.append('action', 'filter_products_newarrival');
    formData.append('nonce', wc_ajax_object.nonce);
    
    // Add attributes
    formData.append('attributes', JSON.stringify(attributes));
    
    // Add price range
    formData.append('min_price', minPrice);
    formData.append('max_price', maxPrice);
    
    // Get current category
    const currentCategoryMeta = document.querySelector('meta[name="current-category"]');
    if (currentCategoryMeta) {
      formData.append('current_category', currentCategoryMeta.getAttribute('content'));
    }
    
    // Add sort order
    if (orderBy) {
      formData.append('orderby', orderBy);
    } else {
      const sortButton = document.querySelector('.sort-dropdown-button');
      if (sortButton) {
        const text = sortButton.textContent.trim();
        if (text.includes('low to high')) {
          formData.append('orderby', 'price');
        } else if (text.includes('high to low')) {
          formData.append('orderby', 'price-desc');
        } else if (text.includes('Newest')) {
          formData.append('orderby', 'date');
        } else {
          formData.append('orderby', 'menu_order'); // Default / Most Popular
        }
      }
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
        
        // Update URL for browser history (without reload)
        updateBrowserURL(attributes, minPrice, maxPrice, orderBy);
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
  
  // Helper function to update browser URL without page reload
  function updateBrowserURL(attributes, minPrice, maxPrice, orderBy) {
    const urlParams = new URLSearchParams(window.location.search);
    
    // Remove existing filter parameters
    Array.from(urlParams.keys()).forEach(key => {
      if (key.startsWith('filter_') || key === 'min_price' || key === 'max_price' || key === 'orderby') {
        urlParams.delete(key);
      }
    });
    
    // Add attributes
    Object.keys(attributes).forEach(key => {
      if (attributes[key]) {
        urlParams.set('filter_' + key, attributes[key]);
      }
    });
    
    // Get price range values from meta tags
    const priceMinMeta = document.querySelector('meta[name="price-min"]');
    const priceMaxMeta = document.querySelector('meta[name="price-max"]');
    
    const priceMin = priceMinMeta ? parseInt(priceMinMeta.getAttribute('content')) : 0;
    const priceMax = priceMaxMeta ? parseInt(priceMaxMeta.getAttribute('content')) : 10000;
    
    // Add price range if different from default
    if (parseInt(minPrice) > priceMin) urlParams.set('min_price', minPrice);
    if (parseInt(maxPrice) < priceMax) urlParams.set('max_price', maxPrice);
    
    // Add sort order
    if (orderBy) urlParams.set('orderby', orderBy);
    
    // Update URL
    const newURL = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
    window.history.pushState({path: newURL}, '', newURL);
  }
});
</script>

<?php get_footer('shop'); ?>