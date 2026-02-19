<?php /* Template Name: Euro contact-us */ ?>

<?php get_header(); ?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
      rel="stylesheet"
    />
    <style>
      .full-width-top {
        background-color: #f5f5f5;
        width: 100%;
        padding: 40px 0;
        text-align: center;
      }
      .full-width-top .container {
        max-width: 800px;
        margin: auto;
        padding-bottom: 50px;
      }

      body {
        background-color: white;
        font-family: "Open Sans";
      }
      .container {
        max-width: 700px;
      }
      .text-center {
        max-width: 800px;
      }
      h1 {
        font-size: 50px;
        font-weight: 900;
        padding-bottom: 50px;
      }
      h2 {
        font-weight: 300;
        font-size: 30px;
      }
      .contact-form {
        border-top: none !important;
        box-shadow: none !important;
        margin-top: 0 !important;
        background: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: none;
        border-top: 2px solid #ddd;
        padding-top: 60px;
      }
      .btn-dark {
        background-color: black;
        border: none;
        font-weight: bold;
        padding: 12px;
        width: 100%;
        border-radius: 0px;
      }
      .btn-dark:hover {
        background-color: #333;
      }
      .form-label {
        font-weight: 400;
      }
      .input-group-text {
        background: transparent;
        border: none;
      }
      .form-check-label a {
        text-decoration: none;
        color: #007bff;
      }
      .form-check-label a:hover {
        text-decoration: underline;
      }
      .contact-form hr {
        margin: 15px 0;
        border: none;
        border-top: 1px solid #ddd;
      }
      .form-control {
        border: none;
        border-bottom: 1px solid gray;
        border-radius: 0;
        box-shadow: none;
      }
      .form-control:focus {
        box-shadow: none;
        border-bottom: 2px solid #007bff;
      }
	  .euro-contact .wpcf7-text{
		  width: 98%;
		  margin-right: 2%;
	  }
	  .euro-contact .wpcf7-submit{
		  background: #192f5a !important;
			color: #fff !important;
			margin: 0 auto;
			text-align: center;
			display: inherit;
	  }
	  label{
		  font-size: 15px;
	  }
    </style>
  </head>
  <body>
    <div class="full-width-top">
      <div class="container text-center mt-5">
        <h1>Need a Hand? Let’s Suit You Up.</h1>
        <p>
          Whether you’ve got questions about sizing, want to book a fitting, or just need help choosing between a few stylish looks for your big occasion – we’re here for it. Reach out via the form below, give us a call or a Whatsapp, or pop into your nearest Eurosuit store. Looking good starts with getting in touch.
        </p>
        
      </div>
    </div>

    <div class="container">
      <div class="contact-form euro-contact">
        <h2 class="text-center">Contact Form</h2>
        <p class="text-center">
          You can reach us by filling out the contact form below.<br />We will
          get back to you within working hours using the contact information you
          provided.
        </p>
<?php echo do_shortcode('[contact-form-7 id="22e6e48" title="Euro suit contact form"]'); ?>
        
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php get_footer(); ?>
