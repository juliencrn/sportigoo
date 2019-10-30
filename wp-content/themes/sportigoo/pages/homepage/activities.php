<?php
if ( !function_exists( 'zz_display_product_row' ) ) {
    return;
}
$page_title = "Les suggestions";
$titre_1 = "Ça cartonne en ce moment !";
$titre_2 = "Les sorties tendances";
$titre_3 = "Derniers ajouts";
$pattern = get_stylesheet_directory_uri() . "/dist/img/pattern-activite.png";

if ( have_rows( 'suggestion', 2 ) ) {
    while (have_rows( 'suggestion', 2 )) {
        the_row();

        $page_title = get_sub_field( 'titre_de_la_section' ) ?: $page_title;
        $titre_1 = get_sub_field( 'titre_1' ) ?: $titre_1;
        $titre_2 = get_sub_field( 'titre_2' ) ?: $titre_2;
        $titre_3 = get_sub_field( 'titre_3' ) ?: $titre_3;
    }
}
?>

<section class="activities activities--<?= is_front_page() ? 'homepage': 'trendy'; ?> activities-display">
    <div class="activities__pattern" style="background-image: url('<?= $pattern ?>');"></div>
    <?php if (is_front_page()) { ?>
        <div class="container">
            <h2 class="section-title"><?= $page_title ?></h2>
        </div>
    <?php } ?>

    <div class="activities__block">
        <div class="">
            <h3 class="section-subtitle <?= is_front_page() ? '': 't-white'; ?>"><?= $titre_1 ?></h3>
        </div>
        <?php zz_display_product_row(array(
            'orderby' => array('meta_value_num' => 'DESC'),
            'meta_key' => 'total_sales'
        )); ?>
    </div>

    <div class="activities__block">
        <div class="">
            <h3 class="section-subtitle"><?= $titre_2 ?></h3>
        </div>
        <?php zz_display_product_row(array(
            'orderby' => array('comment_count' => 'DESC')
        )); ?>
    </div>

    <div class="activities__block">
        <div class="">
            <h3 class="section-subtitle"><?= $titre_3 ?></h3>
        </div>
        <?php zz_display_product_row(array(
            'orderby' => array('modified' => 'DESC')
        )); ?>
    </div>

    <style>
      .activities__block > div > h3.section-subtitle { margin-left: 50px; }
    </style>

</section>
