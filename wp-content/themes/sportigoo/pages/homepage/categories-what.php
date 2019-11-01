<?php if ( !empty( get_field('categorie') ) ) { ?>

  <section id="homepage__categories" class="homepage__categories">
    <div class="container homepage__categories__container">
      <h2 class="homepage__titles h1">
        <?php the_field('titre_categorie'); ?>
      </h2>
      <div class="wrapper">
        <?php sportigoo_section_product_tax( get_field('categorie') ); ?>
      </div>
  </section>

<?php }
