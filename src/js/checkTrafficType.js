(function (d) {
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
      }
    }
  }
  xhr.send(null);
})(document)
