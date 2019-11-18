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
		if (typeof group.cookies === 'object') {
			group.cookies.map(cookie => {
				let myCookie = new EssentialCookie(cookie, root);
				this.state.cookies.push(myCookie);
			});
		}
	}
	toggle(){
		this.state.active.value = true;
	}

}
