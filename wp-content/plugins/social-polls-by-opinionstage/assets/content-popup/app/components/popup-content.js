import Vue from 'vue'
import store from '../store.js'
import RSVP from 'rsvp'

import _ from 'lodash'
import JsonApi from '../../lib/jsonapi.js'

export default Vue.component('popup-content', {
  template: '#opinionstage-popup-content',

  props: [
    'modalIsOpened',
    'showClientContent',
    'clientIsLoggedIn',
    'clientWidgetsUrl',
    'clientWidgetsHasNewUrl',
    'sharedWidgetsUrl',
    'accessKey',
    'pluginVersion',
  ],

  data () {
    return {
      dataLoading: false,
      widgets: [],
      searchCriteria: {},
      noMoreData: false,
      newWidgetsAvailable: false,
      lastUpdateTime: null,
      isCheckingWidgetUpdates: false,
      widgetUpdatesChecker: null,
    }
  },

  mounted () {
    startWidgetUpdatesChecking.call(this)
  },

  store,

  methods: {
    reloadData ({ widgetType, widgetTitle }) {
      this.searchCriteria = { page: 1, perPage: 9, type: widgetType, title: widgetTitle }
      this.$store.commit('clearWidgets')

      loadData.call(this, this.searchCriteria).then( () => {
        this.widgets = this.$store.state.widgets[0]
        this.noMoreData = !hasNextPage(this.$store.state.nextPageNumber)

        if ( !this.searchCriteria.title ) {
          setLastUpdateTimeFromWidget.call(this)
        }
      })
    },

    appendData () {
      this.searchCriteria.page += 1

      loadData.call(this, this.searchCriteria).then( () => {
        const newWidgets = this.$store.state.widgets[this.searchCriteria.page-1]
        this.noMoreData = !hasNextPage(this.$store.state.nextPageNumber)
        this.widgets = this.widgets.concat( newWidgets )
      })
    },

    insertShortcode (shortcode) {
      this.$emit('insert-shortcode', shortcode)
    },

    checkWidgetUpdates ({ widgetType }) {
      pullWidgetsUpdateInformation.call(this, widgetType, this.lastUpdateTime).then( () => {
        if ( this.newWidgetsAvailable ) {
          stopWidgetUpdatesChecking.call(this)
        }
      })
    },

    startWidgetUpdatesChecker() {
      this.newWidgetsAvailable = false
      startWidgetUpdatesChecking.call(this)
    },
  },

  watch: {
    modalIsOpened: function(newState){
      if ( newState && this.showClientContent && this.clientIsLoggedIn ) {
        refreshContent.call(this)
        startWidgetUpdatesChecking.call(this)
      } else {
        this.newWidgetsAvailable = false
        stopWidgetUpdatesChecking.call(this)
      }
    },

    showClientContent: function(newState){
      if ( newState && this.modalIsOpened && this.clientIsLoggedIn ) {
        startWidgetUpdatesChecking.call(this)
      } else {
        stopWidgetUpdatesChecking.call(this)
      }
    },
  },
})

function loadData (searchCriteria) {
  this.dataLoading = true

  const load = this.showClientContent ? loadClientWidgets : loadTemplateWidgets

  return load.call(this, searchCriteria).then( () => {
    this.dataLoading = false
  })
}

function loadClientWidgets (filtering) {
  if ( this.clientIsLoggedIn ) {
    return this.$store.dispatch({
      type: 'loadClientWidgets',
      widgetsUrl:    this.clientWidgetsUrl,
      pluginVersion: this.pluginVersion,
      accessToken:   this.accessKey,
      filtering,
    })
  } else {
    return RSVP.resolve()
  }
}

function loadTemplateWidgets (filtering) {
  return this.$store.dispatch({
    type: 'loadTemplateWidgets',
    widgetsUrl:    this.sharedWidgetsUrl,
    pluginVersion: this.pluginVersion,
    filtering,
  })
}

function withParams(url, type, time) {
  const urlParams = []
  if ( type ) {
    urlParams.push( `type=${type}` )
  }

  if ( time ) {
    urlParams.push( `updated_at=${time}` )
  }

  if ( _.isEmpty(urlParams) ) {
    return url
  } else {
    return url + '?' + _.join( urlParams, '&')
  }
}

function pullWidgetsUpdateInformation(type, updatedAt){
  const url = withParams(this.clientWidgetsHasNewUrl, type, updatedAt)

  return JsonApi.get(url, this.pluginVersion, this.accessKey)
        .then( (payload) => {
          this.newWidgetsAvailable = payload.data['has-new-widgets']
        })
        .catch( (error) => {
          console.error( "[social-polls-by-opinionstage][content-popup] can't load widgets:", error.statusText )
        })
}

function hasNextPage(nextPageNumber) {
  return nextPageNumber > 1
}

function startWidgetUpdatesChecking() {
  if ( this.clientIsLoggedIn ) {
    this.isCheckingWidgetUpdates = true
    this.widgetUpdatesChecker = setInterval(() => {
                           this.checkWidgetUpdates({
                             widgetType: this.searchCriteria.type,
                           })
                         }, 3000)
  }
}

function stopWidgetUpdatesChecking() {
  this.isCheckingWidgetUpdates = false
  clearInterval(this.widgetUpdatesChecker)
}

function setLastUpdateTimeFromWidget() {
  if (typeof this.widgets[0] !== 'undefined') {
    this.lastUpdateTime = this.widgets[0].updatedAt
  } else {
    this.lastUpdateTime = null
  }
}

function refreshContent() {
  if ( this.searchCriteria.type && this.searchCriteria.title ) {
    this.reloadData.call(this, {
      widgetType: this.searchCriteria.type,
      widgetTitle: this.searchCriteria.title
    })

  } else if ( this.searchCriteria.type ) {
    this.reloadData.call(this, {
      widgetType: this.searchCriteria.type,
      widgetTitle: ''
    })
  } else {
    this.reloadData.call({
      widgetType: 'all',
      widgetTitle: ''
    })
  }
}
