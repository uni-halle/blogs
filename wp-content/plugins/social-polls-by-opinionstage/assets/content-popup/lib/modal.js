import tingle from 'tingle.js'
import '../styles/modal-window.scss'

export default class Modal {
  constructor (settings={}) {
    this.modal = new tingle.modal({
      closeMethods: ['overlay', 'escape'],
      cssClass: ['opinionstage-content-popup'],
      onClose: settings.onClose,
      onOpen: settings.onOpen,
    })

    this.modal.setContent(settings.content)

    if ( typeof settings.onCreate === 'function' ) {
      settings.onCreate(this)
    }
  }

  open () { this.modal.open() }
  close () { this.modal.close() }
  checkOverflow () { this.modal.checkOverflow() }
}
