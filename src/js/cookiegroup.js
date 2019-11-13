import Cookie from './cookie';

export default class CookieGroup{
	constructor(group,root,display){
		this.state = {
			id: root.querySelector(`[data-groupname="${group.title}"]`).closest('li').getAttribute('aria-controls'),
			cookies: [],
			display: display,
			active: false,
			self: this
		}
		this.ref = {
			root: root,
			domEl: root.querySelector(`[data-groupname="${group.title}"]`),
			li: root.querySelector(`[data-groupname="${group.title}"]`).closest('li'),
			panel: root.querySelector(`#${this.state.id}`)
		}
		this.toggleGroupClickedEvent = new CustomEvent('toggleGroupClicked',{detail:{id:this.state.id}});

		if(typeof group.cookies === 'object'){
			group.cookies.map(cookie => {
				let myCookie = new Cookie(cookie,root);
				this.state.cookies.push(myCookie);
			})
		}
		this.init();
	}

	setUpProxyVariables(){
		this.state = new Proxy(this.state, {
			get(target, key) {
				return target[key];
			},
			set(obj, prop, value) {
					let returnValue = Reflect.set(...arguments);;
					if ((prop === 'display') || prop === 'active'){
						obj.self.render();
					}
					return returnValue;
				}
		});
	}

	init(){
		this.setUpProxyVariables();
		this.state.active = this.isGroupActive();
		this.setUpListeners();
		this.render();
	}

	isGroupActive(){
		return (this.state.cookies.filter((cookie) => { return cookie.state.active === true }).length > 0);
	}

	setCssClass(element,className,state){
		if(!state){
			element.classList.remove(className);
		}
		else if (!element.classList.contains(className) && state === true){
			element.classList.add(className);
		}
	}

	render(){
		this.ref.domEl.checked = this.state.active;
		this.setCssClass(this.ref.panel,'active',this.state.display);
		this.setCssClass(this.ref.li,'active', this.state.display);
	}

	toggle(){
		this.state.active = !this.state.active;
		this.state.cookies.forEach(cookie=>{
			cookie.setActive(this.state.active);
		});
	}

	setUpListeners(){
		this.ref.domEl.addEventListener('click', (event) => {
			this.toggle();
		});
		this.ref.root.addEventListener('cookieChanged',()=> {
			this.state.active = this.isGroupActive();
		});
		this.ref.li.addEventListener('click',()=>{
			this.ref.root.dispatchEvent(this.toggleGroupClickedEvent,this.state.id);
			this.state.display = true;
		});
		this.ref.root.addEventListener('toggleGroupClicked',(event)=>{
			if (this.state.id !== event.detail.id){
				this.state.display = false;
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
