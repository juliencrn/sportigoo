<?php /* Template Name: Activités */

get_header();
$pattern = get_stylesheet_directory_uri() . "/dist/img/pattern-activite.png";

while (have_posts()) {
    the_post(); ?>

    <div class="content activities-archive">


        <?php
        set_query_var( 'product_id', get_field( 'activite_a_la_une' ) );
        get_template_part( 'template-parts/activity', 'header' ); ?>

        <?php get_template_part( 'pages/homepage/activities' ); ?>

        <section class="activities activities--categories">

            <?php
            $terms = get_terms( array(
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'parent' => 59 // Pour qui [EVG, Kids, Team, ...]
            ) );
            if ( !empty( $terms ) ) { ?>
                <div class="container">
                    <h3 class="section-title">
                        <?php the_field( 'titre_des_activites_pour_tous' ); ?>
                    </h3>
                </div>
                <div class="activities__row">
                <div class="activities__slider categoriesSlider">
                <?php foreach ($terms as $term) {
                    $image = get_field('image', $term) ?: "";
                    ?>
                    <div class="activities__item-wrapper">
                        <a class="activities__link" href="<?php echo get_term_link( $term, 'product_cat' ); ?>">
                            <div class="activities__item">
                                <div class="activities__img" style="background-image: url('<?php echo $image ?>');"></div>
                                <h4 class="activities__title">
                                    <?php echo $term->name ?>
                                </h4>
                                <p class="activities__discover">
                                    Découvrir
                                </p>
                                <div class="activities__filter"></div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
                </div>
            <?php } ?>
        </section>

        <section class="activities">
            <div class="activities__pattern" style="background-image: url('<?php echo $pattern ?>');"></div>
            <span class="section-separator"></span>

            <?php
            $terms = get_terms( array(
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'parent' => 58 // Types [sport, techno, danse, ...]
            ) );

            if ( !empty( $terms ) && function_exists( 'zz_display_product_row' ) ) {
                foreach ($terms as $term) { ?>
                    <div class="activities__block">
                        <div class="">
                            <h3 class="section-subtitle">
                                <?php echo $term->name ?>
                            </h3>
                        </div>
                        <?php zz_display_product_row( array(
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'product_cat',
                                    'field' => 'term_id',
                                    'terms' => $term->term_id,
                                ),
                            ),
                        ) ); ?>
                    </div>
                <?php }
            } ?>
        </section>
    </div>


<?php } // End of the loop.


get_footer();
