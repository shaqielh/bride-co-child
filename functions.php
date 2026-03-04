<?php 
/**
 * Bride & Co Child Theme Functions
 *
 * @package bride-co-child
 */

// Enqueue parent theme style
add_action( 'wp_enqueue_scripts', 'bride_co_child_enqueue_styles' );
function bride_co_child_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' ); 
}

/**
 * Register Bootstrap Nav Walker
 */
if (!class_exists('WP_Bootstrap_Navwalker')) {
    require_once get_stylesheet_directory() . '/class-wp-bootstrap-navwalker.php';
}

/**
 * Register menu locations
 */
function bride_co_register_menus() {
    register_nav_menus(
        array(
            'primary-menu' => __( 'Primary Menu', 'bride-co-child' ),
            'footer-menu'  => __( 'Footer Menu', 'bride-co-child' ),
            'top-bar-menu' => __( 'Top Bar Menu', 'bride-co-child' ),
        )
    );
}
add_action( 'init', 'bride_co_register_menus' );

/**
 * Register scripts and styles
 */
function bride_co_scripts() {
    // Bootstrap CSS
    wp_enqueue_style(
        'bootstrap-css',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
        array(),
        '5.3.0'
    );
    
    // Bootstrap Icons
    wp_enqueue_style(
        'bootstrap-icons',
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css',
        array(),
        '1.10.5'
    );
    
    // Google Fonts
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
        array(),
        null
    );
    
    // Font Awesome for product icons
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css',
        array(),
        '6.4.2'
    );
    
    // Theme main stylesheet
    wp_enqueue_style(
        'brideco-style',
        get_stylesheet_directory_uri() . '/assets/styles.css',
        array('bootstrap-css'),
        '1.0.0'
    );
    
    // Bootstrap JS
    wp_enqueue_script(
        'bootstrap-js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
        array('jquery'),
        '5.3.0',
        true
    );
    
}
add_action( 'wp_enqueue_scripts', 'bride_co_scripts' );

/**
 * Add theme support
 */
function bride_co_theme_setup() {
    // Add theme support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 250,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Add WooCommerce support
    add_theme_support('woocommerce');
    
    // Add featured image support
    add_theme_support('post-thumbnails');
    
    // Add title tag support
    add_theme_support('title-tag');
    
    // Add HTML5 support
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
}
add_action('after_setup_theme', 'bride_co_theme_setup');

/**
 * Add Customizer options
 */
function bride_co_customize_register($wp_customize) {
    // Add section for promo text
    $wp_customize->add_section('bride_co_promo_section', array(
        'title'    => __('Promo Bar', 'bride-co-child'),
        'priority' => 30,
    ));
    
    // Add setting for promo text
    $wp_customize->add_setting('promo_text', array(
        'default'           => 'Online purchasing now available! Shop your dream dresses| Find "The One" | Up to 30% Off Wedding Dresses',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    // Add control for promo text
    $wp_customize->add_control('promo_text', array(
        'label'    => __('Promo Text', 'bride-co-child'),
        'section'  => 'bride_co_promo_section',
        'type'     => 'textarea',
    ));

// Add setting for Eurosuit promo text
$wp_customize->add_setting('promo_text_euro', array(
    'default'           => 'Suits and Tuxedos from R5999 | Get R1200 off your Complete Look | Buy. Hire. Accessorise. All Online | Secure Checkout. Fast Delivery. No Fuss.',
    'sanitize_callback' => 'sanitize_text_field',
));

// Add control for Eurosuit promo text
$wp_customize->add_control('promo_text_euro', array(
    'label'    => __('Eurosuit Promo Text', 'bride-co-child'),
    'section'  => 'bride_co_promo_section',
    'type'     => 'textarea',
));
    
    // Add section for social media links
    $wp_customize->add_section('bride_co_social_section', array(
        'title'    => __('Social Media Links', 'bride-co-child'),
        'priority' => 35,
    ));
    
    // Add settings and controls for social media links
    $social_platforms = array(
        'facebook'  => __('Facebook URL', 'bride-co-child'),
        'instagram' => __('Instagram URL', 'bride-co-child'),
        'pinterest' => __('Pinterest URL', 'bride-co-child'),
        'tiktok'    => __('TikTok URL', 'bride-co-child'),
    );
    
    foreach ($social_platforms as $platform => $label) {
        $wp_customize->add_setting('social_' . $platform, array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control('social_' . $platform, array(
            'label'    => $label,
            'section'  => 'bride_co_social_section',
            'type'     => 'url',
        ));
    }
    
    // Add section for Eurosuit logo
    $wp_customize->add_section('eurosuit_logo_section', array(
        'title'    => __('Eurosuit Logo', 'bride-co-child'),
        'priority' => 40,
    ));

    // Add setting for Eurosuit logo
    $wp_customize->add_setting('eurosuit_logo', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
    ));

    // Add control for Eurosuit logo
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eurosuit_logo', array(
        'label'       => __('Eurosuit Logo', 'bride-co-child'),
        'section'     => 'eurosuit_logo_section',
        'mime_type'   => 'image',
        'description' => __('Upload the Eurosuit logo', 'bride-co-child'),
    )));
}
add_action('customize_register', 'bride_co_customize_register');

/**
 * Enable WooCommerce support in your theme
 */
function bride_co_woocommerce_support() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'bride_co_woocommerce_support');

/**
 * Modify WordPress search form to match Bootstrap styling
 */
function bride_co_search_form($form) {
    $form = '<form role="search" method="get" id="searchform" class="searchform" action="' . home_url('/') . '" >
    <div class="form-group">
        <input type="text" value="' . get_search_query() . '" name="s" id="s" class="form-control" placeholder="' . esc_attr__('Search...', 'bride-co-child') . '" />
        <input type="submit" id="searchsubmit" class="btn d-none" value="' . esc_attr__('Search', 'bride-co-child') . '" />
    </div>
    </form>';
    
    return $form;
}
add_filter('get_search_form', 'bride_co_search_form');

/**
 * Remove "Category:", "Tag:", "Author:" from archive titles
 */
function bride_co_archive_title($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = get_the_author();
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    } elseif (is_tax()) {
        $title = single_term_title('', false);
    }
    
    return $title;
}
function hexToRgbaString($hex) {
    $hex = ltrim($hex, '#');

    // Expand shorthand like #fff to full form #ffffff
    if (strlen($hex) === 3) {
        $hex = $hex[0].$hex[0] . $hex[1].$hex[1] . $hex[2].$hex[2];
    }

    // Default alpha if not provided
    if (strlen($hex) === 6) {
        $hex .= 'FF';
    }

    // Extract RGBA components
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    $a = 0.7; // Alpha in 0-1 format

    return "rgba($r, $g, $b, $a)";
}


add_filter('get_the_archive_title', 'bride_co_archive_title');

/**
 * Customize excerpt length
 */
function bride_co_excerpt_length($length) {
    return 20; // Number of words
}
add_filter('excerpt_length', 'bride_co_excerpt_length');

/**
 * Customize excerpt more string
 */
function bride_co_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'bride_co_excerpt_more');

/**
 * Add body classes based on page template
 */
function bride_co_body_classes($classes) {
    // Add page slug as class
    if (is_singular()) {
        global $post;
        $classes[] = 'page-' . $post->post_name;
    }
    
    // Add class if WooCommerce is active
    if (class_exists('WooCommerce')) {
        $classes[] = 'woocommerce-active';
        
        if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
            $classes[] = 'woocommerce-page';
        }
    }
    
    return $classes;
}
add_filter('body_class', 'bride_co_body_classes');

/**
 * AJAX Search Function for Posts and Products - Optimized
 */
/**
 * AJAX Search Function for Products Only - Optimized
 */
function bride_co_ajax_search() {
    // Check nonce for security
    check_ajax_referer('ajax_search_nonce', 'nonce');
    
    // Get and sanitize the search term
    $search_term = sanitize_text_field($_POST['search']);
    
    // Prepare results container for products
    $results = array(
        'products' => array()
    );
    
    // Cache key for transients
    $cache_key = 'search_products_' . md5($search_term);
    
    // Try to get cached results first
    $cached_results = get_transient($cache_key);
    if ($cached_results !== false) {
        wp_send_json_success($cached_results);
        return;
    }
    
    // Search products if WooCommerce is active - Optimized query
    if (class_exists('WooCommerce')) {
        $product_args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            's' => $search_term,
            'posts_per_page' => 5,
            'fields' => 'ids', // Only get post IDs for better performance
            'no_found_rows' => true, // Skip pagination counts for speed
            'update_post_meta_cache' => false, // Don't update meta cache
            'update_post_term_cache' => false // Don't update term cache
        );
        
        $product_query = new WP_Query($product_args);
        
        if ($product_query->have_posts()) {
            foreach ($product_query->posts as $product_id) {
                $product = wc_get_product($product_id);
                if (!$product) continue;
                
                // Only get essential data
                $image_id = get_post_thumbnail_id($product_id);
                $image = $image_id ? wp_get_attachment_image_src($image_id, 'thumbnail')[0] : '';
                
                // Build minimal result set
                $results['products'][] = array(
                    'id' => $product_id,
                    'title' => $product->get_name(),
                    'url' => get_permalink($product_id),
                    'image' => $image,
                    'price' => $product->get_price_html()
                );
            }
        }
    }
    
    // Cache results for 1 hour (adjust time as needed)
    set_transient($cache_key, $results, HOUR_IN_SECONDS);
    
    // Send results as JSON
    wp_send_json_success($results);
}
add_action('wp_ajax_ajax_search', 'bride_co_ajax_search');
add_action('wp_ajax_nopriv_ajax_search', 'bride_co_ajax_search');

/**
 * Register footer menus
 */
function brideandco_register_footer_menus() {
    register_nav_menus(array(
        'footer-menu-1' => __('Footer Menu 1 (Categories)', 'brideandco'),
        'footer-menu-2' => __('Footer Menu 2 (About)', 'brideandco'),
        'footer-menu-3' => __('Footer Menu 3 (Help)', 'brideandco')
    ));
}
add_action('after_setup_theme', 'brideandco_register_footer_menus');

/**
 * Enqueue Font Awesome
 */
function brideandco_enqueue_font_awesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css', array(), '6.4.2');
}
add_action('wp_enqueue_scripts', 'brideandco_enqueue_font_awesome');

/**
 * Add footer customizer options
 */
function brideandco_footer_customize_register($wp_customize) {
    // Footer Section
    $wp_customize->add_section('footer_section', array(
        'title'    => __('Footer Settings', 'brideandco'),
        'priority' => 40,
    ));
    
    // Footer Logo
    $wp_customize->add_setting('footer_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'footer_logo', array(
        'label'   => __('Footer Logo', 'brideandco'),
        'section' => 'footer_section',
    )));
    
    // Column Titles
    $column_titles = array(
        'footer_column1_title' => __('Column 1 Title', 'brideandco'),
        'footer_column2_title' => __('Column 2 Title', 'brideandco'),
        'footer_column3_title' => __('Column 3 Title', 'brideandco'),
        'footer_column4_title' => __('Column 4 Title', 'brideandco'),
    );
    
    foreach ($column_titles as $setting_id => $label) {
        $wp_customize->add_setting($setting_id, array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control($setting_id, array(
            'label'   => $label,
            'section' => 'footer_section',
            'type'    => 'text',
        ));
    }
    
    // Subscribe Text
    $wp_customize->add_setting('footer_subscribe_text', array(
        'default'           => 'By subscribing to our newsletter, you can be the first to learn about the latest news, new campaigns, and discounts.',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('footer_subscribe_text', array(
        'label'   => __('Subscribe Text', 'brideandco'),
        'section' => 'footer_section',
        'type'    => 'textarea',
    ));
    
    // Subscribe Button Text
    $wp_customize->add_setting('footer_subscribe_button', array(
        'default'           => 'SIGN UP',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('footer_subscribe_button', array(
        'label'   => __('Subscribe Button Text', 'brideandco'),
        'section' => 'footer_section',
        'type'    => 'text',
    ));
    
    // Subscribe Consent Text
    $wp_customize->add_setting('footer_subscribe_consent', array(
        'default'           => 'I have read and accept the Commercial Electronic Message Approval text.',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('footer_subscribe_consent', array(
        'label'   => __('Subscribe Consent Text', 'brideandco'),
        'section' => 'footer_section',
        'type'    => 'textarea',
    ));
    
    // Social Media Links
    $social_links = array(
        'social_facebook'  => __('Facebook URL', 'brideandco'),
        'social_instagram' => __('Instagram URL', 'brideandco'),
        'social_pinterest' => __('Pinterest URL', 'brideandco'),
        'social_youtube'   => __('YouTube URL', 'brideandco'),
        'social_tiktok'    => __('TikTok URL', 'brideandco'),
    );
    
    foreach ($social_links as $setting_id => $label) {
        $wp_customize->add_setting($setting_id, array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control($setting_id, array(
            'label'   => $label,
            'section' => 'footer_section',
            'type'    => 'url',
        ));
    }
    
    // Payment Icons
    $payment_methods = array(
        'payment_visa'       => __('Visa Icon', 'brideandco'),
        'payment_mastercard' => __('Mastercard Icon', 'brideandco'),
        'payment_maestro'    => __('Maestro Icon', 'brideandco'),
        'payment_amex'       => __('Amex Icon', 'brideandco'),
    );
    
    foreach ($payment_methods as $setting_id => $label) {
        $wp_customize->add_setting($setting_id, array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, $setting_id, array(
            'label'   => $label,
            'section' => 'footer_section',
        )));
    }
    
    // Copyright Text
    $wp_customize->add_setting('copyright_text', array(
        'default'           => '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All Rights Reserved.',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('copyright_text', array(
        'label'   => __('Copyright Text', 'brideandco'),
        'section' => 'footer_section',
        'type'    => 'textarea',
    ));
}
add_action('customize_register', 'brideandco_footer_customize_register');

/**
 * Handle newsletter subscription form
 */
function brideandco_handle_subscription() {
    // Check nonce for security
    if (!isset($_POST['subscribe_nonce']) || !wp_verify_nonce($_POST['subscribe_nonce'], 'subscribe_action_nonce')) {
        wp_die(__('Security check failed.', 'brideandco'));
    }

    // Get email from form
    $email = isset($_POST['subscriber_email']) ? sanitize_email($_POST['subscriber_email']) : '';
    
    if (!is_email($email)) {
        wp_die(__('Invalid email address.', 'brideandco'));
    }
    
    // Store the email (you might want to integrate with a newsletter service here)
    // For now, just store as a custom option
    $current_subscribers = get_option('brideandco_newsletter_subscribers', array());
    
    if (!in_array($email, $current_subscribers)) {
        $current_subscribers[] = $email;
        update_option('brideandco_newsletter_subscribers', $current_subscribers);
        
        // Optionally send notification email to admin
        wp_mail(
            get_option('admin_email'),
            __('New Newsletter Subscriber', 'brideandco'),
            sprintf(__('New subscriber: %s', 'brideandco'), $email)
        );
    }
    
    // Redirect back to the previous page
    wp_safe_redirect(wp_get_referer() . '?subscription=success');
    exit;
}
add_action('admin_post_subscribe_action', 'brideandco_handle_subscription');
add_action('admin_post_nopriv_subscribe_action', 'brideandco_handle_subscription');

/**
 * Show subscription confirmation message
 */
function brideandco_subscription_notice() {
    if (isset($_GET['subscription']) && $_GET['subscription'] == 'success') {
        echo '<div class="subscription-success-message">';
        echo __('Thank you for subscribing to our newsletter!', 'brideandco');
        echo '</div>';
    }
}
add_action('wp_body_open', 'brideandco_subscription_notice');


/**
 * Bride & Co - Simplified Slider Solution with ACF Post Object Field Fix
 * 
 * Usage: [bride_slider id="123"] 
 * No need to specify post type, just use the ID
 */

/**
 * ACF Post Object Field Fix
 * Resolves issues with the post selection dropdown not loading properly
 */
function bride_co_fix_acf_post_object_fields() {
    // Only apply in admin
    if (!is_admin()) {
        return;
    }
    
    // Increase server resources for admin area
    @ini_set('memory_limit', '256M');
    @ini_set('max_execution_time', 120);
    
    // Register fixes for post object fields
    add_filter('acf/fields/post_object/query', 'bride_co_optimize_post_query', 10, 3);
    add_action('acf/input/admin_enqueue_scripts', 'bride_co_fix_select2_scripts', 5);
    
    // Cleanup option table periodically
    if (!wp_next_scheduled('bride_co_db_cleanup')) {
        wp_schedule_event(time(), 'daily', 'bride_co_db_cleanup');
    }
    add_action('bride_co_db_cleanup', 'bride_co_cleanup_database');
}
add_action('init', 'bride_co_fix_acf_post_object_fields');

/**
 * Optimize query parameters for post object fields
 */
function bride_co_optimize_post_query($args, $field, $post_id) {
    // Increase posts_per_page for more options
    $args['posts_per_page'] = 150;
    
    // Only get IDs for better performance
    $args['fields'] = 'ids';
    
    // Skip pagination calculations
    $args['no_found_rows'] = true;
    
    // Skip unnecessary metadata
    $args['update_post_meta_cache'] = false;
    $args['update_post_term_cache'] = false;
    
    // Clear search parameter if empty
    if (empty($_POST['s'])) {
        unset($args['s']);
    }
    
    return $args;
}

/**
 * Fix select2 script loading for ACF
 */
function bride_co_fix_select2_scripts() {
    // Deregister potentially problematic select2 script
    wp_deregister_script('select2');
    
    // Register known working version
    wp_register_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), '4.0.13');
    wp_enqueue_script('select2');
    
    // Add select2 styles
    wp_enqueue_style('select2-css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', array(), '4.0.13');
    
    // Add custom fixes for select2
    wp_add_inline_style('acf-input', '
        .select2-container { min-width: 100px !important; }
        .select2-dropdown { z-index: 999999 !important; }
        .select2-results__option { padding: 8px !important; }
        .select2-container--default .select2-results > .select2-results__options { max-height: 400px !important; }
    ');
    
    // Add JS to force refresh select2 fields
    wp_add_inline_script('acf-input', '
        jQuery(document).ready(function($) {
            // Force refresh select2 fields after page loads
            setTimeout(function() {
                $(".acf-field-post-object select").each(function(){
                    $(this).trigger("change");
                });
            }, 500);
            
            // Fix dropdown positioning issues
            $(document).on("select2:open", function() {
                $(".select2-container--open .select2-dropdown").css("z-index", "999999");
            });
        });
    ');
}

/**
 * Database cleanup function to improve performance
 */
function bride_co_cleanup_database() {
    global $wpdb;
    
    // Clear transients which might contain corrupt select data
    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '%_transient_%'");
    
    // Clear ACF field caches
    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '%acf_cache%'");
}

/**
 * Simplified Slider Shortcode - Only Requires ID
 * 
 * Usage: [bride_slider id="123"]
 */
function bride_co_slider_shortcode($atts) {
    // Default attributes - ID is the main parameter now
    $atts = shortcode_atts(array(
        'id' => '',               // Required slider ID
        'location' => 'home',     // Default location to display
        'height' => '500px',      // Carousel height
        'interval' => '5000',     // Slide interval in milliseconds
        'indicators' => 'true',   // Show carousel indicators
        'controls' => 'true',     // Show carousel controls
        'fade' => 'false',        // Use fade transition
    ), $atts);
    
    // If no ID provided, return error message
    if (empty($atts['id'])) {
        return '<p class="error">Error: Slider ID is required. Please use [bride_slider id="123"]</p>';
    }
    
    // Get current post ID for comparison
    $current_post_id = get_the_ID();
    
    // Start output buffer
    ob_start();
    
    // Add base CSS - completely redesigned to avoid flicker
    echo '<style>
        /* Base container */
        .bride-slider-container {
            position: relative;
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        /* Slider core */
        .bride-slider {
            position: relative;
            width: 100%;
            overflow: hidden;
            height: ' . esc_attr($atts['height']) . ';
            -webkit-backface-visibility: hidden;
            -moz-backface-visibility: hidden;
            -ms-backface-visibility: hidden;
            backface-visibility: hidden;
            -webkit-perspective: 1000;
            -moz-perspective: 1000;
            -ms-perspective: 1000;
            perspective: 1000;
            -webkit-transform: translate3d(0,0,0);
            -ms-transform: translate3d(0,0,0);
            transform: translate3d(0,0,0);
            -webkit-transform-style: preserve-3d;
            -ms-transform-style: preserve-3d;
            transform-style: preserve-3d;
        }
        
        /* Slide items */
        .bride-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            transition: opacity 0.8s ease;
            z-index: 1;
            padding: 0;
            margin: 0;
            -webkit-transform: translate3d(0,0,0);
            -ms-transform: translate3d(0,0,0);
            transform: translate3d(0,0,0);
            will-change: opacity, transform;
        }
        
        /* Active slide */
        .bride-slide.active {
            opacity: 1;
            z-index: 2;
        }
        
        /* Overlay */
        .bride-slide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        
        /* Content wrapper */
        .bride-slide-content-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            z-index: 2;
        }
        
        /* Content */
        .bride-slide-content {
            padding: 30px;
            z-index: 2;
			/*background-color: rgba(255, 255, 255, 0.8);*/
			padding: 25px;
			border-radius: 10px;
			max-width: 450px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        
        /* Controls */
        .bride-slider-prev,
        .bride-slider-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            cursor: pointer;
            z-index: 10;
            border: none;
            outline: none;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }
        
        .bride-slider-prev:hover, 
        .bride-slider-next:hover {
            opacity: 1;
        }
        
        .bride-slider-prev {
            left: 20px;
        }
        
        .bride-slider-next {
            right: 20px;
        }
        
        .bride-slider-control-icon {
            width: 20px;
            height: 20px;
            position: relative;
        }
        
        .bride-slider-control-icon:before,
        .bride-slider-control-icon:after {
            content: "";
            position: absolute;
            top: 50%;
            width: 15px;
            height: 2px;
            background-color: #333;
        }
        
        .bride-slider-prev .bride-slider-control-icon:before {
            transform: translate(0, -4px) rotate(-45deg);
        }
        
        .bride-slider-prev .bride-slider-control-icon:after {
            transform: translate(0, 4px) rotate(45deg);
        }
        
        .bride-slider-next .bride-slider-control-icon:before {
            transform: translate(0, -4px) rotate(45deg);
        }
        
        .bride-slider-next .bride-slider-control-icon:after {
            transform: translate(0, 4px) rotate(-45deg);
        }
        
        /* Indicators */
        .bride-slider-indicators {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            z-index: 10;
        }
        
        .bride-slider-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            margin: 0 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border: none;
            padding: 0;
        }
        
        .bride-slider-indicator.active {
            background-color: #fff;
        }
        
        /* Buttons */
        .bride-slider-btn {
            display: inline-block;
            text-decoration: none;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }
        
        /* Hide slider before JS loads */
        .bride-slider.loading {
            visibility: hidden;
        }
        
        /* Image preloader */
        .bride-slider-preloader {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            opacity: 0.01;
            z-index: -1;
        }
    </style>';
    
    // Query by ID using get_post to be post-type agnostic
    $post = get_post(absint($atts['id']));
    
    // Check if post exists and setup_postdata
    if ($post) {
        setup_postdata($post);
        
        // Get global slider settings
        $slider_options = get_field('slider_options', $post->ID);
        $overlay_color = !empty($slider_options['overlay_color']) ? $slider_options['overlay_color'] : '';
        $overlay_opacity = !empty($slider_options['overlay_opacity']) ? $slider_options['overlay_opacity'] : '0';
        $indicator_color = !empty($slider_options['indicator_color']) ? $slider_options['indicator_color'] : '#ffffff';
        $control_color = !empty($slider_options['control_color']) ? $slider_options['control_color'] : '#ffffff';
      
        // Get slides
        $slides = get_field('slide', $post->ID);
        if ($slides && is_array($slides) && !empty($slides)) {
            // Count slides
            $slide_count = count($slides);
            
            // Generate a unique ID for this slider
            $slider_id = 'brideSlider-' . $post->ID;
            
            // Collect image URLs for preloading
            $image_urls = array();
            foreach ($slides as $slide) {
                if (!empty($slide['background_image']) && is_array($slide['background_image']) && !empty($slide['background_image']['url'])) {
                    $image_urls[] = $slide['background_image']['url'];
                }
            }
            
            // Custom indicator and control colors
            echo '<style>
                #' . esc_attr($slider_id) . ' .bride-slider-indicator {
                    background-color: ' . esc_attr($indicator_color) . '50;
                }
                #' . esc_attr($slider_id) . ' .bride-slider-indicator.active {
                    background-color: ' . esc_attr($indicator_color) . ';
                }
                #' . esc_attr($slider_id) . ' .bride-slider-control-icon:before,
                #' . esc_attr($slider_id) . ' .bride-slider-control-icon:after {
                    background-color: ' . esc_attr($control_color) . ';
                }
            </style>';
            ?>
            <div class="bride-slider-container" id="<?php echo esc_attr($slider_id); ?>">
                <!-- Main slider -->
                <div class="bride-slider loading" 
                     data-interval="<?php echo esc_attr($atts['interval']); ?>"
                     data-count="<?php echo esc_attr($slide_count); ?>"
                     data-fade="<?php echo esc_attr($atts['fade']); ?>">
                    
                    <?php foreach ($slides as $index => $slide) : 
                        // Get background image
                        $background_image = isset($slide['background_image']) ? $slide['background_image'] : '';
                        $background_style = '';
                        
                        if (!empty($background_image) && is_array($background_image) && !empty($background_image['url'])) {
                            // For image background
                            $background_style = 'background-image: url(' . esc_url($background_image['url']) . ');';
                        } else {
                            // Background color if specified, otherwise default
                            $bg_color = !empty($slide['background_color']) ? $slide['background_color'] : '#F8F4F0';
                            $background_style = 'background-color: ' . esc_attr($bg_color) . ';';
                        }
                        
                        // Get content positioning
                        $content_position = !empty($slide['content_position']) ? $slide['content_position'] : 'center-center';
                        list($v_pos, $h_pos) = explode('-', $content_position);
                        
                        // Create position styles
                        $position_style = '';
                        
                        // Horizontal positioning
                        if ($h_pos === 'left') {
                            $position_style .= 'justify-content: flex-start; padding-left: 5%;';
                        } elseif ($h_pos === 'right') {
                            $position_style .= 'justify-content: flex-end; padding-right: 5%;';
                        } else {
                            $position_style .= 'justify-content: center;';
                        }
                        
                        // Vertical positioning
                        if ($v_pos === 'top') {
                            $position_style .= 'align-items: flex-start; padding-top: 5%;';
                        } elseif ($v_pos === 'bottom') {
                            $position_style .= 'align-items: flex-end; padding-bottom: 5%;';
                        } else {
                            $position_style .= 'align-items: center;';
                        }
                        
                        // Text color
                        $text_color = !empty($slide['text_color']) ? $slide['text_color'] : '#000000';
                        $text_style = 'color: ' . esc_attr($text_color) . ';';
                        
                        // Content width
                        $content_width = !empty($slide['content_width']) ? $slide['content_width'] : '600px';
                       // $content_style = 'background:'.$control_color_background.';max-width: ' . esc_attr($content_width) . '; ' . $text_style;
                        
                        // Button styling
                        $button_style = '';
                        $button_bg = !empty($slide['button_background']) ? $slide['button_background'] : '#212529';
                        $button_text = !empty($slide['button_text_color']) ? $slide['button_text_color'] : '#ffffff';
                        $button_style .= 'background-color: ' . esc_attr($button_bg) . '; ';
                        $button_style .= 'color: ' . esc_attr($button_text) . '; ';
                        $button_style .= 'border-color: ' . esc_attr($button_bg) . ';';
                    ?>
                        <div class="bride-slide <?php echo ($index === 0) ? 'active' : ''; ?>" style="<?php echo $background_style; ?>" data-index="<?php echo $index; ?>">
                            <?php if (!empty($overlay_color) && $overlay_opacity > 0) : ?>
                            <div class="bride-slide-overlay" style="background-color: <?php echo esc_attr($overlay_color); ?>; opacity: <?php echo esc_attr($overlay_opacity); ?>;"></div>
                            <?php endif; ?>
                            <?php 
                            
                            $bg_val = isset($slide['control_color_background']) ? trim($slide['control_color_background']) : '';
$content_style = 'max-width: ' . esc_attr($content_width) . '; ' . $text_style;

if ($bg_val !== '' && $bg_val !== 'transparent') {
    $content_style = 'background:' . hexToRgbaString($bg_val) . ';' . $content_style;
} else {
    $content_style .= 'background-color: transparent; box-shadow: none;';
}


                            ?>
                            
                            <div class="bride-slide-content-wrapper" style="<?php echo $position_style; ?>">
                                <div class="bride-slide-content" style="<?php echo $content_style; ?>">
                                    <?php 
                                    // Output WYSIWYG editor content if it exists
                                    if (!empty($slide['slide_text'])) {
                                        echo $slide['slide_text']; 
                                    }
                                    
                                    // Add button if text and link exist
                                    if (!empty($slide['ctabutton_text']) && !empty($slide['ctalink'])) : 
                                        $button_classes = !empty($slide['button_classes']) ? $slide['button_classes'] : 'btn btn-lg px-4 py-2';
                                    ?>
                                    <div class="mt-4">
                                        <a href="<?php echo esc_url($slide['ctalink']); ?>" class="bride-slider-btn <?php echo esc_attr($button_classes); ?>" style="<?php echo $button_style; ?>">
                                            <?php echo esc_html($slide['ctabutton_text']); ?>
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if ($atts['controls'] === 'true' && $slide_count > 1) : ?>
                <!-- Slider Controls -->
                <button class="bride-slider-prev" aria-label="Previous slide">
                    <span class="bride-slider-control-icon"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="bride-slider-next" aria-label="Next slide">
                    <span class="bride-slider-control-icon"></span>
                    <span class="visually-hidden">Next</span>
                </button>
                <?php endif; ?>
                
                <?php if ($atts['indicators'] === 'true' && $slide_count > 1) : ?>
                <!-- Slider Indicators -->
                <div class="bride-slider-indicators">
                    <?php for ($i = 0; $i < $slide_count; $i++) : ?>
                        <button class="bride-slider-indicator <?php echo ($i === 0) ? 'active' : ''; ?>" data-index="<?php echo $i; ?>" aria-label="Go to slide <?php echo $i + 1; ?>"></button>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
                
                <!-- Image Preloader -->
                <div class="bride-slider-preloader">
                    <?php foreach ($image_urls as $url) : ?>
                        <img src="<?php echo esc_url($url); ?>" alt="preload">
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
        } else {
            echo '<p>No slides found for this slider.</p>';
        }
        
        // Reset post data
        wp_reset_postdata();
    } else {
        echo '<p>Slider not found. Please check the ID provided in the shortcode.</p>';
    }
    
    // Return output buffer content
    return ob_get_clean();
}
add_shortcode('bride_slider', 'bride_co_slider_shortcode');

/**
 * Add JavaScript to handle the slider functionality
 */
function bride_co_slider_scripts() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Find all slider containers
        const sliderContainers = document.querySelectorAll('.bride-slider-container');
        
        if (!sliderContainers.length) return;
        
        // Store all sliders instance data
        const sliders = {};
        
        // Preload all images before initializing
        const preloadPromises = [];
        
        // Function to preload an image
        function preloadImage(url) {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.onload = () => resolve(url);
                img.onerror = () => reject(url);
                img.src = url;
            });
        }
        
        // Collect all background images for preloading
        sliderContainers.forEach(container => {
            const images = container.querySelectorAll('.bride-slider-preloader img');
            images.forEach(img => {
                preloadPromises.push(preloadImage(img.src));
            });
        });
        
        // Wait for all images to load
        Promise.allSettled(preloadPromises).then(() => {
            // Now initialize all sliders after images are loaded
            sliderContainers.forEach(initializeSlider);
        });
        
        // Function to initialize a slider
        function initializeSlider(container) {
            // Get the main slider element
            const slider = container.querySelector('.bride-slider');
            if (!slider) return;
            
            // Get slider options
            const id = container.id;
            const slideCount = parseInt(slider.getAttribute('data-count') || 0, 10);
            const interval = parseInt(slider.getAttribute('data-interval') || 0, 10);
            const fade = slider.getAttribute('data-fade') === 'true';
            
            // Skip initialization for single slides
            if (slideCount <= 1) {
                // Remove controls and indicators
                const controls = container.querySelectorAll('.bride-slider-prev, .bride-slider-next');
                const indicators = container.querySelector('.bride-slider-indicators');
                
                controls.forEach(control => control.remove());
                if (indicators) indicators.remove();
                
                // Remove loading class to show the slide
                slider.classList.remove('loading');
                return;
            }
            
            // Get slides, controls and indicators
            const slides = Array.from(slider.querySelectorAll('.bride-slide'));
            const prevBtn = container.querySelector('.bride-slider-prev');
            const nextBtn = container.querySelector('.bride-slider-next');
            const indicators = Array.from(container.querySelectorAll('.bride-slider-indicator'));
            
            // Set up slider data
            sliders[id] = {
                element: slider,
                slides: slides,
                currentIndex: 0,
                interval: null,
                isPaused: false,
                slideCount: slideCount
            };
            
            // Function to go to a specific slide
            function goToSlide(index) {
                // Normalize index
                if (index < 0) index = slideCount - 1;
                if (index >= slideCount) index = 0;
                
                // Update current index
                sliders[id].currentIndex = index;
                
                // Update slides
                slides.forEach((slide, i) => {
                    if (i === index) {
                        slide.classList.add('active');
                    } else {
                        slide.classList.remove('active');
                    }
                });
                
                // Update indicators
                indicators.forEach((indicator, i) => {
                    if (i === index) {
                        indicator.classList.add('active');
                    } else {
                        indicator.classList.remove('active');
                    }
                });
            }
            
            // Function to go to next slide
            function nextSlide() {
                goToSlide(sliders[id].currentIndex + 1);
            }
            
            // Function to go to previous slide
            function prevSlide() {
                goToSlide(sliders[id].currentIndex - 1);
            }
            
            // Function to start auto-sliding
            function startAutoSlide() {
                if (interval > 0 && !sliders[id].interval) {
                    sliders[id].interval = setInterval(nextSlide, interval);
                }
            }
            
            // Function to stop auto-sliding
            function stopAutoSlide() {
                if (sliders[id].interval) {
                    clearInterval(sliders[id].interval);
                    sliders[id].interval = null;
                }
            }
            
            // Add event listeners for controls
            if (prevBtn) {
                prevBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    stopAutoSlide();
                    prevSlide();
                    if (!sliders[id].isPaused) {
                        startAutoSlide();
                    }
                });
            }
            
            if (nextBtn) {
                nextBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    stopAutoSlide();
                    nextSlide();
                    if (!sliders[id].isPaused) {
                        startAutoSlide();
                    }
                });
            }
            
            // Add event listeners for indicators
            indicators.forEach(indicator => {
                indicator.addEventListener('click', function(e) {
                    e.preventDefault();
                    const index = parseInt(this.getAttribute('data-index'), 10);
                    stopAutoSlide();
                    goToSlide(index);
                    if (!sliders[id].isPaused) {
                        startAutoSlide();
                    }
                });
            });
            
            // Add hover pause functionality
            slider.addEventListener('mouseenter', function() {
                sliders[id].isPaused = true;
                stopAutoSlide();
            });
            
            slider.addEventListener('mouseleave', function() {
                sliders[id].isPaused = false;
                startAutoSlide();
            });
            
            // Initialize autoplay if needed
            if (interval > 0) {
                startAutoSlide();
            }
            
            // Show the slider now that it's initialized
            slider.classList.remove('loading');
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'bride_co_slider_scripts', 100);

