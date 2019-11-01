const modal = $ => {
  jQuery(function($) {
    var popup = $('.activity-preview__video')
    var popupContainer = $('.activity-preview__video-container')
    var buttonOpen = $('.activity-preview__video-button, .border_orange__center')
    var buttonClose = $('.activity-preview__close-video')
    var tl = new TimelineLite()

    buttonOpen.click(() => openModal())
    buttonClose.click(() => closeModal())

    popup.on('click', function(e) {
      if (popup.has(e.target).length === 0) {
        closeModal()
      }
    })

    function closeModal() {
      tl.to(popupContainer, 0.3, {
        y: '-1000px',
        opacity: 0,
      })
      tl.to(popup, 0.3, {
        scale: 0,
        opacity: 0,
        delay: 0.4,
      })
    }

    function openModal() {
      tl.to(popup, 0.3, {
        scale: 1,
        opacity: 1,
      })
      tl.to(popupContainer, 0.6, {
        opacity: 1,
        y: 0,
        delay: 0.4,
      })
    }
  })
}

export { modal }
