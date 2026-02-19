<?php
/**
 * Template Name: Home Page Template
 *
 * Description: A home page template for displaying the home page.
 *
 * This file is used to render a single page in your WordPress theme.
 * It includes the header, main content area (with the loop), and footer.
 *
 * @package YourThemeName
 */




get_header(); ?>

<head>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<style>
  #RealBridesForm {
    margin: 0 auto;
    padding: 30px;
    background-color: #f9f4ee;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    font-family: 'Poppins', sans-serif;
  }

  #RealBridesForm label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
    font-size: 14px;
  }

  #RealBridesForm input[type="text"],
  #RealBridesForm input[type="email"] {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    transition: border-color 0.3s;
    background-color: #fff;
  }

  #RealBridesForm input[type="text"]:focus,
  #RealBridesForm input[type="email"]:focus {
    border-color: #DDCDBF;
    outline: none;
    box-shadow: 0 0 0 2px rgba(221, 205, 191, 0.3);
  }

  #RealBridesForm .img-upload {
    position: relative;
    display: block;
  }

  #RealBridesForm .real-brides-upload {
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    position: absolute;
    z-index: -1;
  }

  #RealBridesForm .file-upload-label {
    display: inline-block;
    background-color: #fff;
    color: #333;
    border: 1px dashed #DDCDBF;
    padding: 12px 20px;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    width: 100%;
    text-align: center;
    margin-top: 8px;
    transition: all 0.3s;
  }

  #RealBridesForm .file-upload-label:hover {
    background-color: #f0e6dd;
    border-color: #c9b9a9;
  }

  #RealBridesForm .file-name {
    margin-top: 8px;
    font-size: 12px;
    color: #666;
    text-align: center;
    display: none;
  }

  #RealBridesForm .real-brides-submit {
    background-color: #c7b3a2;
    color: #333;
    border: none;
    padding: 12px 30px;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    display: block;
    width: 100%;
    transition: all 0.3s;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
	  margin-top: 32px;
  }

  #RealBridesForm .real-brides-submit:hover {
    background-color: #c9b9a9;
  }

  #RealBridesForm .wpcf7-spinner {
    margin: 0 auto;
    display: block;
  }

  #RealBridesForm .wpcf7-response-output {
    margin-top: 20px;
    padding: 10px;
    border-radius: 5px;
    font-size: 14px;
    text-align: center;
  }

  #RealBridesForm h2 {
    text-align: center;
    margin-bottom: 20px;
    font-family: 'Cinzel', serif;
    color: #333;
  }

  /* Add form title */
  #RealBridesForm::before {
    content: "Share Your Wedding Photos";
    display: block;
    text-align: center;
    font-family: 'Cinzel', serif;
    font-size: 24px;
    color: #333;
	margin-top: 15px;
  }
  #RealBridesForm p{
	margin: 10px 5px;
}
#RealBridesForm .wpcf7-text,#RealBridesForm .wpcf7-email{
	margin-top: 8px;
}
@media only screen and (max-width: 767px) {
  .bride-slider-btn{
    width: 250px !important;
    font-size:14px !important
}
.bride-slide-content{
  background: rgba(255, 255, 255, 0.3)!important;
   width: 350px!important;
   height: auto!important;
   line-height: 30px!important;
}
.bride-slide-content span{
  font-size: 25px!important;
  line-height: 15px!important;
}
.bride-slide-content h1{
  font-size: 1.5rem!important;
  margin-bottom: 1rem!important;
}
  }

  .bride-slider-btn{
    width: 250px !important;
    font-size:14px !important
}
.bride-slide-content{
  background:;
  box-shadow: none!important;
   width: 500px!important;
   height: auto!important;
   line-height: 32px!important;
   margin-bottom: -3%!important;
}
.bride-slide-content span{
  font-size: 28px!important;
  line-height: 15px!important;
  text-shadow: 1px 1px 1px grey!important;
}
.bride-slide-content h1{
  font-size: 1.5rem!important;
  margin-bottom: 1rem!important;
  text-shadow: 1px 1px 1px grey!important;
}
.bride-slider-indicators{
  display: none!important;
}
.bride-slider-control-icon {
    position: static!important;
    margin-left: -15px!important;
}
.mt-4 {
    margin-top: 1rem !important;
}
</style>

