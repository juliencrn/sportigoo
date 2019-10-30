const faq = $ => {
  let items = $('.faq__item');
  items.on('click', function () {
    if ( !$(this).hasClass('active')) {
      items.removeClass('active')
      $(this).addClass('active')
    } else {
      items.removeClass('active')
    }
  })
}
export { faq }
