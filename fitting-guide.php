<?php
/**
 * Template Name: Fitting Guide Page
 */
get_header(); ?>

<!-- Enqueue additional styles if needed instead of using a separate <head> section -->
<link rel="stylesheet" href="/styles.css" />

<style>
  body.fitting-guide {
    font-family: "Poppins", sans-serif;
    background-color: #f8f9fa;
  }
  /* Hero Section */
  .hero-section {
    position: relative;
    background: url("https://prod.davidsbridal.com/content/dam/aem-integration/brand/how-to-measure-size-chart/GRP4_MNUDE_LING_JAN_21_003_DESKTOP_RESIZED.jpg")
      no-repeat center center/cover;
    height: 60vh;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    padding-left: 15%;
  }
  .hero-section .overlay {
    background-color: rgba(255, 255, 255, 0.8);
    padding: 40px;
    border-radius: 10px;
    max-width: 450px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  .overlay h1 {
    font-family: "Cinzel", serif;
    font-size: 28px;
  }
  .overlay h2 {
    font-size: 22px;
    font-weight: bold;
    margin-top: 10px;
  }
  .overlay p {
    font-size: 16px;
    color: #555;
  }
  .btn-custom {
    background-color: #000;
    color: #fff !important;
    padding: 10px 20px;
    border-radius: 5px;
    text-transform: uppercase;
    font-weight: bold;
    text-decoration: none;
    display: inline-block;
  }
  .btn-custom:hover {
    background-color: #808080;
    color: #fff;
  }

  /* Size Section */
  .size-section {
    text-align: left;
    padding: 60px 0;
  }
  .size-section h2 {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 30px;
  }
  .size-box {
    text-align: center;
    padding: 15px;
  }
  .size-box img {
    width: 100%;
     height: 200px;
    border-radius: 5px;
    object-fit: cover;
  }
  .size-box h3 {
    font-size: 18px;
    font-weight: bold;
    margin-top: 15px;
  }
  .size-box p {
    font-size: 14px;
    color: #555;
    margin-top: 5px;
  }

  /* Measuring Tips Section - Single Banner */
  .measuring-section {
    width: 100%;
    background-color: rgb(250,244,239);
    padding: 60px 0;
    text-align: center;
  }
  .measuring-section h2 {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 30px;
  }
  .measuring-container {
    display: flex;
    flex-wrap: nowrap;
    justify-content: space-around;
    align-items: flex-start;
    overflow-x: auto;
    padding: 0 20px;
  }
  .measure-box {
    flex: 0 0 18%; /* Adjust width as needed */
    text-align: center;
    padding: 20px;
    margin: 0 10px;
  }
  .measure-box img {
    width: 60px;
    height: auto;
    margin-bottom: 15px;
  }
  .measure-box h3 {
    font-size: 18px;
    font-weight: bold;
    margin-top: 10px;
  }
  .measure-box p {
    font-size: 14px;
    color: #555;
    margin: 0 auto;
  }

  /* Sizing Section */
  .sizing-section {
    display: flex;
    align-items: stretch;
    background-color: #f5e8dc;
  }
  .sizing-image, .sizing-text {
    width: 50%;
  }
  .sizing-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .sizing-text {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f5e8dc;
    padding: 60px;
  }
  .sizing-content {
    max-width: 500px;
    text-align: left;
  }
  .sizing-content h2 {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 15px;
  }
  .sizing-content p {
    font-size: 16px;
    color: #333;
    margin-bottom: 20px;
  }

  /* Mobile Adjustments */
  @media (max-width: 768px) {
    .hero-section {
      justify-content: center;
      padding-left: 0;
    }
    .sizing-section {
      flex-direction: column;
    }
    .sizing-image, .sizing-text {
      width: 100%;
    }
    .sizing-text {
      padding: 40px;
      text-align: center;
    }
    /* Allow measure boxes to shrink for small screens */
    .measure-box {
      flex: 0 0 40%;
    }
  }
</style>

<body class="fitting-guide">
  <!-- Hero Section -->
  <div class="hero-section">
    <div class="overlay">
      <h1>Bride &amp; Co</h1>
      <h2>How to Measure Guide</h2>
      <p>
        Follow these measuring tips and Bride &amp; Co size charts to find the size
        that’s right for you!
      </p>
      <a href="/book-your-free-fitting" class="btn-custom">Book Your Free Appointment</a>
    </div>
  </div>

  <!-- Size Section -->
  <div class="container size-section">
    <h2>WHAT'S YOUR SIZE?</h2>
    <div class="row">
      <div class="col-md-4">
        <div class="size-box">
          <img
            src="/wp-content/uploads/2025/04/1.-Start-with-your-undergarment-scaled.jpg"
            alt="Start with Undergarments"
          />
          <h3>1. Start with Undergarments</h3>
          <p>
            Put on whatever you plan to wear under your dress; do not measure over any other clothing.
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="size-box">
          <img
            src="/wp-content/uploads/2025/04/2.-Keep-it-level.png"
            alt="Keep It Level"
          />
          <h3>2. Keep It Level</h3>
          <p>
            Make sure the measuring tape is parallel to the floor and not too tight or too loose.
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="size-box">
          <img
            src="/wp-content/uploads/2025/04/Find-Your-Match-scaled.jpg"
            alt="Find Your Match"
          />
          <h3>3. Find Your Match</h3>
          <p>
            Look at the garment’s specific size chart to figure out your size (brands vary, so double-check!).
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Measuring Tips Section (All in One Banner) -->
  <div class="measuring-section">
    <h2>MEASURING TIPS</h2>
    <div class="measuring-container">
      <div class="measure-box">
        <img src="https://img.davidsbridal.com/is/image/DavidsBridalInc/Length_Icon?wid=222&hei=210" alt="Length" />
        <h3>Length</h3>
        <p>
          Measure from the center of the collarbone to the hem. Note: length is not measured from the neckline.
        </p>
      </div>
      <div class="measure-box">
        <img src="https://img.davidsbridal.com/is/image/DavidsBridalInc/Bust_Chest_Icon?wid=223&hei=210" alt="Bust/Chest" />
        <h3>Bust/Chest</h3>
        <p>
          Measure around the fullest part of the chest.
        </p>
      </div>
      <div class="measure-box">
        <img src="https://img.davidsbridal.com/is/image/DavidsBridalInc/Waist_Icon?wid=214&hei=210" alt="Waist" />
        <h3>Waist</h3>
        <p>
          Find the crease or natural waist and measure around the narrowest part.
        </p>
      </div>
      <div class="measure-box">
        <img src="https://img.davidsbridal.com/is/image/DavidsBridalInc/Hip_Icon?wid=228&hei=210" alt="Hips" />
        <h3>Hips</h3>
        <p>
          Stand with feet together and measure around the fullest part of the hips.
        </p>
      </div>
      <div class="measure-box">
        <img src="https://img.davidsbridal.com/is/image/DavidsBridalInc/Maternity_Icon?wid=178&hei=210" alt="Maternity" />
        <h3>Maternity</h3>
        <p>
          Order based on bust measurement. If newly expecting, order one size up.
        </p>
      </div>
    </div>
  </div>

  <!-- Sizing Section -->
  <div class="sizing-section">
    <div class="row no-gutters">
      <!-- Image Column -->
      <div class="col-md-6 sizing-image">
        <img src="https://prod.davidsbridal.com/content/dam/aem-integration/brand/how-to-measure-size-chart/BUSTFORMS_MULTI_DB_CRAFTSMAN_S20_04_RESIZED.jpg" alt="Mannequins for sizing" />
      </div>
      <!-- Text Column -->
      <div class="col-md-6 sizing-text">
        <div class="sizing-content">
          <h2>Bride &amp; Co's Sizing</h2>
          <p>
            We took thousands of real women’s measurements so our designs will fit you better—and they’re true to size!
          </p>
          <a href="/wp-content/uploads/2025/04/BrideCo-Size-Guides.pdf" target="_blank" class="btn-custom">Download our size guide</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Partners Section -->
  <section class="container text-center py-5">
    <div class="row justify-content-center align-items-center">
      <div class="col-md-2 col-6">
        <a href="https://www.olegcassini.com" target="_blank">
          <img src="/wp-content/uploads/2025/04/Untitled-design.png" alt="Bride &amp; Co" class="img-fluid" />
        </a>
      </div>
      <div class="col-md-2 col-6">
        <a href="https://www.olegcassini.com" target="_blank">
          <img src="/wp-content/uploads/2025/03/logo.webp" alt="Oleg Cassini" class="img-fluid" />
        </a>
      </div>
      <div class="col-md-2 col-6">
        <a href="https://www.olegcassini.com" target="_blank">
          <img src="/wp-content/uploads/2025/03/cropped-logo-157x41-1.png" alt="Viola Chan" class="img-fluid" />
        </a>
      </div>
      <div class="col-md-2 col-6">
        <a href="https://www.olegcassini.com" target="_blank">
          <img src="/wp-content/uploads/2025/03/Eurosuit-Logo-1-1.png" alt="Eurosuit" class="img-fluid" />
        </a>
      </div>
    </div>
  </section>
  

<?php get_footer(); ?>
</body>
