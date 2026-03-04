<?php
/**
 * The template for displaying the footer
 */

// Don't load Font Awesome here - it should be enqueued in functions.php
?>
<footer class="site-footer">
  <!-- Top Section: Brand Logo -->
  <div class="footer-top">
    <a href="<?php echo esc_url(is_eurosuit_page() ? home_url('/eurosuit/') : home_url('/')); ?>">
      <?php 
      $custom_logo_id = get_theme_mod('custom_logo');
      $footer_logo = get_theme_mod('footer_logo', '');
      
      if (is_eurosuit_page()) {
          // Eurosuit logo
          echo '<img class="footer-logo" src="' . esc_url('https://brideandco.co.za/wp-content/uploads/2022/06/Eurosuit-Logo.png') . '" alt="Eurosuit"/>';
      } elseif (!empty($footer_logo)) {
          // Custom footer logo from theme settings
          echo '<img class="footer-logo" src="' . esc_url($footer_logo) . '" alt="' . esc_attr(get_bloginfo('name')) . '"/>';
      } elseif (has_custom_logo()) {
          // Default custom logo
          $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
          echo '<img class="footer-logo" src="' . esc_url($logo[0]) . '" alt="' . esc_attr(get_bloginfo('name')) . '"/>';
      } else {
          // Fallback Bride&co logo
          echo '<img class="footer-logo" src="' . esc_url(get_stylesheet_directory_uri()) . '/assets/imgs/cropped-BrideCo-Logo.png" alt="' . esc_attr(get_bloginfo('name')) . '"/>';
      }
      ?>
    </a>
  </div>

  <!-- Middle Section: Columns with improved spacing -->
  <div class="footer-columns">
    <!-- Column 1: Categories (Eurosuit-specific on Eurosuit pages) -->
    <div class="footer-column">
      <h3><?php echo esc_html(get_theme_mod('footer_column1_title', 'Categories')); ?></h3>
      <?php
      if ( is_eurosuit_page() && has_nav_menu('eurosuit-footer-menu') ) {
          wp_nav_menu(array(
              'theme_location' => 'eurosuit-footer-menu',
              'menu_class'     => '',
              'container'      => false,
              'depth'          => 1,
              'fallback_cb'    => false,
          ));
      } elseif (has_nav_menu('footer-menu-1')) {
          wp_nav_menu(array(
              'theme_location' => 'footer-menu-1',
              'menu_class'     => '',
              'container'      => false,
              'depth'          => 1,
              'fallback_cb'    => false,
          ));
      } else {
      ?>
      <ul>
        <?php if ( is_eurosuit_page() ) : ?>
          <li><a href="<?php echo esc_url(home_url('/eurosuit/suits/')); ?>">All Suits</a></li>
          <li><a href="<?php echo esc_url(home_url('/eurosuit/accessories/')); ?>">Accessories</a></li>
          <li><a href="<?php echo esc_url(home_url('/eurosuit/collections/')); ?>">Collections</a></li>
        <?php else : ?>
          <li><a href="<?php echo esc_url(home_url('/wedding-dresses/')); ?>">All Wedding Dresses</a></li>
          <li><a href="<?php echo esc_url(home_url('/bridesmaids-dresses/')); ?>">Bridesmaids Dresses</a></li>
          <li><a href="<?php echo esc_url(home_url('/special-occasion/')); ?>">Special Occasion Dresses</a></li>
          <li><a href="<?php echo esc_url(home_url('/matric-dance-dresses/')); ?>">Matric Dance Dresses</a></li>
          <li><a href="<?php echo esc_url(home_url('/accessories/')); ?>">All Accessories</a></li>
        <?php endif; ?>
      </ul>
      <?php } ?>
    </div>

    <!-- Column 2: About (Always Bride&co) -->
    <div class="footer-column">
      <h3><?php echo esc_html(get_theme_mod('footer_column2_title', 'About')); ?></h3>
      <?php
      if (has_nav_menu('footer-menu-2')) {
          wp_nav_menu(array(
              'theme_location' => 'footer-menu-2',
              'menu_class'     => '',
              'container'      => false,
              'depth'          => 1,
              'fallback_cb'    => false,
          ));
      } else {
      ?>
      <ul>
        <li><a href="<?php echo esc_url(home_url('/faq-page/')); ?>">FAQs</a></li>
        <li><a href="<?php echo esc_url(home_url('/blog/')); ?>">Our Blog</a></li>
        <li><a href="<?php echo esc_url(home_url('/fitting-guide/')); ?>">Fitting Guide</a></li>
        <li><a href="<?php echo esc_url(home_url('/contact-us/')); ?>">Contact</a></li>
        <li><a href="<?php echo esc_url(home_url('/find-a-store/')); ?>">Find a Store</a></li>
        <li><a href="<?php echo esc_url(home_url('/enquiries/')); ?>">Enquiries</a></li>
      </ul>
      <?php } ?>
    </div>

    <!-- Column 3: Help (Always Bride&co) -->
    <div class="footer-column">
      <h3><?php echo esc_html(get_theme_mod('footer_column3_title', 'Legal')); ?></h3>
      <?php
      if (has_nav_menu('footer-menu-3')) {
          wp_nav_menu(array(
              'theme_location' => 'footer-menu-3',
              'menu_class'     => '',
              'container'      => false,
              'depth'          => 1,
              'fallback_cb'    => false,
          ));
      } else {
      ?>
      <ul>
        <li><a href="<?php echo esc_url(home_url('/terms-and-conditions/')); ?>">Terms & Conditions</a></li>
        <li><a href="<?php echo esc_url(home_url('/paia-manual/')); ?>">PAIA Manual</a></li>
        <li><a href="<?php echo esc_url(home_url('/form-2/')); ?>">Form 2</a></li>
        <li><a href="<?php echo esc_url(home_url('/form-3/')); ?>">Form 3</a></li>
        <li><a href="<?php echo esc_url(home_url('/privacy-notice/')); ?>">Privacy Notice</a></li>
      </ul>
      <?php } ?>
    </div>

    <!-- Column 4: Bride Vibes Button (Hidden on Eurosuit Pages) -->
    <?php if (!is_eurosuit_page()) : ?>
