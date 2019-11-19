
import Cookies from 'js-cookie';
import Observable from './observable';
import {setCssClass} from './helpers';
export default class TowaDsgvoCookie {
	constructor(cookie,root){
		this.state = {};

		({
			description: this.state.description,
			link: this.state.link,
			javascript: this.state.javascript,
			name: this.state.name
		} = cookie);

		this.state.active = this.isCookieActive();

		this.ref = {
			root: root,
			domEls: root.querySelectorAll(`[data-cookiename="${this.state.name}"]`),
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
		this.ref.domEls.forEach(domel => {
			domel.checked = this.state.active.value;
		});
		setCssClass(this.ref.listEl,'active',this.state.active.value);
	}

	defineObservables(){
		this.state.active = new Observable(this.state.active, this.ref.domEl),
		this.ref.domEls.forEach(domEl=>{
			domEl.addEventListener('render', () => {
				this.render();
			})
		});
	}

	setUpListeners(){
		this.ref.domEls.forEach(domEl => {
			domEl.addEventListener('click',()=>{
				this.toggle();
			});
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
