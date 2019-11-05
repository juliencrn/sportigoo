<?php

// Posts query
$my_posts = new WP_Query( array(
  'post_type' => 'post',
  'post_status' => 'publish',
  'posts_per_page' => 3,
  'orderby' => array('date' => 'DESC')
) );

if ( have_rows( 'news' ) && $my_posts->have_posts() ) { ?>
  <?php while ( have_rows( 'news' ) ) { the_row(); ?>
    <section class="homepage__news">
      <div class="container homepage__news__container">

        <h2 class="h2 section-title homepage__news__section--title">
          <?php the_sub_field('titre_section'); ?>
        </h2>

        <div class="homepage__news__section__posts">
          <?php while ($my_posts->have_posts()) {
            $my_posts->the_post();
            $link = get_permalink( get_the_ID() );
            $post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
            $thumbnail_url = wp_get_attachment_image_src($post_thumbnail_id, 'med-400');
//            $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'med-400' ) ?: '';
            ?>

            <div class="homepage__news__item--wrapper">
              <div class="homepage__news__item--orange-bg"></div>
              <a href="<?php the_permalink(); ?>" class="homepage__news__item">

                <div
                  class="homepage__news__item__image"
                  style="background-image: url('<?php echo $thumbnail_url; ?>')"
                ></div>

                <div class="homepage__news__item__content">

                  <h3 class="h4 homepage__news__item__title">
                    <?php the_title(); ?>
                  </h3>

                  <div class="homepage__news__item__categorie--wrapper">
                    <?php
                    $categories = get_the_category( get_the_ID() );
                    if ( !empty( $categories ) ) { ?>
                      <span class="homepage__news__item__categorie">
                        # <?php echo $categories[0]->name ?>
                      </span>
                    <?php } ?>
                  </div>

                </div>
              </a>
            </div>

          <?php } wp_reset_postdata(); ?>
        </div>

        <div class="homepage__news__section--button--wrapper">
          <a class="button2 homepage__news__section--button" href="<?php echo get_permalink( 41 ); ?>">
            <?php the_sub_field('bouton'); ?>
          </a>
        </div>

      </div>
    </section>
  <?php } ?>
<?php } ?>
