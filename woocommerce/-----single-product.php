<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * @package Bride-co-child
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header('shop');

// Size Guide Button Module with Modal Trigger
function render_size_guide_button() {
    global $product;
    
    // Default measuring guide URL
    $measuring_guide_url = '/fitting-guide';
    
    // Check if product exists
    if ($product) {
        // Check if product has Eurosuit brand attribute
        $terms = get_the_terms($product->get_id(), 'pa_brand');
        
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                // Check if the brand name contains "Euro suit" or "Eurosuit" (case insensitive)
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

// Start the loop
while ( have_posts() ) : the_post();
    global $product;
    
    // Localize product variations for JavaScript
    if ($product->is_type('variable')) {
        $available_variations = $product->get_available_variations();
        
        // Prepare variations data for JavaScript
        $variations_data = array();
        
        foreach ($available_variations as $variation) {
            $variation_obj = wc_get_product($variation['variation_id']);
            
            if ($variation_obj) {
                $variations_data[] = array(
                    'variation_id' => $variation['variation_id'],
                    'attributes' => $variation['attributes'],
                    'is_in_stock' => $variation_obj->is_in_stock(),
                    'stock_quantity' => $variation_obj->get_stock_quantity(),
                    'stock_status' => $variation_obj->get_stock_status(),
                    'max_qty' => $variation['max_qty'],
                    'min_qty' => $variation['min_qty'],
                    'is_purchasable' => $variation['is_purchasable'],
                    'price_html' => $variation_obj->get_price_html(),
                    'regular_price' => $variation_obj->get_regular_price(),
                    'sale_price' => $variation_obj->get_sale_price(),
                    'raw_variation_data' => $variation, // Include raw data for debugging
                );
            }
        }
        ?>
        <script type="text/javascript">
            window.product_variations = <?php echo json_encode($variations_data); ?>;
            console.log('=== PHP DEBUG INFO ===');
            console.log('Product variations loaded:', window.product_variations);
            console.log('Total variations from PHP:', window.product_variations.length);
            console.log('Product is variable:', <?php echo $product->is_type('variable') ? 'true' : 'false'; ?>);
            console.log('WooCommerce available_variations count:', <?php echo count($available_variations); ?>);
        </script>
        <?php
    } else {
        ?>
        <script type="text/javascript">
            console.log('=== PHP DEBUG INFO ===');
            console.log('Product is not variable type');
            window.product_variations = [];
        </script>
        <?php
    }
    
    // Get product images
    $attachment_ids = $product->get_gallery_image_ids();
    $main_image_id = $product->get_image_id();
    $main_image_url = wp_get_attachment_url($main_image_id);
    $fallback_image = 'https://stage.brideandco.co.za/wp-content/uploads/2022/05/cropped-BrideCo-Logo.png';
    
    // Check if product is new (less than 30 days old)
    $date_created = $product->get_date_created();
    $is_new = function_exists('is_product_new') ? is_product_new($product->get_id()) : false; 
    $is_eurosuite = function_exists('is_eurosuit_page') ? is_eurosuit_page() : false;    
    if($is_eurosuite)
    $newlabel_style ='new-badge-euro';   
    // Get product brand if exists
    $brand = '';
    if (function_exists('get_the_terms')) {
        $terms = get_the_terms($product->get_id(), 'product_brand');
        if (!empty($terms) && !is_wp_error($terms)) {
            $brand = $terms[0]->name;
        }
    }
    
    // Get product price info
    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();
    $is_on_sale = $product->is_on_sale();
    $discount_percentage = 0;
    
    if ($is_on_sale && $regular_price > 0) {
        $discount_percentage = round(100 - (($sale_price / $regular_price) * 100));
    }

    $categories = wp_get_post_terms($product->get_id(), 'product_cat');
    $is_hire=0;
    foreach ( $categories as $term ) {
     
            if ($term->name === 'Hire' ) {
               $is_hire = true;
                break;
            }
        
    }
    
    // Get Technical Specification
    $tech_spec = '';
    $tech_spec_terms = get_the_terms($product->get_id(), 'pa_technical-spec');
    if (!empty($tech_spec_terms) && !is_wp_error($tech_spec_terms)) {
        $tech_spec = $tech_spec_terms[0]->name;
    }
    
    // If tech spec not available, check meta_dress_name
    if (empty($tech_spec)) {
        $meta_dress_name = get_post_meta($product->get_id(), 'meta_dress_name', true);
        if (!empty($meta_dress_name) && $meta_dress_name !== '{{product.meta_dress name}}') {
            $tech_spec = $meta_dress_name;
        }
    }
?>

    <div class="container">
        <div class="breadcrumb">
            <?php woocommerce_breadcrumb(); ?>
        </div>
        
        <div class="product-container">
            <div class="product-gallery clearfix">
                <div class="thumbnail-sidebar">
                    <?php 
                    // Display main product image as first thumbnail
                    if ($main_image_url) {
                        echo '<div class="thumbnail active">';
                        echo '<img src="' . esc_url($main_image_url) . '" alt="' . esc_attr(get_the_title()) . ' Front View">';
                        echo '</div>';
                    }
                    
                    // Display gallery images
                    foreach ($attachment_ids as $attachment_id) {
                        $image_url = wp_get_attachment_url($attachment_id);
                        if ($image_url) {
                            echo '<div class="thumbnail">';
                            echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . ' View">';
                            echo '</div>';
                        }
                    }
                    
                    // If no images, show fallback
                    if (!$main_image_url && empty($attachment_ids)) {
                        echo '<div class="thumbnail active">';
                        echo '<img src="' . esc_url($fallback_image) . '" alt="' . esc_attr(get_the_title()) . '">';
                        echo '</div>';
                    }
                    ?>
                </div>
                
                <div class="main-image-container">
                    <?php if ($is_new) : ?>
                    <div class="new-badge <?php echo $newlabel_style;?>">NEW</div>
                    <?php endif; ?>
                    <button class="add-to-favorites">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                    <div class="main-image">
                        <img src="<?php echo esc_url($main_image_url ? $main_image_url : $fallback_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                    </div>
                    <div class="navigation-arrows">
                        <div class="nav-arrow prev">
                            <i class="fa-solid fa-chevron-left"></i>
                        </div>
                        <div class="nav-arrow next">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
                <div class="social-share-wrapper">
    <div class="share-title">SHARE THIS PRODUCT</div>
    <div class="social-share-buttons">
        <button class="share-btn facebook" onclick="shareOnFacebook()" title="Share on Facebook">
            <i class="fab fa-facebook-f"></i>
        </button>
        <button class="share-btn instagram" onclick="shareOnInstagram()" title="Share on Instagram">
            <i class="fab fa-instagram"></i>
        </button>
        <button class="share-btn email" onclick="shareViaEmail()" title="Share via Email">
            <i class="fas fa-envelope"></i>
        </button>
        <button class="share-btn copy-link" onclick="copyProductLink()" title="Copy Link">
            <i class="fas fa-link"></i>
        </button>
        <button class="share-btn whatsapp" onclick="shareOnWhatsApp()" title="Share on WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </button>
    </div>
    <div class="copy-success-message" id="copySuccessMessage">Link copied to clipboard!</div>
</div>

<!-- Instagram Share Modal -->
<div id="instagramModal" class="instagram-modal">
    <div class="instagram-modal-content">
        <span class="instagram-close" onclick="closeInstagramModal()">&times;</span>
        <h3>Share on Instagram</h3>
        <p>Instagram doesn't allow direct sharing from websites. Here's how to share:</p>
        <ol>
            <li>Copy this product link using the button below</li>
            <li>Open Instagram on your mobile device</li>
            <li>Create a story or post</li>
            <li>Add the link to your bio or story (using the link sticker)</li>
        </ol>
        <button class="instagram-copy-btn" onclick="copyForInstagram()">
            <i class="fas fa-copy"></i> Copy Product Link
        </button>
        <div class="instagram-link-display">
            <input type="text" id="instagramLinkInput" readonly value="<?php echo esc_url(get_permalink()); ?>">
        </div>
    </div>
</div>

<script>
// Get product information for sharing
const productTitle = "<?php echo esc_js(get_the_title()); ?>";
const productUrl = "<?php echo esc_url(get_permalink()); ?>";
const productImage = "<?php echo esc_url($main_image_url ? $main_image_url : $fallback_image); ?>";
const productPrice = "<?php echo esc_js(strip_tags($product->get_price_html())); ?>";
const productDescription = "<?php echo esc_js(wp_trim_words(strip_tags($product->get_short_description()), 20, '...')); ?>";

// Facebook Share
function shareOnFacebook() {
    const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(productUrl)}`;
    window.open(facebookUrl, 'facebook-share', 'width=600,height=400');
    
    if (typeof gtag !== 'undefined') {
        gtag('event', 'share', {
            'method': 'facebook',
            'content_type': 'product',
            'content_id': '<?php echo esc_js($product->get_id()); ?>'
        });
    }
}

// Instagram Share (opens modal with instructions)
function shareOnInstagram() {
    document.getElementById('instagramModal').style.display = 'block';
    
    if (typeof gtag !== 'undefined') {
        gtag('event', 'share', {
            'method': 'instagram',
            'content_type': 'product',
            'content_id': '<?php echo esc_js($product->get_id()); ?>'
        });
    }
}

// Close Instagram Modal
function closeInstagramModal() {
    document.getElementById('instagramModal').style.display = 'none';
}

// Copy link for Instagram
function copyForInstagram() {
    const linkInput = document.getElementById('instagramLinkInput');
    linkInput.select();
    linkInput.setSelectionRange(0, 99999);
    
    try {
        document.execCommand('copy');
        
        const btn = document.querySelector('.instagram-copy-btn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
        btn.classList.add('copied');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('copied');
        }, 2000);
    } catch (err) {
        navigator.clipboard.writeText(productUrl).then(() => {
            const btn = document.querySelector('.instagram-copy-btn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
            btn.classList.add('copied');
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('copied');
            }, 2000);
        });
    }
}

// Email Share
function shareViaEmail() {
    const subject = `Check out this product: ${productTitle}`;
    const body = `I thought you might be interested in this product:\n\n${productTitle}\n${productPrice}\n\n${productDescription}\n\nView it here: ${productUrl}`;
    const mailtoLink = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    window.location.href = mailtoLink;
    
    if (typeof gtag !== 'undefined') {
        gtag('event', 'share', {
            'method': 'email',
            'content_type': 'product',
            'content_id': '<?php echo esc_js($product->get_id()); ?>'
        });
    }
}

// Copy Product Link
function copyProductLink() {
    const tempInput = document.createElement('input');
    tempInput.value = productUrl;
    document.body.appendChild(tempInput);
    tempInput.select();
    tempInput.setSelectionRange(0, 99999);
    
    try {
        document.execCommand('copy');
        showCopySuccess();
    } catch (err) {
        navigator.clipboard.writeText(productUrl).then(() => {
            showCopySuccess();
        }).catch(() => {
            alert('Failed to copy link. Please copy manually: ' + productUrl);
        });
    }
    
    document.body.removeChild(tempInput);
    
    if (typeof gtag !== 'undefined') {
        gtag('event', 'share', {
            'method': 'copy_link',
            'content_type': 'product',
            'content_id': '<?php echo esc_js($product->get_id()); ?>'
        });
    }
}

// Show copy success message
function showCopySuccess() {
    const message = document.getElementById('copySuccessMessage');
    message.style.display = 'block';
    
    const copyBtn = document.querySelector('.copy-link');
    const originalHTML = copyBtn.innerHTML;
    copyBtn.innerHTML = '<i class="fas fa-check"></i>';
    copyBtn.classList.add('copied');
    
    setTimeout(() => {
        message.style.display = 'none';
        copyBtn.innerHTML = originalHTML;
        copyBtn.classList.remove('copied');
    }, 2000);
}

// WhatsApp Share
function shareOnWhatsApp() {
    const whatsappText = `Check out this product: ${productTitle} - ${productPrice}\n${productUrl}`;
    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(whatsappText)}`;
    window.open(whatsappUrl, '_blank');
    
    if (typeof gtag !== 'undefined') {
        gtag('event', 'share', {
            'method': 'whatsapp',
            'content_type': 'product',
            'content_id': '<?php echo esc_js($product->get_id()); ?>'
        });
    }
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('instagramModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
});

// Add keyboard support for closing modal
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeInstagramModal();
    }
});
</script>