<!-- JavaScript for enhanced form interaction -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Create custom file upload UI
    const realUpload = document.querySelector('.real-brides-upload');
    const uploadParent = realUpload.parentElement;
    
    // Create elements for custom file upload
    const customLabel = document.createElement('label');
    customLabel.className = 'file-upload-label';
    customLabel.setAttribute('for', 'real-brides-upload-input');
    customLabel.innerHTML = '<i class="fa fa-cloud-upload"></i> Choose an image';
    
    const fileNameDisplay = document.createElement('div');
    fileNameDisplay.className = 'file-name';
    
    // Set ID for the original input
    realUpload.id = 'real-brides-upload-input';
    
    // Insert custom elements
    uploadParent.appendChild(customLabel);
    uploadParent.appendChild(fileNameDisplay);
    
    // Handle file selection
    realUpload.addEventListener('change', function() {
      if (this.files && this.files[0]) {
        const fileName = this.files[0].name;
        fileNameDisplay.textContent = fileName;
        fileNameDisplay.style.display = 'block';
        customLabel.innerHTML = '<i class="fa fa-check"></i> Image selected';
        customLabel.style.backgroundColor = '#e8f4e5';
        customLabel.style.borderColor = '#a3d39c';
      } else {
        fileNameDisplay.style.display = 'none';
        customLabel.innerHTML = '<i class="fa fa-cloud-upload"></i> Choose an image';
        customLabel.style.backgroundColor = '#fff';
        customLabel.style.borderColor = '#DDCDBF';
      }
    });
    
    // Add validation
    const form = document.getElementById('RealBridesForm');
    const nameInput = form.querySelector('input[name="your-name"]');
    const emailInput = form.querySelector('input[name="your-email"]');
    
    form.addEventListener('submit', function(e) {
      let isValid = true;
      
      // Validate name
      if (!nameInput.value.trim()) {
        nameInput.style.borderColor = '#ff6b6b';
        isValid = false;
      } else {
        nameInput.style.borderColor = '#ddd';
      }
      
      // Validate email
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailInput.value.trim() || !emailPattern.test(emailInput.value)) {
        emailInput.style.borderColor = '#ff6b6b';
        isValid = false;
      } else {
        emailInput.style.borderColor = '#ddd';
      }
      
      // Validate file
      if (!realUpload.files || !realUpload.files[0]) {
        customLabel.style.borderColor = '#ff6b6b';
        isValid = false;
      } else {
        customLabel.style.borderColor = '#DDCDBF';
      }
      
      if (!isValid) {
        e.preventDefault();
      }
    });
  });
</script>
</head>
<!-- Oleg Cassini Custom Promo Slider -->

<?php echo do_shortcode('[bride_slider id="20135393"] '); ?>
<?php bride_co_render_category_cards();  ?>
<?php bride_co_render_evening_wear(); ?>

<?php bride_co_render_featured_products(); ?>
<?php echo do_shortcode('[wedding_dresses_archive]'); ?>
<?php bride_co_render_silhouette(); ?>
<?php bride_co_render_appointment(); ?>


<?php bride_co_render_white_banner();  ?>
<?php echo do_shortcode('[evening_dresses_archive]'); ?>
<!--
<section>
  <div id="promoCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="0" class="active"></button>
      <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="2"></button>
    </div>

    <div class="carousel-inner">
      <div class="carousel-item active" style="background-color: #C2185B;">
        <div class="slide-content-wrapper">
          <div class="slide-content">
            <h2 class="text-white mb-3">SPECIAL FOR WOMEN'S DAY</h2>
            <h4 class="text-white mb-4">ON ALL SEASON EVENING DRESSES</h4>
            <div class="mb-4">
              <span class="display-1 text-white fw-bold">50<span class="fs-1">%</span></span>
              <span class="fs-3 text-white d-block">DISCOUNT</span>
            </div>
            <div class="d-inline-block position-relative mb-4">
              <span class="top-0 end-0 badge rounded-pill bg-white text-dark px-3 py-2">
                FOR A LIMITED TIME ONLY
              </span>
            </div>
            <div>
              <a href="#" class="btn btn-light btn-lg text-uppercase px-4 py-2">Start Shopping</a>
            </div>
          </div>
        </div>
      </div>

      <div class="carousel-item" style="background-color: #F0E9E1;">
        <div class="slide-content-wrapper">
          <div class="slide-content">
            <h1 class="display-4 text-dark mb-3" style="font-family: 'Playfair Display', serif;">Oleg <span class="fw-light">by</span> Bride&Co</h1>
            <h5 class="text-dark mb-4">PRICES UNDER 30,000 TL AND SPECIALLY SELECTED OPTIONS</h5>
            <div class="mt-4">
              <a href="#" class="btn btn-elegant px-5 py-2" style="background-color: #C8B097; color: white;">START SHOPPING</a>
            </div>
          </div>
        </div>
      </div>

      <div class="carousel-item" style="background-color: #F8F4F0;">
        <div class="slide-content-wrapper">
          <div class="slide-content">
            <h2 class="text-dark mb-3">SPRING WEDDING COLLECTION</h2>
            <h4 class="text-dark mb-4">NEW ARRIVALS 2025</h4>
            <p class="mb-4">Discover the elegance of our latest wedding dresses</p>
            <div class="mt-4">
              <a href="#" class="btn btn-dark btn-lg text-uppercase px-4 py-2">Explore Collection</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>