<div class="footer-column">
  <h3>BrideVibes</h3>
  <p>Join The Digital I Do Crew</p>
  <a href="<?php echo esc_url(home_url('/bridevibes-weddings/')); ?>" class="bride-vibes-button">Sign-up To BrideVibes</a>
  <a href="/wp-content/uploads/2025/04/Brideco-Personal-Wedding-Planner.pdf" 
   class="bride-vibes-button" 
   target="_blank" 
   rel="noopener noreferrer">
   Download Our Wedding Planner
</a>
</div>
<?php endif; ?>
</div>

  <!-- Bottom Section: Social Icons and Payment Icons -->
  <div class="footer-bottom">
    <!-- Social Icons -->
    <!--<div class="social-icons">
      <?php 
      $social_links = is_eurosuit_page() ? array(
          'facebook' => 'https://www.facebook.com/Eurosuit/',
          'instagram' => 'https://www.instagram.com/eurosuit/?hl=en',
          'pinterest' => '#',
          'youtube' => '#',
          'tiktok' => '#'
      ) : array(
          'facebook' => get_theme_mod('social_facebook', 'https://www.facebook.com/BrideandcoSA?_rdc=1&_rdr#'),
          'instagram' => get_theme_mod('social_instagram', 'https://www.instagram.com/brideandcosa/'),
          'pinterest' => get_theme_mod('social_pinterest', 'https://za.pinterest.com/brideandcoza/'),
          'youtube' => get_theme_mod('social_youtube', 'https://www.youtube.com/user/BrideandcoSA'),
          'tiktok' => get_theme_mod('social_tiktok', 'https://www.tiktok.com/@bride.and.co')
      );
      
      foreach ($social_links as $platform => $url) {
          if (!empty($url) && $url !== '#') {
              echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer">';
              echo '<i class="fab fa-' . esc_attr($platform) . '"></i>';
              echo '</a>';
          }
      }
      ?>
    </div>-->

    <!-- Payment Icons -->
    <div class="payment-icons">
      <?php
      $payment_methods = array(
          'visa' => get_theme_mod('payment_visa', get_stylesheet_directory_uri() . '/assets/imgs/Visa.svg'),
          'mastercard' => get_theme_mod('payment_mastercard', get_stylesheet_directory_uri() . '/assets/imgs/Mastercard.svg'),
          'maestro' => get_theme_mod('payment_maestro', get_stylesheet_directory_uri() . '/assets/imgs/Maestro.svg'),
      );
      
      foreach ($payment_methods as $method => $img_src) {
          if (!empty($img_src)) {
              echo '<img src="' . esc_url($img_src) . '" alt="' . esc_attr(ucfirst($method)) . '">';
          }
      }
      ?>
    </div>
  </div>
</footer>

