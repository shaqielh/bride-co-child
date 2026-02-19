<?php get_header(); ?>
    <link href="/wp-content/themes/bride-co-child/blogs.css" rel="stylesheet">
<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>

            <div class="entry-content">
                <?php the_content(); ?>
            </div>

            <footer class="entry-footer">
                <?php
                wp_link_pages(array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'textdomain'),
                    'after'  => '</div>',
                ));
                ?>
            </footer>
        </article>
    <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>