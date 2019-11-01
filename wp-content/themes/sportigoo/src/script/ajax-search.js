const ajaxSearch = $ => {

  // Don't use if we are on another page
  if ( !$('body').hasClass('page-template-page-search') ) {
    return null;
  }

  // DOM Search inputs
  const searchSection = $('#js-search__buttons');
  const outputSection = $('#js-search__output');
  const output = outputSection.find('#js-output');
  const productItemClass = '.js-product-item';


  // Ajax args
  const initialArgs = {
    'action': 'zz_get_products'
  };
  let state = {
    isLoading: false
  };

  // Initial load
  callServer();

  // Utils functions
  function getProductCount() {
    return output.find(productItemClass).length
  }

  function callServer(offset = 0) {
    if ( state.isLoading ) {
      return null;
    }

    state.isLoading = true;
    const args = Object.assign(initialArgs, {offset: offset});
    $.post(ajaxurl, args, res => {
      state.isLoading = false;
      if ( res ) {
        output.append(res)
      }
    });
  }

  // Infinite scroll
  $(window).scroll(() => {
    const currentPosition = $(window).scrollTop();
    const footerHeight = $('#site-footer').height();
    const winHeight = $(window).height();
    const docHeight = $(document).height();
    const screenPosition = currentPosition + winHeight + footerHeight + 100;

    if ( !state.isLoading && screenPosition > docHeight ) {
      callServer(getProductCount())
    }
  });

};

export { ajaxSearch }
