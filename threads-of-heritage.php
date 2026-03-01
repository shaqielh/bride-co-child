<?php /* Template Name: Threads of heritage */ ?>

<?php get_header(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Bride Tribe Signup</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Swiper.js CSS -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" rel="stylesheet" />
    <!-- Swiper.js JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <style>
      body {
        margin: 0;
        padding: 0;
        font-family: "Poppins", sans-serif;
        background-color: #fff;
      }

      .custom-hero {
        display: flex;
        padding: 0;
        margin: 0;
        max-width: 100%;
        background-color: #f9f4ee; /* Updated background color */
        height: 70vh;
        overflow: hidden;
      }

      .custom-hero .text-content,.carousel-container {
        width: 50%;
        padding: 80px;
        background-color: #f9f4ee; /* Updated background color */
        display: flex;
        flex-direction: column;
        justify-content: center;
      }

      .custom-hero .text-content h1 {
        font-family: "Cinzel", serif;
        font-size: 42px;
        font-weight: 400;
        color: #d9c4b3;
        margin-bottom: 20px;
      }

      .custom-hero .text-content h2 {
        font-family: "Poppins", sans-serif;
        font-size: 18px;
        color: #5a5a5a;
        line-height: 1.5;
        margin-bottom: 15px;
        font-weight: 500;
      }

      .custom-hero .text-content p {
        font-family: "Poppins", sans-serif;
        font-size: 16px;
        color: #5a5a5a;
        line-height: 1.7;
        margin-bottom: 15px;
      }

      .shop-button {
        display: inline-block;
        background-color: black;
        color: white !important;
        font-weight: 600;
        padding: 14px 18px;
        border-radius: 5px;
        font-size: 14px;
        text-decoration: none;
        transition: background 0.3s ease-in-out;
        margin-top: 20px;
        width: fit-content;
      }

      .custom-hero .shop-button:hover {
        background-color: #b0b0b0;
      }

      .custom-hero .square-image,.bride-inspo .square-image{
        width: 50%;
        height: 100%;
      }

      .custom-hero .square-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
      }
      
      /* Perks Section */
      .perks-section {
        max-width: 1200px;
        margin: auto;
        padding: 60px 20px;
        display: flex;
        flex-direction: column; /* Changed to column layout */
        align-items: flex-start;
      }

      .perks-header {
        width: 100%;
        text-align: left;
        font-family: "Cinzel", serif;
        color: #333;
        position: relative;
        margin-bottom: 30px;
      }

      .perks-header h2 {
        font-size: 36px;
        font-weight: 700;
        color: #333;
        text-align: left; /* Aligned left */
      }

      .perks-highlight {
        display: inline-block;
        position: relative;
      }

      .perks-highlight::before {
        content: "";
        display: block;
        width: 80px;
        height: 6px;
        background-color: #ddcdbf;
        position: absolute;
        top: -10px;
        left: 0;
        background: repeating-linear-gradient(
          45deg,
          #ddcdbf,
          #ddcdbf 5px,
          transparent 5px,
          transparent 10px
        );
      }

      .perks-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
        width: 100%; /* Full width */
      }

      .perk-item {
        padding-bottom: 20px;
        border-bottom: 1px solid #e0e0e0;
      }

      .perk-item h3 {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        display: flex;
        align-items: center;
      }

      .perk-number {
        color: #ddcdbf;
        font-size: 24px;
        font-weight: 700;
        margin-right: 10px;
      }

      .perk-item p {
        font-size: 14px;
        color: #555;
        line-height: 1.6;
        margin-top: 8px;
      }

      /* Wedding Trends Section */
      .wedding-trends {
        position: relative;
        max-width: 100%;
        margin: auto;
        overflow: hidden;
        height: 70vh; /* Added fixed height */
      }

      .trends-image {
        width: 100%;
        height: 70vh;
        position: relative;
      }

      .trends-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
      
      /* Added background slider animation */
      .slider-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.3);
        z-index: 1;
      }

      .trends-overlay {
        position: absolute;
        bottom: 10%;
        left: 5%;
        background: #ddcdbf; /* Updated color */
        padding: 40px;
        max-width: 650px;
        color: white;
        border-radius: 8px;
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
        z-index: 2;
      }

      .trends-overlay h2 {
        font-family: "Cinzel", serif;
        font-size: 32px;
        font-weight: 700;
        color: white;
        margin-bottom: 15px;
      }

      .trends-overlay p {
        font-size: 16px;
        color: white;
        line-height: 1.6;
      }

      /* Bride Inspo Section */
      .bride-inspo {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        max-width: 100%;
        margin: auto;
        padding: 50px 20px;
      }

      /* Left Side - Carousel */
      .carousel-container {
        position: relative;
		    padding: 50px;
      }

      .swiper {
        width: 100%;
        height: 400px; /* Increased height for landscape mode */
        border-radius: 10px;
        overflow: hidden;
      }

      .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
      }

      .slide-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        text-align: center;
        z-index: 2;
        font-family: "Cinzel", serif;
        padding: 0 20px;
        width: 100%;
        pointer-events: none;
      }

      .slide-overlay h2 {
        font-size: 28px;
        font-weight: 600;
        text-shadow: 0px 3px 6px rgba(0, 0, 0, 0.4);
        color: white;
      }

      /* Swiper Navigation */
      .swiper-button-next,
      .swiper-button-prev {
        color: white;
      }

      .swiper-pagination {
        bottom: 10px !important;
      }

      /* Form Section */
      .bride-tribe {
        max-width: 1200px;
        margin: auto;
        background: #fff;
        padding: 50px 100px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        margin-top: 40px;
        margin-bottom: 50px;
      }

      .bride-tribe h2 {
        font-family: "Cinzel", serif;
        text-align: center;
        font-size: 28px;
        color: #333;
        margin-bottom: 30px;
      }

      .wpcf7-form {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
      }

      .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 15px;
      }

      .form-group label {
        font-size: 12px;
        font-weight: 600;
        color: #003b39;
        text-transform: uppercase;
        margin-bottom: 5px;
      }

      .form-group input {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        outline: none;
      }

      .sign-up-btn {
        grid-column: span 2;
        background: #f49ca8;
        color: #000;
        font-weight: 600;
        font-size: 16px;
        padding: 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s ease-in-out;
        margin-top: 15px;
      }

      .sign-up-btn:hover {
        background: #d94862;
        color: #fff;
      }

      .giveaway-text {
        text-align: center;
        font-size: 14px;
        color: #003b39;
        margin-top: 15px;
      }

      .styled-text {
        font-weight: 600;
      }

      .terms-text {
        text-align: center;
        font-size: 12px;
        margin-top: 10px;
      }

      .terms-text a {
        text-decoration: none;
        color: #000;
      }

      .highlight-text {
        color: #d94862;
      }
	  
	  .parallax {
  /* The image used */
  background-image: url("https://brideandco.co.za/wp-content/uploads/2026/01/17A4833.jpg");

  /* Set a specific height */
  min-height: 500px;

  /* Create the parallax scrolling effect */
  background-attachment: fixed;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}

      /* Responsive */
      @media screen and (max-width: 768px) {
        .custom-hero {
          flex-direction: column;
          height: auto;
        }

        .custom-hero .text-content,
        .custom-hero .square-image {
          width: 100%;
          height: 100%;
        }
        
        .custom-hero .text-content {
          padding: 40px 20px;
        }

        .custom-hero .square-image img {
          height: auto;
        }
        
        .perks-section {
          padding: 40px 20px;
        }
        
        .perks-header {
          width: 100%;
          text-align: center;
          margin-bottom: 30px;
        }
        
        .perks-header h2 {
          text-align: center;
        }
        
        .perks-grid {
          grid-template-columns: 1fr;
        }

        .trends-overlay {
          position: relative;
          max-width: 90%;
          padding: 30px;
          bottom: unset;
          left: unset;
          margin: 20px auto;
        }
        
        .bride-inspo {
          flex-direction: column;
        }
        
        .swiper {
          height: 250px;
        }
        
        .bride-tribe {
          padding: 30px 20px;
        }
        
        .wpcf7-form {
          grid-template-columns: 1fr;
        }
        
        .sign-up-btn {
          grid-column: span 1;
        }
		.custom-hero .text-content, .carousel-container{
			width: 100%;
		}
		.carousel-container{
			padding: 0px;
		}
		.custom-hero .square-image, .bride-inspo .square-image {
			width: 100%;
			height: 100%;
		}
		.parallax {
		  /* The image used */
		  background-image: url("https://brideandco.co.za/wp-content/uploads/2026/01/17A4833.jpg");

		  /* Set a specific height */
		  min-height: 500px;

		  /* Create the parallax scrolling effect */
		  background-attachment: unset;
		  background-position: center;
		  background-repeat: no-repeat;
		  background-size: cover;
		}
      }

      /* CF7 Form Styling for Bride Tribe */
