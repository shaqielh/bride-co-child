<?php /* Template Name: bride-vibes */ ?>

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

      .custom-hero .text-content {
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

      .custom-hero .square-image {
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
        max-width: 1200px;
        margin: auto;
        padding: 50px 20px;
      }

      /* Left Side - Carousel */
      .carousel-container {
        position: relative;
        width: 100%;
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

      .bride-form {
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

      /* Responsive */
      @media screen and (max-width: 768px) {
        .custom-hero {
          flex-direction: column;
          height: auto;
        }

        .custom-hero .text-content,
        .custom-hero .square-image {
          width: 100%;
          height: auto;
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
        
        .bride-form {
          grid-template-columns: 1fr;
        }
        
        .sign-up-btn {
          grid-column: span 1;
        }
      }

      /* CF7 Form Styling for Bride Tribe */
.bride-form {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    max-width: 1200px;
    margin: 0 auto;
}

.bride-form .form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 15px;
}

.bride-form .full-width {
    grid-column: span 2;
}

.bride-form label {
    font-size: 12px;
    font-weight: 600;
    color: #003b39;
    text-transform: uppercase;
    margin-bottom: 5px;
    font-family: "Poppins", sans-serif;
}

.bride-form input[type="text"],
.bride-form input[type="email"],
.bride-form input[type="tel"],
.bride-form input[type="date"],
.bride-form select,
.bride-form textarea {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    outline: none;
    width: 100%;
    font-family: "Poppins", sans-serif;
}

.bride-form .sign-up-btn {
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
    width: 100%;
    font-family: "Poppins", sans-serif;
}

.bride-form .sign-up-btn:hover {
    background: #d94862;
    color: #fff;
}

/* Form validation styling */
.bride-form .wpcf7-not-valid-tip {
    color: #d94862;
    font-size: 12px;
    margin-top: 5px;
}

.bride-form .wpcf7-response-output {
    grid-column: span 2;
    margin-top: 20px;
    padding: 10px;
    border-radius: 4px;
    text-align: center;
}

/* Responsive adjustments */
@media screen and (max-width: 768px) {
    .bride-form {
        grid-template-columns: 1fr;
    }
    
    .bride-form .full-width,
    .bride-form .sign-up-btn {
        grid-column: span 1;
    }
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
      <div class="image-content square-image">
        <img
          alt="Wedding Dress Hero"
          src="https://brideandco.co.za/wp-content/uploads/2025/03/17A4833.jpg"
        />
      </div>
    </section>
    
    <!-- Perks Section -->
    <section class="perks-section">
      <div class="perks-header">
        <h2>Unveiling the <span class="perks-highlight">Perks</span></h2>
      </div>
      <div class="perks-grid">
        <div class="perk-item">
          <h3>
            <span class="perk-number">01.</span> WIN R1000 CASH FOR YOUR DREAM
            DAY
          </h3>
          <p>
            Sign up and you're automatically in the running for our quarterly
            <strong>R1,000 cash giveaway</strong> – because every bride deserves
            a little extra sparkle for her dream day!
          </p>
        </div>
        <div class="perk-item">
          <h3>
            <span class="perk-number">02.</span> GRAB SOME GREAT GIFTS &amp;
            GIVEAWAYS
          </h3>
          <p>
            Get access to exclusive competitions, free gifts, and cool collabs
            from some of our incredible partners!
          </p>
        </div>
        <div class="perk-item">
          <h3>
            <span class="perk-number">03.</span> IT STARTS WITH A YES... BUT
            WHAT'S NEXT?
          </h3>
          <p>
            Dive into the wedding planning world with tips on finding your dream
            dress and other valuable nuggets of wisdom, including a free wedding
            planner and checklist.
          </p>
        </div>
        <div class="perk-item">
          <h3>
            <span class="perk-number">04.</span> CREATING YOUR BRIDAL VISION
          </h3>
          <p>
            Download our 2025 Wedding Trends Report to find inspiration for your
            dream day in 2025!
          </p>
        </div>
      </div>
    </section>
    
    <!-- Wedding Trends Section -->
    <section class="wedding-trends">
      <div class="trends-image">
        <div class="slider-background"></div>
        <img
          alt="Wedding Trends"
          src="https://brideandco.co.za/wp-content/uploads/2025/03/17A4833.jpg"
        />
      </div>
      <div class="trends-overlay">
        <h2>WEDDING DAY TRENDS REPORT</h2>
        <p>
          Explore the most captivating <strong>wedding day</strong> trends for
          the year 2025. From setting the perfect ambiance to identifying the
          colour combinations that celebrate life's big moments, we've also
          curated the essential wedding dress styles for this year. Sign up now
          to receive your free trend report!
        </p>
        <a class="shop-button" href="#signup-form">Sign Up</a>
      </div>
    </section>
    
    <!-- Bride Inspo Section -->
    <section class="bride-inspo">
      <!-- Left Side: Carousel -->
      <div class="carousel-container">
        <div class="swiper mySwiper">
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <img
                alt="Bride Inspo 1"
                src="https://brideandco.co.za/wp-content/uploads/2025/03/17A3921.jpg"
              />
              <div class="slide-overlay">
                <h2>Real Bride Inspo</h2>
              </div>
            </div>
            <div class="swiper-slide">
              <img
                alt="Bride Inspo 2"
                src="https://brideandco.co.za/wp-content/uploads/2025/03/2-1.jpg"
              />
              <div class="slide-overlay">
                <h2>Exclusive Perks</h2>
              </div>
            </div>
            <div class="swiper-slide">
              <img
                alt="Bride Inspo 3"
                src="https://brideandco.co.za/wp-content/uploads/2025/03/1-1.jpg"
              />
              <div class="slide-overlay">
                <h2>Gifts & Giveaways</h2>
              </div>
            </div>
            <div class="swiper-slide">
              <img
                alt="Bride Inspo 3"
                src="https://brideandco.co.za/wp-content/uploads/2025/03/4-1.jpg"
              />
              <div class="slide-overlay">
                <h2>Win R1 000 Cash</h2>
              </div>
            </div>
          </div>
          <!-- Navigation Arrows -->
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
          <!-- Pagination Dots -->
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </section>

    <!-- Signup Form Section -->
    <section class="bride-tribe" id="signup-form">
      <h2>Join your Bride Tribe</h2>
      <div class="container my-5"> 
      <?php echo do_shortcode('[contact-form-7 id="bc5165d" html_class="bride-form" title="BrideVibes"]'); ?>
      </div>
    </section>

    <!-- Initialize Swiper.js -->
    <script>
      var swiper = new Swiper(".mySwiper", {
        loop: true,
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
        autoplay: {
          delay: 5000,
        },
      });
    </script>
    <script>
// MailChimp Form Date Validation
// Add this script after your form or in your theme's footer

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('mc-embedded-subscribe-form');
    
    if (!form) return;
    
    // Get current date
    const today = new Date();
    const currentYear = today.getFullYear();
    const currentMonth = today.getMonth() + 1; // JavaScript months are 0-indexed
    const currentDay = today.getDate();
    
    // Get engagement date fields
    const engagementDay = document.getElementById('mce-MMERGE6-day');
    const engagementMonth = document.getElementById('mce-MMERGE6-month');
    const engagementYear = document.getElementById('mce-MMERGE6-year');
    
    // Get wedding date fields
    const weddingDay = document.getElementById('mce-MMERGE7-day');
    const weddingMonth = document.getElementById('mce-MMERGE7-month');
    const weddingYear = document.getElementById('mce-MMERGE7-year');
    
    // Function to validate engagement date (must be in the past)
    function validateEngagementDate() {
        const day = parseInt(engagementDay.value);
        const month = parseInt(engagementMonth.value);
        const year = parseInt(engagementYear.value);
        
        // Check if all fields are filled
        if (!day || !month || !year) return true; // Let required validation handle empty fields
        
        // Check if date is valid
        const engagementDate = new Date(year, month - 1, day);
        const todayDate = new Date(currentYear, currentMonth - 1, currentDay);
        
        // Remove time component for accurate comparison
        engagementDate.setHours(0, 0, 0, 0);
        todayDate.setHours(0, 0, 0, 0);
        
        if (engagementDate >= todayDate) {
            showError(engagementDay, 'Engagement date must be in the past');
            return false;
        }
        
        clearError(engagementDay);
        return true;
    }
    
    // Function to validate wedding date (must be in the future)
    function validateWeddingDate() {
        const day = parseInt(weddingDay.value);
        const month = parseInt(weddingMonth.value);
        const year = parseInt(weddingYear.value);
        
        // Check if all fields are filled
        if (!day || !month || !year) return true; // Let required validation handle empty fields
        
        // Check if date is valid
        const weddingDate = new Date(year, month - 1, day);
        const todayDate = new Date(currentYear, currentMonth - 1, currentDay);
        
        // Remove time component for accurate comparison
        weddingDate.setHours(0, 0, 0, 0);
        todayDate.setHours(0, 0, 0, 0);
        
        if (weddingDate <= todayDate) {
            showError(weddingDay, 'Wedding date must be in the future');
            return false;
        }
        
        clearError(weddingDay);
        return true;
    }
    
    // Function to show error message
    function showError(field, message) {
        // Remove existing error
        clearError(field);
        
        // Create error element
        const errorDiv = document.createElement('div');
        errorDiv.className = 'date-error-message';
        errorDiv.style.color = '#e74c3c';
        errorDiv.style.fontSize = '12px';
        errorDiv.style.marginTop = '5px';
        errorDiv.textContent = message;
        
        // Add error after the datefield div
        const dateField = field.closest('.datefield');
        if (dateField) {
            dateField.parentNode.insertBefore(errorDiv, dateField.nextSibling);
        }
        
        // Add red border to all date inputs in the group
        const allDateInputs = field.closest('.datefield').querySelectorAll('.datepart');
        allDateInputs.forEach(input => {
            input.style.borderColor = '#e74c3c';
        });
    }
    
    // Function to clear error message
    function clearError(field) {
        const dateField = field.closest('.datefield');
        if (dateField) {
            // Remove error message
            const errorMessage = dateField.parentNode.querySelector('.date-error-message');
            if (errorMessage) {
                errorMessage.remove();
            }
            
            // Reset border colors
            const allDateInputs = dateField.querySelectorAll('.datepart');
            allDateInputs.forEach(input => {
                input.style.borderColor = '';
            });
        }
    }
    
    // Add event listeners for real-time validation
    if (engagementDay && engagementMonth && engagementYear) {
        [engagementDay, engagementMonth, engagementYear].forEach(field => {
            field.addEventListener('blur', validateEngagementDate);
            field.addEventListener('input', function() {
                // Clear error when user starts typing
                if (this.value === '') {
                    clearError(this);
                }
            });
        });
    }
    
    if (weddingDay && weddingMonth && weddingYear) {
        [weddingDay, weddingMonth, weddingYear].forEach(field => {
            field.addEventListener('blur', validateWeddingDate);
            field.addEventListener('input', function() {
                // Clear error when user starts typing
                if (this.value === '') {
                    clearError(this);
                }
            });
        });
    }
    
    // Validate on form submission
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validate engagement date
        if (engagementDay && engagementMonth && engagementYear) {
            if (!validateEngagementDate()) {
                isValid = false;
            }
        }
        
        // Validate wedding date
        if (weddingDay && weddingMonth && weddingYear) {
            if (!validateWeddingDate()) {
                isValid = false;
            }
        }
        
        // Prevent form submission if validation fails
        if (!isValid) {
            e.preventDefault();
            
            // Scroll to first error
            const firstError = document.querySelector('.date-error-message');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
    
    // Additional validation for date format and valid dates
    function validateDateFormat(day, month, year) {
        // Check if day is valid (1-31)
        if (day < 1 || day > 31) return false;
        
        // Check if month is valid (1-12)
        if (month < 1 || month > 12) return false;
        
        // Check if year is reasonable (1900-2100)
        if (year < 1900 || year > 2100) return false;
        
        // Check if the date actually exists (handles leap years, etc.)
        const date = new Date(year, month - 1, day);
        return date.getFullYear() === year && 
               date.getMonth() === month - 1 && 
               date.getDate() === day;
    }
    
    // Enhanced validation with date format checking
    function validateEngagementDateEnhanced() {
        const day = parseInt(engagementDay.value);
        const month = parseInt(engagementMonth.value);
        const year = parseInt(engagementYear.value);
        
        if (!day || !month || !year) return true;
        
        // Check date format first
        if (!validateDateFormat(day, month, year)) {
            showError(engagementDay, 'Please enter a valid date');
            return false;
        }
        
        return validateEngagementDate();
    }
    
    function validateWeddingDateEnhanced() {
        const day = parseInt(weddingDay.value);
        const month = parseInt(weddingMonth.value);
        const year = parseInt(weddingYear.value);
        
        if (!day || !month || !year) return true;
        
        // Check date format first
        if (!validateDateFormat(day, month, year)) {
            showError(weddingDay, 'Please enter a valid date');
            return false;
        }
        
        return validateWeddingDate();
    }
    
    // Update event listeners to use enhanced validation
    if (engagementDay && engagementMonth && engagementYear) {
        [engagementDay, engagementMonth, engagementYear].forEach(field => {
            field.removeEventListener('blur', validateEngagementDate);
            field.addEventListener('blur', validateEngagementDateEnhanced);
        });
    }
    
    if (weddingDay && weddingMonth && weddingYear) {
        [weddingDay, weddingMonth, weddingYear].forEach(field => {
            field.removeEventListener('blur', validateWeddingDate);
            field.addEventListener('blur', validateWeddingDateEnhanced);
        });
    }
});

// CSS for better error styling (add to your stylesheet)
const style = document.createElement('style');
style.textContent = `
    .date-error-message {
        color: #e74c3c !important;
        font-size: 12px !important;
        margin-top: 5px !important;
        font-weight: normal !important;
    }
    
    .datepart.error {
        border-color: #e74c3c !important;
        box-shadow: 0 0 5px rgba(231, 76, 60, 0.3) !important;
    }
`;
document.head.appendChild(style);
      </script>
  </body>
</html>

<?php get_footer(); ?>
