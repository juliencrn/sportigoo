<?php
$detect = get_query_var('detect');
$page_title = get_bloginfo( 'name' );
$page_slogan = get_bloginfo( 'description' );
$bg = get_stylesheet_directory_uri() . '/static/img/sportigoo-cover.png';

if ( have_rows( 'header' ) ) {
    while (have_rows( 'header' )) {
        the_row(); ?>
        <section id="homepage__cover" class="homepage__cover">
          <div class="cover cover--full">
            <div class="container">
              <div class="cover__titles h1">
                <h1 class="title">
                  <?php the_sub_field('titre'); ?>
                </h1>
                <h2 class="sub-title h3">
                  <?php the_sub_field('sous_titre'); ?>
                </h2>
              </div>
            </div>
            <div class="go-down">
              <a href="#homepage__search">
                <svg width="25" height="25" style="transform: rotate(90deg)">
                  <use xlink:href="#next"></use>
                </svg>
              </a>
            </div>

            <?php if ( !$detect->isMobile()  ) { ?>
              <img src="<?php echo $bg; ?>" alt="Sportigoo" class="cover__image"/>
            <?php } ?>

          </div>
        </section>
<?php
    }
}
?>


