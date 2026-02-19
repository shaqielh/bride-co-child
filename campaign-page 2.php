<?php /* Template Name: campaign-page-2 */ ?>

<?php get_header(); ?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Black November Sale | Bride & Co</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <style>
      body {
        background-color: #f8f8f8;
        text-align: center;
        padding: 50px;
      }
      h2 {
        color: #c8ad96;
      }
      .sale-banner {
        background: url("image.png") no-repeat center center;
        background-size: cover;
        padding: 50px 0;
      }
      .cta-btn {
        background-color: #c8ad96;
        color: #fff;
        border: none;
        padding: 12px 24px;
        text-transform: uppercase;
        font-weight: bold;
        margin: 10px;
      }
      .cta-btn:hover {
        background-color: #c8ad96;
      }
      .mt-5{
        padding: 50px;
        background-color: #f8f8f8da;
      }
      .text-center{
        color: #404040;
      }
      /* Product Card Styling */
      .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
        padding: 50px;

      .products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
      }

      /* Common Product Card Styling */
      .product-card {
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease-in-out;
        background-color: white;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        padding-bottom: 15px;
        height: 100%;
      }

      .product-card:hover {
        transform: scale(1.02);
      }

      /* Image Container */
      .image-container {
        position: relative;
        width: 100%;
        overflow: hidden;
      }

      /* Product Images */
      .product-image,
      .hover-image {
        width: 100%;
        display: block;
        transition: opacity 0.3s ease-in-out;
      }

      .hover-image {
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
      }

      .image-container:hover .product-image {
        opacity: 0;
      }

      .image-container:hover .hover-image {
        opacity: 1;
      }

      /* Product Card Variants */
      .product-card-image {
        position: relative;
        overflow: hidden;
        margin-bottom: 10px;
      }

      .product-card-image img {
        width: 100%;
        height: auto;
        transition: transform 0.3s ease;
      }

      .product-card:hover .product-card-image img {
        transform: scale(1.05);
      }

      /* Product info */
      .product-card h5 {
        margin-top: 15px;
        font-weight: bold;
        font-size: 16px;
      }

      .product-card p {
        color: #666;
        margin-bottom: 8px;
        font-size: 14px;
      }

      .product-card-brand {
        font-size: 12px;
        color: #666;
        margin-bottom: 5px;
        text-align: center;
      }

      .product-card-title {
        font-size: 14px;
        color: #000;
        margin-bottom: 5px;
        text-align: center;
        height: 40px;
        overflow: hidden;
        padding: 0 10px;
      }

      /* Product Info (detail page) */
      .product-info {
        flex: 1;
        min-width: 300px;
      }

      .brand-name {
        font-size: 14px;
        color: #000;
        margin-bottom: 5px;
        font-weight: 500;
      }

      .product-title {
        font-size: 24px;
        font-weight: 400;
        margin-bottom: 10px;
        color: #000;
      }

      .stock-status {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        color: #ff0000;
        font-size: 14px;
      }

      .stock-status i {
        margin-right: 5px;
      }

      .product-code {
        font-size: 14px;
        color: #666;
        margin-bottom: 20px;
      }

      /* Label Styling */
      .label {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: black;
        color: white;
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 3px;
        z-index: 2;
      }

      .new-label {
        background-color: #777;
      }

      .sold-out-label {
        background-color: black;
      }

      .new-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: #777;
        color: white;
        padding: 3px 8px;
        font-size: 12px;
        font-weight: bold;
        border-radius: 3px;
        z-index: 2;
      }

      /* Price Styling */
      .price-container {
        margin-bottom: 20px;
        display: flex;
        align-items: center;
      }

      .discount-badge,
      .discount-box,
      .discount-badge-small {
        background-color: black;
        color: white;
        padding: 5px 8px;
        font-size: 12px;
        margin-right: 10px;
      }

      .discount-badge-small {
        position: absolute;
        bottom: 10px;
        left: 10px;
      }

      .price-box {
        display: flex;
        flex-direction: column;
      }

      .old-price,
      .product-card-old-price {
        text-decoration: line-through;
        color: #999;
        font-size: 14px;
        display: inline-block;
        margin-right: 5px;
      }

      .current-price,
      .new-price {
        font-size: 18px;
        font-weight: bold;
        color: #000;
      }

      .product-card-price {
        display: flex;
        flex-direction: column;
        align-items: center;
      }

      .product-card-old-price {
        font-size: 12px;
      }

      .product-card-current-price {
        font-size: 16px;
        font-weight: 500;
        color: #000;
      }

      /* Add to Cart Button */
      .add-to-cart-btn {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        font-weight: bold;
        padding: 10px 20px;
        border: 2px solid white;
        border-radius: 5px;
        text-decoration: none;
        display: none;
        font-size: 1rem;
        transition: all 0.3s ease-in-out;
      }

      .image-container:hover .add-to-cart-btn {
        display: block;
      }

      .add-to-cart-btn:hover {
        background-color: white;
        color: black;
      }

      /* Action Buttons */
      .add-to-cart {
        background-color: #333;
        color: #fff;
        border: none;
        padding: 15px;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.2s;
        width: 100%;
      }

      .add-to-cart:hover {
        background-color: #000;
      }

      .add-to-favorites {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: transparent;
        border: none;
        font-size: 24px;
        color: #333;
        cursor: pointer;
      }

      .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 20px;
      }

      /* Quantity controls */
      .quantity-container {
        display: flex;
        margin-bottom: 15px;
      }

      .quantity-btn {
        width: 40px;
        height: 40px;
        background-color: #fff;
        border: 1px solid #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
      }

      .quantity-input {
        width: 60px;
        height: 40px;
        text-align: center;
        border-top: 1px solid #e0e0e0;
        border-bottom: 1px solid #e0e0e0;
        border-left: none;
        border-right: none;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-10 mt-4">
          <img
            src="https://brideandco.co.za/wp-content/uploads/2024/11/November-Black-Friday-Promotions-36-1024x536.png"
            class="img-fluid"
            alt="Black November Sale Banner"
          />
        </div>
      </div>

      <div class="mt-5">
        <h6 class="text-uppercase fw-bold">
          Unveil unmatched savings this Black November
        </h6>
        <h2>Wedding Dresses from R4999</h2>
        <button class="cta-btn">Book a Fitting</button>
        <button class="cta-btn">Find a Store</button>
      </div>
    </div>

    <!-- New Heading Above Product Grid -->
    <div class="container mt-5">
      <h2 class="text-center fw-bold">Dream Day Looks from R4 999</h2>
    </div>

    <!-- Product Grid -->
    <div class="product-grid">
      <!-- Product 1 -->
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
          <a href="#" class="add-to-cart-btn">ADD TO BASKET</a>
        </div>
        <h5 class="mt-3 fw-bold">VIOLA CHAN</h5>
        <p>Red Strapped Lace Embroidered Evening Dress</p>
        <span class="discount-box">35%</span>
        <p>
          <span class="old-price">R 9,995.00</span>
          <span class="new-price">R 6,496.75</span>
        </p>
      </div>

      <!-- Product 2 -->
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
          <a href="#" class="add-to-cart-btn">ADD TO BASKET</a>
        </div>
        <h5 class="mt-3 fw-bold">OLEG BY OLEG CASSINI</h5>
        <p>Gold Colored Strapped Sequin Embroidered Mini Evening Dress</p>
        <span class="discount-box">20%</span>
        <p>
          <span class="old-price">R 6,995.00</span>
          <span class="new-price">R 5,596.00</span>
        </p>
      </div>

      <!-- Product 3 -->
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
          <a href="#" class="add-to-cart-btn">ADD TO BASKET</a>
        </div>
        <h5 class="mt-3 fw-bold">VIOLA CHAN</h5>
        <p>Black Leaf Motif Long Evening Dress</p>
        <span class="discount-box">35%</span>
        <p>
          <span class="old-price">R 14,995.00</span>
          <span class="new-price">R 9,746.75</span>
        </p>
      </div>

      <!-- Product 4 -->
      <div class="product-card">
        <div class="image-container">
          <span class="label new-label">NEW</span>
          <img
            src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/d0187652-045a-404b-9b71-bc28599f08ca/1950/10-11-oleg-cassini-lookbook-1274.webp"
            class="product-image"
            alt="Evening Dress 4"
          />
          <img
            src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/dfb1232d-df89-4736-a9ba-a38216315643/1950/10-11-oleg-cassini-lookbook-1290.webp"
            class="hover-image"
            alt="Evening Dress 4 Hover"
          />
          <a href="#" class="add-to-cart-btn">ADD TO BASKET</a>
        </div>
        <h5 class="mt-3 fw-bold">OLEG BY OLEG CASSINI</h5>
        <p>Gold Colored Strapped Sequin Embroidered Mini Evening Dress</p>
        <span class="discount-box">20%</span>
        <p>
          <span class="old-price">R 6,995.00</span>
          <span class="new-price">R 5,596.00</span>
        </p>
      </div>

      <!-- Product 5 -->
      <div class="product-card">
        <div class="image-container">
          <span class="label new-label">NEW</span>
          <img
            src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/d0187652-045a-404b-9b71-bc28599f08ca/1950/10-11-oleg-cassini-lookbook-1274.webp"
            class="product-image"
            alt="Evening Dress 5"
          />
          <img
            src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/dfb1232d-df89-4736-a9ba-a38216315643/1950/10-11-oleg-cassini-lookbook-1290.webp"
            class="hover-image"
            alt="Evening Dress 5 Hover"
          />
          <a href="#" class="add-to-cart-btn">ADD TO BASKET</a>
        </div>
        <h5 class="mt-3 fw-bold">OLEG BY OLEG CASSINI</h5>
        <p>Gold Colored Strapped Sequin Embroidered Mini Evening Dress</p>
        <span class="discount-box">20%</span>
        <p>
          <span class="old-price">R 6,995.00</span>
          <span class="new-price">R 5,596.00</span>
        </p>
      </div>

      <!-- Product 6 -->
      <div class="product-card">
        <div class="image-container">
          <span class="label new-label">NEW</span>
          <img
            src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/d0187652-045a-404b-9b71-bc28599f08ca/1950/10-11-oleg-cassini-lookbook-1274.webp"
            class="product-image"
            alt="Evening Dress 6"
          />
          <img
            src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/dfb1232d-df89-4736-a9ba-a38216315643/1950/10-11-oleg-cassini-lookbook-1290.webp"
            class="hover-image"
            alt="Evening Dress 6 Hover"
          />
          <a href="#" class="add-to-cart-btn">ADD TO BASKET</a>
        </div>
        <h5 class="mt-3 fw-bold">OLEG BY OLEG CASSINI</h5>
        <p>Gold Colored Strapped Sequin Embroidered Mini Evening Dress</p>
        <span class="discount-box">20%</span>
        <p>
          <span class="old-price">R 6,995.00</span>
          <span class="new-price">R 5,596.00</span>
        </p>
      </div>

      <!-- Product 7 -->
      <div class="product-card">
        <div class="image-container">
          <span class="label new-label">NEW</span>
          <img
            src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/d0187652-045a-404b-9b71-bc28599f08ca/1950/10-11-oleg-cassini-lookbook-1274.webp"
            class="product-image"
            alt="Evening Dress 7"
          />
          <img
            src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/dfb1232d-df89-4736-a9ba-a38216315643/1950/10-11-oleg-cassini-lookbook-1290.webp"
            class="hover-image"
            alt="Evening Dress 7 Hover"
          />
          <a href="#" class="add-to-cart-btn">ADD TO BASKET</a>
        </div>
        <h5 class="mt-3 fw-bold">OLEG BY OLEG CASSINI</h5>
        <p>Gold Colored Strapped Sequin Embroidered Mini Evening Dress</p>
        <span class="discount-box">20%</span>
        <p>
          <span class="old-price">R 6,995.00</span>
          <span class="new-price">R 5,596.00</span>
        </p>
      </div>

      <!-- Product 8 -->
      <div class="product-card">
        <div class="image-container">
          <span class="label new-label">NEW</span>
          <img
            src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/d0187652-045a-404b-9b71-bc28599f08ca/1950/10-11-oleg-cassini-lookbook-1274.webp"
            class="product-image"
            alt="Evening Dress 8"
          />
          <img
            src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/dfb1232d-df89-4736-a9ba-a38216315643/1950/10-11-oleg-cassini-lookbook-1290.webp"
            class="hover-image"
            alt="Evening Dress 8 Hover"
          />
          <a href="#" class="add-to-cart-btn">ADD TO BASKET</a>
        </div>
        <h5 class="mt-3 fw-bold">OLEG BY OLEG CASSINI</h5>
        <p>Gold Colored Strapped Sequin Embroidered Mini Evening Dress</p>
        <span class="discount-box">20%</span>
        <p>
          <span class="old-price">R 6,995.00</span>
          <span class="new-price">R 5,596.00</span>
        </p>
      </div>

      <!-- Product 9 -->
      <div class="product-card">
        <div class="image-container">
          <span class="label new-label">NEW</span>
          <img
            src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/d0187652-045a-404b-9b71-bc28599f08ca/1950/10-11-oleg-cassini-lookbook-1274.webp"
            class="product-image"
            alt="Evening Dress 9"
          />
          <img
            src="https://cdn.myikas.com/images/e99580d7-47a2-4686-b55b-0172e2768516/dfb1232d-df89-4736-a9ba-a38216315643/1950/10-11-oleg-cassini-lookbook-1290.webp"
            class="hover-image"
            alt="Evening Dress 9 Hover"
          />
          <a href="#" class="add-to-cart-btn">ADD TO BASKET</a>
        </div>
        <h5 class="mt-3 fw-bold">OLEG BY OLEG CASSINI</h5>
        <p>Gold Colored Strapped Sequin Embroidered Mini Evening Dress</p>
        <span class="discount-box">20%</span>
        <p>
          <span class="old-price">R 6,995.00</span>
          <span class="new-price">R 5,596.00</span>
        </p>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php get_footer(); ?>