/**
 * Register ACF fields for the enhanced slider
 */
function bride_co_register_slider_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_slider_options',
        'title' => 'Slider Options',
        'fields' => array(
            array(
                'key' => 'field_slider_options',
                'label' => 'Slider Options',
                'name' => 'slider_options',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => 'field_overlay_color',
                        'label' => 'Overlay Color',
                        'name' => 'overlay_color',
                        'type' => 'color_picker',
                        'instructions' => 'Choose a color overlay for all slides (optional)',
                    ),
                    array(
                        'key' => 'field_overlay_opacity',
                        'label' => 'Overlay Opacity',
                        'name' => 'overlay_opacity',
                        'type' => 'range',
                        'instructions' => 'Set the opacity of the overlay (0 = transparent, 1 = solid)',
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
                        'default_value' => 0,
                    ),
                    array(
                        'key' => 'field_indicator_color',
                        'label' => 'Indicator Color',
                        'name' => 'indicator_color',
                        'type' => 'color_picker',
                        'instructions' => 'Choose a color for the slide indicators',
                        'default_value' => '#ffffff',
                    ),
                    array(
                        'key' => 'field_control_color',
                        'label' => 'Control Arrows Color',
                        'name' => 'control_color',
                        'type' => 'color_picker',
                        'instructions' => 'Choose a color for the control arrows',
                        'default_value' => '#ffffff',
                    ),
                   
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'slider',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));
    
    // Enhanced Slide Fields
    acf_add_local_field_group(array(
        'key' => 'group_slide_fields',
        'title' => 'Slide',
        'fields' => array(
            array(
                'key' => 'field_is_active',
                'label' => 'Is Active',
                'name' => 'is_active',
                'type' => 'true_false',
                'instructions' => 'Enable or disable this slider',
                'default_value' => 1,
                'ui' => 1,
            ),
            array(
                'key' => 'field_where_to_display',
                'label' => 'Where to display? Home page etc',
                'name' => 'where_to_display_home_page_etc',
                'type' => 'post_object',
                'instructions' => 'Select pages where this slider should appear',
                'multiple' => 1,
                'return_format' => 'object',
                'post_type' => array('page', 'post'),
                'ui' => 1,
                'ajax' => 0, // Disable AJAX loading
                'query_vars' => array(
                    'posts_per_page' => 150,
                    'post_status' => 'publish'
                ),
            ),
            array(
                'key' => 'field_slide',
                'label' => 'Slide',
                'name' => 'slide',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => 'Add Slide',
                'sub_fields' => array(
                    array(
                        'key' => 'field_slide_text',
                        'label' => 'Slide Text',
                        'name' => 'slide_text',
                        'type' => 'wysiwyg',
                        'tabs' => 'all',
                        'toolbar' => 'full',
                        'media_upload' => 1,
                    ),
                    array(
                        'key' => 'field_ctabutton_text',
                        'label' => 'CTA/Button Text',
                        'name' => 'ctabutton_text',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_ctalink',
                        'label' => 'CTA/Link',
                        'name' => 'ctalink',
                        'type' => 'url',
                    ),
                    array(
                        'key' => 'field_background_image',
                        'label' => 'Background Image',
                        'name' => 'background_image',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                    ),
                    array(
                        'key' => 'field_background_color',
                        'label' => 'Background Color',
                        'name' => 'background_color',
                        'type' => 'color_picker',
                        'instructions' => 'Only used if no background image is selected',
                        'default_value' => '#F8F4F0',
                    ),
                    array(
                        'key' => 'field_content_position',
                        'label' => 'Content Position',
                        'name' => 'content_position',
                        'type' => 'select',
                        'choices' => array(
                            'top-left' => 'Top Left',
                            'top-center' => 'Top Center',
                            'top-right' => 'Top Right',
                            'center-left' => 'Middle Left',
                            'center-center' => 'Middle Center',
                            'center-right' => 'Middle Right',
                            'bottom-left' => 'Bottom Left',
                            'bottom-center' => 'Bottom Center',
                            'bottom-right' => 'Bottom Right',
                        ),
                        'default_value' => 'center-center',
                        'return_format' => 'value',
                    ),
                    array(
                        'key' => 'field_content_width',
                        'label' => 'Content Width',
                        'name' => 'content_width',
                        'type' => 'text',
                        'instructions' => 'Enter a width value (e.g., 600px, 50%, etc.)',
                        'default_value' => '600px',
                    ),
                    array(
                        'key' => 'field_text_color',
                        'label' => 'Text Color',
                        'name' => 'text_color',
                        'type' => 'color_picker',
                        'default_value' => '#000000',
                    ),
                    array(
                        'key' => 'field_button_background',
                        'label' => 'Button Background',
                        'name' => 'button_background',
                        'type' => 'color_picker',
                        'default_value' => '#212529',
                    ),
                    array(
                        'key' => 'field_button_text_color',
                        'label' => 'Button Text Color',
                        'name' => 'button_text_color',
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                    ),
                    array(
                        'key' => 'field_button_classes',
                        'label' => 'Button CSS Classes',
                        'name' => 'button_classes',
                        'type' => 'text',
                        'instructions' => 'Add custom CSS classes (e.g., btn-lg px-4 py-2)',
                        'default_value' => 'btn-lg px-4 py-2',
                    ),
                    array(
    'key' => 'field_control_color_background',
    'label' => 'Control Text Background Color',
    'name' => 'control_color_background',
    'type' => 'select',
    'instructions' => 'Select a Background Color',
    'choices' => array(
    'transparent' => 'None (transparent)',
    '#ffffff'     => 'White',
    '#000000'     => 'Black',
    '#808080'     => 'Gray',
    '#000080'     => 'Navy',
),
'default_value' => 'transparent',
'allow_null'    => 0,

    'multiple'      => 0,
    'ui'            => 1,
    'ajax'          => 0,
    'return_format' => 'value',
    'placeholder'   => 'None',
),


                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'slider',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));
}
add_action('acf/init', 'bride_co_register_slider_acf_fields');
/**
 * Enhanced Text Options for Bride & Co Slider
 * 
 * A simple approach to add more text styling options to the Bride & Co slider
 */

/**
 * Enhance the TinyMCE editor with more text formatting options
 */
function bride_co_enhance_wysiwyg_editor() {
    // Only in admin
    if (!is_admin()) {
        return;
    }
    
    // Add buttons to TinyMCE
    add_filter('mce_buttons_2', 'bride_co_add_mce_buttons');
    // Customize TinyMCE settings
    add_filter('tiny_mce_before_init', 'bride_co_customize_tinymce_settings');
}
add_action('admin_init', 'bride_co_enhance_wysiwyg_editor');

/**
 * Add additional buttons to the TinyMCE editor
 */
function bride_co_add_mce_buttons($buttons) {
    // Make sure 'styleselect' is in the first toolbar
    if (!in_array('styleselect', $buttons)) {
        array_unshift($buttons, 'styleselect');
    }
    
    // Add font size selection
    if (!in_array('fontsizeselect', $buttons)) {
        $buttons[] = 'fontsizeselect';
    }
    
    // Add font family selection
    if (!in_array('fontselect', $buttons)) {
        $buttons[] = 'fontselect';
    }
    
    return $buttons;
}

/**
 * Customize TinyMCE settings
 */
function bride_co_customize_tinymce_settings($settings) {
    // Add custom font size options
    $settings['fontsize_formats'] = '8px 10px 12px 14px 16px 18px 20px 24px 28px 32px 36px 42px 48px 56px 64px 72px';
    
    // Add custom font family options
    $settings['font_formats'] = 
        "Arial=arial,helvetica,sans-serif;" .
        "Arial Black=arial black,avant garde;" .
        "Helvetica=helvetica;" .
        "Tahoma=tahoma,arial,helvetica,sans-serif;" .
        "Trebuchet MS=trebuchet ms,geneva;" .
        "Verdana=verdana,geneva;" .
        "Georgia=georgia,palatino;" .
        "Palatino=palatino,serif;" .
        "Times New Roman=times new roman,times,serif;" .
        "Montserrat=Montserrat,sans-serif;" .
        "Playfair Display=Playfair Display,serif;" .
        "Roboto=Roboto,sans-serif;" .
        "Open Sans=Open Sans,sans-serif;" .
        "Lato=Lato,sans-serif;" .
        "Poppins=Poppins,sans-serif;" .
        "Dancing Script=Dancing Script,cursive;";
    
    // Define custom style formats
    $style_formats = array(
        array(
            'title' => 'Text Styles',
            'items' => array(
                array(
                    'title' => 'Lead Text',
                    'selector' => 'p',
                    'classes' => 'lead',
                ),
                array(
                    'title' => 'Small Text',
                    'inline' => 'small',
                ),
                array(
                    'title' => 'Highlighted Text',
                    'inline' => 'span',
                    'styles' => array(
                        'background-color' => '#fffde7',
                        'padding' => '2px 5px',
                    ),
                ),
            ),
        ),
        array(
            'title' => 'Typography',
            'items' => array(
                array(
                    'title' => 'UPPERCASE',
                    'inline' => 'span',
                    'styles' => array(
                        'text-transform' => 'uppercase',
                    ),
                ),
                array(
                    'title' => 'Wide Letter Spacing',
                    'inline' => 'span',
                    'styles' => array(
                        'letter-spacing' => '0.1em',
                    ),
                ),
                array(
                    'title' => 'Light Weight',
                    'inline' => 'span',
                    'styles' => array(
                        'font-weight' => '300',
                    ),
                ),
                array(
                    'title' => 'Bold Weight',
                    'inline' => 'span',
                    'styles' => array(
                        'font-weight' => '700',
                    ),
                ),
            ),
        ),
        array(
            'title' => 'Custom Headings',
            'items' => array(
                array(
                    'title' => 'Elegant Heading',
                    'selector' => 'h1,h2,h3,h4,h5,h6',
                    'classes' => 'elegant-heading',
                    'styles' => array(
                        'font-family' => 'Playfair Display, serif',
                        'font-weight' => '400',
                        'letter-spacing' => '0.05em',
                    ),
                ),
                array(
                    'title' => 'Modern Heading',
                    'selector' => 'h1,h2,h3,h4,h5,h6',
                    'classes' => 'modern-heading',
                    'styles' => array(
                        'font-family' => 'Montserrat, sans-serif',
                        'font-weight' => '600',
                        'text-transform' => 'uppercase',
                        'letter-spacing' => '0.1em',
                    ),
                ),
                array(
                    'title' => 'Handwritten Heading',
                    'selector' => 'h1,h2,h3,h4,h5,h6',
                    'classes' => 'handwritten-heading',
                    'styles' => array(
                        'font-family' => 'Dancing Script, cursive',
                        'font-weight' => '400',
                    ),
                ),
            ),
        ),
    );
    
    // Add style formats to TinyMCE settings
    $settings['style_formats'] = json_encode($style_formats);
    
    return $settings;
}

/**
 * Add Google Fonts to the front-end
 */
function bride_co_add_google_fonts() {
    ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;600;700&family=Lato:wght@300;400;700&family=Poppins:wght@300;400;500;600;700&family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Pre-defined text styles for the slider */
        .lead {
            font-size: 1.25rem;
            font-weight: 300;
            line-height: 1.5;
        }
        
        .elegant-heading {
            font-family: 'Playfair Display', serif;
            font-weight: 400;
            letter-spacing: 0.05em;
        }
        
        .modern-heading {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        
        .handwritten-heading {
            font-family: 'Dancing Script', cursive;
            font-weight: 400;
        }
        
        /* Text utility classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .text-uppercase { text-transform: uppercase; }
        .text-lowercase { text-transform: lowercase; }
        .text-capitalize { text-transform: capitalize; }
        .fw-light { font-weight: 300; }
        .fw-normal { font-weight: 400; }
        .fw-bold { font-weight: 700; }
        .fst-italic { font-style: italic; }
        .ls-tight { letter-spacing: -0.05em; }
        .ls-normal { letter-spacing: normal; }
        .ls-wide { letter-spacing: 0.05em; }
        .ls-wider { letter-spacing: 0.1em; }
        .ls-widest { letter-spacing: 0.25em; }
    </style>
    <?php
}
add_action('wp_head', 'bride_co_add_google_fonts', 5);

/**
 * Add Google Fonts to the editor
 */
function bride_co_add_editor_styles() {
    // Only in admin
    if (!is_admin()) {
        return;
    }
    
    add_editor_style('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;600;700&family=Lato:wght@300;400;700&family=Poppins:wght@300;400;500;600;700&family=Dancing+Script:wght@400;700&display=swap');
    
    // Add custom styles for the editor
    add_action('admin_head', 'bride_co_add_custom_editor_css');
}
add_action('admin_init', 'bride_co_add_editor_styles');

/**
 * Add custom CSS for editor styles
 */
function bride_co_add_custom_editor_css() {
    // Only in admin
    if (!is_admin()) {
        return;
    }
    
    // Check if we're editing a slider
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'slider') {
        return;
    }
    
    // Add custom CSS for the editor
    ?>
    <style type="text/css">
        /* Make WYSIWYG editor larger for better visibility */
        .acf-field-wysiwyg .wp-editor-container {
            min-height: 300px;
        }
        
        /* Editor styles */
        .mce-content-body .lead {
            font-size: 1.25rem;
            font-weight: 300;
            line-height: 1.5;
        }
        
        .mce-content-body .elegant-heading {
            font-family: 'Playfair Display', serif;
            font-weight: 400;
            letter-spacing: 0.05em;
        }
        
        .mce-content-body .modern-heading {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        
        .mce-content-body .handwritten-heading {
            font-family: 'Dancing Script', cursive;
            font-weight: 400;
        }
    </style>
    <?php
}

/**
 * Add a simple help guide for typography options
 */
function bride_co_add_typography_help_metabox() {
    add_meta_box(
        'bride_co_typography_help',
        'Typography Help Guide',
        'bride_co_render_typography_help',
        'slider',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'bride_co_add_typography_help_metabox');

/**
 * Render the typography help content
 */
function bride_co_render_typography_help() {
    ?>
    <div class="bride-co-help-guide">
        <p><strong>Text Formatting Options:</strong></p>
        
        <h4>Font Controls</h4>
        <ul>
            <li>Use the <strong>Font Family</strong> dropdown to change the typeface</li>
            <li>Use the <strong>Font Size</strong> dropdown to adjust text size</li>
            <li>Use the <strong>Format</strong> dropdown for predefined styles</li>
        </ul>
        
        <h4>Preset Styles</h4>
        <ul>
            <li><strong>Elegant Heading</strong> - Serif font with light spacing</li>
            <li><strong>Modern Heading</strong> - Sans-serif uppercase style</li>
            <li><strong>Handwritten</strong> - Script-style display font</li>
        </ul>
        
        <h4>CSS Classes You Can Add</h4>
        <ul>
            <li><code>text-center</code> - Center align text</li>
            <li><code>text-uppercase</code> - UPPERCASE text</li>
            <li><code>fw-light</code> - Light font weight</li>
            <li><code>fw-bold</code> - Bold font weight</li>
            <li><code>ls-wide</code> - Wider letter spacing</li>
        </ul>
    </div>
    <style>
        .bride-co-help-guide {
            font-size: 12px;
            line-height: 1.4;
        }
        .bride-co-help-guide h4 {
            margin: 12px 0 5px;
            font-size: 13px;
        }
        .bride-co-help-guide ul {
            margin: 5px 0 10px 15px;
        }
        .bride-co-help-guide code {
            background: #f0f0f0;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 11px;
        }
    </style>
    <?php
}

/**
 * Allow additional HTML elements and attributes in WYSIWYG editor
 */
function bride_co_allow_additional_html_tags($tags, $context) {
    if ($context === 'post') {
        // Allow style attribute for various elements
        $tags['span']['style'] = true;
        $tags['p']['style'] = true;
        $tags['h1']['style'] = true;
        $tags['h2']['style'] = true;
        $tags['h3']['style'] = true;
        $tags['h4']['style'] = true;
        $tags['h5']['style'] = true;
        $tags['h6']['style'] = true;
        $tags['div']['style'] = true;
        
        // Allow class attribute for styling
        $tags['span']['class'] = true;
        $tags['p']['class'] = true;
        $tags['h1']['class'] = true;
        $tags['h2']['class'] = true;
        $tags['h3']['class'] = true;
        $tags['h4']['class'] = true;
        $tags['h5']['class'] = true;
        $tags['h6']['class'] = true;
        $tags['div']['class'] = true;
    }
    
    return $tags;
}
add_filter('wp_kses_allowed_html', 'bride_co_allow_additional_html_tags', 10, 2);

/**
 * Add custom classes and additional text styles for slider content
 */
function bride_co_add_slider_text_styles() {
    ?>
    <style>
        /* Additional heading styles for slider */
        .bride-slide-content h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .bride-slide-content h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .bride-slide-content h3 {
            font-size: 2rem;
            margin-bottom: 0.75rem;
        }
        
        .bride-slide-content h4 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .bride-slide-content h5 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }
        
        .bride-slide-content h6 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        
        /* Text shadows for better readability on images */
        .text-shadow-light {
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        
        .text-shadow-medium {
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .text-shadow-dark {
            text-shadow: 0 2px 6px rgba(0,0,0,0.5);
        }
        
        /* Additional font size utility classes */
        .fs-xs { font-size: 0.75rem; }
        .fs-sm { font-size: 0.875rem; }
        .fs-md { font-size: 1rem; }
        .fs-lg { font-size: 1.25rem; }
        .fs-xl { font-size: 1.5rem; }
        .fs-2xl { font-size: 2rem; }
        .fs-3xl { font-size: 2.5rem; }
        .fs-4xl { font-size: 3rem; }
        .fs-5xl { font-size: 4rem; }
    </style>
    <?php
}
add_action('wp_head', 'bride_co_add_slider_text_styles', 10);

/**
 * Add a simple option to customize overlay opacity to improve text readability
 */
function bride_co_add_text_readability_option() {
    // Only implement if ACF is active
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    // Add a field to the slider options group
    add_filter('acf/load_field/key=field_slider_options', function($field) {
        if (!empty($field['sub_fields'])) {
            // Add text shadow option
            $field['sub_fields'][] = array(
                'key' => 'field_text_shadow',
                'label' => 'Enable Text Shadow',
                'name' => 'text_shadow',
                'type' => 'true_false',
                'instructions' => 'Add shadow to text for better readability on image backgrounds',
                'default_value' => 0,
                'ui' => 1,
            );
            
            // Add text shadow intensity option
            $field['sub_fields'][] = array(
                'key' => 'field_text_shadow_intensity',
                'label' => 'Text Shadow Intensity',
                'name' => 'text_shadow_intensity',
                'type' => 'select',
                'instructions' => 'Choose the shadow intensity for text',
                'choices' => array(
                    'light' => 'Light',
                    'medium' => 'Medium',
                    'dark' => 'Dark',
                ),
                'default_value' => 'medium',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_text_shadow',
                            'operator' => '==',
                            'value' => 1,
                        ),
                    ),
                ),
            );
        }
        
        return $field;
    });
    
    // Add the text shadow class to slider content if enabled
    add_filter('acf/load_field/key=field_slide_text', function($field) {
        // Add instructions about available text options
        $field['instructions'] = 'Use the formatting toolbar to style your text. Available options include multiple font families, sizes, and styles.';
        
        return $field;
    });
}
add_action('acf/init', 'bride_co_add_text_readability_option', 20);

/**
 * Apply text shadows to slider content if enabled
 */
function bride_co_apply_text_shadows($content, $slider_id) {
    // Get slider options
    $slider_options = get_field('slider_options', $slider_id);
    
    // Check if text shadow is enabled
    if (empty($slider_options['text_shadow'])) {
        return $content;
    }
    
    // Get shadow intensity
    $intensity = !empty($slider_options['text_shadow_intensity']) ? $slider_options['text_shadow_intensity'] : 'medium';
    $shadow_class = 'text-shadow-' . $intensity;
    
    // Apply shadow class to headings and paragraphs
    $content = preg_replace('/<(h[1-6]|p|div)([^>]*)>/i', '<$1 class="' . $shadow_class . '" $2>', $content);
    
    return $content;
}

/**
 * Modify the slider content to add text shadow if enabled
 */
function bride_co_modify_slider_content() {
    add_filter('the_content', function($content) {
        global $post;
        
        // Only apply to slider content
        if (empty($post) || $post->post_type !== 'slider') {
            return $content;
        }
        
        return bride_co_apply_text_shadows($content, $post->ID);
    });
}
add_action('init', 'bride_co_modify_slider_content');


/**
 * Featured Products Section for Home Page
 * 
 * Adds a featured products section with two large cards
 * that appears directly in your home page editor.
 */

/**
 * Register ACF fields for featured products section
 */
function bride_co_register_featured_products_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_featured_products',
        'title' => 'Featured Products Section',
        'fields' => array(
            array(
                'key' => 'field_show_featured_products',
                'label' => 'Display Featured Products',
                'name' => 'show_featured_products',
                'type' => 'true_false',
                'instructions' => 'Enable or disable the featured products section',
                'default_value' => 1,
                'ui' => 1,
            ),
            array(
                'key' => 'field_featured_products',
                'label' => 'Featured Products',
                'name' => 'featured_products',
                'type' => 'repeater',
                'instructions' => 'Add up to 2 featured product cards',
                'min' => 1,
                'max' => 2,
                'layout' => 'block',
                'button_label' => 'Add Featured Product',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_featured_products',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_featured_top_text',
                        'label' => 'Top Text',
                        'name' => 'top_text',
                        'type' => 'text',
                        'instructions' => 'Enter the small text to display at the top (e.g., "SHOP OUR")',
                    ),
                    array(
                        'key' => 'field_featured_main_text',
                        'label' => 'Main Text',
                        'name' => 'main_text',
                        'type' => 'text',
                        'instructions' => 'Enter the main heading text (e.g., "SHOWSTOPPING")',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_featured_bottom_text',
                        'label' => 'Bottom Text',
                        'name' => 'bottom_text',
                        'type' => 'text',
                        'instructions' => 'Enter the small text to display at the bottom (e.g., "WEDDING DRESSES")',
                    ),
                    array(
                        'key' => 'field_featured_button_text',
                        'label' => 'Button Text',
                        'name' => 'button_text',
                        'type' => 'text',
                        'instructions' => 'Enter the text for the button (e.g., "View Now")',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_featured_button_link',
                        'label' => 'Button Link',
                        'name' => 'button_link',
                        'type' => 'url',
                        'instructions' => 'Enter the URL for the button',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_featured_background_image',
                        'label' => 'Background Image',
                        'name' => 'background_image',
                        'type' => 'image',
                        'instructions' => 'Upload or select the background image for this featured product',
                        'required' => 1,
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'front_page',
                ),
            ),
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'page-home.php',
                ),
            ),
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'templates/home-page.php',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));
}
add_action('acf/init', 'bride_co_register_featured_products_fields');

/**
 * Add CSS for featured products
 */
function bride_co_featured_products_css() {
    if (is_front_page()) {
        ?>
        <style>
            /* Featured Products Styling */
            .featured-product-card {
                position: relative;
                overflow: hidden;
                margin-bottom: 30px;
                border-radius: 5px;
                border: none;
            }
            
            .featured-product-card img {
                width: 100%;
                transition: transform 0.3s ease;
            }
            
            .featured-product-card:hover img {
                transform: scale(1.05);
            }
            
            .featured-product-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: white;
                text-align: center;
            }
         
            
            .featured-product-main-text {
                font-family: "Cinzel", serif;
                font-weight: 700;
                font-size: 40px;
                margin: 5px 0;
                text-transform: uppercase;
            }
            
            .shop-button {
                background-color: white;
                color: #808080!important;
                font-weight: bold;
                padding: 8px 20px;
                border: none;
                text-decoration: none;
                display: inline-block;
                font-size: 0.9rem;
                margin-top: 10px;
                text-transform: uppercase;
                font-family: "Poppins", sans-serif;
                transition: all 0.3s ease;
            }
            
            .shop-button:hover {
                background-color: #ddcdbf;
                color: white;
            }
            
            .grayscale {
                filter: grayscale(20%);
            }
        </style>
        <?php
    }
}
add_action('wp_head', 'bride_co_featured_products_css');

/**
 * Function to render featured products section in template
 */
function bride_co_render_featured_products() {
    // Check if we're on the home page
    if (!is_front_page()) {
        return;
    }
    
    // Check if featured products should be displayed
    $show_products = get_field('show_featured_products');
    
    if (!$show_products) {
        return;
    }
    
    // Get the featured products
    $products = get_field('featured_products');
    
    if (empty($products)) {
        return;
    }
    
    ?>
    <section class="container mt-5">
        <div class="row">
            <?php 
            $count = count($products);
            $col_class = ($count == 1) ? 'col-md-12' : 'col-md-6';
            
            foreach ($products as $product) : 
                // Get product data
                $top_text = $product['top_text'];
                $main_text = $product['main_text'];
                $bottom_text = $product['bottom_text'];
                $button_text = $product['button_text'];
                $button_link = $product['button_link'];
                $background_image = $product['background_image'];
                
                // Skip if missing essential data
                if (empty($main_text) || empty($button_text) || empty($button_link) || empty($background_image)) {
                    continue;
                }
            ?>
                <div class="<?php echo esc_attr($col_class); ?>">
                    <div class="featured-product-card">
                        <img src="<?php echo esc_url($background_image['url']); ?>" class="w-100 grayscale" alt="<?php echo esc_attr($main_text); ?>">
                        <div class="featured-product-overlay">
                            <?php if (!empty($top_text)) : ?>
                                <p class="mb-1"><?php echo esc_html($top_text); ?></p>
                            <?php endif; ?>
                            
                            <h2 class="featured-product-main-text"><?php echo esc_html($main_text); ?></h2>
                            
                            <?php if (!empty($bottom_text)) : ?>
                                <p class="mb-3"><?php echo esc_html($bottom_text); ?></p>
                            <?php endif; ?>
                            
                            <a href="<?php echo esc_url($button_link); ?>" class="shop-button">
                                <?php echo esc_html($button_text); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
}

// Add this code to your home page template (front-page.php or home.php):
// <?php bride_co_render_featured_products(); 

/**
 * Category Cards Carousel for Home Page
 * 
 * Adds a carousel of category cards with editable heading
 * that appears directly in your home page editor.
 */

/**
 * Register ACF fields for category cards carousel
 */

 function bride_co_register_category_cards_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_category_cards',
        'title' => 'Category Cards Carousel',
        'fields' => array(
            array(
                'key' => 'field_show_category_cards',
                'label' => 'Display Category Carousel',
                'name' => 'show_category_cards',
                'type' => 'true_false',
                'instructions' => 'Enable or disable the category cards carousel',
                'default_value' => 1,
                'ui' => 1,
            ),
            array(
                'key' => 'field_category_heading',
                'label' => 'Section Heading',
                'name' => 'category_heading',
                'type' => 'text',
                'instructions' => 'Enter a heading for this section (leave empty for no heading)',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_category_cards',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_category_cards',
                'label' => 'Category Cards',
                'name' => 'category_cards',
                'type' => 'repeater',
                'instructions' => 'Add your category cards (recommended 4-8 cards)',
                'min' => 1,
                'max' => 0, // Unlimited cards for carousel
                'layout' => 'block',
                'button_label' => 'Add Card',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_category_cards',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_card_title',
                        'label' => 'Card Title',
                        'name' => 'card_title',
                        'type' => 'text',
                        'instructions' => 'Enter the title to display on the card (e.g., "DRESS FITTING")',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_button_text',
                        'label' => 'Button Text',
                        'name' => 'button_text',
                        'type' => 'text',
                        'instructions' => 'Enter the text for the button (e.g., "WHAT TO EXPECT")',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_button_link',
                        'label' => 'Button Link',
                        'name' => 'button_link',
                        'type' => 'url',
                        'instructions' => 'Enter the URL for the button',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_background_image',
                        'label' => 'Background Image',
                        'name' => 'background_image',
                        'type' => 'image',
                        'instructions' => 'Upload or select the background image for this card',
                        'required' => 1,
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                    ),
                ),
            ),
            array(
                'key' => 'field_carousel_options',
                'label' => 'Carousel Options',
                'name' => 'carousel_options',
                'type' => 'group',
                'instructions' => 'Configure carousel display options',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_category_cards',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_auto_play',
                        'label' => 'Auto Play',
                        'name' => 'auto_play',
                        'type' => 'true_false',
                        'instructions' => 'Automatically rotate the carousel',
                        'default_value' => 1,
                        'ui' => 1,
                    ),
                    array(
                        'key' => 'field_desktop_cards',
                        'label' => 'Cards per View (Desktop)',
                        'name' => 'desktop_cards',
                        'type' => 'number',
                        'instructions' => 'Number of cards to show at once on desktop',
                        'default_value' => 4,
                        'min' => 1,
                        'max' => 5,
                    ),
                    array(
                        'key' => 'field_tablet_cards',
                        'label' => 'Cards per View (Tablet)',
                        'name' => 'tablet_cards',
                        'type' => 'number',
                        'instructions' => 'Number of cards to show at once on tablets',
                        'default_value' => 2,
                        'min' => 1,
                        'max' => 3,
                    ),
                    array(
                        'key' => 'field_mobile_cards',
                        'label' => 'Cards per View (Mobile)',
                        'name' => 'mobile_cards',
                        'type' => 'number',
                        'instructions' => 'Number of cards to show at once on mobile',
                        'default_value' => 1,
                        'min' => 1,
                        'max' => 2,
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'front_page',
                ),
            ),
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'page-home.php',
                ),
            ),
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'templates/home-page.php',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));
}
add_action('acf/init', 'bride_co_register_category_cards_fields');

/**
 * Enqueue Swiper JS for carousel functionality
 */
function bride_co_enqueue_carousel_assets() {
    if (is_front_page()) {
        // Enqueue Swiper JS from CDN
        wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.0.0');
        wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.0.0', true);
        
        // Enqueue our custom initialization script
        wp_enqueue_script('bride-co-carousel', get_template_directory_uri() . '/js/category-carousel.js', array('jquery', 'swiper-js'), '1.0.0', true);
    }
}
add_action('wp_enqueue_scripts', 'bride_co_enqueue_carousel_assets');

/**
 * Add CSS for category cards carousel
 */
function bride_co_category_cards_css() {
    if (is_front_page()) {
        ?>
        <style>
            /* Category Section Heading */
            .category-section-heading {
                font-family: "Cinzel", sans-serif;
                font-size: 32px;
                font-weight: 700;
                margin-bottom: 25px;
                color: #333;
                text-align: center;
            }
            
            /* Carousel Container */
            .category-carousel-container {
                position: relative;
                padding: 0 40px;
                margin-bottom: 60px;
            }
            
            /* Category Card Styling */
            .category-card {
                position: relative;
                background-size: cover;
                background-position: center;
                height: 400px;
                color: white;
                display: flex;
                align-items: flex-end;
                justify-content: center;
                text-align: center;
                border-radius: 5px;
                overflow: hidden;
                transition: transform 0.3s ease-in-out;
                padding-bottom: 20px;
                margin: 10px;
            }

            .category-card:hover {
                transform: scale(1.03);
            }

            /* Dark Overlay */
            .category-card::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.2);
            }

            /* Card Content */
            .category-content {
                position: absolute;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 2;
                text-align: center;
                width: 100%;
            }

            .category-title {
                font-size: 20px;
                font-weight: bold;
                text-transform: uppercase;
                margin-bottom: 5px;
                white-space: pre-wrap;
                color: white;
                font-family: "Cinzel", sans-serif;
            }

            /* Shop Button */
            .shop-button {
                background-color: white;
                color: #808080;
                font-weight: 400;
                padding: 8px 20px;
                border: none;
                text-decoration: none;
                display: inline-block;
                font-size: 0.9rem;
                white-space: nowrap;
                font-family: "Poppins", sans-serif;
            }

            .shop-button:hover {
                background-color: #ddcdbf;
                color: white;
            }
            
            /* Swiper Navigation */
            .swiper-button-next,
            .swiper-button-prev {
                color: #808080;
                background: rgba(255, 255, 255, 0.8);
                width: 40px;
                height: 40px;
                border-radius: 50%;
                transition: all 0.2s ease;
            }

            .swiper-button-next:hover,
            .swiper-button-prev:hover {
                background: #ddcdbf;
                color: white;
            }

            .swiper-button-next:after,
            .swiper-button-prev:after {
                font-size: 16px;
                font-weight: bold;
            }
            
            /* Swiper Pagination */
            .swiper-pagination {
                position: relative;
                margin-top: 20px;
            }
            
            .swiper-pagination-bullet {
                width: 10px;
                height: 10px;
                background: #ccc;
                opacity: 1;
            }
            
            .swiper-pagination-bullet-active {
                background: #ddcdbf;
            }
            
            @media (max-width: 768px) {
                .category-carousel-container {
                    padding: 0 20px;
                }
                
                .category-section-heading {
                    font-size: 28px;
                }
            }
        </style>
        <?php
    }
}
add_action('wp_head', 'bride_co_category_cards_css');

/**
 * Function to render category cards carousel in template
 */
