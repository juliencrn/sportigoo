<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sportigoo
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
    return;
}

// vars
$comment_title = "Laissez un commentaire";
$button_text = "Voir plus de commentaires";
$comment_submit = "Envoyer";
$comment_placeholder = "Commentaire";
if ( have_rows( 'blog_labels', 'option' ) ) {
    while (have_rows( 'blog_labels', 'option' )) {
        the_row();
        $comment_title = get_sub_field('titre_commentaires') ?: $comment_title;
        $button_text = get_sub_field('plus_de_commentaires') ?: $button_text;
        $comment_submit = get_sub_field('bouton_commenter') ?: $comment_submit;
        $comment_placeholder = get_sub_field('commentaire_placeholder') ?: $comment_placeholder;
    }
}
?>

<div class="container">
    <div class="commentaires" id="comments">
        <h3 class="t-center commentaires__title"><?= $comment_title ?></h3>

        <?php
        /**
         * The Form
         */
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

        $comment_form['comment_field'] .= '<p><textarea id="comment" name="comment"  rows="5" required placeholder="'.$comment_placeholder.'"></textarea></p>';

        comment_form( $comment_form );

        /**
         * The comments list
         */
        if ( have_comments() ) {

            echo '<div class="commentaires__reponses">';

            wp_list_comments( array(
                'walker' => null,
                'max_depth' => 2,
                'style' => 'div',
                'callback' => "sportigoo_comment",
                'end-callback' => null,
                'type' => 'all',
                'reply_text' => sportigoo_reply_comment_text(),
                'page' => '',
                'per_page' => get_option( 'comments_per_page' ),
                'reverse_top_level' => true,
                'reverse_children' => '',
                'format' => 'html5', // or 'xhtml' if no 'HTML5' theme support
                'short_ping' => true,   // @since 3.6
                'echo' => true
            ) );


            if ( !comments_open() ) { ?>
                <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'sportigoo' ); ?></p>
            <?php }

            echo '</div>';
        }


        /**
         * Load more button
         */
        $cpage = get_query_var( 'cpage' ) ? get_query_var( 'cpage' ) : 1;
        if ( $cpage > 1 ) {
            echo '
                <input type="hidden" id="loadmore_comments_text" value="'.$button_text.'">
                <div class="loadmore_comments t-center">'.$button_text.'</div>
                <script>
                    let ajaxurl = "' . site_url( 'wp-admin/admin-ajax.php' ) . '";
                    let parent_post_id = ' . get_the_ID() . ';
                    let cpage = ' . $cpage . ';
                </script>';
        } ?>

    </div>
</div>