<style>
/* Social Share Wrapper - Inside product gallery */
.product-gallery .social-share-wrapper {
    margin-top: 20px;
    padding-top: 35px;
    text-align: center;
    width: 100%;
    clear: both;
    margin-left: 50px;
}

/* Share title */
.share-title {
    font-size: 13px;
    font-weight: 600;
    color: #b19176;
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    text-align: center;
}

/* Social share buttons container */
.social-share-buttons {
    display: flex;
    gap: 12px;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
}

/* Individual share buttons */
.share-btn {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    color: white;
    font-size: 18px;
    position: relative;
    overflow: hidden;
}

.share-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.share-btn:active {
    transform: translateY(-1px);
}

/* Button colors */
.share-btn.facebook {
    background: #333333;
}

.share-btn.facebook:hover {
    background: #333333;
}

.share-btn.instagram {
    background: #333333
}
.share-btn.instagram:hover {
    background: #333333;
}
.share-btn.email {
    background: #333333;
}

.share-btn.email:hover {
    background: #333333;
}

.share-btn.copy-link {
    background: #333333;
}

.share-btn.copy-link:hover {
    background: #333333;
}

.share-btn.copy-link.copied {
    background: #28a745;
    animation: successPulse 0.5s ease;
}

.share-btn.whatsapp {
    background: #DDCDBF;
}

.share-btn.whatsapp:hover {
    background: #b19176;
}

/* Copy success message */
.copy-success-message {
    display: none;
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: #28a745;
    color: white;
    padding: 12px 24px;
    border-radius: 25px;
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    z-index: 10000;
    animation: slideUp 0.3s ease;
    font-size: 14px;
    font-weight: 500;
}

@keyframes slideUp {
    from {
        bottom: -50px;
        opacity: 0;
    }
    to {
        bottom: 20px;
        opacity: 1;
    }
}

@keyframes successPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Instagram Modal Styles */
.instagram-modal {
    display: none;
    position: fixed;
    z-index: 10001;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.6);
    animation: fadeIn 0.3s ease;
}

.instagram-modal-content {
    background-color: #fefefe;
    margin: 10% auto;
    padding: 30px;
    border-radius: 15px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    animation: slideDown 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.instagram-close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s ease;
}

.instagram-close:hover,
.instagram-close:focus {
    color: #000;
}

.instagram-modal-content h3 {
    color: #333;
    margin-bottom: 15px;
    font-size: 20px;
}

.instagram-modal-content p {
    color: #666;
    margin-bottom: 15px;
    font-size: 14px;
}

.instagram-modal-content ol {
    margin: 20px 0;
    padding-left: 20px;
    color: #555;
}

.instagram-modal-content ol li {
    margin-bottom: 10px;
    font-size: 14px;
}

.instagram-copy-btn {
    background: #b19176;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 25px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
    margin: 20px 0 15px;
    width: 100%;
}

.instagram-copy-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.instagram-copy-btn.copied {
    background: #28a745;
}

.instagram-link-display {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 8px;
    margin-top: 10px;
}

.instagram-link-display input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 13px;
    color: #666;
    background: white;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .product-gallery .social-share-wrapper {
        margin-top: 15px;
        padding-top: 12px;
        margin-left: 0px;
    }
    
    .share-title {
        font-size: 12px;
    }
    
    .social-share-buttons {
        gap: 10px;
    }
    
    .share-btn {
        width: 38px;
        height: 38px;
        font-size: 16px;
        color: #333;
    }
    
    .instagram-modal-content {
        width: 95%;
        margin: 20% auto;
        padding: 20px;
    }
    
    .copy-success-message {
        bottom: 60px;
        font-size: 13px;
    }
}

/* Tooltip for share buttons */
.share-btn {
    position: relative;
}

.share-btn::before {
    content: attr(title);
    position: absolute;
    bottom: 120%;
    left: 50%;
    transform: translateX(-50%);
    background: #333;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 12px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
    z-index: 1000;
}

.share-btn:hover::before {
    opacity: 1;
}

/* Add ripple effect on click */
.share-btn::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.share-btn:active::after {
    width: 300%;
    height: 300%;
}

/* Ensure FontAwesome icons display properly */
.share-btn i {
    font-size: 18px;
    line-height: 1;
    align-self: baseline;
}

/* Dark mode support (if your theme has dark mode) */
@media (prefers-color-scheme: dark) {
    .share-title {
        color: #b19176;
    }
    
    .instagram-modal-content {
        background: #2a2a2a;
        color: #e0e0e0;
    }
    
    .instagram-modal-content h3,
    .instagram-modal-content p,
    .instagram-modal-content ol {
        color: #e0e0e0;
    }
    
    .instagram-link-display {
        background: #1a1a1a;
    }
    
    .instagram-link-display input {
        background: #333;
        color: #e0e0e0;
        border-color: #444;
    }
}

