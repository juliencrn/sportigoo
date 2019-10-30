<div class="container">

    <?php
    if ( function_exists( 'whl_get_related_posts' ) ) {
        $my_posts = whl_get_related_posts( get_the_ID() );

        if ( $my_posts->have_posts() ) { ?>

            <div class="mobile_carousel">

                <?php
                while ($my_posts->have_posts()) {
                    $my_posts->the_post();
                    get_template_part( 'template-parts/loop-related', 'post' );
                } ?>
            </div>
            <?php
        }
        wp_reset_postdata();
    }
    ?>


    <div class="row">
        <p class="t-uppercase t-center">
            <?php
            $more = "Voir plus d'actualiés";
            $link = get_permalink( 41 ); // Page des actualité
            if ( have_rows( 'blog_labels', 'option' ) ) {
                while (have_rows( 'blog_labels', 'option' )) {
                    the_row();
                    $more = get_sub_field( 'voir_plus' );
                }
            } ?>
            <a class="button button--jumbotron" href="<?= $link ?>">
                <?= $more ?>
            </a>
        </p>
    </div>
</div>
