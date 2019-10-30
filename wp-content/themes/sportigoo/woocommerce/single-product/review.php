<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div <?php comment_class( 'avis__item' ); ?> id="li-comment-<?php comment_ID() ?>">

    <div class="avis__quote">
        <svg width="15" height="15">
            <use xlink:href="#quote"></use>
        </svg>
    </div>

    <div class="avis__img">
        <?php
        /**
         * The woocommerce_review_before hook
         *
         * @hooked woocommerce_review_display_gravatar - 10
         */
        do_action( 'woocommerce_review_before', $comment );
        ?>
    </div>

    <?php
    /**
     * The woocommerce_review_before_comment_meta hook.
     *
     * @hooked woocommerce_review_display_rating - 10
     */
    //    do_action( 'woocommerce_review_before_comment_meta', $comment );
    ?>

    <p class="avis__name">
        <?php comment_author(); ?>
    </p>


    <p class="avis__avis">
        <?php
        /**
         * The woocommerce_review_comment_text hook
         *
         * @hooked woocommerce_review_display_comment_text - 10
         */
        do_action( 'woocommerce_review_comment_text', $comment );

        //do_action( 'woocommerce_review_after_comment_text', $comment ); ?>
    </p>


</div>
