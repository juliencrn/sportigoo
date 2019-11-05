<?php global $wp_version; if (version_compare($wp_version, '3.5', '>=')) { wp_enqueue_media(); ?>
<div class="form-group">
    <img id="<?php echo $this->get_field_id('place_photo_img'); ?>" src="<?php echo $place_photo; ?>" alt="<?php echo $place_name; ?>" class="grw-place-photo-img" style="display:<?php if ($place_photo) { ?>inline-block<?php } else { ?>none<?php } ?>;width:32px;height:32px;border-radius:50%;">
    <a id="<?php echo $this->get_field_id('place_photo_btn'); ?>" href="#" class="grw-place-photo-btn"><?php echo grw_i('Change Place photo'); ?></a>
    <input type="hidden" id="<?php echo $this->get_field_id('place_photo'); ?>" name="<?php echo $this->get_field_name('place_photo'); ?>" value="<?php echo $place_photo; ?>" class="grw-place-photo" tabindex="2"/>
</div>
<?php } ?>

<div class="form-group">
    <input type="text" id="<?php echo $this->get_field_id('place_name'); ?>" name="<?php echo $this->get_field_name('place_name'); ?>" value="<?php echo $place_name; ?>" class="grw-google-place-name" placeholder="<?php echo grw_i('Google Place Name'); ?>" readonly />
</div>

<div class="form-group">
    <input type="text" id="<?php echo $this->get_field_id('place_id'); ?>" name="<?php echo $this->get_field_name('place_id'); ?>" value="<?php echo $place_id; ?>" class="grw-google-place-id" placeholder="<?php echo grw_i('Google Place ID'); ?>" readonly />
</div>

<div class="form-group">
    <input type="text" id="<?php echo $this->get_field_id('reviews_lang'); ?>" name="<?php echo $this->get_field_name('reviews_lang'); ?>" value="<?php echo $reviews_lang; ?>" class="grw-place-lang" placeholder="<?php echo grw_i('Language'); ?>" readonly />
</div>

<?php if (isset($title)) { ?>
<div class="form-group">
    <label><?php echo grw_i('Title'); ?></label>
    <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>"/>
</div>
<?php } ?>

<div class="form-group">
    <label><?php echo grw_i('Pagination'); ?></label>
    <input type="text" id="<?php echo $this->get_field_id('pagination'); ?>" name="<?php echo $this->get_field_name('pagination'); ?>" value="<?php echo $pagination; ?>"/>
</div>

<div class="form-group">
    <label><?php echo grw_i('Characters before \'read more\' link'); ?></label>
    <input type="text" id="<?php echo $this->get_field_id('text_size'); ?>" name="<?php echo $this->get_field_name('text_size'); ?>" value="<?php echo $text_size; ?>"/>
</div>

<div class="form-group">
    <label for="<?php echo $this->get_field_id('max_width'); ?>"><?php echo grw_i('Widget width'); ?></label>
    <input id="<?php echo $this->get_field_id('max_width'); ?>" name="<?php echo $this->get_field_name('max_width'); ?>" value="<?php echo $max_width; ?>" type="text" />
</div>

<div class="form-group">
    <label for="<?php echo $this->get_field_id('max_height'); ?>"><?php echo grw_i('Widget height'); ?></label>
    <input id="<?php echo $this->get_field_id('max_height'); ?>" name="<?php echo $this->get_field_name('max_height'); ?>" value="<?php echo $max_height; ?>" type="text" />
</div>

