import CookieGroup from "./cookiegroup"
import EssentialCookie from "./cookieEssential";

export default class EssentialCookieGroup  extends CookieGroup {
	constructor(group, root, display){
		super(group, root, display);
	}

	isGroupActive(){
		return true;
	}

	getCookies(group,root) {
		if (group.cookies instanceof Object) {
			this.state.cookies = group.cookies.map(cookie => {
				return new EssentialCookie(cookie, root);
			});
		}
	}
	toggle(){
		this.state.active.value = true;
	}

}
