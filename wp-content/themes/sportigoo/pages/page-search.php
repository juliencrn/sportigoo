<?php
/** Template name: Page de recherche */

require_once dirname(__DIR__) . "/inc/vendors/Mobile_Detect.php";
$detect = new Mobile_Detect;

get_header();

// Read URL params
//$product_cat = $_GET['categorie'];
//if ( !empty($product_cat) ) {
//  // var_dump($product_cat);
//}

echo '<div class="search">';

  set_query_var('detect', $detect);

  get_template_part('pages/search/header');
  get_template_part('pages/search/search');
  get_template_part('pages/search/output');

echo '</div>';


get_footer();
