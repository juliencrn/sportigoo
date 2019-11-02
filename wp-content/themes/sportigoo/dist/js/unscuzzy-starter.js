(function () {
'use strict';

var navbar = function navbar($) {

	var navBlue = function navBlue() {
		$('.nav').css({
			background: 'rgba(29,70,122,1)',
			boxShadow: '3px 0 5px rgba(0,0,0,0.5)'
		}).removeClass('nav--topped');
		$('.nav li ul').css({
			background: 'rgba(29,70,122,1)'
		});
	};

	var navTran = function navTran() {
		$('.nav').css({
			background: 'rgba(29,70,122,0)',
			boxShadow: '0px 0 5px rgba(0,0,0,0)'
		}).addClass('nav--topped');
		$('.nav li ul').css({
			background: 'rgba(29,70,122,0)'
		});
	};

	var toggleNavColor = function toggleNavColor() {
		$(window).scrollTop() < 150 && $('.nav').hasClass('transparent') ? navTran() : navBlue();
	};

	// Hide when scroll down
	$(document).on('scroll', function () {
		return toggleNavColor();
	});

	// Init, on load
	toggleNavColor();

	//Dropdown
	var dropdowns = $('.nav__links-container > li');
	if (window.screen < 999) {
		dropdowns.each(function () {
			$(this).click(function () {
				$(this).find('ul').addClass('active');
			}, function () {
				dropdowns.find('ul').removeClass('active');
			});
		});
	} else {
		dropdowns.each(function () {
			$(this).hover(function () {
				$(this).find('ul').addClass('active');
			}, function () {
				dropdowns.find('ul').removeClass('active');
			});
		});
	}
};

var cookie = function cookie($) {
	window.addEventListener('load', function () {

		var cookieElt = $('#cookie');
		window.cookieconsent.initialise({
			palette: {
				popup: {
					background: '#eb6c44',
					text: '#ffffff'
				},
				button: {
					background: '#ffffff'
				}
			},
			theme: 'classic',
			position: 'bottom-right',
			content: {
				message: cookieElt.find('#cookie_text').val(),
				dismiss: cookieElt.find('#cookie_ok').val(),
				link: cookieElt.find('#cookie_more').val(),
				href: cookieElt.find('#cookie_href').val()
			}
		});
	});
};

/**
 * Cookies getter & setter
 * @link https://plainjs.com/javascript/utilities/set-cookie-get-cookie-and-delete-cookie-5/
 *
 */
function getCookie(name) {
	var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
	return v ? v[2] : null;
}

function setCookie(name, value) {
	var days = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 30;

	var d = new Date();
	d.setTime(d.getTime() + 24 * 60 * 60 * 1000 * days);
	document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
}

// Select all links with hashes

var smoothScroll = function smoothScroll($) {
  $('a[href*="#"]')
  // Remove links that don't actually link to anything
  .not('[href="#"]').not('[href="#0"]').not('.dont-scroll').click(function (event) {
    // On-page links
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
      // Figure out element to scroll to
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      // Does a scroll target exist?
      if (target.length) {
        // Only prevent default if animation is actually gonna happen
        event.preventDefault();
        $('html, body').animate({
          scrollTop: target.offset().top - 100
        }, 1000, function () {
          // Callback after animation
          // Must change focus!
          var $target = $(target);
          $target.focus();
          if ($target.is(':focus')) {
            // Checking if the target was focused
            return false;
          } else {
            $target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable
            $target.focus(); // Set focus again
          }
        });
      }
    }
  });
};

var customSelect = function customSelect($) {
  var x, i, j, selElmnt, a, b, c;
  /* Look for any elements with the class "custom-select": */
  x = document.getElementsByClassName('custom-select');
  for (i = 0; i < x.length; i++) {
    selElmnt = x[i].getElementsByTagName('select')[0];
    /* For each element, create a new DIV that will act as the selected item: */
    a = document.createElement('DIV');
    a.setAttribute('class', 'select-selected');
    a.innerHTML = "<span>" + selElmnt.options[selElmnt.selectedIndex].innerHTML + "</span>";
    // a.innerHTML = "<span>Input</span>";
    x[i].appendChild(a);
    /* For each element, create a new DIV that will contain the option list: */
    b = document.createElement('DIV');
    b.setAttribute('class', 'select-items select-hide');
    for (j = 1; j < selElmnt.length; j++) {
      /* For each option in the original select element,
        create a new DIV that will act as an option item: */
      c = document.createElement('DIV');
      c.innerHTML = selElmnt.options[j].innerHTML;
      c.addEventListener('click', function (e) {
        /* When an item is clicked, update the original select box,
            and the selected item: */
        var y, i, k, s, h;
        s = this.parentNode.parentNode.getElementsByTagName('select')[0];
        h = this.parentNode.previousSibling;
        for (i = 0; i < s.length; i++) {
          if (s.options[i].innerHTML == this.innerHTML) {
            s.selectedIndex = i;
            // h.innerHTML = this.innerHTML
            // h.innerHTML = "Hloa"
            y = this.parentNode.getElementsByClassName('same-as-selected');
            for (k = 0; k < y.length; k++) {
              y[k].removeAttribute('class');
            }
            this.setAttribute('class', 'same-as-selected');
            break;
          }
        }
        h.click();
      });
      b.appendChild(c);
    }
    x[i].appendChild(b);
    a.addEventListener('click', function (e) {
      /* When the select box is clicked, close any other select boxes,
        and open/close the current select box: */
      e.stopPropagation();
      closeAllSelect(this);
      this.nextSibling.classList.toggle('select-hide');
      this.classList.toggle('select-arrow-active');
    });
  }

  function closeAllSelect(elmnt) {
    /* A function that will close all select boxes in the document,
      except the current select box: */
    var x,
        y,
        i,
        arrNo = [];
    x = document.getElementsByClassName('select-items');
    y = document.getElementsByClassName('select-selected');
    for (i = 0; i < y.length; i++) {
      if (elmnt == y[i]) {
        arrNo.push(i);
      } else {
        y[i].classList.remove('select-arrow-active');
      }
    }
    for (i = 0; i < x.length; i++) {
      if (arrNo.indexOf(i)) {
        x[i].classList.add('select-hide');
      }
    }
  }

  /* If the user clicks anywhere outside the select box,
    then close all select boxes: */
  document.addEventListener('click', closeAllSelect);
};

var carousel = function carousel($) {
  $('.home_categories_slider').slick({
    autoplay: true,
    autoplaySpeed: 5000,
    dots: false,
    infinite: true,
    arrows: true,
    prevArrow: $('.home_categories_slider--nav > .carousel__prev'),
    nextArrow: $('.home_categories_slider--nav > .carousel__next'),

    //centerMode: true,
    //centerPadding: '80px',
    speed: 500,
    slidesToShow: 7,
    slidesToScroll: 2,
    responsive: [{
      breakpoint: 1024,
      settings: {
        slidesToShow: 4
      }
    }, {
      breakpoint: 750,
      settings: {
        slidesToShow: 3
      }
    }, {
      breakpoint: 480,
      settings: {
        slidesToShow: 2
      }
    }]
  });

  var laws_slider = $('.carousel__laws');
  var prevArrow = laws_slider.parent().find('.carousel__prev');
  var nextArrow = laws_slider.parent().find('.carousel__next');

  laws_slider.slick({
    autoplay: false,
    autoplaySpeed: 5000,
    dots: false,
    infinite: true,
    speed: 500,
    slidesToShow: 4,
    prevArrow: prevArrow,
    nextArrow: nextArrow,
    responsive: [{
      breakpoint: 1024,
      settings: {
        slidesToShow: 3
      }
    }, {
      breakpoint: 750,
      settings: {
        slidesToShow: 2
      }
    }, {
      breakpoint: 480,
      settings: {
        slidesToShow: 1
      }
    }]
  });

  // Dynamic multi carousel
  $('.carousel').each(function (key) {
    var sliderIdName = 'slider-' + key;
    this.id = sliderIdName;
    var SliderId = '#' + sliderIdName;

    var prevArrow = $(this).parent().find('.carousel__prev');
    var nextArrow = $(this).parent().find('.carousel__next');

    $(SliderId).slick({
      autoplay: true,
      autoplaySpeed: 5000,
      dots: false,
      infinite: true,
      speed: 500,
      slidesToShow: 1,
      prevArrow: prevArrow,
      nextArrow: nextArrow
    });
  });

  var responsive = [{
    breakpoint: 999,
    settings: {
      slidesToShow: 1,
      infinite: true,
      autoplay: true,
      centerMode: false
    }
  }];

  $('.mobile_carousel').slick({
    autoplay: false,
    autoplaySpeed: 5000,
    dots: false,
    infinite: true,
    arrows: false,
    centerMode: true,
    centerPadding: '80px',
    speed: 500,
    slidesToShow: 3,
    responsive: responsive
  });

  // AVIS
  var avisCar = $('.avis__carousel');
  var avisCount = Number(avisCar.attr('data-count'));

  var avisArgs = {
    autoplay: true,
    autoplaySpeed: 5000,
    dots: false,
    infinite: true,
    speed: 500,
    // centerMode: true,
    // centerPadding: '-30px',
    // slidesToShow: 3,
    slidesToScroll: 1,
    responsive: responsive,
    prevArrow: $('.avis__prev'),
    nextArrow: $('.avis__next')
  };

  if (avisCount < 4) {
    avisArgs.slidesToShow = 1;
    // avisArgs.infinite = false;
    avisArgs.centerMode = false;
    avisArgs.centerPadding = '0';
    avisArgs.arrow = false;
  } else {
    avisArgs.slidesToShow = 3;
    avisArgs.infinite = true;
    avisArgs.centerMode = true;
    avisArgs.centerPadding = '-30px';
  }

  avisCar.slick(avisArgs);

  $('.recents_slider__carousel').slick({
    autoplay: true,
    autoplaySpeed: 5000,
    dots: false,
    infinite: true,
    responsive: responsive,
    speed: 500,
    // centerMode: true,
    slidesToShow: 3,
    prevArrow: $('.recents_slider__carousel__prev'),
    nextArrow: $('.recents_slider__carousel__next')
  });
};

var activities = function activities($) {

	var slider = $('.activitiesSlider');
	var padding = (slider.width() - 1140) / 2;

	if ($('body').hasClass('home')) {
		// Padding based on container size


		//Slider
		slider.slick({
			dots: false,
			infinite: true,
			speed: 300,
			// autoplay: true,
			slidesToShow: 5,
			centerMode: true,
			centerPadding: padding,
			prevArrow: '<div class="carousel__prev" style="display: none"></div>',
			nextArrow: '<div class="carousel__next"><svg width="50" height="50"><use xlink:href="#next"></use></svg></div>',

			responsive: [{
				breakpoint: 999,
				settings: {
					slidesToShow: 3
				}
			}, {
				breakpoint: 767,
				settings: {
					slidesToShow: 2
				}
			}, {
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}]
		});
	} else {
		// Dynamic multi carousel
		$('.activitiesSlider').each(function (key, item) {

			var sliderIdName = 'slider-' + key;
			this.id = sliderIdName;
			var SliderId = '#' + sliderIdName;
			var slideCount = $(this).find('article').length;
			var centerMode = true;
			if (slideCount < 5) {
				centerMode = false;
				$(SliderId).addClass('not-all-slide');
			}

			var prevArrow = $(this).parent().find('.carousel__prev');
			var nextArrow = $(this).parent().find('.carousel__next');

			$(SliderId).slick({
				dots: false,
				infinite: true,
				speed: 300,
				autoplay: true,
				slidesToShow: 5,
				centerMode: centerMode,
				centerPadding: padding,
				prevArrow: prevArrow,
				nextArrow: nextArrow,

				responsive: [{
					breakpoint: 999,
					settings: {
						slidesToShow: 3
					}
				}, {
					breakpoint: 767,
					settings: {
						slidesToShow: 2
					}
				}, {
					breakpoint: 480,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
				}]
			});
		});
	}

	$('.categoriesSlider').slick({
		infinite: true,
		speed: 300,
		slidesToShow: 4,
		autoplay: true,
		centerMode: true,
		centerPadding: padding,
		prevArrow: '<div class="carousel__prev"><svg width="20" height="20"><use xlink:href="#chevron-left"></use></svg></div>',
		nextArrow: '<div class="carousel__next"><svg width="20" height="20"><use xlink:href="#chevron-right"></use></svg></div>',
		dots: false,
		responsive: [{
			breakpoint: 1200,
			settings: {
				slidesToShow: 3
			}
		}, {
			breakpoint: 999,
			settings: {
				slidesToShow: 2
			}
		}, {
			breakpoint: 480,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1
			}
		}]
	});
};

