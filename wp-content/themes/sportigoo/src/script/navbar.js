const navbar = $ => {

	const navBlue = () => {
		$('.nav').css({
			background: 'rgba(29,70,122,1)',
			boxShadow: '3px 0 5px rgba(0,0,0,0.5)',
		}).removeClass('nav--topped');
		$('.nav li ul').css({
			background: 'rgba(29,70,122,1)',
		});
	};

	const navTran = () => {
		$('.nav').css({
			background: 'rgba(29,70,122,0)',
			boxShadow: '0px 0 5px rgba(0,0,0,0)',
		}).addClass('nav--topped');
		$('.nav li ul').css({
			background: 'rgba(29,70,122,0)',
		})
	};

	const toggleNavColor = () => {
    $(window).scrollTop() < 150 && $('.nav').hasClass('transparent')
      ? navTran()
      : navBlue();
  }

	// Hide when scroll down
	$(document).on('scroll', () => toggleNavColor());

	// Init, on load
	toggleNavColor();


	//Dropdown
	const dropdowns = $('.nav__links-container > li');
	if (window.screen < 999) {
		dropdowns.each(function () {
			$(this).click(function () {
				$(this).find('ul').addClass('active')
			}, function () {
				dropdowns.find('ul').removeClass('active')
			});
		});
	} else {
		dropdowns.each(function () {
			$(this).hover(function () {
				$(this).find('ul').addClass('active')
			}, function () {
				dropdowns.find('ul').removeClass('active')
			});
		});
	}

};

export {navbar}
