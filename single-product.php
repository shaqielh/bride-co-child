<?php
/**
 * Enhanced Single Product Template with Dynamic Variations
 * FIXED: Color detection for pa_colour attribute
 * Updated: Complete color palette from Excel (Nov 2025)
 * UPDATED: Reordered variations - Colour displays before Size
 * 
 * @package Bride-co-child
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header('shop');

// Function to convert color names to hex codes
function get_color_hex_code($color_name, $sku = '') {
    // First check if this is a product with a SKU-based color
    if (!empty($sku)) {
        // SKU-based color mapping
        $sku_color_map = array(
            'SO01548' => '#2D2E3E', // Midnight
            'SO01549' => '#16474A', // Gem
            'SO01550' => '#0E0E0C', // Black
            'SO01553' => '#0E0E0C', // Black
            'SO01558' => '#0E0E0C', // Black
            'SO01557' => '#0E0E0C', // Black
            'SO01547' => '#0E0E0C', // Black
            'SO01551' => '#0E0E0C', // Black
            'SO01503' => '#0E0E0C', // Black
            'SO01490' => '#0E0E0C', // Black
            'SO01476' => '#0E0E0C', // Black
            'SO01443' => '#0E0E0C', // Black
            'SO01360' => '#0E0E0C', // Black
        );
        
        // Find if the SKU is in our mapping
        foreach ($sku_color_map as $sku_prefix => $hex_code) {
            if (strpos($sku, $sku_prefix) === 0) {
                return $hex_code;
            }
        }
    }
    
    // Complete color name mapping - Updated from Excel file (Nov 2025)
    $color_map = array(
        'apple' => '#99062a',
        'ballet' => '#ebb8af',
        'beige' => '#D4C3B9',
        'biscotti' => '#CFB6A3',
        'black' => '#171513',
        'black / silver' => '#000000',
        'black / white' => '#333333',
        'blue' => '#3c6c92',
        'blush' => '#e0cbc8',
        'bouquet' => '#e6addd',
        'brown' => '#916f50',
        'burgundy' => '#a81134',
        'charcoal' => '#2e2c2e',
        'chianti' => '#a85760',
        'chocolate' => '#473b3b',
        'cinnamon' => '#933b2b',
        'coral' => '#fc3558',
        'cream' => '#FFFDD0',
        'dark brown' => '#544131',
        'dusty sage' => '#beb86c',
        'ecru' => '#BCAEA2',
        'emerald' => '#2c5957',
        'eucalyptus' => '#9b9777',
        'forest green' => '#9cafa2',
        'fuchsia' => '#e32d58',
        'gem' => '#205052',
        'gold' => '#FFD700',
        'gray' => '#91A1B0',
        'green' => '#81711e',
        'grey' => '#91A1B0',
        'horizon' => '#00539f',
        'hot pink' => '#e90d88',
        'indigo' => '#4B0082',
        'iris' => '#d8cfe7',
        'ivory' => '#ddd3c3',
        'juniper' => '#205241',
        'lake' => '#769cb7',
        'latte tulip' => '#a27c6e',
        'lavender' => '#ab9ac4',
        'light blue' => '#c1c8e2',
        'marine' => '#1c2033',
        'maroon' => '#800000',
        'martini olive' => '#4c522f',
        'midnight' => '#2D2E3E',
        'mint' => '#c4e9d7',
        'multi' => '#d8edeb',
        'navy' => '#1D3253',
        'oatmeal' => '#cdc3a7',
        'orange' => '#FFA500',
        'pale yellow' => '#f2db8d',
        'pastel pink' => '#f4d4d5',
        'petal' => '#ebb7a6',
        'pink' => '#e4cbcf',
        'pistachio' => '#d1dbab',
        'plum' => '#491f44',
        'punch' => '#f52b56',
        'purple' => '#800080',
        'quartz' => '#c69791',
        'red' => '#FF0000',
        'rose pink' => '#e68597',
        'sage' => '#aaaa8c',
        'sand' => '#bf8f79',
        'sienna' => '#dd4917',
        'silver' => '#C0C0C0',
        'sndbqtflrl' => '#bf8f79',
        'sndvineflrl' => '#bf8f79',
        'sow' => '#000000',
        'steel blue' => '#6e7a8b',
        'tawny' => '#773f1e',
        'teal' => '#008080',
        'terracotta' => '#eba17f',
        'white' => '#FFFFFF',
        'whtbqtflrl' => '#e7e7e7',
        'wine' => '#571A25',
        'yellow' => '#FFFF00',
    );
    
    // Convert color name to lowercase for case-insensitive matching
    $color_name_lower = strtolower(trim($color_name));
    
    // Check for color in the map
    if (isset($color_map[$color_name_lower])) {
        return $color_map[$color_name_lower];
    }
    
    // Check if the color name already contains a hex code
    if (preg_match('/#([a-fA-F0-9]{3}){1,2}\b/', $color_name)) {
        return $color_name;
    }
    
    // Default fallback color (light grey)
    return '#DDDDDD';
}

// Enhanced size guide button with modal
function render_enhanced_size_guide_button() {
    global $product;
    
    $measuring_guide_url = '/fitting-guide';
    
    if ($product) {
        $terms = get_the_terms($product->get_id(), 'pa_brand');
        
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
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

// Main product loop
while (have_posts()) : the_post();
    global $product;
    
    // Get product data
    $attachment_ids = $product->get_gallery_image_ids();
    $main_image_id = $product->get_image_id();
    $main_image_url = wp_get_attachment_url($main_image_id);
    $fallback_image = 'https://stage.brideandco.co.za/wp-content/uploads/2022/05/cropped-BrideCo-Logo.png';
    
    $is_new = function_exists('is_product_new') ? is_product_new($product->get_id()) : false;
    $is_eurosuite = function_exists('is_eurosuit_page') ? is_eurosuit_page() : false;
    $newlabel_style = $is_eurosuite ? 'new-badge-euro' : '';
    
    // Get brand
    $brand = '';
    $brand_terms = get_the_terms($product->get_id(), 'pa_brand');
    if (!empty($brand_terms) && !is_wp_error($brand_terms)) {
        $brand = $brand_terms[0]->name;
    }
    
    // Get price info
    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();
    $is_on_sale = $product->is_on_sale();
    $discount_percentage = 0;
    
    if ($is_on_sale && $regular_price > 0) {
        $discount_percentage = round(100 - (($sale_price / $regular_price) * 100));
    }
    
    // Check if hire category
    $categories = wp_get_post_terms($product->get_id(), 'product_cat');
    $is_hire = false;
    foreach ($categories as $term) {
        if ($term->name === 'Hire') {
            $is_hire = true;
            break;
        }
    }
    
    // Get technical specification
    $tech_spec = '';
    $tech_spec_terms = get_the_terms($product->get_id(), 'pa_technical-spec');
    if (!empty($tech_spec_terms) && !is_wp_error($tech_spec_terms)) {
        $tech_spec = $tech_spec_terms[0]->name;
    }
    
    if (empty($tech_spec)) {
        $meta_dress_name = get_post_meta($product->get_id(), 'meta_dress_name', true);
        if (!empty($meta_dress_name) && $meta_dress_name !== '{{product.meta_dress name}}') {
            $tech_spec = $meta_dress_name;
        }
    }
    
    // Enhanced variation data
    $available_variations = array();
    if ($product->is_type('variable')) {
        $variations = $product->get_available_variations();
        foreach ($variations as $variation) {
            $variation_obj = wc_get_product($variation['variation_id']);
            $variation['stock_quantity'] = $variation_obj->get_stock_quantity();
            $variation['is_in_stock'] = $variation_obj->is_in_stock();
            $available_variations[] = $variation;
        }
    }
    
    // Get current stock quantity for display
    $stock_quantity = $product->get_stock_quantity();
    $can_purchase = $product->is_in_stock() && $product->is_purchasable();
    
    // Get product SKU for color mapping
    $product_sku = $product->get_sku();
?>

<style>
.related-products .image-container{
height: 360px!important;
}
    
      mark, insert{
        color:#c1272d !important
    }

</style>

<div class="container">
    <div class="breadcrumb">
        <?php woocommerce_breadcrumb(); ?>
    </div>
    
    <div class="product-container">
        <!-- Product Gallery -->
        <div class="product-gallery clearfix">
            <div class="thumbnail-sidebar">
                <?php 
                if ($main_image_url) {
                    echo '<div class="thumbnail active">';
                    echo '<img src="' . esc_url($main_image_url) . '" alt="' . esc_attr(get_the_title()) . '">';
                    echo '</div>';
                }
                
                foreach ($attachment_ids as $attachment_id) {
                    $image_url = wp_get_attachment_url($attachment_id);
                    if ($image_url) {
                        echo '<div class="thumbnail">';
                        echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '">';
                        echo '</div>';
                    }
                }
                
                if (!$main_image_url && empty($attachment_ids)) {
                    echo '<div class="thumbnail active">';
                    echo '<img src="' . esc_url($fallback_image) . '" alt="' . esc_attr(get_the_title()) . '">';
                    echo '</div>';
                }
                ?>
            </div>
            
            <div class="main-image-container">
                <?php if ($is_new) : ?>
                <div class="new-badge <?php echo $newlabel_style; ?>">NEW</div>
                <?php endif; ?>
                
                <button class="add-to-favorites">
                    <i class="fa-regular fa-heart"></i>
                </button>
                
                <div class="main-image">
                    <img src="<?php echo esc_url($main_image_url ? $main_image_url : $fallback_image); ?>" 
                         alt="<?php echo esc_attr(get_the_title()); ?>">
                </div>
                
                <div class="navigation-arrows">
                    <div class="nav-arrow prev"><i class="fa-solid fa-chevron-left"></i></div>
                    <div class="nav-arrow next"><i class="fa-solid fa-chevron-right"></i></div>
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
    margin-left: auto;
    margin-right:auto;
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
    background: #DDCDBF;
}

.share-btn.facebook:hover {
    background: #b19176;
}

.share-btn.instagram {
    background: #DDCDBF;
    display: none!important;
}
.share-btn.instagram:hover {
    background: #b19176;
}
.share-btn.email {
    background: #DDCDBF;
}

.share-btn.email:hover {
    background: #b19176;
}

.share-btn.copy-link {
    background: #DDCDBF;
}

.share-btn.copy-link:hover {
    background: #b19176;
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
.ins {
  background: none;
  font-weight: bold !important;
  color: grey !important;
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
                <?php echo apply_filters('woocommerce_short_description', $post->post_excerpt); ?>
            </div>
            
            <!-- Stock Status Display -->
            <div class="stock-status">
                <i class="fa-solid fa-circle"></i> 
                <?php echo $product->is_in_stock() ? 'In stock' : 'Out of stock'; ?>
            </div>
            
            <?php if ($product->get_sku()) : ?>
            <div class="product-code">Product Code: <?php echo esc_html($product->get_sku()); ?></div>
            <?php endif; ?>
            
            <!-- Price Display -->
            <div class="price-container">
                <?php if ($is_on_sale && $discount_percentage > 0) : ?>
                    <div class="discount-badge"><?php echo esc_html($discount_percentage); ?>%</div>
                <?php endif; ?>
                
                <div class="price-box">
                    <?php if ($is_on_sale && $product->get_regular_price()) : ?>
                        <div class="old-price"><?php echo wc_price($product->get_regular_price()); ?></div>
                        <div class="current-price"><?php echo wc_price($product->get_sale_price()); ?></div>
                    <?php elseif (!$is_hire) : ?>
                        <div class="current-price"><?php echo $product->get_price_html(); ?></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Enhanced Variation Display -->
            <?php
            if ($product->is_type('variable')) :
                $attributes = $product->get_variation_attributes();
                
                // Reorder attributes so colour comes first, then size
                $ordered_attributes = array();
                $color_key = null;
                $size_key = null;
                $other_keys = array();
                
                foreach ($attributes as $attribute_name => $options) {
                    $attr_lower = strtolower($attribute_name);
                    if (strpos($attr_lower, 'colour') !== false || strpos($attr_lower, 'color') !== false) {
                        $color_key = $attribute_name;
                    } elseif (strpos($attr_lower, 'size') !== false) {
                        $size_key = $attribute_name;
                    } else {
                        $other_keys[] = $attribute_name;
                    }
                }
                
                // Build ordered array: colour first, then size, then others
                if ($color_key !== null) {
                    $ordered_attributes[$color_key] = $attributes[$color_key];
                }
                if ($size_key !== null) {
                    $ordered_attributes[$size_key] = $attributes[$size_key];
                }
                foreach ($other_keys as $key) {
                    $ordered_attributes[$key] = $attributes[$key];
                }
                
                // Use ordered attributes instead of original
                $attributes = $ordered_attributes;
                
                // Create PHP color mapping for JavaScript
                $color_mappings = array();
                
                foreach ($attributes as $attribute_name => $options) :
                    $attribute_label = wc_attribute_label($attribute_name);
                    $attribute_id = sanitize_title($attribute_name);
                    $selected_value = isset($_REQUEST['attribute_' . $attribute_id]) ? 
                                     wc_clean(wp_unslash($_REQUEST['attribute_' . $attribute_id])) : 
                                     $product->get_variation_default_attribute($attribute_name);
                    
                    $full_attribute_name = 'attribute_' . $attribute_id;
                    
                    // FIXED: Enhanced color attribute detection
                    // Check both the attribute taxonomy name AND the label
                    $is_color = (
                        stripos($attribute_label, 'color') !== false || 
                        stripos($attribute_label, 'colour') !== false ||
                        stripos($attribute_name, 'color') !== false ||
                        stripos($attribute_name, 'colour') !== false ||
                        $attribute_name === 'pa_colour' ||
                        $attribute_name === 'pa_color' ||
                        $attribute_id === 'pa_colour' ||
                        $attribute_id === 'pa_color'
                    );
                    
                    $is_size = stripos($attribute_label, 'size') !== false || 
                              stripos($attribute_name, 'size') !== false;
                    
                    $attr_class = 'attribute-selection';
                    $option_class = 'attribute-option';
                    
                    if ($is_color) {
                        $attr_class = 'color-selection';
                        $option_class = 'color-option';
                        
                        // Build color mappings for this attribute
                        foreach ($options as $option) {
                            $color_mappings[$option] = get_color_hex_code($option, $product_sku);
                        }
                    } elseif ($is_size) {
                        $attr_class = 'size-selection';
                        $option_class = 'size-option';
                    }
            ?>
            <div class="<?php echo esc_attr($attr_class); ?>" 
                 data-attribute="<?php echo esc_attr($full_attribute_name); ?>"
                 data-attribute-type="<?php echo $is_color ? 'color' : ($is_size ? 'size' : 'other'); ?>">
                <div class="attribute-title"><?php echo esc_html($attribute_label); ?></div>
                <div class="attribute-options">
                    <?php 
                    if ($is_size) {
                        // Sort sizes numerically if possible
                        sort($options, SORT_NUMERIC);
                    }
                    
                    foreach ($options as $option) : 
                        $is_selected = ($selected_value === $option);
                    ?>
                        <div class="<?php echo esc_attr($option_class . ' ' . ($is_selected ? 'active' : '')); ?>" 
                             data-value="<?php echo esc_attr($option); ?>"
                             data-attribute="<?php echo esc_attr($full_attribute_name); ?>"
                             data-attribute-type="<?php echo $is_color ? 'color' : ($is_size ? 'size' : 'other'); ?>"
                             title="<?php echo esc_attr($option); ?>">
                            <?php 
                            if ($is_color) {
                                // Get actual color hex from mapping
                                $color_hex = get_color_hex_code($option, $product_sku);
                                ?>
                                <span class="color-swatch" style="background-color: <?php echo esc_attr($color_hex); ?>"></span>
                                <span class="color-name"><?php echo esc_html($option); ?></span>
                                <?php
                            } else {
                                echo esc_html($option);
                            }
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php 
                endforeach;
            ?>
            
            <!-- Clear Selection Button -->
            <div class="clear-selection-wrapper" style="margin-top: 15px;">
                <button type="button" class="btn-clear-selection" id="clearSelectionBtn" style="display: none;">
                    <i class="fa-solid fa-undo"></i> Clear Selection
                </button>
            </div>
            <?php
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
            
            <?php render_enhanced_size_guide_button(); ?>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <div class="quantity-container" <?php echo !$can_purchase ? 'style="display:none;"' : ''; ?>>
                    <button class="quantity-btn minus">-</button>
                    <input type="text" class="quantity-input" value="1" name="quantity">
                    <button class="quantity-btn plus">+</button>
                </div>
                
                <?php if (!$is_hire) : ?>
                    <?php if ($product->is_type('variable')) : ?>
                        <button class="add-to-cart" id="custom-add-to-cart-btn" <?php echo !$can_purchase ? 'style="display:none;"' : ''; ?>>
                            ADD TO CART
                        </button>
                    <?php else : ?>
                        <?php if ($can_purchase) : ?>
                            <button class="add-to-cart" onclick="addToCartSimpleProduct(<?php echo esc_attr($product->get_id()); ?>, jQuery('.quantity-input').val())">
                                ADD TO CART
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php if (!$can_purchase) : ?>
                    <div class="view-only-notice">
                        <i class="fa-solid fa-info-circle"></i>
                        This product is currently out of stock.
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <a href="/book-your-free-fitting">
                    <button class="appointment-btn">BOOK APPOINTMENT</button>
                </a>
                
                <div class="add-to-cart wishlist-btn">
                    <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
                </div>
            </div>
            
            <!-- Hidden WooCommerce form -->
            <div style="display: none;" class="woo-cart-form-wrapper">
                <?php woocommerce_template_single_add_to_cart(); ?>
            </div>
            
            <!-- Shipping Features -->
            <div class="shipping-features">
                <div class="shipping-feature">
                    <img class="custom-icon" src="/wp-content/themes/bride-co-child/assets/imgs/truck-regular-full.svg"/>
                    <div>Nationwide Shipping</div>
                </div>
                <div class="shipping-feature">
                    <i class="fa-regular fa-clock"></i>
                    <div>3-7 Day Delivery</div>
                </div>
                <div class="shipping-feature">
                     <img class="custom-icon" src="/wp-content/themes/bride-co-child/assets/imgs/headset-solid-full.svg"/>
                    <div>Customer Service Support</div>
                </div>
            </div>
            
            <!-- Product Details Accordion -->
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
    
    <!-- Related Products Section -->
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

</div>

<!-- Size Guide Modal -->
<?php
$categories = wp_get_post_terms($product->get_id(), 'product_cat');
$image_url = '';
if (!empty($categories) && !is_wp_error($categories)) {
    $category_id = $categories[0]->term_id;
    $size_guide_image = get_field('size_guide', 'product_cat_' . $category_id);
    if ($size_guide_image) {
        $image_url = $size_guide_image['url'];
    }
}
?>

<div id="sizeGuideModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeSizeGuideModal()">&times;</span>
        <h2>Size Guide</h2>
        <?php if ($image_url): ?>
            <img src="<?php echo esc_url($image_url); ?>" alt="Size Guide">
        <?php else: ?>
            <p>No size guide available for this product category.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Simplified JavaScript without Stock Rules -->
<script type="text/javascript">
var productVariations = <?php echo json_encode($available_variations); ?>;
var colorMappings = <?php echo json_encode(isset($color_mappings) ? $color_mappings : array()); ?>;

jQuery(document).ready(function($) {
    var selectedAttributes = {};
    
    // Initialize variation management
    function initDynamicVariations() {
        var variationMap = {};
        
        if (typeof productVariations !== 'undefined' && productVariations.length > 0) {
            productVariations.forEach(function(variation) {
                // Build variation availability map
                Object.keys(variation.attributes).forEach(function(attrKey) {
                    if (!variationMap[attrKey]) {
                        variationMap[attrKey] = {};
                    }
                    
                    var attrValue = variation.attributes[attrKey];
                    
                    if (!variationMap[attrKey][attrValue]) {
                        variationMap[attrKey][attrValue] = {
                            variations: [],
                            relatedAttributes: {}
                        };
                    }
                    
                    variationMap[attrKey][attrValue].variations.push(variation.variation_id);
                    
                    // Map related attributes
                    Object.keys(variation.attributes).forEach(function(otherAttrKey) {
                        if (otherAttrKey !== attrKey) {
                            if (!variationMap[attrKey][attrValue].relatedAttributes[otherAttrKey]) {
                                variationMap[attrKey][attrValue].relatedAttributes[otherAttrKey] = {};
                            }
                            
                            var otherValue = variation.attributes[otherAttrKey];
                            if (otherValue) {
                                if (!variationMap[attrKey][attrValue].relatedAttributes[otherAttrKey][otherValue]) {
                                    variationMap[attrKey][attrValue].relatedAttributes[otherAttrKey][otherValue] = {
                                        variationIds: [],
                                        available: false
                                    };
                                }
                                
                                variationMap[attrKey][attrValue].relatedAttributes[otherAttrKey][otherValue].variationIds.push(variation.variation_id);
                                
                                // Mark as available if variation is in stock
                                if (variation.is_in_stock) {
                                    variationMap[attrKey][attrValue].relatedAttributes[otherAttrKey][otherValue].available = true;
                                }
                            }
                        }
                    });
                });
            });
        }
        
        return variationMap;
    }
    
    var variationMap = initDynamicVariations();
    
    // Update available options based on selection
    function updateAvailableOptions(selectedAttr, selectedValue) {
        if (!variationMap[selectedAttr] || !variationMap[selectedAttr][selectedValue]) {
            return;
        }
        
        var availableOptions = variationMap[selectedAttr][selectedValue].relatedAttributes;
        
        // Update all other attribute options
        $('.attribute-option, .color-option, .size-option').each(function() {
            var $option = $(this);
            var optionAttr = $option.data('attribute');
            var optionValue = $option.data('value');
            var optionType = $option.data('attribute-type');
            
            // Skip the currently selected attribute type
            if (optionAttr === selectedAttr) {
                return;
            }
            
            // Check availability
            var isAvailable = false;
            
            if (availableOptions[optionAttr] && availableOptions[optionAttr][optionValue]) {
                isAvailable = availableOptions[optionAttr][optionValue].available;
            }
            
            // Apply appropriate styling based on availability
            if (!isAvailable) {
                $option.addClass('disabled-option unavailable')
                       .attr('title', 'Not available with selected ' + getAttributeLabel(selectedAttr));
                       
                if (optionType === 'color') {
                    $option.addClass('line-through');
                } else if (optionType === 'size') {
                    $option.addClass('greyed-out');
                }
            } else {
                $option.removeClass('disabled-option unavailable greyed-out line-through')
                       .attr('title', optionValue);
            }
        });
    }
    
    // Clear selection functionality
    $('#clearSelectionBtn').on('click', function() {
        // Clear all selections
        $('.attribute-option, .color-option, .size-option').removeClass('active');
        
        // Clear the selectedAttributes object
        selectedAttributes = {};
        
        // Reset WooCommerce form
        $('select[name^="attribute_"]').val('').trigger('change');
        
        // Hide clear button
        $(this).fadeOut();
        
        // Reset to original state
        $('.attribute-option, .color-option, .size-option').removeClass('disabled-option unavailable greyed-out line-through');
    });
    
    // Handle option clicks
    $('.color-option, .size-option, .attribute-option').on('click', function(e) {
        e.preventDefault();
        
        var $this = $(this);
        
        // Don't allow selection of disabled options
        if ($this.hasClass('disabled-option')) {
            return false;
        }
        
        var value = $this.data('value');
        var attributeName = $this.data('attribute');
        
        // Remove active class from siblings and add to selected
        $this.siblings().removeClass('active');
        $this.addClass('active');
        
        // Show clear button
        $('#clearSelectionBtn').fadeIn();
        
        // Update selected attributes
        selectedAttributes[attributeName] = value;
        
        // Update hidden WooCommerce form
        var select = $('select[name="' + attributeName + '"]');
        if (select.length) {
            select.val(value).trigger('change');
        }
        
        // Update available options
        updateAvailableOptions(attributeName, value);
    });
    
    // Initialize with any pre-selected options
    $('.attribute-option.active, .color-option.active, .size-option.active').each(function() {
        var $this = $(this);
        selectedAttributes[$this.data('attribute')] = $this.data('value');
    });
    
    // Trigger initial update if we have selections
    if (Object.keys(selectedAttributes).length > 0) {
        Object.keys(selectedAttributes).forEach(function(attr) {
            updateAvailableOptions(attr, selectedAttributes[attr]);
        });
    }
    
    // Handle add to cart for variable products
    $('#custom-add-to-cart-btn').on('click', function(e) {
        e.preventDefault();
        
        // Validate all variations are selected
        var allSelected = true;
        var missingAttributes = [];
        
        $('form.variations_form').find('select[name^="attribute_"]').each(function() {
            if ($(this).val() === '') {
                allSelected = false;
                var attrName = $(this).attr('name').replace('attribute_pa_', '').replace('attribute_', '').replace(/-/g, ' ');
                missingAttributes.push(attrName);
            }
        });
        
        if (!allSelected) {
            alert('Please select: ' + missingAttributes.join(', '));
            return;
        }
        
        // Set quantity and submit
        var quantity = $('.quantity-input').val();
        $('form.cart .quantity input').val(quantity);
        $('form.cart .single_add_to_cart_button').click();
    });
    
    // Quantity controls
    $('.quantity-btn.minus').on('click', function() {
        var input = $('.quantity-input');
        var val = parseInt(input.val());
        if (val > 1) {
            input.val(val - 1);
            $('form.cart .quantity input').val(val - 1);
        }
    });
    
    $('.quantity-btn.plus').on('click', function() {
        var input = $('.quantity-input');
        var val = parseInt(input.val());
        input.val(val + 1);
        $('form.cart .quantity input').val(val + 1);
    });
    
    // Product image gallery
    $('.thumbnail').on('click', function() {
        $('.thumbnail').removeClass('active');
        $(this).addClass('active');
        $('.main-image img').attr('src', $(this).find('img').attr('src'));
    });
    
    // Navigation arrows
    $('.nav-arrow.prev').on('click', function() {
        var active = $('.thumbnail.active');
        var prev = active.prev('.thumbnail');
        if (prev.length) {
            active.removeClass('active');
            prev.addClass('active');
            $('.main-image img').attr('src', prev.find('img').attr('src'));
        }
    });
    
    $('.nav-arrow.next').on('click', function() {
        var active = $('.thumbnail.active');
        var next = active.next('.thumbnail');
        if (next.length) {
            active.removeClass('active');
            next.addClass('active');
            $('.main-image img').attr('src', next.find('img').attr('src'));
        }
    });
    
    // Accordion functionality
    $('.product-details h3').on('click', function() {
        var content = $(this).next('.product-details-content');
        var icon = $(this).find('i');
        
        if (content.is(':visible')) {
            content.slideUp();
            icon.removeClass('fa-minus').addClass('fa-plus');
        } else {
            content.slideDown();
            icon.removeClass('fa-plus').addClass('fa-minus');
        }
    });
    
    $('.returns-container h3').on('click', function() {
        var content = $(this).next('.returns-content');
        var icon = $(this).find('i');
        
        if (!content.length) {
            content = $('<div class="returns-content">').html(`
                <p>When purchasing from Complete Bride & Co, products in the discount category that don't involve alterations like hemming and resizing, can be exchanged. For this, you need to make an appointment 3-7 days in advance.</p>
                <p>Returns and exchanges are not available for altered items.</p>
            `);
            $(this).after(content);
            icon.removeClass('fa-plus').addClass('fa-minus');
        } else {
            content.toggle();
            icon.toggleClass('fa-plus fa-minus');
        }
    });
    
    // Favorites button
    $('.add-to-favorites').on('click', function() {
        var icon = $(this).find('i');
        icon.toggleClass('fa-regular fa-solid');
        if (icon.hasClass('fa-solid')) {
            icon.css('color', '#d9304f');
        } else {
            icon.css('color', '#333');
        }
    });
});

// Helper function for attribute labels
function getAttributeLabel(attrName) {
    var label = attrName.replace('attribute_pa_', '').replace('attribute_', '').replace(/-/g, ' ');
    return label.charAt(0).toUpperCase() + label.slice(1);
}

// Simple product add to cart
function addToCartSimpleProduct(product_id, quantity) {
    jQuery.ajax({
        type: 'POST',
        url: wc_add_to_cart_params.ajax_url,
        data: {
            action: 'woocommerce_add_to_cart',
            product_id: product_id,
            quantity: quantity
        },
        success: function(response) {
            if (response.error) {
                alert(response.error_message);
            } else {
                alert('Product added to cart!');
                jQuery(document.body).trigger('wc_fragment_refresh');
            }
        }
    });
}

// Size guide modal functions
function openSizeGuideModal() {
    document.getElementById('sizeGuideModal').style.display = 'block';
}

function closeSizeGuideModal() {
    document.getElementById('sizeGuideModal').style.display = 'none';
}

window.onclick = function(event) {
    var modal = document.getElementById('sizeGuideModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

<!-- CSS Styles (continued from JavaScript section) -->
<style>
/* Clear Selection Button */
.btn-clear-selection {
    background: white;
    color: #000;
    padding: 15px 40px;
    border: 2px solid #000;
    font-family: inherit;
}

