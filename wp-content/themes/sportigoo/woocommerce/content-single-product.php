<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}


global $product;
$product_id = $product->get_id();
$fields = get_fields( $product_id )['produit'];
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( "content produit_sporti" ); ?>>

    <?php get_template_part( 'template-parts/activity', 'header' ); ?>

    <div id="booking_now" class="jq-modal">
        <div class="booking_now__content">
            <?php
            /**
             * Hook: woocommerce_single_product_summary.
             *
             * @hooked woocommerce_template_single_title - 5
             * @hooked woocommerce_template_single_rating - 10
             * @hooked woocommerce_template_single_price - 10
             * @hooked woocommerce_template_single_excerpt - 20
             * @hooked woocommerce_template_single_add_to_cart - 30
             * @hooked woocommerce_template_single_meta - 40
             * @hooked woocommerce_template_single_sharing - 50
             * @hooked WC_Structured_Data::generate_product_data() - 60
             */
            do_action( 'woocommerce_single_product_summary' );
            ?>
        </div>
    </div>

    <section>
        <div class="container">
            <div class="woocommerce__notices">
                <?php
                /**
                 * Hook: woocommerce_before_single_product.
                 *
                 * @hooked wc_print_notices - 10
                 */
                do_action( 'woocommerce_before_single_product' );
                ?>
            </div>
        </div>
    </section>


    <section>
        <div class="blog_style">
            <div class="container">
                <div class="row">
                    <div class="twelve cols">
                        <h1 class="h2 t-center">
                            <?php the_field( 'titre_description', $product_id ); ?>
                            <small><br><?php the_field( 'sous_titre_description' ); ?></small>
                        </h1>
                    </div>
                </div>

                <div class="row">
                    <?php if ( have_rows( 'description_colonne_1' ) ) { ?>
                        <div class="six cols">
                            <?php while (have_rows( 'description_colonne_1' )) {
                                the_row();

                                if ( get_sub_field( 'type' ) == 'text' ) {
                                    // Text
                                    the_sub_field( 'block_de_texte' );
                                } else {
                                    // Slider
                                    if ( have_rows( 'slider' ) ) { ?>
                                        <div class="prel">
                                            <div class="carousel">
                                                <?php while (have_rows( 'slider' )) {
                                                    the_row(); ?>
                                                    <div
                                                            class="carousel__item"
                                                            style="background-image:url('<?php the_sub_field( "image" ) ?>');"
                                                    ></div>

                                                <?php } ?>
                                            </div>
                                            <div class="carousel__after1"></div>
                                            <div class="carousel__after2"></div>
                                            <div class="carousel__prev">
                                                <svg width="25" height="25">
                                                    <use xlink:href="#chevron-left"></use>
                                                </svg>
                                            </div>
                                            <div class="carousel__next">
                                                <svg width="25" height="25">
                                                    <use xlink:href="#chevron-right"></use>
                                                </svg>
                                            </div>
                                        </div>
                                    <?php }
                                }
                            } ?>
                        </div>
                    <?php } ?>

                    <?php if ( have_rows( 'description_colonne_2' ) ) { ?>
                        <div class="six cols">
                            <?php while (have_rows( 'description_colonne_2' )) {
                                the_row();

                                if ( get_sub_field( 'type' ) == 'text' ) {
                                    // Text
                                    the_sub_field( 'block_de_texte' );
                                } else {
                                    // Slider
                                    if ( have_rows( 'slider' ) ) { ?>
                                        <div class="prel">
                                            <div class="carousel">
                                                <?php while (have_rows( 'slider' )) {
                                                    the_row(); ?>
                                                    <div
                                                            class="carousel__item"
                                                            style="background-image:url('<?php the_sub_field( "image" ) ?>');"
                                                    ></div>

                                                <?php } ?>
                                            </div>
                                            <div class="carousel__after1"></div>
                                            <div class="carousel__after2"></div>
                                            <div class="carousel__prev">
                                                <svg width="25" height="25">
                                                    <use xlink:href="#chevron-left"></use>
                                                </svg>
                                            </div>
                                            <div class="carousel__next">
                                                <svg width="25" height="25">
                                                    <use xlink:href="#chevron-right"></use>
                                                </svg>
                                            </div>
                                        </div>
                                    <?php }
                                }
                            } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>


    <?php if ( have_rows( 'regles' ) ) :
        $laws = array();
        $i = 0; ?>

        <section class="laws">
            <div class="container">
                <h2 class="t-center">
                    <?php the_field('title_regles', 'option'); ?>
                </h2>
                <div class=" carousel__laws" style="padding: 0 30px">

                <?php while (have_rows( 'regles' )) {
                    the_row();
                    $image = get_sub_field( 'icone' ); ?>

                    <div class="" style="padding: 15px">
                        <div class="alaw">
                            <?php if ( !empty( $image ) ) { ?>
                                <div class="alaw__img">
                                    <div class="alaw__img-bg" style="background-image: url(<?= $image['url']; ?>)">

                                    </div>
                                </div>
                            <?php } ?>
                            <div class="alaw__text">
                                <p><?php the_sub_field( 'regle' ); ?></p>
                            </div>
                        </div>
                    </div>

                <?php } ?>
                </div>
                <div class="carousel__prev t-blue " style="left: 0">
                    <svg width="25" height="25">
                        <use xlink:href="#chevron-left"></use>
                    </svg>
                </div>
                <div class="carousel__next t-blue " style="right:0">
                    <svg width="25" height="25">
                        <use xlink:href="#chevron-right"></use>
                    </svg>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ( have_rows( 'questions-reponses' ) ) : ?>
        <section>
            <div class="container">
                <div class="row">
                    <div class="twelve cols">
                        <div class="faq">
                            <?php while (have_rows( 'questions-reponses' )) : the_row(); ?>
                                <div class="faq__item">
                                    <div class="faq__item-visible">
                                        <p>
                                            <?php the_sub_field( 'question' ) ?>
                                        </p>
                                        <div class="faq__item-btn">
                                            <svg width="15" height="15">
                                                <use xlink:href="#chevron-down"></use>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="faq__item-hidden">
                                        <p>
                                            <?php the_sub_field( 'reponse' ) ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php
    /**
     * Hook: woocommerce_after_single_product_summary.
     *
     * @hooked woocommerce_output_product_data_tabs - 10
     * @hooked woocommerce_upsell_display - 15
     * @hooked woocommerce_output_related_products - 20
     */
    do_action( 'woocommerce_after_single_product_summary' );
    ?>

    <?php if ( $fields['option_film'] ) {
        get_template_part( 'template-parts/product', 'film' );
    } ?>

</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>


