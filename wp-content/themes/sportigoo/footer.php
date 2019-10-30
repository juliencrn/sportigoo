<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package sportigoo
 */

$footer_news_link = 'href="#the_news" ';
if ( !is_product() && !is_shop() && !is_page(35) && !is_page(41) && !is_single()) {
    get_template_part( 'template-parts/modal', 'newsletter' );
    $footer_news_link = 'href="#newsletter_modal" rel="modal:open"';
}
?>

</div><!-- #sportigoo__main -->

<footer>
    <div class="footer">
        <div class="container">
            <div class="footer__col">

                <?php zz_custom_logo(); ?>

                <div class="socials">
                    <?php
                    $social_list = array('instagram', 'facebook', 'twitter', 'linkedin', 'pinterest');
                    zz_print_social_list( $social_list );
                    ?>
                </div>

            </div>

            <?php if ( have_rows( 'widget_footer', 'option' ) ) {
                while (have_rows( 'widget_footer', 'option' )) {
                    the_row(); ?>

                    <div class="footer__col">
                        <h4><?php the_sub_field( 'titre_contact' ); ?></h4>
                        <?php wp_nav_menu( array(
                            'theme_location' => 'footer-1',
                            'menu_class' => 'footer__links'
                        ) ); ?>
                    </div>

                    <div class="footer__col">
                        <h4><?php the_sub_field( 'titre_univers' ); ?></h4>
                        <?php wp_nav_menu( array(
                            'theme_location' => 'footer-2',
                            'menu_class' => 'footer__links'
                        ) ); ?>
                    </div>

                    <div class="footer__col">
                        <h4><?php the_sub_field( 'titre_sur-mesure' ); ?></h4>
                        <?php wp_nav_menu( array(
                            'theme_location' => 'footer-3',
                            'menu_class' => 'footer__links'
                        ) ); ?>
                    </div>

                    <div class="footer__col">
                        <h4><?php the_sub_field( 'titre_newsletter' ); ?></h4>
                        <p><?php the_sub_field( 'texte_newsletter' ); ?></p>
                        <a <?= $footer_news_link ?> class="button button--white">S'inscrire</a>
                    </div>

                <?php }
            } ?>




        </div>
    </div>
    <div class="copyright">
        <div class="container">
            <div class="footer-flex">
                <div class="copyright__align-left">
                    <?php the_field('footer_copyright', 'option'); ?>
                </div>

                <?php wp_nav_menu( array(
                    'theme_location' => 'footer-copy',
                    'menu_id' => 'footer-copy',
                    'menu_class' => 'copyright__align-right'
                ) ); ?>
            </div>
        </div>
    </div>
</footer>




<?php get_template_part( 'template-parts/cookie' ); ?>

<?php wp_footer(); ?>

<!-- Google Tag Manager (noscript) -->
<noscript>
  <iframe
    src="https://www.googletagmanager.com/ns.html?id=GTM-5RVMR6G"
    height="0"
    width="0"
    style="display:none;visibility:hidden"
  ></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

</body>
</html>
