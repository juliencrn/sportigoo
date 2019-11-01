<?php
$has_preview = true;
?>

<section class="activities activities--homepage activities-display">

  <?php if ( have_rows('sections_netflix') ) { ?>
    <?php while ( have_rows('sections_netflix') ) {
      the_row(); ?>

      <div class="activities__block">
        <div class="">
          <h2 class="homepage__titles h1 t-orange">
            <?php the_sub_field('titre') ?>
          </h2>
        </div>
        <?php
        $post_ids = get_sub_field('produits');
        if ( !empty($post_ids) ) { ?>
          <div class="activities__row">
            <div class="activities__slider activitiesSlider <?php echo $has_preview ? 'hasPrev':'' ?>">
              <?php foreach ($post_ids as $post_id) {
                $product = wc_get_product($post_id);
                if ( $product->is_visible() ) {
                  $thumbnail_url = get_the_post_thumbnail_url( $post_id, 'med-400' ); ?>

                  <article
                    data-id="<?php echo $post_id; ?>"
                    <?php wc_product_class('activities__item-wrapper'); ?>
                  >
                    <div class="wrapper">
                      <a class="activities__link" href="<?php echo get_permalink($post_id); ?>">
                        <div class="activities__item">
                          <div class="activities__img" style="background-image: url(<?php echo $thumbnail_url ?>);"></div>
                          <h4 class="activities__title">
                            <?php echo get_the_title($post_id); ?>
                          </h4>
                          <div class="activities__filter"></div>
                        </div>
                      </a>
                      <svg class="activities__preview-button" width="20" height="20">
                        <use xlink:href="#next"></use>
                      </svg>
                    </div>
                  </article>
                <?php } ?>

              <?php } ?>
            </div>
          </div>

          <?php // Preview template
          if ($has_preview) {
            get_template_part( 'pages/homepage/activities', 'preview' );
          }
        } ?>
      </div>

    <?php } ?>
  <?php } ?>

  <div class="activities__cta">
    <a href="#" class="button2">
      Voir tout
    </a>
  </div>


</section>
