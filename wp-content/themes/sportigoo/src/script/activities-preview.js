const activitiesPreview = $ => {

  const productBtn = $('.hasPrev .activities__item-wrapper .activities__preview-button');
  const loader = "<div style='margin: auto;' class=\"loader\"></div>";
  let old_product_id = 'undefined';

  productBtn.on('click', function (e) {
    e.preventDefault();

    const thisContainer = $(this).closest('.activities__row');
    const thisPreview = thisContainer.parent().find('.activity-preview');
    const product_id = $(this).closest('.activities__item-wrapper').attr('data-id');

    // Close popup
    if (typeof old_product_id !== 'undefined') {
      if (old_product_id === product_id) {

        // Reset
        cleaner(thisPreview);
        old_product_id = 'undefined';
        return false;
      }
    }

    // Clean
    cleaner(thisPreview);

    // open preview
    thisPreview.show();
    thisPreview.find('.activity-preview__container').hide();
    thisPreview.find('.container').append(loader);
    scrollToAnchor(thisPreview)

    // Get data using ajax
    $.post(
      ajaxurl,
      {
        'action': 'sportigoo_get_preview',
        'product_id': product_id
      },
      function(response){

        // Parse Response
        response = JSON.parse(response);
        const {html, video, image} = response;
        // console.log({html, video, image})



        // Display HTML
        thisPreview.find('.loader').hide();
        thisPreview.find('.activity-preview__container').css('display', 'flex');
        thisPreview.find('.activity-preview__left').append(html);
        thisPreview.find('.activity-preview__title').addClass('orange');

        // Add image bg
        thisPreview.css('background-image', 'url('+image+')');

        // Add Video
        console.log(video[0]);
        if (video[0]) {
          thisPreview.find('.activity-preview__video').find('iframe').attr('src', video[0]);
        } else {
          thisPreview.find('.activity-preview__video-button').hide()
        }


      }
    );

    old_product_id = product_id;
  })
};


const cleaner = thisPreview => {
  $('.activities__block .activity-preview').hide();
  thisPreview.find('.activity-preview__left').find('*').remove();
  thisPreview.find('.activity-preview__video-button').show();
  thisPreview.css('background-image', 'url()');
  thisPreview.find('.activity-preview__video').find('iframe').attr('src', "")
};

const scrollToAnchor = elt => {
  $('html,body').animate({scrollTop: elt.offset().top - 65},'slow');
}

export { activitiesPreview }