function bride_co_render_category_cards() {
    // Check if we're on the home page
    if (!is_front_page()) {
        return;
    }
    
    // Check if category cards should be displayed
    $show_cards = get_field('show_category_cards');
    
    if (!$show_cards) {
        return;
    }
    
    // Get the category cards and heading
    $cards = get_field('category_cards');
    $heading = get_field('category_heading');
    
    if (empty($cards)) {
        return;
    }
    
    // Get carousel options
    $options = get_field('carousel_options');
    $auto_play = isset($options['auto_play']) ? $options['auto_play'] : true;
    $desktop_cards = isset($options['desktop_cards']) ? $options['desktop_cards'] : 4;
    $tablet_cards = isset($options['tablet_cards']) ? $options['tablet_cards'] : 2;
    $mobile_cards = isset($options['mobile_cards']) ? $options['mobile_cards'] : 1;
    
    // Generate data attributes for Swiper initialization
    $data_attrs = "data-autoplay=\"" . ($auto_play ? 'true' : 'false') . "\" ";
    $data_attrs .= "data-desktop=\"" . esc_attr($desktop_cards) . "\" ";
    $data_attrs .= "data-tablet=\"" . esc_attr($tablet_cards) . "\" ";
    $data_attrs .= "data-mobile=\"" . esc_attr($mobile_cards) . "\"";
    
    ?>
    <section class="container my-5">
        <?php if (!empty($heading)) : ?>
            <h2 class="category-section-heading"><?php echo esc_html($heading); ?></h2>
        <?php endif; ?>
        
        <div class="category-carousel-container" <?php echo $data_attrs; ?>>
            <div class="swiper category-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($cards as $card) : 
                        // Get card data
                        $card_title = $card['card_title'];
                        $button_text = $card['button_text'];
                        $button_link = $card['button_link'];
                        $background_image = $card['background_image'];
                        
                        // Skip if missing essential data
                        if (empty($card_title) || empty($button_text) || empty($button_link) || empty($background_image)) {
                            continue;
                        }
                        
                        // Generate background style
                        $bg_style = '';
                        if (!empty($background_image) && is_array($background_image)) {
                            $bg_style = 'background-image: url(' . esc_url($background_image['url']) . ');';
                        }
                    ?>
                        <div class="swiper-slide">
                            <a href="<?php echo esc_url($button_link); ?>" class="text-decoration-none">
                                <div class="category-card" style="<?php echo $bg_style; ?>">
                                    <div class="category-content">
                                        <h2 class="category-title"><?php echo esc_html($card_title); ?></h2>
                                        <a href="<?php echo esc_url($button_link); ?>" class="shop-button"><?php echo esc_html($button_text); ?></a>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Add navigation arrows -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                
                <!-- Add pagination dots -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Create JavaScript file for Swiper initialization
 * 
 * Create a file named 'category-carousel.js' in your theme's js folder with this content:
 */
function bride_co_create_carousel_js() {
    // Path to create the JS file
    $js_dir = get_template_directory() . '/js';
    $js_file = $js_dir . '/category-carousel.js';
    
    // Create directory if it doesn't exist
    if (!file_exists($js_dir)) {
        wp_mkdir_p($js_dir);
    }
    
    // Only create the file if it doesn't exist
    if (!file_exists($js_file)) {
        $js_content = '
        jQuery(document).ready(function($) {
            // Initialize Swiper carousel
            const categoryCarousel = $(".category-carousel-container");
            
            if (categoryCarousel.length) {
                const autoplay = categoryCarousel.data("autoplay") === true;
                const desktopSlides = parseInt(categoryCarousel.data("desktop")) || 4;
                const tabletSlides = parseInt(categoryCarousel.data("tablet")) || 2;
                const mobileSlides = parseInt(categoryCarousel.data("mobile")) || 1;
                
                const swiper = new Swiper(".category-swiper", {
                    // Optional parameters
                    loop: true,
                    slidesPerView: mobileSlides,
                    spaceBetween: 20,
                    grabCursor: true,
                    
                    // Responsive breakpoints
                    breakpoints: {
                        // when window width is >= 576px (mobile landscape)
                        576: {
                            slidesPerView: mobileSlides,
                            spaceBetween: 20
                        },
                        // when window width is >= 768px (tablet)
                        768: {
                            slidesPerView: tabletSlides,
                            spaceBetween: 30
                        },
                        // when window width is >= 992px (desktop)
                        992: {
                            slidesPerView: desktopSlides,
                            spaceBetween: 30
                        }
                    },
                    
                    // If autoplay is enabled
                    ...(autoplay ? {
                        autoplay: {
                            delay: 5000,
                            disableOnInteraction: false,
                        },
                    } : {}),
                    
                    // Navigation arrows
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                    
                    // Pagination
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },
                });
            }
        });';
        
        // Write to file
        file_put_contents($js_file, $js_content);
    }
}
add_action('after_setup_theme', 'bride_co_create_carousel_js');

// Add this code to your home page template (front-page.php or home.php):
// <?php bride_co_render_category_cards();


/**
 * Wedding Dresses of the Week Shortcode - Displays actual WooCommerce products
 * Usage: [wedding_dresses_archive]
 */
function wedding_dresses_archive_shortcode() {
    ob_start();

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 4,
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => 'wedding-dresses-of-the-week',
            ),
        ),
        'meta_query'     => array(
            'relation' => 'AND',
            array(
                'key'     => '_stock_status',
                'value'   => 'instock',
                'compare' => '=',
            ),
            array(
                'key'     => '_price',
                'value'   => 0,
                'compare' => '>=',
                'type'    => 'NUMERIC',
            ),
        ),
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $products_query = new WP_Query($args);

    if ($products_query->have_posts()) : ?>
        <section class="container my-5">
            <h2 class="mb-4 fw-bold">Wedding Dresses of the Week</h2>
            <div class="row g-4">
                <?php while ($products_query->have_posts()) :
                    $products_query->the_post();
                    global $product;
                    if ( ! is_a($product, 'WC_Product') ) continue;

                    $product_id = $product->get_id();

                    // ----- IMAGES -----
                    $attachment_ids = $product->get_gallery_image_ids();
                    $hover_image    = !empty($attachment_ids) ? wp_get_attachment_image_url($attachment_ids[0], 'large') : '';

                    // ----- TITLE / BRAND -----
                    $brand = get_the_title();

                    $dress_name = '';
                    $tech_spec_terms = get_the_terms($product_id, 'pa_technical-spec');
                    if (!empty($tech_spec_terms) && !is_wp_error($tech_spec_terms)) {
                        $dress_name = $tech_spec_terms[0]->name;
                    }
                    if (empty($dress_name)) {
                        $meta_dress_name = get_post_meta($product_id, 'meta_dress_name', true);
                        if (!empty($meta_dress_name) && $meta_dress_name !== '{{product.meta_dress name}}') {
                            $dress_name = $meta_dress_name;
                        }
                    }

                    $product_brand = '';
                    $brand_terms = get_the_terms($product_id, 'pa_brand');
                    if (!empty($brand_terms) && !is_wp_error($brand_terms)) {
                        $product_brand = $brand_terms[0]->name;
                    }

                    $is_new = function_exists('is_product_new') ? is_product_new($product_id) : false;
                    $is_eurosuite = function_exists('is_eurosuit_page') ? is_eurosuit_page($product_id) : false;
                    $newlabel_style = $is_eurosuite ? 'new-badge-euro' : '';

                    // ----- PRICING -----
                    $price_html = wedding_dresses_get_price_block_html($product);
                    ?>
                    <div class="col-md-3">
                        <a href="<?php echo esc_url(get_permalink()); ?>" class="product-card-link">
                            <div class="product-card">
                                <div class="image-container">
                                    <?php if ($is_new): ?>
                                        <span class="label new-label <?php echo esc_attr($newlabel_style); ?>">NEW</span>
                                    <?php endif; ?>

                                    <?php if (has_post_thumbnail()) : ?>
                                        <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>" class="product-image" alt="<?php echo esc_attr($brand); ?>" />
                                    <?php else : ?>
                                        <img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" class="product-image" alt="<?php echo esc_attr($brand); ?>" />
                                    <?php endif; ?>

                                    <?php if ($hover_image) : ?>
                                        <img src="<?php echo esc_url($hover_image); ?>" class="hover-image" alt="<?php echo esc_attr($brand); ?> Hover" />
                                    <?php endif; ?>

                                    <span class="add-to-cart-btn">SHOP NOW</span>
                                </div>

                                <?php if (!empty($dress_name)) : ?>
                                    <h4 class="mt-3 tech-spec-title"><?php echo esc_html($dress_name); ?></h4>
                                    <h5 class="product-title-with-spec"><?php echo esc_html($brand); ?></h5>
                                <?php else : ?>
                                    <h5 class="mt-3 fw-bold"><?php echo esc_html($brand); ?></h5>
                                <?php endif; ?>

                                <p class="product-description"><?php echo wp_trim_words($product->get_short_description(), 10, '...'); ?></p>

                                <?php if (!empty($product_brand)) : ?>
                                    <p class="product-brand"><?php echo esc_html($product_brand); ?></p>
                                <?php endif; ?>

                                <!-- Pricing -->
                                <p class="product-pricing">
                                    <?php echo $price_html; ?>
                                </p>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <style>
            /* --- INLINE PRICE STYLING --- */
            .product-pricing .sale-price {
                color: #c1272d !important; /* deep red */
                font-weight: 700;
                margin-right: 6px;
            }
            .product-pricing .old-price {
                color: #777;
                text-decoration: line-through;
                font-weight: 400;
            }
            .product-pricing .regular-price {
                font-weight: 600;
                color: #222;
            }
            .product-pricing .price-prefix {
                margin-right: 4px;
                opacity: 0.8;
            }
        </style>
    <?php endif;

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('wedding_dresses_archive', 'wedding_dresses_archive_shortcode');


/**
 * Robust Price Display Helper (handles simple + variable + on sale)
 */
function wedding_dresses_get_price_block_html( WC_Product $product ) {
    $type = $product->get_type();

    $wrap_old_new = function( $sale, $regular, $prefix = '' ) {
        $out  = '';
        if ($prefix !== '') {
            $out .= '<span class="price-prefix">'.esc_html($prefix).' </span>';
        }
        $out .= '<span class="sale-price">' . wc_price( $sale ) . '</span>';
        $out .= ' <span class="old-price">' . wc_price( $regular ) . '</span>';
        return $out;
    };

    // SIMPLE PRODUCTS
    if ( $type === 'simple' ) {
        $reg_raw  = (float) $product->get_regular_price();
        $sale_raw = (float) $product->get_sale_price();

        $reg_disp  = wc_get_price_to_display( $product, array( 'price' => $reg_raw ) );
        $sale_disp = ($sale_raw > 0) ? wc_get_price_to_display( $product, array( 'price' => $sale_raw ) ) : 0;

        if ( $product->is_on_sale() && $sale_disp > 0 && $sale_disp < $reg_disp ) {
            return $wrap_old_new( $sale_disp, $reg_disp );
        }

        $display = ($reg_disp > 0) ? $reg_disp : (float) $product->get_price();
        return '<span class="regular-price">' . wc_price( $display ) . '</span>';
    }

    // VARIABLE PRODUCTS
    if ( $type === 'variable' && $product instanceof WC_Product_Variable ) {
        $reg_min_raw   = (float) $product->get_variation_regular_price( 'min', false );
        $sale_min_raw  = (float) $product->get_variation_sale_price( 'min', false );
        $price_min_raw = (float) $product->get_variation_price( 'min', false );

        $reg_min_disp   = ($reg_min_raw  > 0) ? wc_get_price_to_display( $product, array( 'price' => $reg_min_raw  ) ) : 0;
        $sale_min_disp  = ($sale_min_raw > 0) ? wc_get_price_to_display( $product, array( 'price' => $sale_min_raw ) ) : 0;
        $price_min_disp = ($price_min_raw > 0) ? wc_get_price_to_display( $product, array( 'price' => $price_min_raw ) ) : 0;

        if ( $product->is_on_sale() && $sale_min_disp > 0 && $reg_min_disp > 0 && $sale_min_disp < $reg_min_disp ) {
            return $wrap_old_new( $sale_min_disp, $reg_min_disp, __( 'From', 'woocommerce' ) );
        }

        $base = $price_min_disp ?: $reg_min_disp;
        if ( $base > 0 ) {
            return '<span class="price-prefix">'.esc_html__( 'From', 'woocommerce' ).' </span><span class="regular-price">' . wc_price( $base ) . '</span>';
        }

        return $product->get_price_html();
    }

    // Fallback for grouped/external
    return $product->get_price_html();
}


/**
 * White Banner Section for Home Page
 * 
 * Adds a full-width white banner section with editable heading
 * that appears directly in your home page editor.
 */

/**
 * Register ACF fields for white banner section
 */
function bride_co_register_white_banner_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_white_banner',
        'title' => 'White Banner Section',
        'fields' => array(
            array(
                'key' => 'field_show_white_banner',
                'label' => 'Display White Banner',
                'name' => 'show_white_banner',
                'type' => 'true_false',
                'instructions' => 'Enable or disable the white banner section',
                'default_value' => 1,
                'ui' => 1,
            ),
            array(
                'key' => 'field_banner_title',
                'label' => 'Banner Title',
                'name' => 'banner_title',
                'type' => 'text',
                'instructions' => 'Enter the main title text (e.g., "WHITE")',
                'default_value' => 'WHITE',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_white_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_banner_subtitle',
                'label' => 'Banner Subtitle',
                'name' => 'banner_subtitle',
                'type' => 'text',
                'instructions' => 'Enter the subtitle text (e.g., "WEDDING DRESSES")',
                'default_value' => 'WEDDING DRESSES',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_white_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_banner_button_text',
                'label' => 'Button Text',
                'name' => 'banner_button_text',
                'type' => 'text',
                'instructions' => 'Enter the text for the button (e.g., "EXPLORE")',
                'default_value' => 'EXPLORE',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_white_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_banner_button_link',
                'label' => 'Button Link',
                'name' => 'banner_button_link',
                'type' => 'url',
                'instructions' => 'Enter the URL for the button',
                'default_value' => '#',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_white_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_banner_background_image',
                'label' => 'Background Image',
                'name' => 'banner_background_image',
                'type' => 'image',
                'instructions' => 'Upload or select the background image for the banner',
                'required' => 1,
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_white_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_text_position',
                'label' => 'Text Position',
                'name' => 'text_position',
                'type' => 'select',
                'instructions' => 'Choose the text alignment within the banner',
                'choices' => array(
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right',
                ),
                'default_value' => 'left',
                'return_format' => 'value',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_white_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_text_alignment',
                'label' => 'Text Alignment',
                'name' => 'text_alignment',
                'type' => 'select',
                'instructions' => 'Choose how to align the text content',
                'choices' => array(
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right',
                ),
                'default_value' => 'center',
                'return_format' => 'value',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_white_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_vertical_position',
                'label' => 'Vertical Position',
                'name' => 'vertical_position',
                'type' => 'select',
                'instructions' => 'Choose the vertical positioning of text',
                'choices' => array(
                    'top' => 'Top',
                    'middle' => 'Middle',
                    'bottom' => 'Bottom',
                ),
                'default_value' => 'middle',
                'return_format' => 'value',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_white_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_text_padding',
                'label' => 'Text Padding',
                'name' => 'text_padding',
                'type' => 'number',
                'instructions' => 'Padding around text in pixels',
                'default_value' => 40,
                'min' => 0,
                'max' => 100,
                'step' => 5,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_white_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'front_page',
                ),
            ),
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'page-home.php',
                ),
            ),
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'templates/home-page.php',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));
}
add_action('acf/init', 'bride_co_register_white_banner_fields');

/**
 * Add CSS for white banner
 */
function bride_co_white_banner_css() {
    if (is_front_page()) {
        ?>
        <style>
            .beyaz-banner {
                position: relative;
                width: 100%;
                overflow: hidden;
                margin: 3rem 0;
            }
            
            .banner-content {
                display: flex;
                min-height: 450px;
                position: relative;
            }
            
            .banner-text {
                width: 50%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                background-color: #fff;
                z-index: 2;
                position: relative;
            }
            
            /* Text position classes */
            .banner-text.text-left {
                left: 0;
                margin-right: auto;
            }
            
            .banner-text.text-center {
                left: 0;
                width: 50%;
            }
            
            .banner-text.text-right {
                right: 0;
                left: auto;
                margin-left: auto;
            }
            
            /* Vertical alignment classes */
            .banner-text.align-top {
                justify-content: flex-start;
                padding-top: 40px;
            }
            
            .banner-text.align-middle {
                justify-content: center;
            }
            
            .banner-text.align-bottom {
                justify-content: flex-end;
                padding-bottom: 40px;
            }
            
            .banner-image {
                position: absolute;
                right: 0;
                top: 0;
                width: 65%;
                height: 100%;
                background-size: cover;
                background-position: center;
                z-index: 1;
            }
            
            .banner-title {
                font-size: 3.5rem;
                font-weight: 700;
                margin-bottom: 1rem;
                letter-spacing: 2px;
            }
            
            .banner-subtitle {
                font-size: 1.8rem;
                margin-bottom: 2rem;
                letter-spacing: 1px;
            }
            
            .banner-button {
                display: inline-block;
                padding: 12px 30px;
                background-color: #000;
                color: #fff!important;
                text-decoration: none;
                font-weight: 600;
                letter-spacing: 1px;
                transition: all 0.3s ease;
            }
            
            .banner-button:hover {
                background-color: #333;
                color: #fff;
            }
            
            @media (max-width: 991px) {
                .banner-text {
                    width: 60%;
                }
                
                .banner-image {
                    width: 55%;
                }
            }
            
            @media (max-width: 767px) {
                .banner-content {
                    flex-direction: column;
                }
                
                .banner-text {
                    width: 100%;
                    order: 2;
                    padding: 30px 20px;
                }
                
                .banner-image {
                    position: relative;
                    width: 100%;
                    height: 300px;
                    order: 1;
                }
                
                .banner-title {
                    font-size: 2.5rem;
                }
                
                .banner-subtitle {
                    font-size: 1.5rem;
                }
            }
        </style>
        <?php
    }
}
add_action('wp_head', 'bride_co_white_banner_css');

/**
 * Function to render white banner section in template
 */
function bride_co_render_white_banner() {
    // Check if we're on the home page
    if (!is_front_page()) {
        return;
    }
    
    // Check if white banner should be displayed
    $show_banner = get_field('show_white_banner');
    
    if (!$show_banner) {
        return;
    }
    
    // Get the banner data
    $banner_title = get_field('banner_title');
    $banner_subtitle = get_field('banner_subtitle');
    $button_text = get_field('banner_button_text');
    $button_link = get_field('banner_button_link');
    $background_image = get_field('banner_background_image');
    
    // Get positioning settings
    $text_position = get_field('text_position') ?: 'left';
    $text_alignment = get_field('text_alignment') ?: 'center';
    $vertical_position = get_field('vertical_position') ?: 'middle';
    $text_padding = get_field('text_padding') ?: 40;
    
    // Skip if missing essential data
    if (empty($banner_title) || empty($banner_subtitle) || empty($button_text) || empty($button_link) || empty($background_image)) {
        return;
    }
    
    // Generate background style
    $bg_style = '';
    if (!empty($background_image) && is_array($background_image)) {
        $bg_style = 'background-image: url(' . esc_url($background_image['url']) . ');';
    }
    
    // Generate text position classes and styles
    $text_position_class = 'text-' . $text_position;
    $text_align_style = 'text-align: ' . $text_alignment . ';';
    $text_padding_style = 'padding: ' . $text_padding . 'px;';
    
    // Define vertical alignment class
    $vertical_align_class = '';
    switch ($vertical_position) {
        case 'top':
            $vertical_align_class = 'align-top';
            break;
        case 'bottom':
            $vertical_align_class = 'align-bottom';
            break;
        default:
            $vertical_align_class = 'align-middle';
            break;
    }
    
    ?>
<!-- WHITE Banner Section -->
<section class="container-fluid my-5 px-0">
  <div class="beyaz-banner">
    <div class="banner-content">
      <div class="banner-text <?php echo esc_attr($text_position_class . ' ' . $vertical_align_class); ?>" style="<?php echo esc_attr($text_align_style . ' ' . $text_padding_style); ?>">
        <h2 class="banner-title"><?php echo esc_html($banner_title); ?></h2>
        <p class="banner-subtitle"><?php echo esc_html($banner_subtitle); ?></p>
        <a href="<?php echo esc_url($button_link); ?>" class="banner-button"><?php echo esc_html($button_text); ?></a>
      </div>
      <div class="banner-image" style="<?php echo $bg_style; ?>">
        <!-- The image is loaded as the background -->
      </div>
    </div>
  </div>
</section>
    <?php
}

// Add this code to your home page template (front-page.php or home.php):
// <?php bride_co_render_white_banner(); 



/**
 * Appointment Section with Image for Home Page
 * 
 * Adds an appointment section with editable image and text
 * that appears directly in your home page editor.
 */

/**
 * Register ACF fields for appointment section
 */
function bride_co_register_appointment_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_appointment_section',
        'title' => 'Appointment Section',
        'fields' => array(
            array(
                'key' => 'field_show_appointment',
                'label' => 'Display Appointment Section',
                'name' => 'show_appointment',
                'type' => 'true_false',
                'instructions' => 'Enable or disable the appointment section',
                'default_value' => 1,
                'ui' => 1,
            ),
            array(
                'key' => 'field_appointment_image',
                'label' => 'Appointment Image',
                'name' => 'appointment_image',
                'type' => 'image',
                'instructions' => 'Upload or select an image for the appointment section',
                'required' => 1,
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_appointment',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_appointment_text',
                'label' => 'Appointment Text',
                'name' => 'appointment_text',
                'type' => 'wysiwyg',
                'instructions' => 'Enter the text for the appointment section. Use the bold option to highlight specific text.',
                'required' => 1,
                'default_value' => 'Book a <strong>wedding dress appointment</strong> now to find your dream dress!',
                'tabs' => 'visual',
                'media_upload' => 0,
                'toolbar' => 'basic',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_appointment',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_appointment_button_text',
                'label' => 'Button Text',
                'name' => 'appointment_button_text',
                'type' => 'text',
                'instructions' => 'Enter the text for the button',
                'required' => 1,
                'default_value' => 'BOOK A FREE APPOINTMENT',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_appointment',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_appointment_button_link',
                'label' => 'Button Link',
                'name' => 'appointment_button_link',
                'type' => 'url',
                'instructions' => 'Enter the URL for the button',
                'required' => 1,
                'default_value' => '#',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_appointment',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'front_page',
                ),
            ),
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'page-home.php',
                ),
            ),
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'templates/home-page.php',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));
}
add_action('acf/init', 'bride_co_register_appointment_fields');

/**
 * Add CSS for appointment section
 */
function bride_co_appointment_css() {
    if (is_front_page()) {
        ?>
        <style>
            /* Appointment Section Styling */
            .appointment-section {
                display: flex;
                align-items: center;
                margin: 80px auto;
                background-color: #f9f9f9;
                padding: 0;
                overflow: hidden;
            }
            
            .appointment-image {
                width: 50%;
                height: 100%;
                overflow: hidden;
            }
            
            .appointment-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }
            
            .appointment-text {
                width: 50%;
                padding: 60px;
                text-align: center;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            
            .appointment-text p {
                font-family: "Poppins", sans-serif;
                font-size: 1.5rem;
                margin-bottom: 30px;
                line-height: 1.5;
                color: #333;
            }
            
            .bold-text, .appointment-text p strong {
                font-weight: 700;
                color: #000;
            }
            
            .cta-button {
                display: inline-block;
                background-color: #333;
                color: #fff;
                font-family: "Poppins", sans-serif;
                font-size: 1rem;
                text-transform: uppercase;
                padding: 15px 30px;
                text-decoration: none;
                letter-spacing: 1px;
                transition: all 0.3s ease;
                border: 2px solid #333;
            }
            
            .cta-button:hover {
                background-color: transparent;
                color: #333;
                text-decoration: none;
            }
            
            /* Responsive adjustments */
            @media (max-width: 991px) {
                .appointment-text {
                    padding: 40px;
                }
                
                .appointment-text p {
                    font-size: 1.3rem;
                }
            }
            
            @media (max-width: 767px) {
                .appointment-section {
                    flex-direction: column;
                }
                
                .appointment-image,
                .appointment-text {
                    width: 100%;
                }
                
                .appointment-image {
                    height: 300px;
                }
                
                .appointment-text {
                    padding: 40px 20px;
                }
                
                .appointment-text p {
                    font-size: 1.2rem;
                }
            }
        </style>
        <?php
    }
}
add_action('wp_head', 'bride_co_appointment_css');

/**
 * Function to render appointment section in template
 */
function bride_co_render_appointment() {
    // Check if we're on the home page
    if (!is_front_page()) {
        return;
    }
    
    // Check if appointment section should be displayed
    $show_appointment = get_field('show_appointment');
    
    if (!$show_appointment) {
        return;
    }
    
    // Get the appointment data
    $appointment_image = get_field('appointment_image');
    $appointment_text = get_field('appointment_text');
    $button_text = get_field('appointment_button_text');
    $button_link = get_field('appointment_button_link');
    
    // Skip if missing essential data
    if (empty($appointment_image) || empty($appointment_text) || empty($button_text) || empty($button_link)) {
        return;
    }
    
    ?>
<!-- Image with text Section -->
<section class="container appointment-section">
  <div class="appointment-image">
    <img src="<?php echo esc_url($appointment_image['url']); ?>" alt="<?php echo esc_attr($appointment_image['alt']); ?>" />
  </div>
  <div class="appointment-text">
    <p><?php echo wp_kses_post($appointment_text); ?></p>
    <a href="<?php echo esc_url($button_link); ?>" class="cta-button"><?php echo esc_html($button_text); ?></a>
  </div>
</section>
    <?php
}

// Add this code to your home page template (front-page.php or home.php):
// <?php bride_co_render_appointment();
/**
 * Evening Dresses of the Week Shortcode - Displays actual WooCommerce products
 * Usage: [evening_dresses_archive]
 */
function evening_dresses_archive_shortcode() {
    ob_start();

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 4,
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => 'special-occasions',
            ),
        ),
        'meta_query'     => array(
            'relation' => 'AND',
            array(
                'key'     => '_stock_status',
                'value'   => 'instock',
                'compare' => '=',
            ),
            array(
                'key'     => '_price',
                'value'   => 0,
                'compare' => '>=',
                'type'    => 'NUMERIC',
            ),
        ),
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $products_query = new WP_Query($args);

    if ($products_query->have_posts()) : ?>
        <section class="container my-5">
            <h2 class="mb-4 fw-bold">Evening Dresses of the Week</h2>
            <div class="row g-4">
                <?php while ($products_query->have_posts()) :
                    $products_query->the_post();
                    global $product;
                    if ( ! is_a($product, 'WC_Product') ) continue;

                    $product_id = $product->get_id();

                    // ----- IMAGES -----
                    $attachment_ids = $product->get_gallery_image_ids();
                    $hover_image    = !empty($attachment_ids) ? wp_get_attachment_image_url($attachment_ids[0], 'large') : '';

                    // ----- TITLE / BRAND -----
                    $brand = get_the_title();

                    $dress_name = '';
                    $tech_spec_terms = get_the_terms($product_id, 'pa_technical-spec');
                    if (!empty($tech_spec_terms) && !is_wp_error($tech_spec_terms)) {
                        $dress_name = $tech_spec_terms[0]->name;
                    }
                    if (empty($dress_name)) {
                        $meta_dress_name = get_post_meta($product_id, 'meta_dress_name', true);
                        if (!empty($meta_dress_name) && $meta_dress_name !== '{{product.meta_dress name}}') {
                            $dress_name = $meta_dress_name;
                        }
                    }

                    $product_brand = '';
                    $brand_terms = get_the_terms($product_id, 'pa_brand');
                    if (!empty($brand_terms) && !is_wp_error($brand_terms)) {
                        $product_brand = $brand_terms[0]->name;
                    }

                    $is_new = function_exists('is_product_new') ? is_product_new($product_id) : false;
                    $is_eurosuite = function_exists('is_eurosuit_page') ? is_eurosuit_page($product_id) : false;
                    $newlabel_style = $is_eurosuite ? 'new-badge-euro' : '';

                    // ----- PRICING -----
                    $price_html = evening_dresses_get_price_block_html($product);
                    ?>
                    <div class="col-md-3">
                        <a href="<?php echo esc_url(get_permalink()); ?>" class="product-card-link">
                            <div class="product-card">
                                <div class="image-container">
                                    <?php if ($is_new): ?>
                                        <span class="label new-label <?php echo esc_attr($newlabel_style); ?>">NEW</span>
                                    <?php endif; ?>

                                    <?php if (has_post_thumbnail()) : ?>
                                        <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>" class="product-image" alt="<?php echo esc_attr($brand); ?>" />
                                    <?php else : ?>
                                        <img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" class="product-image" alt="<?php echo esc_attr($brand); ?>" />
                                    <?php endif; ?>

                                    <?php if ($hover_image) : ?>
                                        <img src="<?php echo esc_url($hover_image); ?>" class="hover-image" alt="<?php echo esc_attr($brand); ?> Hover" />
                                    <?php endif; ?>

                                    <span class="add-to-cart-btn">SHOP NOW</span>
                                </div>

                                <?php if (!empty($dress_name)) : ?>
                                    <h4 class="mt-3 tech-spec-title"><?php echo esc_html($dress_name); ?></h4>
                                    <h5 class="product-title-with-spec"><?php echo esc_html($brand); ?></h5>
                                <?php else : ?>
                                    <h5 class="mt-3 fw-bold"><?php echo esc_html($brand); ?></h5>
                                <?php endif; ?>

                                <p class="product-description"><?php echo wp_trim_words($product->get_short_description(), 10, '...'); ?></p>

                                <?php if (!empty($product_brand)) : ?>
                                    <p class="product-brand"><?php echo esc_html($product_brand); ?></p>
                                <?php endif; ?>

                                <!-- Pricing -->
                                <p class="product-pricing">
                                    <?php echo $price_html; ?>
                                </p>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <style>
            /* --- INLINE PRICE STYLING --- */
            .product-pricing .sale-price {
                color: #c1272d !important; /* deep red for sale */
                font-weight: 700;
                margin-right: 6px;
            }
            .product-pricing .old-price {
                color: #777;
                text-decoration: line-through;
                font-weight: 400;
            }
            .product-pricing .regular-price {
                font-weight: 600;
                color: #222;
            }
            .product-pricing .price-prefix {
                margin-right: 4px;
                opacity: 0.8;
            }
        </style>
    <?php endif;

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('evening_dresses_archive', 'evening_dresses_archive_shortcode');


/**
 * Robust Price Display Helper (handles simple + variable + on sale)
 */
function evening_dresses_get_price_block_html( WC_Product $product ) {
    $type = $product->get_type();

    $wrap_old_new = function( $sale, $regular, $prefix = '' ) {
        $out  = '';
        if ($prefix !== '') {
            $out .= '<span class="price-prefix">'.esc_html($prefix).' </span>';
        }
                $out .= ' <span class="old-price">' . wc_price( $regular ) . '</span>';

        $out .= '<span class="sale-price">' . wc_price( $sale ) . '</span>';
        return $out;
    };

    // SIMPLE PRODUCTS
    if ( $type === 'simple' ) {
        $reg_raw  = (float) $product->get_regular_price();
        $sale_raw = (float) $product->get_sale_price();

        $reg_disp  = wc_get_price_to_display( $product, array( 'price' => $reg_raw ) );
        $sale_disp = ($sale_raw > 0) ? wc_get_price_to_display( $product, array( 'price' => $sale_raw ) ) : 0;

        if ( $product->is_on_sale() && $sale_disp > 0 && $sale_disp < $reg_disp ) {
            return $wrap_old_new( $sale_disp, $reg_disp );
        }

        $display = ($reg_disp > 0) ? $reg_disp : (float) $product->get_price();
        return '<span class="regular-price">' . wc_price( $display ) . '</span>';
    }

    // VARIABLE PRODUCTS
    if ( $type === 'variable' && $product instanceof WC_Product_Variable ) {
        $reg_min_raw   = (float) $product->get_variation_regular_price( 'min', false );
        $sale_min_raw  = (float) $product->get_variation_sale_price( 'min', false );
        $price_min_raw = (float) $product->get_variation_price( 'min', false );

        $reg_min_disp   = ($reg_min_raw  > 0) ? wc_get_price_to_display( $product, array( 'price' => $reg_min_raw  ) ) : 0;
        $sale_min_disp  = ($sale_min_raw > 0) ? wc_get_price_to_display( $product, array( 'price' => $sale_min_raw ) ) : 0;
        $price_min_disp = ($price_min_raw > 0) ? wc_get_price_to_display( $product, array( 'price' => $price_min_raw ) ) : 0;

        if ( $product->is_on_sale() && $sale_min_disp > 0 && $reg_min_disp > 0 && $sale_min_disp < $reg_min_disp ) {
            return $wrap_old_new( $sale_min_disp, $reg_min_disp, __( '', 'woocommerce' ) );
        }

        $base = $price_min_disp ?: $reg_min_disp;
        if ( $base > 0 ) {
            return '<span class="price-prefix">'.esc_html__( '', 'woocommerce' ).' </span><span class="regular-price">' . wc_price( $base ) . '</span>';
        }

        return $product->get_price_html();
    }

    // Fallback for grouped/external
    return $product->get_price_html();
}

/**
 * Silhouette Carousel Section for Home Page
 * 
 * Adds a silhouette carousel section with editable items
 * that appears directly in your home page editor.
 */

/**
 * Register ACF fields for silhouette carousel section
 */
function bride_co_register_silhouette_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_silhouette_carousel',
        'title' => 'Silhouette Carousel Section',
        'fields' => array(
            array(
                'key' => 'field_show_silhouette',
                'label' => 'Display Silhouette Carousel',
                'name' => 'show_silhouette',
                'type' => 'true_false',
                'instructions' => 'Enable or disable the silhouette carousel section',
                'default_value' => 1,
                'ui' => 1,
            ),
            array(
                'key' => 'field_silhouette_heading',
                'label' => 'Section Heading',
                'name' => 'silhouette_heading',
                'type' => 'text',
                'instructions' => 'Enter a heading for this section',
                'default_value' => 'Silhouette',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_silhouette',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_silhouette_items',
                'label' => 'Silhouette Items',
                'name' => 'silhouette_items',
                'type' => 'repeater',
                'instructions' => 'Add silhouette items to the carousel',
                'min' => 1,
                'max' => 0,
                'layout' => 'block',
                'button_label' => 'Add Silhouette Item',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_silhouette',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_silhouette_image',
                        'label' => 'Silhouette Image',
                        'name' => 'silhouette_image',
                        'type' => 'image',
                        'instructions' => 'Upload or select an image for this silhouette',
                        'required' => 1,
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                    ),
                    array(
                        'key' => 'field_silhouette_title',
                        'label' => 'Silhouette Title',
                        'name' => 'silhouette_title',
                        'type' => 'text',
                        'instructions' => 'Enter a title for this silhouette (e.g., "Ballgown")',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_silhouette_button_text',
                        'label' => 'Button Text',
                        'name' => 'silhouette_button_text',
                        'type' => 'text',
                        'instructions' => 'Enter the text for the button (e.g., "VIEW NOW")',
                        'required' => 1,
                        'default_value' => 'VIEW NOW',
                    ),
                    array(
                        'key' => 'field_silhouette_button_link',
                        'label' => 'Button Link',
                        'name' => 'silhouette_button_link',
                        'type' => 'url',
                        'instructions' => 'Enter the URL for the button',
                        'required' => 1,
                        'default_value' => '#',
                    ),
                ),
            ),
            array(
                'key' => 'field_auto_rotate',
                'label' => 'Auto-Rotate Carousel',
                'name' => 'auto_rotate',
                'type' => 'true_false',
                'instructions' => 'Enable or disable auto-rotation of the carousel',
                'default_value' => 1,
                'ui' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_silhouette',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_rotation_speed',
                'label' => 'Rotation Speed (seconds)',
                'name' => 'rotation_speed',
                'type' => 'number',
                'instructions' => 'Set the time between rotations in seconds',
                'default_value' => 3,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_auto_rotate',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'front_page',
                ),
            ),
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'page-home.php',
                ),
            ),
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'templates/home-page.php',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));
}
add_action('acf/init', 'bride_co_register_silhouette_fields');