</section>-->

<!--
<section>
  <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="card position-relative overflow-hidden text-white text-center">
                    <img src="https://brideandco.co.za/wp-content/uploads/2024/05/Shop-our-show-stopping-wedding-dresses.png" class="w-100 grayscale" alt="Wedding Dress">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center bg-dark bg-opacity-50">
                        <p class="mb-1">SHOP OUR</p>
                        <h2 class="fw-bold">SHOWSTOPPING</h2>
                        <p class="mb-3">WEDDING DRESSES</p>
                        <button class="shop-button">View Now</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card position-relative overflow-hidden text-white text-center">
                    <img src="https://brideandco.co.za/wp-content/uploads/2024/05/We-have-your-man-covered.png" class="w-100 grayscale" alt="Groom Suit">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center bg-dark bg-opacity-50">
                        <p class="mb-1">WE HAVE</p>
                        <h2 class="fw-bold">YOUR MAN</h2>
                        <p class="mb-3">COVERED</p>
                        <button class="shop-button">Shop Suits</button>
                    </div>
                </div>
            </div>
        </div>
    </section>-->

  

    <!--<section class="container my-5">
      <h2 class="mb-4 fw-bold">Wedding Dresses of the Week</h2>
      <div class="row g-4">
        <div class="col-md-3">
          <div class="product-card">
            <div class="image-container">
              <span class="label new-label">NEW</span>
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/0b09b539-f4c1-4535-9f34-3915929d3596/900/10-11-oleg-cassini-lookbook-1009.webp"
                class="product-image"
                alt="Wedding Dress 1"
              />
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/77e9e1df-869a-4907-9759-dd68fbfecee7/1950/10-11-oleg-cassini-lookbook-1022.webp"
                class="hover-image"
                alt="Wedding Dress 1 Hover"
              />
              <a href="#" class="add-to-cart-btn">SHOP NOW</a>
            </div>
            <h5 class="mt-3 fw-bold">VIOLA CHAN</h5>
            <p>Strapless Tulle Lace Princess Model</p>
            <span class="discount-box">35%</span>
            <p>
              <span class="old-price">R 192,486.25</span> <br />
              <span class="new-price">R 125,116.06</span>
            </p>
          </div>
        </div>

        <div class="col-md-3">
          <div class="product-card">
            <div class="image-container">
              <span class="label new-label">NEW</span>
              <span class="label sold-out-label" style="right: 10px"
                >Sold Out</span
              >
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/f6b80970-31bb-4128-adcd-77b59d6b717f/1950/10-11-oleg-cassini-lookbook-0179.webp"
                class="product-image"
                alt="Wedding Dress 2"
              />
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/7c2b0c6f-292f-41f5-8a5e-cd2cf9931e15/1950/10-11-oleg-cassini-lookbook-0186.webp"
                class="hover-image"
                alt="Wedding Dress 2 Hover"
              />
              <a href="#" class="add-to-cart-btn">SHOP NOW</a>
            </div>
            <h5 class="mt-3 fw-bold">DREAMON FOR OLEG CASSINI</h5>
            <p>Strapped Tulle Fabric A-Line Wedding Dress</p>
            <span class="discount-box">35%</span>
            <p>
              <span class="old-price">R 192,486.25</span> <br />
              <span class="new-price">R 125,116.06</span>
            </p>
          </div>
        </div>

        <div class="col-md-3">
          <div class="product-card">
            <div class="image-container">
              <span class="label new-label">NEW</span>
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/e960e4c7-606a-406c-8667-caa30e5a380d/1950/10-11-oleg-cassini-lookbook-0911.webp"
                class="product-image"
                alt="Wedding Dress 3"
              />
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/7265fe16-3621-44b5-831c-070c2f60ef61/1950/10-11-oleg-cassini-lookbook-0943.webp"
                class="hover-image"
                alt="Wedding Dress 3 Hover"
              />
              <a href="#" class="add-to-cart-btn">SHOP NOW</a>
            </div>
            <h5 class="mt-3 fw-bold">OLEG BY OLEG CASSINI</h5>
            <p>Strapped Satin Fabric Wedding Dress</p>
            <span class="discount-box">20%</span>
            <p>
              <span class="old-price">R 109,986.25</span> <br />
              <span class="new-price">R 87,989.00</span>
            </p>
          </div>
        </div>

        <div class="col-md-3">
          <div class="product-card">
            <div class="image-container">
              <span class="label new-label">NEW</span>
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/269103c9-d773-482c-b24a-3373852d9f28/1950/10-11-oleg-cassini-lookbook-0843.webp"
                class="product-image"
                alt="Wedding Dress 4"
              />
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/a5174ce9-c9af-4d3b-b8d5-3b5154ed123c/1950/10-11-oleg-cassini-lookbook-0855.webp"
                class="hover-image"
                alt="Wedding Dress 4 Hover"
              />
              <a href="#" class="add-to-cart-btn">SHOP NOW</a>
            </div>
            <h5 class="mt-3 fw-bold">VIOLA CHAN</h5>
            <p>Strapless Tulle Lace Princess Model</p>
            <span class="discount-box">50%</span>
            <p>
              <span class="old-price">R 247,486.25</span> <br />
              <span class="new-price">R 123,743.13</span>
            </p>
          </div>
        </div>
      </div>
    </section>-->

    <!--<section class="container appointment-section">
      <div class="appointment-image">
        <img
          src="https://cdn.myikas.com/images/theme-images/29060b96-1fa8-4c93-b8a3-f3478cb8b23c/image_1728.webp"
          alt="Wedding Dress Appointment"
        />
      </div>
      <div class="appointment-text">
        <p>
          Book a <span class="bold-text">wedding dress appointment</span> now to find your dream dress!
        </p>
        <a href="#" class="cta-button">BOOK A FREE APPOINTMENT</a>
      </div>
    </section>-->

    <!--<section class="container my-5">
      <h2 class="mb-4 fw-bold">Evening Dresses of the Week</h2>
      <div class="row g-4">
        <div class="col-md-3">
          <div class="product-card">
            <div class="image-container">
              <span class="label new-label">NEW</span>
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/d89bd019-7cf7-424a-ba6e-13757f4ecf05/900/10-11-oleg-cassini-lookbook-1645.webp"
                class="product-image"
                alt="Evening Dress 1"
              />
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/060479de-563b-410b-ac68-4366cbf8fd5e/1950/10-11-oleg-cassini-lookbook-1660.webp"
                class="hover-image"
                alt="Evening Dress 1 Hover"
              />
              <a href="#" class="add-to-cart-btn">SHOP NOW</a>
            </div>
            <h5 class="mt-3 fw-bold">VIOLA CHAN</h5>
            <p>Red Strapped Lace Embroidered Evening Dress</p>
            <span class="discount-box">35%</span>
            <p>
              <span class="old-price">R 27,486.25</span> <br />
              <span class="new-price">R 17,866.06</span>
            </p>
          </div>
        </div>

        <div class="col-md-3">
          <div class="product-card">
            <div class="image-container">
              <span class="label new-label">NEW</span>
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/d0187652-045a-404b-9b71-bc28599f08ca/1950/10-11-oleg-cassini-lookbook-1274.webp"
                class="product-image"
                alt="Evening Dress 2"
              />
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/dfb1232d-df89-4736-a9ba-a38216315643/1950/10-11-oleg-cassini-lookbook-1290.webp"
                class="hover-image"
                alt="Evening Dress 2 Hover"
              />
              <a href="#" class="add-to-cart-btn">SHOP NOW</a>
            </div>
            <h5 class="mt-3 fw-bold">OLEG BY OLEG CASSINI</h5>
            <p>Gold Coloured Strapped Sequin Embroidered Mini Evening Dress</p>
            <span class="discount-box">20%</span>
            <p>
              <span class="old-price">R 19,236.25</span> <br />
              <span class="new-price">R 15,389.00</span>
            </p>
          </div>
        </div>

        <div class="col-md-3">
          <div class="product-card">
            <div class="image-container">
              <span class="label new-label">NEW</span>
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/a5bd51c9-56df-46b9-9659-8f91b51f4599/1950/10-11-oleg-cassini-lookbook-1418.webp"
                class="product-image"
                alt="Evening Dress 3"
              />
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/c3d6ccb7-8b2b-4f80-af31-0dc12b5b6474/1950/10-11-oleg-cassini-lookbook-1429.webp"
                class="hover-image"
                alt="Evening Dress 3 Hover"
              />
              <a href="#" class="add-to-cart-btn">SHOP NOW</a>
            </div>
            <h5 class="mt-3 fw-bold">VIOLA CHAN</h5>
            <p>Black Leaf Motif Long Evening Dress</p>
            <span class="discount-box">35%</span>
            <p>
              <span class="old-price">R 41,236.25</span> <br />
              <span class="new-price">R 26,803.56</span>
            </p>
          </div>
        </div>

        <div class="col-md-3">
          <div class="product-card">
            <div class="image-container">
              <span class="label new-label">NEW</span>
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/c276fba8-7d13-4eee-b80a-f5787cbe3643/1950/oleg-cassini-lookbook-02-06-24-1544.webp"
                class="product-image"
                alt="Evening Dress 4"
              />
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/3f43ff36-96f5-4699-9963-b6f76ec63e05/1950/oleg-cassini-lookbook-02-06-24-1568.webp"
                class="hover-image"
                alt="Evening Dress 4 Hover"
              />
              <a href="#" class="add-to-cart-btn">SHOP NOW</a>
            </div>
            <h5 class="mt-3 fw-bold">VIOLA CHAN</h5>
            <p>Silver Coloured Strapped Sequin Embroidered Evening Dress</p>
            <span class="discount-box">50%</span>
            <p>
              <span class="old-price">R 52,236.25</span> <br />
              <span class="new-price">R 26,118.13</span>
            </p>
          </div>
        </div>
      </div>
    </section>-->

