/**
 * Display BrideVibes popup on specific bridal category pages
 * Add this to your theme's functions.php file
 */
function bridal_pages_popup() {

    $show_popup = true;

    // 0) Always allow home page
    if (is_front_page() || is_home()) {
        $show_popup = true;
    }

    // 1) Exclude normal WP pages by slug
    $excluded_pages = array(
        'eurosuit',
        'eurosuit-hire',
        'contact-euro-suit',
    );

    if (function_exists('is_page') && is_page($excluded_pages)) {
        $show_popup = false;
    }

    // 2) Exclude product category trees (current term OR any ancestor matches)
    $excluded_product_cat_slugs = array(
        'suits',
        'waistcoats',
        'separates',
        'shirts',
        'belts',
        'neckpieces-2',
        'cufflinks-2',
        'gifts',
        'lapel-pins-2',
        'socks-2',
        'suspenders',
        'shoes-2',
    );

    if ($show_popup && function_exists('is_product_category') && is_product_category()) {

        $current_term = get_queried_object();

        if ($current_term && !empty($current_term->term_id)) {

            $ancestor_ids = get_ancestors((int)$current_term->term_id, 'product_cat');
            $term_ids_to_check = array_merge(array((int)$current_term->term_id), array_map('intval', $ancestor_ids));

            $excluded_ids = array();
            foreach ($excluded_product_cat_slugs as $slug) {
                $t = get_term_by('slug', $slug, 'product_cat');
                if ($t && !is_wp_error($t)) {
                    $excluded_ids[] = (int)$t->term_id;
                }
            }

            if (!empty(array_intersect($term_ids_to_check, $excluded_ids))) {
                $show_popup = false;
            }
        }
    }

    // ✅ Show popup on all pages except excluded ones
    if (!$show_popup) {
        return;
    }
    ?>
    <!-- Sip & Choc Popup -->
    <div id="sipchocs-popup" class="sc-modal-overlay">
      <div class="sc-popup-container">
        <button class="sc-close-btn" type="button">&times;</button>

        <div class="sc-content-wrapper">
          <div class="sc-main-content">
            <div class="sc-heart-icon"><br /></div>
            <h1 class="sc-title">SIP & CHOCS</h1>
            <p class="sc-subtitle">VALENTINES WEEKEND</p>
            <p class="sc-dates">14-15 FEBRUARY</p>
            <p class="sc-visit">VISIT YOUR NEAREST</p>
            <div class="sc-brand">
              <img
                src="https://brideandco.co.za/wp-content/uploads/2022/05/cropped-cropped-cropped-cropped-cropped-cropped-BrideCo-Logo.png"
                alt="Bride&Co Logo"
                class="sc-brand-logo"
              />
            </div>
            <p class="sc-discover">DISCOVER OUR 2026 COLLECTION</p>
            <a href="https://brideandco.co.za/book-your-free-fitting/" class="sc-cta-btn">BOOK A FREE FITTING</a>
          </div>
        </div>
      </div>
    </div>
    <style>
      /* Sip & Chocs Popup Styles */
      .sc-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 999999;
        display: none;
        justify-content: center;
        align-items: center;
      }

      .sc-popup-container {
        position: relative;
        max-width: 700px;
        width: 90%;
        background-image: url("https://brideandco.co.za/wp-content/uploads/2026/02/sipchocsWebPop-upad.jpg");
        background-size: cover;
        background-position: center center;
        color: white;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
      }

      .sc-close-btn {
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

      .sc-close-btn:hover {
        opacity: 1;
      }

      .sc-content-wrapper {
        position: relative;
        padding: 60px 40px;
        min-height: 500px;
      }

      .sc-main-content {
        position: relative;
        text-align: center;
        z-index: 2;
      }

      .sc-heart-icon {
        font-size: 40px;
        margin-bottom: 15px;
      }

      .sc-title {
        font-family: Cinzel, serif;
        font-size: 56px;
        font-weight: 300;
        letter-spacing: 8px;
        margin: 0 0 10px 0;
        text-transform: uppercase;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
      }

      .sc-subtitle {
        font-family: "Glacial Indifference", Arial, sans-serif;
        font-size: 30px;
        font-weight: 300;
        letter-spacing: 4px;
        margin: 0 0 15px 0!important;
        text-transform: uppercase;
        font-style: italic;
      }

      .sc-dates {
        font-family: "Glacial Indifference", Arial, sans-serif;
        font-size: 25px;
        font-weight: 300;
        margin: 0 0 20px 0!important;
        letter-spacing: 2px;
      }

      .sc-visit {
        font-family: "Glacial Indifference", Arial, sans-serif;
        font-size: 30px;
        font-weight: 300;
        margin: 0 0 8px 0!important;
        letter-spacing: 2px;
        text-transform: uppercase;
      }

      .sc-brand {
        margin: 0 0 20px 0;
        text-align: center;
      }

      .sc-brand-logo {
        height: 35px;
        width: auto;
        display: inline-block;
        filter: brightness(0) invert(1);
      }

      .sc-discover {
        font-family: "Glacial Indifference", Arial, sans-serif;
        font-size: 22px;
        font-weight: 300;
        margin: 0 0 30px 0!important;
        letter-spacing: 2px;
        text-transform: uppercase;
      }

      .sc-cta-btn {
        display: inline-block;
        background: white;
        color: #333!important;
        padding: 14px 35px;
        text-decoration: none;
        font-family: "Glacial Indifference", Arial, sans-serif;
        font-weight: bold;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        border-radius: 4px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      }

      .sc-cta-btn:hover {
        background: #f0f0f0;
        color: #8b0000;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
      }

      /* Responsive Design */
      @media screen and (max-width: 768px) {
        .sc-popup-container {
          width: 95%;
          max-width: 500px;
        }

        .sc-content-wrapper {
          padding: 40px 25px;
          min-height: 450px;
        }

        .sc-heart-icon {
          font-size: 35px;
        }

        .sc-title {
          font-size: 42px;
          letter-spacing: 6px;
        }

        .sc-subtitle {
          font-size: 16px;
        }

        .sc-dates {
          font-size: 15px;
        }

        .sc-visit {
          font-size: 15px;
        }

        .sc-brand-logo {
          height: 40px;
        }

        .sc-discover {
          font-size: 15px;
        }

        .sc-cta-btn {
          font-size: 12px;
          padding: 12px 28px;
        }
      }

      @media screen and (max-width: 480px) {
        .sc-content-wrapper {
          padding: 30px 20px;
          min-height: 400px;
        }

        .sc-heart-icon {
          font-size: 30px;
        }

        .sc-title {
          font-size: 32px;
          letter-spacing: 4px;
        }

        .sc-subtitle {
          font-size: 14px;
        }

        .sc-dates {
          font-size: 13px;
          margin-bottom: 25px;
        }

        .sc-visit {
          font-size: 14px;
        }

        .sc-brand-logo {
          height: 35px;
        }

        .sc-discover {
          font-size: 14px;
        }

        .sc-cta-btn {
          font-size: 11px;
          padding: 10px 24px;
        }
      }
    </style>

   <script>
      (function () {

        function initSipChocsPopup() {
          var popup = document.getElementById("sipchocs-popup");
          var closeBtn = document.querySelector(".sc-close-btn");
          if (!popup) return;

          setTimeout(function () {
            popup.style.display = "flex";
          }, 500);

          function closePopup() {
            popup.style.display = "none";
          }

          if (closeBtn) closeBtn.addEventListener("click", closePopup);

          popup.addEventListener("click", function (e) {
            if (e.target === popup) closePopup();
          });

          document.addEventListener("keydown", function (e) {
            if (e.key === "Escape" || e.keyCode === 27) closePopup();
          });
        }

        // ✅ Works even if DOMContentLoaded already fired
        if (document.readyState === "loading") {
          document.addEventListener("DOMContentLoaded", initSipChocsPopup);
        } else {
          initSipChocsPopup();
        }

      })();
    </script>
    <?php
}

add_action('wp_footer', 'bridal_pages_popup');