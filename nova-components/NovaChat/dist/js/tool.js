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
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
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
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);
module.exports = __webpack_require__(6);


/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

Nova.booting(function (Vue, router, store) {
	Vue.component('nova-chat', __webpack_require__(2));
});

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(3)
/* script */
var __vue_script__ = __webpack_require__(4)
/* template */
var __vue_template__ = __webpack_require__(5)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/Tool.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-68ff5483", Component.options)
  } else {
    hotAPI.reload("data-v-68ff5483", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 3 */
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file.
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate

    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),
/* 4 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
	props: ["resourceName", "resourceId", "panel"],

	data: function data() {
		return {
			// I need to know if the auth'd user is a booster or a client
			order: null,
			auth_id: null,
			message: "",
			messages: []
		};
	},

	methods: {
		sendMessage: function sendMessage() {
			var _this = this;

			if (this.message == "") {
				return;
			}
			axios.post("/messages/send", {
				to: this.order.client_id == this.auth_id ? this.order.booster_id : this.order.client_id,
				message: this.message,
				chat: this.chat.id
			}).then(function (response) {
				_this.messages.push(response.data.message);
				_this.scrollToBottom();
			});

			this.message = "";
		},
		scrollToBottom: function scrollToBottom() {
			var _this2 = this;

			setTimeout(function () {
				_this2.$refs.chatContent.scrollTop = _this2.$refs.chatContent.scrollHeight - _this2.$refs.chatContent.clientHeight;
			}, 50);
		},
		handleSendMessage: function handleSendMessage(e) {
			this.sendMessage();
		},
		getMessageSenderName: function getMessageSenderName(id) {
			return id === this.order.client_id ? this.order.client.username : this.order.booster.username;
		},
		listenForMessages: function listenForMessages() {
			var _this3 = this;

			window.Echo.private("channel.messages." + this.auth_id).listen("SendMessage", function (e) {
				_this3.messages.push(e.message);
				_this3.scrollToBottom();
			});
		}
	},

	created: function created() {
		this.order = this.panel.fields[0].order;
		this.chat = this.order.chat;
		this.messages = this.chat.messages;
		this.auth_id = this.panel.fields[0].auth_id;
		this.listenForMessages();
	}
});

/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c("div", { staticClass: "font-sans antialiased h-screen flex" }, [
      _c(
        "div",
        {
          staticClass:
            "flex-1 flex flex-col bg-white overflow-hidden card mb-6 py-3 px-6"
        },
        [
          _c(
            "div",
            { staticClass: "border-b flex px-6 py-2 items-center flex-none" },
            [
              _c("div", { staticClass: "flex flex-col" }, [
                _c(
                  "h3",
                  { staticClass: "text-grey-darkest mb-1 font-extrabold" },
                  [_vm._v("Chat with " + _vm._s(this.order.client.username))]
                ),
                _vm._v(" "),
                _c("div", { staticClass: "text-grey-dark text-sm truncate" }, [
                  _vm._v("Do NOT share your login details in the chat!")
                ])
              ])
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              ref: "chatContent",
              staticClass: "px-6 py-4 flex-1 overflow-y-scroll"
            },
            [
              _vm._m(0),
              _vm._v(" "),
              _vm._l(_vm.messages, function(message) {
                return _c(
                  "div",
                  {
                    key: message.id,
                    staticClass: "flex items-start mb-4 text-sm"
                  },
                  [
                    _c("div", { staticClass: "flex-1 overflow-hidden" }, [
                      _c("div", [
                        _c("span", { staticClass: "font-bold" }, [
                          _vm._v(
                            "\n\t\t\t\t\t\t\t\t" +
                              _vm._s(
                                message.sender ||
                                  _vm.getMessageSenderName(message.sender_id)
                              ) +
                              "\n\t\t\t\t\t\t\t"
                          )
                        ]),
                        _vm._v(" "),
                        _c("span", { staticClass: "text-grey text-xs" }, [
                          _vm._v(_vm._s(message.at))
                        ])
                      ]),
                      _vm._v(" "),
                      _c("p", { staticClass: "text-white leading-normal" }, [
                        _vm._v(_vm._s(message.content))
                      ])
                    ])
                  ]
                )
              })
            ],
            2
          ),
          _vm._v(" "),
          _c("div", { staticClass: "pb-6 px-4 flex-none" }, [
            _c(
              "div",
              {
                staticClass:
                  "flex rounded-lg border-2 border-grey overflow-hidden"
              },
              [
                _c("input", {
                  directives: [
                    {
                      name: "model",
                      rawName: "v-model",
                      value: _vm.message,
                      expression: "message"
                    }
                  ],
                  staticClass:
                    "w-full px-4 text-3xl text-grey border-r-2 border-grey p-2",
                  attrs: { type: "text", placeholder: "Type your message..." },
                  domProps: { value: _vm.message },
                  on: {
                    keydown: function($event) {
                      if (
                        !$event.type.indexOf("key") &&
                        _vm._k($event.keyCode, "enter", 13, $event.key, "Enter")
                      ) {
                        return null
                      }
                      if (
                        $event.ctrlKey ||
                        $event.shiftKey ||
                        $event.altKey ||
                        $event.metaKey
                      ) {
                        return null
                      }
                      $event.preventDefault()
                      return _vm.handleSendMessage($event)
                    },
                    input: function($event) {
                      if ($event.target.composing) {
                        return
                      }
                      _vm.message = $event.target.value
                    }
                  }
                })
              ]
            )
          ])
        ]
      )
    ])
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "flex items-start mb-4 text-sm" }, [
      _c("div", { staticClass: "flex-1 overflow-hidden" }, [
        _c("div", [
          _c("span", { staticClass: "font-bold" }, [_vm._v("King Boosting")])
        ]),
        _vm._v(" "),
        _c("p", { staticClass: "text-white leading-normal" }, [
          _vm._v(
            "\n\t\t\t\t\t\t\tThank you for your purchase! You will be able to talk to your\n\t\t\t\t\t\t\tbooster/coach right here!\n\t\t\t\t\t\t"
          )
        ])
      ])
    ])
  }
]
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-68ff5483", module.exports)
  }
}

/***/ }),
/* 6 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);