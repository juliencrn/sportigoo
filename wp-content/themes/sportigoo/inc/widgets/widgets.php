<?php

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
add_action( 'widgets_init', function () {
    register_sidebar( array(
        'name'          => esc_html__( 'Bog Sidebar', 'sportigoo' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Ajouter ici les widgets du blog.', 'sportigoo' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget__title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer newsletter', 'sportigoo' ),
        'id'            => 'footer-newsletter',
        'description'   => esc_html__( '', 'sportigoo' ),
        'before_widget' => '<div class="footer__col">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Article newsletter', 'sportigoo' ),
        'id'            => 'post-newsletter',
        'description'   => esc_html__( '', 'sportigoo' ),
        'before_widget' => '<div class="newsletter-big">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ) );
} );

/**
 * Déclarer le widget à WordPress
 *
 * @link https://developer.wordpress.org/reference/hooks/widgets_init/
 */
add_action( 'widgets_init', function () {

    // Utiliser le nom de la classe PHP ici
    register_widget( 'sportigoo_search_Widget' );
    register_widget( 'sportigoo_lastposts_Widget' );
    register_widget( 'sportigoo_social_Widget' );
    register_widget( 'sportigoo_twitter_Widget' );
    register_widget( 'sportigoo_social_count_Widget' );
} );