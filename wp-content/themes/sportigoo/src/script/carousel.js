const carousel = $ => {
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
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 4,
        },
      },
      {
        breakpoint: 750,
        settings: {
          slidesToShow: 3,
        },
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 2,
        },
      },
    ],
  })

  const laws_slider = $('.carousel__laws')
  const prevArrow = laws_slider.parent().find('.carousel__prev')
  const nextArrow = laws_slider.parent().find('.carousel__next')

  laws_slider.slick({
    autoplay: false,
    autoplaySpeed: 5000,
    dots: false,
    infinite: true,
    speed: 500,
    slidesToShow: 4,
    prevArrow: prevArrow,
    nextArrow: nextArrow,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
        },
      },
      {
        breakpoint: 750,
        settings: {
          slidesToShow: 2,
        },
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
        },
      },
    ],
  })

  // Dynamic multi carousel
  $('.carousel').each(function(key) {
    let sliderIdName = 'slider-' + key
    this.id = sliderIdName
    let SliderId = '#' + sliderIdName

    let prevArrow = $(this)
      .parent()
      .find('.carousel__prev')
    let nextArrow = $(this)
      .parent()
      .find('.carousel__next')

    $(SliderId).slick({
      autoplay: true,
      autoplaySpeed: 5000,
      dots: false,
      infinite: true,
      speed: 500,
      slidesToShow: 1,
      prevArrow: prevArrow,
      nextArrow: nextArrow,
    })
  })

  let responsive = [
    {
      breakpoint: 999,
      settings: {
        slidesToShow: 1,
        infinite: true,
        autoplay: true,
        centerMode: false,
      },
    },
  ]

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
    responsive: responsive,
  })

  // AVIS
  const avisCar = $('.avis__carousel')
  const avisCount = Number(avisCar.attr('data-count'))

  let avisArgs = {
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
    nextArrow: $('.avis__next'),
  }

  if (avisCount < 4) {
    avisArgs.slidesToShow = 1
    // avisArgs.infinite = false;
    avisArgs.centerMode = false
    avisArgs.centerPadding = '0'
    avisArgs.arrow = false
  } else {
    avisArgs.slidesToShow = 3
    avisArgs.infinite = true
    avisArgs.centerMode = true
    avisArgs.centerPadding = '-30px'
  }

  avisCar.slick(avisArgs)

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
    nextArrow: $('.recents_slider__carousel__next'),
  })
}
export { carousel }
