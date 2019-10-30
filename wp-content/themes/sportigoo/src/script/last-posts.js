const lastPosts = $ => {
  //change picture on hover
  var item = $('.last-posts__nav-item-wrapper')
  let excerptPreview = $('#excerptPreview');
  let linkPreview = $('#preview-link');

  item.on({
    mouseenter: function() {
      // Excerpt
      let excerpt = $(this).find('.last-posts__nav-excerpt').html();
      let postLink = $(this).find('a.last-posts__nav-item').attr('href');

      // Cats
      let cat = [];
      cat[0] = [
        $(this).find('.catList .cat-1').attr('data-name'),
        $(this).find('.catList .cat-1').attr('data-url')
      ];
      cat[1] = [
        $(this).find('.catList .cat-2').attr('data-name'),
        $(this).find('.catList .cat-2').attr('data-url')
      ];

      $('#catoutup-1')
        .html(cat[0][0])
        .attr('href', cat[0][1]);

      if (typeof cat[1][0] !== "undefined") {
        $('#catoutup-2')
          .show()
          .html(cat[1][0])
          .attr('href', cat[1][1]);
      } else {
        $('#catoutup-2').hide()
      }

      // console.log(cat);
      excerptPreview.html(excerpt)

      // Image BG
      $(this)
        .children('.last-posts__img')
        .css({
          zIndex: 1,
        })

      // Update link
      linkPreview.attr('href', postLink);

      // Active css class
      item.removeClass('active');
      $(this).addClass('active')
    },



    mouseleave: function() {
      // console.log('hover out')
      $(this)
        .children('.last-posts__img')
        .css({
          zIndex: 0,
        })
    },
  })
}
export { lastPosts }
