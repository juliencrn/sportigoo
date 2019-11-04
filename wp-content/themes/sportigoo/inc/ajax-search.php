<?php
add_action( 'wp_ajax_zz_get_products', 'zz_get_products' );
add_action( 'wp_ajax_nopriv_zz_get_products', 'zz_get_products' );

function zz_get_products() {

  // Get param
  $offset = $_POST['offset'] ?: 0;
  $product_where = $_POST['product_where'] ?: false;
  $product_cat = $_POST['product_cat'] ?: [];

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
  if ( isset($product_cat) && count($product_cat) > 0 ) {
    $args['tax_query'][] = array(
      'taxonomy' => 'product_cat',
      'field'    => 'slug',
      'terms'    => $product_cat,
      'operator' => 'AND'
    );
  }

  if ( $product_where ) {
    $args['tax_query'][] = array(
      'taxonomy' => 'lieu',
      'field'    => 'slug',
      'terms'    => array($product_where),
    );
  }

   /* ?><pre><code><?php var_dump($args); die(); ?></code></pre><?php */

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
