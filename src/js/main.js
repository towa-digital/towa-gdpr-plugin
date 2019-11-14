import './polyfills';
import Cookies from 'js-cookie';
import CookieGroup from './cookiegroup';
import Observable from './observable';
class TowaDsgvoPlugin{
	constructor(){
		this.refs = {
			triggerPopupLinks: document.querySelectorAll('.Towa-Dsgvo-Link'),
			root: document.getElementById('Towa-Dsgvo-Plugin'),
			myScriptContainer: document.getElementById('TowaDsgvoScripts')
		}
		this.context = towaDsgvoContext;
		this.state = {
			accepted: this.hasDsgvoAccepted(),
			self: this
		};
		if (typeof this.context.settings.cookie_groups === 'object'){
			this.cookieGroups = this.context.settings.cookie_groups.map((group, index)=>{
				return new CookieGroup(group,this.refs.root, (index === 0));
			});
		}
		this.init();
	}

	hasDsgvoAccepted(){
		return (Cookies.get('DsgvoAccepted') === 'true');
	}

	init(){
		this.defineObservables();
		this.applySettings();
		this.setUpListeners();
		this.render();
		this.renderScripts();
	}

	defineObservables(){
		this.state.accepted = new Observable(this.state.accepted,this.refs.root);
		this.refs.root.addEventListener('render',()=>{
			this.render();
		})
	}

	setCssClass(element, className, state) {
		if (!state) {
			element.classList.remove(className);
		}
		else if (!element.classList.contains(className) && state === true) {
			element.classList.add(className);
		}
	}

	render(){
		this.setCssClass(this.refs.root,'show', !this.state.accepted.value);
	}

	convertHexColorToRgbString(hex, opacity) {
		hex = hex.replace('#', '');
		let r = parseInt(hex.substring(0, 2), 16);
		let g = parseInt(hex.substring(2, 4), 16);
		let b = parseInt(hex.substring(4, 6), 16);

		return 'rgba(' + r + ',' + g + ',' + b + ',' + opacity + ')';
	}

	applySettings(){
		if(this.context.settings.highlight_color){
			let highlight_color_light = this.convertHexColorToRgbString(this.context.settings.highlight_color,0.1);
			this.refs.root.style.setProperty('--highlightcolorLight',highlight_color_light);
			this.refs.root.style.setProperty('--highlightcolor', this.context.settings.highlight_color);
		}
	}

	acceptAll(){
		this.cookieGroups.forEach(group=> {
			group.acceptWholeGroup();
		});
		this.accept();
	}

	renderScripts(){
		let scriptEl = document.createElement('script');
		this.cookieGroups.forEach(group => {
			group.state.cookies.forEach((cookie)=>{
				if (cookie.state.active.value === true){
					scriptEl.innerText += cookie.state.javascript;
				}
			})
		});

		this.refs.myScriptContainer.innerHTML = '';
		this.refs.myScriptContainer.appendChild(scriptEl);
	}

	accept(){
		this.state.accepted.value = true;
		Cookies.set('DsgvoAccepted',true,this.context.settings.cookieTime);
		this.renderScripts();
	}

	save(){
		this.cookieGroups.forEach((group)=>{
			group.saveWholeGroup();
		})
		this.accept();
	}

	declineAll(){
		this.deleteAllCookies();
		this.cookieGroups.forEach((group)=>{
			group.declineWholeGroup();
		});
		this.accept()
	}

	setUpListeners(){
		this.refs.root.querySelector('.Towa-Dsgvo-Plugin__save').addEventListener('click',()=>{
			this.save();
		});
		this.refs.root.querySelector('.Towa-Dsgvo-Plugin__accept-all').addEventListener('click',()=>{
			this.acceptAll();
		});
		this.refs.root.querySelector('.Towa-Dsgvo-Plugin__decline-all').addEventListener('click', () => {
			this.declineAll();
		});
		this.refs.triggerPopupLinks.forEach((link) => {
			link.addEventListener('click',()=>{
				this.state.accepted.value = false;
			});
		});
	}

	deleteAllCookies(){
		var cookies = document.cookie.split(";");

		for (var i = 0; i < cookies.length; i++) {
			var cookie = cookies[i];
			var eqPos = cookie.indexOf("=");
			var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
			document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
		}
	}
}
new TowaDsgvoPlugin();
