/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/dist/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/js-cookie/src/js.cookie.js":
/*!*************************************************!*\
  !*** ./node_modules/js-cookie/src/js.cookie.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
 * JavaScript Cookie v2.2.1
 * https://github.com/js-cookie/js-cookie
 *
 * Copyright 2006, 2015 Klaus Hartl & Fagner Brack
 * Released under the MIT license
 */
;(function (factory) {
	var registeredInModuleLoader;
	if (true) {
		!(__WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
				__WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
		registeredInModuleLoader = true;
	}
	if (true) {
		module.exports = factory();
		registeredInModuleLoader = true;
	}
	if (!registeredInModuleLoader) {
		var OldCookies = window.Cookies;
		var api = window.Cookies = factory();
		api.noConflict = function () {
			window.Cookies = OldCookies;
			return api;
		};
	}
}(function () {
	function extend () {
		var i = 0;
		var result = {};
		for (; i < arguments.length; i++) {
			var attributes = arguments[ i ];
			for (var key in attributes) {
				result[key] = attributes[key];
			}
		}
		return result;
	}

	function decode (s) {
		return s.replace(/(%[0-9A-Z]{2})+/g, decodeURIComponent);
	}

	function init (converter) {
		function api() {}

		function set (key, value, attributes) {
			if (typeof document === 'undefined') {
				return;
			}

			attributes = extend({
				path: '/'
			}, api.defaults, attributes);

			if (typeof attributes.expires === 'number') {
				attributes.expires = new Date(new Date() * 1 + attributes.expires * 864e+5);
			}

			// We're using "expires" because "max-age" is not supported by IE
			attributes.expires = attributes.expires ? attributes.expires.toUTCString() : '';

			try {
				var result = JSON.stringify(value);
				if (/^[\{\[]/.test(result)) {
					value = result;
				}
			} catch (e) {}

			value = converter.write ?
				converter.write(value, key) :
				encodeURIComponent(String(value))
					.replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent);

			key = encodeURIComponent(String(key))
				.replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent)
				.replace(/[\(\)]/g, escape);

			var stringifiedAttributes = '';
			for (var attributeName in attributes) {
				if (!attributes[attributeName]) {
					continue;
				}
				stringifiedAttributes += '; ' + attributeName;
				if (attributes[attributeName] === true) {
					continue;
				}

				// Considers RFC 6265 section 5.2:
				// ...
				// 3.  If the remaining unparsed-attributes contains a %x3B (";")
				//     character:
				// Consume the characters of the unparsed-attributes up to,
				// not including, the first %x3B (";") character.
				// ...
				stringifiedAttributes += '=' + attributes[attributeName].split(';')[0];
			}

			return (document.cookie = key + '=' + value + stringifiedAttributes);
		}

		function get (key, json) {
			if (typeof document === 'undefined') {
				return;
			}

			var jar = {};
			// To prevent the for loop in the first place assign an empty array
			// in case there are no cookies at all.
			var cookies = document.cookie ? document.cookie.split('; ') : [];
			var i = 0;

			for (; i < cookies.length; i++) {
				var parts = cookies[i].split('=');
				var cookie = parts.slice(1).join('=');

				if (!json && cookie.charAt(0) === '"') {
					cookie = cookie.slice(1, -1);
				}

				try {
					var name = decode(parts[0]);
					cookie = (converter.read || converter)(cookie, name) ||
						decode(cookie);

					if (json) {
						try {
							cookie = JSON.parse(cookie);
						} catch (e) {}
					}

					jar[name] = cookie;

					if (key === name) {
						break;
					}
				} catch (e) {}
			}

			return key ? jar[key] : jar;
		}

		api.set = set;
		api.get = function (key) {
			return get(key, false /* read as raw */);
		};
		api.getJSON = function (key) {
			return get(key, true /* read as json */);
		};
		api.remove = function (key, attributes) {
			set(key, '', extend(attributes, {
				expires: -1
			}));
		};

		api.defaults = {};

		api.withConverter = init;

		return api;
	}

	return init(function () {});
}));


/***/ }),