/* Ensure no layout shift - product gallery maintains its original width */
.product-gallery {
    display: inline-block;
    vertical-align: top;
}

/* Keep the existing flex layout intact */
.product-container {
    display: flex;
    gap: 30px;
}

.product-info {
    flex: 1;
}

/* Ensure proper stacking on very small screens */
@media (max-width: 480px) {
    .product-container {
        flex-direction: column;
    }
    
    .product-gallery,
    .product-info {
        width: 100%;
    }
}

/* CSS for out-of-stock color options with strike-through effect */
.color-option {
    position: relative;
    transition: all 0.3s ease;
}

/* Strike-through line for out-of-stock colors */
.color-option.out-of-stock .strike-through {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 2px;
    background-color: #dc3545; /* Bootstrap red color */
    z-index: 2;
    transform: translateY(-50%);
    border-radius: 1px;
}

/* Alternative method using CSS pseudo-element */
.color-option.out-of-stock::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 2px;
    background-color: #dc3545;
    z-index: 2;
    transform: translateY(-50%);
    border-radius: 1px;
}

/* Out of stock color styling */
.color-option.out-of-stock {
    opacity: 0.6 !important;
    pointer-events: none !important;
    cursor: not-allowed !important;
}

/* Disabled colors (not available in selected size) */
.color-option.disabled {
    opacity: 0.3 !important;
    pointer-events: none !important;
    cursor: not-allowed !important;
    filter: grayscale(50%);
}

/* Enhanced styling for color swatches */
.color-option {
    border-radius: 50% !important; /* Make color swatches circular */
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 5px;
    border: 2px solid #ddd;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.color-option:hover:not(.out-of-stock):not(.disabled) {
    border-color: #333;
    transform: scale(1.1);
}

.color-option.active {
    border-color: #000 !important;
    border-width: 3px !important;
}

/* Color name styling for text-based colors */
.color-option .color-name {
    font-size: 12px;
    font-weight: 500;
    text-align: center;
    pointer-events: none;
    position: relative;
    z-index: 1;
}

/* Size options out of stock styling */
.size-option.out-of-stock {
    text-decoration: line-through !important;
    text-decoration-color: #dc3545 !important;
    text-decoration-thickness: 2px !important;
    border: 1px solid #dc3545 !important;
    color: #6c757d !important;
    pointer-events: none !important;
    opacity: 0.6 !important;
    cursor: not-allowed !important;
}

/* CSS for improved add to cart functionality */
.highlight-selection {
  border: 2px solid #d9304f;
  animation: pulse 1s infinite;
}

@keyframes pulse {
  0% { box-shadow: 0 0 0 0 rgba(217, 48, 79, 0.7); }
  70% { box-shadow: 0 0 0 10px rgba(217, 48, 79, 0); }
  100% { box-shadow: 0 0 0 0 rgba(217, 48, 79, 0); }
}

/* Styling for product card links */
.product-card-link {
display: block;
text-decoration: none;
color: inherit;
position: relative;
}

.product-card-link:hover {
text-decoration: none;
color: inherit;
}

/* Additional styling for attribute selections */
.attribute-selection {
margin-bottom: 15px;
}

.attribute-title {
font-weight: bold;
margin-bottom: 8px;
}

.attribute-options {
display: flex!important;
flex-wrap: wrap;
gap: 8px;
}

.attribute-option {
padding: 8px 12px;
border: 1px solid #ddd;
cursor: pointer;
transition: all 0.3s ease;
}

.attribute-option.active {
border-color: #000;
background-color: #f5f5f5;
}

.attribute-option:hover {
border-color: #000;
}

.modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0; top: 0;
  width: 100%; height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.4);
}
.modal-content {
  background-color: #fff;
  margin: 10% auto;
  padding: 20px;
  border-radius: 10px;
  width: 80%;
  max-width: 600px;
}
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}
.close:hover {
  color: #000;
}

.woocommerce-Price-amount
{
    font-size: 17px !important;
}
.current-price del .woocommerce-Price-amount {
  font-size: 15px !important;
}
.stock-status
{
    font-weight:bold;
}
.modal-content
{
    max-width:720px !important;
}

/* Technical Specification Styling */
.tech-spec-title {
    font-size: 18px;
    color: #666;
    margin-bottom: 5px;
    font-weight: 500;
}

