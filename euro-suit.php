<?php
/* 
Template Name: Euro Suit Home
*/

// Get the header
get_header();
?>
<style>
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
<?php echo do_shortcode('[bride_slider id="20139576"] '); ?>
<?php eurosuit_render_category_cards(); ?>
<?php eurosuit_render_featured_products(); ?>
<?php eurosuit_render_white_banner(); ?>
<?php echo do_shortcode('[eurosuit_latest_products]'); ?>
<?php eurosuit_render_secondary_category_cards(); ?>


<section class="reviews-section py-5">
  <div class="container">
    <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
      <!-- Carousel Indicators -->
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
      </div>

      <!-- Carousel Items -->
      <div class="carousel-inner">
        <!-- Testimonial 1 -->
        <div class="carousel-item active">
          <div class="text-center">
            <div class="star-rating mb-3">★★★★★</div>
            <p class="review-text lead">
              I've been renting for years. Recently bought a suit. Excellent service from all and Richard who helped me.
            </p>
            <p class="review-author fw-bold">- Terence Tobin</p>
          </div>
        </div>

        <!-- Testimonial 2 -->
        <div class="carousel-item">
          <div class="text-center">
            <div class="star-rating mb-3">★★★★★</div>
            <p class="review-text lead">
              Fidel has sorted my suits out for years, the service is nothing short of impeccable. Always recommended to anyone needing a suit hire.
            </p>
            <p class="review-author fw-bold">- Lee Pullinger</p>
          </div>
        </div>
      </div>

      <!-- Carousel Controls -->
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


<?php echo do_shortcode('[euro_suit_video]'); ?>
<?php eurosuit_render_secondary_banner(); ?>
<?php eurosuit_render_features(); ?>



<?php
// Main page content
while (have_posts()) : the_post();
    the_content();
endwhile;

// Get the footer
get_footer();
?>