<?php
wp_register_script('rplg_js', plugins_url('/static/js/rplg.js', __FILE__));
wp_enqueue_script('rplg_js', plugins_url('/static/js/rplg.js', __FILE__));

if ($lazy_load_img) {
    wp_register_script('rplg_blazy', plugins_url('/static/js/blazy.min.js', __FILE__));
    wp_enqueue_script('rplg_blazy', plugins_url('/static/js/blazy.min.js', __FILE__));
}

include_once(dirname(__FILE__) . '/grw-reviews-helper.php');

$reviews_where = '';
if (strlen($reviews_lang) > 0) {
    $reviews_where = $reviews_where . ' AND language = \'' . $reviews_lang . '\'';
}

$place = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "grp_google_place WHERE place_id = %s", $place_id));
$reviews = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "grp_google_review WHERE google_place_id = %d" . $reviews_where . " ORDER BY time DESC", $place->id));

$rating = 0;
if ($place->rating > 0) {
    $rating = $place->rating;
} else if (count($reviews) > 0) {
    foreach ($reviews as $review) {
        $rating = $rating + $review->rating;
    }
    $rating = round($rating / count($reviews), 1);
}
$rating = number_format((float)$rating, 1, '.', '');
$place_img = strlen($place_photo) > 0 ? $place_photo : (strlen($place->photo) > 0 ? $place->photo : $place->icon);

if (is_numeric($max_width)) {
    $max_width = $max_width . 'px';
}
if (is_numeric($max_height)) {
    $max_height = $max_height . 'px';
}

if ($refresh_reviews) {
    $schedule_step = 60 * 60 * 55;
    $args = array($place_id);
    if (strlen($reviews_lang) > 0) {
        array_push($args, $reviews_lang);
    }
    $schedule_cache_key = 'grw_refresh_reviews_' . join('_', $args);
    if (get_transient($schedule_cache_key) === false) {
        wp_schedule_single_event(time() + $schedule_step, 'grw_refresh_reviews', array($args));
        set_transient($schedule_cache_key, $schedule_cache_key, $schedule_step + 60 * 10);
    }
}

if ($view_mode != 'list') { ?>

<div class="wp-gr wpac">
    <script type="text/javascript">
    function grw_badge_init(el) {
        var btn = el.querySelector('.wp-google-badge'),
            form = el.querySelector('.wp-google-form');

        var wpac = document.createElement('div');
        wpac.className = 'wp-gr wpac';
        wpac.appendChild(form);
        document.body.appendChild(wpac);

        btn.onclick = function() {
            form.style.display='block';
        };
    }
    </script>
    <div class="wp-google-badge<?php if ($view_mode == 'badge') { ?> wp-google-badge-fixed<?php } ?>">
        <div class="wp-google-border"></div>
        <div class="wp-google-badge-btn">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" height="44" width="44"><g fill="none" fill-rule="evenodd"><path d="M482.56 261.36c0-16.73-1.5-32.83-4.29-48.27H256v91.29h127.01c-5.47 29.5-22.1 54.49-47.09 71.23v59.21h76.27c44.63-41.09 70.37-101.59 70.37-173.46z" fill="#4285f4"/><path d="M256 492c63.72 0 117.14-21.13 156.19-57.18l-76.27-59.21c-21.13 14.16-48.17 22.53-79.92 22.53-61.47 0-113.49-41.51-132.05-97.3H45.1v61.15c38.83 77.13 118.64 130.01 210.9 130.01z" fill="#34a853"/><path d="M123.95 300.84c-4.72-14.16-7.4-29.29-7.4-44.84s2.68-30.68 7.4-44.84V150.01H45.1C29.12 181.87 20 217.92 20 256c0 38.08 9.12 74.13 25.1 105.99l78.85-61.15z" fill="#fbbc05"/><path d="M256 113.86c34.65 0 65.76 11.91 90.22 35.29l67.69-67.69C373.03 43.39 319.61 20 256 20c-92.25 0-172.07 52.89-210.9 130.01l78.85 61.15c18.56-55.78 70.59-97.3 132.05-97.3z" fill="#ea4335"/><path d="M20 20h472v472H20V20z"/></g></svg>
            <div class="wp-google-badge-score">
                <div><?php echo grw_i('Google Rating'); ?></div>
                <span class="wp-google-rating"><?php echo $rating; ?></span>
                <span class="wp-google-stars"><?php grw_stars($rating); ?></span>
            </div>
        </div>
    </div>
    <div class="wp-google-form" style="display:none">
        <div class="wp-google-head">
            <div class="wp-google-head-inner">
                <?php grw_place($rating, $place, $place_img, $reviews, $dark_theme, false); ?>
            </div>
            <button class="wp-google-close" type="button" onclick="this.parentNode.parentNode.style.display='none'">×</button>
        </div>
        <div class="wp-google-body"></div>
        <div class="wp-google-content">
            <div class="wp-google-content-inner">
                <?php grw_place_reviews($place, $reviews, $place_id, $text_size, $pagination, $reduce_avatars_size, $open_link, $nofollow_link, $lazy_load_img, $def_reviews_link); ?>
            </div>
        </div>
        <div class="wp-google-footer">
            <img src="<?php echo GRW_PLUGIN_URL; ?>/static/img/powered_by_google_on_<?php if ($dark_theme) { ?>non_<?php } ?>white.png" alt="powered by Google">
        </div>
    </div>
    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="" onload="(function(el) { document.addEventListener('DOMContentLoaded', function() { grw_badge_init(el); }); })(this.parentNode);" style="display:none">
</div>

<?php } else { ?>

<div class="wp-gr wpac" style="<?php if (isset($max_width) && strlen($max_width) > 0) { ?>width:<?php echo $max_width;?>!important;<?php } ?><?php if (isset($max_height) && strlen($max_height) > 0) { ?>height:<?php echo $max_height;?>!important;overflow-y:auto!important;<?php } ?><?php if ($centered) { ?>margin:0 auto!important;<?php } ?>">
    <div class="wp-google-list<?php if ($dark_theme) { ?> wp-dark<?php } ?>">
        <div class="wp-google-place">
            <?php grw_place($rating, $place, $place_img, $reviews, $dark_theme); ?>
        </div>
        <div class="wp-google-content-inner">
            <?php grw_place_reviews($place, $reviews, $place_id, $text_size, $pagination, $reduce_avatars_size, $open_link, $nofollow_link, $lazy_load_img, $def_reviews_link); ?>
        </div>
    </div>
</div>
<?php } ?>