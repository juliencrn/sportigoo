<?php
$shortcode = get_field('avis_google_shortcode');
if ( $shortcode ) { ?>

  <section id="homepage__reviews" class="homepage__reviews">
    <div class="homepage__reviews__waves homepage__reviews__waves--top"></div>
    <div class="homepage__reviews__bg">
      <div class="container homepage__reviews__container">
        <div class="wrapper">
          <?php echo do_shortcode($shortcode); ?>
        </div>
      </div>
    </div>
    <div class="homepage__reviews__waves homepage__reviews__waves--bottom"></div>
  </section>

<?php } ?>
