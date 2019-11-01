<?php /* Template Name: Homepage */
require_once dirname(__DIR__) . "/inc/vendors/Mobile_Detect.php";
$detect = new Mobile_Detect;

get_header();

while ( have_posts() ) {
    the_post(); ?>

    <div class="content homepage">


      <?php
      // Send server media queries utility
      set_query_var('detect', $detect);

      get_template_part('pages/homepage/header');

      get_template_part('pages/homepage/search'); // Buttons

      if ( !empty( get_field('categorie') ) ) { ?>

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

      get_template_part('pages/homepage/blue-section');

      get_template_part('pages/homepage/activities'); // Netflix style

       get_template_part('pages/homepage/chiffres'); // key number

      ?>
      <!-- anchor ID -->
      <section id="homepage__event_types" class="homepage__event_types"></section>
      <?php
      if ( !empty( get_field('types_devenement') ) ) { ?>

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

      // get_template_part('pages/homepage/xxxx'); // taxonmoy of event types

      // get_template_part('pages/homepage/xxxx'); // googles rates

      // get_template_part('pages/homepage/xxxx'); // Blog

      // get_template_part('pages/homepage/xxxx'); // Feverup CTA

      if ( !$detect->isTablet() && !$detect->isMobile()  ) {}
      ?>

    </div>

<?php } // End of the loop.

get_footer();
