
import Cookies from 'js-cookie';
import Cookie from './cookie';

export default class TowaDsgvoCookie {
	constructor(cookie,root){
		this.state = {};
		({
			description: this.state.description,
			link: this.state.link,
			javascript: this.state.javascript
		} = cookie);

		this.state.name = this.state.link.title;

		this.state = {...this.state, ...{
			self: this,
			active: this.isCookieActive(),
		}};

		this.ref = {
			root: root,
			domEl: root.querySelector(`[data-cookiename="${this.state.name}"]`)
		}
		this.changeEvent = new Event('cookieChanged');
		this.init();
	}

	setUpProxyVariables(){
		this.state = new Proxy(this.state,{
			get(target, key) {
				return target[key];
			},
			set(obj, prop, value) {
				let returnValue = Reflect.set(...arguments);;
				if (prop === 'active') {
					obj.self.render();
				}
				return returnValue;
			}
		});
	}

	init(){
		this.setUpProxyVariables();
		this.setUpListeners();
		this.render();
	}

	render(){
		this.ref.domEl.checked = this.state.active;
	}

	setUpListeners(){
		this.ref.domEl.addEventListener('click',()=>{
			this.toggle();
		});
	}

	toggle(){
		this.state.active = !this.state.active;
		this.ref.root.dispatchEvent(this.changeEvent);
	}

	isCookieActive(){
		return (Cookies.get(this.state.name) === 'true') ? true : false;
	}

	accept(){
		this.state.active = true;
		Cookies.set(this.state.name,true,towaDsgvoContext.settings.cookieTime);
		this.ref.root.dispatchEvent(this.changeEvent);
	}

	decline(){
		this.state.active = false;
		Cookies.set(this.state.name,false,towaDsgvoContext.settings.cookieTime);
		this.ref.root.dispatchEvent(this.changeEvent);
	}

	save(){
		this.state.active === true ? Cookies.set(this.state.name, true, towaDsgvoContext.settings.cookieTime) : Cookies.set(this.state.name, false, towaDsgvoContext.settings.cookieTime);;
	}

	setActive(value){
		this.state.active = value;
	}

}
