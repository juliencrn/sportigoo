<?php

if (!current_user_can('manage_options')) {
    die('The account you\'re logged in to doesn\'t have permission to access this page.');
}

function grw_has_valid_nonce() {
    $nonce_actions = array('grw_reset', 'grw_settings', 'grw_active', 'grw_advance');
    $nonce_form_prefix = 'grw-form_nonce_';
    $nonce_action_prefix = 'grw-wpnonce_';
    foreach ($nonce_actions as $key => $value) {
        if (isset($_POST[$nonce_form_prefix.$value])) {
            check_admin_referer($nonce_action_prefix.$value, $nonce_form_prefix.$value);
            return true;
        }
    }
    return false;
}

function grw_debug() {
    global $wpdb;
    $places = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "grp_google_place");
    $places_error = $wpdb->last_error;
    $reviews = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "grp_google_review");
    $reviews_error = $wpdb->last_error; ?>

DB Places: <?php echo print_r($places); ?>

DB Places error: <?php echo $places_error; ?>

DB Reviews: <?php echo print_r($reviews); ?>

DB Reviews error: <?php echo $reviews_error;
}

if (!empty($_POST)) {
    $nonce_result_check = grw_has_valid_nonce();
    if ($nonce_result_check === false) {
        die('Unable to save changes. Make sure you are accessing this page from the Wordpress dashboard.');
    }
}

// Reset
if (isset($_POST['reset_all'])) {
    grw_reset(isset($_POST['reset_db']));
    unset($_POST);
?>
<div class="wrap">
    <h3><?php echo grw_i('Google Reviews Widget Reset'); ?></h3>
    <form method="POST" action="?page=grw">
        <?php wp_nonce_field('grw-wpnonce_grw_reset', 'grw-form_nonce_grw_reset'); ?>
        <p><?php echo grw_i('Google Reviews Widget has been reset successfully.') ?></p>
        <ul style="list-style: circle;padding-left:20px;">
            <li><?php echo grw_i('Local settings for the plugin were removed.') ?></li>
        </ul>
        <p>
            <?php echo grw_i('If you wish to reinstall, you can do that now.') ?>
            <a href="?page=grw">&nbsp;<?php echo grw_i('Reinstall') ?></a>
        </p>
    </form>
</div>
<?php
die();
}

// Post fields that require verification.
$valid_fields = array(
    'grw_active' => array(
        'key_name' => 'grw_active',
        'values' => array('Disable', 'Enable')
    ));

// Check POST fields and remove bad input.
foreach ($valid_fields as $key) {

    if (isset($_POST[$key['key_name']]) ) {

        // SANITIZE first
        $_POST[$key['key_name']] = trim(sanitize_text_field($_POST[$key['key_name']]));

        // Validate
        if (isset($key['regexp']) && $key['regexp']) {
            if (!preg_match($key['regexp'], $_POST[$key['key_name']])) {
                unset($_POST[$key['key_name']]);
            }

        } else if (isset($key['type']) && $key['type'] == 'int') {
            if (!intval($_POST[$key['key_name']])) {
                unset($_POST[$key['key_name']]);
            }

        } else {
            $valid = false;
            $vals = $key['values'];
            foreach ($vals as $val) {
                if ($_POST[$key['key_name']] == $val) {
                    $valid = true;
                }
            }
            if (!$valid) {
                unset($_POST[$key['key_name']]);
            }
        }
    }
}

if (isset($_POST['grw_active']) && isset($_GET['grw_active'])) {
    update_option('grw_active', ($_GET['grw_active'] == '1' ? '1' : '0'));
}

if (isset($_POST['grw_setting'])) {
    update_option('grw_google_api_key', trim($_POST['grw_google_api_key']));

    /*$grw_google_api_key = $_POST['grw_google_api_key'];
    if (strlen($grw_google_api_key) > 0) {
        $test_url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJ3TH9CwFZwokRIvNO1SP0WLg&key=" . $grw_google_api_key;
        $test_response = rplg_urlopen($test_url);
        $test_response_data = $test_response['data'];
        $test_response_json = rplg_json_decode($test_response_data);
        if (isset($test_response_json->error_message) && strlen($test_response_json->error_message) > 0) {
            $grw_google_api_key_error = $test_response_json->error_message;
        }
        update_option('grw_google_api_key', $grw_google_api_key);
    }*/
}