/**
 * Add CSS for silhouette carousel
 */
function bride_co_silhouette_css() {
    if (is_front_page()) {
        ?>
        <style>

        </style>
        <?php
    }
}
add_action('wp_head', 'bride_co_silhouette_css');

/**
 * Function to render silhouette carousel section in template
 */
function bride_co_render_silhouette() {
    // Check if we're on the home page
    if (!is_front_page()) {
        return;
    }
    
    // Check if silhouette section should be displayed
    $show_silhouette = get_field('show_silhouette');
    
    if (!$show_silhouette) {
        return;
    }
    
    // Get the silhouette data
    $silhouette_heading = get_field('silhouette_heading');
    $silhouette_items = get_field('silhouette_items');
    $auto_rotate = get_field('auto_rotate');
    $rotation_speed = get_field('rotation_speed') ? get_field('rotation_speed') : 3;
    
    // Skip if no items
    if (empty($silhouette_items)) {
        return;
    }
    
    // Generate a unique ID for this carousel instance
    $carousel_id = 'silhouette-carousel-' . uniqid();
    
    ?>
<section class="container my-5 Silhouette">
    <h2 class="mb-4 fw-bold"><?php echo esc_html($silhouette_heading); ?></h2>
    <div class="carousel-container">
        <div class="carousel" id="<?php echo esc_attr($carousel_id); ?>-carousel">
            <?php foreach ($silhouette_items as $item) : 
                // Skip if missing essential data
                if (empty($item['silhouette_image']) || empty($item['silhouette_title']) || 
                    empty($item['silhouette_button_text']) || empty($item['silhouette_button_link'])) {
                    continue;
                }
            ?>
                <div class="carousel-item">
                    <img src="<?php echo esc_url($item['silhouette_image']['url']); ?>" 
                         alt="<?php echo esc_attr($item['silhouette_title']); ?>" />
                    <h2><?php echo esc_html($item['silhouette_title']); ?></h2>
                    <a href="<?php echo esc_url($item['silhouette_button_link']); ?>" 
                       class="view-now"><?php echo esc_html($item['silhouette_button_text']); ?></a>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="prev" id="<?php echo esc_attr($carousel_id); ?>-prev">&#10094;</button>
        <button class="next" id="<?php echo esc_attr($carousel_id); ?>-next">&#10095;</button>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the carousel
            let index = 0;
            const carousel = document.getElementById('<?php echo esc_js($carousel_id); ?>-carousel');
            const carouselItems = carousel.querySelectorAll('.carousel-item');
            let visibleItems = 4; // Default visible items
            
            // Determine number of visible items based on screen width
            function updateVisibleItems() {
                if (window.innerWidth <= 480) {
                    visibleItems = 1;
                } else if (window.innerWidth <= 767) {
                    visibleItems = 2;
                } else if (window.innerWidth <= 991) {
                    visibleItems = 3;
                } else {
                    visibleItems = 4;
                }
            }
            
            updateVisibleItems();
            window.addEventListener('resize', updateVisibleItems);
            
            const totalSlides = Math.ceil(carouselItems.length / visibleItems);
            
            // Function to move slide
            function moveSlide(direction) {
                index += direction;
                if (index < 0) index = totalSlides - 1;
                if (index >= totalSlides) index = 0;
                
                // Calculate the translation percentage
                const translatePercentage = (100 / visibleItems) * index * visibleItems;
                carousel.style.transform = `translateX(-${translatePercentage}%)`;
            }
            
            // Set up event listeners for buttons
            document.getElementById('<?php echo esc_js($carousel_id); ?>-prev').addEventListener('click', function() {
                moveSlide(-1);
            });
            
            document.getElementById('<?php echo esc_js($carousel_id); ?>-next').addEventListener('click', function() {
                moveSlide(1);
            });
            
            <?php if ($auto_rotate) : ?>
            // Auto-rotate carousel
            let autoRotateInterval = setInterval(() => moveSlide(1), <?php echo intval($rotation_speed) * 1000; ?>);
            
            // Pause auto-rotation when hovering over carousel
            carousel.parentElement.addEventListener('mouseenter', function() {
                clearInterval(autoRotateInterval);
            });
            
            carousel.parentElement.addEventListener('mouseleave', function() {
                autoRotateInterval = setInterval(() => moveSlide(1), <?php echo intval($rotation_speed) * 1000; ?>);
            });
            <?php endif; ?>
        });
    </script>
</section>
    <?php
}

// Add this code to your home page template (front-page.php or home.php):
// <?php bride_co_render_silhouette(); 




/**
 * Categories Section Shortcode
 * 
 * Displays product categories as a grid of circular images.
 * Usage: [product_categories]
 */

/**
 * Register ACF fields for categories section
 */
function bride_co_register_categories_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_categories_section',
        'title' => 'Categories Section',
        'fields' => array(
            array(
                'key' => 'field_show_categories',
                'label' => 'Display Categories Section',
                'name' => 'show_categories',
                'type' => 'true_false',
                'instructions' => 'Enable or disable the categories section',
                'default_value' => 1,
                'ui' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'front_page',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));
}
add_action('acf/init', 'bride_co_register_categories_fields');

/**
 * Add CSS for categories section
 */
function bride_co_categories_css() {
    // Only add CSS once per page
    static $css_added = false;
    
    if ($css_added) {
        return;
    }
    
    $css_added = true;
    
    ?>
    <style>
        /* Categories Section Styling */
        .categories-section {
            padding: 50px 0;
            background-color: #fff;
        }
        
        .category-item {
            text-align: center;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .category-item:hover {
            transform: translateY(-5px);
        }
        
        .category-image {
            width: 100%;
            height: auto;
            border-radius: 50%;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            aspect-ratio: 1/1;
            object-fit: cover;
        }
        
        .category-item:hover .category-image {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .category-name {
            font-family: "Poppins", sans-serif;
            font-size: 0.9rem;
            color: #333;
            margin-top: 10px;
            font-weight: 500;
        }
        
        @media (max-width: 767px) {
            .category-name {
                font-size: 0.8rem;
            }
        }
    </style>
    <?php
}

/**
 * Generate the categories HTML content
 * 
 * @return string HTML output for the categories section
 */
function bride_co_get_categories_html() {
    // Start output buffer to capture HTML
    ob_start();
    
    // Check if categories section should be displayed (only if used on front page with ACF)
    if (is_front_page()) {
        $show_categories = get_field('show_categories');
        if ($show_categories === false) {
            return '';
        }
    }
    
    // Hardcoded default category data to ensure display even without WooCommerce
    $default_categories = array(
        array(
            'name' => 'After Party',
            'link' => '#',
            'image' => 'https://cdn.myikas.com/images/theme-images/1219b754-712e-4443-9c1e-ac4f4f16c46d/image_720.webp'
        ),
        array(
            'name' => 'Engagement Dresses',
            'link' => '#',
            'image' => 'https://cdn.myikas.com/images/theme-images/f4daba50-8765-4c77-8d97-0fd08d7281d6/image_720.webp'
        ),
        array(
            'name' => 'Graduation Dresses',
            'link' => '#',
            'image' => 'https://cdn.myikas.com/images/theme-images/7f71a623-0f8c-4b4f-b62f-53f439b5d801/image_720.webp'
        ),
        array(
            'name' => 'Children\'s Evening Wear',
            'link' => '#',
            'image' => 'https://cdn.myikas.com/images/theme-images/0a3749a1-5633-4e87-8c0c-60dfcb570a76/image_720.webp'
        ),
        array(
            'name' => 'Online Exclusive Collection',
            'link' => '#',
            'image' => 'https://cdn.myikas.com/images/theme-images/a8f77ba9-20bd-4da5-bebf-810cfde9d541/image_720.webp'
        ),
        array(
            'name' => 'Accessories',
            'link' => '#',
            'image' => 'https://cdn.myikas.com/images/theme-images/b8100cbe-0867-4fef-9a44-a17ce8f0406b/image_720.webp'
        )
    );
    
    // Initialize categories_data with defaults
    $categories_data = $default_categories;
    
    // Only attempt WooCommerce integration if it's active
    if (function_exists('WC')) {
        // Try to get WooCommerce categories
        try {
            $wc_categories = array();
            
            $args = array(
                'taxonomy'   => 'product_cat',
                'orderby'    => 'id',
                'order'      => 'DESC',
                'hide_empty' => true,
                'number'     => 6,
                'exclude'    => array(get_option('default_product_cat')), // Exclude "Uncategorized"
            );
            
            // Get the product categories safely
            $product_categories = get_terms($args);
            
            // Only proceed if valid categories were found
            if (!empty($product_categories) && !is_wp_error($product_categories)) {
                foreach ($product_categories as $index => $category) {
                    // Get category link safely
                    $category_link = get_term_link($category);
                    if (is_wp_error($category_link)) {
                        $category_link = '#';
                    }
                    
                    // Get category image
                    $image_url = '';
                    $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                    
                    if ($thumbnail_id) {
                        $image = wp_get_attachment_image_src($thumbnail_id, 'medium');
                        if ($image && isset($image[0])) {
                            $image_url = $image[0];
                        }
                    }
                    
                    // Use default image as fallback
                    if (empty($image_url)) {
                        // Default to placeholder or use default category images
                        $fallback_index = $index % count($default_categories);
                        $image_url = $default_categories[$fallback_index]['image'];
                    }
                    
                    // Add to our category data array
                    $wc_categories[] = array(
                        'name' => $category->name,
                        'link' => $category_link,
                        'image' => $image_url
                    );
                }
                
                // Only replace defaults if we found some categories
                if (!empty($wc_categories)) {
                    $categories_data = $wc_categories;
                }
            }
        } catch (Exception $e) {
            // On any error, keep using the default data
            // Optionally log the error if needed
        }
    }
    
    // Ensure we only display up to 6 categories
    $categories_data = array_slice($categories_data, 0, 6);
    
    // Only output HTML if we have categories to display
    if (empty($categories_data)) {
        return '';
    }
    
    // Add the CSS
    bride_co_categories_css();
    
    ?>
<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <div class="row justify-content-center g-4">
            <?php 
            foreach ($categories_data as $category) : 
                // Ensure required keys exist
                $name = isset($category['name']) ? $category['name'] : '';
                $link = isset($category['link']) ? $category['link'] : '#';
                $image = isset($category['image']) ? $category['image'] : '';
                
                // Skip if no name or image
                if (empty($name) || empty($image)) {
                    continue;
                }
            ?>
                <div class="col-md-2 col-4">
                    <div class="category-item">
                        <a href="<?php echo esc_url($link); ?>">
                            <img src="<?php echo esc_url($image); ?>" 
                                 class="category-image" 
                                 alt="<?php echo esc_attr($name); ?>" />
                        </a>
                        <p class="category-name"><?php echo esc_html($name); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
    <?php
    
    // Return the buffered content
    return ob_get_clean();
}

/**
 * Create the shortcode for the categories section
 * 
 * @return string HTML output
 */
function bride_co_categories_shortcode() {
    return bride_co_get_categories_html();
}
add_shortcode('product_categories', 'bride_co_categories_shortcode');

/**
 * Original function for backward compatibility (can be called directly from templates)
 */
function bride_co_render_categories() {
    echo bride_co_get_categories_html();
}

/**
 * Usage:
 * 
 * As a shortcode in the content editor:
 * [product_categories]
 * 
 * Or directly in a template file:
 * <?php bride_co_render_categories(); ?>
 */

 /**
  * Accessories Child Categories Display
  * 
  * Displays child categories of the "Accessories" product category.
  * Can be used as a shortcode or directly called in templates.
  */
 
 /**
  * Register ACF fields for accessories categories section (optional)
  */
  function bride_co_register_accessories_categories_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_accessories_categories_section',
        'title' => 'Accessories Categories Section',
        'fields' => array(
            array(
                'key' => 'field_show_accessories_categories',
                'label' => 'Display Accessories Categories Section',
                'name' => 'show_accessories_categories',
                'type' => 'true_false',
                'instructions' => 'Enable or disable the accessories categories section',
                'default_value' => 1,
                'ui' => 1,
            ),
            array(
                'key' => 'field_accessories_categories_title',
                'label' => 'Section Title',
                'name' => 'accessories_categories_title',
                'type' => 'text',
                'instructions' => 'Enter the title for the accessories categories section',
                'default_value' => 'Accessories Categories',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'page-accessories.php',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));
}
add_action('acf/init', 'bride_co_register_accessories_categories_fields');

/**
 * Add CSS for accessories child categories
 */
function bride_co_accessories_categories_css() {
    // Only add CSS once per page
    static $css_added = false;
    
    if ($css_added) {
        return;
    }
    
    $css_added = true;
    
    ?>
    <style>
        /* Accessories Child Categories Styling */
        .accessories-categories-section {
            padding: 40px 0;
            margin-bottom: 30px;
        }
        
        .accessories-section-title {
           font-size: 22px;
   margin-bottom: 25px !important;
   font-weight: 400;
   color: #333;
   text-align: left;
 
        }
        
        
        
        .accessory-category-item {
            text-align: center;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            display: block;
        }
        
        .accessory-category-item:hover {
            transform: translateY(-5px);
        }
        
        .accessory-category-image-wrapper {
            position: relative;
            padding-bottom: 100%; /* 1:1 Aspect Ratio */
            overflow: hidden;
            border-radius: 50%;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            background-color: #f9f9f9;
            width :120px;
            height:auto;
        }
        
        .accessory-category-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.3s ease;
        }
        
        .accessory-category-item:hover .accessory-category-image-wrapper {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .accessory-category-item:hover .accessory-category-image {
            transform: scale(1.05);
        }
        
        .accessory-category-name {
            font-family: "Poppins", sans-serif;
            font-size: 0.9rem;
            color: #333;
            margin-top: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .accessory-category-item:hover .accessory-category-name {
            color: #f0c4d9;
        }
        
        .accessory-count {
            font-size: 0.8rem;
            color: #888;
            display: block;
            margin-top: 2px;
        }
        
        .no-categories-found {
            text-align: center;
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 5px;
            color: #666;
            font-style: italic;
        }
        
        @media (max-width: 767px) {
            .accessories-section-title {
                font-size: 1.5rem;
                margin-bottom: 10px;
            }
            
            .accessory-category-name {
                font-size: 0.8rem;
            }
            
            .accessory-count {
                font-size: 0.7rem;
            }
        }
    </style>
    <?php
}

/**
 * Generate the accessories child categories HTML content
 * 
 * @param array $args Configuration arguments
 * @return string HTML output for the accessories child categories section
 */
function bride_co_get_accessories_child_categories_html($args = array()) {
    // Start output buffer to capture HTML
    ob_start();
    
    // Default arguments
    $defaults = array(
        'title' => 'Accessories',
        'parent_slug' => 'accessories',
        'parent_name' => 'Accessories',
        'cols_desktop' => 6,
        'cols_mobile' => 4,
        'max_items' => 12,
        'show_count' => true,
        'hide_empty' => true // Default to hiding empty categories
    );
    
    // Merge provided args with defaults
    $args = wp_parse_args($args, $defaults);
    
    // Check if WooCommerce is active
    if (!function_exists('WC')) {
        return '<div class="no-categories-found">WooCommerce is required to display accessory categories.</div>';
    }
    
    // Check if we should use ACF field for title (if on a page with ACF and field exists)
    if (function_exists('get_field') && is_page()) {
        $acf_title = get_field('accessories_categories_title');
        if (!empty($acf_title)) {
            $args['title'] = $acf_title;
        }
        
        // Check if section is disabled via ACF
        $show_section = get_field('show_accessories_categories');
        if ($show_section === false) {
            return '';
        }
    }
    
    // Get parent "Accessories" category
    $parent_category = get_term_by('slug', $args['parent_slug'], 'product_cat');
    
    // If parent category doesn't exist, try to find it by name
    if (!$parent_category || is_wp_error($parent_category)) {
        $parent_category = get_term_by('name', $args['parent_name'], 'product_cat');
    }
    
    // If still can't find the parent category, exit with message
    if (!$parent_category || is_wp_error($parent_category)) {
        return '<div class="no-categories-found">Accessories category not found.</div>';
    }
    
    // Get child categories
    $child_categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'parent' => $parent_category->term_id,
        'hide_empty' => true, // Always hide empty categories
        'number' => $args['max_items'],
        'orderby' => 'name',
        'order' => 'ASC',
    ));
    
    // If no child categories are found or there's an error, exit
    if (empty($child_categories) || is_wp_error($child_categories)) {
        return '<div class="no-categories-found">No accessory subcategories found.</div>';
    }
    
    // Default placeholder image for categories without images
    $default_image = plugins_url('assets/images/accessory-placeholder.jpg', __FILE__);
    
    // If default image doesn't exist, use a fallback URL
    if (!$default_image || empty($default_image)) {
        $default_image = 'https://cdn.myikas.com/images/theme-images/b8100cbe-0867-4fef-9a44-a17ce8f0406b/image_720.webp';
    }
    
    // Prepare child categories data
    $categories_data = array();
    $category_names = array(); // Track names to prevent duplicates
    
    foreach ($child_categories as $category) {
        // Skip if category has no products
        if ($category->count <= 0) {
            continue;
        }
        
        // Skip if name already exists (prevents duplicates)
        if (in_array(strtolower($category->name), $category_names)) {
            continue;
        }
        
        // Get category link safely
        $category_link = get_term_link($category);
        if (is_wp_error($category_link)) {
            $category_link = '#';
        }
        
        // Get category image
        $image_url = '';
        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
        
        if ($thumbnail_id) {
            $image = wp_get_attachment_image_src($thumbnail_id, 'medium');
            if ($image && isset($image[0])) {
                $image_url = $image[0];
            }
        }
        
        // Use default image as fallback
        if (empty($image_url)) {
            $image_url = $default_image;
        }
        
        // Add to our category data array
        $categories_data[] = array(
            'name' => $category->name,
            'link' => $category_link,
            'image' => $image_url,
            'count' => $category->count,
            'description' => $category->description
        );
        
        // Track this name to prevent duplicates
        $category_names[] = strtolower($category->name);
    }
    
    // Only output HTML if we have categories to display
    if (empty($categories_data)) {
        return '';
    }
    
    // Add the CSS
    bride_co_accessories_categories_css();
    
    // Set column classes based on args
    $col_desktop_class = 'col-md-' . (12 / intval($args['cols_desktop']));
    $col_mobile_class = 'col-' . (12 / intval($args['cols_mobile']));
    
    ?>
<!-- Accessories Child Categories Section -->
<section class="accessories-categories-section">
    <div class="container">
        <?php if (!empty($args['title'])) : ?>
            <h2 class="accessories-section-title"><?php echo esc_html($args['title']); ?></h2>
        <?php endif; ?>
        
        <div class="row justify-content-center g-4">
            <?php 
            foreach ($categories_data as $category) : 
                // Ensure required keys exist
                $name = isset($category['name']) ? $category['name'] : '';
                $link = isset($category['link']) ? $category['link'] : '#';
                $image = isset($category['image']) ? $category['image'] : '';
                $count = isset($category['count']) ? $category['count'] : 0;
                
                // Skip if no name or image
                if (empty($name) || empty($image)) {
                    continue;
                }
            ?>
                <div class="<?php echo esc_attr($col_desktop_class . ' ' . $col_mobile_class); ?>">
                    <a href="<?php echo esc_url($link); ?>" class="accessory-category-item">
                        <div class="accessory-category-image-wrapper">
                            <img src="<?php echo esc_url($image); ?>" 
                                 class="accessory-category-image" 
                                 alt="<?php echo esc_attr($name); ?>" 
                                 loading="lazy" />
                        </div>
                        <div class="accessory-category-name">
                            <?php echo esc_html($name); ?>
                            <?php if ($args['show_count'] && $count > 0) : ?>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
    <?php
    
    // Return the buffered content
    return ob_get_clean();
}

/**
 * Create the shortcode for the accessories child categories section
 * 
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function bride_co_accessories_child_categories_shortcode($atts = array()) {
    // Parse shortcode attributes
    $atts = shortcode_atts(
        array(
            'title' => 'Accessories Categories',
            'parent' => 'accessories',
            'cols_desktop' => 6,
            'cols_mobile' => 4,
            'max' => 12,
            'show_count' => 'yes',
            'hide_empty' => 'no'
        ),
        $atts,
        'accessories_child_categories'
    );
    
    // Convert string values to proper types
    $args = array(
        'title' => sanitize_text_field($atts['title']),
        'parent_slug' => sanitize_title($atts['parent']),
        'parent_name' => ucfirst(sanitize_text_field($atts['parent'])),
        'cols_desktop' => absint($atts['cols_desktop']),
        'cols_mobile' => absint($atts['cols_mobile']),
        'max_items' => absint($atts['max']),
        'show_count' => $atts['show_count'] === 'yes',
        'hide_empty' => $atts['hide_empty'] === 'yes'
    );
    
    return bride_co_get_accessories_child_categories_html($args);
}
add_shortcode('accessories_child_categories', 'bride_co_accessories_child_categories_shortcode');

/**
 * Function for direct template inclusion
 * 
 * @param array $args Configuration arguments
 */
function bride_co_render_accessories_child_categories($args = array()) {
    // If no specific args provided, check if we need to use Euro Suit accessories instead
    if (empty($args)) {
        global $product;
        
        // Default to standard accessories
        $parent_slug = 'accessories';
        $section_title = 'Accessories';
        
        // Check if product exists and has Euro Suit brand
        if ($product) {
            // Check if product has Eurosuit brand attribute
            $terms = get_the_terms($product->get_id(), 'pa_brand');
            
            if (!empty($terms) && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    // Check if the brand name contains "Euro suit" or "Eurosuit" (case insensitive)
                    if (stripos($term->name, 'euro suit') !== false || stripos($term->name, 'eurosuit') !== false) {
                        // Use Euro Suit accessories instead
                        $parent_slug = 'euro-suit-accessories';
                        $section_title = 'Euro Suit Accessories';
                        break;
                    }
                }
            }
        }
        
        // Set custom args for the appropriate category
        $args = array(
            'title' => $section_title,
            'parent_slug' => $parent_slug,
            'parent_name' => $section_title
        );
    }
    
    echo bride_co_get_accessories_child_categories_html($args);
}

/**
 * Usage:
 * 
 * 1. As a shortcode in the content editor:
 *    [accessories_child_categories]
 * 
 * 2. With custom parameters:
 *    [accessories_child_categories title="Browse Accessories" cols_desktop="4" show_count="no"]
 * 
 * 3. Directly in a template file:
 *    <?php bride_co_render_accessories_child_categories(); ?>
 * 
 * 4. With custom parameters in a template:
 *    <?php 
 *    $args = array(
 *        'title' => 'Our Accessories',
 *        'cols_desktop' => 4,
 *        'show_count' => false
 *    );
 *    bride_co_render_accessories_child_categories($args); 
 *    ?>
 */
/**
 * Blog Posts Section - Latest 4 Posts
 * 
 * Displays the 4 most recent blog posts regardless of category.
 * Available as both a function and shortcode.
 */

/**
 * Register ACF fields for blog posts section
 */
function bride_co_register_blog_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_blog_section',
        'title' => 'Blog Posts Section',
        'fields' => array(
            array(
                'key' => 'field_show_blog',
                'label' => 'Display Blog Section',
                'name' => 'show_blog',
                'type' => 'true_false',
                'instructions' => 'Enable or disable the blog section',
                'default_value' => 1,
                'ui' => 1,
            ),
            array(
                'key' => 'field_blog_heading',
                'label' => 'Section Heading',
                'name' => 'blog_heading',
                'type' => 'text',
                'instructions' => 'Enter a heading for the blog section',
                'default_value' => 'Bride&Co Blog',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_blog',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'front_page',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));
}
add_action('acf/init', 'bride_co_register_blog_fields');

function bride_co_blog_css() {
    // Only add CSS once per page
    static $css_added = false;
    
    if ($css_added) {
        return;
    }
    
    $css_added = true;
    
    ?>
    <style>
        /* Blog Section Styling */
        .blog-section {
            /* Reduce vertical padding from 60px to e.g. 30px if you want less space above/below */
            padding: 30px 0; 
            background-color: #f9f9f9;
        }
        
        .blog-section h2 {
            font-family: "Cinzel", serif;
            /* Remove extra top margin so there's less space above the heading */
            margin-top: 0; 
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #333;
            text-align: left;
        }
        
        .blog-container {
            display: flex;
            flex-wrap: wrap;
        }
        
        .blog-card {
            background-color: #fff;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: all 0.3s ease;
        }
        
        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* 
           LANDSCAPE IMAGES:
           Use a 16:9 aspect ratio so images are displayed in a wide (landscape) format 
           without being cut off vertically. 
           
           NOTE: aspect-ratio is well-supported in modern browsers, 
           but for older browsers, you may need a fallback approach.
        */
        .blog-image {
            width: 100%;
            aspect-ratio: 16/9; 
            object-fit: cover; 
            padding: 5px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        
        .blog-title {
            font-family: "Poppins", sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            line-height: 1.4;
            margin: 15px 20px 10px;
            color: #333;
        }
        
        .blog-description {
            font-family: "Poppins", sans-serif;
            font-size: 0.9rem;
            color: #666;
            margin: 0 20px 10px;
            flex-grow: 1;
        }
        
        .blog-category {
            font-family: "Poppins", sans-serif;
            font-size: 0.8rem;
            color: #999;
            text-transform: uppercase;
            margin: 0 20px;
            letter-spacing: 1px;
        }
        
        .read-more {
            font-family: "Poppins", sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
            text-transform: uppercase;
            margin: 15px 20px 20px;
            display: inline-block;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .read-more:hover {
            color: #999;
            text-decoration: none;
        }
        
        @media (max-width: 991px) {
            .blog-section h2 {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 767px) {
            .blog-card {
                margin-bottom: 30px;
            }
            
            .blog-title {
                font-size: 1rem;
            }
        }
    </style>
    <?php
}


/**
 * Generate the blog posts HTML content
 * 
 * @param array $atts Shortcode attributes or function parameters
 * @return string HTML output for the blog posts section
 */
function bride_co_get_blog_posts_html($atts = array()) {
    // Start output buffer to capture HTML
    ob_start();
    
    // Default attributes
    $default_atts = array(
        'heading' => 'Bride&Co Blog',
    );
    
    // Merge default attributes with passed attributes
    $atts = shortcode_atts($default_atts, $atts);
    
    // Check if we're on the front page and should use ACF fields
    if (is_front_page()) {
        // Check if blog section should be displayed
        $show_blog = get_field('show_blog');
        if ($show_blog === false) {
            return '';
        }
        
        // Get ACF field values
        $heading = get_field('blog_heading');
        if (!empty($heading)) {
            $atts['heading'] = $heading;
        }
    }
    
    // Query args for blog posts - always get 4 latest posts
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 3,  // Always set to 4
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    // Get posts
    $query = new WP_Query($args);
    
    // If no posts found, return empty
    if (!$query->have_posts()) {
        return '';
    }
    
    // Add the CSS
    bride_co_blog_css();
    
    ?>
<!-- Blog Section -->
<section class="blog-section">
    <div class="container">
        <h2 class="fw-bold"><?php echo esc_html($atts['heading']); ?></h2>
        <div class="row g-4 mt-4 blog-container">
            <?php while ($query->have_posts()) : $query->the_post(); 
                // Get post data
                $post_title = get_the_title();
                $post_excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 20, '...');
                $post_link = get_permalink();
                
                // Get post categories
                $post_categories = get_the_category();
                $category_name = !empty($post_categories) ? $post_categories[0]->name : '';
                
                // Get featured image
                $featured_image = '';
                if (has_post_thumbnail()) {
                    $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
                }
            ?>
            <div class="col-md-3 d-flex">
                <div class="blog-card">
                <?php if (!empty($featured_image)) : ?>
    <a href="<?php echo esc_url($post_link); ?>">
        <img src="<?php echo esc_url($featured_image); ?>" 
             class="blog-image" 
             alt="<?php echo esc_attr($post_title); ?>" />
    </a>
<?php endif; ?>
                    
                    <p class="blog-title">
                    <a href="<?php echo esc_url($post_link); ?>">
                        <?php echo esc_html($post_title); ?>
                </a>
                    </p>

                    
                    <!--<p class="blog-description"><?php echo esc_html($post_excerpt); ?></p>
                    <p class="blog-category"><?php echo esc_html($category_name); ?></p>
                    <a href="<?php echo esc_url($post_link); ?>" class="read-more">READ MORE</a> -->
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
    <?php
    
    // Reset post data
    wp_reset_postdata();
    
    // Return the buffered content
    return ob_get_clean();
}

/**
 * Create the shortcode for the blog posts section
 * 
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function bride_co_blog_posts_shortcode($atts) {
    return bride_co_get_blog_posts_html($atts);
}
add_shortcode('bride_blog', 'bride_co_blog_posts_shortcode');

/**
 * Function for rendering blog posts in templates
 * 
 * @param string $heading Section heading
 */
function bride_co_render_blog_posts($heading = '') {
    $atts = array();
    
    if (!empty($heading)) {
        $atts['heading'] = $heading;
    }
    
    echo bride_co_get_blog_posts_html($atts);
}

/**
 * Usage:
 * 
 * As a shortcode in the content editor:
 * [bride_blog heading="Latest Articles"]
 * 
 * Or directly in a template file:
 * <?php bride_co_render_blog_posts('Latest Articles'); ?>
 * 
 * If used on the home page, ACF values will override the heading parameter.
 */


 /**
 * Brand Logos Section for Home Page
 * 
 * Adds a customizable brand logos section with repeater field
 * that appears directly in your home page editor.
 */

/**
 * Register ACF fields for brand logos section
 */
function bride_co_register_brand_logos_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_brand_logos_section',
        'title' => 'Brand Logos Section',
        'fields' => array(
            array(
                'key' => 'field_show_brand_logos',
                'label' => 'Display Brand Logos Section',
                'name' => 'show_brand_logos',
                'type' => 'true_false',
                'instructions' => 'Enable or disable the brand logos section',
                'default_value' => 1,
                'ui' => 1,
            ),
            array(
                'key' => 'field_brand_logos',
                'label' => 'Brand Logos',
                'name' => 'brand_logos',
                'type' => 'repeater',
                'instructions' => 'Add brand logos to display',
                'min' => 1,
                'max' => 0,
                'layout' => 'block',
                'button_label' => 'Add Brand Logo',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_brand_logos',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_brand_logo_image',
                        'label' => 'Logo Image',
                        'name' => 'logo_image',
                        'type' => 'image',
                        'instructions' => 'Upload or select a logo image',
                        'required' => 1,
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                    ),
                    array(
                        'key' => 'field_brand_logo_url',
                        'label' => 'Logo URL',
                        'name' => 'logo_url',
                        'type' => 'url',
                        'instructions' => 'Enter the URL for this brand logo',
                        'required' => 0,
                        'default_value' => '#',
                    ),
                    array(
                        'key' => 'field_brand_logo_alt',
                        'label' => 'Alt Text',
                        'name' => 'logo_alt',
                        'type' => 'text',
                        'instructions' => 'Enter alternative text for this logo',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_brand_logo_target',
                        'label' => 'Open in New Tab',
                        'name' => 'logo_target',
                        'type' => 'true_false',
                        'instructions' => 'Open link in a new tab?',
                        'default_value' => 1,
                        'ui' => 1,
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'front_page',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));
}
add_action('acf/init', 'bride_co_register_brand_logos_fields');

/**
 * Function to render brand logos section in template
 */
function bride_co_render_brand_logos() {
    // Check if we're on the home page
    if (!is_front_page()) {
        return;
    }
    
    // Check if brand logos section should be displayed
    $show_brand_logos = get_field('show_brand_logos');
    
    if (!$show_brand_logos) {
        return;
    }
    
    // Get the brand logos data
    $brand_logos = get_field('brand_logos');
    
    // Skip if no logos
    if (empty($brand_logos)) {
        return;
    }
    
    // Default logos if ACF is not set up yet
    if (empty($brand_logos)) {
        $brand_logos = array(
            array(
                'logo_image' => array(
                    'url' => 'https://randevu.olegcassini.com.tr/image/logo.webp',
                    'alt' => 'Oleg Cassini'
                ),
                'logo_url' => 'https://www.olegcassini.com',
                'logo_alt' => 'Oleg Cassini',
                'logo_target' => true
            ),
            array(
                'logo_image' => array(
                    'url' => 'https://brideandco.co.za/wp-content/uploads/2022/05/cropped-BrideCo-Logo.png',
                    'alt' => 'Bride&Co'
                ),
                'logo_url' => 'https://www.olegcassini.com',
                'logo_alt' => 'Bride&Co',
                'logo_target' => true
            ),
            array(
                'logo_image' => array(
                    'url' => 'https://www.viola-chan.com/wp-content/uploads/2020/06/cropped-logo-157x41.png',
                    'alt' => 'Viola Chan'
                ),
                'logo_url' => 'https://www.olegcassini.com',
                'logo_alt' => 'Viola Chan',
                'logo_target' => true
            ),
            array(
                'logo_image' => array(
                    'url' => 'https://brideandco.co.za/wp-content/uploads/2022/06/Eurosuit-Logo-1.png',
                    'alt' => 'Eurosuit'
                ),
                'logo_url' => 'https://www.olegcassini.com',
                'logo_alt' => 'Eurosuit',
                'logo_target' => true
            ),
            array(
                'logo_image' => array(
                    'url' => '/wp-content/themes/bride-co-child/assets/imgs/dreamon.png',
                    'alt' => 'Dreamon for Oleg Cassini'
                ),
                'logo_url' => 'https://www.olegcassini.com',
                'logo_alt' => 'Dreamon for Oleg Cassini',
                'logo_target' => true
            )
        );
    }
    
    ?>
<!-- Brand Logos Section -->
<section class="text-center py-5">
    <div class="row justify-content-center align-items-center">
        <?php foreach ($brand_logos as $logo) : 
            // Skip if missing essential data
            if (empty($logo['logo_image']) || empty($logo['logo_alt'])) {
                continue;
            }
            
            // Get logo data
            $logo_url = isset($logo['logo_url']) ? $logo['logo_url'] : '#';
            $logo_image = $logo['logo_image'];
            $logo_alt = $logo['logo_alt'];
            $logo_target = isset($logo['logo_target']) && $logo['logo_target'] ? '_blank' : '_self';
            
            // Handle different image return formats
            $image_url = '';
            if (is_array($logo_image) && isset($logo_image['url'])) {
                $image_url = $logo_image['url'];
            } elseif (is_numeric($logo_image)) {
                $image_url = wp_get_attachment_url($logo_image);
            } elseif (is_string($logo_image)) {
                $image_url = $logo_image;
            }
            
            // Skip if no valid image
            if (empty($image_url)) {
                continue;
            }
        ?>
        <div class="col-md-2 col-6">
            <a href="<?php echo esc_url($logo_url); ?>" target="<?php echo esc_attr($logo_target); ?>">
                <img
                    src="<?php echo esc_url($image_url); ?>"
                    alt="<?php echo esc_attr($logo_alt); ?>"
                    class="img-fluid"
                />
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</section>
    <?php
}

/**
 * Also available as a shortcode
 */
function bride_co_brand_logos_shortcode() {
    ob_start();
    bride_co_render_brand_logos();
    return ob_get_clean();
}
add_shortcode('brand_logos', 'bride_co_brand_logos_shortcode');

// Add this code to your home page template (front-page.php or home.php):
// <?php bride_co_render_brand_logos(); 


/**
 * Features Section for Home Page
 * 
 * Adds a customizable features section with icons
 * that appears directly in your home page editor.
 */

/**
 * Register ACF fields for features section
 */
function bride_co_register_features_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_features_section',
        'title' => 'Features Section',
        'fields' => array(
            array(
                'key' => 'field_show_features',
                'label' => 'Display Features Section',
                'name' => 'show_features',
                'type' => 'true_false',
                'instructions' => 'Enable or disable the features section',
                'default_value' => 1,
                'ui' => 1,
            ),
            array(
                'key' => 'field_features',
                'label' => 'Features',
                'name' => 'features',
                'type' => 'repeater',
                'instructions' => 'Add features to display',
                'min' => 1,
                'max' => 0,
                'layout' => 'block',
                'button_label' => 'Add Feature',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_features',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_feature_icon',
                        'label' => 'Feature Icon',
                        'name' => 'feature_icon',
                        'type' => 'image',
                        'instructions' => 'Upload or select an icon image',
                        'required' => 1,
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'library' => 'all',
                    ),
                    array(
                        'key' => 'field_feature_text',
                        'label' => 'Feature Text',
                        'name' => 'feature_text',
                        'type' => 'text',
                        'instructions' => 'Enter the text for this feature',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_icon_width',
                        'label' => 'Icon Width (px)',
                        'name' => 'icon_width',
                        'type' => 'number',
                        'instructions' => 'Enter the width for this icon in pixels',
                        'default_value' => 50,
                        'min' => 20,
                        'max' => 200,
                    ),
                    array(
                        'key' => 'field_icon_height',
                        'label' => 'Icon Height (px)',
                        'name' => 'icon_height',
                        'type' => 'number',
                        'instructions' => 'Enter the height for this icon in pixels',
                        'default_value' => 50,
                        'min' => 20,
                        'max' => 200,
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'front_page',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));
}
add_action('acf/init', 'bride_co_register_features_fields');

/**
 * Function to render features section in template
 */
function bride_co_render_features() {
    // Check if we're on the home page
    if (!is_front_page()) {
        return;
    }
    
    // Check if features section should be displayed
    $show_features = get_field('show_features');
    
    if (!$show_features) {
        return;
    }
    
    // Get the features data
    $features = get_field('features');
    
    // Default features if ACF is not set up yet
    if (empty($features)) {
        $features = array(
            array(
                'feature_icon' => array(
                    'url' => 'https://cdn.myikas.com/images/theme-images/03310641-9822-4fc3-927d-983f85d1baea/image_180.webp',
                    'alt' => 'Secure Shopping'
                ),
                'feature_text' => 'Secure Shopping',
                'icon_width' => 50,
                'icon_height' => 50
            ),
            array(
                'feature_icon' => array(
                    'url' => 'https://cdn.myikas.com/images/theme-images/95b7493f-c23b-4f3a-9082-a5eff8d27b18/image_180.webp',
                    'alt' => 'Free Shipping'
                ),
                'feature_text' => 'Free Shipping on Purchases Over R 2,750',
                'icon_width' => 50,
                'icon_height' => 50
            ),
            array(
                'feature_icon' => array(
                    'url' => 'https://cdn.myikas.com/images/theme-images/9a98ee6b-dd5a-48a3-8f0e-654dd1bfa07f/image_180.webp',
                    'alt' => 'Returns/Exchanges'
                ),
                'feature_text' => 'Returns/Exchanges Within 30 Days',
                'icon_width' => 50,
                'icon_height' => 50
            ),
        );
    }
    
    // Skip if no features
    if (empty($features)) {
        return;
    }
    
    ?>
<!-- Features Section -->
<section class="container text-center py-5 every-icon">
    <div class="row justify-content-center align-items-center">
        <?php foreach ($features as $feature) : 
            // Skip if missing essential data
            if (empty($feature['feature_icon']) || empty($feature['feature_text'])) {
                continue;
            }
            
            // Get feature data
            $feature_icon = $feature['feature_icon'];
            $feature_text = $feature['feature_text'];
            $icon_width = isset($feature['icon_width']) ? $feature['icon_width'] : 50;
            $icon_height = isset($feature['icon_height']) ? $feature['icon_height'] : 50;
            
            // Handle different image return formats
            $icon_url = '';
            if (is_array($feature_icon) && isset($feature_icon['url'])) {
                $icon_url = $feature_icon['url'];
                $icon_alt = isset($feature_icon['alt']) ? $feature_icon['alt'] : $feature_text;
            } elseif (is_numeric($feature_icon)) {
                $icon_url = wp_get_attachment_url($feature_icon);
                $icon_alt = $feature_text;
            } elseif (is_string($feature_icon)) {
                $icon_url = $feature_icon;
                $icon_alt = $feature_text;
            }
            
            // Skip if no valid image
            if (empty($icon_url)) {
                continue;
            }
            
            // Determine column width based on number of features
            $col_class = 'col-md-4 col-6';
            $feature_count = count($features);
            if ($feature_count == 2) {
                $col_class = 'col-md-6 col-6';
            } elseif ($feature_count == 1) {
                $col_class = 'col-md-12 col-12';
            } elseif ($feature_count == 4) {
                $col_class = 'col-md-3 col-6';
            } elseif ($feature_count == 5 || $feature_count == 6) {
                $col_class = 'col-md-2 col-6';
            }
        ?>
        <div class="<?php echo esc_attr($col_class); ?>">
            <img
                src="<?php echo esc_url($icon_url); ?>"
                alt="<?php echo esc_attr($icon_alt); ?>"
                style="width: <?php echo esc_attr($icon_width); ?>px; height: <?php echo esc_attr($icon_height); ?>px"
            />
            <p class="mt-2"><?php echo esc_html($feature_text); ?></p>
        </div>
        <?php endforeach; ?>
    </div>
	<style>
	.every-icon .col-md-2.col-6:last-child img{
		margin-top: 15px;
	}
	</style>
</section>
    <?php
}

/**
 * Also available as a shortcode
 */
function bride_co_features_shortcode() {
    ob_start();
    bride_co_render_features();
    return ob_get_clean();
}
add_shortcode('store_features', 'bride_co_features_shortcode');

// Add this code to your home page template (front-page.php or home.php):
// <?php bride_co_render_features(); 

/**
 * Fix ACF post object field loading issue
 */
function bride_co_fix_acf_post_loading() {
    // Increase post limits for ACF admin
    if (is_admin()) {
        add_filter('acf/fields/post_object/query', 'increase_acf_post_query_limit', 10, 3);
    }
}

function increase_acf_post_query_limit($args) {
    // Increase posts per page to load more options
    $args['posts_per_page'] = 100;
    
    // Remove any search parameter that might be limiting results
    if (empty($_POST['s'])) {
        unset($args['s']);
    }
    
    return $args;
}
add_action('acf/init', 'bride_co_fix_acf_post_loading');


// Add this to your theme's functions.php file

/**
 * Handle AJAX add to cart requests
 */
function ajax_add_to_cart() {
    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    
    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity)) {
        do_action('woocommerce_ajax_added_to_cart', $product_id);
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
    
    wp_die();
}
add_action('wp_ajax_add_to_cart', 'ajax_add_to_cart');
add_action('wp_ajax_nopriv_add_to_cart', 'ajax_add_to_cart');