/***/ "./src/js/cookie.js":
/*!**************************!*\
  !*** ./src/js/cookie.js ***!
  \**************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return TowaDsgvoCookie; });
/* harmony import */ var js_cookie__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! js-cookie */ "./node_modules/js-cookie/src/js.cookie.js");
/* harmony import */ var js_cookie__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(js_cookie__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _cookie__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./cookie */ "./src/js/cookie.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(source, true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(source).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }




var TowaDsgvoCookie =
/*#__PURE__*/
function () {
  function TowaDsgvoCookie(cookie, root) {
    _classCallCheck(this, TowaDsgvoCookie);

    this.state = {};
    this.state.description = cookie.description;
    this.state.link = cookie.link;
    this.state.javascript = cookie.javascript;
    this.state.name = this.state.link.title;
    this.state = _objectSpread({}, this.state, {}, {
      self: this,
      active: this.isCookieActive()
    });
    this.ref = {
      root: root,
      domEl: root.querySelector("[data-cookiename=\"".concat(this.state.name, "\"]"))
    };
    this.changeEvent = new Event('cookieChanged');
    this.init();
  }

  _createClass(TowaDsgvoCookie, [{
    key: "setUpProxyVariables",
    value: function setUpProxyVariables() {
      this.state = new Proxy(this.state, {
        get: function get(target, key) {
          return target[key];
        },
        set: function set(obj, prop, value) {
          var returnValue = Reflect.set.apply(Reflect, arguments);
          ;

          if (prop === 'active') {
            obj.self.render();
          }

          return returnValue;
        }
      });
    }
  }, {
    key: "init",
    value: function init() {
      this.setUpProxyVariables();
      this.setUpListeners();
      this.render();
    }
  }, {
    key: "render",
    value: function render() {
      this.ref.domEl.checked = this.state.active;
    }
  }, {
    key: "setUpListeners",
    value: function setUpListeners() {
      var _this = this;

      this.ref.domEl.addEventListener('click', function () {
        _this.toggle();
      });
    }
  }, {
    key: "toggle",
    value: function toggle() {
      this.state.active = !this.state.active;
      this.ref.root.dispatchEvent(this.changeEvent);
    }
  }, {
    key: "isCookieActive",
    value: function isCookieActive() {
      return js_cookie__WEBPACK_IMPORTED_MODULE_0___default.a.get(this.state.name) === 'true' ? true : false;
    }
  }, {
    key: "accept",
    value: function accept() {
      this.state.active = true;
      js_cookie__WEBPACK_IMPORTED_MODULE_0___default.a.set(this.state.name, true, towaDsgvoContext.settings.cookieTime);
      this.ref.root.dispatchEvent(this.changeEvent);
    }
  }, {
    key: "decline",
    value: function decline() {
      this.state.active = false;
      js_cookie__WEBPACK_IMPORTED_MODULE_0___default.a.set(this.state.name, false, towaDsgvoContext.settings.cookieTime);
      this.ref.root.dispatchEvent(this.changeEvent);
    }
  }, {
    key: "save",
    value: function save() {
      this.state.active === true ? js_cookie__WEBPACK_IMPORTED_MODULE_0___default.a.set(this.state.name, true, towaDsgvoContext.settings.cookieTime) : js_cookie__WEBPACK_IMPORTED_MODULE_0___default.a.set(this.state.name, false, towaDsgvoContext.settings.cookieTime);
      ;
    }
  }, {
    key: "setActive",
    value: function setActive(value) {
      this.state.active = value;
    }
  }]);

  return TowaDsgvoCookie;
}();



/***/ }),

/***/ "./src/js/cookiegroup.js":
/*!*******************************!*\
  !*** ./src/js/cookiegroup.js ***!
  \*******************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return CookieGroup; });
/* harmony import */ var _cookie__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./cookie */ "./src/js/cookie.js");
function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }



