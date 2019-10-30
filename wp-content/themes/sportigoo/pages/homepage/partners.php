<?php if ( have_rows( 'partenaires_&_clients' ) ) { ?>
    <div class="logo-partners">
        <div class="container">
            <?php while (have_rows( 'partenaires_&_clients' )) {
                the_row();
                ?>

                <h2 class="section-title"><?php the_sub_field( 'titre_1' ); ?></h2>

                <div class="logo-partners__container">
                    <?php if ( have_rows( 'clients' ) ) {
                        while (have_rows( 'clients' )) {
                            the_row();
                            if ( get_sub_field( 'logo' ) ) { ?>

                                <a href="<?php the_sub_field( 'lien' ); ?>" target="_blank">
                                    <img src="<?php the_sub_field( 'logo' ); ?>"/>
                                </a>
                            <?php }
                        }
                    }
                    ?>
                </div>

                <h2 class="section-title"><?php the_sub_field( 'titre_2' ); ?></h2>

                <div class="logo-partners__container">
                    <?php if ( have_rows( 'partenaires' ) ) {
                        while (have_rows( 'partenaires' )) {
                            the_row();
                            if ( get_sub_field( 'logo' ) ) { ?>

                                <a href="<?php the_sub_field( 'lien' ); ?>" target="_blank">
                                    <img src="<?php the_sub_field( 'logo' ); ?>"/>
                                </a>
                            <?php }
                        }
                    }
                    ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?php }
