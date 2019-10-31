<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.5.0
 */
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $product;

if ( !comments_open() ) {
    return;
}

// vars
$comment_title = "Laissez un avis";
$avis_title = "Ils sont déjà réservé";
$avis_bg_url = "";
$comment_submit = "Envoyer";
$comment_placeholder = "Avis";
if ( have_rows( 'labels_e-commerce', 'option' ) ) {
    while (have_rows( 'labels_e-commerce', 'option' )) {
        the_row();
        $comment_title = get_sub_field( 'laisser_un_avis' ) ?: $comment_title;
        $avis_title = get_sub_field( 'titre_avis' ) ?: $avis_title;
        $comment_submit = get_sub_field( 'envoyer_un_avis' ) ?: $comment_submit;
        $comment_placeholder = get_sub_field( 'placeholder_avis' ) ?: $comment_placeholder;
    }
}
$avis_bg_url = get_field( 'background_avis', $product->get_id() );
?>

<?php if ( have_comments()  ) : // && get_comments_number() > 3 ?>
    <section id="reviews" class="avis">
        <h2 class="t-center avis__title">
            <?php echo $avis_title ?>
        </h2>
        <div class="avis__bg" style="background-image: url(<?php echo $avis_bg_url ?>);">
            <div class="avis__bg-filter"></div>
            <div class="container">
                <div class="avis__carousel <?php echo (get_comments_number() < 4) ?'hasMin': 'hasMore'; ?>" id="comments" data-count="<?php echo get_comments_number() ?>">
                    <?php wp_list_comments( apply_filters(
                        'woocommerce_product_review_list_args',
                        array('callback' => 'woocommerce_comments')
                    ) ); ?>
                </div>
                <div class="avis__prev">
                    <svg width="25" height="25">
                        <use xlink:href="#chevron-left"></use>
                    </svg>
                </div>
                <div class="avis__next">
                    <svg width="25" height="25">
                        <use xlink:href="#chevron-right"></use>
                    </svg>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>


<section class="produit_sporti__commentaires">
    <div class="container">
        <div class="commentaires">
            <h3 class="t-center commentaires__title"><?php echo $comment_title ?></h3>

            <?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>

                <div id="review_form_wrapper">
                    <div>
                        <?php
                        $commenter = wp_get_current_commenter();

                        $comment_form = array(
                            'class_form' => "formulaire",
                            'title_reply' => '',
                            'title_reply_to' => '',
                            'title_reply_before' => '',
                            'title_reply_after' => '',
                            'comment_notes_after' => '',
                            'fields' => array(),
                            'label_submit' => $comment_submit,
                            'class_submit' => 'button',
                            'id_submit' => 'submit',
                            'logged_in_as' => '',
                            'comment_field' => '',
                        );

                        if ( $account_page_url = wc_get_page_permalink( 'myaccount' ) ) {
                            $comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a review.', 'woocommerce' ), esc_url( $account_page_url ) ) . '</p>';
                        }

                        if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
                            $comment_form['comment_field'] = '<div class="comment-form-rating">
                        <select name="rating" id="rating" required>
							<option value="">' . esc_html__( 'Rate&hellip;', 'woocommerce' ) . '</option>
							<option value="5">' . esc_html__( 'Perfect', 'woocommerce' ) . '</option>
							<option value="4">' . esc_html__( 'Good', 'woocommerce' ) . '</option>
							<option value="3">' . esc_html__( 'Average', 'woocommerce' ) . '</option>
							<option value="2">' . esc_html__( 'Not that bad', 'woocommerce' ) . '</option>
							<option value="1">' . esc_html__( 'Very poor', 'woocommerce' ) . '</option>
						</select><br></div>';
                        }

                        $comment_form['comment_field'] .= '<p><textarea id="comment" name="comment"  rows="5" required placeholder="' . $comment_placeholder . '"></textarea></p>';

                        comment_form( array_merge(
                            apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ),
                            array('per_page' => 50)
                        ) );
                        ?>
                    </div>
                </div>

            <?php else : ?>

                <p class="woocommerce-verification-required"><?php _e( 'Only logged in customers who have purchased this product may leave a review.', 'woocommerce' ); ?></p>

            <?php endif; ?>
        </div>
    </div>
</section>


