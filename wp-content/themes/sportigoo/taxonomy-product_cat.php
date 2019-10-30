<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
$pattern_url = get_stylesheet_directory_uri() . '/dist/img/pattern-activite.png';
$term = get_queried_object();
?>

    <div class="content activities-search">
        <section class="activities activities-display">
            <div class="container activities-search__header">
                <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                    <h1 class="section-title"><?php woocommerce_page_title(); ?></h1>
                <?php endif; ?>
                <div class="">
                    <?php
                    /**
                     * Hook: woocommerce_archive_description.
                     *
                     * @hooked woocommerce_taxonomy_archive_description - 10
                     * @hooked woocommerce_product_archive_description - 10
                     */
                    //do_action( 'woocommerce_archive_description' );
                    ?>

                    <?php the_field('texte_avant_le_contenu', $term); ?>
                </div>
            </div>
            <div class="activities__pattern" style="background-image: url(<?= $pattern_url ?>);"></div>
            <div class="activities__block">


                <?php if ( woocommerce_product_loop() ) {

                    /**
                     * Hook: woocommerce_before_shop_loop.
                     *
                     * @hooked woocommerce_output_all_notices - 10
                     * @hooked woocommerce_result_count - 20
                     * @hooked woocommerce_catalog_ordering - 30
                     */
                    do_action( 'woocommerce_before_shop_loop' );

                    woocommerce_product_loop_start();

                    if ( wc_get_loop_prop( 'total' ) ) { ?>

                        <div id="results" class="activities__row hasPrev container">

                            <?php while (have_posts()) {
                                the_post();

                                /**
                                 * Hook: woocommerce_shop_loop.
                                 *
                                 * @hooked WC_Structured_Data::generate_product_data() - 10
                                 */
                                do_action( 'woocommerce_shop_loop' );

                                wc_get_template_part( 'content', 'product' );
                            } ?>

                        </div>
                        <?php get_template_part( 'pages/homepage/activities', 'preview' ); ?>

                    <?php }

                    woocommerce_product_loop_end();

                    /**
                     * Hook: woocommerce_after_shop_loop.
                     *
                     * @hooked woocommerce_pagination - 10
                     */
                    do_action( 'woocommerce_after_shop_loop' );
                } else {
                    /**
                     * Hook: woocommerce_no_products_found.
                     *
                     * @hooked wc_no_products_found - 10
                     */
                    do_action( 'woocommerce_no_products_found' );
                } ?>

            </div>
            <div class="container">
                <?php the_field('texte_apres_le_contenu', $term); ?>
            </div>
        </section>
    </div>

<?php

get_footer( 'shop' );
