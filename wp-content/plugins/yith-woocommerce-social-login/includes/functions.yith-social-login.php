<?php

function ywsl_curPageURL() {
    $pageURL = 'http';
    if ( isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

if ( ! function_exists( 'ywsl_check_wpengine' ) ) {
	/**
	 * Check if the website is stored on wp engine
	 * @return bool
	 * @since 1.3.0
	 * @author Emanuela Castorina <emanuela.castorina@yithemes.com>
	 */
	function ywsl_check_wpengine() {
		$is_wp_engine = defined( 'WPE_APIKEY' );

		if ( $is_wp_engine && ! defined( 'YWSL_FINAL_SLASH' ) ) {
			define( 'YWSL_FINAL_SLASH', true );
		}

		return $is_wp_engine;
	}
}