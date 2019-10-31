<?php
require_once dirname(dirname(__DIR__)) . "/inc/vendors/Mobile_Detect.php";
$detects = new Mobile_Detect;
$i = 0;
if ( have_rows( 'infos_et_sliders' ) ) {
  while ( have_rows( 'infos_et_sliders' ) ) {
     the_row();
     $i++;
     $reverse = $i % 2 ? '' : '-reverse';
$pattern = get_stylesheet_directory_uri() . "/dist/img/pattern-activite.png";
?>


<?php if ( have_rows( 'presentation' ) ) { ?>
    <section class="text-img text-img--repeat">
    <div class="activities__pattern" style="background-image: url('<?php echo $pattern ?>');"></div>
        <div class="container-fluid" style="margin: 0 20px; display:flex; flex-direction: row<?php echo $reverse ?>;">
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
                <div class="text-img__right" style="margin<?php echo $reverse != '' ? '-right' : '-left'; ?>: auto;">


                    <?php if (!$detects->isMobile()) { ?>
                        <?php if (!$detects->isTablet()) { ?>
                            <?php if ( have_rows( 'slider' ) ) { ?>
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
                        <?php } ?>
                    <?php } ?>


                </div>
            <?php } ?>
        </div>
    </section>
<?php }



} ?>
<?php } ?>