.product-title-with-spec {
    margin-top: 0;
}
</style>
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
                    <?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ); ?>
                </div>
                
                <div class="stock-status">
                    <i class="fa-solid fa-circle"></i> 
                    <?php 
                    if ($product->get_stock_status() === 'instock') {
                        echo 'In stock';
                    } elseif ($product->get_stock_status() === 'outofstock') {
                        echo 'Out of stock';
                    } else {
                        echo 'Limited stock';
                    }
                    ?>
                </div>
                
                <?php if ($product->get_sku()) : ?>
                <div class="product-code">Product Code: <?php echo esc_html($product->get_sku()); ?></div>
                <?php endif; ?>
                
                <div class="price-container">
                    <?php if ($is_on_sale && $discount_percentage > 0) : ?>
                        <div class="discount-badge"><?php echo esc_html($discount_percentage); ?>%</div>
                    <?php endif; ?>
                    
                    <div class="price-box">
                        <?php if ($is_on_sale && $product->get_regular_price()) : ?>
                            <div class="old-price"><?php echo wc_price($product->get_regular_price()); ?></div>
                            <div class="current-price"><?php echo wc_price($product->get_sale_price()); ?></div>
                        <?php elseif(!$is_hire) : ?>
                            <div class="current-price"><?php echo $product->get_price_html(); ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <?php
                // Display product variations if this is a variable product
                if ($product->is_type('variable')) :
                    $attributes = $product->get_variation_attributes();
                    
                    foreach ($attributes as $attribute_name => $options) :
                        $attribute_label = wc_attribute_label($attribute_name);
                        $attribute_id = sanitize_title($attribute_name);
                        $selected_value = isset($_REQUEST['attribute_' . $attribute_id]) ? wc_clean(wp_unslash($_REQUEST['attribute_' . $attribute_id])) : $product->get_variation_default_attribute($attribute_name);
                        
                        // Standardized attribute name for data attribute
                        $full_attribute_name = 'attribute_' . $attribute_id;
                        
                        // Detect attribute type
                        $is_color = strtolower($attribute_label) === 'color' || strpos(strtolower($attribute_name), 'color') !== false || strpos(strtolower($attribute_id), 'color') !== false || strpos(strtolower($attribute_id), 'colour') !== false;
                        $is_size = strtolower($attribute_label) === 'size' || strpos(strtolower($attribute_name), 'size') !== false;
                        
                        // Default class for custom styling
                        $attr_class = 'attribute-selection';
                        $option_class = 'attribute-option';
                        
                        // Use specific classes for known types
                        if ($is_color) {
                            $attr_class = 'color-selection';
                            $option_class = 'color-option';
                        } elseif ($is_size) {
                            $attr_class = 'size-selection';
                            $option_class = 'size-option';
                        }
                ?>
                <div class="<?php echo esc_attr($attr_class); ?>" data-attribute="<?php echo esc_attr($full_attribute_name); ?>">
                    <div class="attribute-title"><?php echo esc_html($attribute_label); ?></div>
                    <div class="attribute-options">
                    
                        <?php 
                        if (!$is_color) {
                            // Regular display for non-color options
                            sort($options, SORT_NUMERIC);
                            foreach ($options as $option) : 
                                $is_selected = ($selected_value === $option);
                                $variation_in_stock = false;

                                $available_variations = $product->get_available_variations() ?: [];

                                foreach ($available_variations as $variation) {
                                    if (
                                        isset($variation['attributes'][$full_attribute_name]) &&
                                        $variation['attributes'][$full_attribute_name] === $option &&
                                        $variation['is_in_stock']
                                    ) {
                                        $variation_in_stock = true;
                                        break;
                                    }
                                }

                                $stock_class = $variation_in_stock ? '' : 'out-of-stock';
                                $disabled = $variation_in_stock ? '' : 'pointer-events: none; opacity: 0.5; text-decoration: line-through;';
                            ?>
                            <div class="<?php echo esc_attr($option_class . ' ' . ($is_selected ? 'active' : '') . ' ' . $stock_class); ?>" 
                                 data-value="<?php echo esc_attr($option); ?>"
                                 data-attribute="<?php echo esc_attr($full_attribute_name); ?>"
                                 title="<?php echo $variation_in_stock ? '' : 'Out of stock'; ?>"
                                 style="<?php echo esc_attr($disabled); ?>">
                                <?php echo esc_html($option); ?>
                            </div>
                            <?php 
                            endforeach; 
                        } else {
                            // Color swatch display for color options
                            foreach ($options as $option) : 
                                $is_selected = ($selected_value === $option);
                                $variation_in_stock = false;

                                $available_variations = $product->get_available_variations() ?: [];

                                foreach ($available_variations as $variation) {
                                    if (
                                        isset($variation['attributes'][$full_attribute_name]) &&
                                        $variation['attributes'][$full_attribute_name] === $option &&
                                        $variation['is_in_stock']
                                    ) {
                                        $variation_in_stock = true;
                                        break;
                                    }
                                }

                                $stock_class = $variation_in_stock ? '' : 'out-of-stock';
                                $disabled = $variation_in_stock ? '' : 'pointer-events: none; opacity: 0.5;';
                                
                                // Display color swatch using the render function
                                if (function_exists('render_color_swatch')) {
                                    render_color_swatch($option, $is_selected, $full_attribute_name, $variation_in_stock, $stock_class, $disabled);
                                } else {
                                    // Fallback if function doesn't exist
                                    $color_hex = function_exists('get_color_hex_code') ? get_color_hex_code($option) : '#ddd';
                                    ?>
                                    <div class="<?php echo esc_attr($option_class . ' ' . ($is_selected ? 'active' : '') . ' ' . $stock_class); ?>" 
                                         data-value="<?php echo esc_attr($option); ?>"
                                         data-attribute="<?php echo esc_attr($full_attribute_name); ?>"
                                         title="<?php echo esc_attr($variation_in_stock ? $option : 'Out of stock: ' . $option); ?>"
                                         style="background-color: <?php echo esc_attr($color_hex); ?>; <?php echo esc_attr($disabled); ?>">
                                        <span class="color-name"><?php echo esc_html($option); ?></span>
                                    </div>
                                    <?php
                                }
                            endforeach;
                        }
                        ?>
                    </div>
                </div>
                <?php 
                    endforeach;
                endif;
                ?>
                
                <div class="shipping-info">
                Orders placed now will be shipped within 3-7 business days.
                </div>

                <?php if (has_term('Hire', 'product_cat')) : ?>
               <div class="shipping-info">
                    Hire products only available in-store.
                </div>
                <?php endif; ?>
                
                <?php render_size_guide_button(); ?>
                
                <div class="action-buttons">
                    <div class="quantity-container">
                        <button class="quantity-btn minus">-</button>
                        <input type="text" class="quantity-input" value="1" name="quantity">
                        <button class="quantity-btn plus">+</button>
                    </div>

                    <?php if ($product->is_type('variable') && !$is_hire): ?>
                        <button class="add-to-cart" id="custom-add-to-cart-btn">
                            ADD TO CART
                        </button>
                    <?php elseif(!$is_hire): ?>
                        <button class="add-to-cart" onclick="addToCartSimpleProduct(<?php echo esc_attr($product->get_id()); ?>, jQuery('.quantity-input').val())">
                            ADD TO CART
                        </button>
                    <?php endif; ?>
                    
   
                    <a href="/book-your-free-fitting"><button class="appointment-btn">BOOK APPOINTMENT</button></a>
               
                    <div class="add-to-cart wishlist-btn">
                        <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
                    </div>
                </div>
                
                <!-- Hidden WooCommerce form for cart functionality -->
                <div style="display: none;" class="woo-cart-form-wrapper">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
                
                <div class="shipping-features">
                    <div class="shipping-feature">
                        <img src="https://cdn.myikas.com/images/theme-images/71932194-a6be-49ad-af5f-0a2547861719/image_180.webp" alt="Shipping Icon" class="shipping-icon">
                        <div>Nationwide Shipping</div>
                    </div>
                    <div class="shipping-feature">
                        <i class="fa-regular fa-clock"></i>
                        <div>3-7 Day Delivery</div>
                    </div>
                    <div class="shipping-feature">
                        <?php
                    echo '<img  src="' . esc_url(get_stylesheet_directory_uri()) . '/assets/imgs/service.png" alt="Customer Service" class="shipping-icon"/>';
                   ?>
                    <div>Customer Service Support</div> 
                    </div>
                </div>
                
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
 
        <div class="related-products">
            <h2>Recently Viewed</h2>
                
            <section class="container my-5">
                <div class="row g-4">
                    <?php
                    
                    // Get recently viewed products from cookie
                    $viewed_products = !empty($_COOKIE['woocommerce_recently_viewed']) ? (array) explode('|', $_COOKIE['woocommerce_recently_viewed']) : array();

                    // Remove current product from viewed list
                    $viewed_products = array_diff($viewed_products, array($product->get_id()));

                    // Get the most recent 4 products (in reverse order)
                    $viewed_products = array_reverse(array_slice($viewed_products, -4));

                    // Check if we have viewed products
                    if (!empty($viewed_products)) {
                        $args = array(
                            'post_type'      => 'product',
                            'post_status'    => 'publish',
                            'post__in'       => $viewed_products,
                            'orderby'        => 'post__in',
                            'posts_per_page' => 4,
                        );
                    } else {
                        // If no recently viewed products, get products from the same category as current product
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
                            $related_product = wc_get_product(get_the_ID());
                            
                            // Skip if not a valid product
                            if (!is_a($related_product, 'WC_Product')) {
                                continue;
                            }
                            
                            // Get product data safely
                            $product_id = $related_product->get_id();
                            $regular_price = $related_product->get_regular_price();
                            $sale_price = $related_product->get_sale_price();
                            $is_on_sale = $related_product->is_on_sale();
                            $discount_percentage = 0;
                            
                            if ($is_on_sale && $regular_price > 0) {
                                $discount_percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
                            }
                            
                            // First gallery image as hover image
                            $attachment_ids = $related_product->get_gallery_image_ids();
                            $hover_image = '';
                            if (!empty($attachment_ids)) {
                                $hover_image = wp_get_attachment_image_url($attachment_ids[0], 'large');
                            }
                            
                            // Check if product is in stock
                            $in_stock = $related_product->is_in_stock();
                            
                            // Always show NEW label for simplicity
                            $is_new = function_exists('is_product_new') ? is_product_new($product_id) : false;


                            
                            // Get product title
                            $product_title = get_the_title();
                            
                            // Get product brand from pa_brand taxonomy
                            $product_brand = '';
                            $brand_terms = get_the_terms($product_id, 'pa_brand');
                            if (!empty($brand_terms) && !is_wp_error($brand_terms)) {
                                $product_brand = $brand_terms[0]->name;
                            }
                            
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
                            ?>
                            <div class="col-md-3">
                                <a href="<?php echo esc_url(get_permalink()); ?>" class="product-card-link">
                                    <div class="product-card">
                                        <div class="image-container">
                                            
                                            <?php if ($is_new) : ?>
                                            <div class="label new-label <?php echo $newlabel_style;?>">NEW</div>
                                            <?php endif; ?>
                                            <?php if (!$in_stock) : ?>
                                                <span class="label sold-out-label" style="right: 10px">Sold Out</span>
                                            <?php endif; ?>
                                            
                                            <?php if (has_post_thumbnail()) : ?>
                                                <img
                                                src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>"
                                                class="product-image"
                                                alt="<?php echo esc_attr($product_title); ?>"
                                                />
                                            <?php else : ?>
                                                <img
                                                src="<?php echo wc_placeholder_img_src(); ?>"
                                                class="product-image"
                                                alt="<?php echo esc_attr($product_title); ?>"
                                                />
                                            <?php endif; ?>
                                            
                                            <?php if ($hover_image) : ?>
                                                <img
                                                src="<?php echo esc_url($hover_image); ?>"
                                                class="hover-image"
                                                alt="<?php echo esc_attr($product_title); ?> Hover"
                                                />
                                            <?php elseif (has_post_thumbnail()) : ?>
                                                <img
                                                src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>"
                                                class="hover-image"
                                                alt="<?php echo esc_attr($product_title); ?> Hover"
                                                />
                                            <?php endif; ?>
                                            
                                            <span class="add-to-cart-btn">SHOP NOW</span>
                                        </div>
                                        
                                        <?php if (!empty($dress_name)) : ?>
                                            <h4 class="mt-3 tech-spec-title"><?php echo esc_html($dress_name); ?></h4>
                                            <h5 class="product-title-with-spec"><?php echo esc_html($product_title); ?></h5>
                                        <?php else : ?>
                                            <h5 class="mt-3 fw-bold"><?php echo esc_html($product_title); ?></h5>
                                        <?php endif; ?>
                                        
                                        <p class="product-description"><?php echo wp_trim_words($related_product->get_short_description(), 10, '...'); ?></p>
                                        
                                        <?php if ($is_on_sale && $discount_percentage > 0) : ?>
                                            <span class="discount-box"><?php echo esc_html($discount_percentage); ?>%</span>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($product_brand)) : ?>
                                            <p class="product-brand"><?php echo esc_html($product_brand); ?></p>
                                        <?php endif; ?>
                                        
                                        <p class="product-pricing">
                                            <?php echo $related_product->get_price_html(); ?>
                                        </p>
                                    </div>
                                </a>
                            </div>
                            <?php
                        }
                        wp_reset_postdata();
                    } else {
                        // If no products were found in the same category, show random products
                        $args = array(
                            'post_type'      => 'product',
                            'post_status'    => 'publish',
                            'post__not_in'   => array($product->get_id()),
                            'posts_per_page' => 4,
                            'orderby'        => 'rand',
                        );
                        
                        $random_query = new WP_Query($args);
                        
                        if ($random_query->have_posts()) {
                            while ($random_query->have_posts()) {
                                $random_query->the_post();
                                $random_product = wc_get_product(get_the_ID());
                                
                                // Display the random product (same code as above)
                                // Skip if not a valid product
                                if (!is_a($random_product, 'WC_Product')) {
                                    continue;
                                }
                                
                                // Get product data safely
                                $product_id = $random_product->get_id();
                                $regular_price = $random_product->get_regular_price();
                                $sale_price = $random_product->get_sale_price();
                                $is_on_sale = $random_product->is_on_sale();
                                $discount_percentage = 0;
                                
                                if ($is_on_sale && $regular_price > 0) {
                                    $discount_percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
                                }
                                
                                // First gallery image as hover image
                                $attachment_ids = $random_product->get_gallery_image_ids();
                                $hover_image = '';
                                if (!empty($attachment_ids)) {
                                    $hover_image = wp_get_attachment_image_url($attachment_ids[0], 'large');
                                }
                                
                                // Check if product is in stock
                                $in_stock = $random_product->is_in_stock();
                                
                                // Get product title
                                $product_title = get_the_title();
                                $is_new = function_exists('is_product_new') ? is_product_new($product_id) : false; 
                                // Get product brand from pa_brand taxonomy
                                $product_brand = '';
                                $brand_terms = get_the_terms($product_id, 'pa_brand');
                                if (!empty($brand_terms) && !is_wp_error($brand_terms)) {
                                    $product_brand = $brand_terms[0]->name;
                                }
                                
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
                                    if (!empty($meta_dress_name) && $meta_dress_name !== '{{product.meta_dress name}}') {
                                        $dress_name = $meta_dress_name;
                                    }
                                }
                                ?>
                                <div class="col-md-3">
                                    <a href="<?php echo esc_url(get_permalink()); ?>" class="product-card-link">
                                        <div class="product-card">
                                            <div class="image-container">
                                                
                                                <?php if ($is_new) : ?>
                                                <div class="label new-label <?php echo $newlabel_style;?>">NEW</div>
                                                <?php endif; ?>
                                                <?php if (!$in_stock) : ?>
                                                    <span class="label sold-out-label" style="right: 10px">Sold Out</span>
                                                <?php endif; ?>
                                                
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <img
                                                    src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>"
                                                    class="product-image"
                                                    alt="<?php echo esc_attr($product_title); ?>"
                                                    />
                                                <?php else : ?>
                                                    <img
                                                    src="<?php echo wc_placeholder_img_src(); ?>"
                                                    class="product-image"
                                                    alt="<?php echo esc_attr($product_title); ?>"
                                                    />
                                                <?php endif; ?>
                                                
                                                <?php if ($hover_image) : ?>
                                                    <img
                                                    src="<?php echo esc_url($hover_image); ?>"
                                                    class="hover-image"
                                                    alt="<?php echo esc_attr($product_title); ?> Hover"
                                                    />
                                                <?php elseif (has_post_thumbnail()) : ?>
                                                    <img
                                                    src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>"
                                                    class="hover-image"
                                                    alt="<?php echo esc_attr($product_title); ?> Hover"
                                                    />
                                                <?php endif; ?>
                                                
                                                <span class="add-to-cart-btn">SHOP NOW</span>
                                            </div>
                                            
                                            <?php if (!empty($dress_name)) : ?>
                                                <h4 class="mt-3 tech-spec-title"><?php echo esc_html($dress_name); ?></h4>
                                                <h5 class="product-title-with-spec"><?php echo esc_html($product_title); ?></h5>
                                            <?php else : ?>
                                                <h5 class="mt-3 fw-bold"><?php echo esc_html($product_title); ?></h5>
                                            <?php endif; ?>
                                            
                                            <p class="product-description"><?php echo wp_trim_words($random_product->get_short_description(), 10, '...'); ?></p>
                                            
                                            <?php if ($is_on_sale && $discount_percentage > 0) : ?>
                                                <span class="discount-box"><?php echo esc_html($discount_percentage); ?>%</span>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($product_brand)) : ?>
                                                <p class="product-brand"><?php echo esc_html($product_brand); ?></p>
                                            <?php endif; ?>
                                            
                                            <p class="product-pricing">
                                                <?php echo $random_product->get_price_html(); ?>
                                            </p>
                                        </div>
                                    </a>
                                </div>
                                <?php
                            }
                            wp_reset_postdata();
                        }
                    }
                    ?>
                </div>
            </section>
        </div>
        <hr>
        <?php bride_co_render_accessories_child_categories(); ?>
        <//?php bride_co_render_categories(); ?>
    </div>

    <?php
    // Get the current product object
    global $product;

    // Get the categories of the current product
    $categories = wp_get_post_terms($product->get_id(), 'product_cat');

    // Check if the product has categories assigned
    if (!empty($categories) && !is_wp_error($categories)) {
        // Get the first category ID
        $category_id = $categories[0]->term_id;

        // Retrieve the size guide image from ACF (custom field for product categories)
        $size_guide_image = get_field('size_guide', 'product_cat_' . $category_id);

        // Check if an image is set
        if ($size_guide_image) {
            $image_url = $size_guide_image['url']; // The image URL
        } else {
            $image_url = ''; // No image, you can set a default or fallback
        }
    }
    ?>

    <!-- Size Guide Modal -->
    <div id="sizeGuideModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeSizeGuideModal()">&times;</span>
            <h2>Size Guide</h2>
            <p>Include your size guide image or table here.</p>
            
            <!-- Add the image dynamically -->
            <?php if ($image_url): ?>
                <img src="<?php echo esc_url($image_url); ?>" alt="Size Guide">
            <?php else: ?>
                <p>No size guide available for this product category.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
    // Manual debug function - call debugVariations() in the browser console
    window.debugVariations = function() {
        console.log('=== MANUAL DEBUG FUNCTION ===');
        console.log('Current variations array:', window.product_variations || variations);
        console.log('WooCommerce form variations:', jQuery('.variations_form').data('product_variations'));
        console.log('Form HTML data attribute:', jQuery('.variations_form').attr('data-product_variations'));
        console.log('Available size options:');
        jQuery('.size-option').each(function() {
            console.log('- Size:', jQuery(this).data('value'), 'Active:', jQuery(this).hasClass('active'));
        });
        console.log('Available color options:');
        jQuery('.color-option').each(function() {
            console.log('- Color:', jQuery(this).data('value'), 'Out of stock:', jQuery(this).hasClass('out-of-stock'));
        });
    };

    // Manual test function - call testSizeSelection('YOUR_SIZE') in the browser console
    window.testSizeSelection = function(testSize) {
        console.log('=== TESTING SIZE SELECTION ===');
        console.log('Testing size:', testSize);
        
        var availableColors = [];
        var outOfStockColors = [];
        var variations = window.product_variations || [];
        
        variations.forEach(function(variation) {
            // Get all possible size and color values
            var allSizeKeys = Object.keys(variation.attributes).filter(key => 
                key.toLowerCase().includes('size')
            );
            var allColorKeys = Object.keys(variation.attributes).filter(key => 
                key.toLowerCase().includes('color') || key.toLowerCase().includes('colour')
            );
            
            var variationSize = null;
            var variationColor = null;
            
            // Find size value
            for (var i = 0; i < allSizeKeys.length; i++) {
                if (variation.attributes[allSizeKeys[i]]) {
                    variationSize = variation.attributes[allSizeKeys[i]];
                    break;
                }
            }
            
            // Find color value
            for (var i = 0; i < allColorKeys.length; i++) {
                if (variation.attributes[allColorKeys[i]]) {
                    variationColor = variation.attributes[allColorKeys[i]];
                    break;
                }
            }
            
            console.log('Variation check - Size:', variationSize, 'Color:', variationColor, 'Stock:', variation.is_in_stock);
            
            if (variationSize === testSize && variationColor) {
                if (variation.is_in_stock) {
                    availableColors.push(variationColor);
                } else {
                    outOfStockColors.push(variationColor);
                }
            }
        });
        
        console.log('Results for size', testSize + ':');
        console.log('Available colors:', availableColors);
        console.log('Out of stock colors:', outOfStockColors);
        
        return {
            available: availableColors,
            outOfStock: outOfStockColors
        };
    };

    // Enhanced Size-Color Filtering System
    jQuery(document).ready(function($) {
        // Fix for the invalid form control error
        function fixQuantityInputValidation() {
            $('input[type="number"][name="quantity"]').each(function() {
                const $input = $(this);
                
                // Make sure the input has proper attributes to be valid
                if (!$input.attr('min')) $input.attr('min', '1');
                
                // Remove any validation-breaking attributes (if they exist)
                $input.attr('tabindex', '1'); // Ensure it's focusable
                
                // Ensure the value is valid compared to min/max
                const min = parseInt($input.attr('min')) || 1;
                const max = parseInt($input.attr('max'));
                let val = parseInt($input.val()) || min;
                
                if (val < min) $input.val(min);
                if (max && val > max) $input.val(max);
            });
        }
        
        // Run this on page load and after AJAX events
        fixQuantityInputValidation();
        $(document).on('found_variation updated_wc_div', fixQuantityInputValidation);
        
        // Get variations data - this should be localized from PHP
        var variations = [];
        
        // Try to get variations from multiple sources
        if (typeof window.product_variations !== 'undefined') {
            variations = window.product_variations;
            console.log('✓ Got variations from window.product_variations');
        } else if (typeof product_variations !== 'undefined') {
            variations = product_variations;
            console.log('✓ Got variations from product_variations');
        } else if (typeof wc_single_product_params !== 'undefined' && wc_single_product_params.variations) {
            variations = wc_single_product_params.variations;
            console.log('✓ Got variations from wc_single_product_params');
        } else if ($('form.variations_form').length) {
            // Fallback: try to get from the variations form
            variations = $('form.variations_form').data('product_variations') || [];
            console.log('✓ Got variations from variations form data');
        } else {
            console.log('✗ Could not find variations data from any source');
        }
        
        console.log('=== VARIATIONS DEBUG INFO ===');
        console.log('Total variations found:', variations.length);
        console.log('Full variations data:', variations);
        
        // If still no variations, try to extract from the form's JSON data
        if (variations.length === 0 && $('.variations_form').length) {
            var formData = $('.variations_form').attr('data-product_variations');
            if (formData) {
                try {
                    variations = JSON.parse(formData);
                    console.log('✓ Got variations from form data-product_variations attribute');
                    console.log('Parsed variations:', variations);
                } catch (e) {
                    console.log('✗ Could not parse variations from form data:', e);
                }
            }
        }
        
        // Function to update color availability based on selected size
        function updateColorAvailability(selectedSize) {
            if (!selectedSize || variations.length === 0) {
                // If no size selected or no variations, show all colors as available
                $('.color-option').removeClass('out-of-stock disabled').css({
                    'pointer-events': '',
                    'opacity': '',
                    'text-decoration': ''
                });
                return;
            }
            
            // Get available colors for the selected size
            var availableColors = [];
            var outOfStockColors = [];
            
            console.log('=== DEBUGGING SIZE SELECTION ===');
            console.log('Selected size:', selectedSize);
            console.log('Total variations:', variations.length);
            
            variations.forEach(function(variation, index) {
                console.log('--- Variation ' + index + ' ---');
                console.log('Full variation object:', variation);
                console.log('Attributes object:', variation.attributes);
                
                // Get all possible size attribute values
                var possibleSizes = [
                    variation.attributes.attribute_pa_size,
                    variation.attributes['attribute_pa_size'],
                    variation.attributes.attribute_size,
                    variation.attributes['attribute_size'],
                    variation.attributes.size,
                    variation.attributes['pa_size']
                ];
                
                // Get all possible color attribute values
                var possibleColors = [
                    variation.attributes.attribute_pa_color,
                    variation.attributes['attribute_pa_color'],
                    variation.attributes.attribute_pa_colour,
                    variation.attributes['attribute_pa_colour'],
                    variation.attributes.attribute_color,
                    variation.attributes['attribute_color'],
                    variation.attributes.attribute_colour,
                    variation.attributes['attribute_colour'],
                    variation.attributes.color,
                    variation.attributes.colour,
                    variation.attributes['pa_color'],
                    variation.attributes['pa_colour']
                ];
                
                // Find the first non-empty value
                var variationSize = possibleSizes.find(function(val) { return val && val !== ''; });
                var variationColor = possibleColors.find(function(val) { return val && val !== ''; });
                
                console.log('Detected size:', variationSize);
                console.log('Detected color:', variationColor);
                console.log('Stock status - is_in_stock:', variation.is_in_stock);
                console.log('Stock status - stock_quantity:', variation.stock_quantity);
                console.log('Stock status - max_qty:', variation.max_qty);
                console.log('Stock status - min_qty:', variation.min_qty);
                console.log('Stock status - is_purchasable:', variation.is_purchasable);
                
                // Check if this variation matches the selected size
                if (variationSize === selectedSize && variationColor) {
                    console.log('✓ Size matches! Checking stock...');
                    
                    // More comprehensive stock checking
                    var isInStock = false;
                    
                    // Check multiple stock indicators
                    if (variation.is_in_stock === true || variation.is_in_stock === 1 || variation.is_in_stock === 'true') {
                        isInStock = true;
                    }
                    
                    // Also check if stock quantity is available (if it exists)
                    if (variation.stock_quantity !== undefined && variation.stock_quantity !== null) {
                        isInStock = isInStock && variation.stock_quantity > 0;
                    }
                    
                    // Also check max_qty if it exists
                    if (variation.max_qty !== undefined && variation.max_qty !== null) {
                        isInStock = isInStock && variation.max_qty > 0;
                    }
                    
                    console.log('Final stock decision:', isInStock);
                    
                    if (isInStock) {
                        availableColors.push(variationColor);
                        console.log('✓ Added to available colors');
                    } else {
                        outOfStockColors.push(variationColor);
                        console.log('✗ Added to out of stock colors');
                    }
                } else {
                    console.log('✗ Size does not match or no color');
                }
                
                console.log(''); // Empty line for readability
            });
            
            console.log('=== FINAL RESULTS ===');
            console.log('Available colors for size ' + selectedSize + ':', availableColors);
            console.log('Out of stock colors for size ' + selectedSize + ':', outOfStockColors);
            
            // Update each color option
            $('.color-option').each(function() {
                var $colorOption = $(this);
                var thisColor = $colorOption.data('value');
                
                if (availableColors.includes(thisColor)) {
                    // Color is available - remove out of stock styling
                    $colorOption.removeClass('out-of-stock disabled')
                               .css({
                                   'pointer-events': '',
                                   'opacity': '',
                                   'text-decoration': '',
                                   'position': 'relative'
                               })
                               .attr('title', thisColor);
                               
                    // Remove any strike-through elements
                    $colorOption.find('.strike-through').remove();
                    
                } else if (outOfStockColors.includes(thisColor)) {
                    // Color exists but is out of stock - add strike-through
                    $colorOption.addClass('out-of-stock')
                               .css({
                                   'pointer-events': 'none',
                                   'opacity': '0.6',
                                   'position': 'relative'
                               })
                               .attr('title', 'Out of stock: ' + thisColor);
                               
                    // Add strike-through line if not already present
                    if (!$colorOption.find('.strike-through').length) {
                        $colorOption.append('<div class="strike-through"></div>');
                    }
                    
                } else {
                    // Color doesn't exist in this size - hide or disable completely
                    $colorOption.addClass('out-of-stock disabled')
                               .css({
                                   'pointer-events': 'none',
                                   'opacity': '0.3',
                                   'position': 'relative'
                               })
                               .attr('title', 'Not available in selected size');
                               
                    // Add strike-through line if not already present
                    if (!$colorOption.find('.strike-through').length) {
                        $colorOption.append('<div class="strike-through"></div>');
                    }
                }
            });
            
            // If a color is currently selected but not available, deselect it
            var $selectedColor = $('.color-option.active');
            if ($selectedColor.length && $selectedColor.hasClass('out-of-stock')) {
                $selectedColor.removeClass('active');
                
                // Clear the WooCommerce variation selection
                var colorAttributeName = $selectedColor.data('attribute');
                if (colorAttributeName) {
                    $('select[name="' + colorAttributeName + '"]').val('').trigger('change');
                }
            }
        }
        
        // Handle size selection
        $('.size-option').on('click', function() {
            // Don't proceed if this size is out of stock
            if ($(this).hasClass('out-of-stock') || $(this).css('pointer-events') === 'none') {
                return;
            }
            
            var selectedSize = $(this).data('value');
            
            // Update UI - mark this size as selected
            $('.size-option').removeClass('active');
            $(this).addClass('active');
            
            // Update WooCommerce variation form
            var sizeAttributeName = $(this).data('attribute');
            if (sizeAttributeName) {
                var $sizeSelect = $('select[name="' + sizeAttributeName + '"]');
                if ($sizeSelect.length) {
                    $sizeSelect.val(selectedSize).trigger('change');
                }
            }
            
            // Update color availability
            updateColorAvailability(selectedSize);
            
            console.log('Size selected:', selectedSize); // Debug log
        });
        
        // Handle color selection
        $('.color-option').on('click', function() {
            // Don't proceed if this color is out of stock or disabled
            if ($(this).hasClass('out-of-stock') || $(this).css('pointer-events') === 'none') {
                return;
            }
            
            var selectedColor = $(this).data('value');
            
            // Update UI - mark this color as selected
            $('.color-option').removeClass('active');
            $(this).addClass('active');
            
            // Update WooCommerce variation form
            var colorAttributeName = $(this).data('attribute');
            if (colorAttributeName) {
                var $colorSelect = $('select[name="' + colorAttributeName + '"]');
                if ($colorSelect.length) {
                    $colorSelect.val(selectedColor).trigger('change');
                }
            }
            
            console.log('Color selected:', selectedColor); // Debug log
        });
        
        // Listen for WooCommerce variation changes to sync our UI
        $('.variations_form').on('found_variation', function(event, variation) {
            console.log('WooCommerce variation found:', variation);
            
            // Update quantity limits
            var $quantityInput = $('.quantity-input');
            if (variation.max_qty) {
                $quantityInput.attr('max', variation.max_qty);
            } else {
                $quantityInput.removeAttr('max');
            }
        });
        
        $('.variations_form').on('hide_variation', function() {
            console.log('WooCommerce variation hidden');
            $('.quantity-input').removeAttr('max');
        });
        
        // Initialize on page load
        setTimeout(function() {
            // Auto-select first available size if none selected
            if (!$('.size-option.active').length) {
                var $firstAvailableSize = $('.size-option').not('.out-of-stock').first();
                if ($firstAvailableSize.length) {
                    $firstAvailableSize.click();
                }
            } else {
                // If a size is already selected, update colors
                var selectedSize = $('.size-option.active').data('value');
                updateColorAvailability(selectedSize);
            }
        }, 100);

        // Handle variable product add to cart
        $('#custom-add-to-cart-btn').on('click', function(e) {
            e.preventDefault();
            
            // Fix quantity validation before proceeding
            fixQuantityInputValidation();
            
            // Check if all required variation attributes are selected
            const $variationForm = $('form.variations_form');
            let allSelected = true;
            let missingAttributes = [];
            
            if ($variationForm.length) {
                $variationForm.find('select[name^="attribute_"]').each(function() {
                    if ($(this).val() === '') {
                        allSelected = false;
                        // Get the attribute name in a readable format
                        const fullAttributeName = $(this).attr('name');
                        const attributeName = fullAttributeName.replace('attribute_', '');
                        const readableName = attributeName.replace('pa_', '').replace(/-/g, ' ');
                        
                        missingAttributes.push({
                            name: readableName,
                            fullName: fullAttributeName
                        });
                    }
                });
                
                if (!allSelected) {
                    // Construct a helpful message for the user
                    let message = 'Please select the following before adding to cart:\n';
                    missingAttributes.forEach(attr => {
                        message += '- ' + attr.name.charAt(0).toUpperCase() + attr.name.slice(1) + '\n';
                        
                        // Try to highlight the corresponding UI element
                        const attrLower = attr.name.toLowerCase();
                        
                        if (attrLower.includes('color')) {
                            $('.color-selection').addClass('highlight-selection');
                            setTimeout(function() { $('.color-selection').removeClass('highlight-selection'); }, 2000);
                        } else if (attrLower.includes('size')) {
                            $('.size-selection').addClass('highlight-selection');
                            setTimeout(function() { $('.size-selection').removeClass('highlight-selection'); }, 2000);
                        } else {
                            // For any other attribute type, try to find a containing element
                            $('[data-attribute="' + attr.fullName + '"]').addClass('highlight-selection');
                            setTimeout(function() { 
                                $('[data-attribute="' + attr.fullName + '"]').removeClass('highlight-selection'); 
                            }, 2000);
                        }
                    });
                    
                    alert(message);
                    return;
                }
            }
            
            if (allSelected) {
                // Check quantity limits
                const $quantityInput = $('.quantity-input');
                const quantity = parseInt($quantityInput.val());
                const max = parseInt($quantityInput.attr('max'));
                
                // Validate quantity before submission
                if (max && quantity > max) {
                    alert('The desired quantity is not available');
                    return;
                }
                
                // Set the quantity in the hidden form
                $('form.cart .quantity input').val(quantity);
                
                // Click the hidden WooCommerce add to cart button
                $('form.cart .single_add_to_cart_button').click();
            }
        });
        
        // Make sure the quantity is synchronized between our UI and WooCommerce's form
        $('.quantity-input').on('change', function() {
            const quantity = $(this).val();
            $('form.cart .quantity input').val(quantity);
        });

        // Also sync WooCommerce's quantity attributes to our custom input
        function syncQuantityAttributes() {
            const $wooQuantity = $('form.cart .quantity input');
            const $customQuantity = $('.quantity-input');
            
            if ($wooQuantity.length && $customQuantity.length) {
                // Copy attributes
                const min = $wooQuantity.attr('min');
                const max = $wooQuantity.attr('max');
                const step = $wooQuantity.attr('step');
                
                if (min) $customQuantity.attr('min', min);
                if (max) $customQuantity.attr('max', max);
                if (step) $customQuantity.attr('step', step);
            }
        }
        
        // Run sync on variation changes
        $(document).on('found_variation', function() {
            syncQuantityAttributes();
            fixQuantityInputValidation();
        });
    });

    // Function to add simple product to cart via AJAX
    function addToCartSimpleProduct(product_id, quantity) {
        // Validate quantity before submission
        const $quantityInput = jQuery('.quantity-input, form.cart .quantity input').first();
        const max = parseInt($quantityInput.attr('max'));
        quantity = parseInt(quantity);
        
        // If max exists and quantity exceeds it, show message and abort
        if (max && quantity > max) {
            alert('The desired quantity is not available');
            return;
        }
        
        jQuery.ajax({
            type: 'POST',
            url: wc_add_to_cart_params.ajax_url,
            data: {
                action: 'add_to_cart',
                product_id: product_id,
                quantity: quantity
            },
            success: function(response) {
                if (response.error) {
                    // Check for stock-related error message
                    if (response.error.indexOf('You cannot add that amount') >= 0) {
                        alert('The desired quantity is not available');
                    } else {
                        alert(response.error);
                    }
                } else if (response.success) {
                    // Show success message
                    alert('Product added to cart!');
                    
                    // Optional: Refresh cart fragments
                    jQuery(document.body).trigger('wc_fragment_refresh');
                } else {
                    alert('Error adding product to cart. Please try again.');
                }
            },
            error: function() {
                alert('Error adding product to cart. Please try again.');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Product details accordion
        const detailsHeader = document.querySelector('.product-details h3');
        const detailsContent = document.querySelector('.product-details-content');
        
        detailsHeader.addEventListener('click', function() {
            const icon = this.querySelector('i');
            
            if (detailsContent.style.display === 'none') {
                detailsContent.style.display = 'block';
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-minus');
            } else {
                detailsContent.style.display = 'none';
                icon.classList.remove('fa-minus');
                icon.classList.add('fa-plus');
            }
        });
        
        // Returns accordion toggle
        const returnsHeader = document.querySelector('.returns-container h3');
        
        returnsHeader.addEventListener('click', function() {
            const icon = this.querySelector('i');
            let contentDiv = this.parentNode.querySelector('.product-details-content');
            
            if (!contentDiv) {
                contentDiv = document.createElement('div');
                contentDiv.className = 'product-details-content';
                contentDiv.innerHTML = `
                    <p>When purchasing from Complete Bride & Co, products in the discount category that don't involve alterations like hemming and resizing, can be exchanged. For this, you need to make an appointment 3-7 days in advance.</p>
                    <p>Returns and exchanges are not available for altered items.</p>
                `;
                this.after(contentDiv);
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-minus');
            } else {
                contentDiv.remove();
                icon.classList.remove('fa-minus');
                icon.classList.add('fa-plus');
            }
        });
        
        // Thumbnail click functionality
        const thumbnails = document.querySelectorAll('.thumbnail');
        const mainImage = document.querySelector('.main-image img');
        
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                // Remove active class from all thumbnails
                thumbnails.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked thumbnail
                this.classList.add('active');
                
                // Update main image
                const thumbnailImg = this.querySelector('img');
                mainImage.src = thumbnailImg.src;
            });
        });
        
        // Generic attribute options (handled by the enhanced jQuery code above)
        // But keeping this for any non-size/non-color attributes
        const attributeOptions = document.querySelectorAll('.attribute-option');
        
        attributeOptions.forEach(option => {
            if (!option.classList.contains('color-option') && !option.classList.contains('size-option')) {
                option.addEventListener('click', function() {
                    // Remove active class from siblings
                    const parent = this.parentNode;
                    parent.querySelectorAll('.attribute-option').forEach(o => {
                        o.classList.remove('active');
                    });
                    
                    // Add active class to clicked option
                    this.classList.add('active');
                    
                    // For WooCommerce - update variation selection
                    const attributeName = this.getAttribute('data-attribute');
                    const value = this.getAttribute('data-value');
                    updateVariationForm(attributeName, value);
                });
            }
        });
        
        // Function to update the hidden WooCommerce variation form
        function updateVariationForm(attributeName, value) {
            const variationsForm = document.querySelector('form.variations_form');
            
            if (variationsForm) {
                const select = variationsForm.querySelector('select[name="' + attributeName + '"]');
                
                if (select) {
                    select.value = value;
                    
                    // Trigger change event
                    const event = new Event('change');
                    select.dispatchEvent(event);
                }
            }
        }
        
        // Quantity buttons with improved validation
        const minusBtn = document.querySelector('.minus');
        const plusBtn = document.querySelector('.plus');
        const quantityInput = document.querySelector('.quantity-input');
        
        if (minusBtn && plusBtn && quantityInput) {
            minusBtn.addEventListener('click', function() {
                let quantity = parseInt(quantityInput.value);
                const min = parseInt(quantityInput.getAttribute('min')) || 1;
                
                if (quantity > min) {
                    quantityInput.value = quantity - 1;
                    
                    // Update hidden WooCommerce quantity input
                    const wooQuantity = document.querySelector('form.cart .quantity input');
                    if (wooQuantity) {
                        wooQuantity.value = quantity - 1;
                        
                        // Trigger change event
                        const event = new Event('change');
                        wooQuantity.dispatchEvent(event);
                    }
                }
            });
            
            plusBtn.addEventListener('click', function() {
                let quantity = parseInt(quantityInput.value);
                const max = parseInt(quantityInput.getAttribute('max'));
                
                // Check if we have a max limit and are already at it
                if (max && quantity >= max) {
                    alert('The desired quantity is not available');
                    return;
                }
                
                quantityInput.value = quantity + 1;
                
                // Update hidden WooCommerce quantity input
                const wooQuantity = document.querySelector('form.cart .quantity input');
                if (wooQuantity) {
                    wooQuantity.value = quantity + 1;
                    
                    // Trigger change event
                    const event = new Event('change');
                    wooQuantity.dispatchEvent(event);
                }
            });
            
            // Validate quantity input on manual change
            quantityInput.addEventListener('change', function() {
                let quantity = parseInt(this.value);
                const min = parseInt(this.getAttribute('min')) || 1;
                const max = parseInt(this.getAttribute('max'));
                
                // Enforce minimum
                if (isNaN(quantity) || quantity < min) {
                    this.value = min;
                    quantity = min;
                }
                
                // Enforce maximum if it exists
                if (max && quantity > max) {
                    this.value = max;
                    quantity = max;
                    alert('The desired quantity is not available');
                }
                
                // Update hidden WooCommerce quantity input
                const wooQuantity = document.querySelector('form.cart .quantity input');
                if (wooQuantity) {
                    wooQuantity.value = quantity;
                    
                    // Trigger change event
                    const event = new Event('change');
                    wooQuantity.dispatchEvent(event);
                }
            });
        }
        
        // Favorite button
        const favoriteBtn = document.querySelector('.add-to-favorites');
        
        favoriteBtn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            
            if (icon.classList.contains('fa-regular')) {
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid');
                icon.style.color = '#d9304f'; // Changed to red color to match the button
            } else {
                icon.classList.remove('fa-solid');
                icon.classList.add('fa-regular');
                icon.style.color = '#333';
            }
        });
        
        // Navigation arrows
        const prevArrow = document.querySelector('.prev');
        const nextArrow = document.querySelector('.next');
        
        prevArrow.addEventListener('click', function() {
            const activeThumb = document.querySelector('.thumbnail.active');
            const prevThumb = activeThumb.previousElementSibling;
            
            if (prevThumb && prevThumb.classList.contains('thumbnail')) {
                activeThumb.classList.remove('active');
                prevThumb.classList.add('active');
                
                const thumbnailImg = prevThumb.querySelector('img');
                mainImage.src = thumbnailImg.src;
            }
        });
        
        nextArrow.addEventListener('click', function() {
            const activeThumb = document.querySelector('.thumbnail.active');
            const nextThumb = activeThumb.nextElementSibling;
            
            if (nextThumb && nextThumb.classList.contains('thumbnail')) {
                activeThumb.classList.remove('active');
                nextThumb.classList.add('active');
                
                const thumbnailImg = nextThumb.querySelector('img');
                mainImage.src = thumbnailImg.src;
            }
        });
        
        // Add hover effects to product cards
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach(card => {
            const defaultImage = card.querySelector('.product-card-image img');
            if (defaultImage && !defaultImage.classList.contains('default-image') && !card.querySelector('.hover-image')) {
                // Add classes to existing image
                defaultImage.classList.add('default-image');
                
                // Create and add hover image if it doesn't exist
                const hoverImage = document.createElement('img');
                hoverImage.classList.add('hover-image');
                hoverImage.src = defaultImage.src.replace(/(.+?)(?=\.\w+$)/, '$1-hover'); // Create hover image name
                hoverImage.alt = "";
                defaultImage.parentElement.appendChild(hoverImage);
            }
        });
        
        // Make sure the product details are expanded by default (if they weren't already)
        if (!detailsContent.style.display || detailsContent.style.display === '') {
            detailsContent.style.display = 'block';
        }
        
        // Mobile-specific adjustments
        function handleResponsiveLayout() {
            const windowWidth = window.innerWidth;
            
            if (windowWidth <= 768) {
                // For mobile view, ensure horizontal scrolling works for thumbnails
                const thumbnailSidebar = document.querySelector('.thumbnail-sidebar');
                if (thumbnailSidebar) {
                    thumbnailSidebar.style.overflowX = 'auto';
                }
            }
        }
        
        // Run on load and resize
        handleResponsiveLayout();
        window.addEventListener('resize', handleResponsiveLayout);
        
        // Add smooth transitions for better UX
        document.querySelectorAll('.product-card, .thumbnail, .size-option, .add-to-favorites, .nav-arrow')
            .forEach(element => {
                element.style.transition = 'all 0.3s ease';
            });
    });

    function openSizeGuideModal() {
        document.getElementById("sizeGuideModal").style.display = "block";
    }
    function closeSizeGuideModal() {
        document.getElementById("sizeGuideModal").style.display = "none";
    }
    window.onclick = function(event) {
        const modal = document.getElementById("sizeGuideModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    </script>

<?php
endwhile; // end of the loop.

get_footer('shop'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sizeSelect = document.querySelector('select[name="attribute_pa_size"]');
    const colorOptions = document.querySelectorAll('.color-swatch'); // Adjust to match your HTML
    if (!sizeSelect || !colorOptions.length || !window.sizeColorAvailability) return;

    function updateColorsForSize(size) {
        colorOptions.forEach(option => {
            const color = option.dataset.color;
            const available = window.sizeColorAvailability[size]?.[color] ?? false;

            // Force show all options first
            option.classList.remove('disabled');
            option.style.display = 'inline-block';

            if (!available) {
                option.classList.add('unavailable');
            } else {
                option.classList.remove('unavailable');
            }
        });
    }

    // Hook into WooCommerce updates
    jQuery(document).on('woocommerce_update_variation_values', function () {
        if (sizeSelect.value) {
            updateColorsForSize(sizeSelect.value);
        }
    });

    // On size change
    sizeSelect.addEventListener('change', function () {
        updateColorsForSize(this.value);
    });

    // Initial
    if (sizeSelect.value) {
        updateColorsForSize(sizeSelect.value);
    }
});
</script>

<style>
.color-swatch.unavailable {
    opacity: 0.5;
    text-decoration: line-through;
    cursor: not-allowed;
}
</style>