import Cookies from 'js-cookie'
import Observable from './observable'
import { setCssClass } from './helpers'

export default class TowaGdprCookie {
  constructor (cookie, root) {
    this.state = { ...cookie }
    this.state.active = this.isCookieActive()

    this.ref = {
      root: root,
      domEls: root.querySelectorAll(`[data-cookiename="${this.state.name}"]`),
      domEl: root.querySelector(`[data-cookiename="${this.state.name}"]`),
      listEls: Array.from(root.querySelectorAll(`[data-cookiename="${this.state.name}"]`)).map(item => item.closest('li'))
    }

    this.changeEvent = new Event('cookieChanged')
    this.init()
  }

  init () {
    this.defineObservables()
    this.setListeners()
    this.render()
  }

  render () {
    this.ref.domEl.checked = this.state.active.value
    this.ref.domEls.forEach(domel => {
      domel.checked = this.state.active.value
    })
    this.ref.listEls.forEach(listelement => {
      setCssClass(listelement, 'active', this.state.active.value)
    })
  }

  defineObservables () {
    this.state.active = new Observable(this.state.active, this.ref.domEl)
    this.ref.domEls.forEach(domEl => {
      domEl.addEventListener('render', () => {
        this.render()
      })
    })
  }

  setListeners () {
    this.ref.domEls.forEach(domEl => {
      domEl.addEventListener('click', () => {
        this.toggle()
      })
    })
  }

  toggle () {
    this.setActive(!this.state.active.value)
  }

  isCookieActive () {
    return !!(Cookies.get(this.state.name) === 'true')
  }

  accept () {
    this.setActive(true)
  }

  decline () {
    this.setActive(false)
  }

  save () {
    Cookies.set(this.state.name, !!this.state.active.value, {
      expires: parseInt(towaGdprContext.settings.cookieTime),
      sameSite: 'lax'
    })
  }

  setActive (value, notifyRoot = true) {
    this.state.active.value = value
    this.ref.domEl.dispatchEvent(this.changeEvent)
    if (notifyRoot) {
      this.ref.root.dispatchEvent(this.changeEvent)
    }
    Cookies.set(this.state.name, value, {
      expires: parseInt(towaGdprContext.settings.cookieTime),
      sameSite: 'lax'
    })
  }

  getCookieForLog () {
    return { [this.state.name]: this.state.active.value }
  }
}
