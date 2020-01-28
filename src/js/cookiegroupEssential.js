import CookieGroup from './cookiegroup'
import EssentialCookie from './cookieEssential'

export default class EssentialCookieGroup extends CookieGroup {
  isGroupActive () {
    return true
  }

  getCookies (group) {
    if (group.cookies instanceof Object) {
      this.state.cookies = group.cookies.map(cookie => {
        return new EssentialCookie(cookie, this.ref.root)
      })
    }
  }

  toggle () {
    this.state.active.value = true
  }
}
