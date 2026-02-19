<?php
/**
 * The header for our theme
 *
 * @package Bride_Co_Child
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
    <?php wp_head(); ?>
	
    <script>
(function($) {
  $(function() {
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    const hiddenInput = document.getElementById('search-hidden-input');
    const searchForm = document.getElementById('searchform');

    const searchCache = {};
    let searchTimer;
    let lastQuery = '';
    let isRequestPending = false;

    const productTemplate = item => `
      <a href="${item.url}" class="search-item">
        ${item.image ? `<img src="${item.image}" alt="${item.title}" loading="lazy">` : ''}
        <div class="search-item-details">
          <p class="search-item-title">${item.title}</p>
          <p class="search-item-type">${item.price}</p>
        </div>
      </a>
    `;

    const postTemplate = item => `
      <a href="${item.url}" class="search-item">
        ${item.image ? `<img src="${item.image}" alt="${item.title}" loading="lazy">` : ''}
        <div class="search-item-details">
          <p class="search-item-title">${item.title}</p>
          <p class="search-item-type">Blog Post</p>
        </div>
      </a>
    `;

    function performSearch(query) {
      if (query.length < 2) {
        searchResults.classList.add('d-none');
        searchResults.innerHTML = '';
        return;
      }

      if (searchCache[query]) {
        searchResults.innerHTML = searchCache[query];
        searchResults.classList.remove('d-none');
        return;
      }

      if (isRequestPending) return;
      isRequestPending = true;

      searchResults.innerHTML = '<div class="p-3 text-center"><div class="spinner-border spinner-border-sm" role="status"></div><span class="ms-2">Searching...</span></div>';
      searchResults.classList.remove('d-none');

      $.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'post',
        data: {
          action: 'ajax_search',
          search: query,
          nonce: '<?php echo wp_create_nonce('ajax_search_nonce'); ?>'
        },
        success: function(response) {
          isRequestPending = false;

          if (response.success) {
            let html = '';

            if (response.data.products && response.data.products.length > 0) {
              html += '<h4>Products</h4>';
              html += response.data.products.map(productTemplate).join('');
            }

            if (response.data.posts && response.data.posts.length > 0) {
              html += '<h4>Blog Posts</h4>';
              html += response.data.posts.map(postTemplate).join('');
            }

            //html += `<div class="view-all"><a href="<?php echo esc_url(home_url('/')); ?>?s=${encodeURIComponent(query)}">View all results</a></div>`;
       
            if (!html) {
              html = '<div class="p-3 text-center">No results found</div>';
            }

            searchCache[query] = html;

            if (query === lastQuery) {
              searchResults.innerHTML = html;
              searchResults.classList.remove('d-none');
            }
          } else {
            searchResults.innerHTML = '<div class="p-3 text-center">Error loading results</div>';
          }
        },
        error: function() {
          isRequestPending = false;
          searchResults.innerHTML = '<div class="p-3 text-center">Error loading results</div>';
        }
      });
    }

    searchInput.addEventListener('input', function() {
      const query = this.value.trim();
      lastQuery = query;
      hiddenInput.value = query;

      clearTimeout(searchTimer);
      searchTimer = setTimeout(() => performSearch(query), 200);
    });

    /*
    searchInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        hiddenInput.value = searchInput.value.trim();
        searchForm.submit();
      }
    });
    */

    document.addEventListener('click', function(e) {
      if (!e.target.closest('.search-box')) {
        searchResults.classList.add('d-none');
      }
    });

    searchResults.addEventListener('click', function(e) {
      e.stopPropagation();
    });
  });
})(jQuery);
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cookie&display=swap" rel="stylesheet">
<style>
  .bride-slider-btn:hover
  {
  background : #dac9c0!important;
  }
