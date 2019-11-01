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
                  set_query_var('post_id', $post_id);
                  set_query_var('has_preview', $has_preview);
                  get_template_part('template-parts/loop-product-netflix');
                } ?>

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
    <a href="<?php echo get_permalink(get_field('page_de_recherche', 'option')) ?>" class="button2">
      Voir tout
    </a>
  </div>


</section>