<!--<section class="container my-5 Silhouette">
    <h1>Silhouette</h1>
    <div class="carousel-container">
      <div class="carousel">
        <div class="carousel-item">
          <img
            src="https://brideandco.co.za/wp-content/uploads/2025/03/Ballgown.png"
            alt="Ballgown"
          />
          <h2>Ballgown</h2>
          <a href="#" class="view-now">VIEW NOW</a>
        </div>
        <div class="carousel-item">
          <img
            src="https://brideandco.co.za/wp-content/uploads/2025/03/A-Line.png"
            alt="A-Line"
          />
          <h2>A-Line</h2>
          <a href="#" class="view-now">VIEW NOW</a>
        </div>
        <div class="carousel-item">
          <img
            src="https://brideandco.co.za/wp-content/uploads/2025/03/Fitted.png"
            alt="Fitted"
          />
          <h2>Fitted</h2>
          <a href="#" class="view-now">VIEW NOW</a>
        </div>
        <div class="carousel-item">
          <img
            src="https://brideandco.co.za/wp-content/uploads/2022/10/Sheath.png"
            alt="Sheath"
          />
          <h2>Sheath</h2>
          <a href="#" class="view-now">VIEW NOW</a>
        </div>
      </div>
      <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
      <button class="next" onclick="moveSlide(1)">&#10095;</button>
    </section>-->

    <script>
      let index = 0;
      const carousel = document.querySelector(".carousel");
      const totalSlides =
        document.querySelectorAll(".carousel-item").length / 4;

      function moveSlide(direction) {
        index += direction;
        if (index < 0) index = totalSlides - 1;
        if (index >= totalSlides) index = 0;
        carousel.style.transform = `translateX(-${index * 100}%)`;
      }

      setInterval(() => moveSlide(1), 3000); // Auto-rotate every 3 seconds
    </script>


