import Modal from './lib/modal.js'
import ContentPopup from './app/index.js'

jQuery(function($) {
  let app
  let modal

  if(window.location.href.indexOf("modal_is_open") > -1) {
    if ( modal === undefined ) {
      modal = new Modal({
        content: $('[data-opinionstage-content-popup-template]').html(),
        onCreate (modal) {
          app = new ContentPopup(modal)
        },
      })
    }

    modal.open()
  }

  $('body').on('click', '[data-opinionstage-content-launch]', function (event) {
    event.preventDefault()

    if ( modal === undefined ) {
      modal = new Modal({
        content: $('[data-opinionstage-content-popup-template]').html(),
        onCreate (modal) {
          app = new ContentPopup(modal)
        },
        onClose (modal) {
          app.isModalOpened = false
        },
        onOpen (modal) {
          app.isModalOpened = true
        }
      })
    }

    modal.open()
  })
})
