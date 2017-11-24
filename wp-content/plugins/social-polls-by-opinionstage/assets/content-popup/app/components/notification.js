import Vue from 'vue'

export default Vue.component('notification', {
  template: '#opinionstage-notification',

  props: [
    'widgetType',
  ],

  methods: {
    reload () {
      this.$emit('hide')
      this.$emit('reload', {
        widgetType: this.widgetType
      })
    },
  }
})
