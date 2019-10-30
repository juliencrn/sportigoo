<?php

/**
 * Supprimer les widgets du tableau de Bord
 *
 * @link https://codex.wordpress.org/Function_Reference/remove_meta_box
 */
add_action( 'admin_init', function () {

    // Supprimer le widget "Bienvenue sur WordPress" central
//    remove_action('welcome_panel', 'wp_welcome_panel');

    // Supprimer le widget "D'un coup d'oeil"
//    remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );

    // Supprimer le widget 'Activité' (et commentaires quand il y en a)
    remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );

    // Supprimer le widget 'Événements et nouveatés WordPress'
//    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );

    // Supprimer le widget  'Brouillon rapide'
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );



    // Plugins
    // Yoast
    remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'normal');

    // Yith
    remove_meta_box('yith_dashboard_products_news', 'dashboard', 'normal');
    remove_meta_box('yith_dashboard_blog_news', 'dashboard', 'normal');

    // Google analytics
//    remove_meta_box('gadwp-widget', 'dashboard', 'normal');

});



/**
 * Remove access to the dashboard
 */
//add_action( 'admin_init', function () {
//
//    // Get current page
//    global $pagenow;
//
//    // Where to redirect
//    $redirect = get_admin_url( null, 'edit.php' );
//    if ( $pagenow == 'index.php' ) {
//        wp_redirect( $redirect, 301 );
//        exit;
//    }
//});
