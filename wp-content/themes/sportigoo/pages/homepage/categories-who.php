<?php if ( !empty( get_field('types_devenement') ) ) { ?>

  <!-- anchor ID -->
  <section id="homepage__event_types" class="homepage__categories">
    <div class="container homepage__categories__container">
      <h2 class="homepage__titles h1">
        <?php the_field('titre_types_devenement'); ?>
      </h2>
      <div class="wrapper">
        <?php sportigoo_section_product_tax( get_field('types_devenement') ); ?>
      </div>
  </section>

<?php }
