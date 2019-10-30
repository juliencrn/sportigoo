<?php
/**
 * This template used in product page
 *
 * Its settings are in theme options
 *
 * "reverser" Button used to booking woocommerce product
 */

$bouton_reserver = 'Réserver';
$bg_url = "";
if ( have_rows( 'labels_e-commerce', 'option' ) ) {
    while (have_rows( 'labels_e-commerce', 'option' )) {
        the_row();
        $bouton_reserver = get_sub_field( 'bouton_reserver' ) ?: $bouton_reserver;
    }
}
global $product;
$bg_url = get_field( 'background_video_de_votre_session', $product->get_id() );

if ( have_rows( 'video_de_la_session', 'option' ) ) {
    while (have_rows( 'video_de_la_session', 'option' )) {
        the_row(); ?>
        <section class="film">
            <div class="film__bg" style="background-image: url('<?= $bg_url ?>');">
                <div class="film__bg-filter"></div>
                <div class="film__bg-filter film__bg-filter-grad"></div>
                <div class="container">
                    <h2 class="t-center film__title">
                        <?php the_sub_field( 'titre' ); ?>
                        <span class="t-orange"> <?php the_sub_field( 'titre_partie_orange' ); ?></span>
                        <small><br><?php the_sub_field( 'sous_titre' ); ?></small>
                    </h2>
                    <p class="film__text">
                        <?php the_sub_field( 'texte' ); ?>
                    </p>
                    <div class="film__button">
                        <a class="button button--white" href="#booking_now" rel="modal:open">
                            <?= $bouton_reserver ?>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    <?php }
}
