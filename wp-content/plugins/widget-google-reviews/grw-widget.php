<?php

/**
 * Google Reviews Widget
 *
 * @description: The Google Reviews Widget
 * @since      : 1.0
 */

//class name start with Goog_ instead Google_ coz it failed with w3c-total-cache plugin
//https://wordpress.org/support/topic/fix-for-fatal-error-require/
class Goog_Reviews_Widget extends WP_Widget {

    public static $widget_fields = array(
        'title'                => '',
        'place_name'           => '',
        'place_id'             => '',
        'place_photo'          => '',
        'text_size'            => '120',
        'dark_theme'           => '',
        'view_mode'            => 'list',
        'pagination'           => '5',
        'max_width'            => '',
        'max_height'           => '',
        'refresh_reviews'      => true,
        'centered'             => false,
        'reduce_avatars_size'  => true,
        'open_link'            => true,
        'nofollow_link'        => true,
        'lazy_load_img'        => true,
        'def_reviews_link'     => false,
        'reviews_lang'         => '',
    );

    public function __construct() {
        parent::__construct(
            'grw_widget', // Base ID
            'Google Reviews Widget', // Name
            array(
                'classname'   => 'google-reviews-widget',
                'description' => grw_i('Display Google Places Reviews on your website.', 'grw')
            )
        );

        add_action('wp_enqueue_scripts', array($this, 'grw_frontend_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'grw_widget_scripts'));
    }

    function grw_frontend_scripts() {
        wp_register_script('wpac_time_js', plugins_url('/static/js/wpac-time.js', __FILE__), array(), GRW_VERSION);
        wp_enqueue_script('wpac_time_js', plugins_url('/static/js/wpac-time.js', __FILE__));

        wp_register_style('grw_css', plugins_url('/static/css/google-review.css', __FILE__), array(), GRW_VERSION);
        wp_enqueue_style('grw_css', plugins_url('/static/css/google-review.css', __FILE__));
    }

    function grw_widget_scripts($hook) {
        if ($hook == 'widgets.php' || $hook == 'settings_page_grw' || ($hook == 'post.php' && defined('SITEORIGIN_PANELS_VERSION'))) {

            wp_register_style('rplg_wp_css', plugins_url('/static/css/rplg-wp.css', __FILE__), array(), GRW_VERSION);
            wp_enqueue_style('rplg_wp_css', plugins_url('/static/css/rplg-wp.css', __FILE__));

            wp_enqueue_script('jquery');

            wp_register_script('wpac_js', plugins_url('/static/js/wpac.js', __FILE__), array(), GRW_VERSION);
            wp_enqueue_script('wpac_js', plugins_url('/static/js/wpac.js', __FILE__));

            wp_register_script('grw_finder_js', plugins_url('/static/js/grw-finder.js', __FILE__), array(), GRW_VERSION);
            wp_localize_script('grw_finder_js', 'grwVars', array(
                'GOOGLE_AVATAR' => GRW_GOOGLE_AVATAR,
                'handlerUrl' => admin_url('options-general.php?page=grw'),
                'actionPrefix' => 'grw'
            ));
            wp_enqueue_script('grw_finder_js', plugins_url('/static/js/grw-finder.js', __FILE__));
        }
    }

    function widget($args, $instance) {
        global $wpdb;

        if (grw_enabled()) {
            extract($args);
            foreach (self::$widget_fields as $variable => $value) {
                ${$variable} = !isset($instance[$variable]) ? self::$widget_fields[$variable] : esc_attr($instance[$variable]);
            }

            echo $before_widget;
            if ($place_id) {
                if ($title) { ?><h2 class="grw-widget-title widget-title"><?php echo $title; ?></h2><?php }
                include(dirname(__FILE__) . '/grw-reviews.php');
                if ($view_mode == 'badge') {
                    ?>
                    <style>
                    #<?php echo $this->id; ?> {
                      margin: 0;
                      padding: 0;
                      border: none;
                    }
                    </style>
                    <?php
                }
            } else { ?>
                <div class="grw-error" style="padding:10px;color:#B94A48;background-color:#F2DEDE;border-color:#EED3D7;">
                    <?php echo grw_i('Please check that this widget <b>Google Reviews</b> has a Google Place ID set.'); ?>
                </div>
            <?php }
            echo $after_widget;
        }
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        foreach (self::$widget_fields as $field => $value) {
            $instance[$field] = isset($new_instance[$field]) ? strip_tags(stripslashes($new_instance[$field])) : '';
        }
        return $instance;
    }

    function form($instance) {
        global $wp_version;
        foreach (self::$widget_fields as $field => $value) {
            if (array_key_exists($field, self::$widget_fields)) {
                ${$field} = !isset($instance[$field]) ? $value : esc_attr($instance[$field]);
            }
        }

        wp_nonce_field('grw_wpnonce', 'grw_nonce');

        $grw_google_api_key = get_option('grw_google_api_key');
        if ($grw_google_api_key) {
            ?>
            <div id="<?php echo $this->id; ?>" class="rplg-widget"><?php
                if (!$place_id) {
                    include(dirname(__FILE__) . '/grw-finder.php');
                } else { ?>
                    <script type="text/javascript">
                        jQuery('.grw-tooltip').remove();
                    </script> <?php
                }
                include(dirname(__FILE__) . '/grw-options.php'); ?>
            </div>
            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" onload="(function(el) { var t = setInterval(function () {if (window.grw_init){grw_init({el: el});clearInterval(t);}}, 200); })(this.parentNode);" style="display:none">
            <?php
        } else {
            ?>
            <h4 class="text-left">First of all, please create and save the Google API Key on <a href="<?php echo admin_url('options-general.php?page=grw&grw_tab=setting'); ?>">the setting page</a> of the plugin</h4>
            <script type="text/javascript">
                var apikey = document.querySelectorAll('.grw-apikey');
                if (apikey) {
                    WPacFastjs.onall(apikey, 'change', function() {
                        if (!this.value) return;
                        jQuery.post('<?php echo admin_url('options-general.php?page=grw'); ?>&cf_action=' + this.getAttribute('name'), {
                            key: this.value,
                            grw_wpnonce: jQuery('#grw_nonce').val()
                        }, function(res) {
                            console.log('RESPONSE', res);
                        }, 'json');
                    });
                }
            </script>
            <?php
        }
        ?>
        <script type="text/javascript">
            function grw_load_js(src, cb) {
                var script = document.createElement('script');
                script.type = 'text/javascript';
                script.src = src;
                script.async = 'true';
                if (cb) {
                    script.addEventListener('load', function (e) { cb(null, e); }, false);
                }
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function grw_load_css(href) {
                var link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = href;
                document.getElementsByTagName('head')[0].appendChild(link);
            }

            if (!window.grw_init) {
                grw_load_css('<?php echo plugins_url('/static/css/rplg-wp.css?ver=' . GRW_VERSION, __FILE__); ?>');
                grw_load_js('<?php echo plugins_url('/static/js/wpac.js?ver=' . GRW_VERSION, __FILE__); ?>', function() {
                    window.grwVars = {
                        GOOGLE_AVATAR : '<?php echo GRW_GOOGLE_AVATAR; ?>',
                        handlerUrl    : '<?php echo admin_url('options-general.php?page=grw'); ?>',
                        actionPrefix  : 'grw'
                    };
                    grw_load_js('<?php echo plugins_url('/static/js/grw-finder.js?ver=' . GRW_VERSION, __FILE__); ?>');
                });
            }
        </script>
        <?php
    }
}
?>