/**
 * Ensure WooCommerce AJAX parameters are available
 */
function add_wc_add_to_cart_params() {
    if (function_exists('is_product') && is_product()) {
        wp_enqueue_script('wc-add-to-cart');
    }
}
add_action('wp_enqueue_scripts', 'add_wc_add_to_cart_params');

/*price-slider*/
function enqueue_price_slider_scripts() {
    wp_enqueue_style('nouislider-style', 'https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css');
    wp_enqueue_script('nouislider', 'https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_price_slider_scripts');
/**
 * ACF Field Setup for FAQ Template
 * 
 * Add this code to your theme's functions.php file
 * Requires ACF Pro plugin to be installed and activated
 */

// Register the FAQ template ACF fields
function register_faq_acf_fields() {
    if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_faq_page',
        'title' => 'FAQ Page Settings',
        'fields' => array(
            array(
                'key' => 'field_faq_main_title',
                'label' => 'Main Title',
                'name' => 'faq_main_title',
                'type' => 'text',
                'instructions' => 'Enter the main title for the FAQ page',
                'default_value' => 'Frequently Asked Questions',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_faq_subtitle',
                'label' => 'Subtitle',
                'name' => 'faq_subtitle',
                'type' => 'textarea',
                'instructions' => 'Enter subtitle text that appears below the main title',
                'default_value' => 'We have listed the most frequently asked questions and topics below to help you feel safe at every stage of your shopping.',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => 3,
                'new_lines' => 'wpautop',
            ),
            array(
                'key' => 'field_faq_contact_text',
                'label' => 'Contact Text',
                'name' => 'faq_contact_text',
                'type' => 'text',
                'instructions' => 'Text that appears before the contact link',
                'default_value' => 'For your other questions, you can use the',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_contact_page_link',
                'label' => 'Contact Page Link',
                'name' => 'contact_page_link',
                'type' => 'page_link',
                'instructions' => 'Select your contact page',
                'required' => 0,
                'conditional_logic' => 0,
                'post_type' => array(
                    0 => 'page',
                ),
                'taxonomy' => '',
                'allow_null' => 0,
                'allow_archives' => 0,
                'multiple' => 0,
            ),
            array(
                'key' => 'field_faq_sections',
                'label' => 'FAQ Sections',
                'name' => 'faq_sections',
                'type' => 'repeater',
                'instructions' => 'Add FAQ section groups (e.g., About Membership, Shipping and Delivery, etc.)',
                'required' => 0,
                'conditional_logic' => 0,
                'collapsed' => 'field_section_title',
                'min' => 0,
                'max' => 0,
                'layout' => 'block',
                'button_label' => 'Add FAQ Section',
                'sub_fields' => array(
                    array(
                        'key' => 'field_section_title',
                        'label' => 'Section Title',
                        'name' => 'section_title',
                        'type' => 'text',
                        'instructions' => 'Enter the title for this FAQ section',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => 'e.g., About Membership',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_faq_items',
                        'label' => 'FAQ Items',
                        'name' => 'faq_items',
                        'type' => 'repeater',
                        'instructions' => 'Add question and answer pairs for this section',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'collapsed' => 'field_question',
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'row',
                        'button_label' => 'Add FAQ Item',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_question',
                                'label' => 'Question',
                                'name' => 'question',
                                'type' => 'text',
                                'instructions' => 'Enter the question',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),
                            array(
                                'key' => 'field_answer',
                                'label' => 'Answer',
                                'name' => 'answer',
                                'type' => 'wysiwyg',
                                'instructions' => 'Enter the answer to the question',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'tabs' => 'all',
                                'toolbar' => 'full',
                                'media_upload' => 1,
                                'delay' => 0,
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post',
                    'operator' => '==',
                    'value' => '20135452',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 0,
    ));

    endif;
}


// Hook into ACF initialization to register fields
add_action('acf/init', 'register_faq_acf_fields');



/**
 * Add ACF field group for menu item featured images
 * Only if ACF function exists
 */
function bride_co_add_menu_acf_fields() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_mega_menu_options',
            'title' => 'Mega Menu Options',
            'fields' => array(
                array(
                    'key' => 'field_menu_featured_image',
                    'label' => 'Featured Image',
                    'name' => 'menu_featured_image',
                    'type' => 'image',
                    'instructions' => 'Select an image to display in the mega menu',
                    'required' => 0,
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'nav_menu_item',
                        'operator' => '==',
                        'value' => 'all',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
        ));
    }
}
add_action('acf/init', 'bride_co_add_menu_acf_fields');

/**
 * Hide WooCommerce Customer Zone Match Notice
 *
 * This function removes the notice that appears when a customer matches a specific zone.
 */
function hide_woocommerce_customer_zone_match_notice() {
    // Remove the action that adds the notice
    remove_action('woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10);
    
    // Optional: Re-add notices without the zone match notice
    add_action('woocommerce_before_checkout_form', 'custom_output_notices', 10);
}
add_action('init', 'hide_woocommerce_customer_zone_match_notice');

/**
 * Optional: Custom function to output notices except zone match notices
 */
function custom_output_notices() {
    $all_notices = WC()->session->get('wc_notices', array());
    
    // Filter out zone match notices
    foreach ($all_notices as $notice_type => $notices) {
        foreach ($notices as $key => $notice) {
            // Check if the notice contains zone match text
            if (isset($notice['notice']) && strpos($notice['notice'], 'zone') !== false) {
                unset($all_notices[$notice_type][$key]);
            }
        }
    }
    
    // Set the filtered notices back
    WC()->session->set('wc_notices', $all_notices);
    
    // Output the remaining notices
    wc_print_notices();
}




/**
 * Custom function to change WooCommerce's stock quantity error message
 * Add this to your theme's functions.php file
 */
function custom_wc_stock_message($message, $product_data, $variation) {
    // Get the exact text that needs to be replaced
    $default_text = 'You cannot add that amount';
    
    // Replace it with your custom message
    if (strpos($message, $default_text) !== false) {
        $message = str_replace($default_text, 'The desired quantity is not available', $message);
    }
    
    return $message;
}
add_filter('woocommerce_cart_product_cannot_add_another_message', 'custom_wc_stock_message', 10, 3);
add_filter('woocommerce_cart_product_cannot_add_message', 'custom_wc_stock_message', 10, 3);

/**
 * Alternative approach that handles all stock-related messages
 * Uncomment this if you want to replace all stock-related error messages
 */
/*
function custom_stock_messages($message) {
    if (strpos($message, 'You cannot add that amount') !== false) {
        return 'The desired quantity is not available';
    }
    
    // You can add more message replacements here if needed
    
    return $message;
}
add_filter('woocommerce_add_to_cart_message', 'custom_stock_messages', 10, 1);
add_filter('woocommerce_add_error', 'custom_stock_messages', 10, 1);
*/


/**
 * Handle AJAX add to cart requests with custom stock message
 * Add this to your theme's functions.php file
 */
function custom_ajax_add_to_cart_handler() {
    // Get product ID and quantity
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? wc_stock_amount(sanitize_text_field($_POST['quantity'])) : 1;
    $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;
    $variations = isset($_POST['variation']) ? (array) $_POST['variation'] : array();
    
    // Get the product
    $product = wc_get_product($product_id);
    
    // Check if product exists
    if (!$product) {
        wp_send_json(array(
            'error' => true,
            'message' => __('Product does not exist', 'woocommerce')
        ));
        return;
    }
    
    // Check if there's enough stock
    $product_to_check = $variation_id ? wc_get_product($variation_id) : $product;
    
    if (!$product_to_check->has_enough_stock($quantity)) {
        wp_send_json(array(
            'error' => true,
            'message' => __('The desired quantity is not available', 'woocommerce')
        ));
        return;
    }
    
    // Add to cart
    $added = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variations);
    
    if ($added) {
        wp_send_json(array(
            'success' => true,
            'message' => __('Product added to cart!', 'woocommerce')
        ));
    } else {
        wp_send_json(array(
            'error' => true,
            'message' => __('Error adding product to cart. Please try again.', 'woocommerce')
        ));
    }
}
add_action('wp_ajax_custom_add_to_cart', 'custom_ajax_add_to_cart_handler');
add_action('wp_ajax_nopriv_custom_add_to_cart', 'custom_ajax_add_to_cart_handler');

/**
 * Remove "NEW" labels from products older than 30 days
 * Add this to your theme's functions.php file
 */
function hide_new_labels_for_old_products() {
    // Only run on the frontend
    if (is_admin()) {
        return;
    }
    
    // Add JavaScript to check and hide NEW labels
    add_action('wp_footer', function() {
        ?>
        <script>
        jQuery(document).ready(function($) {
            // Add CSS to hide all NEW labels initially
            $('head').append('<style id="new-label-controller">.new-label, .new-badge { display: block; position: absolute; z-index: 1; background: #ddcdbf; padding: 8px 15px;  font-weight: 600; }</style>');
            
            // Function to show NEW labels only on recent products
            function processNewLabels() {
                // Get all product elements
                $('.product-card, .product, .product-container, .type-product').each(function() {
                    const $product = $(this);
                    let productId = $product.data('product-id');
                    
                    // If there's no product ID data attribute, try to find it in the URL
                    if (!productId) {
                        const $link = $product.find('a[href*="/product/"]').first();
                        if ($link.length) {
                            const href = $link.attr('href');
                            const matches = href.match(/\/product\/([^\/]+)/);
                            if (matches && matches[1]) {
                                // Extract from URL
                                productId = matches[1];
                            }
                        }
                    }
                    
                    if (productId) {
                        // Make an AJAX call to check if the product is new
                        $.ajax({
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            type: 'POST',
                            data: {
                                action: 'check_if_product_is_new',
                                product_id: productId
                            },
                            success: function(response) {
                                if (response.is_new) {
                                    // If product is new, show the NEW label
                                    $product.find('.new-label, .new-badge').css('display', 'block');
                                }
                            }
                        });
                    }
                });
            }
            
            // Process on page load
            processNewLabels();
        });
        </script>
        <?php
    }, 99);
    
    // Add AJAX handler for checking if a product is new
    add_action('wp_ajax_check_if_product_is_new', 'ajax_check_if_product_is_new');
    add_action('wp_ajax_nopriv_check_if_product_is_new', 'ajax_check_if_product_is_new');
}
add_action('init', 'hide_new_labels_for_old_products');

/**
 * AJAX function to check if a product is new
 */
function ajax_check_if_product_is_new() {
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    
    if ($product_id) {
        // Get the post
        $post = get_post($product_id);
        if (!$post) {
            wp_send_json(['is_new' => false]);
            return;
        }
        
        // Calculate days since publication
        $post_date = strtotime($post->post_date_gmt . ' GMT');
        $current_time = current_time('timestamp', true); // Get GMT time
        $days_difference = ($current_time - $post_date) / DAY_IN_SECONDS;
        
        // Product is new if less than 30 days old
        wp_send_json(['is_new' => ($days_difference < 30)]);
    } else {
        wp_send_json(['is_new' => false]);
    }
}

function add_cross_out_price_script() {
    ?>
    <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        // Select the price container
        const priceContainer = document.querySelector('.price-container');

        // Ensure the price container exists
        if (priceContainer) {
            // Select both the current price and the original price (higher price)
            const priceElements = priceContainer.querySelectorAll('.woocommerce-Price-amount');

            if (priceElements.length === 2) {
                const currentPrice = parseFloat(priceElements[0].textContent.replace(/[^\d.-]/g, ''));
                const originalPrice = parseFloat(priceElements[1].textContent.replace(/[^\d.-]/g, ''));

                // If the original price is higher than the current price, cross out the original price
                if (originalPrice > currentPrice) {
                    // Add the line-through style to the higher price (original price)
                    priceElements[1].style.textDecoration = 'line-through';
                }
            }
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'add_cross_out_price_script');

function custom_cross_out_higher_price() {
    global $product;

    // Check if the product has both regular and sale prices
    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();

    // If there is a sale price and it's lower than the regular price, cross out the regular price
    if ($sale_price && $regular_price > $sale_price) {
        // Format the regular price and sale price
        $regular_price_html = '<span class="woocommerce-Price-amount amount" style="text-decoration: line-through;"><bdi><span class="woocommerce-Price-currencySymbol">R</span>' . number_format($regular_price, 2) . '</bdi></span>';
        $sale_price_html = '<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">R</span><strong>' . number_format($sale_price, 2) . '</strong></bdi></span>';

        // Output the prices (regular price is crossed out, sale price is bold)
        echo '<div class="price-container">
                <div class="price-box">
                    <div class="current-price">' . $sale_price_html . ' – ' . $regular_price_html . '</div>
                </div>
              </div>';
    } else {
        // If there's no sale price, just display the regular price
        $regular_price_html = '<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">R</span>' . number_format($regular_price, 2) . '</bdi></span>';
        echo '<div class="price-container">
                <div class="price-box">
                    <div class="current-price">' . $regular_price_html . '</div>
                </div>
              </div>';
    }
}

// Hook into the WooCommerce product page to display the modified prices
add_action('woocommerce_single_product_summary', 'custom_cross_out_higher_price', 10);


/**
 * Register Eurosuit specific menu locations
 * Add this to your child theme's functions.php file
 */

/**
 * Register additional menu locations for Eurosuit
 */
function eurosuit_register_menus() {
    register_nav_menus(
        array(
            'eurosuit-primary-menu' => __('Eurosuit Primary Menu', 'bride-co-child'),
            'eurosuit-footer-menu'  => __('Eurosuit Footer Menu', 'bride-co-child'),
            'eurosuit-top-bar-menu' => __('Eurosuit Top Bar Menu', 'bride-co-child'),
        )
    );
}
add_action('init', 'eurosuit_register_menus');

/**
 * Check if we should apply Eurosuit styling and menus
 */
function is_eurosuit_page($product_id = NULL) {
    // Check ACF field if it exists
    if (function_exists('get_field') && get_field('is_euro_suit')) {
        return true;
    }

    // Check if we're on the Euro Suit template
    if (is_page_template('euro-suit-home.php') || is_page('eurosuit')) {
        return true;
    }

    if (is_page('contact-euro-suit')) {
        return true;
    }  
    
    if (is_page('find-a-store-euro-suit')) {
        return true;
    }  

 
    if($product_id)
    {
        if ($product_id && function_exists('wc_get_product')) {
            $product = wc_get_product($product_id);
        }
        //echo "^^^^^".($product);
        if ($product) {
            $attributes = $product->get_attributes();
            if (isset($attributes['pa_brand'])) {
                $terms = wc_get_product_terms($product->get_id(), 'pa_brand', array('fields' => 'names'));
                if (in_array('Eurosuit', $terms) || in_array('eurosuit', $terms)) {
                    return true;
                }
            }
        }
    }
    
     // Check if we're on a product page with "eurosuit" brand attribute
        if (is_singular('product')) {
            global $product;
            if (!$product && function_exists('wc_get_product')) {
                $product = wc_get_product(get_the_ID());
            }
            $product = wc_get_product(get_the_ID());
            if ($product) {
                $attributes = $product->get_attributes();
                if (isset($attributes['pa_brand'])) {
                    $terms = wc_get_product_terms($product->get_id(), 'pa_brand', array('fields' => 'names'));
                    if (in_array('Eurosuit', $terms) || in_array('eurosuit', $terms)) {
                        return true;
                    }
                }
            }
        }
    
    
    // Check if we're on a category page that has products with eurosuit attribute
    if (is_tax('product_cat') || is_shop()) {
        $term = get_queried_object();
        
        // Get category's products with eurosuit brand
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 1,
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'pa_brand',
                    'field'    => 'slug',
                    'terms'    => 'eurosuit',
                ),
            ),
        );
        
        // Add category constraint if we're on a category page
        if (is_tax('product_cat') && isset($term->term_id)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $term->term_id,
            );
        }
        
        // Check if any products match
        $products_query = new WP_Query($args);
        if ($products_query->have_posts()) {
            return true;
        }
        wp_reset_postdata();
    }
    
    return false;
}
/**
 * Filter the menu locations to use Eurosuit menus when on Eurosuit pages
 */
function eurosuit_filter_menu_args($args) {
    if (!is_eurosuit_page()) {
        return $args;
    }
    
    // Replace the menu location with Eurosuit-specific ones
    if (isset($args['theme_location'])) {
        switch ($args['theme_location']) {
            case 'primary-menu':
                $args['theme_location'] = 'eurosuit-primary-menu';
                break;
            case 'footer-menu':
                $args['theme_location'] = 'eurosuit-footer-menu';
                break;
            case 'top-bar-menu':
                $args['theme_location'] = 'eurosuit-top-bar-menu';
                break;
        }
    }
    
    return $args;
}
add_filter('wp_nav_menu_args', 'eurosuit_filter_menu_args');

/**
 * Filter the footer menu to use Eurosuit footer menu
 */
function eurosuit_footer_menu_filter($nav_menu, $args) {
    if (!is_eurosuit_page()) {
        return $nav_menu;
    }
    
    // Check if this is a footer menu
    if (isset($args->theme_location) && 
        ($args->theme_location == 'footer-menu' || strpos($args->menu_class, 'footer') !== false)) {
        
        // Get the Eurosuit footer menu instead
        $args->theme_location = 'eurosuit-footer-menu';
        $eurosuit_menu = wp_nav_menu(array(
            'theme_location' => 'eurosuit-footer-menu',
            'menu_class'     => $args->menu_class,
            'container'      => $args->container,
            'echo'           => false,
        ));
        
        if (!empty($eurosuit_menu)) {
            return $eurosuit_menu;
        }
    }
    
    return $nav_menu;
}
add_filter('wp_nav_menu', 'eurosuit_footer_menu_filter', 10, 2);

/**
 * Add admin notice to remind about setting up Eurosuit menus
 */
function eurosuit_admin_notice() {
    global $pagenow;
    
    // Only show on the Menus page or Dashboard
    if ($pagenow === 'nav-menus.php' || $pagenow === 'index.php') {
        ?>
        <div class="notice notice-info is-dismissible">
            <p><strong>Eurosuit Menus:</strong> Please set up the Eurosuit-specific menus in Appearance > Menus. 
            Create separate menus for Eurosuit and assign them to the 'Eurosuit Primary Menu', 'Eurosuit Footer Menu', 
            and 'Eurosuit Top Bar Menu' locations.</p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'eurosuit_admin_notice');

/**
 * Apply Eurosuit styling (colors, logo, etc.)
 */
function apply_eurosuit_styling() {
    if (!is_eurosuit_page()) {
        return;
    }
    
    ?>
    <!-- Eurosuit Styling -->
    <style>
        /* Change the navbar background to navy blue */
        .sticky-nav .bg-light {
            background-color: #192f5a !important;
        }
        
        /* Make text white on dark background */
        .sticky-nav .bg-light,
        .sticky-nav .bg-light .text-dark,
        .sticky-nav .bg-light a.text-dark,
        .sticky-nav .bg-light .visit-link,
        .sticky-nav .bg-light .social-icons a {
            color: #ffffff !important;
            text-transform: uppercase;
        }
        
        /* Ensure social icons in the nav are white */
        .sticky-nav .social-icons a,
        .sticky-nav .social-icons a i,
        .sticky-nav .bg-light .social-icons a,
        .sticky-nav .bg-light .social-icons a i {
            color: #ffffff !important;
        }
        
        /* Change footer background */
        .copyright {
            background-color: #192f5a !important;
        }
        
        /* Hide the Bride Vibes column in the footer */
        .footer-column:nth-last-child(1) {
            display: none !important;
        }
        
        /* Change appointment button background color */
        .appointment-btn {
            background-color: #192f5a !important;
            color: #ffffff !important;
        }
        
        /* Change stock status color */
        .stock-status {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            color: #192f5a !important;
            font-size: 14px;
        }
        
        /* Change stock status dot color */
        .stock-status i.fa-circle {
            color: #192f5a !important;
        }
    </style>
    
    <script>
    // Run when the page is loaded
    window.addEventListener('load', function() {
        // New logo URL
       // var euroLogo = 'https://brideandco.co.za/wp-content/uploads/2022/06/Eurosuit-Logo.png';
       // var euroLogoLink = '/eurosuit/';
        
        // Replace all logos and update their links
       // var images = document.getElementsByTagName('img');
       // for (var i = 0; i < images.length; i++) {
          //  if (images[i].src.includes('BrideCo-Logo') || 
            //    images[i].classList.contains('custom-logo') ||
             //   images[i].classList.contains('footer-logo')) {
                
           //     images[i].src = euroLogo;
           //     images[i].alt = 'Eurosuit';
                
                // Check if image is inside a link
           //     var parent = images[i].parentNode;
           //     if (parent.tagName === 'A') {
           //         parent.href = euroLogoLink;
           //     }
          //  }
        //}
        
        // Ensure the navbar brand links to /eurosuit/
        //var navbarBrand = document.querySelector('.navbar-brand');
        //if (navbarBrand) {
        //    navbarBrand.href = euroLogoLink;
        //}
        
        // Ensure the custom logo link points to /eurosuit/
       // var customLogoLink = document.querySelector('.custom-logo-link');
       // if (customLogoLink) {
         //   customLogoLink.href = euroLogoLink;
        //}
        
         // Update footer logo link
        // var footerLogoLink = document.querySelector('.footer-top a');
        // if (footerLogoLink) {
        //     footerLogoLink.href = euroLogoLink;
        // }
        
        // Hide Bride Vibes column
        var footerColumns = document.querySelectorAll('.footer-column');
        footerColumns.forEach(function(column) {
            var heading = column.querySelector('h3');
            if (heading && heading.textContent.includes('Bride Vibes')) {
                column.style.display = 'none';
            }
            
            var button = column.querySelector('.bride-vibes-button');
            if (button) {
                column.style.display = 'none';
            }
        });
        
        // Update copyright text
        var copyrightElements = document.querySelectorAll('.copyright p, .site-info p, footer .copyright-text');
        copyrightElements.forEach(function(element) {
            if (element.innerHTML.includes('Bride')) {
                element.innerHTML = element.innerHTML.replace(/Bride&amp;co|Bride&co|Bride & co/g, 'Eurosuit');
            }
        });
        
        // Make sure social icons are white
        var socialIcons = document.querySelectorAll('.sticky-nav .social-icons a, .sticky-nav .social-icons a i');
        socialIcons.forEach(function(icon) {
            icon.style.color = '#ffffff';
        });
        
        // Update appointment button color
        var appointmentButtons = document.querySelectorAll('.appointment-btn');
        appointmentButtons.forEach(function(button) {
            button.style.backgroundColor = '#192f5a';
            button.style.color = '#ffffff';
        });
        
        // Update stock status color
        var stockStatus = document.querySelectorAll('.stock-status');
        stockStatus.forEach(function(status) {
            status.style.color = '#192f5a';
            var icon = status.querySelector('i.fa-circle');
            if (icon) {
                icon.style.color = '#192f5a';
            }
        });
    });
    </script>
    <?php
}
add_action('wp_head', 'apply_eurosuit_styling', 100);

/**
 * Add live preview for Eurosuit customizer settings
 */
function eurosuit_customizer_preview() {
    if (is_customize_preview()) {
        ?>
        <script>
        // Live preview for Eurosuit settings
        (function($) {
            // Preview promo text change
            wp.customize('eurosuit_promo_text', function(value) {
                value.bind(function(newval) {
                    $('.promo-text').text(newval);
                });
            });
            
            // Preview color change
            wp.customize('eurosuit_primary_color', function(value) {
                value.bind(function(newval) {
                    $('.sticky-nav .bg-light').css('background-color', newval);
                    $('.copyright').css('background-color', newval);
                    $('.appointment-btn').css('background-color', newval);
                    $('.stock-status').css('color', newval);
                    $('.stock-status i.fa-circle').css('color', newval);
                });
            });
            
            // Preview logo change
            wp.customize('eurosuit_logo', function(value) {
                value.bind(function(newval) {
                    $('.custom-logo, .footer-logo').attr('src', newval);
                });
            });
        })(jQuery);
        </script>
        <?php
    }
}
add_action('wp_footer', 'eurosuit_customizer_preview');
/**
 * Update the "VISIT EUROSUIT" / "VISIT BRIDE&CO" link in the top navigation
 * Add this to your existing Eurosuit styling function
 */

// Add this code to your apply_eurosuit_styling() function before the closing PHP tag
function update_visit_link_in_eurosuit_styling() {
    if (!is_eurosuit_page()) {
        return;
    }
    
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Look for the "VISIT EUROSUIT" link in the top navigation and change it to "VISIT BRIDE&CO"
        var navLinks = document.querySelectorAll('.nav-links a.visit-link');
        
        navLinks.forEach(function(link) {
            // Check if this is the Eurosuit link
            if (link.textContent.includes('EUROSUIT') || link.href.includes('eurosuit')) {
                // Change to Bride&co
                link.textContent = 'VISIT BRIDE&CO';
                link.href = 'https://brideandco.co.za/';
            } 
            // If on Eurosuit page but looking at a Bride&co link, keep it as is
        });
        
        // For regular Bride&co pages, this function doesn't run since we're checking is_eurosuit_page() first
    });
    </script>
    <?php
}

// Add this function to the wp_head hook
add_action('wp_head', 'update_visit_link_in_eurosuit_styling', 110);

/**
 * Also add the complementary function for Bride&co pages to show "VISIT EUROSUIT"
 */
function update_visit_link_in_brideco_styling() {
    // Only run on non-Eurosuit pages
    if (is_eurosuit_page()) {
        return;
    }
    
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Look for the "VISIT BRIDE&CO" link in the top navigation and ensure it says "VISIT EUROSUIT"
        var navLinks = document.querySelectorAll('.nav-links a.visit-link');
        
        navLinks.forEach(function(link) {
            // Check if this link goes to eurosuit
            if (link.href.includes('eurosuit')) {
                // Ensure it says VISIT EUROSUIT
                link.textContent = 'VISIT EUROSUIT';
            }
        });
    });
    </script>
    <?php
}

