<?php
$detect = get_query_var('detect');
?>

<section class="search__header">
  <div class="cover cover--full">

    <?php if ( !$detect->isMobile()  ) {
      $bg = get_stylesheet_directory_uri() . '/static/img/bg-search.png';?>
      <img src="<?php echo $bg; ?>" alt="Sportigoo" class="cover__image"/>
    <?php }  ?>

    <div class="container">
      <div class="cover__titles">
        <h1 class="title">
          <?php the_title(); ?>
        </h1>

      </div>
    </div>

    <!--      <div class="search__header__waves"></div>-->
  </div>
</section>