var lastPosts = function lastPosts($) {
  //change picture on hover
  var item = $('.last-posts__nav-item-wrapper');
  var excerptPreview = $('#excerptPreview');
  var linkPreview = $('#preview-link');

  item.on({
    mouseenter: function mouseenter() {
      // Excerpt
      var excerpt = $(this).find('.last-posts__nav-excerpt').html();
      var postLink = $(this).find('a.last-posts__nav-item').attr('href');

      // Cats
      var cat = [];
      cat[0] = [$(this).find('.catList .cat-1').attr('data-name'), $(this).find('.catList .cat-1').attr('data-url')];
      cat[1] = [$(this).find('.catList .cat-2').attr('data-name'), $(this).find('.catList .cat-2').attr('data-url')];

      $('#catoutup-1').html(cat[0][0]).attr('href', cat[0][1]);

      if (typeof cat[1][0] !== "undefined") {
        $('#catoutup-2').show().html(cat[1][0]).attr('href', cat[1][1]);
      } else {
        $('#catoutup-2').hide();
      }

      // console.log(cat);
      excerptPreview.html(excerpt);

      // Image BG
      $(this).children('.last-posts__img').css({
        zIndex: 1
      });

      // Update link
      linkPreview.attr('href', postLink);

      // Active css class
      item.removeClass('active');
      $(this).addClass('active');
    },

    mouseleave: function mouseleave() {
      // console.log('hover out')
      $(this).children('.last-posts__img').css({
        zIndex: 0
      });
    }
  });
};

