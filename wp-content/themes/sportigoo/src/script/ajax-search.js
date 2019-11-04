const ajaxSearch = $ => {
  // TODO : Update seected in select when "reset" & "url"

  // Don't use if we are on another page
  if ( !$('body').hasClass('page-template-page-search') ) {
    return null;
  }

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

  // Ajax args
  const tmpInitialArgs = {
    action: 'zz_get_products',
    offset: 0,
    product_where: null,
    product_cat: []
  };
  const initialArgs = Object.freeze(tmpInitialArgs);
  let args = resetArgs();
  let state = {
    isLoading: false,
    hasPosts: true
  };

  /**
   * Initial load
   * Check if has URL param
   * @type {URLSearchParams}
   */
  const urlParams = new URLSearchParams(window.location.search);
  if ( urlParams.has('categorie') ) {
    const val = urlParams.get('categorie');
    args.product_cat = [val];
    updateSeleted(val);
    callServer(args, true)
  } else if ( urlParams.has('s') ) {
    const val = urlParams.get('s');
    console.log('has categorie : ', val)
  } else {
    callServer(initialArgs);
  }


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
  refreshButton.click(() => {
    removeSeleted();
    args = resetArgs();
    state.hasPosts = true;
    callServer(args, true)
  });


  /**
   * Utils functions
   */
  // Return activities item count
  function getProductCount () {
    return output.find(productItemClass).length;
  }

  // Call ajax and print response
  function callServer( argument, rm = false ) {
    console.log({ argument }); // debug

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
      console.log({res})
      if ( res && res !== "" ) {
        output.append(res)
        console.log('a')
      } else {
        console.log('b')
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
    const value = select.val();

    args.offset = 0;
    state.hasPosts = true;

    switch (name) {
      case "who":
      case "what":
        // Remove "who" categories if exits And push the new value
        args.product_cat = args.product_cat.filter(slug => !getSelectValues(name).includes(slug));
        args.product_cat.push(value);
        break;
      case "where":
        args.product_where = value;
        break;
    }

    callServer(args, true)
  }

  // Return an array with all values of one select by this name attr
  function getSelectValues( name ) {
    const array = [];
    allSelect.find(`select[name=${name}]`).find('option').each(function () {
      const slug = $(this).val();
      if ( slug && slug !== '0' ) {
        array.push(slug)
      }
    });
    return array;
  }

  // Update selected item when something change
  function updateSeleted( optionName ) {
    allSelect.each(function () {
      $(this).find('.select-items div').each(function() {
        if ( $(this).attr('data-slug') === optionName ) {
          $(this).addClass('same-as-selected')
        }
      })
    });
  }

  // Remove selected class for each select
  function removeSeleted() {
    allSelect.find('.select-items div').each(function() {
      $(this).removeClass('same-as-selected')
    })
  }

  // Reset args
  function resetArgs() {
    return Object.assign({}, tmpInitialArgs);
  }

};

export { ajaxSearch }