<!-- Copyright Section -->
<div class="copyright">
  <p><?php echo wp_kses_post(get_theme_mod('copyright_text', '© ' . date('Y') . ' ' . (is_eurosuit_page() ? 'Eurosuit' : get_bloginfo('name')) . '. All Rights Reserved.')); ?></p>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const elements = document.querySelectorAll("h4.mt-3.tech-spec-title");

    elements.forEach(element => {
        if (element.textContent.trim() === "{{product.meta_dress name}}") {
            element.style.display = "none";
        }
    });
});
</script>
<script>
// Hide elements containing {{product.meta_dress name}} placeholder
(function() {
    'use strict';
    
    function hideTechSpecElements() {
        // Target the specific classes and elements from your HTML
        const selectors = [
            '.tech-spec-title',           // Main tech spec title class
            '.product-details h4.tech-spec-title',
            '.mt-3.tech-spec-title',     // Product card tech spec title
            'h4.tech-spec-title',
            '.product-info .tech-spec-title'
        ];
        
        selectors.forEach(selector => {
            const elements = document.querySelectorAll(selector);
            
            elements.forEach(element => {
                const textContent = element.textContent || element.innerText;
                
                // Check if element contains the placeholder
                if (textContent.includes('{{product.meta_dress name}}')) {
                    element.style.display = 'none';
                    element.classList.add('hidden-tech-spec');
                    
                    // Optional: Also hide the parent container if it becomes empty
                    const parent = element.parentElement;
                    if (parent && parent.children.length === 1) {
                        parent.style.display = 'none';
                    }
                }
            });
        });
        
        // Additional check for any div containing the placeholder text
        const allDivs = document.querySelectorAll('div');
        allDivs.forEach(div => {
            const textContent = div.textContent || div.innerText;
            if (textContent.trim() === '{{product.meta_dress name}}') {
                div.style.display = 'none';
                div.classList.add('hidden-tech-spec');
            }
        });
        
        // Check for h4, h5, and other heading elements that might contain the placeholder
        const headings = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
        headings.forEach(heading => {
            const textContent = heading.textContent || heading.innerText;
            if (textContent.includes('{{product.meta_dress name}}')) {
                heading.style.display = 'none';
                heading.classList.add('hidden-tech-spec');
            }
        });
    }
    
    // Run immediately when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', hideTechSpecElements);
    } else {
        hideTechSpecElements();
    }
    
    // Also run after a short delay to catch dynamically loaded content
    setTimeout(hideTechSpecElements, 500);
    setTimeout(hideTechSpecElements, 1000);
    
    // Watch for dynamic content changes
    if (window.MutationObserver) {
        const observer = new MutationObserver(function(mutations) {
            let shouldCheck = false;
            
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    // Check if any added nodes contain our target elements
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // Element node
                            if (node.classList && node.classList.contains('tech-spec-title')) {
                                shouldCheck = true;
                            } else if (node.querySelector && node.querySelector('.tech-spec-title')) {
                                shouldCheck = true;
                            }
                        }
                    });
                }
            });
            
            if (shouldCheck) {
                setTimeout(hideTechSpecElements, 100);
            }
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    // Handle AJAX/dynamic loading events
    document.addEventListener('DOMNodeInserted', function() {
        setTimeout(hideTechSpecElements, 100);
    });
    
    // WooCommerce specific events (if present)
    if (typeof jQuery !== 'undefined') {
        jQuery(document).on('updated_wc_div found_variation woocommerce_variation_select_change', function() {
            setTimeout(hideTechSpecElements, 100);
        });
    }
})();

// Alternative CSS-only approach (add this to your stylesheet if preferred)
/*
.tech-spec-title:has-text("{{product.meta_dress name}}"),
div:has-text("{{product.meta_dress name}}") {
    display: none !important;
}
*/

// jQuery version (if jQuery is available)
if (typeof jQuery !== 'undefined') {
    jQuery(document).ready(function($) {
        function hideWithJQuery() {
            // Hide elements containing the placeholder text
            $('.tech-spec-title').each(function() {
                if ($(this).text().includes('{{product.meta_dress name}}')) {
                    $(this).hide().addClass('hidden-tech-spec');
                }
            });
            
            // Hide any div with just the placeholder text
            $('div').each(function() {
                if ($(this).text().trim() === '{{product.meta_dress name}}') {
                    $(this).hide().addClass('hidden-tech-spec');
                }
            });
            
            // Hide headings containing the placeholder
            $('h1, h2, h3, h4, h5, h6').each(function() {
                if ($(this).text().includes('{{product.meta_dress name}}')) {
                    $(this).hide().addClass('hidden-tech-spec');
                }
            });
        }
        
        hideWithJQuery();
        
        // Run on WooCommerce events
        $('body').on('updated_wc_div found_variation', hideWithJQuery);
        
        // Run on AJAX complete
        $(document).ajaxComplete(hideWithJQuery);
    });
}
  </script>

      <script>
      window.cueWidgetConfig = {
        id: 'dd01429c-998a-407a-ab49-b81b3f29cfab', // put your widget id here
        onReady: function () {
          // You can use CueWidget here to interact with the widget programmatically
          
          // Example: Open the widget
          // CueWidget.openWidget();
          
          // Example: Close the widget
          // CueWidget.closeWidget();
          
          // Example: Set Initial Session Data
          // CueWidget.updateInitialSessionData({ name: 'John Doe' });
        },
      };

      var s = document.createElement('script');
      s.dataset.cueWidgetScript = 'true';

      if (window.requirejs && window.requirejs.defined) {
        s.src = 'https://webchat.cuedesk.com/widget.iife.js';
      } else {
        s.src = 'https://webchat.cuedesk.com/widget.js';
      }
      document.head.appendChild(s);
    </script>
<?php wp_footer(); ?>

</body>
</html>