var respNavigation = function respNavigation($) {
  var body = void 0;
  var menu = void 0;

  var init = function init() {
    body = document.querySelector('body');
    menu = document.querySelector('.hamburger');

    applyListeners();
  };

  var applyListeners = function applyListeners() {
    menu.addEventListener('click', function () {
      return toggleClass(body, 'is-active');
    });
  };

  var toggleClass = function toggleClass(element, stringClass) {
    if (element.classList.contains(stringClass)) element.classList.remove(stringClass);else element.classList.add(stringClass);
  };

  init();
};

var faq = function faq($) {
  var items = $('.faq__item');
  items.on('click', function () {
    if (!$(this).hasClass('active')) {
      items.removeClass('active');
      $(this).addClass('active');
    } else {
      items.removeClass('active');
    }
  });
};

var ajaxSearch = function ajaxSearch($) {

  // Don't use if we are on another page
  if (!$('body').hasClass('page-template-page-search')) {
    return null;
  }

  var urlParams = new URLSearchParams(window.location.search);

  // DOM Search inputs
  var searchSection = $('#js-search__buttons');
  var allSelect = searchSection.find('.custom-select');
  var outputSection = $('#js-search__output');
  var output = outputSection.find('#js-output');
  var productItemClass = '.js-product-item';

  var notFound = '\n    <div style="margin: auto; text-align: center" class="ff-book">\n        Oups, aucune activit\xE9 ne correspond \xE0 votre recherche.\n    </div>';

  // const setParam = param => urlParams.has(param) ? urlParams.get(param) : 0

  // Ajax args
  var args = {
    action: 'zz_get_products',
    offset: 0,
    product_who: 0,
    product_where: 0,
    product_what: 0,
    product_cat: []
  };
  var state = {
    isLoading: false,
    hasPosts: true
  };

  // Initial load
  callServer();

  /**
   * EVENTS
   */

  // Infinite scroll
  $(window).scroll(function () {
    var currentPosition = $(window).scrollTop();
    var footerHeight = $('#site-footer').height();
    var winHeight = $(window).height();
    var docHeight = $(document).height();
    var screenPosition = currentPosition + winHeight + footerHeight + 100;

    if (!state.isLoading && screenPosition > docHeight) {
      args.offset = getProductCount();
      callServer();
    }
  });

  // On selects filters change
  allSelect.find('.select-items div').click(function () {
    var select = $(this).parent().parent().find('select');
    var name = select.attr('name');
    var value = parseInt(select.val());
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

    callServer(true);
  });

  /**
   * Utils functions
   */

  function getLoader() {
    var slug = '';
    var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    for (var i = 0; i < 10; i++) {
      var randomInt = Math.floor(Math.random() * chars.length);
      slug += chars.charAt(randomInt);
    }
    var loader = '<div class="loader ' + slug + '" style="margin: auto;"></div>';
    return { loader: loader, slug: slug };
  }

  function getProductCount() {
    return output.find(productItemClass).length;
  }

  function callServer() {
    var rm = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

    // console.log({ args, state });
    if (state.isLoading || !state.hasPosts) {
      return null;
    }

    // Loading... animation
    state.isLoading = true;

    var _getLoader = getLoader(),
        loader = _getLoader.loader,
        slug = _getLoader.slug;

    output.append(loader);

    $.post(ajaxurl, args, function (res) {
      state.isLoading = false;
      output.find('.' + slug).remove();
      if (rm) output.find('*').remove();
      if (res) {
        output.append(res);
      } else {
        if (rm) output.append(notFound);
        state.hasPosts = false;
      }
    });
  }
};