// Add this function to the wp_head hook for non-Eurosuit pages
add_action('wp_head', 'update_visit_link_in_brideco_styling', 110);



/**
 * Category Cards Section for Eurosuit Template
 * 
 * Adds a category cards section with editable heading
 * that appears directly in your Eurosuit page editor.
 */

/**
 * Register ACF fields specifically for the Eurosuit page
 */
function eurosuit_register_category_cards_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    // First, let's find the ID of the Eurosuit page to target it directly
    $eurosuit_page = get_page_by_path('eurosuit');
    $eurosuit_page_id = $eurosuit_page ? $eurosuit_page->ID : 0;
    
    // If we couldn't find the page by path, try looking for it by title
    if (!$eurosuit_page_id) {
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'euro-suit-home.php'
        ));
        
        if (!empty($pages)) {
            $eurosuit_page_id = $pages[0]->ID;
        }
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_eurosuit_category_cards',
        'title' => 'Eurosuit Category Cards Section',
        'fields' => array(
            array(
                'key' => 'field_show_eurosuit_category_cards',
                'label' => 'Display Eurosuit Category Cards',
                'name' => 'show_eurosuit_category_cards',
                'type' => 'true_false',
                'instructions' => 'Enable or disable the Eurosuit category cards section',
                'default_value' => 1,
                'ui' => 1,
            ),
            array(
                'key' => 'field_eurosuit_category_heading',
                'label' => 'Section Heading',
                'name' => 'eurosuit_category_heading',
                'type' => 'text',
                'instructions' => 'Enter a heading for this section (leave empty for no heading)',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_category_cards',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_eurosuit_category_cards',
                'label' => 'Category Cards',
                'name' => 'eurosuit_category_cards',
                'type' => 'repeater',
                'instructions' => 'Add up to 4 category cards',
                'min' => 1,
                'max' => 4,
                'layout' => 'block',
                'button_label' => 'Add Card',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_category_cards',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_eurosuit_card_title',
                        'label' => 'Card Title',
                        'name' => 'card_title',
                        'type' => 'text',
                        'instructions' => 'Enter the title to display on the card (e.g., "FORMAL SUITS")',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_eurosuit_button_text',
                        'label' => 'Button Text',
                        'name' => 'button_text',
                        'type' => 'text',
                        'instructions' => 'Enter the text for the button (e.g., "SHOP NOW")',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_eurosuit_button_link',
                        'label' => 'Button Link',
                        'name' => 'button_link',
                        'type' => 'url',
                        'instructions' => 'Enter the URL for the button',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_eurosuit_background_image',
                        'label' => 'Background Image',
                        'name' => 'background_image',
                        'type' => 'image',
                        'instructions' => 'Upload or select the background image for this card',
                        'required' => 1,
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                    ),
                ),
            ),
        ),
        'location' => array(
            // Target by specific page ID if found
            $eurosuit_page_id ? array(
                array(
                    'param' => 'post',
                    'operator' => '==',
                    'value' => $eurosuit_page_id,
                ),
            ) : array(),
            // Target by page slug
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => 'eurosuit',
                ),
            ),
            // Target by URL path
            array(
                array(
                    'param' => 'page_slug',
                    'operator' => '==',
                    'value' => 'eurosuit',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));
}
add_action('acf/init', 'eurosuit_register_category_cards_fields');

/**
 * Add CSS for Eurosuit category cards
 */
function eurosuit_category_cards_css() {
    // Only add styles on Eurosuit pages
    if (!is_eurosuit_page()) {
        return;
    }
    
    // Get Eurosuit primary color from customizer
    $eurosuit_color = get_theme_mod('eurosuit_primary_color', '#192f5a');
    
    ?>
    <style>
        /* Eurosuit Category Section Heading */
        .eurosuit-category-section-heading {
            font-family: "Cinzel", sans-serif;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #333;
            text-align: left;
        }
        
        /* Eurosuit Category Card Styling */
        .eurosuit-category-card {
            position: relative;
            background-size: cover;
            background-position: center;
            height: 400px;
            color: white;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            text-align: center;
            border-radius: 0; /* More squared look for Eurosuit */
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
            padding-bottom: 20px;
            margin-bottom: 30px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2); /* Subtle shadow for depth */
        }

        .eurosuit-category-card:hover {
            transform: scale(1.03);
        }

        /* Dark Overlay - slightly darker for Eurosuit */
        .eurosuit-category-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
        }

        /* Card Content */
        .eurosuit-category-content {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            text-align: center;
            width: 100%;
        }

        .eurosuit-category-title {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
            white-space: nowrap;
            color: white;
            font-family: "Cinzel", sans-serif;
        }

        /* Shop Button - Using Eurosuit colors */
        .eurosuit-shop-button {
            background-color: <?php echo esc_attr($eurosuit_color); ?>;
            color: white;
            font-weight: 400;
            padding: 8px 20px;
            border: none;
            text-decoration: none;
            display: inline-block;
            font-size: 0.9rem;
            white-space: nowrap;
            font-family: "Poppins", sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .eurosuit-shop-button:hover {
            background-color: <?php echo esc_attr($eurosuit_color); ?>dd;
            color: white;
        }
    </style>
    <?php
}
add_action('wp_head', 'eurosuit_category_cards_css');

/**
 * Function to render Eurosuit category cards section in template
 */
function eurosuit_render_category_cards() {
    // Make sure we're on an Eurosuit page
    if (!is_eurosuit_page()) {
        return;
    }
    
    // Check if Eurosuit category cards should be displayed
    $show_cards = get_field('show_eurosuit_category_cards');
    
    if (!$show_cards) {
        return;
    }
    
    // Get the Eurosuit category cards and heading
    $cards = get_field('eurosuit_category_cards');
    $heading = get_field('eurosuit_category_heading');
    
    if (empty($cards)) {
        return;
    }
    
    ?>
    <section class="container my-5">
        <?php if (!empty($heading)) : ?>
            <h2 class="eurosuit-category-section-heading"><?php echo esc_html($heading); ?></h2>
        <?php endif; ?>
        
        <div class="row g-4">
            <?php foreach ($cards as $card) : 
                // Get card data
                $card_title = $card['card_title'];
                $button_text = $card['button_text'];
                $button_link = $card['button_link'];
                $background_image = $card['background_image'];
                
                // Skip if missing essential data
                if (empty($card_title) || empty($button_text) || empty($button_link) || empty($background_image)) {
                    continue;
                }
                
                // Generate background style
                $bg_style = '';
                if (!empty($background_image) && is_array($background_image)) {
                    $bg_style = 'background-image: url(' . esc_url($background_image['url']) . ');';
                }
            ?>
                <div class="col-md-3">
                    <a href="<?php echo esc_url($button_link); ?>" class="text-decoration-none">
                        <div class="eurosuit-category-card" style="<?php echo $bg_style; ?>">
                            <div class="eurosuit-category-content">
                                <h2 class="eurosuit-category-title"><?php echo esc_html($card_title); ?></h2>
                                <a href="<?php echo esc_url($button_link); ?>" class="eurosuit-shop-button"><?php echo esc_html($button_text); ?></a>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
}

/**
 * Helper function to check if we're on an Eurosuit page
 * Include this if the is_eurosuit_page() function isn't already defined elsewhere
 */
if (!function_exists('is_eurosuit_page')) {
    function is_eurosuit_page() {
        // Check if we're on the Eurosuit template
        if (is_page_template('euro-suit-home.php') || is_page('eurosuit')) {
            return true;
        }
        
        // Check if we're on a product page with "eurosuit" brand attribute
        if (is_singular('product')) {
            global $product;
            if (!$product && function_exists('wc_get_product')) {
                $product = wc_get_product(get_the_ID());
            }
            
            if ($product) {
                $attributes = $product->get_attributes();
                if (isset($attributes['pa_brand'])) {
                    $terms = wc_get_product_terms($product->get_id(), 'pa_brand', array('fields' => 'names'));
                    if (in_array('Eurosuit', $terms) || in_array('eurosuit', $terms)) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
}

// Add this function call to your Eurosuit home page template (euro-suit-home.php):
// <?php eurosuit_render_category_cards(); 

/**
 * Featured Products Section for Eurosuit Template
 * 
 * Adds a featured products section with two large cards
 * that appears directly in your Eurosuit page editor.
 */

/**
 * Register ACF fields for Eurosuit featured products section
 */
function eurosuit_register_featured_products_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    // First, let's find the ID of the Eurosuit page to target it directly
    $eurosuit_page = get_page_by_path('eurosuit');
    $eurosuit_page_id = $eurosuit_page ? $eurosuit_page->ID : 0;
    
    // If we couldn't find the page by path, try looking for it by title
    if (!$eurosuit_page_id) {
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'euro-suit-home.php'
        ));
        
        if (!empty($pages)) {
            $eurosuit_page_id = $pages[0]->ID;
        }
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_eurosuit_featured_products',
        'title' => 'Eurosuit Featured Products Section',
        'fields' => array(
            array(
                'key' => 'field_show_eurosuit_featured_products',
                'label' => 'Display Featured Products',
                'name' => 'show_eurosuit_featured_products',
                'type' => 'true_false',
                'instructions' => 'Enable or disable the featured products section',
                'default_value' => 1,
                'ui' => 1,
            ),
            array(
                'key' => 'field_eurosuit_featured_products',
                'label' => 'Featured Products',
                'name' => 'eurosuit_featured_products',
                'type' => 'repeater',
                'instructions' => 'Add up to 2 featured product cards',
                'min' => 1,
                'max' => 2,
                'layout' => 'block',
                'button_label' => 'Add Featured Product',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_featured_products',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_eurosuit_featured_top_text',
                        'label' => 'Top Text',
                        'name' => 'top_text',
                        'type' => 'text',
                        'instructions' => 'Enter the small text to display at the top (e.g., "SHOP OUR")',
                    ),
                    array(
                        'key' => 'field_eurosuit_featured_main_text',
                        'label' => 'Main Text',
                        'name' => 'main_text',
                        'type' => 'text',
                        'instructions' => 'Enter the main heading text (e.g., "FORMAL SUITS")',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_eurosuit_featured_bottom_text',
                        'label' => 'Bottom Text',
                        'name' => 'bottom_text',
                        'type' => 'text',
                        'instructions' => 'Enter the small text to display at the bottom (e.g., "COLLECTION")',
                    ),
                    array(
                        'key' => 'field_eurosuit_featured_button_text',
                        'label' => 'Button Text',
                        'name' => 'button_text',
                        'type' => 'text',
                        'instructions' => 'Enter the text for the button (e.g., "Shop Now")',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_eurosuit_featured_button_link',
                        'label' => 'Button Link',
                        'name' => 'button_link',
                        'type' => 'url',
                        'instructions' => 'Enter the URL for the button',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_eurosuit_featured_background_image',
                        'label' => 'Background Image',
                        'name' => 'background_image',
                        'type' => 'image',
                        'instructions' => 'Upload or select the background image for this featured product',
                        'required' => 1,
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                    ),
                ),
            ),
        ),
        'location' => array(
            // Target by specific page ID if found
            $eurosuit_page_id ? array(
                array(
                    'param' => 'post',
                    'operator' => '==',
                    'value' => $eurosuit_page_id,
                ),
            ) : array(),
            // Target by page slug
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => 'eurosuit',
                ),
            ),
            // Target by URL path
            array(
                array(
                    'param' => 'page_slug',
                    'operator' => '==',
                    'value' => 'eurosuit',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));
}
add_action('acf/init', 'eurosuit_register_featured_products_fields');

/**
 * Add CSS for Eurosuit featured products
 */
function eurosuit_featured_products_css() {
    // Only add styles on Eurosuit pages
    if (!is_eurosuit_page()) {
        return;
    }
    
    // Get Eurosuit primary color from customizer
    $eurosuit_color = get_theme_mod('eurosuit_primary_color', '#192f5a');
    
    ?>
    <style>
        /* Eurosuit Featured Products Styling */
        .eurosuit-featured-product-card {
            position: relative;
            overflow: hidden;
            margin-bottom: 30px;
            border-radius: 0; /* Squared corners for Eurosuit */
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); /* Add subtle shadow */
        }
        
        .eurosuit-featured-product-card img {
            width: 100%;
            transition: transform 0.3s ease;
            filter: contrast(105%); /* Slightly increase contrast for men's formal wear */
        }
        
        .eurosuit-featured-product-card:hover img {
            transform: scale(1.05);
        }
        
        .eurosuit-featured-product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            background: rgba(0, 0, 0, 0.3); /* Slightly darker overlay */
        }
        
        .eurosuit-featured-product-overlay p {
            margin-bottom: 4px;
            font-family: "Poppins", sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 18px;
        }
        
        .eurosuit-featured-product-main-text {
            font-family: "Cinzel", serif;
            font-weight: 700;
            font-size: 55px;
            margin: 5px 0;
            text-transform: uppercase;
        }
        
        .eurosuit-shop-button {
            background-color: <?php echo esc_attr($eurosuit_color); ?>;
            color: white !important;
            font-weight: 500;
            padding: 8px 20px;
            border: none;
            text-decoration: none;
            display: inline-block;
            font-size: 0.9rem;
            margin-top: 10px;
            text-transform: uppercase;
            font-family: "Poppins", sans-serif;
            transition: all 0.3s ease;
            letter-spacing: 1px;
        }
        
        .eurosuit-shop-button:hover {
            background-color: <?php echo esc_attr($eurosuit_color); ?>dd;
            color: white !important;
            text-decoration: none;
        }
    </style>
    <?php
}
add_action('wp_head', 'eurosuit_featured_products_css');

/**
 * Function to render Eurosuit featured products section in template
 */
function eurosuit_render_featured_products() {
    // Make sure we're on an Eurosuit page
    if (!is_eurosuit_page()) {
        return;
    }
    
    // Check if Eurosuit featured products should be displayed
    $show_products = get_field('show_eurosuit_featured_products');
    
    if (!$show_products) {
        return;
    }
    
    // Get the Eurosuit featured products
    $products = get_field('eurosuit_featured_products');
    
    if (empty($products)) {
        return;
    }
    
    ?>
    <section class="container mt-5">
        <div class="row">
            <?php 
            $count = count($products);
            $col_class = ($count == 1) ? 'col-md-12' : 'col-md-6';
            
            foreach ($products as $product) : 
                // Get product data
                $top_text = $product['top_text'];
                $main_text = $product['main_text'];
                $bottom_text = $product['bottom_text'];
                $button_text = $product['button_text'];
                $button_link = $product['button_link'];
                $background_image = $product['background_image'];
                
                // Skip if missing essential data
                if (empty($main_text) || empty($button_text) || empty($button_link) || empty($background_image)) {
                    continue;
                }
            ?>
                <div class="<?php echo esc_attr($col_class); ?>">
                    <div class="eurosuit-featured-product-card">
                        <img src="<?php echo esc_url($background_image['url']); ?>" class="w-100" alt="<?php echo esc_attr($main_text); ?>">
                        <div class="eurosuit-featured-product-overlay">
                            <?php if (!empty($top_text)) : ?>
                                <p class="mb-1"><?php echo esc_html($top_text); ?></p>
                            <?php endif; ?>
                            
                            <h2 class="eurosuit-featured-product-main-text"><?php echo esc_html($main_text); ?></h2>
                            
                            <?php if (!empty($bottom_text)) : ?>
                                <p class="mb-3"><?php echo esc_html($bottom_text); ?></p>
                            <?php endif; ?>
                            
                            <a href="<?php echo esc_url($button_link); ?>" class="eurosuit-shop-button">
                                <?php echo esc_html($button_text); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
}

/**
 * Helper function to check if we're on an Eurosuit page
 * Include this if the is_eurosuit_page() function isn't already defined elsewhere
 */
if (!function_exists('is_eurosuit_page')) {
    function is_eurosuit_page() {
        // Check if we're on the Eurosuit template
        if (is_page_template('euro-suit-home.php') || is_page('eurosuit')) {
            return true;
        }
        
        // Check if we're on a product page with "eurosuit" brand attribute
        if (is_singular('product')) {
            global $product;
            if (!$product && function_exists('wc_get_product')) {
                $product = wc_get_product(get_the_ID());
            }
            
            if ($product) {
                $attributes = $product->get_attributes();
                if (isset($attributes['pa_brand'])) {
                    $terms = wc_get_product_terms($product->get_id(), 'pa_brand', array('fields' => 'names'));
                    if (in_array('Eurosuit', $terms) || in_array('eurosuit', $terms)) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
}

// Add this code to your Eurosuit home page template (euro-suit-home.php):
// <?php eurosuit_render_featured_products(); 

/**
 * White Banner Section for Eurosuit Template
 * 
 * Adds a full-width white banner section with editable heading
 * that appears directly in your Eurosuit page editor.
 */

/**
 * Register ACF fields for Eurosuit white banner section
 */
function eurosuit_register_white_banner_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    // First, let's find the ID of the Eurosuit page to target it directly
    $eurosuit_page = get_page_by_path('eurosuit');
    $eurosuit_page_id = $eurosuit_page ? $eurosuit_page->ID : 0;
    
    // If we couldn't find the page by path, try looking for it by title
    if (!$eurosuit_page_id) {
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'euro-suit-home.php'
        ));
        
        if (!empty($pages)) {
            $eurosuit_page_id = $pages[0]->ID;
        }
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_eurosuit_white_banner',
        'title' => 'Eurosuit Banner Section',
        'fields' => array(
            array(
                'key' => 'field_show_eurosuit_white_banner',
                'label' => 'Display Banner',
                'name' => 'show_eurosuit_white_banner',
                'type' => 'true_false',
                'instructions' => 'Enable or disable the banner section',
                'default_value' => 1,
                'ui' => 1,
            ),
            array(
                'key' => 'field_eurosuit_banner_title',
                'label' => 'Banner Title',
                'name' => 'eurosuit_banner_title',
                'type' => 'text',
                'instructions' => 'Enter the main title text (e.g., "TAILORED")',
                'default_value' => 'TAILORED',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_white_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_eurosuit_banner_subtitle',
                'label' => 'Banner Subtitle',
                'name' => 'eurosuit_banner_subtitle',
                'type' => 'text',
                'instructions' => 'Enter the subtitle text (e.g., "PERFECTION")',
                'default_value' => 'PERFECTION',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_white_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_eurosuit_banner_button_text',
                'label' => 'Button Text',
                'name' => 'eurosuit_banner_button_text',
                'type' => 'text',
                'instructions' => 'Enter the text for the button (e.g., "SHOP NOW")',
                'default_value' => 'SHOP NOW',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_white_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_eurosuit_banner_button_link',
                'label' => 'Button Link',
                'name' => 'eurosuit_banner_button_link',
                'type' => 'url',
                'instructions' => 'Enter the URL for the button',
                'default_value' => '#',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_white_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_eurosuit_banner_background_image',
                'label' => 'Background Image',
                'name' => 'eurosuit_banner_background_image',
                'type' => 'image',
                'instructions' => 'Upload or select the background image for the banner',
                'required' => 1,
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_white_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_eurosuit_text_position',
                'label' => 'Text Position',
                'name' => 'eurosuit_text_position',
                'type' => 'select',
                'instructions' => 'Choose the text alignment within the banner',
                'choices' => array(
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right',
                ),
                'default_value' => 'left',
                'return_format' => 'value',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_white_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_eurosuit_text_alignment',
                'label' => 'Text Alignment',
                'name' => 'eurosuit_text_alignment',
                'type' => 'select',
                'instructions' => 'Choose how to align the text content',
                'choices' => array(
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right',
                ),
                'default_value' => 'center',
                'return_format' => 'value',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_white_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_eurosuit_vertical_position',
                'label' => 'Vertical Position',
                'name' => 'eurosuit_vertical_position',
                'type' => 'select',
                'instructions' => 'Choose the vertical positioning of text',
                'choices' => array(
                    'top' => 'Top',
                    'middle' => 'Middle',
                    'bottom' => 'Bottom',
                ),
                'default_value' => 'middle',
                'return_format' => 'value',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_white_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_eurosuit_text_padding',
                'label' => 'Text Padding',
                'name' => 'eurosuit_text_padding',
                'type' => 'number',
                'instructions' => 'Padding around text in pixels',
                'default_value' => 40,
                'min' => 0,
                'max' => 100,
                'step' => 5,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_white_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            // Target by specific page ID if found
            $eurosuit_page_id ? array(
                array(
                    'param' => 'post',
                    'operator' => '==',
                    'value' => $eurosuit_page_id,
                ),
            ) : array(),
            // Target by page slug
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => 'eurosuit',
                ),
            ),
            // Target by URL path
            array(
                array(
                    'param' => 'page_slug',
                    'operator' => '==',
                    'value' => 'eurosuit',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));
}
add_action('acf/init', 'eurosuit_register_white_banner_fields');

/**
 * Add CSS for Eurosuit white banner
 */
function eurosuit_white_banner_css() {
    // Only add styles on Eurosuit pages
    if (!is_eurosuit_page()) {
        return;
    }
    
    // Get Eurosuit primary color from customizer
    $eurosuit_color = get_theme_mod('eurosuit_primary_color', '#192f5a');
    
    ?>
    <style>
        .eurosuit-banner {
            position: relative;
            width: 100%;
            overflow: hidden;
            margin: 3rem 0;
        }
        
        .eurosuit-banner-content {
            display: flex;
            min-height: 450px;
            position: relative;
        }
        
        .eurosuit-banner-text {
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: #fff;
            z-index: 2;
            position: relative;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); /* Subtle shadow for depth */
        }
        
        /* Text position classes */
        .eurosuit-banner-text.text-left {
            left: 0;
            margin-right: auto;
        }
        
        .eurosuit-banner-text.text-center {
            left: 0;
            width: 50%;
        }
        
        .eurosuit-banner-text.text-right {
            right: 0;
            left: auto;
            margin-left: auto;
        }
        
        /* Vertical alignment classes */
        .eurosuit-banner-text.align-top {
            justify-content: flex-start;
            padding-top: 40px;
        }
        
        .eurosuit-banner-text.align-middle {
            justify-content: center;
        }
        
        .eurosuit-banner-text.align-bottom {
            justify-content: flex-end;
            padding-bottom: 40px;
        }
        
        .eurosuit-banner-image {
            position: absolute;
    right: 0;
    top: 0;
    width: 50%;
    height: 100%;
    background-size: cover;
    background-position: center center;
    z-index: 1;
    filter: contrast(105%);
    background-repeat: no-repeat;
        }
        
        .eurosuit-banner-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: 2px;
            color: #333;
            text-transform: uppercase;
        }
        
        .eurosuit-banner-subtitle {
            font-size: 1.8rem;
            margin-bottom: 2rem;
            letter-spacing: 1px;
            color: #555;
            text-transform: uppercase;
        }
        
        .eurosuit-banner-button {
            display: inline-block;
            padding: 12px 30px;
            background-color: <?php echo esc_attr($eurosuit_color); ?>;
            color: #fff !important;
            text-decoration: none;
            font-weight: 500;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            border: none;
        }
        
        .eurosuit-banner-button:hover {
            background-color: <?php echo esc_attr($eurosuit_color); ?>dd;
            color: #fff;
            text-decoration: none;
        }
        
        @media (max-width: 991px) {
            .eurosuit-banner-text {
                width: 60%;
            }
            
            .eurosuit-banner-image {
                width: 55%;
            }
        }
        
        @media (max-width: 767px) {
            .eurosuit-banner-content {
                flex-direction: column;
            }
            
            .eurosuit-banner-text {
                width: 100%;
                order: 2;
                padding: 30px 20px;
            }
            
            .eurosuit-banner-image {
                position: relative;
                width: 100%;
                height: 300px;
                order: 1;
            }
            
            .eurosuit-banner-title {
                font-size: 2.5rem;
            }
            
            .eurosuit-banner-subtitle {
                font-size: 1.5rem;
            }
        }
    </style>
    <?php
}
add_action('wp_head', 'eurosuit_white_banner_css');

/**
 * Function to render Eurosuit white banner section in template
 */
function eurosuit_render_white_banner() {
    // Make sure we're on an Eurosuit page
    if (!is_eurosuit_page()) {
        return;
    }
    
    // Check if white banner should be displayed
    $show_banner = get_field('show_eurosuit_white_banner');
    
    if (!$show_banner) {
        return;
    }
    
    // Get the banner data
    $banner_title = get_field('eurosuit_banner_title');
    $banner_subtitle = get_field('eurosuit_banner_subtitle');
    $button_text = get_field('eurosuit_banner_button_text');
    $button_link = get_field('eurosuit_banner_button_link');
    $background_image = get_field('eurosuit_banner_background_image');
    
    // Get positioning settings
    $text_position = get_field('eurosuit_text_position') ?: 'left';
    $text_alignment = get_field('eurosuit_text_alignment') ?: 'center';
    $vertical_position = get_field('eurosuit_vertical_position') ?: 'middle';
    $text_padding = get_field('eurosuit_text_padding') ?: 40;
    
    // Skip if missing essential data
    if (empty($banner_title) || empty($banner_subtitle) || empty($button_text) || empty($button_link) || empty($background_image)) {
        return;
    }
    
    // Generate background style
    $bg_style = '';
    if (!empty($background_image) && is_array($background_image)) {
        $bg_style = 'background-image: url(' . esc_url($background_image['url']) . ');';
    }
    
    // Generate text position classes and styles
    $text_position_class = 'text-' . $text_position;
    $text_align_style = 'text-align: ' . $text_alignment . ';';
    $text_padding_style = 'padding: ' . $text_padding . 'px;';
    
    // Define vertical alignment class
    $vertical_align_class = '';
    switch ($vertical_position) {
        case 'top':
            $vertical_align_class = 'align-top';
            break;
        case 'bottom':
            $vertical_align_class = 'align-bottom';
            break;
        default:
            $vertical_align_class = 'align-middle';
            break;
    }
    
    ?>
<!-- Eurosuit Banner Section -->
<section class="container-fluid my-5 px-0">
  <div class="eurosuit-banner">
    <div class="eurosuit-banner-content">
      <div class="eurosuit-banner-text <?php echo esc_attr($text_position_class . ' ' . $vertical_align_class); ?>" style="<?php echo esc_attr($text_align_style . ' ' . $text_padding_style); ?>">
        <h2 class="eurosuit-banner-title"><?php echo esc_html($banner_title); ?></h2>
        <p class="eurosuit-banner-subtitle"><?php echo esc_html($banner_subtitle); ?></p>
        <a href="<?php echo esc_url($button_link); ?>" class="eurosuit-banner-button"><?php echo esc_html($button_text); ?></a>
      </div>
      <div class="eurosuit-banner-image" style="<?php echo $bg_style; ?>">
        <!-- The image is loaded as the background -->
      </div>
    </div>
  </div>
</section>
    <?php
}

/**
 * Helper function to check if we're on an Eurosuit page
 * Include this if the is_eurosuit_page() function isn't already defined elsewhere
 */
if (!function_exists('is_eurosuit_page')) {
    function is_eurosuit_page() {
        // Check if we're on the Eurosuit template
        if (is_page_template('euro-suit-home.php') || is_page('eurosuit')) {
            return true;
        }
        
        // Check if we're on a product page with "eurosuit" brand attribute
        if (is_singular('product')) {
            global $product;
            if (!$product && function_exists('wc_get_product')) {
                $product = wc_get_product(get_the_ID());
            }
            
            if ($product) {
                $attributes = $product->get_attributes();
                if (isset($attributes['pa_brand'])) {
                    $terms = wc_get_product_terms($product->get_id(), 'pa_brand', array('fields' => 'names'));
                    if (in_array('Eurosuit', $terms) || in_array('eurosuit', $terms)) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
}

// Add this code to your Eurosuit home page template (euro-suit-home.php):
// <?php eurosuit_render_white_banner(); 

/**
 * Eurosuit Latest Products Shortcode - Displays actual WooCommerce products with Eurosuit brand attribute
 * Usage: [eurosuit_latest_products]
 */
function eurosuit_latest_products_shortcode() {
    // Start output buffering
    ob_start();
    
    // Get Eurosuit primary color from customizer for styling
    $eurosuit_color = get_theme_mod('eurosuit_primary_color', '#192f5a');
    
    // Add custom styling for Eurosuit products
    ?>
    <style>
        /* Eurosuit product card styling */
        .eurosuit-product-card {
            position: relative;
            border: none;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
            height: 100%;
			text-align: center;
        }
        
        .eurosuit-product-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .eurosuit-image-container {
            position: relative;
            overflow: hidden;
            padding-bottom: 130%; /* Maintain aspect ratio */
        }
        
        .eurosuit-product-image,
        .eurosuit-hover-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.3s ease;
        }
        
        .eurosuit-hover-image {
            opacity: 0;
        }
        
        .eurosuit-image-container:hover .eurosuit-product-image {
            opacity: 0;
        }
        
        .eurosuit-image-container:hover .eurosuit-hover-image {
            opacity: 1;
        }
        
        .eurosuit-label {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: <?php echo esc_attr($eurosuit_color); ?>;
            color: white;
            padding: 5px 10px;
            font-size: 12px;
            z-index: 2;
            text-transform: uppercase;
            font-weight: 500;
        }
        
        .eurosuit-add-to-cart-btn {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: <?php echo esc_attr($eurosuit_color); ?>;
            color: white;
            text-align: center;
            padding: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
            font-weight: 500;
        }
        
        .eurosuit-image-container:hover .eurosuit-add-to-cart-btn {
            opacity: 1;
        }
        
        .eurosuit-product-title {
            font-weight: 700;
            font-size: 16px;
            margin-top: 1rem;
            margin-bottom: 5px;
            color: #333;
        }
        
        .eurosuit-tech-spec-title {
            font-weight: 700;
            font-size: 18px;
            margin-top: 1rem;
            margin-bottom: 5px;
            color: #333;
        }
        
        .eurosuit-product-title-with-spec {
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 5px;
            color: #666;
        }
        
        .eurosuit-product-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
			padding: 0 10px;
        }
        
        .eurosuit-product-brand {
            font-size: 14px;
            font-weight: 500;
            color: #888;
            margin-bottom: 5px;
        }
        
        .eurosuit-product-pricing {
            font-weight: 700;
            font-size: 16px;
            color: #333;
            margin-bottom: 0;
        }
        
        .eurosuit-product-pricing del {
            color: #999;
            font-weight: 400;
            margin-right: 5px;
        }
        
        .eurosuit-product-pricing ins {
            text-decoration: none;
            color: #d04747;
        }
        
        .eurosuit-discount-box {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #d04747;
            color: white;
            padding: 5px 10px;
            font-size: 12px;
            z-index: 2;
            font-weight: 500;
        }
        
        .eurosuit-product-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }
        
        .eurosuit-product-card-link:hover {
            text-decoration: none;
            color: inherit;
        }
        
        .eurosuit-section-title {
            font-weight: 700;
            margin-bottom: 2rem;
            color: #333;
            text-transform: uppercase;
        }
    </style>
    <?php
    
    // Query to get products with Eurosuit brand attribute - excluding sold out and products without price
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 4,
        'tax_query'      => array(
            array(
                'taxonomy' => 'pa_brand',
                'field'    => 'slug',
                'terms'    => 'eurosuit',
            ),
        ),
        'meta_query'     => array(
            'relation'    => 'AND',
            // Only in-stock products
            array(
                'key'     => '_stock_status',
                'value'   => 'instock',
                'compare' => '=',
            ),
            // Only products with price
            array(
                'key'     => '_price',
                'value'   => '',
                'compare' => '!=',
            ),
            array(
                'key'     => '_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'NUMERIC'
            ),
        ),
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    
    $products_query = new WP_Query($args);
    
    if ($products_query->have_posts()) : ?>
    <section class="container my-5">
      <h2 class="eurosuit-section-title">Latest Eurosuit Collection</h2>
      <div class="row g-4">
        <?php 
        while ($products_query->have_posts()) : 
            $products_query->the_post();
            global $product;
            
            // Skip if not a valid product
            if (!is_a($product, 'WC_Product')) {
                continue;
            }
            
            // Get product data using WooCommerce's display methods for better compatibility
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
            
            // Get brand or title
            $brand = get_the_title();
            
            // Get technical specification attribute value or product name
            $product_name = '';
            $tech_spec_terms = get_the_terms($product_id, 'pa_technical-spec');
            if (!empty($tech_spec_terms) && !is_wp_error($tech_spec_terms)) {
                $product_name = $tech_spec_terms[0]->name;
            }
            
            // If tech spec not available, check meta_product_name
            if (empty($product_name)) {
                $meta_product_name = get_post_meta($product_id, 'meta_product_name', true);
                if (!empty($meta_product_name) && $meta_product_name !== '{{product.meta_product name}}') {
                    $product_name = $meta_product_name;
                }
            }
            
            // Get product categories for additional info
            $product_categories = '';
            $categories = get_the_terms($product_id, 'product_cat');
            if (!empty($categories) && !is_wp_error($categories)) {
                // Get the last category (most specific)
                $product_categories = end($categories)->name;
            }
            
            // Check if product is new (less than 30 days old)
            $is_new = function_exists('is_product_new') ? is_product_new($product_id) : false; 
            $product_id;$is_eurosuite = function_exists('is_eurosuit_page') ? is_eurosuit_page($product_id) : false;    
             if($is_eurosuite)
             $newlabel_style ='new-badge-euro'; 
        ?>
        <div class="col-md-3">
          <a href="<?php echo esc_url(get_permalink()); ?>" class="eurosuit-product-card-link">
            <div class="eurosuit-product-card">
              <div class="eurosuit-image-container">
              <?php
                              if($is_new)
                              {
                                ?>
                                <span class="label new-label <?php echo $newlabel_style;?>">NEW</span>
                              <?php
                            }
                            ?>
                
                <?php if (has_post_thumbnail()) : ?>
                  <img
                    src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>"
                    class="eurosuit-product-image"
                    alt="<?php echo esc_attr(get_the_title()); ?>"
                  />
                <?php else : ?>
                  <img
                    src="<?php echo wc_placeholder_img_src(); ?>"
                    class="eurosuit-product-image"
                    alt="<?php echo esc_attr(get_the_title()); ?>"
                  />
                <?php endif; ?>
                
                <?php if ($hover_image) : ?>
                  <img
                    src="<?php echo esc_url($hover_image); ?>"
                    class="eurosuit-hover-image"
                    alt="<?php echo esc_attr(get_the_title()); ?> Hover"
                  />
                <?php elseif (has_post_thumbnail()) : ?>
                  <img
                    src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>"
                    class="eurosuit-hover-image"
                    alt="<?php echo esc_attr(get_the_title()); ?> Hover"
                  />
                <?php endif; ?>
                
                <span class="eurosuit-add-to-cart-btn">SHOP NOW</span>
              </div>
              
              <?php if (!empty($product_name)) : ?>
                <h4 class="eurosuit-tech-spec-title"><?php echo esc_html($brand); ?></h4>
              <?php else : ?>
                <h5 class="eurosuit-product-title"><?php echo esc_html($brand); ?></h5>
              <?php endif; ?>
              
              <p class="eurosuit-product-description"><?php echo wp_trim_words($product->get_short_description(), 10, '...'); ?></p>
              
              <?php if ($is_on_sale && $discount_percentage > 0) : ?>
                <span class="eurosuit-discount-box"><?php echo esc_html($discount_percentage); ?>% OFF</span>
              <?php endif; ?>
              
              <?php if (!empty($product_categories)) : ?>
                <p class="eurosuit-product-brand"><?php echo esc_html($product_categories); ?></p>
              <?php endif; ?>
              
              <p class="eurosuit-product-pricing">
                <?php echo $product->get_price_html(); ?>
              </p>
            </div>
          </a>
        </div>
        <?php endwhile; ?>
      </div>
    </section>
    <?php else: ?>
      <div class="container my-5">
        <div class="alert alert-info">No Eurosuit products found.</div>
      </div>
    <?php
    endif;
    
    // Reset post data to avoid conflicts
    wp_reset_postdata();
    
    // Return the buffered content
    return ob_get_clean();
}
add_shortcode('eurosuit_latest_products', 'eurosuit_latest_products_shortcode');

// Step 1: Register ACF Field Group for Euro Suit Video with Title
function register_euro_suit_video_field() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_euro_suit_video',
            'title' => 'Euro Suit Video Settings',
            'fields' => array(
                array(
                    'key' => 'field_euro_suit_video_title',
                    'label' => 'Video Section Title',
                    'name' => 'euro_suit_video_title',
                    'type' => 'text',
                    'instructions' => 'Enter a title for the video section',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => 'Euro Suit Fitting Video',
                    'placeholder' => 'Enter video section title',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_euro_suit_video_link',
                    'label' => 'Euro Suit Fitting Video Link',
                    'name' => 'euro_suit_video_link',
                    'type' => 'text',
                    'instructions' => 'Enter the full embed URL of the video',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => 'https://www.youtube.com/embed/videoID?si=uniquestring',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'page_template',
                        'operator' => '==',
                        'value' => 'euro-suit.php',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => false,
        ));
    }
}
add_action('acf/init', 'register_euro_suit_video_field');