var CookieGroup =
/*#__PURE__*/
function () {
  function CookieGroup(group, root, display) {
    var _this = this;

    _classCallCheck(this, CookieGroup);

    this.state = {
      id: root.querySelector("[data-groupname=\"".concat(group.title, "\"]")).closest('li').getAttribute('aria-controls'),
      cookies: [],
      display: display,
      active: false,
      self: this
    };
    this.ref = {
      root: root,
      domEl: root.querySelector("[data-groupname=\"".concat(group.title, "\"]")),
      li: root.querySelector("[data-groupname=\"".concat(group.title, "\"]")).closest('li'),
      panel: root.querySelector("#".concat(this.state.id))
    };
    this.toggleGroupClickedEvent = new CustomEvent('toggleGroupClicked', {
      detail: {
        id: this.state.id
      }
    });

    if (_typeof(group.cookies) === 'object') {
      group.cookies.map(function (cookie) {
        var myCookie = new _cookie__WEBPACK_IMPORTED_MODULE_0__["default"](cookie, root);

        _this.state.cookies.push(myCookie);
      });
    }

    this.init();
  }

  _createClass(CookieGroup, [{
    key: "setUpProxyVariables",
    value: function setUpProxyVariables() {
      this.state = new Proxy(this.state, {
        get: function get(target, key) {
          return target[key];
        },
        set: function set(obj, prop, value) {
          var returnValue = Reflect.set.apply(Reflect, arguments);
          ;

          if (prop === 'display' || prop === 'active') {
            obj.self.render();
          }

          return returnValue;
        }
      });
    }
  }, {
    key: "init",
    value: function init() {
      this.setUpProxyVariables();
      this.state.active = this.isGroupActive();
      this.setUpListeners();
      this.render();
    }
  }, {
    key: "isGroupActive",
    value: function isGroupActive() {
      return this.state.cookies.filter(function (cookie) {
        return cookie.state.active === true;
      }).length > 0;
    }
  }, {
    key: "setCssClass",
    value: function setCssClass(element, className, state) {
      if (!state) {
        element.classList.remove(className);
      } else if (!element.classList.contains(className) && state === true) {
        element.classList.add(className);
      }
    }
  }, {
    key: "render",
    value: function render() {
      this.ref.domEl.checked = this.state.active;
      this.setCssClass(this.ref.panel, 'active', this.state.display);
      this.setCssClass(this.ref.li, 'active', this.state.display);
    }
  }, {
    key: "toggle",
    value: function toggle() {
      var _this2 = this;

      this.state.active = !this.state.active;
      this.state.cookies.forEach(function (cookie) {
        cookie.setActive(_this2.state.active);
      });
    }
  }, {
    key: "setUpListeners",
    value: function setUpListeners() {
      var _this3 = this;

      this.ref.domEl.addEventListener('click', function (event) {
        _this3.toggle();
      });
      this.ref.root.addEventListener('cookieChanged', function () {
        _this3.state.active = _this3.isGroupActive();
      });
      this.ref.li.addEventListener('click', function () {
        _this3.ref.root.dispatchEvent(_this3.toggleGroupClickedEvent, _this3.state.id);

        _this3.state.display = true;
      });
      this.ref.root.addEventListener('toggleGroupClicked', function (event) {
        if (_this3.state.id !== event.detail.id) {
          _this3.state.display = false;
        }
      });
    }
  }, {
    key: "acceptWholeGroup",
    value: function acceptWholeGroup() {
      this.state.cookies.forEach(function (cookie) {
        cookie.accept();
      });
    }
  }, {
    key: "declineWholeGroup",
    value: function declineWholeGroup() {
      this.state.cookies.forEach(function (cookie) {
        cookie.decline();
      });
    }
  }, {
    key: "saveWholeGroup",
    value: function saveWholeGroup() {
      this.state.cookies.forEach(function (cookie) {
        cookie.save();
      });
    }
  }]);

  return CookieGroup;
}();



/***/ }),

/***/ "./src/js/main.js":
/*!************************!*\
  !*** ./src/js/main.js ***!
  \************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var js_cookie__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! js-cookie */ "./node_modules/js-cookie/src/js.cookie.js");
/* harmony import */ var js_cookie__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(js_cookie__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _cookiegroup__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./cookiegroup */ "./src/js/cookiegroup.js");
function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }




