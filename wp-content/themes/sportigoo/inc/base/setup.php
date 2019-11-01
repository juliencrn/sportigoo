<?php

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
add_action( 'after_setup_theme', function () {

    /**
     * Support for woocommerce template extending
     */
    add_theme_support( 'woocommerce' );


    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     * If you're building a theme based on sportigoo, use a find and replace
     * to change 'sportigoo' to the name of your theme in all the template files.
     */
    load_theme_textdomain( 'sportigoo', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support( 'post-thumbnails' );

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus( array(
        'menu-1' => esc_html__( 'Primary', 'sportigoo' ),
        'footer-1' => esc_html__( 'Footer 1', 'sportigoo' ),
        'footer-2' => esc_html__( 'Footer 2', 'sportigoo' ),
        'footer-3' => esc_html__( 'Footer 3', 'sportigoo' ),
        'footer-copy' => esc_html__( 'Footer Copyright', 'sportigoo' ),
    ) );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ) );

    // Set up the WordPress core custom background feature.
    add_theme_support( 'custom-background', apply_filters( 'sportigoo_custom_background_args', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    ) ) );

    // Add theme support for selective refresh for widgets.
//    add_theme_support( 'customize-selective-refresh-widgets' );

    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support( 'custom-logo', array(
        'height' => 175,
        'width' => 175,
        'flex-width' => true,
        'flex-height' => true,
    ) );


    // Add new post thumbnail size
    add_image_size( 'medhome', 520, 9999 ); // and unlimited height
    add_image_size( 'med-400', 400, 9999 ); // and unlimited height

} );


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
add_action( 'after_setup_theme', function () {
    // This variable is intended to be overruled from themes.
    // Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
    $GLOBALS['content_width'] = apply_filters( 'sportigoo_content_width', 640 );
}, 0 );



/**
 * Change excerpt lenght
 */
add_filter( 'excerpt_length', function ( $length ) {
    return 20;
}, 999 );
