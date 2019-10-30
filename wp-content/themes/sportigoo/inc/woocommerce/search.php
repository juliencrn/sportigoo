<?php

/**
 * Modifier les résultats de recherches dans WordPress
 *
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts
 */
add_filter( 'pre_get_posts', function ($query) {

//    global $wp_query;

    if ( $query->is_search ) {

        $query->set( 'post_type', array('post', 'product') );

    }

    return $query;
} );