var TowaDsgvoPlugin =
/*#__PURE__*/
function () {
  function TowaDsgvoPlugin() {
    var _this = this;

    _classCallCheck(this, TowaDsgvoPlugin);

    this.refs = {
      triggerPopupLinks: document.querySelectorAll('.Towa-Dsgvo-Link'),
      root: document.getElementById('Towa-Dsgvo-Plugin'),
      myScriptContainer: document.getElementById('TowaDsgvoScripts')
    };
    this.context = towaDsgvoContext;
    this.state = {
      accepted: this.hasDsgvoAccepted(),
      self: this
    };

    if (_typeof(this.context.settings.cookie_groups) === 'object') {
      this.cookieGroups = this.context.settings.cookie_groups.map(function (group, index) {
        return new _cookiegroup__WEBPACK_IMPORTED_MODULE_1__["default"](group, _this.refs.root, index === 0);
      });
    }

    this.init();
  }

  _createClass(TowaDsgvoPlugin, [{
    key: "hasDsgvoAccepted",
    value: function hasDsgvoAccepted() {
      return js_cookie__WEBPACK_IMPORTED_MODULE_0___default.a.get('DsgvoAccepted') === 'true';
    }
  }, {
    key: "init",
    value: function init() {
      this.setUpProxieVariables();
      this.applySettings();
      this.setUpListeners();
      this.render();
      this.renderScripts();
    }
  }, {
    key: "setUpProxieVariables",
    value: function setUpProxieVariables() {
      this.state = new Proxy(this.state, {
        get: function get(target, key) {
          return target[key];
        },
        set: function set(obj, prop, value) {
          var returnValue = Reflect.set.apply(Reflect, arguments);

          if (prop === 'accepted') {
            obj.self.render();
          }

          return returnValue;
        }
      });
    }
  }, {
    key: "setCssClass",
    value: function setCssClass(element, className, state) {
      if (!state) {
        element.classList.remove(className);
      } else if (!element.classList.contains(className) && state === true) {
        element.classList.add(className);
      }
    }
  }, {
    key: "render",
    value: function render() {
      this.setCssClass(this.refs.root, 'show', !this.state.accepted);
    }
  }, {
    key: "applySettings",
    value: function applySettings() {
      if (this.context.settings.highlight_color) {
        this.refs.root.style.setProperty('--highlightcolor', this.context.settings.highlight_color);
      }
    }
  }, {
    key: "acceptAll",
    value: function acceptAll() {
      this.cookieGroups.forEach(function (group) {
        group.acceptWholeGroup();
      });
      this.accept();
    }
  }, {
    key: "renderScripts",
    value: function renderScripts() {
      var scriptEl = document.createElement('script');
      this.cookieGroups.forEach(function (group) {
        group.state.cookies.forEach(function (cookie) {
          if (cookie.state.active === true) {
            scriptEl.innerText += cookie.state.javascript;
          }
        });
      });
      this.refs.myScriptContainer.innerHTML = '';
      this.refs.myScriptContainer.appendChild(scriptEl);
    }
  }, {
    key: "accept",
    value: function accept() {
      this.state.accepted = true;
      js_cookie__WEBPACK_IMPORTED_MODULE_0___default.a.set('DsgvoAccepted', true, this.context.settings.cookieTime);
      this.renderScripts();
    }
  }, {
    key: "save",
    value: function save() {
      this.cookieGroups.forEach(function (group) {
        group.saveWholeGroup();
      });
      this.accept();
    }
  }, {
    key: "declineAll",
    value: function declineAll() {
      this.deleteAllCookies();
      this.cookieGroups.forEach(function (group) {
        group.declineWholeGroup();
      });
      this.accept();
    }
  }, {
    key: "setUpListeners",
    value: function setUpListeners() {
      var _this2 = this;

      this.refs.root.querySelector('.Towa-Dsgvo-Plugin__save').addEventListener('click', function () {
        _this2.save();
      });
      this.refs.root.querySelector('.Towa-Dsgvo-Plugin__accept-all').addEventListener('click', function () {
        _this2.acceptAll();
      });
      this.refs.root.querySelector('.Towa-Dsgvo-Plugin__decline-all').addEventListener('click', function () {
        _this2.declineAll();
      });
      this.refs.triggerPopupLinks.forEach(function (link) {
        link.addEventListener('click', function () {
          _this2.state.accepted = false;
        });
      });
    }
  }, {
    key: "deleteAllCookies",
    value: function deleteAllCookies() {
      var cookies = document.cookie.split(";");

      for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
      }
    }
  }]);

  return TowaDsgvoPlugin;
}();

new TowaDsgvoPlugin();

/***/ }),

/***/ "./src/scss/main.scss":
/*!****************************!*\
  !*** ./src/scss/main.scss ***!
  \****************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!***************************************************!*\
  !*** multi ./src/js/main.js ./src/scss/main.scss ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! C:\xampp\htdocs\towa-dsgvo-plugin\wp-content\plugins\towa-dsgvo-plugin\src\js\main.js */"./src/js/main.js");
module.exports = __webpack_require__(/*! C:\xampp\htdocs\towa-dsgvo-plugin\wp-content\plugins\towa-dsgvo-plugin\src\scss\main.scss */"./src/scss/main.scss");


/***/ })

/******/ });