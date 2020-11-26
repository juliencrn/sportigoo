<?php

if (false === apply_filters('nsl-pro', false)) {
    $current = time();
    if (mktime(0, 0, 0, 11, 26, 2020) <= $current && $current <= mktime(0, 0, 0, 12, 2, 2020)) {
        if (get_transient('nsl_bf_2020') != '1') {

            add_action('admin_enqueue_scripts', function () {
                wp_enqueue_script('jquery');
            });

            add_action('admin_notices', function () {
                ?>
                <div class="notice notice-info is-dismissible" data-nsldismissable="" style="display:grid;grid-template-columns: 100px auto;padding-top: 25px; padding-bottom: 22px;">
                    <img alt="Nextend Social Login" src="<?php echo plugins_url('images/notice.png', NSL_ADMIN_PATH) ?>" width="74px" height="74px" style="grid-row: 1 / 4; align-self: center;justify-self: center"/>
                    <h3 style="margin:0;">Nextend Social Login - Black Friday Deal</h3>
                    <p style="margin:0 0 2px;">Don't miss out on our biggest sale of the year! Get your <b>Pro Addon</b>
                        with <b>40% OFF</b> to access <b>WooCommerce support</b>, Apple provider and much more! Limited
                        time offer expires on December 1.</p>
                    <p style="margin:0;">
                        <a class="button button-primary" href="https://nextendweb.com/social-login/?coupon=SAVE4020&utm_source=nslfree&utm_medium=wp&utm_campaign=bf20#pricing" target="_blank">
                            Buy Now</a>
                        <a class="button button-dismiss" href="#">Dismiss</a>
                    </p>
                </div>
                <?php
            });

            add_action('admin_footer', function () {
                ?>
                <script type="text/javascript">
                    (function ($) {
                        $(function () {
                            setTimeout(function () {
                                $('div[data-nsldismissable] .notice-dismiss, div[data-nsldismissable] .button-dismiss')
                                    .on('click', function (e) {
                                        e.preventDefault();
                                        $.post(ajaxurl, {
                                            'action': 'nsl_dismiss_admin_notice',
                                            'nonce': <?php echo json_encode(wp_create_nonce('nsl-dismissible-notice')); ?>
                                        });
                                        $(e.target).closest('.is-dismissible').remove();
                                    });
                            }, 1000);
                        });
                    })(jQuery);
                </script>
                <?php
            });

            add_action('wp_ajax_nsl_dismiss_admin_notice', function () {
                check_ajax_referer('nsl-dismissible-notice', 'nonce');

                set_transient('nsl_bf_2020', '1', YEAR_IN_SECONDS);
                wp_die();
            });
        }
    }
}