</style>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
  <div class="sticky-nav">
    <!-- Top Bar with Store Locations and Free Appointment Button -->
    <div class="bg-light text-dark py-1 px-3">
      <div class="d-flex justify-content-between align-items-center">
         <?php
        if (is_eurosuit_page()) {
        ?>
        <div class="social-icons">
          <?php
          // Social media links from customizer settings
          $social_platforms = array(
              'facebook'  => 'https://www.facebook.com/Eurosuit/',
              'instagram' => 'https://www.instagram.com/eurosuit/?hl=en',
              'pinterest' => '#',
              'tiktok'    => '#'
          );
          
          foreach ($social_platforms as $platform => $default_url) {
              $url = get_theme_mod('social_' . $platform, $default_url);
              echo '<a href="' . esc_url($url) . '" class="text-dark me-2" target="_blank"><i class="bi bi-' . esc_attr($platform) . '"></i></a>';
          }
          ?>
        </div>
        <?php
        }
        ?>
         <?php
        if (!is_eurosuit_page()) {
        ?>
        <div class="social-icons">
          <?php
          // Social media links from customizer settings
          $social_platforms = array(
              'facebook'  => 'https://www.facebook.com/BrideandcoSA?_rdc=1&_rdr#',
              'instagram' => 'https://www.instagram.com/brideandcosa/',
              'pinterest' => 'https://za.pinterest.com/brideandcoza/',
              'tiktok'    => 'https://www.tiktok.com/@bride.and.co'
          );
          
          foreach ($social_platforms as $platform => $default_url) {
              $url = get_theme_mod('social_' . $platform, $default_url);
              echo '<a href="' . esc_url($url) . '" class="text-dark me-2" target="_blank"><i class="bi bi-' . esc_attr($platform) . '"></i></a>';
          }
          ?>
        </div>
        <?php
        }
        ?>
        <div class="nav-links">
          <?php
          // Display top bar menu if it exists
          if (has_nav_menu('top-bar-menu')) {
              wp_nav_menu(array(
                  'theme_location' => 'top-bar-menu',
                  'container'      => false,
                  'menu_class'     => 'top-bar-nav',
                  'fallback_cb'    => false,
                  'depth'          => 1,
                  'items_wrap'     => '%3$s', // Just output the menu items with no container
                  'walker'         => new WP_Bootstrap_Navwalker()
              ));
          } else {
              // Fallback to hardcoded links if no menu assigned
              ?>
              <a href="<?php echo esc_url(home_url('/eurosuit')); ?>" class="visit-link">VISIT EUROSUIT</a>
              <?php
              if (is_eurosuit_page()) {
                ?>
              <a href="<?php echo esc_url(home_url('/find-a-store-euro-suit')); ?>" class="visit-link">FIND A STORE</a>
              <?php } else
              {
              ?>
              <a href="<?php echo esc_url(home_url('/find-a-store')); ?>" class="visit-link">FIND A STORE</a>
              <?php
              }
              ?>
              <a href="<?php echo esc_url(home_url('/book-your-free-fitting')); ?>" class="btn">BOOK A FREE FITTING</a>
              <?php
          }
          ?>
        </div>
      </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
      <div class="container-fluid navbar-stuff">
        <!-- Logo -->
     <a class="navbar-brand fw-bold" href="<?php echo esc_url(home_url('/')); ?>">
  <?php
  if (is_eurosuit_page()) {
      // Eurosuit logo
      $eurosuit_logo_id = get_theme_mod('eurosuit_logo');
      if ($eurosuit_logo_id) {
          $eurosuit_logo_url = wp_get_attachment_image_url($eurosuit_logo_id, 'full');
          echo '<img src="' . esc_url($eurosuit_logo_url) . '" alt="Eurosuit" class="custom-logo"/>';
      } else {
          // Fallback if no Eurosuit logo set
          echo '<img src="https://brideandco.co.za/wp-content/uploads/2026/01/20-year-grey-Euro.png" alt="Eurosuit" class="custom-logo"/>';
      }
  } else {
      // Bride and Co logo
      if (function_exists('the_custom_logo') && has_custom_logo()) {
          the_custom_logo();
      } else {
          echo '<img src="https://brideandco.co.za/wp-content/uploads/2022/05/cropped-BrideCo-Logo.png" alt="' . get_bloginfo('name') . '" class="custom-logo"/>';
      }
  }
  ?>
</a>

        <!-- Toggler for Mobile -->
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Items -->
        <div class="collapse navbar-collapse" id="navbarNav">
          <?php
          if (has_nav_menu('primary-menu')) {
              wp_nav_menu(array(
                  'theme_location'    => 'primary-menu',
                  'depth'             => 3,
                  'container'         => false,
                  'menu_class'        => 'navbar-nav mx-auto',
                  'fallback_cb'       => 'Complete_Mega_Menu_Walker::fallback',
                  'walker'            => new Complete_Mega_Menu_Walker()
              ));
          } else {
              // Fallback to mega menu example if no menu assigned
              ?>
             
              <?php
          }
          ?>

          <!-- Search Bar with Dropdown -->
          <div class="search-box position-relative">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="form-control" id="search-input" placeholder="What are you looking for?" autocomplete="off">
            <div id="search-results" class="search-results-dropdown position-absolute d-none" style="width: 100%; z-index: 1000; background: white; border: 1px solid #ddd; border-radius: 0 0 4px 4px; max-height: 400px; overflow-y: auto;">
              <!-- Search results will be populated here -->
            </div>
            <form id="searchform" method="get" action="<?php echo esc_url(home_url('/')); ?>" style="display:none;">
              <input type="hidden" name="s" id="search-hidden-input">
            </form>
          </div>

          <!-- User and Cart Icons -->
          <div class="d-flex align-items-center">
            <a href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account')); ?>" class="text-dark me-3"><i class="bi bi-person"></i></a>
            <a href="<?php echo esc_url(function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart')); ?>" class="text-dark position-relative">
              <i class="bi bi-cart"></i>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">
                <?php 
                if (function_exists('WC') && isset(WC()->cart)) {
                  echo WC()->cart->get_cart_contents_count();
                } else {
                  echo '0';
                }
                ?>
              </span>
            </a>
          </div>
        </div>
      </div>
    </nav>

    <!-- Updated Scrolling Promo Bar -->
    <div class="promo-scrolling-text py-2">
 <?php
        if (is_eurosuit_page()) {
        ?>
       <style>
  .custom-btn {
    display:none;
  }
  </style>
      <div class="promo-text">
        <?php echo esc_html(get_theme_mod('promo_text_euro', 'Suits and Tuxedos from R5999 | Get R1200 off your Complete Look | Buy. Hire. Accessorise. All Online | Secure Checkout. Fast Delivery. No Fuss.')); ?>
      </div>
      <?php
        }
        if (!is_eurosuit_page()) {
        ?>
       <div class="promo-text">
        <?php echo esc_html(get_theme_mod('promo_text', 'Online purchasing now available! Shop your dream dresses| Find "The One" | Up to 30% Off Wedding Dresses | International Designer Wedding Dress Sale | Book your free fitting & shop in-store')); ?>
      </div>   
      <style>
  .custom-btn {
  background-color: #ddcdbf!important;
  border: none!important;
  color: #fff!important;
  padding: 5px 5px;
  border-radius: 4px;
  transition: background-color 0.3s ease;
  bottom: 5%;
}

.custom-btn:hover {
  background-color: #b19984!important;
  color: #fff;
}
  </style>    
        <?php
        }
        ?>
    </div>
  </div>

  <div id="content" class="site-content">