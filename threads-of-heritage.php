<?php /* Template Name: Threads of heritage */ ?>

<?php get_header(); ?>
    <style>
    
      .custom-hero {
        display: flex;
        padding: 0;
        margin: 0;
        max-width: 100%;
        background-color: #f9f4ee; /* Updated background color */
        height: 70vh;
        overflow: hidden;
      }

      .threads-hero-img{
        background-image: url('https://brideandco.co.za/wp-content/uploads/2026/03/Landing-Page-image-1080x1350-1.jpg');
          min-height: 500px;
  /* Create the parallax scrolling effect */
background-position: center left;
    background-repeat: no-repeat;
    background-size: contain;
      }

      	.custom-hero .square-image, .bride-inspo .square-image {
			width: 40%;
			height: 100%;
		}

      .custom-hero .text-content,.carousel-container {
        width: 50%;
        padding-left:0;
        padding-right:80px;
        padding-top:80px;
        padding-bottom:80px;
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

   
      .custom-hero .square-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
      }
@media screen and (max-width: 1440px) {
.custom-hero .text-content, .carousel-container{
padding-left:80px;
}
.shop-button{
    margin-right: 20px;
}
}

      /* Responsive */
      @media screen and (max-width: 768px) {
        .custom-hero {
          flex-direction: column;
          height: auto!important;
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

@media screen and (max-width: 1400px) {
	.custom-hero{
		height: 100vh;
}
}
@media screen and (max-width: 1024px) {
  .custom-hero {
    flex-direction: column;
    height: auto;
  }

  .custom-hero .square-image,
  .bride-inspo .square-image {
    width: 100%;
    height: 500px;
    background-position: top;
  }

  .custom-hero .text-content,
  .carousel-container {
    width: 100%;
    padding: 40px 30px;
  }

  .custom-hero .text-content h1 {
    font-size: 32px;
  }

  .custom-hero .text-content h2 {
    font-size: 16px;
  }

  .custom-hero .text-content p {
    font-size: 14px;
  }

  .threads-hero-img {
    background-size: cover;
    background-position: center;
    min-height: 400px;
  }
}
    </style>

    <!-- Welcome Section -->
    <section class="welcome-section custom-hero thrads-hero">
         <div class="image-content square-image threads-hero-img">
       
      </div>
      <div class="text-content">
        <h1>THREADS OF HERITAGE</h1>
        
        <p>
         At Bride & Co, our Threads of Heritage service is rooted in inclusivity, ensuring that every bride feels seen, celebrated, and authentically represented. Brides can select any gown, from 
         Bride & Co stores and personalise it with a heritage pattern, that reflects thier culture, blending the richness of South African fabric, beadwork, colour, and adornment with modern bridal elegance.
        </p>
        <p>
         Designed as a meaningful and elevated experience, our expert alteration team, thoughfully integrates these cultural elements into your chosen gown, whether through subtle detailing or bold statement features, ensuring seamless craftsmanship and a flawless fit. 
         The results is more than a dress, it is a personal expression of heritage, inclusivity, and individuality, where contemporary sophisticatio meets cultural pride in a gown that is uniquely and beautifully yours. 
        </p>
        <div class="d-none d-md-inline-flex justify-content-between">
         <a class="shop-button" href="/book-your-free-fitting" target="_blank" rel="noopener noreferrer">Book a fitting</a>
        <a class="shop-button" href="/wp-content/uploads/2026/03/Online-Knowledge-Book-Qwerty.pdf" target="_blank" rel="noopener noreferrer">Download Knowledge Book</a>
        <a class="shop-button" href="https://brideandco.co.za/wp-content/uploads/2026/03/Threads-of-Heritage-Lookbook_compressed.pdf" target="_blank" rel="noopener noreferrer">Download Lookbook</a>
       <!-- <a class="shop-button" href="/wp-content/uploads/2026/03/Threads-of-Heritage-Pricelist.pdf" target="_blank" rel="noopener noreferrer">Pricelist</a> --> 
</div>
 <a class="shop-button d-md-none" href="/book-your-free-fitting" target="_blank" rel="noopener noreferrer">Book a fitting</a>
        <a class="shop-button d-md-none" href="/wp-content/uploads/2026/03/Online-Knowledge-Book-Qwerty.pdf" target="_blank" rel="noopener noreferrer">Download Knowledge Book</a>
        <a class="shop-button d-md-none" href="https://brideandco.co.za/wp-content/uploads/2026/03/Threads-of-Heritage-Lookbook_compressed.pdf" target="_blank" rel="noopener noreferrer">Download Lookbook</a>
       <!-- <a class="shop-button d-md-none" href="/wp-content/uploads/2026/03/Threads-of-Heritage-Pricelist.pdf" target="_blank" rel="noopener noreferrer">Pricelist</a>  -->
    
      </div>
     
    </section>
	

<?php get_footer(); ?>
