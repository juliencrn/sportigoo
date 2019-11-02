<?php
add_action( 'wp_ajax_zz_get_products', 'zz_get_products' );
add_action( 'wp_ajax_nopriv_zz_get_products', 'zz_get_products' );

function zz_get_products() {

  // Get param
  $offset = $_POST['offset'] ?: 0;
  $product_who = $_POST['product_who'];
  $product_where = $_POST['product_where'];
  $product_what = $_POST['product_what'];

  // Basic query arguments default
  $args = [
    'posts_per_page' => 12,
    'offset' => $offset,
    'post_type' => 'product',
    'post_status' => 'publish',
    'order' => 'DECS',
    'tax_query' => [
      'relation' => 'AND'
    ]
  ];

  // Controller
  $cat_arr = array();
  $lieu_arr = array();
  if ( isset($product_who) && $product_who != 0) {
    array_push($cat_arr, $product_who);
  }
  if ( isset($product_what) && $product_what != 0) {
    array_push($cat_arr, $product_what);
  }
  if ( isset($product_where) && $product_where != 0) {
    array_push($lieu_arr, $product_where);
  }

  if ( count($cat_arr) ) {
    $args['tax_query'][] = array(
      'taxonomy' => 'product_cat',
      'field'    => 'term_id',
      'terms'    => $cat_arr,
      'operator' => 'AND'
    );
  }
  if ( count($lieu_arr) ) {
    $args['tax_query'][] = array(
      'taxonomy' => 'lieu',
      'field'    => 'term_id',
      'terms'    => $lieu_arr,
    );
  }

  /* ?>
  <pre>
    <code>
      <?php
      var_dump($args);
      ?>
    </code>
  </pre>
  <?php */

//  die();

  // Instance
  $query = new WP_Query( $args );

  // Loop
  if ( $query->have_posts() ) {
    while ($query->have_posts()) {
      $query->the_post();
      set_query_var('post_id', get_the_ID());
      get_template_part('template-parts/loop-product-search');
    }
    wp_reset_postdata();
  }

  die(); // important

}
