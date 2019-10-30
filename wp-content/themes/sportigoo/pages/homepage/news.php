<?php

$titre_section = "";
$bouton = "";

if ( have_rows( 'news' ) ) {
    while (have_rows( 'news' )) {
        the_row();

        $titre_section = get_sub_field( 'titre_section' ) ?: $titre_section;
        $bouton = get_sub_field( 'bouton' ) ?: $bouton;
    }
}
?>

<section class="homepage-posts">
    <div class="container">
        <h2 class="section-title">
            <?= $titre_section ?>
        </h2>

        <?php
        // Posts query
        $my_posts = new WP_Query( array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 4,
            'orderby' => array('date' => 'DESC')
        ) );

        // 1er Post
        $post1 = $my_posts->posts[0];
        $id = $post1->ID;
        $excerpt = whl_get_the_excerpt( $id );
        $link = get_permalink( $id );
        ?>

        <div class="last-posts">
            <div class="last-posts__preview">
                <div class="categories">
                    <?php
                    $categories = get_the_category( $id );
                    if ( !empty( $categories ) ) {
                        $i = 0;
                        foreach ($categories as $cat) {
                            $i++;
                            if ( $i < 3 ) { ?>
                                <a class="categories__item" id="catoutup-<?= $i ?>" href="<?php echo get_category_link( $cat->cat_ID ); ?>">
                                    <?= $cat->name ?>
                                </a>
                            <?php } ?>

                        <?php }
                    } ?>
                </div>
                <div class="last-posts__excerpt-wrapper">
                    <p id="excerptPreview">
                        <?= $excerpt ?>
                    </p>
                    <a class="link" id="preview-link" href="<?= $link ?>">Lire la suite</a>
                </div>
            </div>

            <?php if ( $my_posts->have_posts() ) { ?>
                <div class="last-posts__nav">
                    <?php while ($my_posts->have_posts()) {
                        $my_posts->the_post();
                        $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'large' ); ?>


                        <div class="last-posts__nav-item-wrapper">
                            <div class="d-none catList">
                                <?php
                                $categories = get_the_category( get_the_ID() );
                                if ( !empty( $categories ) ) {
                                    $i = 0;
                                    foreach ($categories as $cat) {
                                        $i++;
                                        if ( $i < 3 ) { ?>
                                            <span class="cat-<?= $i ?>"
                                                  data-name="<?= $cat->name ?>"
                                                  data-url="<?= get_category_link( $cat->cat_ID ); ?>"
                                            ></span>
                                        <?php } ?>

                                    <?php }
                                } ?>
                            </div>
                            <span class="last-posts__nav-excerpt d-none"><?php the_excerpt() ?></span>
                            <a class="last-posts__nav-item" href="<?php the_permalink(); ?>">
                                <h4><?php the_title(); ?></h4>
                                <span class="link link__white">Découvrir</span>
                            </a>
                            <div class="last-posts__img"
                                 style="background-image:url('<?= $thumbnail_url ?>');">
                            </div>

                        </div>
                    <?php }
                    wp_reset_postdata(); ?>
                </div>
                <div class="last-posts__filter"></div>

            <?php } ?>


        </div>
        <a class="button" href="<?php echo get_permalink( 41 ); ?>">
            <?= $bouton ?>
        </a>
    </div>
</section>