var modal = function modal($) {
  jQuery(function ($) {
    var popup = $('.activity-preview__video');
    var popupContainer = $('.activity-preview__video-container');
    var buttonOpen = $('.activity-preview__video-button, .border_orange__center');
    var buttonClose = $('.activity-preview__close-video');
    var tl = new TimelineLite();

    buttonOpen.click(function () {
      return openModal();
    });
    buttonClose.click(function () {
      return closeModal();
    });

    popup.on('click', function (e) {
      if (popup.has(e.target).length === 0) {
        closeModal();
      }
    });

    function closeModal() {
      tl.to(popupContainer, 0.3, {
        y: '-1000px',
        opacity: 0
      });
      tl.to(popup, 0.3, {
        scale: 0,
        opacity: 0,
        delay: 0.4
      });
    }

    function openModal() {
      tl.to(popup, 0.3, {
        scale: 1,
        opacity: 1
      });
      tl.to(popupContainer, 0.6, {
        opacity: 1,
        y: 0,
        delay: 0.4
      });
    }
  });
};

var searchActivity = function searchActivity($) {

	var form = $('form#js-search-activity');
	var whereBtn = form.find('#where-group');

	form.on('submit', function (e) {
		e.preventDefault();
		var base_url = $('#search_base_url').val();

		// Make form data readable
		var formData = [['what', e.target[0].value], ['who', e.target[1].value], ['where', e.target[2].value]];

		// Force use dept geoloc
		var where = formData[2][1];
		if (where === "0") {
			whereBtn.addClass('invalid');
			return false;
		}

		// Save as cookie where user is
		var selected = e.target[2].selectedOptions[0].dataset.dept;
		setCookie('whereIam', selected);

		// Build param
		var params = '?';
		formData.map(function (value, index, array) {
			params += '&' + value[0] + '=' + value[1];
		});

		// redirect to search page
		window.location.href = base_url + params;

		return false;
	});
};

