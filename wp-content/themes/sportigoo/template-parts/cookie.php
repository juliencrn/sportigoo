<?php
if ( have_rows( 'cookie', 'option' ) ) { ?>
    <div id="cookie" class="d-none">
        <?php while (have_rows( 'cookie', 'option' )) {
            the_row(); ?>
            <input type="hidden" id="cookie_text" value="<?php the_sub_field( 'texte' ); ?>">
            <input type="hidden" id="cookie_more" value="<?php the_sub_field( 'more' ); ?>">
            <input type="hidden" id="cookie_ok" value="<?php the_sub_field( 'ok' ); ?>">
            <input type="hidden" id="cookie_href" value="<?php the_sub_field( 'page_des_cookies' ); ?>">
        <?php } ?>
    </div>

<?php }