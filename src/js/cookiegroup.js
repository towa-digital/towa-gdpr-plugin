import Cookie from './cookie';
import Observable from './observable';
import {setCssClass} from './helpers';
export default class CookieGroup{
	constructor(group,root,display){
		let domEl = root.querySelector(`[data-groupname="${group.title}"]`);
		this.state = {
			id: domEl.closest('li').getAttribute('aria-controls'),
			cookies: [],
			display: display,
			active: false,
		}
		this.ref = {
			root: root,
			domEl: domEl,
			li: domEl.closest('li'),
			panel: root.querySelector(`#${this.state.id}`),
			panelMobile: root.querySelector(`#${this.state.id}-mobile`)
		}
		this.toggleGroupClickedEvent = new CustomEvent('toggleGroupClicked',{detail:{id:this.state.id}});
		this.getCookies(group,root);
		this.init();
	}

	getCookies(group,root){
		if (group.cookies instanceof Object) {
			this.state.cookies = group.cookies.map(cookie => {
				return new Cookie(cookie, root);
			});
		}
	}

	init(){
		this.state.active = this.isGroupActive();
		this.defineObservables();
		this.setUpListeners();
		this.render();
	}

	defineObservables(){
		this.state.display = new Observable(this.state.display, this.ref.domEl);
		this.state.active = new Observable(this.state.active,this.ref.domEl);
	}

	isGroupActive(){
		return !!this.state.cookies.find(cookie => !!cookie.state.active.value);
	}

	render(){
		this.ref.domEl.checked = this.state.active.value;
		setCssClass(this.ref.panel,'active',this.state.display.value);
		setCssClass(this.ref.panelMobile,'active',this.state.display.value);
		setCssClass(this.ref.li,'active', this.state.display.value);
	}

	toggle(){
		this.state.active.value =  !this.state.active.value;
		this.state.cookies.forEach(cookie => {
			cookie.setActive(this.state.active.value,false);
		});
	}

	setUpListeners(){
		this.ref.domEl.addEventListener('render', () => {
			this.render();
		});
		this.ref.domEl.addEventListener('click', (event) => {
			this.toggle();
		});
		this.ref.root.addEventListener('cookieChanged',()=> {
			this.state.active.value =  this.isGroupActive();
		});
		this.ref.li.addEventListener('click',()=>{
			this.ref.root.dispatchEvent(this.toggleGroupClickedEvent,this.state.id);
			this.state.display.value = true;
		});
		this.ref.root.addEventListener('toggleGroupClicked',(event)=>{
			if (this.state.id !== event.detail.id){
				this.state.display.value = false;
			}
		});
	}

	acceptWholeGroup(){
		this.state.cookies.forEach((cookie)=>{
			cookie.accept();
		});
	}

	declineWholeGroup(){
		this.state.cookies.forEach((cookie)=>{
			cookie.decline();
		});
	}

	saveWholeGroup(){
		this.state.cookies.forEach((cookie)=>{
			cookie.save();
		});
	}
}