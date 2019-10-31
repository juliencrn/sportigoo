<?php

/**
 * Ajouter des styles et scripts à la page de connexion/inscription
 *
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/login_enqueue_scripts
 */
add_action( 'login_enqueue_scripts', function () {
    // CSS
//    wp_enqueue_style( 'login-style', get_stylesheet_directory_uri() . '/js/login-style.css' );

    // JS
    wp_enqueue_script( 'login-script', get_stylesheet_directory_uri() . '/static/js/login-script.js', array('jquery'), true );
} );

add_action( 'login_head', function () {

    // Custom Styles  ?>
    <style type="text/css">
        #login_error {
            display: none;
        }

        .login label {
            font-size: 12px;
            color: #555555;
        }

        .login input[type="text"]{
            background-color: #ffffff;
            border-color:#dddddd;
            -webkit-border-radius: 4px;
        }

        .login input[type="password"]{
            background-color: #ffffff;
            border-color:#dddddd;
            -webkit-border-radius: 4px;
        }
    </style>
    <?php
} );

/**
 * Changer le lien du logo
 */
add_filter( 'login_headerurl', function () {
    return esc_url( home_url() );
} );

/**
 * Changer le titre du logo
 */
add_filter( 'login_headertext', function () {
    return get_bloginfo( 'name' ) . ' - ' . get_bloginfo( 'description' );
} );

/**
 * Désactiver l'affichage des erreurs de connexion
 */
add_action( 'login_errors', function () {
    return false;
} );

/**
 * Changer le logo sur la page de connexion/inscription
 */
add_action( 'login_head', function () {

    // On récupère le Logo
    $custom_logo_id = get_theme_mod( 'custom_logo' );

    if ( $custom_logo_id ) {
        // On récupère l'URL du logo
        $image = wp_get_attachment_image_src( $custom_logo_id, 'full' );

        // On écrase le logo de WordPress par le notre
        echo '<style type="text/css">
        h1 a { background-image:url(' . $image[0] . ') !important; }
    </style>';
    }

} );

/*
body.login {}
body.login div#login {}
body.login div#login h1 {}
body.login div#login h1 a {}
body.login div#login form#loginform {}
body.login div#login form#loginform p {}
body.login div#login form#loginform p label {}
body.login div#login form#loginform input {}
body.login div#login form#loginform input#user_login {}
body.login div#login form#loginform input#user_pass {}
body.login div#login form#loginform p.forgetmenot {}
body.login div#login form#loginform p.forgetmenot input#rememberme {}
body.login div#login form#loginform p.submit {}
body.login div#login form#loginform p.submit input#wp-submit {}
body.login div#login p#nav {}
body.login div#login p#nav a {}
body.login div#login p#backtoblog {}
body.login div#login p#backtoblog a {}
 */
