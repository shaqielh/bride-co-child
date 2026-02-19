<?php
/**
 * Custom Search Results Template for Products Only
 * File: search.php
 */
get_header(); 

// Modify the main query to show only products
global $wp_query;
$wp_query = new WP_Query(array(
    's' => get_search_query(),
    'post_type' => 'product',
    'posts_per_page' => 9
));
?>

<div class="search-results-container">
    <div class="container">
        <!-- Search Results Header -->
        <div class="search-results-header">
            <h1 class="search-title">
                <?php printf( __( 'Search Results for: %s', 'bride-co-child' ), '<span>' . get_search_query() . '</span>' ); ?>
            </h1>
            
            <!-- Search Form Inline -->
            <div class="search-form-inline">
                <?php get_search_form(); ?>
            </div>
        </div>

        <!-- Results Grid -->
        <div class="search-results-grid">
            <?php 
            // Check if there are search results
            if ( $wp_query->have_posts() ) : 
            ?>
                <section class="container my-5">
                    <div class="row g-4">
                        <?php 
                        while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
                        
                        // Ensure we're working with a product
                        global $product;
                        $product = wc_get_product( get_the_ID() );
                        
                        // Skip products that are out of stock or have zero price
                        if (!$product->is_in_stock() || $product->get_price() <= 0) {
                            continue;
                        }
                        
                        // Get product data
                        $regular_price = wc_get_price_to_display($product, array('price' => $product->get_regular_price()));
                        $is_on_sale = $product->is_on_sale();
                        $discount_percentage = 0;
                        
                        // Calculate discount percentage
                        if ($is_on_sale) {
                            $sale_price = wc_get_price_to_display($product, array('price' => $product->get_sale_price()));
                            if ($regular_price > 0) {
                                $discount_percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
                            }
                        }
                        
                        // First gallery image as hover image
                        $attachment_ids = $product->get_gallery_image_ids();
                        $hover_image = '';
                        if (!empty($attachment_ids)) {
                            $hover_image = wp_get_attachment_image_url($attachment_ids[0], 'large');
                        }
                        
                        // Get brand or product code
                        $brand = get_the_title();
                        
                        // Get dress name from technical spec or meta field
                        $dress_name = '';
                        $tech_spec_terms = get_the_terms(get_the_ID(), 'pa_technical-spec');
                        if (!empty($tech_spec_terms) && !is_wp_error($tech_spec_terms)) {
                            $dress_name = $tech_spec_terms[0]->name;
                        }
                        
                        // If tech spec not available, check meta_dress_name
                        if (empty($dress_name)) {
                            $meta_dress_name = get_post_meta(get_the_ID(), 'meta_dress_name', true);
                            if (!empty($meta_dress_name) && $meta_dress_name !== '{{product.meta_dress name}}') {
                                $dress_name = $meta_dress_name;
                            }
                        }
                        
                        // Get product brand from pa_brand taxonomy
                        $product_brand = '';
                        $brand_terms = get_the_terms(get_the_ID(), 'pa_brand');
                        if (!empty($brand_terms) && !is_wp_error($brand_terms)) {
                            $product_brand = $brand_terms[0]->name;
                        }
                        ?>
                        
                        <div class="col-md-4">
                            <a href="<?php echo esc_url(get_permalink()); ?>" class="product-card-link">
                                <div class="product-card">
                                    <div class="image-container">
                                        <span class="label new-label">NEW</span>
                                        
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
                                    
                                    <?php if (get_the_content()) : ?>
                                        <p class="product-description"><?php echo wp_kses_post(get_the_content()); ?></p>
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
                        endwhile; 
                        wp_reset_postdata(); // Reset the post data
                        ?>
                    </div>
                </section>

                <!-- Pagination -->
                <div class="search-results-pagination container">
                    <?php 
                    the_posts_pagination( array(
                        'mid_size'  => 2,
                        'prev_text' => __( '&laquo; Previous', 'bride-co-child' ),
                        'next_text' => __( 'Next &raquo;', 'bride-co-child' ),
                    ) ); 
                    ?>
                </div>

            <?php else : ?>
                <!-- No Results Found -->
                <div class="no-search-results container">
                    <h2><?php _e( 'No Products Found', 'bride-co-child' ); ?></h2>
                    <p><?php _e( 'Sorry, but no products matched your search terms. Please try again with different keywords.', 'bride-co-child' ); ?></p>
                    
                    <!-- Suggest Popular Products -->
                    <?php 
                    $popular_products = wc_get_products( array(
                        'status'    => 'publish',
                        'limit'     => 4,
                        'orderby'   => 'popularity'
                    ) );

                    if ( !empty($popular_products) ) : ?>
                        <div class="suggested-products">
                            <h3>Popular Products You Might Like</h3>
                            <section class="container">
                                <div class="row g-4">
                                    <?php foreach ( $popular_products as $product ) : 
                                    // Skip products that are out of stock or have zero price
                                    if (!$product->is_in_stock() || $product->get_price() <= 0) {
                                        continue;
                                    }
                                    ?>
                                        <div class="col-md-4">
                                            <a href="<?php echo esc_url($product->get_permalink()); ?>" class="product-card-link">
                                                <div class="product-card">
                                                    <div class="image-container">
                                                        <?php 
                                                        $image_url = $product->get_image_id() ? wp_get_attachment_image_url($product->get_image_id(), 'large') : wc_placeholder_img_src('large');
                                                        ?>
                                                        <img src="<?php echo esc_url($image_url); ?>" 
                                                            class="product-image" alt="<?php echo esc_attr($product->get_name()); ?>" />
                                                        
                                                        <span class="add-to-cart-btn">SHOP NOW</span>
                                                    </div>
                                                    
                                                    <h5 class="mt-3 fw-bold"><?php echo esc_html($product->get_name()); ?></h5>
                                                    
                                                    <p class="product-pricing">
                                                        <?php echo $product->get_price_html(); ?>
                                                    </p>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Include product archive styles -->
<style>
<?php include_once(get_stylesheet_directory() . '/style.css'); ?>
</style>

<?php get_footer(); ?>