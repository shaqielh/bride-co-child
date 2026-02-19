<!DOCTYPE html>
<html lang="en">

<?php get_header(); ?> 

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Wedding Dress Showcase & Collection</title>
    <!-- Bootstrap 5 CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
    <style>
      body {
        background-color: #fff;
        font-family: "Poppins", sans-serif;
      }
      .content {
        max-width: 1000px;
        margin: 50px auto;
      }
      h1 {
        font-family: "Cinzel";
        font-size: 40px;
        line-height: 53px;
        color: #DDCDBF;
      }
      h2 {
        font-weight: bold;
        font-size: 28px;
        text-transform: lowercase;
        letter-spacing: 1px;
        text-align: center;
      }
      h3 {
        color: #808080;
        font-family: "Cinzel";
        font-size: 20px;
        line-height: 25px;
        letter-spacing: 0.4px;
      }
      p {
        color: #7a7a7a;
        font-size: 16px;
        max-width: 1000px;
        margin: 0 auto;
      }
      .image-container {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}
.image-right img,
.image-left img {
  max-width: 60%;
  border-radius: 5px;
  margin: 0; /* Remove negative margins */
}
.img-fluid {
  max-width: 95%; /* Adjust this value to control size */
  height: auto;
  display: block;
  margin: 0 auto; /* Centers image */
}
      .section {
        margin-bottom: 70px;
        page-break-before: always;
      }
      .custom-section {
        padding: 60px 0;
        width: 100%;
        page-break-before: always;
        /*height: 60vh;*/
      }
      .custom-image {
        max-width: 100%;
        height: auto;
        display: block;
		margin-right: 0;
        /*margin-left:500px;
        height: 60vh;*/
      }
      .custom-button {
        background-color: #ddcdbf;
        border: none;
        padding: 12px 30px;
        font-size: 16px;
        color: white !important;
        border-radius: 5px;
        display: inline-block;
        margin: 20px auto;
        text-align: center;
		text-decoration: none;
      }
	  .book-button {
        background-color: #404040;
        border: none;
        padding: 12px 30px;
        font-size: 16px;
        color: #fff !important;
        border-radius: 5px;
        display: inline-block;
        margin: 20px auto;
        text-align: center;
		text-decoration: none;
      }
      .container-top {
        padding: 100px 0 100px 50px;
        background-color: #f9f4ee;
        padding-bottom: 0px;
        padding-top: 0px;
        page-break-before: always;
      }
      .divider {
        width: 98%;
        height: 2px;
        background-color: #ddcdbf;
        margin: 20px auto;
      }
      .default-content {
        padding: 40px 0;
        max-width: 1000px;
        margin: 0 auto;
      }
      .default-content img {
        max-width: 100%;
        height: auto;
      }
	  .product-card p ,.product-card h5{
		  color: #000;
	  }
	  .product-card-link{
		  text-decoration: none;
	  }
      @media (max-width: 768px) {
  h1 {
    font-size: 28px;
    line-height: 38px;
    text-align: center;
  }

  h2 {
    font-size: 22px;
  }

  h3 {
    font-size: 18px;
    padding-left: 10px;
  }

  .custom-button {
    font-size: 14px;
    padding: 10px 20px;
  }

  .container-fluid {
    padding: 30px 15px;
  }

  .custom-section {
    height: auto;
    padding: 30px 15px;
  }

  p {
    font-size: 14px;
    padding: 0 10px;
  }
}
    </style>
  </head>
  <body>
    <!-- Featured Collection Section -->

    <?php if( have_rows('section_1') ): ?>
        <?php while( have_rows('section_1') ) : the_row(); ?>
			
			<div class="container-top custom-section">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-5 font-weight-light">
                        <?php the_sub_field('heading'); ?>
                        </h1>
                        <p class="lead pb-3">
                        <?php the_sub_field('lead_title'); ?>
                        </p>
                        <p class="text-muted">
                        <?php the_sub_field('content'); ?>
                        </p>
                        <a href="<?php the_sub_field('button'); ?>" class="custom-button">
                            <?php the_sub_field('button_name'); ?>
                        </a>
						 <a href="<?php the_sub_field('book_button_link'); ?>" class="book-button">
                            <?php the_sub_field('book_button'); ?>
                        </a>
                    </div>
                    <div class="col-md-4 pb-0">
                        <img
                            src="<?php the_sub_field('image'); ?>"
                            alt="Wedding Dress Collection"
                            class="custom-image"
                        />
                    </div>
                </div>
            </div>
			
		<?php endwhile; ?>
    <?php endif; ?>
	
    <!-- Default WordPress Content -->
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php if (!empty(get_the_content())) : ?>
                <div class="container default-content">
                    <div class="row">
                        <div class="col-12">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php endif; ?>
		
	<?php $row = 0;?>	
		<?php if( have_rows('other_section') ): ?>
            <?php while( have_rows('other_section') ) : the_row(); ?>
		
                <?php if( have_rows('section_2') ): ?>
                    <?php while( have_rows('section_2') ) : the_row(); ?>
		
                        <?php if ($row % 2 === 0) : ?>
                            <!-- Individual Dress Showcases (Even) -->
                            <div class="container section pt-5 pb-2">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h2><?php the_sub_field('heading'); ?></h2>
                                        <p>
                                        <?php the_sub_field('content'); ?>
                                        </p>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <img src="<?php the_sub_field('image'); ?>" alt="Wedding Dress" class="img-fluid">
										 
                                    </div>
									
									<?php the_sub_field('gallery-name'); 

									 $images = get_sub_field('gallery_image'); 

									 if( $images ):?>
										<?php foreach( $images as $image ): ?>
										<div class="col-md-3">
										<div class="gallery-img pt-3 pb-3">
									   <a href="<?php echo $image['url']; ?>">
										  <img src="<?php echo $image['sizes']['large']; ?>" alt="<?php echo $image['alt']; ?>" />
										   </a>
										   <p><?php echo $image['caption']; ?></p>
										</div>
										</div>
										<?php endforeach; ?>
									 <?php endif;  ?>
									</div>
                              
                            </div>
                        <?php else: ?>  
                            <!-- Individual Dress Showcases (Odd) -->
                            <div class="container section pt-2 pb-2">
                                <div class="row align-items-center">
                                    <div class="col-md-6 order-md-2">
                                        <h2><?php the_sub_field('heading'); ?></h2>
                                        <p>
                                        <?php the_sub_field('content'); ?>
                                        </p>
                                    </div>
                                    <div class="col-md-6 text-center order-md-1">
                                        <img src="<?php the_sub_field('image'); ?>" alt="Wedding Dress" class="img-fluid">
                                    </div>
								<?php the_sub_field('gallery-name'); 

									 $images = get_sub_field('gallery_image'); 

									 if( $images ):?>
										<?php foreach( $images as $image ): ?>
										<div class="col-md-3">
										<div class="gallery-img pt-3 pb-3">
									   <a href="<?php echo $image['url']; ?>">
										  <img src="<?php echo $image['sizes']['large']; ?>" alt="<?php echo $image['alt']; ?>" />
										   </a>
										   <p><?php echo $image['caption']; ?></p>
										</div>
										</div>
										<?php endforeach; ?>
									 <?php endif;  ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php $row++; ?>
                    <?php endwhile; ?>
                <?php endif; ?>
            <?php endwhile; ?>
        <?php endif; ?>
		
		<?php echo do_shortcode('[evening_dresses_archive]'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php get_footer(); ?>