<!-- Category Section -->
<section class="container my-5">
<h2 class="mb-4 fw-bold">Eveningwear</h2>
      <div class="row g-4">
        <!-- First Card (Matric Dance) -->
        <div class="col-md-3">
          <a href="https://stage.brideandco.co.za/product-category/special-occasions/matric-dance/" class="text-decoration-none">
            <!-- Link to another page -->
            <div class="category-card card-matric">
              <div class="category-content">
                <h2 class="category-title">A STYLISH<br>FAREWELL<br>Matric Dance</h2>
                <a href="https://stage.brideandco.co.za/product-category/special-occasions/matric-dance/" class="shop-button">View here</a>
              </div>
            </div>
          </a>
        </div>

        <!-- Second Card (Evening Gowns) -->
        <div class="col-md-3">
          <a href="https://stage.brideandco.co.za/product-category/special-occasions/" class="text-decoration-none">
            <div class="category-card card-evening">
              <div class="category-content">
                <h2 class="category-title">SHOP<br>BREATHTAKING<br> Evening Gowns</h2>
                <a href="https://stage.brideandco.co.za/product-category/special-occasions/" class="shop-button">View here</a>
              </div>
            </div>
          </a>
        </div>

        <!-- Third Card (Bride / Groom) -->
        <div class="col-md-3">
          <a href="https://stage.brideandco.co.za/product-category/mother-of-the-bride-groom/" class="text-decoration-none">
            <div class="category-card card-bride">
              <div class="category-content">
                <h2 class="category-title">MOM OF THE <br> Bride / Groom</h2>
                <a href="https://stage.brideandco.co.za/product-category/mother-of-the-bride-groom/" class="shop-button">View here</a>
              </div>
            </div>
          </a>
        </div>

        <!-- Fourth Card (New Arrivals) -->
        <div class="col-md-3">
          <a href="https://stage.brideandco.co.za/product-category/new-arrivals/" class="text-decoration-none">
            <div class="category-card card-new-arrivals">
              <div class="category-content">
                <h2 class="category-title">THIS SEASON'S <br>New Arrivals</h2>
                <a href="https://stage.brideandco.co.za/product-category/new-arrivals/" class="shop-button">View here</a>
              </div>
            </div>
          </a>
        </div>
      </div>
    </section>

  

