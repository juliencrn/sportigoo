<?php


/**
 * Remove product editor
 */
add_action( 'init', function () {
    remove_post_type_support( 'product', 'editor' );
} );

/**
 * woocommerce_single_product_summary hook
 *
 * @hooked woocommerce_template_single_title - 5
 * @hooked woocommerce_template_single_rating - 10
 * @hooked woocommerce_template_single_price - 10
 * @hooked woocommerce_template_single_excerpt - 20
 * @hooked woocommerce_template_single_add_to_cart - 30
 * @hooked woocommerce_template_single_meta - 40
 * @hooked woocommerce_template_single_sharing - 50
 */

// Remove product title from its original position
remove_action(
    'woocommerce_single_product_summary',
    'woocommerce_template_single_title',
    5
);

// Remove product rating from its original position
remove_action(
    'woocommerce_single_product_summary',
    'woocommerce_template_single_rating',
    10
);

// Remove product price from its original position
remove_action(
    'woocommerce_single_product_summary',
    'woocommerce_template_single_price',
    10
);

// Remove product excerpt from its original position
remove_action(
    'woocommerce_single_product_summary',
    'woocommerce_template_single_excerpt',
    20
);

// Remove product category/tag meta from its original position
remove_action(
    'woocommerce_single_product_summary',
    'woocommerce_template_single_meta',
    40
);

// Removesharing from its original position
remove_action(
    'woocommerce_single_product_summary',
    'woocommerce_template_single_sharing',
    50
);



/**
 * woocommerce_after_single_product_summary hook
 *
 * @hooked woocommerce_output_product_data_tabs - 10
 * @hooked woocommerce_upsell_display - 15
 * @hooked woocommerce_output_related_products - 20
 */

// Remove product 'Related Products'
remove_action(
    'woocommerce_after_single_product_summary',
    'woocommerce_output_related_products',
    20
);

/**
 * Remove existing tabs from single product pages.
 */

add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
    unset( $tabs['description'] );
//    unset( $tabs['reviews'] );
    unset( $tabs['additional_information'] );
    return $tabs;
}, 98 );