.btn-clear-selection:hover {
    background: #000;
    color: #fff;
}

.clear-selection-wrapper {
    margin-top: 15px;
}

/* Base Styles */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.product-container {
    display: flex;
    gap: 30px;
    margin-bottom: 50px;
}

.product-gallery {
    flex: 0 0 50%;
    display: flex;
    gap: 15px;
}

.thumbnail-sidebar {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 80px;
}

.thumbnail {
    cursor: pointer;
    border: 2px solid transparent;
    transition: border-color 0.3s;
}

.thumbnail.active,
.thumbnail:hover {
    border-color: #000;
}

.thumbnail img {
    width: 100%;
    height: auto;
}

.main-image-container {
    flex: 1;
    position: relative;
}

.main-image img {
    width: 100%;
    height: auto;
}

.new-badge {
  display: block;
    position: absolute;
    z-index: 1;
    background: #ddcdbf;
    padding: 8px 15px;
    font-weight: 600;
}
.add-to-favorites {
    position: absolute;
    top: 10px;
   left: 10px;
    background: white;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    z-index: 10;
}

.navigation-arrows {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 100%;
    display: none;
    justify-content: space-between;
    padding: 0 10px;
}

.nav-arrow {
    background: white;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* Product Info Styles */
.product-info {
    flex: 0 0 45%;
}

.brand-name {
    color: #666;
    font-size: 14px;
    margin-bottom: 5px;
}

.tech-spec-title {
    color: #666;
    font-size: 16px;
    margin-bottom: 5px;
}

.product-title {
    font-size: 28px;
    margin-bottom: 15px;
}

.product-short-description {
    color: #666;
    margin-bottom: 15px;
    line-height: 1.6;
}

/* Stock Status Styles */
.stock-status {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 10px;
    font-weight: bold;
}

.stock-status i {
    font-size: 8px;
    color: #28a745;
}

/* Price Styles */
.price-container {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.discount-badge {
    background: #d9304f;
    color: white;
    padding: 5px 10px;
    font-size: 14px;
    font-weight: bold;
}

.old-price {
    text-decoration: line-through;
    color: #999;
    font-size: 18px;
}

.current-price {
    font-size: 24px;
    font-weight: bold;
    color: #000!important;
}

/* Variation Selection Styles */
.attribute-selection,
.color-selection,
.size-selection {
    margin-bottom: 20px;
}

.attribute-title {
    font-weight: bold;
    margin-bottom: 10px;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 1px;
}

.attribute-options {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

/* Size Option Styles */
.size-option {
    padding: 8px 16px;
    border: 1px solid #ddd;
    cursor: pointer;
    transition: all 0.3s;
    min-width: 50px;
    text-align: center;
    background: white;
}

.size-option:hover:not(.disabled-option) {
    border-color: #000;
}

.size-option.active {
    background: #000;
    color: white;
    border-color: #000;
}

.size-option.greyed-out {
    background: #f5f5f5;
    color: #999;
    border-color: #ddd;
    cursor: not-allowed;
    opacity: 0.6;
}

.size-option.disabled-option {
    pointer-events: none;
}

/* Color Option Styles */
.color-option {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    border: 2px solid #ddd;
    cursor: pointer;
    position: relative;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.color-option:hover:not(.disabled-option) {
    transform: scale(1.1);
    border-color: #000;
}

.color-option.active {
    border-color: #000;
    box-shadow: 0 0 0 2px #fff, 0 0 0 4px #000;
}

.color-option .color-swatch {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: block;
}

/* Color name tooltip */
.color-option .color-name {
    position: absolute;
    bottom: -30px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 11px;
    white-space: nowrap;
    opacity: 0;
    transition: opacity 0.3s;
    pointer-events: none;
    z-index: 10;
    text-transform: capitalize;
}

.color-option:hover .color-name {
    opacity: 1;
}

/* Special styling for white and light colors */
.color-option .color-swatch[style*="#FFFFFF"],
.color-option .color-swatch[style*="#FFFFF0"],
.color-option .color-swatch[style*="#FFFDD0"],
.color-option .color-swatch[style*="#e7e7e7"],
.color-option .color-swatch[style*="#ddd3c3"] {
    box-shadow: inset 0 0 0 1px rgba(0,0,0,0.1);
}

/* Special styling for black colors */
.color-option .color-swatch[style*="#0E0E0C"],
.color-option .color-swatch[style*="#000000"],
.color-option .color-swatch[style*="#171513"] {
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.1);
}

.color-option.line-through::after {
    content: "";
    position: absolute;
    top: 50%;
    left: -2px;
    right: -2px;
    height: 2px;
    background: #ff0000;
    transform: rotate(-45deg);
}

.color-option.disabled-option {
    cursor: not-allowed;
    opacity: 0.6;
}

/* View Only Notice */
.view-only-notice {
    background: #fff3cd;
    border: 1px solid #ffc107;
    color: #856404;
    padding: 12px 20px;
    border-radius: 5px;
    margin: 15px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.view-only-notice i {
    font-size: 18px;
}

/* Action Buttons */
.action-buttons {
    margin: 30px 0;
}

.quantity-container {
    display: flex;
    align-items: center;
    gap: 0;
    margin-bottom: 15px;
    width: fit-content;
}

.quantity-btn {
    width: 40px;
    height: 40px;
    border: 1px solid #ddd;
    background: white;
    cursor: pointer;
    font-size: 18px;
    transition: all 0.3s;
}

.quantity-btn:hover {
    background: #f5f5f5;
}

.quantity-input {
    width: 60px;
    height: 40px;
    border: 1px solid #ddd;
    border-left: none;
    border-right: none;
    text-align: center;
    font-size: 16px;
}

.add-to-cart {
    background: #000;
    color: white;
    padding: 15px 40px;
    border: none;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
    margin-right: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.add-to-cart:hover {
    background: #333;
}

.appointment-btn {
    background: white;
    color: #000;
    padding: 15px 40px;
    border: 2px solid #000;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.appointment-btn:hover {
    background: #000;
    color: white;
}

/* Shipping Features */
.shipping-features {
    display: flex;
    gap: 30px;
    margin: 30px 0;
    padding: 20px 0;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
}

.shipping-feature {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
}

/* Accordion Styles */
.product-details h3,
.returns-container h3 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
    font-size: 16px;
}

.product-details-content,
.returns-content {
    padding: 15px 0;
    line-height: 1.6;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 30px;
    width: 80%;
    max-width: 800px;
    border-radius: 10px;
    position: relative;
}

.modal-content .close {
    position: absolute;
    right: 20px;
    top: 20px;
    font-size: 30px;
    cursor: pointer;
    color: #999;
}

.modal-content img {
    width: 100%;
    height: auto;
    margin-top: 20px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .product-container {
        flex-direction: column;
    }
    
    .product-gallery,
    .product-info {
        flex: 1;
        width: 100%;
    }
    
    .thumbnail-sidebar {
        flex-direction: row;
        width: 100%;
        overflow-x: auto;
    }
    
    .thumbnail {
        min-width: 60px;
    }
    
    .shipping-features {
        flex-direction: column;
        gap: 15px;
    }
    
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .add-to-cart,
    .appointment-btn {
        width: 100%;
    }
    
    .color-option .color-name {
        bottom: -35px;
    }
}
</style>

<?php
endwhile; // end of the loop

get_footer('shop');
?>