var activitiesPreview = function activitiesPreview($) {

  var productBtn = $('.hasPrev .activities__item-wrapper .activities__preview-button');
  var loader = "<div style='margin: auto;' class=\"loader\"></div>";
  var old_product_id = 'undefined';

  productBtn.on('click', function (e) {
    e.preventDefault();

    var thisContainer = $(this).closest('.activities__row');
    var thisPreview = thisContainer.parent().find('.activity-preview');
    var product_id = $(this).closest('.activities__item-wrapper').attr('data-id');

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
    scrollToAnchor(thisPreview);

    // Get data using ajax
    $.post(ajaxurl, {
      'action': 'sportigoo_get_preview',
      'product_id': product_id
    }, function (response) {

      // Parse Response
      response = JSON.parse(response);
      var _response = response,
          html = _response.html,
          video = _response.video,
          image = _response.image;
      // console.log({html, video, image})


      // Display HTML

      thisPreview.find('.loader').hide();
      thisPreview.find('.activity-preview__container').css('display', 'flex');
      thisPreview.find('.activity-preview__left').append(html);
      thisPreview.find('.activity-preview__title').addClass('orange');

      // Add image bg
      thisPreview.css('background-image', 'url(' + image + ')');

      // Add Video
      console.log(video[0]);
      if (video[0]) {
        thisPreview.find('.activity-preview__video').find('iframe').attr('src', video[0]);
      } else {
        thisPreview.find('.activity-preview__video-button').hide();
      }
    });

    old_product_id = product_id;
  });
};

