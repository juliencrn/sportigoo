const ajaxSearch = $ => {

  // Don't use if we are on another page
  if ( !$('body').hasClass('page-template-page-search') ) {
    return null;
  }

  // DOM Search inputs
  const searchSection = $('#js-search__buttons');
  const outputSection = $('#js-search__output');
  const output = outputSection.find('#js-output');
  const viewMoreButton = outputSection.find('#js-view-more');
  const productItemClass = '.js-product-item';


  // Ajax args
  const initialArgs = {
    'action': 'zz_get_products'
  };

  // Initial load
  callServer();

  // Utils functions
  function getProductCount() {
    return output.find(productItemClass).length
  }

  function callServer(offset = 0) {
    const args = Object.assign(initialArgs, {offset: offset});
    $.post(ajaxurl, args, res => output.append(res));
  }

  // View more button first
  viewMoreButton.on('click', () => callServer(getProductCount()));

  // Infinite scroll
  $(window).scroll(() => {
    const currentPosition = $(window).scrollTop();
    const winHeight = $(window).height();
    const docHeight = $(document).height();

    if ( currentPosition + winHeight === docHeight ) {
      callServer(getProductCount())
    }
  });

};

export { ajaxSearch }
