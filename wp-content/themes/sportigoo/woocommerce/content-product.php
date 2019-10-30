<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
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

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
$thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
?>

<article data-id="<?php echo get_the_ID(); ?>" style="overflow: hidden;"  <?php wc_product_class('activities__item-wrapper'); ?>>
    <a class="activities__link" href="<?php the_permalink(); ?>">
        <div class="activities__item">
            <div class="activities__img" style="background-image: url(<?= $thumbnail_url ?>);"></div>
            <h4 class="activities__title">
                <?php the_title(); ?>
            </h4>
            <div class="activities__filter"></div>
        </div>
    </a>
    <svg class="activities__preview-button" width="20" height="20">
        <use xlink:href="#chevron-down"></use>
    </svg>
</article>