<section class="reviews-section py-5">
  <div class="container">
    <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
      <!-- Indicators (optional) -->
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>

      <!-- Carousel items -->
      <div class="carousel-inner">
        <!-- Testimonial 1 -->
        <div class="carousel-item active">
          <div class="text-center">
            <div class="star-rating mb-3">★★★★★</div>
            <p class="review-text lead">
              If I could give this amazing team 1000000 ⭐️ I probably would give them even more! Specifically the Rivonia Bride and Co team! I cannot thank Pinky and the amazing team enough for making my dream wedding dress a reality! My journey to finding the perfect dress didn’t start well—at another bridal store, a consultant told me I was too fat and needed to lose weight before I could find something that fit. That experience left me feeling discouraged and heartbroken. But then I walked into this store, and everything changed. Pinky welcomed me with warmth, kindness, and genuine care. She pulled dresses that fit me perfectly, making me feel beautiful, confident, and truly like a bride. When I finally found the dress, I was overjoyed but worried about not being able to pay upfront. Instead of turning me away, the team went above and beyond to make my dream come true. Thanks to them, I’ll be walking down the aisle in a dress that makes me feel like the best version of myself. If you're a plus-size bride or anyone who wants to feel valued and celebrated, I cannot recommend this team enough!
            </p>
            <p class="review-author fw-bold">Nadia van den Heever</p>
          </div>
        </div>

        <!-- Testimonial 2 -->
        <div class="carousel-item">
          <div class="text-center">
            <div class="star-rating mb-3">★★★★★</div>
            <p class="review-text lead">
              I had the most incredible experience at Bride &amp; Co. From the moment I walked in, I felt so special—the space is big, open, and beautifully designed to make every bride feel like a queen. The service was absolutely outstanding! A huge thank you to the lovely Zantay, who assisted me with so much patience and care. She truly made my dream come true! I said YES to the dress, and I cannot wait to wear it on my big day. If you're looking for your dream gown, I highly recommend booking a free fitting at Bride &amp; Co. You won’t regret it!
            </p>
            <p class="review-author fw-bold">- Leandri Krige</p>
          </div>
        </div>

        <!-- Testimonial 3 -->
        <div class="carousel-item">
          <div class="text-center">
            <div class="star-rating mb-3">★★★★★</div>
            <p class="review-text lead">
              Awesome store, Zantay assisted us very helpfully from start to finish. Overall a very fun experience and my daughter said yes to her matric dance dress. Alterations are done in store which is very helpful. Thanks to all the staff.
            </p>
            <p class="review-author fw-bold">- Suzette Visser</p>
          </div>
        </div>
      </div>

      <!-- Carousel controls -->
      <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>
</section>




    <?php 
