<?php


// Cacher l'adminbar pour les users basics
if (!current_user_can('edit_posts')) {
    show_admin_bar(false);
}