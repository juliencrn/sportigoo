import { Map } from 'immutable'

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
  const loader = outputSection.find('#js-loader');
  const refreshButton = searchSection.find('.js-refresh');
  const productItemClass = '.js-product-item';

  const notFound = `
    <div style="margin: auto; text-align: center;" class="ff-book">
        Oups, aucune activité ne correspond à votre recherche.
    </div>`;

  // const setParam = param => urlParams.has(param) ? urlParams.get(param) : 0

  // Ajax args
  const initialArgs = Map({
    action: 'zz_get_products',
    offset: 0,
    product_who: 0,
    product_where: 0,
    product_what: 0
  });
  let args = initialArgs.toObject();
  let state = {
    isLoading: false,
    hasPosts: true
  };

  // Initial load
  callServer(initialArgs.toObject());


  /**
   * EVENTS
   */

  // Infinite scroll
  $(window).scroll(() => handleScroll());

  // On selects filters change
  allSelect.find('.select-items div').click(function () {
    handleFilter( $(this) );
  });

  // On "Refresh" button click
  refreshButton.click(() => callServer(initialArgs.toObject(), true));


  /**
   * Utils functions
   */
  // Return activities item count
  function getProductCount () {
    return output.find(productItemClass).length;
  }

  // Call ajax and print response
  function callServer( argument, rm = false ) {
    // Echap double request
    if ( state.isLoading || !state.hasPosts ) return null;

    // Clean output if necessary
    if (rm) output.find('*').remove();

    // Loading... animation
    state.isLoading = true;
    loader.show();

    $.post(ajaxurl, argument, res => {
      state.isLoading = false;
      loader.hide();
      if ( res ) {
        output.append(res)
      } else {
        if (rm) output.append(notFound);
        state.hasPosts = false;
      }
    });
  }

  // Call server when breakpoint touched
  function handleScroll() {
    const currentPosition = $(window).scrollTop();
    const footerHeight = $('#site-footer').height();
    const winHeight = $(window).height();
    const docHeight = $(document).height();
    const screenPosition = currentPosition + winHeight + footerHeight + 100;

    if ( !state.isLoading && screenPosition > docHeight ) {
      args.offset = getProductCount();
      callServer(args);
    }
  }

  // Call server after switch requests arguments
  function handleFilter( selector ) {
    const select = selector.parent().parent().find('select');
    const name = select.attr('name');
    const value = parseInt(select.val());
    args.offset = 0;
    state.hasPosts = true;

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

    callServer(args, true)
  }

};

export { ajaxSearch }
