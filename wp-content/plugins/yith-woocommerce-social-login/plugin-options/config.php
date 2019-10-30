<?php
/**
 * Config file for HybridAuth Class
 *
 * @author  YITH
 * @package YITH WooCommerce Social Login
 * @version 1.0.0
 */

$slash = defined( 'YWSL_FINAL_SLASH' ) && YWSL_FINAL_SLASH ? '/' : '';

return array(
	'base_url'   => ( get_option( 'ywsl_callback_url' ) == 'root' ) ? site_url() . $slash : YITH_YWSL_URL . 'includes/hybridauth/',
	'providers'  => array(
		// openid providers
		"OpenID" => array(
			"enabled" => true
		),

		'Google' => array(
			'enabled' => ( get_option( 'ywsl_google_enable' ) == 'yes' ) ? true : false,
			'keys'    => array(
				'id'     => get_option( 'ywsl_google_id' ),
				'secret' => get_option( 'ywsl_google_secret' )
			)
		),

		'Facebook' => array(
			'enabled'        => ( get_option( 'ywsl_facebook_enable' ) == 'yes' ) ? true : false,
			'keys'           => array(
				'id'     => get_option( 'ywsl_facebook_id' ),
				'secret' => get_option( 'ywsl_facebook_secret' )
			),
			'trustForwarded' => false
		),

		'Twitter' => array(
			'enabled'      => ( get_option( 'ywsl_twitter_enable' ) == 'yes' ) ? true : false,
			'keys'         => array(
				'key'    => get_option( 'ywsl_twitter_key' ),
				'secret' => get_option( 'ywsl_twitter_secret' )
			),
			'includeEmail' => true,
		),


		//        'AOL' => array(
		//            'enabled' => ( get_option( 'ywsl_aol_enable' ) == 'yes' ) ? true : false,
		//        )
	),
	'debug_mode' => ( get_option( 'ywsl_enable_log' ) == 'yes' ) ? true : false,
	'debug_file' => YITH_YWSL_DIR . 'logs/log.txt',
);