if (isset($_POST['create_db'])) {
    grw_install_db();
}

if (isset($_POST['install'])) {
    grw_reset(true);
    grw_install();
}

wp_register_style('rplg_setting_css', plugins_url('/static/css/rplg-setting.css', __FILE__));
wp_enqueue_style('rplg_setting_css', plugins_url('/static/css/rplg-setting.css', __FILE__));

wp_enqueue_script('jquery');

$tab                = isset($_GET['grw_tab']) && strlen($_GET['grw_tab']) > 0 ? $_GET['grw_tab'] : 'about';
$grw_enabled        = get_option('grw_active') == '1';
$grw_google_api_key = get_option('grw_google_api_key');
?>

<span class="rplg-version"><?php echo grw_i('Free Version: %s', esc_html(GRW_VERSION)); ?></span>

<div class="rplg-setting">

    <div class="rplg-page-title">
        <img src="<?php echo GRW_PLUGIN_URL . '/static/img/google.png'; ?>" alt="Google"> Reviews Widget
    </div>

    <div class="rplg-settings-workspace">

        <div data-nav-tabs="">
            <div class="nav-tab-wrapper">
                <a href="#about"     class="nav-tab<?php if ($tab == 'about')     { ?> nav-tab-active<?php } ?>"><?php echo grw_i('About'); ?></a>
                <a href="#setting"   class="nav-tab<?php if ($tab == 'setting')   { ?> nav-tab-active<?php } ?>"><?php echo grw_i('Settings'); ?></a>
                <a href="#shortcode" class="nav-tab<?php if ($tab == 'shortcode') { ?> nav-tab-active<?php } ?>"><?php echo grw_i('Shortcode'); ?></a>
                <a href="#support"   class="nav-tab<?php if ($tab == 'support')   { ?> nav-tab-active<?php } ?>"><?php echo grw_i('Support'); ?></a>
                <a href="#advance"   class="nav-tab<?php if ($tab == 'advance')   { ?> nav-tab-active<?php } ?>"><?php echo grw_i('Advance'); ?></a>
            </div>

            <div id="about" class="tab-content" style="display:<?php echo $tab == 'about' ? 'block' : 'none'?>;">
                <h3>Google Reviews Widget for WordPress</h3>
                <div class="rplg-flex-row">
                    <div class="rplg-flex-col">
                        <span>Google Reviews plugin is an easy and fast way to integrate Google business reviews right into your WordPress website. This plugin works instantly and keep all Google places and reviews in WordPress database thus it has no depend on external services.</span>
                        <p>Please see Introduction Video to understand how it works. Also you can find most common answers and solutions for most common questions and issues in next tabs.</p>
                        <div class="rplg-alert rplg-alert-success">
                            <strong>Try more features in the Business version</strong>: Merge Google, Facebook and Yelp reviews, Beautiful themes (Slider, Grid, Trust Badges), Shortcode support, Rich Snippets, Rating filter, Any sorting, Include/Exclude words filter, Hide/Show any elements, Priority support and many others.
                            <a class="button-primary button" href="https://richplugins.com/business-reviews-bundle-wordpress-plugin" target="_blank" style="margin-left:10px">Upgrade to Business</a>
                        </div>
                        <br>
                        <div class="rplg-socials">
                            <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                              var js, fjs = d.getElementsByTagName(s)[0];
                              if (d.getElementById(id)) return;
                              js = d.createElement(s); js.id = id;
                              js.src = "//connect.facebook.net/en_EN/sdk.js#xfbml=1&version=v2.6&appId=1501100486852897";
                              fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));</script>
                            <div class="fb-like" data-href="https://richplugins.com/" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
                            <a href="https://twitter.com/richplugins?ref_src=twsrc%5Etfw" class="twitter-follow-button" data-show-count="false">Follow @richplugins</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                            <div class="g-plusone" data-size="medium" data-annotation="inline" data-width="200" data-href="https://plus.google.com/101080686931597182099"></div>
                            <script type="text/javascript">
                                window.___gcfg = { lang: 'en-US' };
                                (function () {
                                    var po = document.createElement('script');
                                    po.type = 'text/javascript';
                                    po.async = true;
                                    po.src = 'https://apis.google.com/js/plusone.js';
                                    var s = document.getElementsByTagName('script')[0];
                                    s.parentNode.insertBefore(po, s);
                                })();
                            </script>
                        </div>
                    </div>
                    <div class="rplg-flex-col">
                        <iframe width="100%" height="315" src="https://www.youtube.com/embed/lmaTBmvDPKk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            </div>

            <div id="setting" class="tab-content" style="display:<?php echo $tab == 'setting' ? 'block' : 'none'?>;">
                <h3>General Settings</h3>
                <form method="post" action="?page=grw&amp;grw_tab=setting&amp;grw_active=<?php echo (string)((int)($grw_enabled != true)); ?>">
                    <div class="rplg-field">
                        <div class="rplg-field-label">
                            <label>The plugin is currently <b><?php echo $grw_enabled ? 'enabled' : 'disabled' ?></b></label>
                        </div>
                        <div class="wp-review-field-option">
                            <?php wp_nonce_field('grw-wpnonce_grw_active', 'grw-form_nonce_grw_active'); ?>
                            <input type="submit" name="grw_active" class="button" value="<?php echo $grw_enabled ? grw_i('Disable') : grw_i('Enable'); ?>" />
                        </div>
                    </div>
                </form>
                <form method="POST" action="?page=grw&amp;grw_tab=setting" enctype="multipart/form-data">
                    <?php wp_nonce_field('grw-wpnonce_grw_settings', 'grw-form_nonce_grw_settings'); ?>
                    <div class="rplg-field">
                        <div class="rplg-field-label">
                            <label>Google Places API key</label>
                        </div>
                        <div class="wp-review-field-option">
                            <input type="text" id="grw_google_api_key" name="grw_google_api_key" class="regular-text" value="<?php echo esc_attr($grw_google_api_key); ?>">
                            <div style="padding-top:15px">
                                <input type="submit" value="Save" name="grw_setting" class="button" />
                            </div>
                        </div>
                    </div>
                    <div class="rplg-field">
                        <div class="rplg-field-label">
                            <label>Instruction: how to create Google Places API key</label>
                        </div>
                        <div class="wp-review-field-option">
                            <p>1. Go to your <a href="https://console.developers.google.com/apis/dashboard?pli=1" target="_blank">Google Console</a></p>
                            <p>2. Click '<b>Create Project</b>' or '<b>Select Project</b>' button</p>
                            <p>3. Create new project or select existing</p>
                            <p>4. On the project page click '<b>ENABLE APIS AND SERVICES</b>'</p>
                            <p>5. Type '<b>Places API</b>' in the search area</p>
                            <p>6. Select the first result '<b>Places API</b>' and click '<b>ENABLE</b>' button</p>
                            <p>7. On the 'Places API' page select '<b>Credential</b>' tab and '<b>Create credential</b>' / '<b>API key</b>' option</p>
                            <p>8. Copy created API key, paste to this setting and save</p>
                            <h3>Video instruction</h3>
                            <iframe src="//www.youtube.com/embed/Kf_bkg7WeC0?rel=0" allowfullscreen=""></iframe>
                        </div>
                    </div>
                </form>
            </div>

            <div id="shortcode" class="tab-content" style="display:<?php echo $tab == 'shortcode' ? 'block' : 'none'?>;">
                <h3>Shortcode</h3>
                <div class="rplg-flex-row">
                    <div class="rplg-flex-col3">
                        <div class="widget-content">
                            <?php $grw_widget = new Goog_Reviews_Widget; $grw_widget->form(array()); ?>
                        </div>
                    </div>
                    <div class="rplg-flex-col6">
                        <div class="shortcode-content">
                            <textarea id="rplg_shortcode" style="display:block;width:100%;height:200px;padding:10px" onclick="window.rplg_shortcode.select();document.execCommand('copy');window.rplg_shortcode_msg.innerHTML='Shortcode copied, please paste it to the page content';" readonly>Connect Google place to show the shortcode</textarea>
                            <p id="rplg_shortcode_msg"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="support" class="tab-content" style="display:<?php echo $tab == 'support' ? 'block' : 'none'?>;">
                <h3>Most Common Questions</h3>
                <div class="rplg-flex-row">
                    <div class="rplg-flex-col">
                        <div class="rplg-support-question">
                            <h3>I can't connect my Google Place.</h3>
                            <p>Please check that you correctly found the Place ID of your Google business. It should look like <b>ChIJ</b>3TH9CwFZwokRI... This instruction helps to find any Place ID regardless of whether it has a physical address or it is an area: <a href="https://www.launch2success.com/guide/find-any-google-id/" target="_blank">how to find Place ID of any Google business</a></p>
                        </div>
                    </div>
                    <div class="rplg-flex-col">
                        <div class="rplg-support-question">
                            <h3>I have some error messages about the Google API key.</h3>
                            <p>Please make sure that your correctly created the Google Places API key with <b>Places API library and without any restrictions (IP or Referrer)</b>. It should look like <b>AIzaS</b>yB3k4oWDJPF... On the <b>Settings</b> tab there is a detailed instruction and video tutorial how to create the free and correct Google Places API key.</p>
                        </div>
                    </div>
                </div>
                <div class="rplg-flex-row">
                    <div class="rplg-flex-col">
                        <div class="rplg-support-question">
                            <h3>Why I see only 5 Google reviews?</h3>
                            <p>The plugin uses the Google Places API to get your reviews. The API only returns the 5 most helpful reviews. When Google changes the 5 most helpful the plugin will automatically add the new one to your database. Thus slowly building up a database of reviews. It's a limitation of Google, not specifically the plugin.</p>
                            <p>The plugin can only download what Google returns in their Places API. It is usually the 5 Most Helpful (not newest) reviews. You can check what the API returns by entering your Place ID and Goolge API key in this url:</p>
                            <code>https://maps.googleapis.com/maps/api/place/details/json?placeid=YOUR_PLACE_ID&key=YOUR_GOOGLE_API_KEY</code>
                            <p>However, if you got new reviews for your Google place, but the plugin does not show these, it means that Google didn't include it to 5 most helpful and the plugin just can't get this. It's a limitation of Google, not the plugin.</p>
                            <p>Also, please check that the 'Refresh' option is enable in the widget. It will call the Google API once in three days (to avoid a Google Billing and keeps the API key is free) to check the new most helpful reviews.</p>
                        </div>
                    </div>
                    <div class="rplg-flex-col">
                        <div class="rplg-support-question">
                            <h3>Is the plugin compatible with the latest version of PHP? I saw warnings about the compatibility with PHP 7 in the checker plugin.</h3>
                            <p>Yes, the plugin is absolutely compatible with PHP 7 and by the way, we are using PHP 7 on the demo site.</p>
                            <p>The warnings come from the code which is needed for compatible with old versions of PHP (&lt; 5.0) which is sometimes found in some users and without this code, the plugin will not work.</p>
                            <p>The problem is that the plugin which you’re using to test compatibility with PHP 7 cannot understand that this code is not used under PHP 7 or greater. The compatibility plugin just search some keywords which deprecated in the latest version PHP and show warnings about it (without understanding that this is not used).</p>
                        </div>
                    </div>
                </div>
                <div class="rplg-flex-row">
                    <div class="rplg-flex-col">
                        <div class="rplg-support-question">
                            <h3>If you need support</h3>
                            <p>You can contact us directly by email <a href="mailto:support@richplugins.com">support@richplugins.com</a> and would be great and save us a lot of time if each request to the support will contain the following data:</p>
                            <ul>
                                <li><b>1.</b> Clear and understandable description of the issue;</li>
                                <li><b>2.</b> Direct links to your reviews on: Google map;</li>
                                <li><b>3.</b> Link to the page of your site where the plugin installed;</li>
                                <li><b>4.</b> Better if you attach a screenshot(s) (or screencast) how you determine the issue;</li>
                                <li><b>5. The most important:</b> please always copy & paste the DEBUG INFORMATION from the <b>Advance</b> tab.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="rplg-flex-col">
                        <div class="rplg-support-question">
                        </div>
                    </div>
                </div>
            </div>

            <div id="advance" class="tab-content" style="display:<?php echo $tab == 'advance' ? 'block' : 'none'?>;">
                <h3>Advance Options</h3>
                <form method="post" action="?page=grw&amp;grw_tab=advance">
                    <?php wp_nonce_field('grw-wpnonce_grw_advance', 'grw-form_nonce_grw_advance'); ?>
                    <div class="rplg-field">
                        <div class="rplg-field-label">
                            <label>Re-create the database tables of the plugin (service option)</label>
                        </div>
                        <div class="wp-review-field-option">
                            <input type="submit" value="Re-create Database" name="create_db" onclick="return confirm('Are you sure you want to re-create database tables?')" class="button" />
                        </div>
                    </div>
                    <div class="rplg-field">
                        <div class="rplg-field-label">
                            <label><b>Please be careful</b>: this removes all settings, reviews and install the plugin from scratch</label>
                        </div>
                        <div class="wp-review-field-option">
                            <input type="submit" value="Install from scratch" name="install" onclick="return confirm('It will delete all current reviews, are you sure you want to install the plugin from scratch?')" class="button" />
                        </div>
                    </div>
                    <div class="rplg-field">
                        <div class="rplg-field-label">
                            <label><b>Please be careful</b>: this removes all plugin-specific settings (and reviews if 'Remove all reviews' checkbox is set)</label>
                        </div>
                        <div class="wp-review-field-option">
                            <input type="submit" value="Delete the plugin" name="reset_all" onclick="return confirm('Are you sure you want to reset all plugin data' + (window.reset_db.checked ? ' including reviews' : '') + '?')" class="button" />
                            <br><br>
                            <label>
                                <input type="checkbox" id="reset_db" name="reset_db"> Remove all reviews
                            </label>
                        </div>
                    </div>
                    <div id="debug_info" class="rplg-field">
                        <div class="rplg-field-label">
                            <label>DEBUG INFORMATION</label>
                        </div>
                        <div class="wp-review-field-option">
                            <input type="button" value="Copy Debug Information" name="reset_all" onclick="window.rplg_debug_info.select();document.execCommand('copy');window.rplg_debug_msg.innerHTML='Debug Information copied, please paste it to your email to support';" class="button" />
                            <textarea id="rplg_debug_info" style="display:block;width:30em;height:100px;margin-top:10px" onclick="window.rplg_debug_info.select();document.execCommand('copy');window.rplg_debug_msg.innerHTML='Debug Information copied, please paste it to your email to support';" readonly><?php rplg_debug(GRW_VERSION, grw_options(), 'widget_grw_widget'); grw_debug(); ?></textarea>
                            <p id="rplg_debug_msg"></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('a.nav-tab').on('click', function(e)  {
        var $this = $(this), activeId = $this.attr('href');
        $(activeId).show().siblings('.tab-content').hide();
        $this.addClass('nav-tab-active').siblings().removeClass('nav-tab-active');
        e.preventDefault();
    });

    var el = document.body.querySelector('.widget-content'),
        elms = '.widget-content input[type="text"][name],' +
               '.widget-content input[type="hidden"][name],' +
               '.widget-content input[type="checkbox"][name]';

    $(elms).change(function() {
        if (!this.getAttribute('name')) return;
        if (!el.querySelector('.grw-google-place-id').value) return;

        var args = '',
            ctrls = el.querySelectorAll(elms);
        for (var i = 0; i < ctrls.length; i++) {
            var ctrl = ctrls[i],
                match = ctrl.getAttribute('name').match(/\[\]\[(.*?)\]/);
            if (match && match.length > 1) {
                var name = match[1];
                if (ctrl.type == 'checkbox') {
                    if (ctrl.checked) args += ' ' + name + '=true';
                } else {
                    if (ctrl.value) args += ' ' + name + '=' + '"' + ctrl.value + '"';
                }
            }
        }
        window.rplg_shortcode.value = '[grw' + args + ']';
    });
});
</script>