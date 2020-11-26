<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>
<ol>
    <li><?php printf(__('Navigate to <b>%s</b>', 'nextend-facebook-connect'), '<a href="https://developer.twitter.com/en/apps" target="_blank">https://developer.twitter.com/en/apps</a>'); ?></li>
    <li><?php printf(__('Log in with your %s credentials if you are not logged in', 'nextend-facebook-connect'), 'Twitter'); ?></li>
    <li><?php _e('Find your App and click on the "<b>Details</b>" button', 'nextend-facebook-connect'); ?></li>
    <li><?php _e('The <b>Edit</b> button can be found on the App details tab. Click on it and select "<b>Edit details</b>"', 'nextend-facebook-connect'); ?></li>
    <li><?php printf(__('Add the following URL to the "<b>Callback URLs</b>" field: <b>%s</b>', 'nextend-facebook-connect'), $provider->getRedirectUriForApp()); ?></li>
    <li><?php _e('Click on "<b>Save</b>"', 'nextend-facebook-connect'); ?></li>
</ol>