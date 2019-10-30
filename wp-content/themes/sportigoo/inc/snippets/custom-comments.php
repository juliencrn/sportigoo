<?php

if ( !function_exists('sportigoo_comment')) {
    /**
     * Coment list callback
     *
     * @return HTML
     */
    function sportigoo_comment($comment, $args, $depth)
    {
        $add_below = 'div' === $args['style'] ? 'comment' : 'div-comment'; ?>

    <div <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID() ?>">

        <?php if ('div' != $args['style']) { ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
    <?php } ?>
        <div class="comment__meta">
            <p>
                <?php printf( __( '<span class="fn">%s</span>' ), get_comment_author_link() ); ?>
                <?php printf( __( '<span class="date">%1$s, %2$s</span>' ), get_comment_time(), get_comment_date() ); ?>
            </p>
        </div>
        <?php if ( $comment->comment_approved == '0' ) { ?>
        <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
        <br/>
    <?php } ?>

        <div class="comment__content">
            <?php comment_text(); ?>
        </div>

        <div class="comment__reply">
            <div class="reply_link">
                <?php comment_reply_link(
                    array_merge( $args, array(
                        'add_below' => $add_below,
                        'depth' => $depth,
                        'max_depth' => $args['max_depth'],
                    ) )
                ); ?>
            </div>
        </div>

        <?php
        if ('div' != $args['style']) : ?></div><?php endif;
    }
}



if ( ! function_exists('sportigoo_reply_comment_text')) {
    /**
     * Build HTML "réponse" output
     *
     * @return string HTML
     */
    function sportigoo_reply_comment_text() {
        $rep = "Répondre";
        $arrow_url = get_stylesheet_directory_uri() . "/static/img/double-angle-pointing-to-right.png";
        if ( have_rows( 'blog_labels', 'option' ) ) {
            while (have_rows( 'blog_labels', 'option' )) {
                the_row();
                $rep = get_sub_field( 'repondre_au_commentaire' );
            }
        }
        $rep = '<img src="' . $arrow_url . '" alt="Répondre" width="10" height="10"> ' . $rep;
        return $rep;
    }
}


if ( ! function_exists('')) {
    /**
     * Ajax load more comments
     */
    add_action('wp_ajax_load_more_comments', 'sportigoo_load_more_comments'); // wp_ajax_{action}
    add_action('wp_ajax_nopriv_load_more_comments', 'sportigoo_load_more_comments'); // wp_ajax_nopriv_{action}

    function sportigoo_load_more_comments(){

        // maybe it isn't the best way to declare global $post variable, but it is simple and works perfectly!
        global $post;
        $post = get_post( $_POST['post_id'] );
        setup_postdata( $post );

        // actually we must copy the params from wp_list_comments() used in our theme
        wp_list_comments( array(
            'walker' => null,
            'max_depth' => 2,
            'style' => 'div',
            'callback' => "sportigoo_comment",
            'end-callback' => null,
            'type' => 'all',
            'reply_text' => sportigoo_reply_comment_text(),
            'page' => $_POST['cpage'], // current comment page
            'per_page' => get_option('comments_per_page'),
            'reverse_top_level' => true,
            'reverse_children' => '',
            'format' => 'html5', // or 'xhtml' if no 'HTML5' theme support
            'short_ping' => true,   // @since 3.6
            'echo' => true
        ) );
        die; // don't forget this thing if you don't want "0" to be displayed
    }
}