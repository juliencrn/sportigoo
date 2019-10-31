<?php
/**
 * Template name: Search
 */

/*
 $img1 = get_stylesheet_directory_uri() . '/dist/img/abstract_orange.png';
$img2 = get_stylesheet_directory_uri() . '/dist/img/button-deco.png';
$search_title = get_bloginfo( 'name' );
$label_what = "Quoi ?";
$label_who = "Pour qui ?";
$label_where = "Où ?";
$label_submit = "GOO";

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

<section class="search" id="3-clics">
    <div class="container">
        <input type="hidden" id="search_base_url" value="<?php echo get_permalink( (defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV == true) ?485 : 30052 ); // Search custom  ?>">
        <form id="js-search-activity">
            <div class="custom-select">
                <select name="what">
                    <option value="0"><?php echo $label_what ?></option>

                    <?php foreach ($what_terms as $term) { ?>
                        <option value="<?php echo $term->term_id ?>"><?php echo $term->name ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="custom-select">
                <select name="who">
                    <option value="0"><?php echo $label_who ?></option>

                    <?php foreach ($who_terms as $term) { ?>
                        <option value="<?php echo $term->term_id ?>"><?php echo $term->name ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="custom-select" id="where-group" onclick="this.className = 'custom-select'">
                <select name="where">
                    <option value="0"><?php echo $label_where ?></option>

                    <?php foreach ($where_terms as $term) {
                        $dept = get_field('numero', $term); ?>
                        <option data-dept="<?php echo $dept ?>" value="<?php echo  $term->term_id ?>"><?php echo $term->name ?></option>
                    <?php } ?>
                </select>
            </div>

            <input class="button button--white" type="submit" value="<?php echo $label_submit ?>"/>
            <img class="search__deco-button" src="<?php echo $img2 ?>"/>
        </form>
    </div>
</section>
 */

// Read the url
$req = $_SERVER['REQUEST_URI'];
$params = parse_url( $_SERVER['REQUEST_URI'] );

// Redirect to archive if no has params
if (!isset($params['query'])) {
    wp_redirect( get_permalink( 39 ), 301 );
    exit;
}


