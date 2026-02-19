<?php /* Template Name: Euro suit men-fitting-guide-page */ ?>

<?php get_header(); ?>

<head>
  <link rel="stylesheet" href="/styles.css" />
</head>
<style>
      body {
        font-family: "Poppins", sans-serif;
        background-color: #f8f9fa;
      }
      .hero-section {
        position: relative;
        background: url("https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=2071&auto=format&fit=crop")
          no-repeat center center/cover;
        height: 60vh;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding-left: 15%;
      }
      .overlay {
        background-color: rgba(255, 255, 255, 0.8);
        padding: 40px;
        border-radius: 10px;
        max-width: 450px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }
      .overlay h1 {
        font-family: "cinzel", serif;
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
        color: #fff!important;
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
      .size-section {
        text-align: center;
        padding: 60px 0;
      }
      .size-section h2 {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 30px;
        text-align: left;
      }
      .size-box {
        text-align: center;
        padding: 15px;
      }
      .size-box img {
        width: 100%;
        height: auto;
        border-radius: 5px;
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
      .measuring-section {
        width: 100vw;
        margin-left: calc(-50vw + 50%);
        text-align: center;
        padding: 60px 0;
        background-color: rgb(240, 240, 245);
      }
      .measuring-section h2 {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 30px;
        text-align: left;
        padding-left:125px;
      }
      .measure-box {
        text-align: center;
        padding: 20px;
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
        max-width: 250px;
        margin: 0 auto;
      }
      .sizing-section {
        width: 100%;
        display: flex;
        align-items: stretch;
        background-color: #e8e8e8;
      }

      .sizing-image {
        width: 50%;
        height: auto;
        overflow: hidden;
      }

      .sizing-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      .sizing-text {
        width: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e8e8e8;
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
      
      .fit-styles {
        padding: 60px 0;
        background-color: #f8f9fa;
      }
      
      .fit-styles h2 {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 30px;
        text-align: center;
      }
      
      .fit-box {
        text-align: center;
        padding: 20px;
        margin-bottom: 20px;
      }
      
      .fit-box img {
        width: 100%;
        height: auto;
        border-radius: 5px;
        margin-bottom: 15px;
      }
      
      .fit-box h3 {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
      }
      
      .fit-box p {
        font-size: 14px;
        color: #555;
      }
      
      /* Mobile adjustments */
      @media (max-width: 768px) {
        .sizing-section {
          flex-direction: column;
        }

        .sizing-image,
        .sizing-text {
          width: 100%;
        }

        .sizing-text {
          padding: 40px;
          text-align: center;
        }
      }
      @media (max-width: 768px) {
        .hero-section {
          justify-content: center;
          padding-left: 0;
        }
      }
    </style>

<body class="fitting-guide">
  <div class="hero-section">
    <div class="overlay">
      <h1>Euro Suit</h1>
      <h2>Men's Suit Measuring Guide</h2>
      <p>
        Follow these measuring tips and Euro Suit size charts to find the perfect suit that fits you like it was made just for you!
      </p>
      <a href="/book-your-free-fitting" class="btn-custom">Book Your Free Fitting</a>
    </div>
  </div>

  <div class="container size-section">
    <h2>BEFORE YOU MEASURE</h2>
    <div class="row">
      <div class="col-md-4">
        <div class="size-box">
          <img
            src="https://stage.brideandco.co.za/wp-content/uploads/2025/04/siora-photography-cixohzDpNIo-unsplash.jpg"
            alt="Get the Right Tools"
          />
          <h3>1. Get the right tools</h3>
          <p>
            Use a flexible measuring tape and have someone assist you for the most accurate measurements.
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="size-box">
          <img
            src="https://stage.brideandco.co.za/wp-content/uploads/2025/04/nimble-made-GDebkNryTd4-unsplash-1.jpg"
            alt="Wear the Right Clothes"
          />
          <h3>2. Wear the right clothes</h3>
          <p>
            Measure over a dress shirt and without a jacket. Remove bulky items from pockets.
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="size-box">
          <img
            src="https://stage.brideandco.co.za/wp-content/uploads/2025/04/hunters-race-MYbhN8KaaEc-unsplash.jpg"
            alt="Stand Naturally"
          />
          <h3>3. Stand naturally</h3>
          <p>
            Keep your posture natural and relaxed. Don't flex or pull in your stomach during measurements.
          </p>
        </div>
      </div>
    </div>
  </div>

  <div class="measuring-section">
    <h2>KEY MEASUREMENTS</h2>

    <!-- First row with 3 items -->
    <div class="row justify-content-center">
      <div class="col-md-4 col-6">
        <div class="measure-box">
          <img
            src="https://stage.brideandco.co.za/wp-content/uploads/2025/04/siora-photography-cixohzDpNIo-unsplash.jpg"
            alt="Chest"
            style="width: 60px; height: 60px; object-fit: cover;"
          />
          <h3>chest</h3>
          <p>
            Measure around the fullest part of your chest, keeping the tape parallel to the floor.
          </p>
        </div>
      </div>
      <div class="col-md-4 col-6">
        <div class="measure-box">
          <img
            src="https://stage.brideandco.co.za/wp-content/uploads/2025/04/siora-photography-cixohzDpNIo-unsplash.jpg"
            alt="Waist"
            style="width: 60px; height: 60px; object-fit: cover;"
          />
          <h3>waist</h3>
          <p>Measure around your natural waistline, about 1 inch above your belly button.</p>
        </div>
      </div>
      <div class="col-md-4 col-6">
        <div class="measure-box">
          <img
            src="https://stage.brideandco.co.za/wp-content/uploads/2025/04/siora-photography-cixohzDpNIo-unsplash.jpg"
            alt="Shoulders"
            style="width: 60px; height: 60px; object-fit: cover;"
          />
          <h3>shoulders</h3>
          <p>
            Measure from the end of one shoulder to the other, across the back.
          </p>
        </div>
      </div>
    </div>

    <!-- Second row with 3 items -->
    <div class="row justify-content-center">
      <div class="col-md-4 col-6">
        <div class="measure-box">
          <img
            src="https://stage.brideandco.co.za/wp-content/uploads/2025/04/siora-photography-cixohzDpNIo-unsplash.jpg"
            alt="Sleeve"
            style="width: 60px; height: 60px; object-fit: cover;"
          />
          <h3>sleeve length</h3>
          <p>
            Measure from shoulder seam to just below the wrist bone.
          </p>
        </div>
      </div>
      <div class="col-md-4 col-6">
        <div class="measure-box">
          <img
            src="https://stage.brideandco.co.za/wp-content/uploads/2025/04/siora-photography-cixohzDpNIo-unsplash.jpg"
            alt="Inseam"
            style="width: 60px; height: 60px; object-fit: cover;"
          />
          <h3>inseam</h3>
          <p>
            Measure from the crotch seam down to where you want the trouser to end.
          </p>
        </div>
      </div>
      <div class="col-md-4 col-6">
        <div class="measure-box">
          <img
            src="https://stage.brideandco.co.za/wp-content/uploads/2025/04/siora-photography-cixohzDpNIo-unsplash.jpg"
            alt="Neck"
            style="width: 60px; height: 60px; object-fit: cover;"
          />
          <h3>neck</h3>
          <p>
            Measure around your neck where a collar would sit, adding 1/2 inch for comfort.
          </p>
        </div>
      </div>
    </div>
  </div>

  <div class="container fit-styles">
    <h2>SUIT FIT STYLES</h2>
    <div class="row">
      <div class="col-md-4">
        <div class="fit-box">
          <img
            src="https://stage.brideandco.co.za/wp-content/uploads/2025/04/luwadlin-bosman-NFUTqf1CGFk-unsplash.jpg"
            alt="Classic Fit"
          />
          <h3>Classic Fit</h3>
          <p>
            Offers a timeless, comfortable silhouette with room in the chest and waist. Perfect for business and formal occasions.
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="fit-box">
          <img
            src="https://stage.brideandco.co.za/wp-content/uploads/2025/04/mohammad-hossein-mirzagol-T8leCXVWEjc-unsplash.jpg"
            alt="Slim Fit"
          />
          <h3>Slim Fit</h3>
          <p>
            Tailored closer to the body with a narrower shoulder and tapered waist for a modern, streamlined look.
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="fit-box">
          <img
            src="https://stage.brideandco.co.za/wp-content/uploads/2025/04/salvador-godoy-ksLWYYmK-0k-unsplash-scaled.jpg"
            alt="Tailored Fit"
          />
          <h3>Tailored Fit</h3>
          <p>
            The perfect middle ground between classic and slim, offering a shaped silhouette with comfortable movement.
          </p>
        </div>
      </div>
    </div>
  </div>

  <div class="sizing-section">
    <div class="row no-gutters">
      <!-- Image Column -->
      <div class="col-md-6 sizing-image">
        <img
          src="https://stage.brideandco.co.za/wp-content/uploads/2025/04/christian-erra-jZbGE9odDAE-unsplash-scaled.jpg"
          alt="Suit tailoring"
        />
      </div>
      <!-- Text Column -->
      <div class="col-md-6 sizing-text">
        <div class="sizing-content">
          <h2>Euro Suit tailoring expertise</h2>
          <p>
            Our skilled tailors ensure every Euro Suit fits perfectly. After selecting your size, we offer complimentary basic alterations to achieve that custom-made look.
          </p>
          <a href="#" class="btn-custom">BOOK A TAILORING APPOINTMENT</a>
        </div>
      </div>
    </div>
  </div>

  <section class="container text-center py-5">
    <div class="row justify-content-center align-items-center">
      <div class="col-md-2 col-6">
        <a href="https://www.brideandco.co.za" target="_blank">
          <img
            src="https://brideandco.co.za/wp-content/uploads/2022/05/cropped-BrideCo-Logo.png"
            alt="bride & co"
            class="img-fluid"
          />
        </a>
      </div>
      <div class="col-md-2 col-6">
        <a href="https://www.olegcassini.com" target="_blank">
          <img
            src="https://stage.brideandco.co.za/wp-content/uploads/2025/03/logo.webp"
            alt="Oleg Cassini"
            class="img-fluid"
          />
        </a>
      </div>
      <div class="col-md-2 col-6">
        <a href="#" target="_blank">
          <img
            src="https://cdn.myikas.com/images/theme-images/39478211-c7ac-43b0-ac4a-a15e28c3d599/image_1512.webp"
            alt="Viola Chan"
            class="img-fluid"
          />
        </a>
      </div>
      <div class="col-md-2 col-6">
        <a href="#" target="_blank">
          <img
            src="https://stage.brideandco.co.za/wp-content/uploads/2025/03/Eurosuit-Logo-1-1.png"
            alt="Eurosuit"
            class="img-fluid"
          />
        </a>
      </div>
    </div>
  </section>

  <?php get_footer(); ?>
</body>