const ajaxSearch = $ => {

  // Don't use if we are on another page
  if ( !$('body').hasClass('page-template-page-search') ) {
    return null;
  }

  let urlParams = new URLSearchParams(window.location.search)

  // DOM Search inputs
  const searchSection = $('#js-search__buttons');
  const allSelect = searchSection.find('.custom-select');
  const outputSection = $('#js-search__output');
  const output = outputSection.find('#js-output');
  const productItemClass = '.js-product-item';

  const notFound = `
    <div style="margin: auto; text-align: center" class="ff-book">
        Oups, aucune activité ne correspond à votre recherche.
    </div>`;

  // const setParam = param => urlParams.has(param) ? urlParams.get(param) : 0

  // Ajax args
  const args = {
    action: 'zz_get_products',
    offset: 0,
    product_who: 0,
    product_where: 0,
    product_what: 0,
    product_cat: []
  };
  const state = {
    isLoading: false,
    hasPosts: true
  };

  // Initial load
  callServer();


  /**
   * EVENTS
   */

  // Infinite scroll
  $(window).scroll(() => {
    const currentPosition = $(window).scrollTop();
    const footerHeight = $('#site-footer').height();
    const winHeight = $(window).height();
    const docHeight = $(document).height();
    const screenPosition = currentPosition + winHeight + footerHeight + 100;

    if ( !state.isLoading && screenPosition > docHeight ) {
      args.offset = getProductCount();
      callServer();
    }
  });

  // On selects filters change
  allSelect.find('.select-items div').click(function () {
    const select = $(this).parent().parent().find('select');
    const name = select.attr('name');
    const value = parseInt(select.val());
    args.offset = 0;
    state.hasPosts = true;

    // console.log({name, value, select })

    switch (name) {
      case "who":
        args.product_who = value;
        break;
      case "where":
        args.product_where = value;
        break;
      case "what":
        args.product_what = value;
        break;
    }

    callServer(true)
  })


  /**
   * Utils functions
   */

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

  function callServer( rm = false ) {
    // console.log({ args, state });
    if ( state.isLoading || !state.hasPosts ) {
      return null;
    }

    // Loading... animation
    state.isLoading = true;
    const { loader, slug } = getLoader();
    output.append(loader);

    $.post(ajaxurl, args, res => {
      state.isLoading = false;
      output.find(`.${slug}`).remove();
      if (rm) output.find('*').remove();
      if ( res ) {
        output.append(res)
      } else {
        if (rm) output.append(notFound);
        state.hasPosts = false;
      }
    });
  }

};

export { ajaxSearch }
