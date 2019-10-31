<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package sportigoo
 */

get_header();

while (have_posts()) {
    the_post();
    $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );?>

    <div class="content single">
        <section>
            <div class="activity-preview" style="background-image: url(<?php echo $thumbnail_url ?>);">
                <div class="container">
                    <h1 class="t-center">
                        <?php the_title(); ?>
                    </h1>
                </div>
                <div class="activity-preview__filter"></div>
            </div>
        </section>
        <section class="actualites__main">
            <div class="container">
                <div class="categories">
                    <?php
                    $categories = get_the_category();
                    if ( !empty( $categories ) ) {
                        foreach ($categories as $cat) { ?>
                            <a class="categories__item" href="<?php echo get_category_link( $cat->cat_ID ); ?>">
                                <?php echo $cat->name ?>
                            </a>
                        <?php }
                    } ?>
                </div>

                <div class="actualites__main-page nine cols">
                    <div class="content blog_style">
                        <div class="row">
                            <?php the_content(); ?>
                        </div>
                        <div class="row">
                            <?php
                            $link = get_the_permalink();
                            $share = "Partagez sur";
                            if (have_rows('blog_labels', 'option')) {
                                while (have_rows('blog_labels', 'option')) {
                                    the_row();
                                    $share = get_sub_field('partager');
                                }
                            }
                            ?>
                            <p class="blog__share t-blue"><?php echo $share ?>
                                <a class="icon" href="https://twitter.com/share?url=<?php echo $link ?>" target="_blank">
                                    <svg width="20" height="20">
                                        <use xlink:href="#twitter"></use>
                                    </svg>
                                </a>
                                <a class="icon" href="http://www.facebook.com/sharer.php?u=<?php echo $link ?>"
                                   target="_blank">
                                    <svg width="20" height="20">
                                        <use xlink:href="#facebook"></use>
                                    </svg>
                                </a>
                                <a class="icon"
                                   href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $link ?>"
                                   target="_blank">
                                    <svg width="20" height="20">
                                        <use xlink:href="#linkedin"></use>
                                    </svg>
                                </a>
                            </p>
                        </div>
                    </div>


                    <?php if ( is_active_sidebar( 'post-newsletter' ) ) {
                        dynamic_sidebar( 'post-newsletter' );
                    } ?>

                </div>

                <?php get_template_part( 'template-parts/blog', 'sidebar' ); ?>

            </div>

            <?php
            get_template_part( 'template-parts/related', 'post' );

            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }
            ?>
        </section>
    </div>

    <?php
}

get_footer();