// Step 2: Create Shortcode to Display Video with Title
function euro_suit_video_shortcode() {
    // Only proceed if we're on the Euro Suit page
    if (!is_page_template('euro-suit.php')) {
        return '';
    }

    // Get the video URL and title from ACF fields
    $video_url = get_field('euro_suit_video_link');
    $video_title = get_field('euro_suit_video_title');

    // If no video URL is set, return empty string
    if (empty($video_url)) {
        return '';
    }

    // Ensure the URL is a valid YouTube embed URL
    if (!preg_match('/^https?:\/\/(www\.)?youtube\.com\/embed\/[a-zA-Z0-9_-]+/', $video_url)) {
        return '';
    }

    // Generate the HTML with Bootstrap layout
    $video_html = sprintf(
        '<div class="container">
            <div class="row">
                <div class="col-12">
                    %s
                    <div class="euro-suit-video-container">
                        <iframe 
                            src="%s" 
                            class="img-fluid w-100" 
                            style="min-height: 500px; max-height: 720px;" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            referrerpolicy="strict-origin-when-cross-origin" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        </div>',
        // Title with left-aligned class
        !empty($video_title) ? sprintf('<h2 class="mb-3 text-start">%s</h2>', esc_html($video_title)) : '',
        esc_url($video_url)
    );

    return $video_html;
}
add_shortcode('euro_suit_video', 'euro_suit_video_shortcode');


/**
 * Features Section for Euro Suit Home Page
 * 
 * Adds a customizable features section with icons
 * that appears directly in the Euro Suit page editor.
 */

/**
 * Register ACF fields for features section
 */
function eurosuit_register_features_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_eurosuit_features_section',
        'title' => 'Euro Suit Features Section',
        'fields' => array(
            array(
                'key' => 'field_eurosuit_show_features',
                'label' => 'Display Features Section',
                'name' => 'eurosuit_show_features',
                'type' => 'true_false',
                'instructions' => 'Enable or disable the features section',
                'default_value' => 1,
                'ui' => 1,
            ),
            array(
                'key' => 'field_eurosuit_features',
                'label' => 'Features',
                'name' => 'eurosuit_features',
                'type' => 'repeater',
                'instructions' => 'Add features to display',
                'min' => 1,
                'max' => 0,
                'layout' => 'block',
                'button_label' => 'Add Feature',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_eurosuit_show_features',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_eurosuit_feature_icon',
                        'label' => 'Feature Icon',
                        'name' => 'feature_icon',
                        'type' => 'image',
                        'instructions' => 'Upload or select an icon image',
                        'required' => 1,
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'library' => 'all',
                    ),
                    array(
                        'key' => 'field_eurosuit_feature_text',
                        'label' => 'Feature Text',
                        'name' => 'feature_text',
                        'type' => 'text',
                        'instructions' => 'Enter the text for this feature',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_eurosuit_icon_width',
                        'label' => 'Icon Width (px)',
                        'name' => 'icon_width',
                        'type' => 'number',
                        'instructions' => 'Enter the width for this icon in pixels',
                        'default_value' => 50,
                        'min' => 20,
                        'max' => 200,
                    ),
                    array(
                        'key' => 'field_eurosuit_icon_height',
                        'label' => 'Icon Height (px)',
                        'name' => 'icon_height',
                        'type' => 'number',
                        'instructions' => 'Enter the height for this icon in pixels',
                        'default_value' => 50,
                        'min' => 20,
                        'max' => 200,
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'euro-suit.php',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));
}
add_action('acf/init', 'eurosuit_register_features_fields');

/**
 * Function to render features section in template
 */
function eurosuit_render_features() {
    // Check if we're on the Euro Suit page
    if (!is_page_template('euro-suit.php')) {
        return;
    }
    
    // Check if features section should be displayed
    $show_features = get_field('eurosuit_show_features');
    
    if (!$show_features) {
        return;
    }
    
    // Get the features data
    $features = get_field('eurosuit_features');
    
    // Default features if ACF is not set up yet
    if (empty($features)) {
        $features = array(
            array(
                'feature_icon' => array(
                    'url' => 'https://example.com/icon1.png',
                    'alt' => 'Feature 1'
                ),
                'feature_text' => 'Custom Tailoring',
                'icon_width' => 50,
                'icon_height' => 50
            ),
            array(
                'feature_icon' => array(
                    'url' => 'https://example.com/icon2.png',
                    'alt' => 'Feature 2'
                ),
                'feature_text' => 'Premium Fabrics',
                'icon_width' => 50,
                'icon_height' => 50
            ),
            array(
                'feature_icon' => array(
                    'url' => 'https://example.com/icon3.png',
                    'alt' => 'Feature 3'
                ),
                'feature_text' => 'Expert Fitting',
                'icon_width' => 50,
                'icon_height' => 50
            ),
        );
    }
    
    // Skip if no features
    if (empty($features)) {
        return;
    }
    
    ?>
<!-- Euro Suit Features Section -->
<section class="container text-center py-5">
    <div class="row justify-content-center align-items-center">
        <?php foreach ($features as $feature) : 
            // Skip if missing essential data
            if (empty($feature['feature_icon']) || empty($feature['feature_text'])) {
                continue;
            }
            
            // Get feature data
            $feature_icon = $feature['feature_icon'];
            $feature_text = $feature['feature_text'];
            $icon_width = isset($feature['icon_width']) ? $feature['icon_width'] : 50;
            $icon_height = isset($feature['icon_height']) ? $feature['icon_height'] : 50;
            
            // Handle different image return formats
            $icon_url = '';
            if (is_array($feature_icon) && isset($feature_icon['url'])) {
                $icon_url = $feature_icon['url'];
                $icon_alt = isset($feature_icon['alt']) ? $feature_icon['alt'] : $feature_text;
            } elseif (is_numeric($feature_icon)) {
                $icon_url = wp_get_attachment_url($feature_icon);
                $icon_alt = $feature_text;
            } elseif (is_string($feature_icon)) {
                $icon_url = $feature_icon;
                $icon_alt = $feature_text;
            }
            
            // Skip if no valid image
            if (empty($icon_url)) {
                continue;
            }
            
            // Determine column width based on number of features
            $col_class = 'col-md-4 col-6';
            $feature_count = count($features);
            if ($feature_count == 2) {
                $col_class = 'col-md-6 col-6';
            } elseif ($feature_count == 1) {
                $col_class = 'col-md-12 col-12';
            } elseif ($feature_count == 4) {
                $col_class = 'col-md-3 col-6';
            } elseif ($feature_count == 5 || $feature_count == 6) {
                $col_class = 'col-md-2 col-6';
            }
        ?>
        <div class="<?php echo esc_attr($col_class); ?>">
            <img
                src="<?php echo esc_url($icon_url); ?>"
                alt="<?php echo esc_attr($icon_alt); ?>"
                style="width: <?php echo esc_attr($icon_width); ?>px; height: <?php echo esc_attr($icon_height); ?>px"
            />
            <p class="mt-2"><?php echo esc_html($feature_text); ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>
    <?php
}

/**
 * Shortcode for features section
 */
function eurosuit_features_shortcode() {
    ob_start();
    eurosuit_render_features();
    return ob_get_clean();
}
add_shortcode('eurosuit_features', 'eurosuit_features_shortcode');

// To use in the template, add this before the closing PHP tag:
// <?php eurosuit_render_features(); 



/**
 * Enqueue mega menu styles and scripts
 */
function bride_co_enqueue_mega_menu_assets() {
    // Enqueue the CSS
    wp_enqueue_style(
        'mega-menu-styles',
        get_stylesheet_directory_uri() . '/assets/css/mega-menu-styles.css',
        array(),
        '1.0.0'
    );
     
    // Enqueue the JavaScript
    wp_enqueue_script(
        'mega-menu-scripts',
        get_stylesheet_directory_uri() . '/assets/js/mega-menu.js',
        array('jquery'),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'bride_co_enqueue_mega_menu_assets');

/**
 * Add JavaScript for mega menu behavior
 */
function bride_co_mega_menu_js() {
    // This function is no longer needed as the JS is in an external file
    // You can safely remove this function
}
add_action('wp_footer', 'bride_co_mega_menu_js', 99);

/**
 * Initialize the mega menu in your theme
 */
function bride_co_init_mega_menu() {
    // Make sure the walker class exists
    if (class_exists('Complete_Mega_Menu_Walker')) {
        // Register your menu locations if not already done
        register_nav_menus(array(
            'primary-menu' => __('Primary Menu', 'bride-co-child'),
            'top-bar-menu' => __('Top Bar Menu', 'bride-co-child')
        ));
    }
}
add_action('after_setup_theme', 'bride_co_init_mega_menu');

/**
 * Helper function to display the mega menu in your theme
 */
function bride_co_mega_menu($theme_location = 'primary-menu') {
    wp_nav_menu(array(
        'theme_location'    => $theme_location,
        'depth'             => 3, // Changed from 2 to 3 to support nested submenus
        'container'         => false,
        'menu_class'        => 'navbar-nav mx-auto',
        'fallback_cb'       => 'Complete_Mega_Menu_Walker::fallback',
        'walker'            => new Complete_Mega_Menu_Walker()
    ));
}

/**
 * Evening Wear Banner Section for Home Page
 *
 * Adds a full-width evening wear banner section with editable heading
 * that appears directly in your home page editor.
 */

/**
 * Register ACF fields for evening wear banner section
 */
function bride_co_register_evening_wear_fields() {
    if ( ! function_exists('acf_add_local_field_group') ) {
        return;
    }
    
    acf_add_local_field_group( array(
        'key'    => 'group_evening_wear_banner',
        'title'  => 'Evening Wear Banner Section',
        'fields' => array(
            array(
                'key'             => 'field_show_evening_wear_banner',
                'label'           => 'Display Evening Wear Banner',
                'name'            => 'show_evening_wear_banner',
                'type'            => 'true_false',
                'instructions'    => 'Enable or disable the evening wear banner section',
                'default_value'   => 1,
                'ui'              => 1,
            ),
            array(
                'key'             => 'field_evening_wear_banner_title',
                'label'           => 'Banner Title',
                'name'            => 'evening_wear_banner_title',
                'type'            => 'text',
                'instructions'    => 'Enter the main title text (e.g., "EVENING WEAR")',
                'default_value'   => 'EVENING WEAR',
                'required'        => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_show_evening_wear_banner',
                            'operator' => '==',
                            'value'    => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key'             => 'field_evening_wear_banner_subtitle',
                'label'           => 'Banner Subtitle',
                'name'            => 'evening_wear_banner_subtitle',
                'type'            => 'text',
                'instructions'    => 'Enter the subtitle text (e.g., "Evening Gowns")',
                'default_value'   => 'Evening Gowns',
                'required'        => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_show_evening_wear_banner',
                            'operator' => '==',
                            'value'    => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key'             => 'field_evening_wear_banner_button_text',
                'label'           => 'Button Text',
                'name'            => 'evening_wear_banner_button_text',
                'type'            => 'text',
                'instructions'    => 'Enter the text for the button (e.g., "SHOP NOW")',
                'default_value'   => 'SHOP NOW',
                'required'        => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_show_evening_wear_banner',
                            'operator' => '==',
                            'value'    => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key'             => 'field_evening_wear_banner_button_link',
                'label'           => 'Button Link',
                'name'            => 'evening_wear_banner_button_link',
                'type'            => 'url',
                'instructions'    => 'Enter the URL for the button',
                'default_value'   => '#',
                'required'        => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_show_evening_wear_banner',
                            'operator' => '==',
                            'value'    => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key'             => 'field_evening_wear_banner_background_image',
                'label'           => 'Background Image',
                'name'            => 'evening_wear_banner_background_image',
                'type'            => 'image',
                'instructions'    => 'Upload or select the background image for the evening wear banner',
                'required'        => 1,
                'return_format'   => 'array',
                'preview_size'    => 'medium',
                'library'         => 'all',
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_show_evening_wear_banner',
                            'operator' => '==',
                            'value'    => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key'             => 'field_evening_wear_text_position',
                'label'           => 'Text Position',
                'name'            => 'evening_wear_text_position',
                'type'            => 'select',
                'instructions'    => 'Choose the text alignment within the banner',
                'choices'         => array(
                    'left'   => 'Left',
                    'center' => 'Center',
                    'right'  => 'Right',
                ),
                'default_value'   => 'left',
                'return_format'   => 'value',
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_show_evening_wear_banner',
                            'operator' => '==',
                            'value'    => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key'             => 'field_evening_wear_text_alignment',
                'label'           => 'Text Alignment',
                'name'            => 'evening_wear_text_alignment',
                'type'            => 'select',
                'instructions'    => 'Choose how to align the text content',
                'choices'         => array(
                    'left'   => 'Left',
                    'center' => 'Center',
                    'right'  => 'Right',
                ),
                'default_value'   => 'center',
                'return_format'   => 'value',
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_show_evening_wear_banner',
                            'operator' => '==',
                            'value'    => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key'             => 'field_evening_wear_vertical_position',
                'label'           => 'Vertical Position',
                'name'            => 'evening_wear_vertical_position',
                'type'            => 'select',
                'instructions'    => 'Choose the vertical positioning of text',
                'choices'         => array(
                    'top'    => 'Top',
                    'middle' => 'Middle',
                    'bottom' => 'Bottom',
                ),
                'default_value'   => 'middle',
                'return_format'   => 'value',
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_show_evening_wear_banner',
                            'operator' => '==',
                            'value'    => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key'             => 'field_evening_wear_text_padding',
                'label'           => 'Text Padding',
                'name'            => 'evening_wear_text_padding',
                'type'            => 'number',
                'instructions'    => 'Padding around text in pixels',
                'default_value'   => 40,
                'min'             => 0,
                'max'             => 100,
                'step'            => 5,
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_show_evening_wear_banner',
                            'operator' => '==',
                            'value'    => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'page_type',
                    'operator' => '==',
                    'value'    => 'front_page',
                ),
            ),
            array(
                array(
                    'param'    => 'page_template',
                    'operator' => '==',
                    'value'    => 'page-home.php',
                ),
            ),
            array(
                array(
                    'param'    => 'page_template',
                    'operator' => '==',
                    'value'    => 'templates/home-page.php',
                ),
            ),
        ),
        'position' => 'normal',
        'style'    => 'default',
        'active'   => true,
    ));
}
add_action('acf/init', 'bride_co_register_evening_wear_fields');

/**
 * Add CSS for evening wear banner
 */
function bride_co_evening_wear_banner_css() {
    if ( is_front_page() ) {
        ?>
        <style>
            .evening-banner {
                position: relative;
                width: 100%;
                overflow: hidden;
                margin: 3rem 0;
            }
            
            .evening-banner .banner-content {
                display: flex;
                min-height: 450px;
                position: relative;
            }
            
            .evening-banner .banner-text {
                width: 50%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                background-color: #fff;
                z-index: 2;
                position: relative;
            }
            
            .evening-banner .banner-text.text-left {
                left: 0;
                margin-right: auto;
            }
            
            .evening-banner .banner-text.text-center {
                left: 0;
                width: 50%;
            }
            
            .evening-banner .banner-text.text-right {
                right: 0;
                left: auto;
                margin-left: auto;
            }
            
            .evening-banner .banner-text.align-top {
                justify-content: flex-start;
                padding-top: 40px;
            }
            
            .evening-banner .banner-text.align-middle {
                justify-content: center;
            }
            
            .evening-banner .banner-text.align-bottom {
                justify-content: flex-end;
                padding-bottom: 40px;
            }
            
            .evening-banner .banner-image {
                position: absolute;
                right: 0;
                top: 0;
                width: 65%;
                height: 100%;
                background-size: cover;
                background-position: center;
                z-index: 1;
            }
            
            .evening-banner .banner-title {
                font-size: 3.5rem;
                font-weight: 700;
                margin-bottom: 1rem;
                letter-spacing: 2px;
            }
            
            .evening-banner .banner-subtitle {
                font-size: 1.8rem;
                margin-bottom: 2rem;
                letter-spacing: 1px;
            }
            
            .evening-banner .banner-button {
                display: inline-block;
                padding: 12px 30px;
                background-color: #000;
                color: #fff!important;
                text-decoration: none;
                font-weight: 600;
                letter-spacing: 1px;
                transition: all 0.3s ease;
            }
            
            .evening-banner .banner-button:hover {
                background-color: #333;
                color: #fff;
            }
            
            @media (max-width: 991px) {
                .evening-banner .banner-text {
                    width: 60%;
                }
                
                .evening-banner .banner-image {
                    width: 55%;
                }
            }
            
            @media (max-width: 767px) {
                .evening-banner .banner-content {
                    flex-direction: column;
                }
                
                .evening-banner .banner-text {
                    width: 100%;
                    order: 2;
                    padding: 30px 20px;
                }
                
                .evening-banner .banner-image {
                    position: relative;
                    width: 100%;
                    height: 300px;
                    order: 1;
                }
                
                .evening-banner .banner-title {
                    font-size: 2.5rem;
                }
                
                .evening-banner .banner-subtitle {
                    font-size: 1.5rem;
                }
            }
        </style>
        <?php
    }
}
add_action('wp_head', 'bride_co_evening_wear_banner_css');

/**
 * Function to render evening wear banner section in template
 */
function bride_co_render_evening_wear() {
    // Check if we're on the home page.
    if ( ! is_front_page() ) {
        return;
    }
    
    // Check if evening wear banner should be displayed.
    $show_banner = get_field('show_evening_wear_banner');
    if ( ! $show_banner ) {
        return;
    }
    
    // Get the banner data.
    $banner_title    = get_field('evening_wear_banner_title');
    $banner_subtitle = get_field('evening_wear_banner_subtitle');
    $button_text     = get_field('evening_wear_banner_button_text');
    $button_link     = get_field('evening_wear_banner_button_link');
    $background_image = get_field('evening_wear_banner_background_image');
    
    // Get positioning settings.
    $text_position     = get_field('evening_wear_text_position') ?: 'left';
    $text_alignment    = get_field('evening_wear_text_alignment') ?: 'center';
    $vertical_position = get_field('evening_wear_vertical_position') ?: 'middle';
    $text_padding      = get_field('evening_wear_text_padding') ?: 40;
    
    // Skip if any essential data is missing.
    if ( empty($banner_title) || empty($banner_subtitle) || empty($button_text) || empty($button_link) || empty($background_image) ) {
        return;
    }
    
    // Generate background style.
    $bg_style = '';
    if ( ! empty($background_image) && is_array($background_image) ) {
        $bg_style = 'background-image: url(' . esc_url($background_image['url']) . ');';
    }
    
    // Generate text position classes and styles.
    $text_position_class = 'text-' . $text_position;
    $text_align_style    = 'text-align: ' . $text_alignment . ';';
    $text_padding_style  = 'padding: ' . $text_padding . 'px;';
    
    // Define vertical alignment class.
    $vertical_align_class = '';
    switch ( $vertical_position ) {
        case 'top':
            $vertical_align_class = 'align-top';
            break;
        case 'bottom':
            $vertical_align_class = 'align-bottom';
            break;
        default:
            $vertical_align_class = 'align-middle';
            break;
    }
    ?>
<!-- Evening Wear Banner Section -->
<section class="container-fluid my-5 px-0">
  <div class="evening-banner">
    <div class="banner-content">
      <div class="banner-text <?php echo esc_attr($text_position_class . ' ' . $vertical_align_class); ?>" style="<?php echo esc_attr($text_align_style . ' ' . $text_padding_style); ?>">
        <h2 class="banner-title"><?php echo esc_html($banner_title); ?></h2>
        <p class="banner-subtitle"><?php echo esc_html($banner_subtitle); ?></p>
        <a href="<?php echo esc_url($button_link); ?>" class="banner-button"><?php echo esc_html($button_text); ?></a>
      </div>
      <div class="banner-image" style="<?php echo $bg_style; ?>">
        <!-- The image is loaded as the background -->
      </div>
    </div>
  </div>
</section>
    <?php
}

/**
 * Secondary Category Cards Section for Eurosuit Template
 * 
 * Adds a second category cards section with editable heading
 * that appears directly in your Eurosuit page editor.
 */

/**
 * Register ACF fields specifically for the secondary cards on Eurosuit page
 */
function eurosuit_secondary_register_category_cards_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    // First, let's find the ID of the Eurosuit page to target it directly
    $eurosuit_page = get_page_by_path('eurosuit');
    $eurosuit_page_id = $eurosuit_page ? $eurosuit_page->ID : 0;
    
    // If we couldn't find the page by path, try looking for it by title
    if (!$eurosuit_page_id) {
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'euro-suit-home.php'
        ));
        
        if (!empty($pages)) {
            $eurosuit_page_id = $pages[0]->ID;
        }
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_eurosuit_secondary_category_cards',
        'title' => 'Eurosuit Secondary Category Cards Section',
        'fields' => array(
            array(
                'key' => 'field_show_eurosuit_secondary_category_cards',
                'label' => 'Display Secondary Category Cards',
                'name' => 'show_eurosuit_secondary_category_cards',
                'type' => 'true_false',
                'instructions' => 'Enable or disable the secondary category cards section',
                'default_value' => 1,
                'ui' => 1,
            ),
            array(
                'key' => 'field_eurosuit_secondary_category_heading',
                'label' => 'Secondary Section Heading',
                'name' => 'eurosuit_secondary_category_heading',
                'type' => 'text',
                'instructions' => 'Enter a heading for this secondary section (leave empty for no heading)',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_secondary_category_cards',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_eurosuit_secondary_category_cards',
                'label' => 'Secondary Category Cards',
                'name' => 'eurosuit_secondary_category_cards',
                'type' => 'repeater',
                'instructions' => 'Add up to 4 secondary category cards',
                'min' => 1,
                'max' => 4,
                'layout' => 'block',
                'button_label' => 'Add Card',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_secondary_category_cards',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_eurosuit_secondary_card_title',
                        'label' => 'Card Title',
                        'name' => 'card_title',
                        'type' => 'text',
                        'instructions' => 'Enter the title to display on the card (e.g., "CASUAL SUITS")',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_eurosuit_secondary_button_text',
                        'label' => 'Button Text',
                        'name' => 'button_text',
                        'type' => 'text',
                        'instructions' => 'Enter the text for the button (e.g., "SHOP NOW")',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_eurosuit_secondary_button_link',
                        'label' => 'Button Link',
                        'name' => 'button_link',
                        'type' => 'url',
                        'instructions' => 'Enter the URL for the button',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_eurosuit_secondary_background_image',
                        'label' => 'Background Image',
                        'name' => 'background_image',
                        'type' => 'image',
                        'instructions' => 'Upload or select the background image for this card',
                        'required' => 1,
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                    ),
                ),
            ),
        ),
        'location' => array(
            // Target by specific page ID if found
            $eurosuit_page_id ? array(
                array(
                    'param' => 'post',
                    'operator' => '==',
                    'value' => $eurosuit_page_id,
                ),
            ) : array(),
            // Target by page slug
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => 'eurosuit',
                ),
            ),
            // Target by URL path
            array(
                array(
                    'param' => 'page_slug',
                    'operator' => '==',
                    'value' => 'eurosuit',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));
}
add_action('acf/init', 'eurosuit_secondary_register_category_cards_fields');

/**
 * Add CSS for secondary Eurosuit category cards
 */
function eurosuit_secondary_category_cards_css() {
    // Only add styles on Eurosuit pages
    if (!is_eurosuit_page()) {
        return;
    }
    
    // Get Eurosuit primary color from customizer
    $eurosuit_color = get_theme_mod('eurosuit_primary_color', '#192f5a');
    
    ?>
    <style>
        /* Eurosuit Secondary Category Section Heading */
        .eurosuit-secondary-category-section-heading {
            font-family: "Cinzel", sans-serif;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #333;
            text-align: left;
        }
        
        /* Eurosuit Secondary Category Card Styling */
        .eurosuit-secondary-category-card {
            position: relative;
            background-size: cover;
            background-position: top center;
            height: 400px;
            color: white;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            text-align: center;
            border-radius: 0; /* More squared look for Eurosuit */
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
            padding-bottom: 20px;
            margin-bottom: 30px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2); /* Subtle shadow for depth */
        }

        .eurosuit-secondary-category-card:hover {
            transform: scale(1.03);
        }

        /* Dark Overlay - slightly darker for Eurosuit */
        .eurosuit-secondary-category-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
        }

        /* Card Content */
        .eurosuit-secondary-category-content {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            text-align: center;
            width: 100%;
        }

        .eurosuit-secondary-category-title {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
            white-space: nowrap;
            color: white;
            font-family: "Cinzel", sans-serif;
        }

        /* Shop Button - Using Eurosuit colors */
        .eurosuit-secondary-shop-button {
            background-color: <?php echo esc_attr($eurosuit_color); ?>;
            color: white;
            font-weight: 400;
            padding: 8px 20px;
            border: none;
            text-decoration: none;
            display: inline-block;
            font-size: 0.9rem;
            white-space: nowrap;
            font-family: "Poppins", sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .eurosuit-secondary-shop-button:hover {
            background-color: <?php echo esc_attr($eurosuit_color); ?>dd;
            color: white;
        }
    </style>
    <?php
}
add_action('wp_head', 'eurosuit_secondary_category_cards_css');

/**
 * Function to render secondary Eurosuit category cards section in template
 */
function eurosuit_render_secondary_category_cards() {
    // Make sure we're on an Eurosuit page
    if (!is_eurosuit_page()) {
        return;
    }
    
    // Check if secondary Eurosuit category cards should be displayed
    $show_cards = get_field('show_eurosuit_secondary_category_cards');
    
    if (!$show_cards) {
        return;
    }
    
    // Get the secondary Eurosuit category cards and heading
    $cards = get_field('eurosuit_secondary_category_cards');
    $heading = get_field('eurosuit_secondary_category_heading');
    
    if (empty($cards)) {
        return;
    }
    
    ?>
    <section class="container get-the-look my-5">
        <?php if (!empty($heading)) : ?>
            <h2 class="eurosuit-secondary-category-section-heading"><?php echo esc_html($heading); ?></h2>
        <?php endif; ?>
        
        <div class="row g-4">
            <?php foreach ($cards as $card) : 
                // Get card data
                $card_title = $card['card_title'];
                $button_text = $card['button_text'];
                $button_link = $card['button_link'];
                $background_image = $card['background_image'];
                
                // Skip if missing essential data
                if (empty($card_title) || empty($button_text) || empty($button_link) || empty($background_image)) {
                    continue;
                }
                
                // Generate background style
                $bg_style = '';
                if (!empty($background_image) && is_array($background_image)) {
                    $bg_style = 'background-image: url(' . esc_url($background_image['url']) . ');';
                }
            ?>
                <div class="col-md-4">
                    <a href="<?php echo esc_url($button_link); ?>" class="text-decoration-none">
                        <div class="eurosuit-secondary-category-card" style="<?php echo $bg_style; ?>">
                            <div class="eurosuit-secondary-category-content">
                                <h2 class="eurosuit-secondary-category-title"><?php echo esc_html($card_title); ?></h2>
                                <a href="<?php echo esc_url($button_link); ?>" class="eurosuit-secondary-shop-button"><?php echo esc_html($button_text); ?></a>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
}

// We don't need to define a new page check function as we can use the existing is_eurosuit_page() function

// Add this function call to your Eurosuit home page template (euro-suit-home.php) after the original category cards:
// <?php eurosuit_render_secondary_category_cards();


/**
 * Secondary White Banner Section for Eurosuit Template
 * 
 * Adds a second full-width white banner section with editable heading
 * that appears directly in your Eurosuit page editor.
 */

/**
 * Register ACF fields for Eurosuit secondary white banner section
 */
function eurosuit_register_secondary_banner_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    // First, let's find the ID of the Eurosuit page to target it directly
    $eurosuit_page = get_page_by_path('eurosuit');
    $eurosuit_page_id = $eurosuit_page ? $eurosuit_page->ID : 0;
    
    // If we couldn't find the page by path, try looking for it by title
    if (!$eurosuit_page_id) {
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'euro-suit-home.php'
        ));
        
        if (!empty($pages)) {
            $eurosuit_page_id = $pages[0]->ID;
        }
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_eurosuit_secondary_banner',
        'title' => 'Eurosuit Secondary Banner Section',
        'fields' => array(
            array(
                'key' => 'field_show_eurosuit_secondary_banner',
                'label' => 'Display Secondary Banner',
                'name' => 'show_eurosuit_secondary_banner',
                'type' => 'true_false',
                'instructions' => 'Enable or disable the secondary banner section',
                'default_value' => 1,
                'ui' => 1,
            ),
            array(
                'key' => 'field_eurosuit_secondary_banner_title',
                'label' => 'Banner Title',
                'name' => 'eurosuit_secondary_banner_title',
                'type' => 'text',
                'instructions' => 'Enter the main title text (e.g., "PREMIUM")',
                'default_value' => 'PREMIUM',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_secondary_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_eurosuit_secondary_banner_subtitle',
                'label' => 'Banner Subtitle',
                'name' => 'eurosuit_secondary_banner_subtitle',
                'type' => 'text',
                'instructions' => 'Enter the subtitle text (e.g., "COLLECTION")',
                'default_value' => 'COLLECTION',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_secondary_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_eurosuit_secondary_banner_button_text',
                'label' => 'Button Text',
                'name' => 'eurosuit_secondary_banner_button_text',
                'type' => 'text',
                'instructions' => 'Enter the text for the button (e.g., "EXPLORE NOW")',
                'default_value' => 'EXPLORE NOW',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_secondary_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_eurosuit_secondary_banner_button_link',
                'label' => 'Button Link',
                'name' => 'eurosuit_secondary_banner_button_link',
                'type' => 'url',
                'instructions' => 'Enter the URL for the button',
                'default_value' => '#',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_secondary_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_eurosuit_secondary_banner_background_image',
                'label' => 'Background Image',
                'name' => 'eurosuit_secondary_banner_background_image',
                'type' => 'image',
                'instructions' => 'Upload or select the background image for the banner',
                'required' => 1,
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_secondary_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_eurosuit_secondary_text_position',
                'label' => 'Text Position',
                'name' => 'eurosuit_secondary_text_position',
                'type' => 'select',
                'instructions' => 'Choose the text alignment within the banner',
                'choices' => array(
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right',
                ),
                'default_value' => 'right',
                'return_format' => 'value',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_secondary_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_eurosuit_secondary_text_alignment',
                'label' => 'Text Alignment',
                'name' => 'eurosuit_secondary_text_alignment',
                'type' => 'select',
                'instructions' => 'Choose how to align the text content',
                'choices' => array(
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right',
                ),
                'default_value' => 'center',
                'return_format' => 'value',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_secondary_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_eurosuit_secondary_vertical_position',
                'label' => 'Vertical Position',
                'name' => 'eurosuit_secondary_vertical_position',
                'type' => 'select',
                'instructions' => 'Choose the vertical positioning of text',
                'choices' => array(
                    'top' => 'Top',
                    'middle' => 'Middle',
                    'bottom' => 'Bottom',
                ),
                'default_value' => 'middle',
                'return_format' => 'value',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_secondary_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_eurosuit_secondary_text_padding',
                'label' => 'Text Padding',
                'name' => 'eurosuit_secondary_text_padding',
                'type' => 'number',
                'instructions' => 'Padding around text in pixels',
                'default_value' => 40,
                'min' => 0,
                'max' => 100,
                'step' => 5,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_eurosuit_secondary_banner',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            // Target by specific page ID if found
            $eurosuit_page_id ? array(
                array(
                    'param' => 'post',
                    'operator' => '==',
                    'value' => $eurosuit_page_id,
                ),
            ) : array(),
            // Target by page slug
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => 'eurosuit',
                ),
            ),
            // Target by URL path
            array(
                array(
                    'param' => 'page_slug',
                    'operator' => '==',
                    'value' => 'eurosuit',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));
}
add_action('acf/init', 'eurosuit_register_secondary_banner_fields');

/**
 * Add CSS for Eurosuit secondary white banner
 */
