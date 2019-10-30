<?php /* Template Name: Actualités */

get_header();

while (have_posts()) {
    the_post(); ?>

    <div class="content actualites">

        <?php get_template_part( 'pages/actualities/header' ); ?>


        <section class="actualites__main">

            <div class="container">

                <div class="actualites__main-page nine cols">


                    <?php if ( have_rows( 'categories_darticles_a_afficher' ) ) { ?>
                        <div class="row">
                            <?php while (have_rows( 'categories_darticles_a_afficher' )) {
                                the_row();
                                $term_id = get_sub_field( 'categorie' );
                                if ( $term_id ) {

                                    set_query_var('term_id', $term_id);
                                    get_template_part('pages/actualities/loop-actu-group');
                                }
                            }
                            unset($term_id);
                            ?>
                        </div>
                    <?php } ?>

                    <?php get_template_part( 'pages/actualities/recent-posts' ); ?>

                    <?php if ( have_rows( 'categories_darticles_suivantes' ) ) { $i = 0;?>
                        <div class="row">
                            <?php while (have_rows( 'categories_darticles_suivantes' )) {
                                the_row();
                                $i++;
                                $term_id = get_sub_field( 'categorie' );
                                if ( $term_id ) {
                                    set_query_var('term_id', $term_id);
                                    get_template_part('pages/actualities/loop-actu-group');
                                }
                                if ($i == 2) {
                                    echo "</div><div class=\"row\">";
                                }
                            } ?>
                        </div>
                    <?php } ?>


                </div>

                <?php get_template_part( 'template-parts/blog', 'sidebar' ); ?>

            </div>


            <div class="container">

                <?php if ( is_active_sidebar( 'post-newsletter' ) ) {
                    dynamic_sidebar( 'post-newsletter' );
                } ?>

            </div>
        </section>

    </div><!-- .content -->

<?php } // End of the loop.

get_footer();