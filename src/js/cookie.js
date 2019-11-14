
import Cookies from 'js-cookie';
import Cookie from './cookie';
import Observable from './observable';
export default class TowaDsgvoCookie {
	constructor(cookie,root){
		this.state = {};
		({
			description: this.state.description,
			link: this.state.link,
			javascript: this.state.javascript
		} = cookie);

		this.state = {
			...this.state, ...{
				active: this.isCookieActive(),
				name: this.state.link.title
			}
		};

		this.ref = {
			root: root,
			domEl: root.querySelector(`[data-cookiename="${this.state.name}"]`),
			listEl: root.querySelector(`[data-cookiename="${this.state.name}"]`).closest('li')
		}

		this.changeEvent = new Event('cookieChanged');
		this.init();
	}

	init(){
		this.defineObservables();
		this.setUpListeners();
		this.render();
	}

	render(){
		this.ref.domEl.checked = this.state.active.value;
		this.setCssClass(this.ref.listEl,'active',this.state.active.value);
	}

	setCssClass(element, className, state) {
		if (!state) {
			element.classList.remove(className);
		}
		else if (!element.classList.contains(className) && state === true) {
			element.classList.add(className);
		}
	}

	defineObservables(){
		this.state.active = new Observable(this.state.active, this.ref.domEl),
		this.ref.domEl.addEventListener('render', () => {
			this.render();
		});
	}

	setUpListeners(){
		this.ref.domEl.addEventListener('click',()=>{
			this.toggle();
		});
	}

	toggle(){
		this.state.active.value =  !this.state.active.value;
		this.ref.root.dispatchEvent(this.changeEvent);
	}

	isCookieActive(){
		return (Cookies.get(this.state.name) === 'true') ? true : false;
	}

	accept(){
		this.state.active.value = true;
		Cookies.set(this.state.name,true,towaDsgvoContext.settings.cookieTime);
		this.ref.root.dispatchEvent(this.changeEvent);
	}

	decline(){
		this.state.active.value =  false;
		Cookies.set(this.state.name,false,towaDsgvoContext.settings.cookieTime);
		this.ref.root.dispatchEvent(this.changeEvent);
	}

	save(){
		this.state.active.value === true ? Cookies.set(this.state.name, true, towaDsgvoContext.settings.cookieTime) : Cookies.set(this.state.name, false, towaDsgvoContext.settings.cookieTime);;
	}

	setActive(value){
		this.state.active.value =  value;
	}

}