function eurosuit_secondary_banner_css() {
    // Only add styles on Eurosuit pages
    if (!is_eurosuit_page()) {
        return;
    }
    
    // Get Eurosuit primary color from customizer
    $eurosuit_color = get_theme_mod('eurosuit_primary_color', '#192f5a');
    
    ?>
    <style>
        .eurosuit-secondary-banner {
            position: relative;
            width: 100%;
            overflow: hidden;
            margin: 3rem 0;
        }
        
        .eurosuit-secondary-banner-content {
            display: flex;
            min-height: 450px;
            position: relative;
        }
        
        .eurosuit-secondary-banner-text {
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: #fff;
            z-index: 2;
            position: relative;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); /* Subtle shadow for depth */
        }
        
        /* Text position classes */
        .eurosuit-secondary-banner-text.text-left {
            left: 0;
            margin-right: auto;
        }
        
        .eurosuit-secondary-banner-text.text-center {
            left: 0;
            width: 50%;
        }
        
        .eurosuit-secondary-banner-text.text-right {
            right: 0;
            left: auto;
            margin-left: auto;
        }
        
        /* Vertical alignment classes */
        .eurosuit-secondary-banner-text.align-top {
            justify-content: flex-start;
            padding-top: 40px;
        }
        
        .eurosuit-secondary-banner-text.align-middle {
            justify-content: center;
        }
        
        .eurosuit-secondary-banner-text.align-bottom {
            justify-content: flex-end;
            padding-bottom: 40px;
        }
        
        .eurosuit-secondary-banner-image {
            position: absolute;
    right: 0;
    top: 0;
    width: 50%;
    height: 100%;
    background-size: cover;
    background-position: center;
    z-index: 1;
    filter: contrast(105%); /* Slight contrast increase for men's formal wear */
        }
        
        .eurosuit-secondary-banner-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: 2px;
            color: #333;
            text-transform: uppercase;
        }
        
        .eurosuit-secondary-banner-subtitle {
            font-size: 1.8rem;
            margin-bottom: 2rem;
            letter-spacing: 1px;
            color: #555;
            text-transform: uppercase;
        }
        
        .eurosuit-secondary-banner-button {
            display: inline-block;
            padding: 12px 30px;
            background-color: <?php echo esc_attr($eurosuit_color); ?>;
            color: #fff !important;
            text-decoration: none;
            font-weight: 500;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            border: none;
        }
        
        .eurosuit-secondary-banner-button:hover {
            background-color: <?php echo esc_attr($eurosuit_color); ?>dd;
            color: #fff;
            text-decoration: none;
        }
        
        @media (max-width: 991px) {
            .eurosuit-secondary-banner-text {
                width: 60%;
            }
            
            .eurosuit-secondary-banner-image {
                width: 55%;
            }
        }
        
        @media (max-width: 767px) {
            .eurosuit-secondary-banner-content {
                flex-direction: column;
            }
            
            .eurosuit-secondary-banner-text {
                width: 100%;
                order: 2;
                padding: 30px 20px;
            }
            
            .eurosuit-secondary-banner-image {
                position: relative;
                width: 100%;
                height: 300px;
                order: 1;
            }
            
            .eurosuit-secondary-banner-title {
                font-size: 2.5rem;
            }
            
            .eurosuit-secondary-banner-subtitle {
                font-size: 1.5rem;
            }
        }
    </style>
    <?php
}
add_action('wp_head', 'eurosuit_secondary_banner_css');

/**
 * Function to render Eurosuit secondary white banner section in template
 */
function eurosuit_render_secondary_banner() {
    // Make sure we're on an Eurosuit page
    if (!is_eurosuit_page()) {
        return;
    }
    
    // Check if secondary white banner should be displayed
    $show_banner = get_field('show_eurosuit_secondary_banner');
    
    if (!$show_banner) {
        return;
    }
    
    // Get the banner data
    $banner_title = get_field('eurosuit_secondary_banner_title');
    $banner_subtitle = get_field('eurosuit_secondary_banner_subtitle');
    $button_text = get_field('eurosuit_secondary_banner_button_text');
    $button_link = get_field('eurosuit_secondary_banner_button_link');
    $background_image = get_field('eurosuit_secondary_banner_background_image');
    
    // Get positioning settings
    $text_position = get_field('eurosuit_secondary_text_position') ?: 'right';
    $text_alignment = get_field('eurosuit_secondary_text_alignment') ?: 'center';
    $vertical_position = get_field('eurosuit_secondary_vertical_position') ?: 'middle';
    $text_padding = get_field('eurosuit_secondary_text_padding') ?: 40;
    
    // Skip if missing essential data
    if (empty($banner_title) || empty($banner_subtitle) || empty($button_text) || empty($button_link) || empty($background_image)) {
        return;
    }
    
    // Generate background style
    $bg_style = '';
    if (!empty($background_image) && is_array($background_image)) {
        $bg_style = 'background-image: url(' . esc_url($background_image['url']) . ');';
    }
    
    // Generate text position classes and styles
    $text_position_class = 'text-' . $text_position;
    $text_align_style = 'text-align: ' . $text_alignment . ';';
    $text_padding_style = 'padding: ' . $text_padding . 'px;';
    
    // Define vertical alignment class
    $vertical_align_class = '';
    switch ($vertical_position) {
        case 'top':
            $vertical_align_class = 'align-top';
            break;
        case 'bottom':
            $vertical_align_class = 'align-bottom';
            break;
        default:
            $vertical_align_class = 'align-middle';
            break;
    }
    
    ?>
<!-- Eurosuit Secondary Banner Section -->
<section class="container-fluid my-5 px-0">
  <div class="eurosuit-secondary-banner">
    <div class="eurosuit-secondary-banner-content">
      <div class="eurosuit-secondary-banner-text <?php echo esc_attr($text_position_class . ' ' . $vertical_align_class); ?>" style="<?php echo esc_attr($text_align_style . ' ' . $text_padding_style); ?>">
        <h2 class="eurosuit-secondary-banner-title"><?php echo esc_html($banner_title); ?></h2>
        <p class="eurosuit-secondary-banner-subtitle"><?php echo esc_html($banner_subtitle); ?></p>
        <a href="<?php echo esc_url($button_link); ?>" class="eurosuit-secondary-banner-button"><?php echo esc_html($button_text); ?></a>
      </div>
      <div class="eurosuit-secondary-banner-image" style="<?php echo $bg_style; ?>">
        <!-- The image is loaded as the background -->
      </div>
    </div>
  </div>
</section>
    <?php
}

// Add this code to your Eurosuit home page template (euro-suit-home.php):
// <?php eurosuit_render_secondary_banner();
/**
 * Handle AJAX product filtering - FIXED VERSION v2
 * Fixes: No auto-filtering, consistent ordering, proper price logic
 */
function filter_products_ajax_handler() {
    check_ajax_referer('product_filter_nonce', 'nonce');

    $paged = isset($_POST['paged']) ? max(1, intval($_POST['paged'])) : 1;
    $per_page = 9;

    $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'date';
    $attributes = !empty($_POST['attributes']) ? json_decode(stripslashes($_POST['attributes']), true) : [];
    $category = isset($_POST['current_category']) ? sanitize_text_field($_POST['current_category']) : '';

    // Get the ACTUAL price range from database for this category/filter combination
    $base_args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_query' => [
            [
                'key' => '_stock_status',
                'value' => 'instock'
            ]
        ],
        'tax_query' => ['relation' => 'AND'],
    ];

    // Add category filter to base query
    if (!empty($category)) {
        $base_args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => [$category],
        ];
    }

    // Add attribute filters to base query (but NOT price filters yet)
    foreach ($attributes as $taxonomy => $value) {
        if (!empty($value)) {
            $base_args['tax_query'][] = [
                'taxonomy' => wc_attribute_taxonomy_name($taxonomy),
                'field' => 'slug',
                'terms' => explode(',', $value),
                'operator' => 'IN',
            ];
        }
    }

    // Get all products matching current filters (except price) to calculate actual min/max
    $base_query = new WP_Query($base_args);
    $products_min_price = PHP_INT_MAX;
    $products_max_price = 0;

    if ($base_query->have_posts()) {
        foreach ($base_query->posts as $product_id) {
            $product = wc_get_product($product_id);
            if ($product && $product->is_in_stock()) {
                $price = wc_get_price_to_display($product);
                if ($price > 0) {
                    $products_min_price = min($products_min_price, $price);
                    $products_max_price = max($products_max_price, $price);
                }
            }
        }
    }

    // Set fallback values if no valid prices found
    if ($products_min_price === PHP_INT_MAX) {
        $products_min_price = 0;
    }
    if ($products_max_price === 0) {
        $products_max_price = 10000;
    }

    // Round values to make them more user-friendly (same as template)
    $products_min_price = floor($products_min_price / 100) * 100;
    $products_max_price = ceil($products_max_price * 1.1 / 100) * 100;

    // ONLY apply price filter if user has actively changed the values
    $user_min_price = isset($_POST['min_price']) ? floatval($_POST['min_price']) : null;
    $user_max_price = isset($_POST['max_price']) ? floatval($_POST['max_price']) : null;
    
    $apply_price_filter = false;
    if ($user_min_price !== null && $user_max_price !== null) {
        // Only apply price filter if values are different from the calculated range
        if ($user_min_price > $products_min_price || $user_max_price < $products_max_price) {
            $apply_price_filter = true;
        }
    }

    // Build main query args
    $args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => $per_page,
        'paged' => $paged,
        'meta_query' => ['relation' => 'AND'],
        'tax_query' => ['relation' => 'AND'],
    ];

    // Apply consistent ordering with secondary sort for consistency
    switch ($orderby) {
        case 'date':
            $args['orderby'] = ['date' => 'DESC', 'ID' => 'DESC'];
            break;
        case 'price':
            $args['orderby'] = ['meta_value_num' => 'ASC', 'ID' => 'ASC'];
            $args['meta_key'] = '_price';
            break;
        case 'price-desc':
            $args['orderby'] = ['meta_value_num' => 'DESC', 'ID' => 'DESC'];
            $args['meta_key'] = '_price';
            break;
        case 'menu_order':
            $args['orderby'] = ['menu_order' => 'ASC', 'ID' => 'ASC'];
            break;
        case 'date':
        default:
            $args['orderby'] = ['date' => 'DESC', 'ID' => 'DESC'];
            break;
    }

    // Add category filter
    if (!empty($category)) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => [$category],
        ];
    }

    // Add attribute filters
    foreach ($attributes as $taxonomy => $value) {
        if (!empty($value)) {
            $args['tax_query'][] = [
                'taxonomy' => wc_attribute_taxonomy_name($taxonomy),
                'field' => 'slug',
                'terms' => explode(',', $value),
                'operator' => 'IN',
            ];
        }
    }

    // Add price filter ONLY if user has actively set different values
    if ($apply_price_filter) {
        $args['meta_query'][] = [
            'key' => '_price',
            'value' => [$user_min_price, $user_max_price],
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN'
        ];
    }

    // Add stock status filter
    $args['meta_query'][] = [
        'key' => '_stock_status',
        'value' => 'instock'
    ];

    // Execute the query
    $products_query = new WP_Query($args);
    
    // Build product HTML (same as before but using the query loop)
    ob_start();
    
    if ($products_query->have_posts()) {
        while ($products_query->have_posts()) {
            $products_query->the_post();
            global $product;
            
            // Skip if not a valid product
            if (!is_a($product, 'WC_Product')) {
                continue;
            }
            
            $product_id = $product->get_id();
            $regular_price = wc_get_price_to_display($product, ['price' => $product->get_regular_price()]);
            $is_on_sale = $product->is_on_sale();
            $discount_percentage = 0;

            // Ensure we have a valid regular price
            if (empty($regular_price)) {
                $regular_price = wc_get_price_to_display($product);
            }

            // Get sale price if product is on sale
            if ($is_on_sale) {
                $sale_price = wc_get_price_to_display($product, ['price' => $product->get_sale_price()]);
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
            
            $is_new = function_exists('is_product_new') ? is_product_new($product->get_id()) : false;    
            $is_eurosuite = function_exists('is_eurosuit_page') ? is_eurosuit_page() : false;    
            if($is_eurosuite) {
                $newlabel_style = 'new-badge-euro';
            } else {
                $newlabel_style = '';
            }
            ?>

            <div class="col-md-4">
                <a href="<?php echo esc_url(get_permalink()); ?>" class="product-card-link">
                    <div class="product-card">
                        <div class="image-container">
                            <?php if($is_new): ?>
                                <span class="label new-label <?php echo esc_attr($newlabel_style);?>">NEW</span>
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
    }
    wp_reset_postdata();
    
    $products_html = ob_get_clean();

    // Build pagination
    ob_start();
    $total_pages = $products_query->max_num_pages;
    
    if ($total_pages > 1) {
        echo '<ul class="page-numbers">';
        
        // Previous page link
        if ($paged > 1) {
            echo '<li><a class="page-numbers prev" href="#" data-page="' . ($paged - 1) . '">← Previous</a></li>';
        }
        
        // Page number links
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i === $paged) {
                echo '<li><span class="page-numbers current">' . $i . '</span></li>';
            } else {
                echo '<li><a class="page-numbers" href="#" data-page="' . $i . '">' . $i . '</a></li>';
            }
        }
        
        // Next page link
        if ($paged < $total_pages) {
            echo '<li><a class="page-numbers next" href="#" data-page="' . ($paged + 1) . '">Next →</a></li>';
        }
        
        echo '</ul>';
    }
    $pagination_html = ob_get_clean();

    // Build active filter tags - ONLY show filters that are actually applied
    ob_start();
    $has_active_filters = false;
    $active_filters = [];

    // Get all attribute taxonomies for labels
    $attribute_taxonomies = wc_get_attribute_taxonomies();
    
    // Check attribute filters
    foreach ($attributes as $taxonomy => $value) {
        if (!empty($value)) {
            $has_active_filters = true;
            
            // Get attribute label
            $attribute_label = $taxonomy;
            foreach ($attribute_taxonomies as $attribute) {
                if ($attribute->attribute_name === $taxonomy) {
                    $attribute_label = $attribute->attribute_label;
                    break;
                }
            }
            
            // Handle comma-separated values
            $filter_values = explode(',', $value);
            foreach ($filter_values as $filter_value) {
                // Get term name
                $term = get_term_by('slug', $filter_value, wc_attribute_taxonomy_name($taxonomy));
                if ($term) {
                    $active_filters[] = [
                        'attr_name' => $taxonomy,
                        'attr_label' => $attribute_label,
                        'value' => $filter_value,
                        'label' => $term->name
                    ];
                }
            }
        }
    }

    // Handle price range filter - ONLY if actually applied
    if ($apply_price_filter) {
        $has_active_filters = true;
        $active_filters[] = [
            'attr_name' => 'price_range',
            'attr_label' => 'Price',
            'value' => $user_min_price . '-' . $user_max_price,
            'label' => 'R' . $user_min_price . ' - R' . $user_max_price
        ];
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
        $clear_url = '';
        if (!empty($category)) {
            $category_term = get_term_by('slug', $category, 'product_cat');
            if ($category_term) {
                $clear_url = get_term_link($category_term);
            }
        }
        if (empty($clear_url)) {
            $clear_url = wc_get_page_permalink('shop');
        }
        
        echo '<a href="' . esc_url($clear_url) . '" class="clear-all-filters">Clear All</a>';
        echo '</div>';
    }
    $active_filters_html = ob_get_clean();

    // Send JSON response
    wp_send_json([
        'success' => true,
        'html' => $products_html,
        'active_filters' => $active_filters_html,
        'pagination' => $pagination_html,
        'count' => $products_query->found_posts,
        'total_pages' => $total_pages,
        'current_page' => $paged,
        'price_range_applied' => $apply_price_filter,
        'calculated_min' => $products_min_price,
        'calculated_max' => $products_max_price,
    ]);

    wp_die();
}

add_action('wp_ajax_filter_products', 'filter_products_ajax_handler');
add_action('wp_ajax_nopriv_filter_products', 'filter_products_ajax_handler');

//if (!function_exists('is_product_new')) {
   
function is_product_new($product_id) {
    // Example logic: consider product "new" if published within the last 30 days
    $product = wc_get_product($product_id);
    $date_created = $product->get_date_created();
   
    if (!$date_created) {
        return false;
    }
//  echo "%%%%%".$date_created;exit;
    $days_new = 30;
    $created_timestamp = $date_created->getTimestamp();
    $current_timestamp = current_time('timestamp');

    return ($current_timestamp - $created_timestamp) < ($days_new * DAY_IN_SECONDS);


}


function filter_products_last_30_days_if_is_new( $query ) {

   
    if (
        is_admin() ||
        ! $query->is_main_query() ||
        ( ! is_post_type_archive('product') && ! is_product_category() ) ||
        ! isset($_GET['is_new']) || $_GET['is_new'] !== 'yes' ) {
        return;
    }
    $date_30_days_ago = date('Y-m-d', strtotime('-30 days'));
    // Get date 30 days ago from now
    

    // Filter products published in the last 30 days
    $query->set( 'date_query', array(
        array(
            'after'     => $date_30_days_ago,
            'inclusive' => true,
        )
    ));

    // Limit to 9 products per page
    $query->set( 'posts_per_page', 9 );
}
add_action( 'pre_get_posts', 'filter_products_last_30_days_if_is_new' );
    
function get_colour_code_by_name( $colour_name ) {
    $slug = sanitize_title($colour_name); // Convert to slug format
    $term = get_term_by( 'slug', $slug, 'pa_colour' );

    if ( $term && ! is_wp_error( $term ) ) {
        $hex = get_term_meta( $term->term_id, 'product_attribute_color', true );
        return $hex ?: '#ffffff'; // fallback color
    }

    return false;
}

// 1) On the Hire category archive, stop WooCommerce from printing the loop price
function brideco_hide_hire_prices() {
    if ( is_product_category( 'hire' ) ) {
        // Remove the default price display in the loop
        remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
    }
}
add_action( 'wp', 'brideco_hide_hire_prices' );

// 2) Hide the price‑slider widget and any stray .product-pricing via CSS
function brideco_hide_hire_price_slider_css() {
    if ( is_product_category( 'hire' ) ) {
        echo '<style>
            /* Hide the price range filter widget */
            .price-range-filter { display: none !important; }
            /* Hide any remaining price markup in the loop */
            .product-pricing,
            .product-pricing * { display: none !important; }
        </style>';
    }
}
add_action( 'wp_head', 'brideco_hide_hire_price_slider_css' );

/**
 * Force every “BRIDE&CO” (and “BRIDE & CO” / “Bride & Co”) 
 * to display as “Bride&co” site‑wide.
 */

// 1) Replace any translatable strings via gettext filter
function brideco_force_brand_name( $translated, $text, $domain ) {
    // list all variants you want to catch
    $find = [ 'BRIDE&CO', 'BRIDE & CO', 'Bride & Co' ];
    if ( in_array( $text, $find, true ) ) {
        return 'Bride&co';
    }
    return $translated;
}
add_filter( 'gettext', 'brideco_force_brand_name', 20, 3 );

// 2) Fallback: catch any raw “BRIDE&CO” in the final HTML via output buffer
function brideco_start_buffer() {
    ob_start( 'brideco_replace_in_buffer' );
}
add_action( 'template_redirect', 'brideco_start_buffer', 0 );

function brideco_replace_in_buffer( $html ) {
    return str_replace(
        [ 'BRIDE&CO', 'BRIDE & CO', 'Bride & Co' ],
        'Bride&co',
        $html
    );
}



add_action( 'pre_get_posts', 'custom_woocommerce_products_per_page' );
function custom_woocommerce_products_per_page( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_shop() || is_product_category() || is_product_tag() ) ) {
        $query->set( 'posts_per_page', 9 );
    }
}



add_action( 'pre_get_posts', 'filter_valid_woocommerce_products' );
function filter_valid_woocommerce_products( $query ) {
    if (
        ! is_admin() &&
        $query->is_main_query() &&
        ( is_shop() || is_product_category() || is_product_tag() )
    ) {
        // Ensure it's a product query
        $query->set( 'post_type', 'product' );

        // Setup meta query
        $meta_query = $query->get( 'meta_query' ) ?: [];

        $meta_query[] = [
            'key'     => '_stock_status',
            'value'   => 'instock',
            'compare' => '='
        ];

        $meta_query[] = [
            'key'     => '_price',
            'value'   => 0,
            'compare' => '>',
            'type'    => 'NUMERIC'
        ];

        $query->set( 'meta_query', $meta_query );
    }
}


function jr3_theme_setup() {
     add_theme_support( 'align-wide' );
}
add_action( 'after_setup_theme', 'jr3_theme_setup' );

/**
 * SNW2: Force WooCommerce archive pagination to navigate in the SAME TAB.
 * Safe version: uses wp_add_inline_script only on product archives.
 */
if ( ! function_exists('snw2_wc_pagination_same_tab') ) {
    function snw2_wc_pagination_same_tab() {
        if ( is_admin() ) return;
        if ( function_exists('is_shop') && ( is_shop() || is_post_type_archive('product') || is_product_taxonomy() ) ) {
            $handle = 'snw2-wc-paginate-sametab';
            wp_register_script( $handle, '', array(), null, true );
            wp_enqueue_script( $handle );
            $js = <<<'JS'
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

JS;
            wp_add_inline_script( $handle, $js );
        }
    }
    add_action( 'wp_enqueue_scripts', 'snw2_wc_pagination_same_tab', 20 );
}


require_once get_stylesheet_directory() . '/inc/stock-rules.php';

/**
 * Booking System Notice Popup
 * Shows everywhere EXCEPT:
 * - The 'bridal' product category archive
 * - Any child/descendant categories of 'bridal'
 * - Any single product belonging to 'bridal' or its child categories
 */
function brideco_booking_notice_popup() {

    $show_popup = true;

    // 1) Exclude the bridal category and all its descendants
    if ( function_exists('is_product_category') && is_product_category() ) {

        $current_term = get_queried_object();

        if ( $current_term && ! empty( $current_term->term_id ) ) {

            $bridal_term = get_term_by( 'slug', 'bridal', 'product_cat' );

            if ( $bridal_term && ! is_wp_error( $bridal_term ) ) {

                // Get all descendant IDs of bridal
                $child_ids   = get_term_children( $bridal_term->term_id, 'product_cat' );
                $all_ids     = array_merge( array( $bridal_term->term_id ), $child_ids );

                // Also walk up the ancestor chain of the current term
                $ancestor_ids        = get_ancestors( (int) $current_term->term_id, 'product_cat' );
                $terms_to_check      = array_merge( array( (int) $current_term->term_id ), array_map( 'intval', $ancestor_ids ) );

                if ( ! empty( array_intersect( $terms_to_check, array_map( 'intval', $all_ids ) ) ) ) {
                    $show_popup = false;
                }
            }
        }
    }

    // 2) Exclude single products that belong to bridal or its child categories
    if ( $show_popup && is_singular( 'product' ) ) {

        $bridal_term = get_term_by( 'slug', 'bridal', 'product_cat' );

        if ( $bridal_term && ! is_wp_error( $bridal_term ) ) {

            $child_ids     = get_term_children( $bridal_term->term_id, 'product_cat' );
            $all_ids       = array_map( 'intval', array_merge( array( $bridal_term->term_id ), $child_ids ) );
            $product_terms = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'ids' ) );

            if ( ! empty( array_intersect( $all_ids, array_map( 'intval', $product_terms ) ) ) ) {
                $show_popup = false;
            }
        }
    }

    if ( ! $show_popup ) return;

    ?>
    <!-- Booking Notice Popup -->
    <div id="booking-notice-popup" class="bn-modal-overlay">
        <div class="bn-popup-container">
            <button class="bn-close-btn" type="button">&times;</button>

            <div class="bn-content-wrapper">
                <div class="bn-main-content">


                    <h1 class="bn-title">BOOKING NOTICE</h1>

                    <p class="bn-body">We are currently experiencing technical difficulties with our booking system. Please find a store and make a booking telephonically or through WhatsApp.</p>

                    <div class="bn-brand">
                        <img
                            src="https://brideandco.co.za/wp-content/uploads/2022/05/cropped-cropped-cropped-cropped-cropped-cropped-BrideCo-Logo.png"
                            alt="Bride&Co Logo"
                            class="bn-brand-logo"
                        />
                    </div>

                    <a href="/find-a-store" class="bn-cta-btn">Find a Store</a>

                    <button class="bn-dismiss-btn" type="button">Continue browsing</button>

                </div>
            </div>
        </div>
    </div>

    <style>
        .bn-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.75);
            z-index: 999999;
            display: none;
            justify-content: center;
            align-items: center;
        }

        .bn-popup-container {
            position: relative;
            max-width: 560px;
            width: 90%;
            background-color: #fff;
            background-image: url("https://brideandco.co.za/wp-content/uploads/2022/10/Untitled-design-3.png");
            background-size: cover;
            background-position: center center;
            color: white;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        /* Dark overlay on top of bg image so text is readable */
        .bn-popup-container::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            z-index: 1;
        }

        .bn-close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            background: none;
            border: none;
            color: white;
            font-size: 35px;
            cursor: pointer;
            z-index: 10;
            padding: 0;
            line-height: 1;
            opacity: 0.9;
            transition: opacity 0.3s ease;
        }

        .bn-close-btn:hover {
            opacity: 1;
        }

        .bn-content-wrapper {
            position: relative;
            z-index: 2;
            padding: 60px 40px 50px;
            min-height: 420px;
        }

        .bn-main-content {
            text-align: center;
        }

        .bn-icon {
            font-size: 38px;
            margin-bottom: 18px;
        }

        .bn-title {
            font-family: Cinzel, serif;
            font-size: 38px;
            font-weight: 400;
            letter-spacing: 6px;
            margin: 0 0 20px 0;
            text-transform: uppercase;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
            color: #fff;
        }

        .bn-body {
            font-family: "Poppins", Arial, sans-serif;
            font-size: 15px;
            font-weight: 300;
            line-height: 1.75;
            margin: 0 0 28px 0 !important;
            color: rgba(255, 255, 255, 0.92);
            letter-spacing: 0.3px;
        }

        .bn-brand {
            margin: 0 0 28px 0;
            text-align: center;
        }

        .bn-brand-logo {
            height: 30px;
            width: auto;
            display: inline-block;
            filter: brightness(0) invert(1);
        }

        .bn-cta-btn {
            display: inline-block;
            background: #ddcdbf;
            color: #fff !important;
            padding: 14px 38px;
            text-decoration: none;
            font-family: "Poppins", Arial, sans-serif;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border-radius: 4px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 16px;
        }

        .bn-cta-btn:hover {
            background: #c8b5a3;
            color: #fff !important;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .bn-dismiss-btn {
            display: block;
            margin: 0 auto;
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.65);
            font-family: "Poppins", Arial, sans-serif;
            font-size: 13px;
            cursor: pointer;
            text-decoration: underline;
            transition: color 0.3s ease;
            padding: 0;
        }

        .bn-dismiss-btn:hover {
            color: rgba(255, 255, 255, 0.9);
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            .bn-content-wrapper {
                padding: 50px 28px 40px;
                min-height: 380px;
            }

            .bn-title {
                font-size: 28px;
                letter-spacing: 4px;
            }

            .bn-body {
                font-size: 14px;
            }
        }

        @media screen and (max-width: 480px) {
            .bn-content-wrapper {
                padding: 40px 22px 35px;
                min-height: 340px;
            }

            .bn-title {
                font-size: 22px;
                letter-spacing: 3px;
            }

            .bn-body {
                font-size: 13px;
            }

            .bn-cta-btn {
                font-size: 12px;
                padding: 12px 28px;
            }
        }
    </style>

    <script>
        (function () {

            function initBookingNoticePopup() {
                var popup   = document.getElementById("booking-notice-popup");
                var closeBtn     = document.querySelector(".bn-close-btn");
                var dismissBtn   = document.querySelector(".bn-dismiss-btn");
                if (!popup) return;

                setTimeout(function () {
                    popup.style.display = "flex";
                }, 500);

                function closePopup() {
                    popup.style.display = "none";
                }

                if (closeBtn)    closeBtn.addEventListener("click", closePopup);
                if (dismissBtn)  dismissBtn.addEventListener("click", closePopup);

                popup.addEventListener("click", function (e) {
                    if (e.target === popup) closePopup();
                });

                document.addEventListener("keydown", function (e) {
                    if (e.key === "Escape" || e.keyCode === 27) closePopup();
                });
            }

            if (document.readyState === "loading") {
                document.addEventListener("DOMContentLoaded", initBookingNoticePopup);
            } else {
                initBookingNoticePopup();
            }

        })();
    </script>
    <?php
}

add_action('wp_footer', 'brideco_booking_notice_popup');

/**
 * Display BrideVibes popup on bridal category pages, children, and products
 */
function bridevibes_popup() {

    $show_popup = false;

    if (function_exists('is_product_category') && is_product_category()) {
        $current_term = get_queried_object();
        if ($current_term && !empty($current_term->term_id)) {
            $bridal_term = get_term_by('slug', 'bridal', 'product_cat');
            if ($bridal_term && !is_wp_error($bridal_term)) {
                $bridal_id = (int) $bridal_term->term_id;
                $current_id = (int) $current_term->term_id;
                if ($current_id === $bridal_id) {
                    $show_popup = true;
                } else {
                    $ancestor_ids = array_map('intval', get_ancestors($current_id, 'product_cat'));
                    if (in_array($bridal_id, $ancestor_ids)) {
                        $show_popup = true;
                    }
                }
            }
        }
    }

    if (!$show_popup && function_exists('is_product') && is_product()) {
        $bridal_term = get_term_by('slug', 'bridal', 'product_cat');
        if ($bridal_term && !is_wp_error($bridal_term)) {
            $bridal_id = (int) $bridal_term->term_id;
            $product_terms = get_the_terms(get_the_ID(), 'product_cat');
            if ($product_terms && !is_wp_error($product_terms)) {
                foreach ($product_terms as $term) {
                    $term_id = (int) $term->term_id;
                    if ($term_id === $bridal_id) { $show_popup = true; break; }
                    $ancestors = array_map('intval', get_ancestors($term_id, 'product_cat'));
                    if (in_array($bridal_id, $ancestors)) { $show_popup = true; break; }
                }
            }
        }
    }

    if (!$show_popup) return;
    ?>

    <div id="bridevibes-popup" class="bv-modal-overlay">
      <div class="bv-popup-container">
        <button class="bv-close-btn" type="button">&times;</button>

        <div class="bv-right-panel">

          <div class="bv-brand-header">
            <img
              src="https://brideandco.co.za/wp-content/uploads/2026/03/BV-BNC-logo-combo-PNG.png"
              alt="BrideVibes by Bride&Co"
              class="bv-logo-combo"
            />
          </div>

          <div class="bv-model-inset">
            <img
              src="https://brideandco.co.za/wp-content/uploads/2026/03/Untitled-design-21.png"
              alt="Bridal gown model"
              class="bv-model-img"
            />
          </div>

          <p class="bv-join-text">Join Bridevibes &amp; get</p>
          <p class="bv-discount">15% off</p>
          <p class="bv-sub-text">on your seasonal<br/>bridal gown</p>

          <a href="https://brideandco.co.za/bridevibes/" class="bv-cta-btn">JOIN NOW</a>

          <p class="bv-tc">T's &amp; C's Apply<br/><span class="bv-tc-note">*Markdowns Excluded</span></p>

        </div>
      </div>
    </div>

    <style>
      .bv-modal-overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.75);
        z-index: 999999;
        display: none;
        justify-content: center;
        align-items: center;
      }

      .bv-popup-container {
        position: relative;
        width: 90%;
        max-width: 680px;
        min-height: 440px;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 25px 70px rgba(0,0,0,0.5);
        display: flex;
        background-image: url("https://brideandco.co.za/wp-content/uploads/2026/03/Untitled-design-21.png");
        background-size: cover;
        background-position: left center;
      }

      .bv-popup-container::before {
        content: '';
        flex: 1.1;
      }

      .bv-right-panel {
        flex: 1;
        background-color: #f5a7b8;
        padding: 30px 28px 20px 28px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: relative;
        min-height: 440px;
      }

      .bv-close-btn {
        position: absolute;
        top: 12px;
        right: 14px;
        background: none;
        border: none;
        color: #222;
        font-size: 28px;
        cursor: pointer;
        z-index: 10;
        padding: 0;
        line-height: 1;
        opacity: 0.7;
        transition: opacity 0.2s;
      }
      .bv-close-btn:hover { opacity: 1; }

      .bv-brand-header {
        display: flex;
        justify-content: center;
        margin-bottom: 14px;
      }

      .bv-logo-combo {
        height: 70px;
        width: auto;
        display: block;
      }

      .bv-model-inset {
        width: 100%;
        max-width: 220px;
        margin: 0 auto 14px auto;
        border-radius: 2px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
      }

      .bv-model-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        object-position: center top;
        display: block;
      }

      .bv-join-text {
        font-family: Georgia, serif;
        font-size: 15px;
        color: #222;
        margin: 0 0 2px 0 !important;
      }

      .bv-discount {
        font-family: Georgia, "Times New Roman", serif;
        font-size: 44px;
        font-weight: 700;
        color: #111;
        margin: 0 0 4px 0 !important;
        line-height: 1;
      }

      .bv-sub-text {
        font-family: Georgia, serif;
        font-size: 15px;
        color: #222;
        margin: 0 0 20px 0 !important;
        line-height: 1.5;
      }

      .bv-cta-btn {
        display: inline-block;
        background: #111;
        color: #fff !important;
        padding: 13px 40px;
        text-decoration: none !important;
        font-family: "Trebuchet MS", Arial, sans-serif;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 2.5px;
        border-radius: 3px;
        transition: background 0.25s, transform 0.2s;
        margin-bottom: 14px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.25);
      }
      .bv-cta-btn:hover {
        background: #3a0010;
        transform: translateY(-2px);
        text-decoration: none !important;
      }

      .bv-tc {
        font-family: Arial, sans-serif;
        font-size: 10px;
        color: #444;
        margin: 0 !important;
        font-style: italic;
        line-height: 1.6;
      }
      .bv-tc-note {
        font-size: 9px;
        color: #555;
      }

      @media screen and (max-width: 560px) {
        .bv-popup-container::before { display: none; }
        .bv-right-panel {
          flex: 1;
          padding: 40px 22px 24px 22px;
        }
      }
    </style>

    <script>
      (function () {
        function initBVPopup() {
          var popup = document.getElementById("bridevibes-popup");
          var closeBtn = document.querySelector(".bv-close-btn");
          if (!popup) return;

          setTimeout(function () { popup.style.display = "flex"; }, 600);

          function closePopup() { popup.style.display = "none"; }

          if (closeBtn) closeBtn.addEventListener("click", closePopup);
          popup.addEventListener("click", function (e) { if (e.target === popup) closePopup(); });
          document.addEventListener("keydown", function (e) { if (e.key === "Escape") closePopup(); });
        }

        if (document.readyState === "loading") {
          document.addEventListener("DOMContentLoaded", initBVPopup);
        } else {
          initBVPopup();
        }
      })();
    </script>
    <?php
}

add_action('wp_footer', 'bridevibes_popup');