// bride_co_render_categories(); 
?>
    
    <!--<section class="categories-section">
      <div class="container">
        <div class="row justify-content-center g-4">
          <div class="col-md-2 col-4">
            <div class="category-item">
              <a href="#">
                <img
                  src="https://cdn.myikas.com/images/theme-images/1219b754-712e-4443-9c1e-ac4f4f16c46d/image_720.webp"
                  class="category-image"
                  alt="After Party"
                />
              </a>
              <p class="category-name">After Party</p>
            </div>
          </div>

          <div class="col-md-2 col-4">
            <div class="category-item">
              <a href="#">
                <img
                  src="https://cdn.myikas.com/images/theme-images/f4daba50-8765-4c77-8d97-0fd08d7281d6/image_720.webp"
                  class="category-image"
                  alt="Engagement Dresses"
                />
              </a>
              <p class="category-name">Engagement Dresses</p>
            </div>
          </div>

          <div class="col-md-2 col-4">
            <div class="category-item">
              <a href="#">
                <img
                  src="https://cdn.myikas.com/images/theme-images/7f71a623-0f8c-4b4f-b62f-53f439b5d801/image_720.webp"
                  class="category-image"
                  alt="Graduation Dresses"
                />
              </a>
              <p class="category-name">Graduation Dresses</p>
            </div>
          </div>

          <div class="col-md-2 col-4">
            <div class="category-item">
              <a href="#">
                <img
                  src="https://cdn.myikas.com/images/theme-images/0a3749a1-5633-4e87-8c0c-60dfcb570a76/image_720.webp"
                  class="category-image"
                  alt="Children's Evening Wear"
                />
              </a>
              <p class="category-name">Children's Evening Wear</p>
            </div>
          </div>

          <div class="col-md-2 col-4">
            <div class="category-item">
              <a href="#">
                <img
                  src="https://cdn.myikas.com/images/theme-images/a8f77ba9-20bd-4da5-bebf-810cfde9d541/image_720.webp"
                  class="category-image"
                  alt="Online Exclusive Collection"
                />
              </a>
              <p class="category-name">Online Exclusive Collection</p>
            </div>
          </div>

          <div class="col-md-2 col-4">
            <div class="category-item">
              <a href="#">
                <img
                  src="https://cdn.myikas.com/images/theme-images/b8100cbe-0867-4fef-9a44-a17ce8f0406b/image_720.webp"
                  class="category-image"
                  alt="Accessories"
                />
              </a>
              <p class="category-name">Accessories</p>
            </div>
          </div>
        </div>
      </div>
    </section>-->

    <?php bride_co_render_blog_posts('Latest Articles'); ?>


    <!-- 
    <section class="blog-section">
      <div class="container">
        <h2 class="fw-bold">Bride&Co Blog</h2>
        <div class="row g-4 mt-4 blog-container">
          <div class="col-md-3 d-flex">
            <div class="blog-card">
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/028a0f81-c856-4162-b6a2-a945cddf4ed0/image_1080.webp"
                class="blog-image"
                alt="Blog 1"
              />
              <p class="blog-title">
                Elegant Touches with Simple and Plain Wedding Dress Styles
              </p>
              <p class="blog-description">
                Plain wedding dress styles are known for their simple and non-voluminous forms...
              </p>
              <p class="blog-category">WEDDING DRESS THEMES</p>
              <a href="#" class="read-more">READ MORE</a>
            </div>
          </div>

          <div class="col-md-3 d-flex">
            <div class="blog-card">
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/e3203751-e634-4abb-b8bb-d2c2bee3786f/image_1080.webp"
                class="blog-image"
                alt="Blog 2"
              />
              <p class="blog-title">
                From Back Décolleté Wedding Dresses to Bust Décolleté Wedding Dress Models
              </p>
              <p class="blog-description">
                Décolleté wedding dress models are known as one of the most assertive styles...
              </p>
              <p class="blog-category">WEDDING DRESS THEMES</p>
              <a href="#" class="read-more">READ MORE</a>
            </div>
          </div>

          <div class="col-md-3 d-flex">
            <div class="blog-card">
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/722a5243-4ba2-48c0-8821-2daf382abe89/image_1080.webp"
                class="blog-image"
                alt="Blog 3"
              />
              <p class="blog-title">
                Feel Like a Princess with Princess Wedding Dress (Voluminous Wedding Dress Models)
              </p>
              <p class="blog-description">
                When we think of princess wedding dresses, royal weddings and classic models come to mind...
              </p>
              <p class="blog-category">WEDDING DRESS THEMES</p>
              <a href="#" class="read-more">READ MORE</a>
            </div>
          </div>

          <div class="col-md-3 d-flex">
            <div class="blog-card">
              <img
                src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/0a867423-27df-48fd-bdea-f9225ef848a9/image_1080.webp"
                class="blog-image"
                alt="Blog 4"
              />
              <p class="blog-title">
                Mermaid Wedding Dresses, Fitted Wedding Dress Models and General Mermaid Wedding Dress Trends
              </p>
              <p class="blog-description">
                Known as the wedding dress of all periods from past to present, mermaid models...
              </p>
              <p class="blog-category">WEDDING DRESS THEMES</p>
              <a href="#" class="read-more">READ MORE</a>
            </div>
          </div>
        </div>
      </div>
    </section>

       <section class="container text-center py-5">
      <h2 class="fw-bold">
        Oleg Cassini: Wedding Dress and Evening Wear Models for Every Style
      </h2>
      <p class="mt-3">
        All the wedding dress models or evening wear you choose at Bride & co are designed to make women look enchanting, whatever their style. In addition to wedding dress models and evening wear designs, a very wide collection of wedding accessories, from veils to cloaks, from lingerie to jewellery pouches, is offered to brides-to-be. It's possible to access all the details such as evening dresses, engagement and bridesmaid outfits, shoes, bags, accessories, gifts and children's clothes for weddings, engagements, graduations, hen parties and similar special occasions.
      </p>
    </section>-->

    <!-- Category Section -->
