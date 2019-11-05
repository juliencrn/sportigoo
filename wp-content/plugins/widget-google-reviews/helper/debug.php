<?php

if (!function_exists('rplg_debug')) {

    function rplg_debug($version, $options, $widget_name) {
        global $wp_version; ?>
URL: <?php echo esc_url(get_option('siteurl')); ?>

PHP Version: <?php echo esc_html(phpversion()); ?>

WP Version: <?php echo esc_html($wp_version); ?>

Active Theme:
<?php
if (!function_exists('wp_get_theme')) {
    $theme = get_theme(get_current_theme());
    echo esc_html($theme['Name'] . ' ' . $theme['Version']);
} else {
    $theme = wp_get_theme();
    echo esc_html($theme->Name . ' ' . $theme->Version);
}
?>

URLOpen Method: <?php echo esc_html(rplg_url_method()); ?>

URLOpen allow: <?php $urlopen = rplg_json_urlopen('https://graph.facebook.com/2/ratings');
echo ($urlopen && $urlopen->error) ? strlen($urlopen->error->message) > 0 : false; ?>

Plugin Version: <?php echo esc_html($version); ?>

Settings:
<?php foreach ($options as $opt) {
    echo esc_html($opt.': '.get_option($opt)."\n");
}
?>

Widgets: <?php $widget = get_option($widget_name); echo ($widget ? print_r($widget) : '')."\n"; ?>

Plugins:
<?php
foreach (get_plugins() as $key => $plugin) {
    $isactive = "";
    if (is_plugin_active($key)) {
        $isactive = "(active)";
    }
    echo esc_html($plugin['Name'].' '.$plugin['Version'].' '.$isactive."\n");
}
    }

}
?>
