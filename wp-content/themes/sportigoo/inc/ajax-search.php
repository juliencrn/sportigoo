<?php
add_action( 'wp_ajax_zz_get_products', 'zz_get_products' );
add_action( 'wp_ajax_nopriv_zz_get_products', 'zz_get_products' );

function zz_get_products() {

  // Get param
  $offset = $_POST['offset'];

  // Basic query arguments default
  $args = [
    'posts_per_page' => 12,
    'offset' => 0,
    'post_type' => 'product',
    'post_status' => 'publish',
    'order' => 'DECS',
    'tax_query' => [
      'relation' => 'AND',
    ]
  ];

  // Controller
  if ( isset( $offset ) ) $args['offset'] = $offset; // Pagination

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
