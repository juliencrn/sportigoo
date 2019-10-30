<?php
/**
 * Use Gutenberg only for posts
 *
 * @link https://digwp.com/2018/04/how-to-disable-gutenberg/
 */
add_filter( 'use_block_editor_for_post_type', function ($is_enabled, $post_type) {
    if ( $post_type === 'post' ) {
        return $is_enabled;
    }
    return false;
}, 10, 2 );


/**
 * Remove  editor
 */
add_action( 'init', function () {
    //remove_post_type_support( 'page', 'editor' );
} );


/**
 * Remove personnaliser from adminbar
 */
add_action('wp_before_admin_bar_render', function() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu( 'customize' );
}, 99);