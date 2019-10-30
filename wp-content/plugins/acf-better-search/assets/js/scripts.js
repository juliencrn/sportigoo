(function($) {

  /* ---
    Closing admin notice
  --- */

    var isHidden = false;

    $(document).on('click', '.notice[data-notice=acf-better-search] .notice-dismiss', function() {

      if (isHidden)
        return;

      $.ajax(ajaxurl, {
        type: 'POST',
        data: {
          action : 'acf_better_search_notice'
        }
      });

    });

    $(document).on('click', '.notice[data-notice=acf-better-search] .button[data-permanently]', function(e) {

      e.preventDefault();

      isHidden = true;
      $('.notice[data-notice=acf-better-search] .notice-dismiss').click();

      $.ajax(ajaxurl, {
        type: 'POST',
        data: {
          action      : 'acf_better_search_notice',
          permanently : 1
        }
      });

    });

  /* ---
    File type
  --- */

    var isLiteMode = $('#acfbs_lite_mode').is(':checked');

    $('#acfbs_lite_mode').change(function() {

      if (isLiteMode)
        return;

      $('#acfbs_fields_file').prop('disabled', $(this).is(':checked'));

    });

})(jQuery);