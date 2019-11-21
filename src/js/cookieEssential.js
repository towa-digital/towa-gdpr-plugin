import TowaGdprCookie from "./cookie";

export default class EssentialCookie extends TowaGdprCookie{
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
	}
	setActive(){
		this.state.active.value = true;
	}
}
