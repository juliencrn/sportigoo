<?php

$terms = get_field('categorie', 2);
$pattern = get_stylesheet_directory_uri() . "/dist/img/pattern-activite.png";

if ($terms) { ?>
<section>

  <div class="activities__pattern" style="background-image: url('<?= $pattern ?>');"></div>
  <div class="home_categories_slider ">

    <?php foreach($terms as $term) {

          $imageUr = get_field('image', $term)['sizes']['medhome'];

          if ($imageUr != null) {
?>

  <a
    href="<?php echo get_term_link($term); ?>"
  >
<div
    style="background-image: url(<?php echo $imageUr;  ?>);"
></div>
</a>



<?php } // Endif

} ?>
</div>
    <div class="home_categories_slider--nav">

      <div class="carousel__prev">
          <svg width="25" height="25">
              <use xlink:href="#chevron-left"></use>
          </svg>
      </div>
      <div class="carousel__next">
          <svg width="25" height="25">
              <use xlink:href="#chevron-right"></use>
          </svg>
      </div>

    </div>
  </section>
<style>
.home_categories_slider {padding: 150px 0 0 20px;}
.home_categories_slider  a {
  overflow: hidden;
  border-radius: 50%;
  margin-right: 20px;
  position: relative;
}
.home_categories_slider  a:after {
  content: "";
  display: block;
  padding-bottom: 100%;
}
.home_categories_slider a div {
  width: 100%;
  height: 100%;
  background-position: center center;
  background-repeat: no-repeat;
  background-size: cover;
  position: absolute;
  top: 0; bottom: 0; left: 0; right: 0;
}
.home_categories_slider--nav .carousel__next {
  fill: #e7511e;
  right: 10px;
  top: calc(50% - 15px);
  margin-top: 75px;
}
.home_categories_slider--nav .carousel__prev {
  fill: #e7511e;
  left: 10px;
  top: calc(50% - 15px);
  margin-top: 75px;
}

</style>
<?php }