.wpcf7-form {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    max-width: 1200px;
    margin: 0 auto;
}

.wpcf7-form .form-group,#mc-embedded-subscribe-form .mc-field-group {
     display: flex;
    flex-direction: column;
    margin-bottom: 15px;
    width: 48%;
    float: left;
    margin-right: 2%;
}

.wpcf7-form .full-width {
    grid-column: span 2;
}

.wpcf7-form label,.mc-field-group label {
    font-size: 12px;
    font-weight: 600;
    color: #003b39;
    text-transform: uppercase;
    margin-bottom: 5px;
    font-family: "Poppins", sans-serif;
}

.wpcf7-form input[type="text"],.mc-field-group input[type="text"]
.wpcf7-form input[type="email"],.mc-field-group input[type="email"]
.wpcf7-form input[type="tel"],.mc-field-group input[type="tel"]
.wpcf7-form input[type="date"],.mc-field-group input[type="date"]
.wpcf7-form select,.mc-field-group select
.wpcf7-form textarea,.mc-field-group textarea{
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    outline: none;
    width: 100%;
    font-family: "Poppins", sans-serif;
}

.wpcf7-form .sign-up-btn,#mc-embedded-subscribe-form .button {
    grid-column: span 2;
    background: #DDCDBF;
    color: #000;
    font-weight: 600;
    font-size: 16px;
    padding: 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s ease-in-out;
    margin: 15px auto;
    width: 30%;
    font-family: "Poppins", sans-serif;
	display: table;
}

