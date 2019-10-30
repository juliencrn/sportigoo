<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package sportigoo
 */

get_header();
?>

    <section class="error-404 not-found content activities-search" style="width: 100%;min-height: 50vh;display: flex;">
        <div style="margin: auto;">
            <?php the_field( '404', 'option' ); ?>
        </div><!-- .page-header -->
    </section><!-- .error-404 -->

<?php
get_footer();
