<?php
$post_id = get_query_var('post_id');
$has_preview = get_query_var('has_preview');
$thumbnail_url = get_the_post_thumbnail_url( $post_id, 'med-400' );
?>


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
    <?php if ( $has_preview ) { ?>
      <svg class="activities__preview-button" width="20" height="20">
        <use xlink:href="#next"></use>
      </svg>
    <?php } ?>
  </div>
</article>
