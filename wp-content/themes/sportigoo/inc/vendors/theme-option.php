<?php

if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
        'page_title' 	=> 'Options du thème',
        'menu_title'	=> 'Options du thème',
        'menu_slug' 	=> 'theme-general-settings',
        'capability'	=> 'edit_posts',
        'redirect'		=> true
    ));

//    acf_add_options_sub_page(array(
//        'page_title' 	=> 'Réglages du Header',
//        'menu_title'	=> 'Header',
//        'parent_slug'	=> 'theme-general-settings',
//    ));

    acf_add_options_sub_page(array(
        'page_title' 	=> 'Réglages du Footer',
        'menu_title'	=> 'Footer',
        'parent_slug'	=> 'theme-general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title' 	=> '404',
        'menu_title'	=> '404',
        'parent_slug'	=> 'theme-general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title' 	=> 'Réglages sociaux',
        'menu_title'	=> 'Social',
        'parent_slug'	=> 'theme-general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title' 	=> 'Réglages e-commerce',
        'menu_title'	=> 'E-commerce',
        'parent_slug'	=> 'theme-general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title' 	=> 'Cookies notice',
        'menu_title'	=> 'Cookies',
        'parent_slug'	=> 'theme-general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title' 	=> 'Réglages du blob',
        'menu_title'	=> 'Blog',
        'parent_slug'	=> 'theme-general-settings',
    ));

}

/**
 * Google Map API KEY using ACF PRO
 *
 * @link https://www.advancedcustomfields.com/resources/google-map/
 */
add_action('acf/init', function () {
    $key = get_field('google_map_api', 'option');
    if ( !empty($key)) {
        acf_update_setting('google_api_key', $key);
    }
});