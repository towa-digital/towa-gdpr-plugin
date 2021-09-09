export function convertHexColorToRgbString (hex, opacity) {
  hex = hex.replace('#', '')

  const colorChannels = Object.values({
    r: parseInt(hex.substring(0, 2), 16),
    g: parseInt(hex.substring(2, 4), 16),
    b: parseInt(hex.substring(4, 6), 16),
    a: opacity
  }).join(',')

  return `rgba(${colorChannels})`
}

export function setCssClass (element, className, state) {
  if (!state) {
    element.classList.remove(className)
  } else if (!element.classList.contains(className) && state === true) {
    element.classList.add(className)
  }
}

export function deleteAllCookies () {
  const cookies = document.cookie.split(';')
  cookies.forEach(cookie => {
    const equalPos = cookie.indexOf('=')
    const name = equalPos > -1 ? cookie.substr(0, equalPos) : cookie
    document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT'
  })
}
