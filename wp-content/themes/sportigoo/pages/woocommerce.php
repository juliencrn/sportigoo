<?php
/**
 * The template for displaying all woocommerce pages
 *
 * Template name: Woocommerce
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sportigoo
 */

get_header();
?>

    <div id="primary" class="content">
        <main id="main" class="site-main container" style="margin-bottom: 50px">

            <?php
            while ( have_posts() ) :
                the_post();

                get_template_part( 'template-parts/content', 'page' );

                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;

            endwhile; // End of the loop.
            ?>

            <style>
                .entry-title {
                    text-align: center;
                    font-size: 30px;
                    margin-bottom: 0;
                    font-weight: 500;
                    padding: 100px 0 50px 0;
                }
            </style>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();