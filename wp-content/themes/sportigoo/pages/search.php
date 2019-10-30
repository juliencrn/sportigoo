<?php
/**
 * Template name: Search
 */

// Read the url
$req = $_SERVER['REQUEST_URI'];
$params = parse_url( $_SERVER['REQUEST_URI'] );

// Redirect to archive if no has params
if (!isset($params['query'])) {
    wp_redirect( get_permalink( 39 ), 301 );
    exit;
}


