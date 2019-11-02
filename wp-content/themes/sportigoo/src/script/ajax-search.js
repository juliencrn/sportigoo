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
  function getLoader() {
    let slug = '';
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    for (let i = 0; i < 10; i++ ) {
      const randomInt = Math.floor(Math.random() * chars.length);
      slug += chars.charAt(randomInt)
    }
    const loader = '<div class="loader ' + slug + '" style="margin: auto;"></div>';
    return { loader, slug }
  }

  function getProductCount() {
    return output.find(productItemClass).length
  }

  function callServer(offset = 0) {
    if ( state.isLoading ) {
      return null;
    }

    state.isLoading = true;
    const { loader, slug } = getLoader();
    output.append(loader);

    const args = Object.assign(initialArgs, {offset: offset});
    $.post(ajaxurl, args, res => {
      state.isLoading = false;
      if ( res ) {
        output.find(`.${slug}`).remove();
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
