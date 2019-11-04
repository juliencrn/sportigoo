<?php
if ( !function_exists( 'zz_get_the_term_list' ) ) {
    return;
}

$bouton_reserver = 'Réserver';
$bouton_info = 'Demande d\'information';
$info = '';
if ( have_rows( 'labels_e-commerce', 'option' ) ) {
    while (have_rows( 'labels_e-commerce', 'option' )) {
        the_row();
        $bouton_reserver = get_sub_field( 'bouton_reserver' ) ?: $bouton_reserver;
        $bouton_info = get_sub_field( 'bouton_demande_dinfo' ) ?: $bouton_info;
        $info = get_sub_field( 'informations' ) ?: $info;
    }
}

global $product;
$product_id = $product->get_id();
$fields = get_fields( $product_id )['produit'];
$large_thumbnail_url = get_the_post_thumbnail_url( $product_id, 'full' );

if ( have_rows( 'produit' ) ) {
  while (have_rows( 'produit' )) {
    the_row(); ?>

    <section>
      <div class="activity-preview" style="background-image: url('<?php echo $large_thumbnail_url ?>');">
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

              <div class="activity-preview__buttons">
                <a href="#booking_now" class="button button--white button--jumbotron" rel="modal:open">
                  <?php echo $bouton_reserver ?>
                </a>

                <a href="#demande_info" class="button button--white button--jumbotron" rel="modal:open">
                  <?php echo $bouton_info ?>
                </a>
              </div>

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
                  <a href="#video" rel="modal:open">
                    <svg width="75" height="75">
                      <use xlink:href="#play"></use>
                    </svg>
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <div class="activity-preview__filter"></div>


        <!-- Modal info -->
        <div id="demande_info" class="jq-modal activity-preview__modal--info">
          <div class="wrapper">
            <?php echo $info ?>
          </div>
        </div>

        <!-- Modal video -->
        <?php if ( !empty( $fields['video'] ) ) { ?>
            <div id="video" class="jq-modal activity-preview__modal--video">
              <div class="video-container">
                <?php the_sub_field('video'); ?>
              </div>
            </div>
        <?php } ?>

      </div>
    </section>

  <?php }
}
?>
