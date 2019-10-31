<?php

/**
 * Register styles & scripts nicely
 *
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
 */
add_action( 'wp_enqueue_scripts', function () {

    $min = (defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV == true) ? "" : ".min";

    // Path
    $dist = get_stylesheet_directory_uri() . '/dist/';
    $root = get_stylesheet_directory_uri() . '/';

    // lib
    if ( is_page( 41 ) || is_singular( 'post' ) || is_post_type_archive( 'post' ) ) {
        wp_enqueue_script( 'twitter', "https://platform.twitter.com/widgets.js", null, null, false );
    }
    wp_enqueue_style( 'cookie-consent2-css', 'https://cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css' );
    wp_enqueue_script( 'gsap-js', "https://cdnjs.cloudflare.com/ajax/libs/gsap/1.14.2/TweenMax.min.js", null, null, true );
    wp_enqueue_script( 'slick-js', "https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js", null, null, true );
    wp_enqueue_script( 'cookiec-onsent2-js', "https://cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js", null, null, true );
    wp_enqueue_script( 'jquery-modal-js', "https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js", array('jquery'), null, true );

    // Dependencies
    $deps = array('jquery', 'gsap-js', 'slick-js', 'cookiec-onsent2-js', 'jquery-modal-js');

    // Current theme
    wp_enqueue_script( 'main-theme-js', $dist . 'js/unscuzzy-starter' . $min . '.js', $deps, false, true );

    // Comments
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
    if ( is_singular('post') && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'ajax-comments-js', $root . 'static/js/ajax-comments.js', array('jquery') );
    }

    // Ajax
    wp_localize_script('main-theme-js', 'ajaxurl', admin_url( 'admin-ajax.php' ) );

} );

/**
 * Clean footer
 */
add_action( 'wp_footer', function () {
    // Disables embed
    wp_dequeue_script( 'wp-embed' );
} );

add_action( 'wp_enqueue_scripts', function () {
    // jQuery
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'https://code.jquery.com/jquery-3.3.1.min.js', null, null, true );
    wp_enqueue_script( 'jquery' );
}, 12 );

// Load style fists
add_action('wp_enqueue_scripts', function () {
    $min = (defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV == true) ? "" : ".min";
    $dist = get_stylesheet_directory_uri() . '/dist/';
    wp_enqueue_style( 'main-style', $dist . 'css/unscuzzy-starter' . $min . '.css' );
}, 10);
