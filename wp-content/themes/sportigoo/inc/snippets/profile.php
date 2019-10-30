<?php

/**
 * Cacher les options personnelles
 *
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/admin_print_scripts
 * @link https://developer.wordpress.org/reference/hooks/admin_print_scripts-hook_suffix/
 */
add_action( 'admin_print_scripts-profile.php', function ()
{
    ?>
    <style>
        .user-rich-editing-wrap,        /* Éditeur visuel */
        .user-syntax-highlighting-wrap, /* Édition de code */
        .user-admin-color-wrap,         /* Couleurs de l’interface d’administration */
        .user-comment-shortcuts-wrap,   /* Raccourcis clavier */
        .show-admin-bar,                /* Barre d’outils */
        .user-language-wrap,            /* Langue */
        .user-description-wrap,         /* Renseignements biographiques */
        .yoast.yoast-settings {         /* Réglages Yoast SEO */
            display: none;
        }
    </style>
    <?php
} );