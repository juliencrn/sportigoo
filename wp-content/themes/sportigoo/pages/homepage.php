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

      get_template_part('pages/homepage/categories');

      get_template_part('pages/homepage/blue-section');

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

    </div>

<?php } // End of the loop.

get_footer();
