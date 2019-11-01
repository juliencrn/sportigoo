<section class="homepage__chiffres">
    <?php if ( have_rows('chiffres') ) { ?>
      <div class="homepage__chiffres__waves"></div>

      <div class="homepage__chiffres__bg">

            <div class="homepage__chiffres__container">
                <div class="wrapper">
                    <?php while ( have_rows('chiffres') ) {
                      the_row(); ?>

                      <div class="homepage__chiffres__item">
                        <p class="homepage__chiffres__item--chiffre">
                          <?php the_sub_field('chiffre'); ?>
                        </p>
                        <p class="homepage__chiffres__item--label">
                          <?php the_sub_field('label'); ?>
                        </p>
                        <?php $icon = get_sub_field('icon');
                        if ( $icon ) { ?>
                          <div class="homepage__chiffres__item--image">
                            <img
                              src="<?php echo $icon['url']; ?>"
                              alt="<?php echo $icon['alt']; ?>"
                            />
                          </div>
                        <?php } ?>
                      </div>

                    <?php } ?>
              </div>
          </div>
      </div>
    <?php } ?>
</section>
