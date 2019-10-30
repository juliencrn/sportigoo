<?php 

  class Notice_ACFBetterSearch {

    function __construct() {

      add_action('admin_notices', [$this, 'showAdminNotice']);
      add_action('admin_notices', [$this, 'showAdminError']);

      add_action('admin_init',                       [$this, 'hideNoticeByDefault']);
      add_action('wp_ajax_acf_better_search_notice', [$this, 'hideNotice']);

    }

    /* ---
      Notice box
    --- */

      public function showAdminNotice() {

        if (get_option(ACFBS_NOTICE, false) === false) {

          $this->saveNoticeExpires();
          return;

        }

        if ((get_option(ACFBS_NOTICE, 0) >= time()) || (get_current_screen()->id != 'dashboard'))
          return;

        ?>
          <div class="notice notice-success is-dismissible" data-notice="acf-better-search">
            <h2>
              <?= __('Thank you for using our plugin ACF: Better Search!', 'acf-better-search'); ?>
            </h2>
            <p>
              <?php echo sprintf(__('Please let us know what you think about our plugin. It is important that we can develop this tool. Thank you for all the ratings, reviews and donates. %sIf you have a technical problem, please contact us first before adding the rating. We will try to help you!', 'acf-better-search'), '<br>'); ?>
            </p>
            <p>
              <a href="https://wordpress.org/support/plugin/acf-better-search/" target="_blank" class="button button-primary">
                <?= __('Add technical topic', 'acf-better-search'); ?>
              </a>
              <a href="https://wordpress.org/support/plugin/acf-better-search/reviews/#new-post" target="_blank" class="button button-primary">
                <?= __('Add review', 'acf-better-search'); ?>
              </a>
              <a href="https://www.paypal.me/mateuszgbiorczyk/" target="_blank" class="button button-primary">
                <?= __('Donate us', 'acf-better-search'); ?>
              </a>
              <a href="#" target="_blank" class="button" data-permanently>
                <?= __('I added review, do not show again', 'acf-better-search'); ?>
              </a>
            </p>
          </div>
        <?php

      }

      public function showAdminError() {

        if (function_exists('acf_get_setting'))
          return;

        ?>

          <div class="notice notice-error is-dismissible">
            <h2>
              <?= __('ACF: Better Search error!', 'acf-better-search'); ?>
            </h2>
            <p>
              <?= __('Unfortunately, but this plugin requires the Advanced Custom Field plugin version 5 for all functionalities to work properly. Please install the latest version of the ACF plugin.', 'acf-better-search'); ?>
            </p>
          </div>

        <?php

      }

    /* ---
      Turn off notice
    --- */

      public function hideNoticeByDefault() {

        if (get_option(ACFBS_NOTICE, false) !== false)
          return;

        $expires = strtotime('+1 week');
        $this->saveNoticeExpires($expires);

      }

      public function hideNotice() {

        $isPermanent = isset($_POST['permanently']) && $_POST['permanently'];
        $expires     = strtotime($isPermanent ? '+10 years' : '+1 month');

        $this->saveNoticeExpires($expires);

      }

      public function saveNoticeExpires($expires = 0) {

        $expires = $expires ? $expires : strtotime('+1 week');

        if (get_option(ACFBS_NOTICE, false) !== false)
          update_option(ACFBS_NOTICE, $expires);
        else
          add_option(ACFBS_NOTICE, $expires);

      }

  }