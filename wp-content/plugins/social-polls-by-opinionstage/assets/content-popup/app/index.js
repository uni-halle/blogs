import Vue from 'vue'
import './components/popup-content.js'
import './components/widget-list.js'
import './components/notification.js'
import '../styles/content-popup.scss'

export default function (modal) {
  return new Vue({
    el: '[data-opinionstage-content-popup]',

    data: {
      showClientContent: true,
      isClientLoggedIn: null,
      isModalOpened: true,
    },

    beforeMount () {
      this.isClientLoggedIn = this.$el.dataset.opinionstageClientLoggedIn === '1'
    },

    methods: {
      closePopup (/*event*/) {
        modal.close()
        this.isModalOpened = false
      },

      insertShortcode (shortcode) {
        wp.media.editor.insert(shortcode)
        this.closePopup()
        this.isModalOpened = false
      },

      showClientWidgets () {
        this.showClientContent = true
      },

      showTemplatesWidgets () {
        this.showClientContent = false
      },
    },
  })
}
