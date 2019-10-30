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


?>
    <div class="container" style="padding: 100px 0;">
        <?php


        /**
         * Hook: woocommerce_before_main_content.
         *
         * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
         * @hooked woocommerce_breadcrumb - 20
         * @hooked WC_Structured_Data::generate_website_data() - 30
         */
        //do_action( 'woocommerce_before_main_content' );
        ?>

        <header class="woocommerce-products-header">
            <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
            <?php endif; ?>

            <?php
            /**
             * Hook: woocommerce_archive_description.
             *
             * @hooked woocommerce_taxonomy_archive_description - 10
             * @hooked woocommerce_product_archive_description - 10
             */
            do_action( 'woocommerce_archive_description' );
            ?>
        </header>

        <?php
        if ( woocommerce_product_loop() ) {

            /**
             * Hook: woocommerce_before_shop_loop.
             *
             * @hooked woocommerce_output_all_notices - 10
             * @hooked woocommerce_result_count - 20
             * @hooked woocommerce_catalog_ordering - 30
             */
            do_action( 'woocommerce_before_shop_loop' );

            woocommerce_product_loop_start();

            if ( wc_get_loop_prop( 'total' ) ) {
                while (have_posts()) {
                    the_post();

                    /**
                     * Hook: woocommerce_shop_loop.
                     *
                     * @hooked WC_Structured_Data::generate_product_data() - 10
                     */
                    do_action( 'woocommerce_shop_loop' );

                    wc_get_template_part( 'content', 'product' );
                }
            }

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
        }
        /* ?>

        <div class="content activities-search">
            <section class="activities activities-display">
                <div class="container activities-search__header">
                    <h1 class="section-title">"Bubble Foot"</h1>
                    <p class="section-paragraph"> Recherches associés Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod</p>
                </div>
                <div class="activities__pattern" style="background-image: url('img/pattern-activite.png');"></div>
                <div class="activities__block">
                    <div class="activities__row container">
                        <div class="activities__item-wrapper"><a class="activities__link" href="produit.html">
                                <div class="activities__item">
                                    <div class="activities__img" style="background-image: url(img/bubble-2.jpg);"></div>
                                    <h4 class="activities__title">Bubble Foot</h4>
                                    <div class="activities__filter"></div>
                                </div></a>
                            <svg class="activities__preview-button" width="20" height="20">
                                <use xlink:href="#chevron-down"></use>
                            </svg>
                        </div>
                        <div class="activities__item-wrapper"><a class="activities__link" href="produit.html">
                                <div class="activities__item">
                                    <div class="activities__img" style="background-image: url(img/paintball.jpg);"></div>
                                    <h4 class="activities__title">Paintball</h4>
                                    <div class="activities__filter"></div>
                                </div></a>
                            <svg class="activities__preview-button" width="20" height="20">
                                <use xlink:href="#chevron-down"></use>
                            </svg>
                        </div>
                        <div class="activities__item-wrapper"><a class="activities__link" href="produit.html">
                                <div class="activities__item">
                                    <div class="activities__img" style="background-image: url(img/bubble-2.jpg);"></div>
                                    <h4 class="activities__title">Bubble Foot</h4>
                                    <div class="activities__filter"></div>
                                </div></a>
                            <svg class="activities__preview-button" width="20" height="20">
                                <use xlink:href="#chevron-down"></use>
                            </svg>
                        </div>
                        <div class="activities__item-wrapper"><a class="activities__link" href="produit.html">
                                <div class="activities__item">
                                    <div class="activities__img" style="background-image: url(img/paintball.jpg);"></div>
                                    <h4 class="activities__title">Paintball</h4>
                                    <div class="activities__filter"></div>
                                </div></a>
                            <svg class="activities__preview-button" width="20" height="20">
                                <use xlink:href="#chevron-down"></use>
                            </svg>
                        </div>
                    </div>
                    <div class="activity-preview" style="background-image: url('img/arrow.jpg');">
                        <div class="container">
                            <div class="activity-preview__container">
                                <div class="activity-preview__left">
                                    <div class="categories"><a class="categories__item" href="resultat.html">Catégorie</a><a class="categories__item" href="resultat.html">Tag</a></div>
                                    <h3 class="activity-preview__title">Archery Goo</h3>
                                    <p class="activity-preview__excerpt">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Repellat esse dolores aperiam laborum accusantium, dignissimos quasi ipsum quibusdam autem modi sed atque illo explicabo rem deleniti vero! Rem, temporibus explicabo!</p><a class="button button--white button--jumbotron" href="produit.html">Réserver</a>
                                    <div class="stars">
                                        <svg width="25" height="25">
                                            <use xlink:href="#star"></use>
                                        </svg>
                                        <svg width="25" height="25">
                                            <use xlink:href="#star"></use>
                                        </svg>
                                        <svg width="25" height="25">
                                            <use xlink:href="#star"></use>
                                        </svg>
                                        <svg width="25" height="25">
                                            <use xlink:href="#star"></use>
                                        </svg>
                                        <svg width="25" height="25">
                                            <use xlink:href="#star"></use>
                                        </svg>
                                    </div>
                                    <p class="activity-preview__price">À partir de 14€/personne</p>
                                </div>
                                <div class="activity-preview__right">
                                    <div class="activity-preview__video-button">
                                        <svg width="75" height="75">
                                            <use xlink:href="#play"></use>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="activity-preview__video">
                            <div class="activity-preview__video-container">
                                <div class="activity-preview__close-video">
                                    <svg width="25" height="25">
                                        <use xlink:href="#cross"></use>
                                    </svg>
                                </div>
                                <iframe width="560" height="315" src="https://www.youtube.com/embed/H2PDrPh-fxE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="allowfullscreen"></iframe>
                            </div>
                        </div>
                        <div class="activity-preview__filter"></div>
                    </div>
                </div>
                <div class="activities__block">
                    <div class="activities__row container">
                        <div class="activities__item-wrapper"><a class="activities__link" href="produit.html">
                                <div class="activities__item">
                                    <div class="activities__img" style="background-image: url(img/paintball.jpg);"></div>
                                    <h4 class="activities__title">Paintball</h4>
                                    <div class="activities__filter"></div>
                                </div></a>
                            <svg class="activities__preview-button" width="20" height="20">
                                <use xlink:href="#chevron-down"></use>
                            </svg>
                        </div>
                        <div class="activities__item-wrapper"><a class="activities__link" href="produit.html">
                                <div class="activities__item">
                                    <div class="activities__img" style="background-image: url(img/bubble-2.jpg);"></div>
                                    <h4 class="activities__title">Bubble Foot</h4>
                                    <div class="activities__filter"></div>
                                </div></a>
                            <svg class="activities__preview-button" width="20" height="20">
                                <use xlink:href="#chevron-down"></use>
                            </svg>
                        </div>
                        <div class="activities__item-wrapper"><a class="activities__link" href="produit.html">
                                <div class="activities__item">
                                    <div class="activities__img" style="background-image: url(img/paintball.jpg);"></div>
                                    <h4 class="activities__title">Paintball</h4>
                                    <div class="activities__filter"></div>
                                </div></a>
                            <svg class="activities__preview-button" width="20" height="20">
                                <use xlink:href="#chevron-down"></use>
                            </svg>
                        </div>
                        <div class="activities__item-wrapper"><a class="activities__link" href="produit.html">
                                <div class="activities__item">
                                    <div class="activities__img" style="background-image: url(img/bubble-2.jpg);"></div>
                                    <h4 class="activities__title">Bubble Foot</h4>
                                    <div class="activities__filter"></div>
                                </div></a>
                            <svg class="activities__preview-button" width="20" height="20">
                                <use xlink:href="#chevron-down"></use>
                            </svg>
                        </div>
                    </div>
                    <div class="activity-preview" style="background-image: url('img/arrow.jpg');">
                        <div class="container">
                            <div class="activity-preview__container">
                                <div class="activity-preview__left">
                                    <div class="categories"><a class="categories__item" href="resultat.html">Catégorie</a><a class="categories__item" href="resultat.html">Tag</a></div>
                                    <h3 class="activity-preview__title">Archery Goo</h3>
                                    <p class="activity-preview__excerpt">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Repellat esse dolores aperiam laborum accusantium, dignissimos quasi ipsum quibusdam autem modi sed atque illo explicabo rem deleniti vero! Rem, temporibus explicabo!</p><a class="button button--white button--jumbotron" href="produit.html">Réserver</a>
                                    <div class="stars">
                                        <svg width="25" height="25">
                                            <use xlink:href="#star"></use>
                                        </svg>
                                        <svg width="25" height="25">
                                            <use xlink:href="#star"></use>
                                        </svg>
                                        <svg width="25" height="25">
                                            <use xlink:href="#star"></use>
                                        </svg>
                                        <svg width="25" height="25">
                                            <use xlink:href="#star"></use>
                                        </svg>
                                        <svg width="25" height="25">
                                            <use xlink:href="#star"></use>
                                        </svg>
                                    </div>
                                    <p class="activity-preview__price">À partir de 14€/personne</p>
                                </div>
                                <div class="activity-preview__right">
                                    <div class="activity-preview__video-button">
                                        <svg width="75" height="75">
                                            <use xlink:href="#play"></use>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="activity-preview__video">
                            <div class="activity-preview__video-container">
                                <div class="activity-preview__close-video">
                                    <svg width="25" height="25">
                                        <use xlink:href="#cross"></use>
                                    </svg>
                                </div>
                                <iframe width="560" height="315" src="https://www.youtube.com/embed/H2PDrPh-fxE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="allowfullscreen"></iframe>
                            </div>
                        </div>
                        <div class="activity-preview__filter"></div>
                    </div>
                </div>
                <div class="activities__block">
                    <div class="activities__row container">
                        <div class="activities__item-wrapper"><a class="activities__link" href="produit.html">
                                <div class="activities__item">
                                    <div class="activities__img" style="background-image: url(img/bubble-2.jpg);"></div>
                                    <h4 class="activities__title">Bubble Foot</h4>
                                    <div class="activities__filter"></div>
                                </div></a>
                            <svg class="activities__preview-button" width="20" height="20">
                                <use xlink:href="#chevron-down"></use>
                            </svg>
                        </div>
                        <div class="activities__item-wrapper"><a class="activities__link" href="produit.html">
                                <div class="activities__item">
                                    <div class="activities__img" style="background-image: url(img/paintball.jpg);"></div>
                                    <h4 class="activities__title">Paintball</h4>
                                    <div class="activities__filter"></div>
                                </div></a>
                            <svg class="activities__preview-button" width="20" height="20">
                                <use xlink:href="#chevron-down"></use>
                            </svg>
                        </div>
                        <div class="activities__item-wrapper"><a class="activities__link" href="produit.html">
                                <div class="activities__item">
                                    <div class="activities__img" style="background-image: url(img/bubble-2.jpg);"></div>
                                    <h4 class="activities__title">Bubble Foot</h4>
                                    <div class="activities__filter"></div>
                                </div></a>
                            <svg class="activities__preview-button" width="20" height="20">
                                <use xlink:href="#chevron-down"></use>
                            </svg>
                        </div>
                        <div class="activities__item-wrapper"><a class="activities__link" href="produit.html">
                                <div class="activities__item">
                                    <div class="activities__img" style="background-image: url(img/paintball.jpg);"></div>
                                    <h4 class="activities__title">Paintball</h4>
                                    <div class="activities__filter"></div>
                                </div></a>
                            <svg class="activities__preview-button" width="20" height="20">
                                <use xlink:href="#chevron-down"></use>
                            </svg>
                        </div>
                    </div>
                    <div class="activity-preview" style="background-image: url('img/arrow.jpg');">
                        <div class="container">
                            <div class="activity-preview__container">
                                <div class="activity-preview__left">
                                    <div class="categories"><a class="categories__item" href="resultat.html">Catégorie</a><a class="categories__item" href="resultat.html">Tag</a></div>
                                    <h3 class="activity-preview__title">Archery Goo</h3>
                                    <p class="activity-preview__excerpt">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Repellat esse dolores aperiam laborum accusantium, dignissimos quasi ipsum quibusdam autem modi sed atque illo explicabo rem deleniti vero! Rem, temporibus explicabo!</p><a class="button button--white button--jumbotron" href="produit.html">Réserver</a>
                                    <div class="stars">
                                        <svg width="25" height="25">
                                            <use xlink:href="#star"></use>
                                        </svg>
                                        <svg width="25" height="25">
                                            <use xlink:href="#star"></use>
                                        </svg>
                                        <svg width="25" height="25">
                                            <use xlink:href="#star"></use>
                                        </svg>
                                        <svg width="25" height="25">
                                            <use xlink:href="#star"></use>
                                        </svg>
                                        <svg width="25" height="25">
                                            <use xlink:href="#star"></use>
                                        </svg>
                                    </div>
                                    <p class="activity-preview__price">À partir de 14€/personne</p>
                                </div>
                                <div class="activity-preview__right">
                                    <div class="activity-preview__video-button">
                                        <svg width="75" height="75">
                                            <use xlink:href="#play"></use>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="activity-preview__video">
                            <div class="activity-preview__video-container">
                                <div class="activity-preview__close-video">
                                    <svg width="25" height="25">
                                        <use xlink:href="#cross"></use>
                                    </svg>
                                </div>
                                <iframe width="560" height="315" src="https://www.youtube.com/embed/H2PDrPh-fxE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="allowfullscreen"></iframe>
                            </div>
                        </div>
                        <div class="activity-preview__filter"></div>
                    </div>
                </div>
            </section>
        </div>

        <?php */
        /**
         * Hook: woocommerce_after_main_content.
         *
         * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
         */
        //do_action( 'woocommerce_after_main_content' );

        ///**
        // * Hook: woocommerce_sidebar.
        // *
        // * @hooked woocommerce_get_sidebar - 10
        // */
        //do_action( 'woocommerce_sidebar' );
        ?>
    </div>
<?php

get_footer( 'shop' );
