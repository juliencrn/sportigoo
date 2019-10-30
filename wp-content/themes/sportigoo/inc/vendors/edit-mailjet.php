<?php

/**
 * Replace default Mailjet widget form file with your own
 * @param string $templatePath - the path of the default Mailjet widget form file
 * @return string
 */

add_filter( 'mailjet_widget_form_filename', function ($templatePath) {
    return get_stylesheet_directory() . '/template-parts/mailjet-newsletter-widget.php';
} );
