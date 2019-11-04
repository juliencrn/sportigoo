<?php

/**
 * Remove checkout field & add placeholder from label
 *
 * Billing
 * billing_first_name
 * billing_last_name
 * billing_company
 * billing_address_1
 * billing_address_2
 * billing_city
 * billing_postcode
 * billing_country
 * billing_state
 * billing_email
 * billing_phone
 *
 * Shipping
 * shipping_first_name
 * shipping_last_name
 * shipping_company
 * shipping_address_1
 * shipping_address_2
 * shipping_city
 * shipping_postcode
 * shipping_country
 * shipping_state
 *
 * Account
 * account_username
 * account_password
 * account_password-2
 *
 * Order
 * order_comments
 */
add_filter( 'woocommerce_checkout_fields', function ($fields) {
    unset( $fields['billing']['billing_state'] );
    unset( $fields['shipping']['billing_state'] );

    foreach ($fields as $key => $value) {
        foreach ($fields[$key] as $k => $v) {
            $fields[$key][$k]['placeholder'] = $fields[$key][$k]['label'];
        }
    }

    return $fields;
}, 12 );

/**
 * Redirect shop page to search page
 */
add_action( 'template_redirect', function () {
  $search_page_id = get_field('page_de_recherche', 'option');
    if ( is_shop() && $search_page_id ) {
        wp_redirect( get_permalink( $search_page_id ), 301 );
        exit;
    }
}, 12 );

/**
 * Remove "my account" tabs
 */
add_filter( 'woocommerce_account_menu_items', function ($menu_links) {
//        unset( $menu_links['edit-address'] ); // Addresses
    unset( $menu_links['dashboard'] ); // Remove Dashboard
    //unset( $menu_links['payment-methods'] ); // Remove Payment Methods
    //unset( $menu_links['orders'] ); // Remove Orders
    unset( $menu_links['downloads'] ); // Disable Downloads
    //unset( $menu_links['edit-account'] ); // Remove Account details tab
    //unset( $menu_links['customer-logout'] ); // Remove Logout link

    return $menu_links;
} );


/**
 * Add the field to the checkout page
 *
 * @link https://gitlab.com/Junscuzzy/wp-content/blob/master/themes/nighthawks/inc/wc-custom.php
 */
add_action( 'woocommerce_after_order_notes', function ($checkout) {

    /* Type select
        'type' => 'select',
        'options' => array(
            '0' => 'Tranche d\'âges',
            ''
        ),
     */

    // Tranche d'âges
    woocommerce_form_field( 'age', array(
        'type' => 'text',
        'class' => array(),
        'placeholder' => __( 'Tranche d\'âges' ),
        'required' => true,
    ), $checkout->get_value( 'date_liv' ) );

    // Type d'événement
    woocommerce_form_field( 'event_type', array(
        'type' => 'text',
        'class' => array(),
        'placeholder' => __( 'Type d\'événement' ),
        'required' => true,
    ), $checkout->get_value( 'event_type' ) );

} );

/**
 * Validation des données
 */
add_action( 'woocommerce_checkout_process', function () {
    if ( !$_POST['age'] ) {
        wc_add_notice( __( "Merci d'indiquer la tranche d'âges des participants" ), 'error' );
    }
    if ( !$_POST['event_type'] ) {
        wc_add_notice( __( 'Merci d\'indiquer le type d\'événement ( Anniversaire, team Building, EVG... )' ), 'error' );
    }
} );

/**
 * Update new values
 */
add_action( 'woocommerce_checkout_update_order_meta', function ($order_id) {

    if ( !empty( $_POST['age'] ) ) {
        update_post_meta( $order_id, 'age', sanitize_text_field( $_POST['age'] ) );
    }
    if ( !empty( $_POST['event_type'] ) ) {
        update_post_meta( $order_id, 'event_type', sanitize_text_field( $_POST['event_type'] ) );
    }
} );

/**
 * Display field value on the order edit page
 */
add_action( 'woocommerce_admin_order_data_after_order_details', function ($order) {
    global $post;
    if ( get_post_meta( $post->ID, 'age', true ) ) {
        echo '<p class="form-field form-field-wide">' . __( 'Tranche d\'âges ' ) . ': <br/>' . get_post_meta( $post->ID, 'age', true ) . '</p>';
    }
    if ( get_post_meta( $post->ID, 'event_type', true ) ) {
        echo '<p class="form-field form-field-wide">' . __( 'Type d\'événement ' ) . ':<br/>' . get_post_meta( $post->ID, 'event_type', true ) . '</p>';
    }

} );
