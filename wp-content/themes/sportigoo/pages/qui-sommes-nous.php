<?php /* Template Name: Qui sommes nous */

get_header();

while (have_posts()) {
    the_post(); ?>

    <div class="content quisommesnous">
        <section>
            <?php if ( !empty( get_field( 'video_intro' ) ) ) {
                $iframe = get_field( 'video_intro' );
                preg_match( '/src="(.+?)"/', $iframe, $matches );
                $iframe_src = $matches[1];
                ?>
                <div class="activity-preview__video">
                    <div class="activity-preview__video-container">
                        <div class="activity-preview__close-video">
                            <svg width="25" height="25">
                                <use xlink:href="#cross"></use>
                            </svg>
                        </div>
                        <iframe
                                width="560"
                                height="315"
                                src="<?= $iframe_src ?>"
                                frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen="allowfullscreen"
                        ></iframe>
                    </div>
                </div>
            <?php } ?>


            <div class="quisommesnous__content blog_style">
                <div class="container">
                    <div class="row">
                        <div class="six cols">
                            <h1 class="h2"><?php the_field( 'titre_principal' ) ?></h1>
                            <p><?php the_field( 'texte_intro' ) ?></p>
                        </div>
                        <div class="six cols">
                            <div class="border_orange">
                                <div class="border_orange__item"
                                     style="background-image:url(<?php the_field( 'photo_illustration' ) ?>);"></div>
                                <div class="border_orange__after1"></div>
                                <div class="border_orange__after2"></div>
                                <div class="border_orange__after3"></div>
                                <?php if ( !empty( get_field( 'video_intro' ) ) ) { ?>
                                    <div class="border_orange__center activity-preview__video-button">
                                        <svg width="35" height="35">
                                            <use xlink:href="#music-player-play"></use>
                                        </svg>
                                        <p>PLAY</p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="container">
                    <div class="row">
                        <div class="twelve cols">
                            <h2 class="h2 t-center">
                                <?php the_field( 'titre_2' ); ?>
                            </h2>
                        </div>
                    </div>
                </div>

                <?php if ( have_rows( 'texte_principal' ) ) { ?>

                    <?php while (have_rows( 'texte_principal' )) {
                        the_row(); ?>

                        <div class="container">
                            <div class="row">

                                <?php switch (get_sub_field( 'layout' )) {
                                    case "full-text":
                                        ?>

                                        <div class="twelve cols">
                                            <p><?php the_sub_field( 'texte' ); ?></p>
                                        </div>

                                        <?php break;
                                    case "img-right":
                                        ?>

                                        <div class="four cols first-child">
                                            <p><?php the_sub_field( 'texte' ); ?></p>
                                        </div>
                                        <div class="eight cols">
                                            <img src="<?php the_sub_field( 'image' ); ?>"/>
                                        </div>

                                        <?php break;
                                    case "img-left":
                                        ?>

                                        <div class="eight cols">
                                            <img src="<?php the_sub_field( 'image' ); ?>"/>
                                        </div>
                                        <div class="four cols">
                                            <p><?php the_sub_field( 'texte' ); ?></p>
                                        </div>

                                        <?php break;
                                }
                                ?>
                            </div>
                        </div>

                    <?php } ?>

                <?php } ?>
                <?php ?>
            </div>

            <?php if ( have_rows( 'faq' ) ) { ?>
                <div class="quisommesnous__faq">
                    <div class="container">
                        <div class="row">
                            <div class="twelve cols">
                                <div class="faq">
                                    <?php while (have_rows( 'faq' )) {
                                        the_row(); ?>
                                        <div class="faq__item">
                                            <div class="faq__item-visible">
                                                <p><?php the_sub_field( 'question' ); ?></p>
                                                <div class="faq__item-btn">
                                                    <svg width="15" height="15">
                                                        <use xlink:href="#chevron-down"></use>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="faq__item-hidden">
                                                <p><?php the_sub_field( 'reponse' ); ?></p>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </section>

        <?php
        $titre_1 = "Ça cartonne en ce moment !";
        $pattern = get_stylesheet_directory_uri() . "/dist/img/pattern-activite.png";
        if ( have_rows( 'suggestion', 2 ) ) {
            while (have_rows( 'suggestion', 2 )) {
                the_row();
                $titre_1 = get_sub_field( 'titre_1' ) ?: $titre_1;
            }
        }

        if ( function_exists( 'zz_display_product_row' ) ) { ?>
            <section class="activities activities--homepage activities-display">
                <div class="activities__pattern" style="background-image: url('<?= $pattern ?>');"></div>
                <div class="activities__block">
                    <div class="container">
                        <h3 class="section-subtitle"><?= $titre_1 ?></h3>
                    </div>
                    <?php zz_display_product_row( array(
                        'orderby' => array('meta_value_num' => 'DESC'),
                        'meta_key' => 'total_sales'
                    ) ); ?>
                </div>
            </section>
        <?php } ?>

    </div>

<?php } // End of the loop.

get_footer();