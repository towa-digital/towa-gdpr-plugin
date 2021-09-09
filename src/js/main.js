import './polyfills'
import axios from 'axios'
import Cookies from 'js-cookie'
import CookieGroup from './cookiegroup'
import Observable from './observable'
import { convertHexColorToRgbString, setCssClass, deleteAllCookies } from './helpers'
import EssentialCookieGroup from './cookiegroupEssential'

class TowaGdprPlugin {
  constructor () {
    this.refs = {
      triggerPopupLinks: document.querySelectorAll('.Towa-Gdpr-Link'),
      root: document.getElementById('Towa-Gdpr-Plugin'),
      myScriptContainer: document.getElementById('TowaGdprScripts')
    }
    // eslint-disable-next-line no-undef
    this.context = towaGdprContext
    this.state = {
      accepted: this.isGdprAccepted()
    }
    if (this.context.settings.cookie_groups instanceof Object) {
      this.state.cookieGroups = this.context.settings.cookie_groups.map((group, index) => {
        return new CookieGroup(group, this.refs.root)
      })
    }
    if (this.context.settings.essential_group instanceof Object) {
      const group = new EssentialCookieGroup(this.context.settings.essential_group, this.refs.root, false)
      this.state.cookieGroups.push(group)
    }
    this.init()
  }

  isGdprAccepted () {
    return (Cookies.get('GdprAccepted') === this.context.settings.hash)
  }

  init () {
    this.applySettings()
    this.defineObservables()
    this.setListeners()
    if (!this.isNoCookiePage()) {
      this.render()
      this.renderScripts()
    }
  }

  defineObservables () {
    this.state.accepted = new Observable(this.state.accepted, this.refs.root)
    this.refs.root.addEventListener('render', () => {
      this.render()
    })
  }

  render () {
    setCssClass(this.refs.root, 'show', !this.state.accepted.value)
  }

  applySettings () {
    if (this.context.settings.highlight_color) {
      const highlightColorLight = convertHexColorToRgbString(this.context.settings.highlight_color, 0.1)
      this.refs.root.style.setProperty('--highlightcolorLight', highlightColorLight)
      this.refs.root.style.setProperty('--highlightcolor', this.context.settings.highlight_color)
    }
  }

  acceptAll () {
    this.state.cookieGroups.forEach(group => {
      group.acceptWholeGroup()
    })
    this.accept()
  }

  renderScripts () {
    const scriptEl = document.createElement('script')
    this.state.cookieGroups.forEach(group => {
      group.state.cookies.forEach((cookie) => {
        if (cookie.state.active.value === true) {
          scriptEl.innerText += cookie.state.javascript
        }
      })
    })

    this.refs.myScriptContainer.innerHTML = ''
    this.refs.myScriptContainer.appendChild(scriptEl)
  }

  accept () {
    this.state.accepted.value = true
    Cookies.set('GdprAccepted', this.context.settings.hash, {
      expires: parseInt(this.context.settings.cookieTime),
      sameSite: 'lax'
    })
    this.renderScripts()
    this.saveConsent()
  }

  save () {
    this.state.cookieGroups.forEach((group) => {
      group.saveWholeGroup()
    })
    this.accept()
  }

  saveConsent () {
    const cookies = this.state.cookieGroups.flatMap((group) => {
      return group.getCookiesForLog()
    })
    axios({
      method: 'post',
      url: this.context.settings.consent_url,
      data: {
        url: window.location.href,
        hash: this.context.settings.hash,
        config: cookies
      }
    }).catch((error) => {
      return error
    })
  }

  declineAll () {
    deleteAllCookies()
    this.state.cookieGroups.forEach((group) => {
      group.declineWholeGroup()
    })
    this.accept()
  }

  setListeners () {
    this.refs.root.querySelector('.Towa-Gdpr-Plugin__save').addEventListener('click', () => {
      this.save()
    })
    this.refs.root.querySelector('.Towa-Gdpr-Plugin__accept-all').addEventListener('click', () => {
      this.acceptAll()
    })
    this.refs.root.querySelector('.Towa-Gdpr-Plugin__decline-all').addEventListener('click', () => {
      this.declineAll()
    })
    this.refs.triggerPopupLinks.forEach((link) => {
      link.addEventListener('click', () => {
        this.state.accepted.value = false
      })
    })
  }

  isNoCookiePage () {
    return (document.querySelector('meta[name="towa-gdpr-no-cookies"]') !== null)
  }
}

document.addEventListener('DOMContentLoaded', () => {
  // eslint-disable-next-line
  const towagdpr = new TowaGdprPlugin()
})
