import Cookie from './cookie'
import Observable from './observable'

export default class CookieGroup {
  constructor (group, root) {
    const domEl = root.querySelector(`[data-groupname="${group.title}"]`)
    this.state = {
      id: domEl.closest('li').getAttribute('aria-controls'),
      cookies: [],
      active: false
    }
    this.ref = {
      root: root,
      domEl: domEl,
      li: domEl.closest('li'),
      accordionBtn: domEl.closest('li').querySelector('.Towa-Gdpr-Plugin__accordion')
    }
    this.toggleGroupClickedEvent = new CustomEvent('toggleGroupClicked', { detail: { id: this.state.id } })
    this.getCookies(group)
    this.init()
  }

  getCookies (group) {
    if (group.cookies instanceof Object) {
      this.state.cookies = group.cookies.map(cookie => {
        return new Cookie(cookie, this.ref.root)
      })
    }
  }

  init () {
    this.state.active = this.isGroupActive()
    this.defineObservables()
    this.setListeners()
    this.render()
  }

  defineObservables () {
    this.state.active = new Observable(this.state.active, this.ref.domEl)
  }

  isGroupActive () {
    return !!this.state.cookies.find(cookie => !!cookie.state.active.value)
  }

  render () {
    this.ref.domEl.checked = this.state.active.value
  }

  toggle () {
    this.state.active.value = !this.state.active.value
    this.state.cookies.forEach(cookie => {
      cookie.setActive(this.state.active.value, false)
    })
  }

  setListeners () {
    this.ref.domEl.addEventListener('render', () => {
      this.render()
    })
    this.ref.domEl.addEventListener('click', (event) => {
      this.toggle()
    })
    this.ref.root.addEventListener('cookieChanged', () => {
      this.state.active.value = this.isGroupActive()
    })
    this.ref.li.addEventListener('click', () => {
      this.ref.root.dispatchEvent(this.toggleGroupClickedEvent, this.state.id)
    })
    
    if(this.ref.accordionBtn){
      this.ref.accordionBtn.addEventListener('click', () => {
        console.log(towaGdprContext.settings.activate_accordion)
        this.ref.accordionBtn.classList.toggle('active')
        const panel = this.ref.li.querySelector('.Towa-Gdpr-Plugin__group-panel')
        panel.style.maxHeight = panel.style.maxHeight ? null : panel.scrollHeight + "px";
      })
    }
   
    
  }

  acceptWholeGroup () {
    this.state.cookies.forEach((cookie) => {
      cookie.accept()
    })
  }

  declineWholeGroup () {
    this.state.cookies.forEach((cookie) => {
      cookie.decline()
    })
  }

  saveWholeGroup () {
    this.state.cookies.forEach((cookie) => {
      cookie.save()
    })
  }

  getCookiesForLog () {
    return this.state.cookies.map(cookie => {
      return cookie.getCookieForLog()
    })
  }
}
