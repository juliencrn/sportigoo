<?php

add_action( 'wp_ajax_sportigoo_get_preview', 'sportigoo_get_preview' );
add_action( 'wp_ajax_nopriv_sportigoo_get_preview', 'sportigoo_get_preview' );

function sportigoo_get_preview()
{
    // Initials vars
    $product_id = $_POST['product_id'];
    $product = wc_get_product( $product_id );

    // Get image
    $thumbnail_url = get_the_post_thumbnail_url( $product_id, 'full' );

    // Get video
    $iframe_src = false;
    $prix_public = false;
    if (have_rows('produit', $product_id)) {
        while (have_rows('produit', $product_id)) {
            the_row();
            $video = get_sub_field('video');
            if ( !empty( $video ) ) {
                preg_match( '/src="(.+?)"/', $video, $matches );
                $iframe_src = $matches[1];
            }

            // & price
            $prix_public = get_sub_field('prix_public');
        }
    }



    /**
     * Build HTML
     */

    // categories
    $html = "";
    $html .= "<div class=\"categories\">";
    $html .= zz_get_the_term_list(
        $product_id, 'product_cat', '', '', ''
    );
    $html .= "</div>";

    // Titre
    $html .= "<h3 class=\"activity-preview__title orange\">";
    $html .= get_the_title( $product_id );
    $html .= "</h3>";

    // Excerpt
    $html .= "<p class=\"activity-preview__excerpt\">";
    $html .= $product->get_short_description();
    $html .= "</p>";

    // Bouton
    $html .= "<a class=\"button button--white button--jumbotron\" href=\"" . get_permalink( $product_id ) . "\">";
    $html .= 'En savoir plus';
    $html .= "</a>";

    // Rating stars
    $html .= "<div class=\"stars woocommerce orange\">";
    $rating_count = $product->get_rating_count();
    $average = $product->get_average_rating();
    if ( $rating_count > 0 ) {
        $html .= wc_get_rating_html( $average, $rating_count );
    }
    $html .= "</div>";

    // price
    $html .= "<p class=\"activity-preview__price\">";
    $html .= empty( $prix_public ) ?  $product->get_price_html() : $prix_public;
    $html .= "</p>";


    // Send data to JS
    echo $response = json_encode( [
        'html' => $html,
        'video' => [$iframe_src,$video],
        'image' => $thumbnail_url
    ] );


    die();
}
