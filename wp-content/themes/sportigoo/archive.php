e<?php
/**
 * The template for displaying posts archive page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sportigoo
 */

get_header();


if ( is_category() ) {
    $title = single_cat_title( '', false );
} elseif ( is_tag() ) {
    $title = single_tag_title( '', false );
}


if ( have_posts() ) { ?>

    <div class="content activities-search">
        <section class="blog-search">
            <div class="container activities-search__header">
                <h1 class="section-title">
                    <?php echo $title; ?>
                </h1>
                <p class="section-paragraph">
                    <?php
                    $archive_desc = get_the_archive_description();
                    echo ($archive_desc) ? $archive_desc : 'Tous les articles de la catégories ' . $title;
                    ?>
                </p>
            </div>
            <div class="blog-search__block">
                <div class="blog-search__row container">
                    <?php while (have_posts()) {
                        the_post();
                        get_template_part( 'template-parts/content', 'post' );
                    } ?>
                </div>

                <div class="container">
                    <?php
                    $arrow_next = "<svg width='10' height='10'><use xlink:href='#chevron-left'></use></svg>";
                    $arrow_prev = "<svg width='10' height='10'><use xlink:href='#chevron-right'></use></svg>";

                    the_posts_navigation( array(
                        'prev_text' => "Articles plus anciens  " . $arrow_prev,
                        'next_text' => $arrow_next . "  Articles plus récents  ",
                    ) ); ?>
                </div>

            </div>
        </section>
    </div>

<?php } else {
    get_template_part('template-parts/content', 'none');
}

get_footer();
