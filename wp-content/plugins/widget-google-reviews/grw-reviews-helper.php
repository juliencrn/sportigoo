<?php
function grw_place($rating, $place, $place_img, $reviews, $dark_theme, $show_powered = true) {
    ?>
    <div class="wp-google-left">
        <img src="<?php echo $place_img; ?>" alt="<?php echo $place->name; ?>">
    </div>
    <div class="wp-google-right">
        <div class="wp-google-name">
            <?php $place_name_content = '<span>' . $place->name . '</span>';
            echo grw_anchor($place->url, '', $place_name_content, true, true); ?>
        </div>
        <div>
            <span class="wp-google-rating"><?php echo $rating; ?></span>
            <span class="wp-google-stars"><?php grw_stars($rating); ?></span>
        </div>
        <?php if ($show_powered) { ?>
        <div class="wp-google-powered">
            <img src="<?php echo GRW_PLUGIN_URL; ?>/static/img/powered_by_google_on_<?php if ($dark_theme) { ?>non_<?php } ?>white.png" alt="powered by Google">
        </div>
        <?php } ?>
    </div>
    <?php
}

function grw_place_reviews($place, $reviews, $place_id, $text_size, $pagination, $reduce_avatars_size, $open_link, $nofollow_link, $lazy_load_img, $def_reviews_link) {
    ?>
    <div class="wp-google-reviews">
    <?php
    $hr = false;
    if (count($reviews) > 0) {
        $i = 0;
        foreach ($reviews as $review) {
            if ($pagination > 0 && $pagination <= $i++) {
                $hr = true;
            }
        ?>
        <div class="wp-google-review<?php if ($hr) { ?> wp-google-hide<?php } ?>">
            <div class="wp-google-left">
                <?php
                if (strlen($review->profile_photo_url) > 0) {
                    $profile_photo_url = $review->profile_photo_url;
                } else {
                    $profile_photo_url = GRW_GOOGLE_AVATAR;
                }
                if ($reduce_avatars_size) {
                    $profile_photo_url = str_replace('s128', 's50', $profile_photo_url);
                }
                grw_image($profile_photo_url, $review->author_name, $lazy_load_img, GRW_GOOGLE_AVATAR);
                ?>
            </div>
            <div class="wp-google-right">
                <?php
                if (strlen($review->author_url) > 0) {
                    grw_anchor($review->author_url, 'wp-google-name', $review->author_name, $open_link, $nofollow_link);
                } else {
                    if (strlen($review->author_name) > 0) {
                        $author_name = $review->author_name;
                    } else {
                        $author_name = grw_i('Google User');
                    }
                    ?><div class="wp-google-name"><?php echo $author_name; ?></div><?php
                }
                ?>
                <div class="wp-google-time" data-time="<?php echo $review->time; ?>"><?php echo gmdate("H:i d M y", $review->time); ?></div>
                <div class="wp-google-feedback">
                    <span class="wp-google-stars"><?php echo grw_stars($review->rating); ?></span>
                    <span class="wp-google-text"><?php echo grw_trim_text($review->text, $text_size); ?></span>
                </div>
            </div>
        </div>
        <?php
        }
    }
    ?>
    </div>
    <?php if ($pagination > 0 && $hr) { ?>
    <a class="wp-google-url" href="#" onclick="return rplg_next_reviews.call(this, 'google', <?php echo $pagination; ?>);">
        <?php echo grw_i('Next Reviews'); ?>
    </a>
    <?php } else {
    $reviews_link = $def_reviews_link ? $place->url : 'https://search.google.com/local/reviews?placeid=' . $place_id;
    grw_anchor($reviews_link, 'wp-google-url', grw_i('See All Reviews'), true, true);
    }
}

function grw_stars($rating) {
    ?><span class="wp-stars"><?php
    foreach (array(1,2,3,4,5) as $val) {
        $score = $rating - $val;
        if ($score >= 0) {
            ?><span class="wp-star"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="17" height="17" viewBox="0 0 1792 1792"><path d="M1728 647q0 22-26 48l-363 354 86 500q1 7 1 20 0 21-10.5 35.5t-30.5 14.5q-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z" fill="#e7711b"></path></svg></span><?php
        } else if ($score > -1 && $score < 0) {
            ?><span class="wp-star"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="17" height="17" viewBox="0 0 1792 1792"><path d="M1250 957l257-250-356-52-66-10-30-60-159-322v963l59 31 318 168-60-355-12-66zm452-262l-363 354 86 500q5 33-6 51.5t-34 18.5q-17 0-40-12l-449-236-449 236q-23 12-40 12-23 0-34-18.5t-6-51.5l86-500-364-354q-32-32-23-59.5t54-34.5l502-73 225-455q20-41 49-41 28 0 49 41l225 455 502 73q45 7 54 34.5t-24 59.5z" fill="#e7711b"></path></svg></span><?php
        } else {
            ?><span class="wp-star"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="17" height="17" viewBox="0 0 1792 1792"><path d="M1201 1004l306-297-422-62-189-382-189 382-422 62 306 297-73 421 378-199 377 199zm527-357q0 22-26 48l-363 354 86 500q1 7 1 20 0 50-41 50-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z" fill="#ccc"></path></svg></span><?php
        }
    }
    ?></span><?php
}

function grw_rstrpos($haystack, $needle, $offset) {
    $size = strlen ($haystack);
    $pos = strpos (strrev($haystack), $needle, $size - $offset);

    if ($pos === false)
        return false;

    return $size - $pos;
}

function grw_trim_text($text, $size) {
    if ($size > 0 && strlen($text) > $size) {
        $visible_text = $text;
        $invisible_text = '';
        $idx = grw_rstrpos($text, ' ', $size);
        if ($idx < 1) {
            $idx = $size;
        }
        if ($idx > 0) {
            $visible_text = substr($text, 0, $idx);
            $invisible_text = substr($text, $idx, strlen($text));
        }
        echo $visible_text;
        if (strlen($invisible_text) > 0) {
            ?><span class="wp-more"><?php echo $invisible_text; ?></span><span class="wp-more-toggle" onclick="this.previousSibling.className='';this.textContent='';"><?php echo grw_i('read more'); ?></span><?php
        }
    } else {
        echo $text;
    }
}

function grw_anchor($url, $class, $text, $open_link, $nofollow_link) {
    ?><a href="<?php echo $url; ?>" class="<?php echo $class; ?>" <?php if ($open_link) { ?>target="_blank"<?php } ?> <?php if ($nofollow_link) { ?>rel="nofollow"<?php } ?>><?php echo $text; ?></a><?php
}

function grw_image($src, $alt, $lazy, $def_ava = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7', $atts = '') {
    ?><img <?php if ($lazy) { ?>src="<?php echo $def_ava; ?>" data-<?php } ?>src="<?php echo $src; ?>" class="rplg-review-avatar<?php if ($lazy) { ?> rplg-blazy<?php } ?>" alt="<?php echo $alt; ?>" onerror="if(this.src!='<?php echo $def_ava; ?>')this.src='<?php echo $def_ava; ?>';" <?php echo $atts; ?>><?php
}
?>