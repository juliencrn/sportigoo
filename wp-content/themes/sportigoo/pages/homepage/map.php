<?php if ( have_rows( 'map', 2 ) ) { ?>
    <section class="homepage__map">
        <input type="hidden" id="permalink_activity" value="<?php echo get_permalink(39); ?>">
        <img class="logo-grey" src="<?php echo get_stylesheet_directory_uri(); ?>/dist/img/logo-gris.png"/>
        <div class="container">

            <?php while (have_rows( 'map', 2 )) {
                the_row(); ?>

                <h2 class="section-title">
                    <?php the_sub_field( 'titre' ) ?>
                </h2>

                <?php if ( have_rows( 'etapes' ) ) {
                    $i = 0; ?>
                    <div class="how-to-use">
                        <?php while (have_rows( 'etapes' )) {
                            the_row();
                            $i++; ?>
                            <div class="how-to-use__step">
                                <div class="how-to-use__number">
                                    <?php echo $i ?>
                                </div>
                                <p><?php the_sub_field( "etape" ); ?></p>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>


            <?php } ?>
            <div style="max-width: 500px" class="mapsvg" id="mapsvg"
                 data-url="<?php echo get_stylesheet_directory_uri(); ?>/static/map/france.svg"></div>
        </div>
    </section>

<?php }

