<?php
add_action( 'wp_ajax_zz_get_products', 'zz_get_products' );
add_action( 'wp_ajax_nopriv_zz_get_products', 'zz_get_products' );

function zz_get_products() {

  // Get param
  echo $offset = $_POST['offset'];

  // Basic query arguments default
  $args = [
    'posts_per_page' => 12,
    'offset' => 0,
    'post_type' => 'product',
    'order' => 'DECS',
    'tax_query' => [
      'relation' => 'AND',
    ]
  ];

  // Controller
  if ( isset( $offset ) ) $args['offset'] = $offset; // Pagination


  $query = new WP_Query( $args );

  echo "<ul style='border-bottom: 3px solid grey;'>";

  if ( $query->have_posts() ) {
//    echo '<input type="hidden" id="post_count" value="' . $post_count . '">';
    while ($query->have_posts()) {
      $query->the_post();
      ?>
        <li class="js-product-item">
          <?php echo the_title() . ' ( ' . get_the_ID() . ' ) '; ?>
        </li>
      <?php
    }
    wp_reset_postdata();
  } else {
    echo '<p style="margin: auto">Il n\'y a pas de résultats pour ces critères</p>';
  }

  echo "</ul>";

  die();

}
