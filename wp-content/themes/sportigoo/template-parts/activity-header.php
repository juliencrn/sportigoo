<?php
if ( !function_exists( 'zz_get_the_term_list' ) ) {
    return;
}

$bouton_reserver = 'Réserver';
if ( have_rows( 'labels_e-commerce', 'option' ) ) {
    while (have_rows( 'labels_e-commerce', 'option' )) {
        the_row();
        $bouton_reserver = get_sub_field( 'bouton_reserver' ) ?: $bouton_reserver;
    }
}

$product_id = get_query_var( 'product_id' );
if ( $product_id ) {
    $product = wc_get_product( $product_id );
    $product_link = 'href="' . get_permalink( $product_id ) . '"';
    $bouton_reserver = "En savoir plus";

} else {
    global $product;
    $product_id = $product->get_id();
    $product_link = 'href="#booking_now" rel="modal:open"';

}

$fields = get_fields( $product_id )['produit'];
$large_thumbnail_url = get_the_post_thumbnail_url( $product_id, 'full' );

?>

<section>
    <div class="activity-preview" style="background-image: url('<?= $large_thumbnail_url ?>');">
        <div class="container">
            <div class="activity-preview__container">
                <div class="activity-preview__left relative">

                    <div class="categories">
                        <?php echo zz_get_the_term_list( $product_id, 'product_cat', '', '', '' ); ?>
                    </div>

                    <h3 class="activity-preview__title orange">
                        <?php echo get_the_title( $product_id ); ?>
                    </h3>

                    <p class="activity-preview__excerpt">
                        <?php echo $product->get_short_description(); ?>
                    </p>

                    <a class="button button--white button--jumbotron" <?= $product_link ?>>
                        <?= $bouton_reserver ?>
                    </a>

                    <div class="stars orange">
                        <?php
                        $rating_count = $product->get_rating_count();
                        $average = $product->get_average_rating();
                        if ( $rating_count > 0 && comments_open() ) {
                            echo wc_get_rating_html( $average, $rating_count );
                        } ?>
                    </div>

                    <p class="activity-preview__price">
                        <?php echo empty( $fields['prix_public'] ) ?
                            $product->get_price_html() :
                            $fields['prix_public'];
                        ?>
                    </p>

                </div>
                <div class="activity-preview__right">
                    <div class="activity-preview__video-button">
                        <?php if ( !empty( $fields['video'] ) ) : ?>
                            <svg width="75" height="75">
                                <use xlink:href="#play"></use>
                            </svg>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if ( !empty( $fields['video'] ) ) {
            $iframe = $fields['video'];
            preg_match( '/src="(.+?)"/', $iframe, $matches );
            $iframe_src = $matches[1];
            ?>
            <div class="activity-preview__video">
                <div class="activity-preview__video-container" style="max-width: 100%">
                    <div class="activity-preview__close-video">
                        <svg width="25" height="25">
                            <use xlink:href="#cross"></use>
                        </svg>
                    </div>
                    <div class="video-container">
                        <iframe
                                width="1080"
                                height="610"
                                src="<?= $iframe_src ?>"
                                frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen="allowfullscreen"
                        ></iframe>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="activity-preview__filter"></div>
    </div>
</section>
