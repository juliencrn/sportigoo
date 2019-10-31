<?php /* Template Name: Homepage */
require_once dirname(__DIR__) . "/inc/vendors/Mobile_Detect.php";
$detect = new Mobile_Detect;

get_header();

while ( have_posts() ) {
    the_post(); ?>

    <div class="content homepage">

        <?php
        get_template_part('pages/homepage/header');

        get_template_part('pages/homepage/search');

        get_template_part('pages/homepage/qui-sommes-nous');

        get_template_part('pages/homepage/categories-slider');

        get_template_part('pages/homepage/activities');

        get_template_part('pages/homepage/qui-sommes-nous-repeat');

        if ( !$detect->isTablet() && !$detect->isMobile()  ) {
            get_template_part('pages/homepage/news');

            get_template_part('pages/homepage/partners');

        }

        ?>

    </div>

<?php } // End of the loop.

get_footer();
