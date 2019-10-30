<?php

/**
 * Hook: woocommerce_before_shop_loop.
 *
 * @hooked woocommerce_output_all_notices - 10
 * @hooked woocommerce_result_count - 20
 * @hooked woocommerce_catalog_ordering - 30
 */

// Remove result count its original position
remove_action(
    'woocommerce_before_shop_loop',
    'woocommerce_result_count',
    20
);

// Remove ordering count its original position
remove_action(
    'woocommerce_before_shop_loop',
    'woocommerce_catalog_ordering',
    30
);


/**
 * Applying geoloc filter to archive page woocommerce loop
 *
 * @link https://www.kathyisawesome.com/woocommerce-modifying-product-query/
 */
//add_action( 'woocommerce_product_query', function ( $q ){
//
//    if (get_option( 'region_term_id' ) && is_archive()) {
//        $q->set( 'tax_query', array(
//            array('relation' => 'AND'),
//            array(
//                'taxonomy' => 'lieu',
//                'terms' => get_option( 'region_term_id' )
//            )
//        ) );
//    }
//
//} );


