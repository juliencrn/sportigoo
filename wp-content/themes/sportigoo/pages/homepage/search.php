<?php

$img1 = get_stylesheet_directory_uri() . '/dist/img/abstract_orange.png';
$img2 = get_stylesheet_directory_uri() . '/dist/img/button-deco.png';
$search_title = get_bloginfo( 'name' );
$label_what = "Quoi ?";
$label_who = "Pour qui ?";
$label_where = "Où ?";
$label_submit = "GOO";

if ( have_rows( 'rechercher' ) ) {
    while (have_rows( 'rechercher' )) {
        the_row();
        $search_title = get_sub_field( 'titre_formulaire' ) ?: $search_title;
        $label_what = get_sub_field( 'label_quoi' ) ?: $label_what;
        $label_who = get_sub_field( 'label_pour_qui' ) ?: $label_who;
        $label_where = get_sub_field( 'label_ou' ) ?: $label_where;
        $label_submit = get_sub_field( 'label_bouton_envoyer' ) ?: $label_submit;
    }
}

$what_terms = get_terms( array(
    'hide_empty' => false,
    'taxonomy' => 'product_cat',
    'child_of' => 58 // product_cat > type > x
) );
$who_terms = get_terms( array(
    'hide_empty' => false,
    'taxonomy' => 'product_cat',
    'child_of' => 59 // product_cat > qui > x
) );
$where_terms = get_terms( array(
    'hide_empty' => false,
    'taxonomy' => 'lieu'
) );
?>

<section class="search" id="3-clics" style="background-image:url('<?= $img1 ?>');">
    <div class="container">
        <h3 class="section-subtitle">
            <?= $search_title ?>
        </h3>
        <input type="hidden" id="search_base_url" value="<?= get_permalink( (defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV == true) ?485 : 30052 ); // Search custom  ?>">
        <form id="js-search-activity">
            <div class="custom-select">
                <select name="what">
                    <option value="0"><?= $label_what ?></option>

                    <?php foreach ($what_terms as $term) { ?>
                        <option value="<?= $term->term_id ?>"><?= $term->name ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="custom-select">
                <select name="who">
                    <option value="0"><?= $label_who ?></option>

                    <?php foreach ($who_terms as $term) { ?>
                        <option value="<?= $term->term_id ?>"><?= $term->name ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="custom-select" id="where-group" onclick="this.className = 'custom-select'">
                <select name="where">
                    <option value="0"><?= $label_where ?></option>

                    <?php foreach ($where_terms as $term) {
                        $dept = get_field('numero', $term); ?>
                        <option data-dept="<?= $dept ?>" value="<?=  $term->term_id ?>"><?= $term->name ?></option>
                    <?php } ?>
                </select>
            </div>
            <input class="button button--white" type="submit" value="<?= $label_submit ?>"/>
            <img class="search__deco-button" src="<?= $img2 ?>"/>
        </form>
    </div>
</section>
