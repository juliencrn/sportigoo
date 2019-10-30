<?php

// Si le plugin est actif
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if (is_plugin_active( 'wp-mail-smtp/wp_mail_smtp.php')) {

    //var_dump(get_option('wp_mail_smtp'));

    /**
     * Pré-remplir les réglages du plugin WP MAIL SMTP
     *
     * Cela est fait pour faire tourner le server SMTP
     * - En production avec le MX d'OHV
     * - En local avec maildev
     *
     * Les variable "define" sont à définir dans le wp-config.php
     *
     * @link https://github.com/djfarrelly/MailDev
     * @link https://wordpress.org/plugins/wp-mail-smtp/
     * @version wp-mail-smtp = 1.4.1
     * @command maildev
     */
    add_action('admin_init', function () {

        // On récupère les réglages du plugin
        $options = get_option('wp_mail_smtp');
        $smtp = $options ? $options['smtp'] : array();

        // On modifie le tableau SMTP
        if (defined('SMTP_AUTO_TLS')) {
            $smtp['autotls'] =  SMTP_AUTO_TLS;
        }
        if (defined('SMTP_HOST')) {
            $smtp['host'] =  SMTP_HOST;
        }
        if (defined('SMTP_ENCRYPT')) {
            $smtp['encryption'] =  SMTP_ENCRYPT;
        }
        if (defined('SMTP_PORT')) {
            $smtp['port'] =  SMTP_PORT;
        }
        if (defined('SMTP_PASS')) {
            $smtp['pass'] =  SMTP_PASS;
        }
        if (defined('SMTP_AUTH')) {
            $smtp['auth'] =  SMTP_AUTH;
        }

        $smtp['user'] =  get_bloginfo('admin_email');

        // On met à jour l'option utilisé par le plugin
        $options['smtp'] = $smtp;
        update_option('wp_mail_smtp', $options);

    });
}