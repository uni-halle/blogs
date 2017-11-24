import { Promise } from 'rsvp'

const $ = jQuery
const APIJSON_CONTENT_TYPE = 'application/vnd.api+json'

export default {
  get (url, pluginVersion, accessToken) {
    return new Promise( (resolve, reject) => {
      $.getJSON({
        url,
        beforeSend: (request) => {
          request.setRequestHeader('Accept', APIJSON_CONTENT_TYPE)
          request.setRequestHeader('Content-Type', APIJSON_CONTENT_TYPE)
          request.setRequestHeader('OSWP-Plugin-Version', pluginVersion)
          if ( accessToken ) {
            request.setRequestHeader('OSWP-Client-Token', accessToken)
          }
        },
      })
        .done( resolve/*(apiJson)*/ )
        .fail( reject/*(jqxhr, textStatus, error)*/ )
    })
  }
}
