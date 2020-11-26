<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>

<div class="nsl-admin-sub-content">
    <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

    <p style="max-width:55em;"><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create a %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "Twitter", "Consumer Key", "Consumer Secret"); ?></p>

    <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'Twitter App'); ?></h2>

    <ol>
        <li><?php printf(__('Navigate to <b>%s</b>', 'nextend-facebook-connect'), '<a href="https://developer.twitter.com/en/apps/create" target="_blank">https://developer.twitter.com/en/apps/create</a>'); ?></li>
        <li><?php printf(__('Log in with your %s credentials if you are not logged in yet', 'nextend-facebook-connect'), 'Twitter'); ?></li>
        <li><?php _e('If you don\'t have a developer account yet, please apply one by filling all the required details! This is required for the next steps!', 'nextend-facebook-connect'); ?></li>
        <li><?php printf(__('Once your developer account is complete, navigate back to <b>%s</b> if you aren\'t already there!', 'nextend-facebook-connect'), '<a href="https://developer.twitter.com/en/apps/create" target="_blank">https://developer.twitter.com/en/apps/create</a>'); ?>
        <li><?php printf(__('Fill the <b>App name</b>, <b>Application description</b> fields. Then enter your site\'s URL to the <b>Website URL</b> field: <b>%s</b>', 'nextend-facebook-connect'), site_url()); ?></li>
        <li><?php _e('Tick the checkbox next to <b>Enable Sign in with Twitter</b>!', 'nextend-facebook-connect'); ?></li>
        <li><?php printf(__('Add the following URL to the "<b>Callback URLs</b>" field: <b>%s</b>', 'nextend-facebook-connect'), $provider->getRedirectUriForApp()); ?></li>
        <li><?php _e('Fill the “<b>Terms of Service URL</b>", "<b>Privacy policy URL</b>" and "<b>Tell us how this app will be used</b>" fields!', 'nextend-facebook-connect'); ?></li>
        <li><?php _e('Click the <b>Create</b> button.', 'nextend-facebook-connect'); ?></li>
        <li><?php _e('Read the Developer Terms and click the <b>Create</b> button again!', 'nextend-facebook-connect'); ?></li>
        <li><?php _e('Select the <b>Permissions</b> tab and click <b>Edit</b>.', 'nextend-facebook-connect'); ?></li>
        <li><?php _e('Tick the <b>Request email address from users</b> under the Additional permissions section and click <b>Save</b>.', 'nextend-facebook-connect'); ?></li>
        <li><?php _e('Go to the <b>Keys and tokens</b> tab and find the <b>API key</b> and <b>API secret key</b>', 'nextend-facebook-connect'); ?></li>
    </ol>

    <a href="<?php echo $this->getUrl('settings'); ?>"
       class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'Twitter App'); ?></a>

    <br>
    <div class="nsl-admin-embed-youtube">
        <div></div>
        <iframe src="https://www.youtube.com/embed/5m4kD11Ai2w?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
    </div>
</div>