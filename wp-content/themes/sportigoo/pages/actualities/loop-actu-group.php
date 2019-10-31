<?php
$term_id = get_query_var('term_id');
if ( !$term_id ) {
    return;
}

$term = get_term( $term_id, 'category' );
if ( !$term ) {
    return;
}

// Term ACF Field
$image = get_field( 'image_a_la_une', $term );
$color = get_field( 'couleur', $term );
$link = get_category_link( $term_id );
?>

<div class="six cols">
    <div class="actu-group">

        <div class="actu-group__topbar" style="background-color:<?php echo $color ?>"></div>

        <h2 class="actu-group__title t-center">
            <a class="t-center h2 t-gris"
               href="<?php echo $link ?>">
                <?php echo $term->name ?>
            </a>
        </h2>

        <a class="d-block actu-group__img" href="<?php echo $link ?>" style="background-image: url('<?php echo $image ?>');"></a>

        <?php
        $last_posts = new WP_Query( array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 5,
            'orderby' => array('date' => 'DESC'),
            'category__in' => array($term_id)
        ) );

        if ( $last_posts->have_posts() ) {
            $i = 0; ?>

            <div class="actu-group__list">

                <?php  while ($last_posts->have_posts()) {
                    $last_posts->the_post();
                    $i++;

                    // Categories
                    $categories = get_the_category();
                    $the_cat = false;
                    if ( !empty( $categories ) ) {
                        $the_cat = $categories[0];
                    }

                    // Img
                    $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );


                    // Separator
                    if ( $i > 1 ) { ?>
                        <hr class="separator"/>
                    <?php } ?>

                    <article class="actu-item">
                        <a class="actu-item__wrapper" href="<?php the_permalink(); ?>">
                            <div class="actu-item__img">
                                <?php if ( $thumbnail_url ) { ?>
                                    <img src="<?php echo $thumbnail_url ?>" style="min-width: 70px;max-width: 70px"/>
                                <?php } ?>
                            </div>
                            <div class="actu-item__content">
                                <p>
                                    <?php the_title(); ?>
                                </p>
                                <small>
                                    <span><?php echo get_the_date(); ?></span>

                                    <?php if ( $the_cat ) { ?>
                                        <span> |</span>
                                        <span> <?php echo $the_cat->name ?></span>
                                    <?php } ?>

                                </small>
                            </div>
                        </a>
                    </article>

                <?php } wp_reset_postdata();  ?>

            </div>

        <?php } ?>

        <div class="actu-group__footer">
            <a href="<?php echo $link ?>">
                <svg width="30" height="30">
                    <use xlink:href="#chevron-down"></use>
                </svg>
            </a>
        </div>


    </div>
</div>
