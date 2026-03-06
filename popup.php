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
                $bridal_id  = (int) $bridal_term->term_id;
                $current_id = (int) $current_term->term_id;
                if ($current_id === $bridal_id) {
                    $show_popup = true;
                } else {
                    $ancestor_ids = array_map('intval', get_ancestors($current_id, 'product_cat'));
                    if (in_array($bridal_id, $ancestor_ids)) $show_popup = true;
                }
            }
        }
    }

    if (!$show_popup && function_exists('is_product') && is_product()) {
        $bridal_term = get_term_by('slug', 'bridal', 'product_cat');
        if ($bridal_term && !is_wp_error($bridal_term)) {
            $bridal_id    = (int) $bridal_term->term_id;
            $product_terms = get_the_terms(get_the_ID(), 'product_cat');
            if ($product_terms && !is_wp_error($product_terms)) {
                foreach ($product_terms as $term) {
                    $tid = (int) $term->term_id;
                    if ($tid === $bridal_id) { $show_popup = true; break; }
                    $anc = array_map('intval', get_ancestors($tid, 'product_cat'));
                    if (in_array($bridal_id, $anc)) { $show_popup = true; break; }
                }
            }
        }
    }

    if (!$show_popup) return;
    ?>

    <!-- BrideVibes Popup -->
    <div id="bridevibes-popup" class="bv-overlay">
      <div class="bv-modal">

        <button class="bv-close" type="button">&times;</button>

        <!-- Full background image (model + diagonal pink) -->
        <!-- All content sits on the right pink half -->

        <div class="bv-content">

          <!-- Bride&Co logo — top right of pink area -->
          <div class="bv-bnc-wrap">
            <img
              src="https://brideandco.co.za/wp-content/uploads/2022/05/cropped-cropped-cropped-cropped-cropped-cropped-BrideCo-Logo.png"
              alt="Bride&Co"
              class="bv-bnc-logo"
            />
            <sup class="bv-20">20</sup>
            <span class="bv-tagline">A Lifetime of Moments</span>
          </div>

          <!-- BrideVibes BV logo -->
          <div class="bv-logo-wrap">
            <img
              src="https://brideandco.co.za/wp-content/uploads/2026/03/BV-BNC-logo-combo-PNG.png"
              alt="BrideVibes by Bride&Co"
              class="bv-logo"
            />
          </div>

          <!-- Offer text -->
          <p class="bv-join">Join Bridevibes &amp; get</p>
          <p class="bv-pct">15% off</p>
          <p class="bv-desc">on your seasonal<br>bridal gown</p>

          <!-- CTA -->
          <a href="https://brideandco.co.za/bridevibes/" class="bv-btn">JOIN NOW</a>

        </div>

        <!-- T&Cs pinned bottom-left over the image -->
        <div class="bv-tc">
          T's &amp; C's Apply<br>
          <span>*Markdowns Excluded</span>
        </div>

      </div>
    </div>

    <style>
      /* ── Overlay ── */
      .bv-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.72);
        z-index: 999999;
        display: none;
        justify-content: center;
        align-items: center;
      }

      /* ── Modal shell — full background image ── */
      .bv-modal {
        position: relative;
        width: 92%;
        max-width: 700px;
        aspect-ratio: 700 / 467; /* matches original ad ratio */
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 24px 64px rgba(0,0,0,0.55);
        background-image: url("https://brideandco.co.za/wp-content/uploads/2026/03/Untitled-design-21.png");
        background-size: cover;
        background-position: center center;
      }

      /* ── Close button ── */
      .bv-close {
        position: absolute;
        top: 10px;
        right: 14px;
        background: none;
        border: none;
        font-size: 26px;
        color: #333;
        cursor: pointer;
        z-index: 5;
        line-height: 1;
        padding: 0;
        opacity: 0.75;
        transition: opacity 0.2s;
      }
      .bv-close:hover { opacity: 1; }

      /* ── Content block — right ~48% of modal ── */
      .bv-content {
        position: absolute;
        top: 0;
        right: 0;
        width: 48%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 5% 5% 5% 2%;
        box-sizing: border-box;
        text-align: center;
      }

      /* Bride&Co header row */
      .bv-bnc-wrap {
        position: absolute;
        top: 5%;
        right: 4%;
        display: flex;
        align-items: flex-start;
        gap: 3px;
      }
      .bv-bnc-logo {
        height: 18px;
        width: auto;
        filter: brightness(0); /* make it dark/black */
      }
      .bv-20 {
        font-size: 11px;
        font-weight: 700;
        color: #111;
        font-family: Georgia, serif;
        margin-top: -2px;
      }
      .bv-tagline {
        display: none; /* too small to render cleanly */
      }

      /* BrideVibes logo — black bg so we use mix-blend-mode to drop the black */
      .bv-logo-wrap {
        margin-bottom: 8%;
        width: 85%;
      }
      .bv-logo {
        width: 100%;
        height: auto;
        display: block;
        mix-blend-mode: multiply; /* drops black bg, shows logo on pink */
      }

      /* Offer text */
      .bv-join {
        font-family: Georgia, serif;
        font-size: clamp(11px, 1.8vw, 16px);
        color: #222;
        margin: 0 0 2px 0 !important;
      }
      .bv-pct {
        font-family: Georgia, "Times New Roman", serif;
        font-size: clamp(26px, 5vw, 44px);
        font-weight: 700;
        color: #111;
        margin: 0 0 2px 0 !important;
        line-height: 1;
      }
      .bv-desc {
        font-family: Georgia, serif;
        font-size: clamp(11px, 1.8vw, 16px);
        color: #222;
        margin: 0 0 6% 0 !important;
        line-height: 1.5;
      }

      /* CTA button */
      .bv-btn {
        display: inline-block;
        background: #111;
        color: #fff !important;
        padding: 10px 32px;
        text-decoration: none !important;
        font-family: "Trebuchet MS", Arial, sans-serif;
        font-weight: 600;
        font-size: clamp(10px, 1.4vw, 13px);
        text-transform: uppercase;
        letter-spacing: 2.5px;
        border-radius: 3px;
        transition: background 0.25s, transform 0.2s;
        box-shadow: 0 4px 14px rgba(0,0,0,0.2);
      }
      .bv-btn:hover {
        background: #3a0010;
        transform: translateY(-2px);
        text-decoration: none !important;
      }

      /* T&Cs — bottom-left corner over the photo */
      .bv-tc {
        position: absolute;
        bottom: 4%;
        left: 3%;
        font-family: Arial, sans-serif;
        font-size: clamp(8px, 1.1vw, 11px);
        color: #fff;
        font-style: italic;
        line-height: 1.5;
        text-shadow: 0 1px 3px rgba(0,0,0,0.5);
      }
      .bv-tc span {
        font-size: clamp(7px, 1vw, 10px);
      }

      /* ── Mobile ── */
      @media screen and (max-width: 540px) {
        .bv-modal {
          aspect-ratio: auto;
          height: 85vw;
          max-height: 520px;
        }
        .bv-content {
          width: 52%;
          padding: 4% 4% 4% 1%;
        }
        .bv-logo-wrap {
          width: 95%;
        }
      }
    </style>

    <script>
      (function () {
        function initBVPopup() {
          var popup   = document.getElementById("bridevibes-popup");
          var closeBtn = document.querySelector(".bv-close");
          if (!popup) return;

          setTimeout(function () { popup.style.display = "flex"; }, 600);

          function closePopup() { popup.style.display = "none"; }

          if (closeBtn) closeBtn.addEventListener("click", closePopup);
          popup.addEventListener("click", function (e) {
            if (e.target === popup) closePopup();
          });
          document.addEventListener("keydown", function (e) {
            if (e.key === "Escape") closePopup();
          });
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