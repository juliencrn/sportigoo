<?php

/**
 * Remplir l'attribut Alt d'une image depuis son titre AUTOMATIQUEMENT
 *
 * @link https://developer.wordpress.org/reference/hooks/add_attachment/
 */
add_action( 'add_attachment', 'whl_set_image_alt' );


/**
 * Remplir l'attribut Alt d'une image depuis son titre
 *
 * @param int $post_ID ID de l'image
 */
function whl_set_image_alt ( $post_ID ) {

    // On vérifie que le 'post' est bien une image
    if ( wp_attachment_is_image( $post_ID ) ) {

        // On récupère le titre
        $img_title = get_post( $post_ID )->post_title;

        // Nettoyage du titre en supprimant les caractères spéciaux
        $img_title = preg_replace( '%\s*[-_\s]+\s*%', ' ', $img_title );

        // On 'capitalise' le titre
        $img_title = ucwords( strtolower( $img_title ) );

        // Et enfin on met à jour l'attribut ALT
        update_post_meta( $post_ID, '_wp_attachment_image_alt', $img_title );
    }
}