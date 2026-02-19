<?php
/**
 * Template Name: Blog Archive
 *
 * This template displays a custom archive layout for blog posts.
 *
 * @package Bride_Co_Theme
 */
get_header(); 
?>

<style>
    /* Blog Archive Styling - Extending previous blog section styles */
    .blog-container {
        padding: 60px 0;
        background-color: #f9f9f9;
    }

    .blog-container h2 {
        font-family: "Cinzel", serif;
        font-size: 2.5rem;
        margin-bottom: 30px;
        color: #333;
        text-align: center;
    }

    .blog-card {
        background-color: #fff;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: all 0.3s ease;
        margin-bottom: 30px;
    }

    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .blog-card-img-top {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }

    .blog-card-body {
        padding: 20px;
        flex-grow: 1;
    }

    .blog-category {
        font-family: "Poppins", sans-serif;
        font-size: 0.8rem;
        color: #999;
        text-transform: uppercase;
        margin-bottom: 10px;
        letter-spacing: 1px;
		display: none;
    }

    .blog-card-title {
        font-family: "Poppins", sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .blog-card-title a {
        color: #333;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .blog-card-title a:hover {
        color: #666;
    }

    .blog-card-text {
        font-family: "Poppins", sans-serif;
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 15px;
        flex-grow: 1;
    }

    .read-more {
        font-family: "Poppins", sans-serif;
        font-size: 0.9rem;
        font-weight: 600;
        color: #333;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.3s ease;
        align-self: center;
    }
    .read-more:hover {
        color: #999;
    }

    @media (max-width: 991px) {
        .blog-container h2 {
            font-size: 2rem;
        }
    }

    @media (max-width: 767px) {
        .blog-card {
            margin-bottom: 30px;
        }
    }

    .blog-pagination-wrapper {
        text-align: center;
        margin-top: 40px;
    }

    .blog-pagination ul.page-numbers {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding-left: 0;
        list-style: none;
        margin: 2rem 0 0 0;
    }

    .blog-pagination ul.page-numbers li {
        display: inline-block;
    }

    .blog-pagination ul.page-numbers a,
    .blog-pagination ul.page-numbers span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-family: "Poppins", sans-serif;
        font-size: 1rem;
        color: #000;
        text-decoration: none;
        background-color: #fff;
        transition: all 0.2s ease;
    }

    .blog-pagination ul.page-numbers a:hover {
        background-color: #f2f2f2;
    }

    .blog-pagination ul.page-numbers .current {
        background-color: #000;
        color: #fff;
        border-color: #000;
        pointer-events: none;
    }
</style>

<div class="container blog-container mt-5">
    <h2 class="mb-4"><?php echo esc_html( get_bloginfo( 'name' ) ); ?> Blog</h2>

    <div class="row">
        <?php
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => 9,
            'paged'          => $paged
        );
        $archive_query = new WP_Query( $args );

        if ( $archive_query->have_posts() ) :
            while ( $archive_query->have_posts() ) : $archive_query->the_post();
        ?>
            <div class="col-md-4 d-flex">
                <div class="blog-card card">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'medium_large', array( 'class' => 'blog-card-img-top', 'alt' => get_the_title() ) ); ?>
                    <?php else : ?>
                        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/default-blog.jpg" class="blog-card-img-top" alt="<?php esc_attr_e( 'Default Blog Image', 'bride-co' ); ?>">
                    <?php endif; ?>

                    <div class="blog-card-body d-flex flex-column">
                        <p class="blog-category">
                            <?php 
                            $categories = get_the_category();
                            if ( ! empty( $categories ) ) {
                                echo esc_html( $categories[0]->name );
                            }
                            ?>
                        </p>

                        <h5 class="blog-card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h5>

                        <p class="blog-card-text">
                            <?php 
                            $excerpt = get_the_excerpt();
                            echo esc_html( wp_trim_words( $excerpt, 20, '...' ) ); 
                            ?>
                        </p>

                        <a href="<?php the_permalink(); ?>" class="read-more">Read More</a>
                    </div>
                </div>
            </div>
        <?php 
            endwhile;
        ?>
    </div> <!-- Close row -->

    <?php
        // Pagination
        $total_pages = $archive_query->max_num_pages;
        if ( $total_pages > 1 ) {
            echo '<div class="blog-pagination-wrapper">';
            echo '<nav class="blog-pagination" aria-label="Blog navigation">';
            echo paginate_links( array(
                'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                'total'        => $total_pages,
                'current'      => $paged,
                'format'       => '?paged=%#%',
                'show_all'     => false,
                'type'         => 'list',
                'end_size'     => 1,
                'mid_size'     => 2,
                'prev_next'    => true,
                'prev_text'    => '«',
                'next_text'    => '»',
                'add_args'     => false,
                'add_fragment' => '',
            ) );
            echo '</nav>';
            echo '</div>';
        }

        wp_reset_postdata();
        else :
    ?>
        <div class="col-12">
            <p class="text-center"><?php esc_html_e( 'Sorry, no posts found.', 'bride-co' ); ?></p>
        </div>
    </div> <!-- Close row -->
    <?php endif; ?>
</div>

<?php get_footer(); ?>
