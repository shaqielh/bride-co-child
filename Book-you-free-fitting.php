<?php /* Template Name: book-your-free-fitting */ ?>

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
        background: url("/wp-content/uploads/2025/04/brideandco-booking-banner.jpg")
          no-repeat center center/cover;
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: flex-start; /* Move content to the left */
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
        color: #white;
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
          justify-content: center; /* Center for smaller screens */
          padding-left: 0;
          height: 200px;
        }
      }
    </style>

<body class="fitting-guide">
  <div class="hero-section">
    <h1>Book Your Free Fitting</h1>
    
    
    </div>
  </div>
  <div class="container my-5">
  <?php echo do_shortcode('[bookly-form hide="time_range"]'); ?>

    </div> 


  <?php get_footer(); ?>
</body>
