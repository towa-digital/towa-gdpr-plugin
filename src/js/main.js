import './polyfills';
import Cookies from 'js-cookie';
import CookieGroup from './cookiegroup';
import Observable from './observable';
import {convertHexColorToRgbString,setCssClass,deleteAllCookies} from './helpers';
import EssentialCookieGroup from './cookiegroupEssential';
class TowaDsgvoPlugin{
	constructor(){
		this.refs = {
			triggerPopupLinks: document.querySelectorAll('.Towa-Dsgvo-Link'),
			root: document.getElementById('Towa-Dsgvo-Plugin'),
			myScriptContainer: document.getElementById('TowaDsgvoScripts')
		}
		this.context = towaDsgvoContext;
		this.state = {
			accepted: this.UserhasDsgvoAccepted()
		};
		if (this.context.settings.cookie_groups instanceof Object){
			this.state.cookieGroups = this.context.settings.cookie_groups.map((group, index)=>{
				return new CookieGroup(group,this.refs.root, (index === 0));
			});
		}
		if (this.context.settings.essential_group instanceof Object){
			let group = new EssentialCookieGroup(this.context.settings.essential_group, this.refs.root, false);
			this.state.cookieGroups.push(group);
		}
		this.init();
	}

	UserhasDsgvoAccepted(){
		return (Cookies.get('DsgvoAccepted') === 'true');
	}

	init(){
		this.applySettings();
		this.defineObservables();
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

	render(){
		setCssClass(this.refs.root,'show', !this.state.accepted.value);
	}

	applySettings(){
		if(this.context.settings.highlight_color){
			let highlight_color_light = convertHexColorToRgbString(this.context.settings.highlight_color,0.1);
			this.refs.root.style.setProperty('--highlightcolorLight',highlight_color_light);
			this.refs.root.style.setProperty('--highlightcolor', this.context.settings.highlight_color);
		}
	}

	acceptAll(){
		this.state.cookieGroups.forEach(group=> {
			group.acceptWholeGroup();
		});
		this.accept();
	}

	renderScripts(){
		let scriptEl = document.createElement('script');
		this.state.cookieGroups.forEach(group => {
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
		this.state.cookieGroups.forEach((group)=>{
			group.saveWholeGroup();
		})
		this.accept();
	}

	declineAll(){
		deleteAllCookies();
		this.state.cookieGroups.forEach((group)=>{
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
}
new TowaDsgvoPlugin();
