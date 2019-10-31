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

      // get_template_part('pages/homepage/xxxx'); // taxonmoy of activités types

      ?>
      <!-- anchor ID -->
      <?php
      $what_terms = get_terms( array(
        'hide_empty' => false,
        'posts_per_page' => 6,
        'taxonomy' => 'product_cat',
        'child_of' => 58 // TODO : ACF
      ) );
      var_dump($what_terms);
      ?>
      <section id="homepage__categories" class="homepage__categories">

        <?php
        // TODO : Creer une function-tag ($tax_parent_id) -> <ul>[<tag/>]</ul>
        ?>

      </section>

      <section id="homepage__blue-section" class="homepage__blue-section">
        <div class="homepage__blue-section__waves homepage__blue-section__waves--top"></div>
        <div class="homepage__blue-section__bg">
          <div class="container homepage__blue-section__container">
            <div class="wrapper">
              <p>
                Tincidunt id aliquet risus feugiat in ante metus dictum at. Id faucibus nisl tincidunt eget nullam. Eu lobortis elementum nibh tellus molestie nunc. Consequat interdum varius sit amet mattis. Scelerisque in dictum non consectetur a. Vitae aliquet nec ullamcorper sit amet risus.
              </p>
              <p>
                Accumsan sit amet nulla facilisi morbi. Tempor commodo ullamcorper a lacus vestibulum sed arcu. Non nisi est sit amet facilisis. Id diam maecenas ultricies mi eget mauris pharetra. Duis at consectetur lorem donec massa sapien faucibus et molestie. Arcu felis bibendum ut tristique et. Sagittis eu volutpat odio facilisis mauris. Scelerisque eleifend donec pretium vulputate sapien nec sagittis aliquam. Adipiscing elit duis tristique sollicitudin nibh sit amet commodo nulla. Varius morbi enim nunc faucibus a pellentesque.
              </p>
              <p>
                Dui sapien eget mi proin sed libero enim sed.
              </p>
            </div>
          </div>
        </div>
        <div class="homepage__blue-section__waves homepage__blue-section__waves--bottom"></div>
      </section>
      <?php



      get_template_part('pages/homepage/activities'); // Netflix style

      // get_template_part('pages/homepage/xxxx'); // key number

      ?>
      <!-- anchor ID -->
      <section id="homepage__event_types" class="homepage__event_types"></section>
      <?php

      // get_template_part('pages/homepage/xxxx'); // taxonmoy of event types

      // get_template_part('pages/homepage/xxxx'); // googles rates

      // get_template_part('pages/homepage/xxxx'); // Blog

      // get_template_part('pages/homepage/xxxx'); // Feverup CTA

      if ( !$detect->isTablet() && !$detect->isMobile()  ) {}
      ?>

        <?php
        get_template_part('pages/homepage/categories-slider');

        get_template_part('pages/homepage/activities');

        if ( !$detect->isTablet() && !$detect->isMobile()  ) {
            get_template_part('pages/homepage/news');

            get_template_part('pages/homepage/partners');
        }

        ?>

    </div>

<?php } // End of the loop.

get_footer();
