const activities = $ => {

	let slider = $('.activitiesSlider');
	let padding = (slider.width() - 1140) / 2;

	if ($('body').hasClass('home')) {
		// Padding based on container size


		//Slider
		slider.slick({
			dots: false,
			infinite: true,
			speed: 300,
			autoplay: true,
			slidesToShow: 5,
			centerMode: true,
			centerPadding: padding,
			prevArrow:
				'<div class="carousel__prev"><svg width="20" height="20"><use xlink:href="#chevron-left"></use></svg></div>',
			nextArrow:
				'<div class="carousel__next"><svg width="20" height="20"><use xlink:href="#chevron-right"></use></svg></div>',

			responsive: [
				{
					breakpoint: 999,
					settings: {
						slidesToShow: 3,
					},
				},
				{
					breakpoint: 767,
					settings: {
						slidesToShow: 2,
					},
				},
				{
					breakpoint: 480,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1,
					},
				},
			],
		});
	} else {
		// Dynamic multi carousel
		$('.activitiesSlider').each(function (key, item) {

			let sliderIdName = 'slider-' + key;
			this.id = sliderIdName;
			let SliderId = '#' + sliderIdName;
			let slideCount = $(this).find('article').length;
			let centerMode = true;
			if (slideCount < 5) {
				centerMode = false;
				$(SliderId).addClass('not-all-slide')
			}

			let prevArrow = $(this).parent().find('.carousel__prev');
			let nextArrow = $(this).parent().find('.carousel__next');

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

				responsive: [
					{
						breakpoint: 999,
						settings: {
							slidesToShow: 3,
						},
					},
					{
						breakpoint: 767,
						settings: {
							slidesToShow: 2,
						},
					},
					{
						breakpoint: 480,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1,
						},
					},
				],
			})
		})
	}






  $('.categoriesSlider').slick({
    infinite: true,
    speed: 300,
    slidesToShow: 4,
		autoplay: true,
    centerMode: true,
    centerPadding: padding,
    prevArrow:
      '<div class="carousel__prev"><svg width="20" height="20"><use xlink:href="#chevron-left"></use></svg></div>',
    nextArrow:
      '<div class="carousel__next"><svg width="20" height="20"><use xlink:href="#chevron-right"></use></svg></div>',
    dots: false,
    responsive: [
      {
        breakpoint: 1200,
        settings: {
          slidesToShow: 3,
        },
      },
      {
        breakpoint: 999,
        settings: {
          slidesToShow: 2,
        },
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  })


}
export { activities }
