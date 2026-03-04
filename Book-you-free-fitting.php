<?php /* Template Name: book-your-free-fitting */ ?>

<?php get_header(); ?>

<style>
      body {
        font-family: "Poppins", sans-serif;
        background-color: #f8f9fa;
      }
      .hero-section {
        position: relative;
        background: url("/wp-content/uploads/2025/04/brideandco-booking-banner.jpg")
          no-repeat center center/cover;
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-left: 15%;
      }

      .hero-section h1{
        color:#fff;
        text-align:center!important;
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
      @media (max-width: 768px) {
        .hero-section {
          justify-content: center;
          padding-left: 0;
          height: 200px;
        }
      }

      /* Notice Banner */
      .booking-notice {
        background-color: #fff8f5;
        border: 1px solid #ddcdbf;
        border-left: 5px solid #ddcdbf;
        border-radius: 6px;
        padding: 30px 40px;
        margin: 50px auto;
        max-width: 680px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
      }

      .booking-notice .notice-icon {
        font-size: 36px;
        margin-bottom: 15px;
        display: block;
      }

      .booking-notice h2 {
        font-family: "Cinzel", serif;
        font-size: 20px;
        color: #333;
        margin-bottom: 12px;
        font-weight: 600;
      }

      .booking-notice p {
        font-size: 15px;
        color: #666;
        line-height: 1.7;
        margin-bottom: 25px;
      }

      .booking-notice .btn-find-store {
        display: inline-block;
        background-color: #333;
        color: #fff !important;
        padding: 12px 28px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-decoration: none;
        transition: background-color 0.3s ease;
      }

      .booking-notice .btn-find-store:hover {
        background-color: #808080;
      }
</style>

<div class="hero-section">
  <h1>Book Your Free Fitting</h1>
</div>

<div class="container my-5">

  <div class="booking-notice">
    <span class="notice-icon">🛠️</span>
    <h2>Booking System Temporarily Unavailable</h2>
    <p>We are currently experiencing technical difficulties with our online booking system. Please find your nearest store and make a booking telephonically or via WhatsApp — we'd love to hear from you!</p>
    <a href="/find-a-store" class="btn-find-store">Find a Store</a>
  </div>

  <?php
  // Bookly form temporarily disabled — re-enable once booking system is restored
   echo do_shortcode('[bookly-form hide="time_range"]'); 
  ?>

</div>

<?php get_footer(); ?>