.wpcf7-form .sign-up-btn:hover,#mc-embedded-subscribe-form .button:hover  {
    background: #333333;
    color: #fff;
}

/* Form validation styling */
.wpcf7-form .wpcf7-not-valid-tip {
    color: #d94862;
    font-size: 12px;
    margin-top: 5px;
}

.wpcf7-form .wpcf7-response-output {
    grid-column: span 2;
    margin-top: 20px;
    padding: 10px;
    border-radius: 4px;
    text-align: center;
}

/* Responsive adjustments */
@media screen and (max-width: 768px) {
    .wpcf7-form {
        grid-template-columns: 1fr;
    }
    
    .wpcf7-form .full-width,
    .wpcf7-form .sign-up-btn {
        grid-column: span 1;
    }
	.wpcf7-form .form-group,#mc-embedded-subscribe-form .mc-field-group {
     display: flex;
    flex-direction: column;
    margin-bottom: 15px;
    width: 100%;
    float: none;
    margin-right: 0;
}
}
@media screen and (max-width: 1400px) {
	.custom-hero{
		height: 100vh;
}
}
#mc_embed_signup input,#mc_embed_signup textarea ,#mc_embed_signup select {
    padding: 5px 10px !important;
    border-radius: 10px !important;
    background: rgb(221 205 191 / 14%);
    box-shadow: 2px 2px 1px 1px #888888;
}
#mc_embed_signup .small-meta{
	    display: table;
    margin-top: 10px;
}
.indicates-required{
	margin-bottom: 20px;
}

    </style>
  </head>
  <body>
    <!-- Welcome Section -->
    <section class="welcome-section custom-hero">
      <div class="text-content">
        <h1>Welcome, Brides.</h1>
        <h2>Meet your Digital 'I Do' Crew</h2>
        <p>
          Hey Beautiful Bride-to-Be,
        </p>
        <p>
          Welcome to BrideVibes, an exclusive space designed to make your
          journey to the aisle as magical as your love story! 🌸
        </p>
        <p>
          Dive into a world where we spill the tea on everything bridal, share
          the love, and maybe even win some cool stuff together. No strings
          attached, just good vibes and beautiful brides!
        </p>
        <a class="shop-button" href="#signup-form">Say Yes</a>
      </div>
      <div class="image-content square-image parallax">
       
      </div>
    </section>
	

<?php get_footer(); ?>
