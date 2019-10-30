<?php
/**
 * The template for displaying search results pages
 *
 * Template name: Search
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package sportigoo
 */



// Read the url
$req = $_SERVER['REQUEST_URI'];
$params = parse_url( $req );
$is_product = false;
if ( isset( $params['query'] ) ) {
    parse_str( $params['query'], $query );
    $is_product = (isset( $query['post_type'] ) && $query['post_type'] == 'product');
} else {
    wp_redirect( get_permalink( 39 ), 301 );
    exit;
}

$pattern_url = get_stylesheet_directory_uri() . '/dist/img/pattern-activite.png';
$sub_title_post = "Tous les articles associés au mot :";
$sub_title_product = "Toutes les activités associés au mot :";
$arrow_next = "<svg width='10' height='10'><use xlink:href='#chevron-left'></use></svg>";
$arrow_prev = "<svg width='10' height='10'><use xlink:href='#chevron-right'></use></svg>";

get_header();

?>
    <style>
        .nav-previous {
            position: absolute;
            right: 0;
            text-align: right;
        }

        .nav-next {
            position: absolute;
            left: 0;
        }

        .nav-links {
            position: relative;
            height: 50px;
        }
    </style>
<?php

if (is_search()) {
    // Loop
    if ( have_posts() ) {
        if ( $is_product ) { ?>

            <div class="content activities-search">
                <section class="activities activities-display">
                    <div class="container activities-search__header">
                        <h1 class="section-title">
                            "<?= ucfirst( get_search_query() ); ?>"
                        </h1>
                        <p class="section-paragraph">
                            <?= $sub_title_product . " \"" . get_search_query() ?>"
                        </p>
                    </div>
                    <div class="activities__pattern" style="background-image: url(<?= $pattern_url ?>);"></div>
                    <div class="activities__block ">
                        <div id="results" class="activities__row hasPrev container" style="position: unset;">
                            <?php while (have_posts()) {
                                the_post();
                                wc_get_template_part( 'content', 'product' );
                            } ?>
                        </div>
                        <?php get_template_part( 'pages/homepage/activities', 'preview' ); ?>

                        <div class="container">
                            <?php the_posts_navigation( array(
                                'prev_text' => "Page suivante  " . $arrow_prev,
                                'next_text' => $arrow_next . "  Page précédente",
                            ) ); ?>
                        </div>
                    </div>
                </section>
            </div>

        <?php } else { ?>

            <div class="content">
                <section class="blog-search">
                    <div class="container activities-search__header">
                        <h1 class="section-title">
                            "<?= ucfirst( get_search_query() ); ?>"
                        </h1>
                        <p class="section-paragraph">
                            <?= $sub_title_post . " \"" . get_search_query() ?>"
                        </p>
                    </div>
                    <div class="blog-search__block">
                        <div class="blog-search__row container">
                            <?php
                            query_posts(array('post_type'=> 'post'));
                            while (have_posts()) {
                                the_post();
                                get_template_part( 'template-parts/content', 'post' );
                            } ?>
                        </div>

                        <div class="container">
                            <?php the_posts_navigation( array(
                                'prev_text' => "Articles plus anciens  " . $arrow_prev,
                                'next_text' => $arrow_next . "  Articles plus récents  ",
                            ) ); ?>
                        </div>

                    </div>
                </section>
            </div>
        <?php } // Is_product
    } else { // Have posts
        get_template_part( 'template-parts/content', 'none' );
    }
} else {

    // Default req arguments
    $args = [
        "post_type" => 'product',
        "posts_per_page" => -1,
        'post_status' => 'publish',
        'orderby' => 'rand',
        'tax_query' => array(
            'relation' => 'AND'
        )
    ];

    // Build custom req
    $product_cat_items = [];
    if ( $query ) {
        foreach ($query as $key => $value) {
            switch ($key) {
                case "who":
                    if ( $value != "0" ) {
                        $product_cat_items[] = $value;
                    }
                    break;
                case "what":
                    if ( $value != "0" ) {
                        $product_cat_items[] = $value;
                    }
                    break;
                case "where":
                    $args['tax_query'][] = array(
                        'taxonomy' => 'lieu',
                        'terms' => array($query['where'])
                    );
                    break;
                default:
                    break;
            }
        }
        if ( $product_cat_items ) {
            foreach ($product_cat_items as $i) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_cat',
                    'terms' => $i
                );
            }
        }
    }

// Data
    $titre = "Resultats";
    $pattern_url = get_stylesheet_directory_uri() . '/dist/img/pattern-activite.png';
    $results = new WP_Query( $args ); ?>



    <div class="content">
        <section class="activities activities-display">
            <div class="container activities-search__header">
                <h1 class="section-title">
                    <?php echo $titre ?>
                </h1>
            </div>
            <div class="activities__pattern" style="background-image: url(<?= $pattern_url ?>);"></div>
            <div class="activities__block container">
                <div id="results" class="activities__row ">
                    <?php if ( $results->have_posts() ) {
                        while ($results->have_posts()) {
                            $results->the_post();
                            wc_get_template_part( 'content', 'product' );
                        }
                    } else {
                        get_template_part( 'template-parts/content', 'none' );
                    } ?>
                </div>
            </div>
        </section>
    </div>

    <pre style="display: none;">
        <code>
            <?php var_dump($args); ?>
        </code>
    </pre>

    <?php
}



get_footer();