<div class="form-group">
    <label>
        <input id="<?php echo $this->get_field_id('refresh_reviews'); ?>" name="<?php echo $this->get_field_name('refresh_reviews'); ?>" type="checkbox" value="1" <?php checked('1', $refresh_reviews); ?>/>
        <?php echo grw_i('Refresh reviews'); ?>
        <span class="rplg-quest rplg-toggle" title="Click to help">?</span>
        <div style="display:none">
            The plugin uses the Google Places API to get your reviews. <b>The API only returns the 5 most helpful reviews (it's a limitation of Google, not the plugin)</b>. This option calls the Places API once in three days (to avoid a Google Billing) to check for a new reviews and if there are, adds to the plugin.<br><br>Also if you see the new reviews on Google map, but after some time it's not added to the plugin, it means that Google does not include these reviews to the API and the plugin can't get this.<br><br>If you need to get all reviews, please use <a href="https://richplugins.com/business-reviews-bundle-wordpress-plugin" target="_blank">the Business plugin</a> which uses a Google My Business API, but this GMB API is available only for verified Google business owner accounts.
        </div>
    </label>
</div>

<div class="form-group">
    <label>
        <input id="<?php echo $this->get_field_id('centered'); ?>" name="<?php echo $this->get_field_name('centered'); ?>" type="checkbox" value="1" <?php checked('1', $centered); ?>/>
        <?php echo grw_i('Place by center (only if Width is set)'); ?>
    </label>
</div>

<div class="form-group">
    <label>
        <input id="<?php echo $this->get_field_id('dark_theme'); ?>" name="<?php echo $this->get_field_name('dark_theme'); ?>" type="checkbox" value="1" <?php checked('1', $dark_theme); ?>/>
        <?php echo grw_i('Dark background'); ?>
    </label>
</div>

<div class="rplg-options-toggle rplg-toggle"><?php echo grw_i('Advance Options'); ?></div>
<div class="rplg-options" style="display:none">
    <div class="form-group">
        <label>
            <input id="<?php echo $this->get_field_id('lazy_load_img'); ?>" name="<?php echo $this->get_field_name('lazy_load_img'); ?>" type="checkbox" value="1" <?php checked('1', $lazy_load_img); ?>/>
            <?php echo grw_i('Lazy load images'); ?>
        </label>
    </div>

    <div class="form-group">
        <label>
            <input id="<?php echo $this->get_field_id('reduce_avatars_size'); ?>" name="<?php echo $this->get_field_name('reduce_avatars_size'); ?>" type="checkbox" value="1" <?php checked('1', $reduce_avatars_size); ?>/>
            <?php echo grw_i('Reduce avatars size'); ?>
            <span class="rplg-quest rplg-toggle" title="Click to help">?</span>
            <div style="display:none">
                By default the Google Places API returns the reviewer's avatars of 128px in size, this option reduces the size to 50px.
            </div>
        </label>
    </div>

    <div class="form-group">
        <label>
            <input id="<?php echo $this->get_field_id('def_reviews_link'); ?>" name="<?php echo $this->get_field_name('def_reviews_link'); ?>" type="checkbox" value="1" <?php checked('1', $def_reviews_link); ?>/>
            <?php echo grw_i('Use default reviews link'); ?>
            <span class="rplg-quest rplg-toggle" title="Click to help">?</span>
            <div style="display:none">
                If the direct link to all reviews <b>https://search.google.com/local/reviews?placeid=&lt;PLACE_ID&gt;</b> does not work with your Google place, please use this option to use the default reviews link to Google map.
            </div>
        </label>
    </div>

    <div class="form-group">
        <label>
            <input id="<?php echo $this->get_field_id('open_link'); ?>" name="<?php echo $this->get_field_name('open_link'); ?>" type="checkbox" value="1" <?php checked('1', $open_link); ?>/>
            <?php echo grw_i('Open links in new Window'); ?>
        </label>
    </div>

    <div class="form-group">
        <label>
            <input id="<?php echo $this->get_field_id('nofollow_link'); ?>" name="<?php echo $this->get_field_name('nofollow_link'); ?>" type="checkbox" value="1" <?php checked('1', $nofollow_link); ?>/>
            <?php echo grw_i('Use no follow links'); ?>
        </label>
    </div>
</div>

<div class="form-group">
    <div class="rplg-pro">
        <?php echo grw_i('Try more features in the Business version: '); ?>
        <a href="https://richplugins.com/business-reviews-bundle-wordpress-plugin" target="_blank">
            <?php echo grw_i('Upgrade to Business'); ?>
        </a>
    </div>
</div>

<input id="<?php echo $this->get_field_id('view_mode'); ?>" name="<?php echo $this->get_field_name('view_mode'); ?>" type="hidden" value="list" />
