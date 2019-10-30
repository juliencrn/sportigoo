<?php

if ( defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV ) {

    /**
     * Désactiver une liste de plugins en local
     *
     * @link https://codex.wordpress.org/Function_Reference/deactivate_plugins
     * @link https://developer.wordpress.org/reference/functions/activate_plugins/
     */
    add_action( 'admin_init', function () {

        // Liste des plugins à désactiver en local
        $plugins = array(
            'gitium/gitium.php',
            'google-analytics-dashboard-for-wp/gadwp.php',
            'really-simple-ssl/rlrsssl-really-simple-ssl.php',
            'wp-fastest-cache/wpFastestCache.php',
            'wp-rocket/wp-rocket.php',
            'accelerated-mobile-pages/accelerated-mobile-pages.php',
            'updraftplus/updraftplus.php',
            'wp-bitly/wp-bitly.php'
        );

        deactivate_plugins( $plugins );
    } );

}
