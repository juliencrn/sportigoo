<?php

$page_title = get_bloginfo( 'name' );
$page_slogan = get_bloginfo( 'description' );
$header_bg = "";

if ( have_rows( 'header' ) ) {
    while (have_rows( 'header' )) {
        the_row();

        $page_title = get_sub_field( 'titre' ) ?: $page_title;
        $page_slogan = get_sub_field( 'sous_titre' ) ?: $page_slogan;
        $header_bg = get_sub_field( 'image_de_fond' );
    }
}
?>

<section class="homepage__cover">
    <div class="cover cover--full" style="background-image:url('<?= $header_bg ?>')">
        <div class="cover__filter"></div>
        <div class="container">
            <h1><?= $page_title ?></h1>
            <h2><?= $page_slogan ?></h2>
        </div>
        <div class="go-down">
            <a href="#3-clics">
                <svg width="25" height="25">
                    <use xlink:href="#chevron-down"></use>
                </svg>
            </a>
        </div>
    </div>
</section>
