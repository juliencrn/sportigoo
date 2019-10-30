<?php
require_once dirname(dirname(__DIR__)) . "/inc/vendors/Mobile_Detect.php";
$detect = new Mobile_Detect;

if ( have_rows( 'presentation' ) ) { ?>
    <section class="text-img">
        <div class="container">
            <?php while (have_rows( 'presentation' )) {
                the_row(); ?>

                <div class="text-img__left">
                    <h2 class="section-subtitle"><?php the_sub_field( 'titre' ); ?></h2>

                    <p><?php the_sub_field( 'presentation' ); ?></p>

                    <?php if ( get_sub_field( 'bouton' ) ) { ?>
                        <a class="button" href="<?php the_sub_field( 'bouton' ); ?>">
                            <?php the_sub_field( 'texte_du_bouton' ); ?>
                        </a>
                    <?php } ?>
                </div>
                <div class="text-img__right">

                    <?php if ( have_rows( 'slider' ) &&!$detect->isTablet() && !$detect->isMobile() ) { ?>
                        <div class="carousel">
                            <?php while (have_rows( 'slider' )) {
                                the_row();

                                if ( get_sub_field( 'slide' ) ) {
                                    $url = get_sub_field( 'slide' )['sizes']['medhome'] ?>
                                    <div class="carousel__item"
                                         style="background-image:url('<?php echo $url; ?>')"></div>
                                <?php }
                            } ?>
                        </div>
                        <div class="carousel__after1"></div>
                        <div class="carousel__after2"></div>
                        <div class="carousel__prev">
                            <svg width="25" height="25">
                                <use xlink:href="#chevron-left"></use>
                            </svg>
                        </div>
                        <div class="carousel__next">
                            <svg width="25" height="25">
                                <use xlink:href="#chevron-right"></use>
                            </svg>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </section>
<?php }





