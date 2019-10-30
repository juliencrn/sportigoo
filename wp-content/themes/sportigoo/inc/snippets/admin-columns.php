<?php

/**
 * Supprimer des colonnes dans l'admin
 *
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_edit-post_type_columns
 */


// Catégories de produit
add_filter( 'manage_edit-product_cat_columns', function ($columns) {

    unset(
        $columns['thumb'],
        $columns['description'],
        $columns['slug']
    );
    return $columns;
}, 12 );

// Post (blog)
add_filter( 'manage_edit-post_columns', function ($columns) {
    unset( $columns['author'] );
    return $columns;
}, 12 );

// Produit Woocommerce
add_filter( 'manage_edit-product_columns', function ($columns) {
    unset(
        $columns['thumb'],
        $columns['sku'],
        $columns['featured'],
        $columns['is_in_stock']
    );
    return $columns;
}, 12 );




