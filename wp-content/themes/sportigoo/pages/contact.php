<?php /* Template Name: Contact */

get_header();

while ( have_posts() ) {
    the_post(); ?>

    <div class="content contact">
        <section class="contact__header">
            <div class="container">
                <h1 class="section-title">
                    <?php the_field('titre_'); ?></h1>
            </div>
        </section>
        <section class="contact__content">
            <div class="container">
                <div class="contact__grey">
                    <p class="intro">
                        <?php the_field('texte_intro_avant_le_formulaire'); ?>
                    </p>

                    <?php echo do_shortcode(get_field('formulaire')); ?>

                </div>
            </div>
        </section>
        <section class="contact__sub">
            <div class="container">
                <div class="socialshare">
                    <p>
                        <?php the_field('reseaux_sociaux'); ?>
                    </p>

                    <div class="socialshare__icons">
                        <?php
                        $social_list = array('twitter', 'facebook', 'instagram');
                        zz_print_social_list( $social_list, 48 );
                        ?>
                    </div>
                </div>

                <?php if ( is_active_sidebar( 'post-newsletter' ) ) {
                    dynamic_sidebar( 'post-newsletter' );
                } ?>
            </div>
        </section>
    </div>

<?php } // End of the loop.

get_footer();