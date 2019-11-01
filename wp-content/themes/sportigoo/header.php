<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package sportigoo
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0, maximum-scale=1.0"/>
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <?php wp_head(); ?>

  <!-- Google Tag Manager -->
  <script>
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','<?php the_field("google_tag_manager", "option"); ?>');
  </script>
  <!-- End Google Tag Manager -->
</head>

<body <?php body_class(); ?>>

<input type="hidden" id="permalink" value="<?php the_permalink(); ?>">

<span style="display: none;">
    <?php echo file_get_contents( get_template_directory() . "/dist/img/symbol/sprite.svg" ); ?>
</span>

<?php if ( is_admin_bar_showing() ) {
    echo "<style>
            @media all and (min-width: 783px) {
                header.nav {top: 32px;}
            }
            @media all and (max-width: 782px) {}
        </style>";
} ?>

<?php $transparent = is_page( array(2, 39, 41, 32369) ) || is_singular( array('post') ) ? 'transparent nav--topped' : ''; ?>

<header class="nav <?php echo $transparent ?>">
    <div class="container nav__container">

        <div class="nav__logo">
            <?php zz_custom_logo(); ?>
        </div>

        <?php wp_nav_menu( array(
            'theme_location' => 'menu-1',
            'menu_id' => 'primary-menu',
            'menu_class' => 'nav__links-container'
        ) ); ?>

        <div class="nav__search">
            <form action="<?php echo site_url() ?>" role="search" method="get" style="margin-bottom: 0; display: flex;">
                <input class="nav__search-input" required name="s" id="search" type="text" placeholder="Rechecher..."
                       value="<?php the_search_query(); ?>"/>
                <input type="hidden" value="product" name="post_type"/>
                <button class="nav__search-button" style="border: none" type="submit">
                    <svg width="22" height="22">
                        <use xlink:href="#search"></use>
                    </svg>
                </button>
            </form>
        </div>

        <div class="socials socials--white">
            <a href="<?php echo get_permalink( 35 ); ?>">
                <svg width="18" height="18">
                    <use xlink:href="#mail"></use>
                </svg>
            </a>

            <?php
            $social_list = array('instagram', 'facebook', 'twitter');
            zz_print_social_list( $social_list );
            ?>
        </div>

        <?php if (WC()->cart->get_cart_contents_count()) { ?>
            <div class="socials" style="padding-left: 20px">
                <a href="<?php echo wc_get_cart_url(); ?>">
                    <svg width="18" height="18">
                        <use xlink:href="#cart"></use>
                    </svg>
                </a>
            </div>
        <?php } ?>

        <div class="hamburger">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </div>

    </div>
</header>

<?php
// Get taxonomy "lieu" term_id from "postal code" using ACF & cookies
update_option( 'region_term_id', 0 );
if ( isset( $_COOKIE['whereIam'] ) && $_COOKIE['whereIam'] != "0" ) {
    $cp = (int)$_COOKIE['whereIam'];
    foreach (get_terms( array(
        'taxonomy' => 'lieu',
        'hide_empty' => false
    ) ) as $term) {
        if ( get_field( 'numero', $term ) == $cp ) {
            update_option( 'region_term_id', $term->term_id );
        }
    }
}



?>

<div id="sportigoo__main" style="overflow-x: hidden;">
