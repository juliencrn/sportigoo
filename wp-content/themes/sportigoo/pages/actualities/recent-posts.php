<?php

// Posts query
$my_posts = new WP_Query( array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 8,
    'orderby' => array('date' => 'DESC')
) );

if ( !$my_posts->have_posts() ) {
    return;
} ?>

<div class="row">
    <div class="twelve cols">
        <div class="recents_slider">

            <div class="recents_slider__topbar bg-blue"></div>
            <h2 class="t-center t-blue bg-white recents_slider__title">
                Articles récents
            </h2>

            <div class="recents_slider__wrapper">
                <div class="recents_slider__carousel">

                    <?php while ($my_posts->have_posts()) {
                        $my_posts->the_post();
                        $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'medium' ); ?>


                        <article class="blogcard">
                            <a class="blogcard__wrapper" href="<?php the_permalink(); ?>">
                                <div class="blogcard__img" style="background-image:url('<?= $thumbnail_url ?>')">

                                    <div class="blogcard__cat">
                                        <?php
                                        $categories = get_the_category( $id );
                                        if ( !empty( $categories ) ) { ?>
                                            <p class="categories__item">
                                                <?= $categories[0]->name ?>
                                            </p>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="blogcard__content">
                                    <div class="blogcard__date">
                                        <p><?php echo get_the_date(); ?></p>
                                    </div>
                                    <div class="blogcard__title">
                                        <h3><?php the_title(); ?></h3>
                                    </div>
                                    <div class="blogcard__excerpt">
                                        <p>
                                            <?php the_excerpt() ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </article>

                    <?php }
                    wp_reset_postdata(); ?>

                </div>
                <div class="recents_slider__carousel__prev">
                    <svg width="25" height="25">
                        <use xlink:href="#chevron-left"></use>
                    </svg>
                </div>
                <div class="recents_slider__carousel__next">
                    <svg width="25" height="25">
                        <use xlink:href="#chevron-right"></use>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>