var cleaner = function cleaner(thisPreview) {
  $('.activities__block .activity-preview').hide();
  thisPreview.find('.activity-preview__left').find('*').remove();
  thisPreview.find('.activity-preview__video-button').show();
  thisPreview.css('background-image', 'url()');
  thisPreview.find('.activity-preview__video').find('iframe').attr('src', "");
};

var scrollToAnchor = function scrollToAnchor(elt) {
  $('html,body').animate({ scrollTop: elt.offset().top - 65 }, 'slow');
};

var modalNewsletter = function modalNewsletter($) {

	$('form#mjForm').on('submit', function () {
		return setCookie('newsletter', 1, 365);
	});

	var newsletterCookie = getCookie('newsletter');

	if (!newsletterCookie) {

		var newsletter = $("#newsletter_modal");

		if (newsletter.length) {

			// setTimeout(() => newsletter.modal(), 12000);

			newsletter.on($.modal.CLOSE, function () {
				return setCookie('newsletter', 1, 30);
			});
		}
	}
};

var woocommerce = function woocommerce($) {
	var body = $('body');

	cleanWCString();
	body.on('updated_checkout', function () {
		cleanWCString();
	});

	function cleanWCString() {

		// Cart & Checkout
		var texts = body.find('.variation dt');
		var texts2 = body.find('.woocommerce-table__product-name .wc-item-meta .wc-item-meta-label');

		texts.each(cleanup);
		texts2.each(cleanup);

		function cleanup() {
			var copy = true;
			var text = $(this).text().split('').filter(function (i) {
				if (i === '(') {
					copy = false;
					i = '';
				} else if (i === ')') {
					copy = true;
					i = '';
					return false;
				}
				return copy;
			});
			$(this).text(text.join(''));
		}
	}
};

// Document ready
jQuery(function ($) {

	customSelect($);
	cookie($);
	navbar($);
	activitiesPreview($);
	smoothScroll($);
	modal($);
	carousel($);
	activities($);
	lastPosts($);
	respNavigation($);
	faq($);
	modalNewsletter($);
	searchActivity($);
	woocommerce($);
	ajaxSearch($);
});

}());
