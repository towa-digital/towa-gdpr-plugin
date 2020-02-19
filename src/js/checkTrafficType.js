(function (d, w) {
  // eslint-disable-next-line no-prototype-builtins
  if (!w.hasOwnProperty(w, 'dataLayer')) {
    w.dataLayer = [];
  }
  // eslint-disable-next-line no-undef
  const xhr = new XMLHttpRequest()
  xhr.open('GET', '/towa/gdpr/checkip', true)
  xhr.onload = function (e) {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        const data = JSON.parse(xhr.responseText)
        const tag = d.createElement('meta')
        tag.setAttribute('name', 'traffic-type')
        const trafficType = data.internal ? 'internal' : 'external'
        tag.setAttribute('content', trafficType)

        const metaElement = document.getElementsByTagName('head')[0]
        metaElement.insertBefore(tag, metaElement.childNodes[0])
        w.dataLayer.push({ 'traffic-type': trafficType })
        w.dataLayer.push({ event: 'trafficTypeLoaded' })
      } else {
        w.dataLayer.push({ event: 'trafficTypeLoaded' })
      }
    }
  }
  xhr.onerror = function(e) {
    w.dataLayer.push({ event: 'trafficTypeLoaded' })
  }
  xhr.send(null);
})(document, window)