<section class="container my-5">
<h2 class="mb-4 fw-bold">Bride&co Real Brides</h2>
<p>SHOW OFF YOUR DREAM DAY IN YOUR BRIDE&CO WEDDING DRESS FOR A CHANCE TO BE OUR BRIDE OF THE MONTH AND FEATURED ACROSS OUR SOCIAL CHANNELS.</P>
      <div class="row g-4">
        <!-- First Card (Matric Dance) -->
        <div class="col-md-3">
            <!-- Link to another page -->
            <div class="category-card card-real-bride-1">
              <div class="category-content">
                <h2 class="category-title"></h2>
                <!--<a href="https://stage.brideandco.co.za/product-category/special-occasions/matric-dance/" class="shop-button"></a> -->
              </div>
            </div>
        </div>

        <!-- Second Card (Evening Gowns) -->
        <div class="col-md-3">
            <div class="category-card card-real-bride-2">
              <div class="category-content">
                <h2 class="category-title"></h2>
                <!--<a href="https://stage.brideandco.co.za/product-category/special-occasions/" class="shop-button">View here</a> -->
              </div>
            </div>
        </div>

        <!-- Third Card (Bride / Groom) -->
        <div class="col-md-3">
            <div class="category-card card-real-bride-3">
              <div class="category-content">
                <h2 class="category-title"></h2>
               <!-- <a href="https://stage.brideandco.co.za/product-category/mother-of-the-bride-groom/" class="shop-button">View here</a> -->
              </div>
            </div>
        </div>

        <!-- Fourth Card (New Arrivals) -->
        <div class="col-md-3">
            <div class="category-card card-real-bride-4">
              <div class="category-content">
                <h2 class="category-title"></h2>
                <!--<a href="https://stage.brideandco.co.za/product-category/new-arrivals/" class="shop-button">View here</a>-->
              </div>
            </div>
        </div>
      </div>
      <?php echo do_shortcode('[contact-form-7 id="e198ee2" title="Real Brides" html_id="RealBridesForm" html_class="real-brides-form]'); ?>

    </section>

    <?php bride_co_render_brand_logos(); ?>
    <!-- 
    <section class="container text-center py-5">
      <div class="row justify-content-center align-items-center">
        <div class="col-md-2 col-6">
          <a href="https://www.olegcassini.com" target="_blank">
            <img
              src="https://randevu.olegcassini.com.tr/image/logo.webp"
              alt="Oleg Cassini"
              class="img-fluid"
            />
          </a>
        </div>
        <div class="col-md-2 col-6">
          <a href="https://www.olegcassini.com" target="_blank">
            <img
              src="https://brideandco.co.za/wp-content/uploads/2022/05/cropped-BrideCo-Logo.png"
              alt="Oleg Cassini Collection"
              class="img-fluid"
            />
          </a>
        </div>
        <div class="col-md-2 col-6">
          <a href="https://www.olegcassini.com" target="_blank">
            <img
              src="https://www.viola-chan.com/wp-content/uploads/2020/06/cropped-logo-157x41.png"
              alt="Viola Chan"
              class="img-fluid"
            />
          </a>
        </div>
        <div class="col-md-2 col-6">
          <a href="https://www.olegcassini.com" target="_blank">
            <img
              src="https://brideandco.co.za/wp-content/uploads/2022/06/Eurosuit-Logo-1.png"
              alt="Oleg by Cassini"
              class="img-fluid"
            />
          </a>
        </div>
        <div class="col-md-2 col-6">
          <a href="https://www.olegcassini.com" target="_blank">
            <img
              src="/wp-content/themes/bride-co-child/assets/imgs/dreamon.png"
              alt="Dreamon for Oleg Cassini"
              class="img-fluid"
            />
          </a>
        </div>
      </div>
    </section>-->
    <?php bride_co_render_features(); ?>
    <!-- Features Section 
    <section class="container text-center py-5">
      <div class="row justify-content-center align-items-center">
        <div class="col-md-4 col-6">
          <img
            src="https://cdn.myikas.com/images/theme-images/03310641-9822-4fc3-927d-983f85d1baea/image_180.webp"
            alt="Secure Shopping"
            style="width: 50px; height: 50px"
          />
          <p class="mt-2">Secure Shopping</p>
        </div>

        <div class="col-md-4 col-6">
          <img
            src="https://cdn.myikas.com/images/theme-images/95b7493f-c23b-4f3a-9082-a5eff8d27b18/image_180.webp"
            alt="Free Shipping"
            style="width: 50px; height: 50px"
          />
          <p class="mt-2">Free Shipping on Purchases Over R 2,750</p>
        </div>

        <div class="col-md-4 col-6">
          <img
            src="https://cdn.myikas.com/images/theme-images/9a98ee6b-dd5a-48a3-8f0e-654dd1bfa07f/image_180.webp"
            alt="Returns/Exchanges"
            style="width: 50px; height: 50px"
          />
          <p class="mt-2">Returns/Exchanges Within 30 Days</p>
        </div>

        <div class="col-md-4 col-6">
          <img
            src="https://cdn.myikas.com/images/theme-images/9a98ee6b-dd5a-48a3-8f0e-654dd1bfa07f/image_180.webp"
            alt="Returns/Exchanges"
            style="width: 50px; height: 50px"
          />
          <p class="mt-2">Returns/Exchanges Within 30 Days</p>
        </div>
      </div>
    </section>-->
  </body>
</html>
<?php get_footer(); ?>