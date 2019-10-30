<?php


// Register Custom Taxonomy
add_action( 'init', function () {

    $labels = array(
        'name' => _x( 'Départements', 'Taxonomy General Name', 'sportigoo' ),
        'singular_name' => _x( 'Département', 'Taxonomy Singular Name', 'sportigoo' ),
        'menu_name' => __( 'Départements', 'sportigoo' ),
        'all_items' => __( 'Tous les Départements', 'sportigoo' ),
        'parent_item' => __( 'Parent Item', 'sportigoo' ),
        'parent_item_colon' => __( 'Parent Item:', 'sportigoo' ),
        'new_item_name' => __( 'Nouveau Département', 'sportigoo' ),
        'add_new_item' => __( 'Ajouter un Département', 'sportigoo' ),
        'edit_item' => __( 'Edit Item', 'sportigoo' ),
        'update_item' => __( 'Update Item', 'sportigoo' ),
        'view_item' => __( 'View Item', 'sportigoo' ),
        'separate_items_with_commas' => __( 'Separate items with commas', 'sportigoo' ),
        'add_or_remove_items' => __( 'Add or remove items', 'sportigoo' ),
        'choose_from_most_used' => __( 'Choose from the most used', 'sportigoo' ),
        'popular_items' => __( 'Popular Items', 'sportigoo' ),
        'search_items' => __( 'Search Items', 'sportigoo' ),
        'not_found' => __( 'Not Found', 'sportigoo' ),
        'no_terms' => __( 'No items', 'sportigoo' ),
        'items_list' => __( 'Items list', 'sportigoo' ),
        'items_list_navigation' => __( 'Items list navigation', 'sportigoo' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => false,
    );
    register_taxonomy( 'lieu', array('product'), $args );

    $labels = array(
        'name' => _x( 'Régions', 'Taxonomy General Name', 'sportigoo' ),
        'singular_name' => _x( 'Région', 'Taxonomy Singular Name', 'sportigoo' ),
        'menu_name' => __( 'Régions', 'sportigoo' ),
        'all_items' => __( 'Tous les Régions', 'sportigoo' ),
        'parent_item' => __( 'Parent Item', 'sportigoo' ),
        'parent_item_colon' => __( 'Parent Item:', 'sportigoo' ),
        'new_item_name' => __( 'Nouveau Région', 'sportigoo' ),
        'add_new_item' => __( 'Ajouter un Région', 'sportigoo' ),
        'edit_item' => __( 'Edit Item', 'sportigoo' ),
        'update_item' => __( 'Update Item', 'sportigoo' ),
        'view_item' => __( 'View Item', 'sportigoo' ),
        'separate_items_with_commas' => __( 'Separate items with commas', 'sportigoo' ),
        'add_or_remove_items' => __( 'Add or remove items', 'sportigoo' ),
        'choose_from_most_used' => __( 'Choose from the most used', 'sportigoo' ),
        'popular_items' => __( 'Popular Items', 'sportigoo' ),
        'search_items' => __( 'Search Items', 'sportigoo' ),
        'not_found' => __( 'Not Found', 'sportigoo' ),
        'no_terms' => __( 'No items', 'sportigoo' ),
        'items_list' => __( 'Items list', 'sportigoo' ),
        'items_list_navigation' => __( 'Items list navigation', 'sportigoo' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'rewrite' => array('slug' => 'region'),
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => false,
    );
    register_taxonomy( 'region', array('product'), $args );

}, 0 );

add_filter( 'post_type_link', function ($post_link, $post) {
    if ( get_post_type( $post ) == 'product' ) {
        if ( false !== strpos( $post_link, '%region%' ) ) {
            $projectscategory_type_term = get_the_terms( $post->ID, 'region' );
            if ( !empty( $projectscategory_type_term ) ) {
                $post_link = str_replace(
                    '%region%',
                    array_pop( $projectscategory_type_term )->slug, $post_link
                );
            } else {
                $post_link = str_replace( '%region%', 'region', $post_link );
            }
        }
    }
    return $post_link;

}, 10, 2 );
