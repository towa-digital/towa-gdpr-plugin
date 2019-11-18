import TowaDsgvoCookie from "./cookie";

export default class EssentialCookie extends TowaDsgvoCookie{
	constructor(cookie, root) {
		super(cookie,root);
	}
	isCookieActive(){
		return true;
	}
	toggle(){
		this.state.active.value = true;
	}
	decline(){
		this.state.active.value = true;
		Cookies.set(this.state.name, true, towaDsgvoContext.settings.cookieTime);
	}
	setActive(){
		this.state.active.value = true;
	}
}
