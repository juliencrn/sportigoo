<?php

/**
 * Theme dependencies
 * ==================
 */
add_action( 'admin_init', 'flush_rewrite_rules' );
$inc = get_stylesheet_directory() . "/inc/";
include_once(ABSPATH . 'wp-admin/includes/plugin.php');

// Force using ACF
if ( !class_exists( 'ACF' ) ) return;

require $inc . "base/setup.php";
require $inc . "base/enqueue.php";
require $inc . "base/template-tags.php";
require $inc . "base/bases.php";

require $inc . "dev/smtp.php";
require $inc . "dev/dev-plugin.php";

require $inc . "snippets/related-posts.php"; // Needs template-tags.php
require $inc . "snippets/profile.php";
require $inc . "snippets/block-admin-access.php";
require $inc . "snippets/clean-dashboard-widgets.php";
require $inc . "snippets/custom-login.php";
require $inc . "snippets/disable-emoji.php";
require $inc . "snippets/admin-columns.php";
require $inc . "snippets/custom-comments.php";
require $inc . "vendors/theme-option.php"; // using ACF
require $inc . "snippets/set-image-alt.php";

require $inc . "widgets/widget-search.php";
require $inc . "widgets/widget-last-posts.php";
require $inc . "widgets/widget-social.php";
require $inc . "widgets/widget-social-count.php";
require $inc . "widgets/widget-twitter.php";
require $inc . "widgets/widgets.php";


if ( is_plugin_active( 'mailjet-for-wordpress/wp-mailjet.php' ) ) {
    require $inc . "vendors/edit-mailjet.php"; // Mailjet
}

// Woocommerce Snippets
if ( class_exists( 'WooCommerce' ) ) {
    require $inc . "woocommerce/wc-single-product.php";
    require $inc . "woocommerce/wc-archive.php";
    require $inc . "woocommerce/woocommerce.php";
    require $inc . "woocommerce/ajax-activity-preview.php";
    require $inc . "woocommerce/search.php";
    require $inc . "woocommerce/taxonomy-lieu.php";

    // Block woocommerce updates
//    add_filter('site_transient_update_plugins', function( $value ) {
//      unset( $value->response['woocommerce/woocommerce.php']);
//      var_dump(plugin_basename(__FILE__));
//    });
}


