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

/***/ }),
/* 1 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__posts_block_js__ = __webpack_require__(2);


var __ = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;


registerBlockType('italystrap/posts', {

	title: __('ItalyStrap Posts', 'italystrap'),
	icon: 'universal-access-alt',
	category: 'widgets',
	keywords: [__('posts', 'italystrap')],

	supports: {
		html: false
		// customClassName: false,
	},

	attributes: {
		url: {
			type: 'string',
			source: 'attribute',
			selector: 'img',
			attribute: 'src'
		}
	},

	edit: __WEBPACK_IMPORTED_MODULE_0__posts_block_js__["a" /* default */],

	save: function save() {
		return null;
	}
});

/***/ }),
/* 2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * wp.blocks
 */
var _wp$blocks = wp.blocks,
    AlignmentToolbar = _wp$blocks.AlignmentToolbar,
    BlockControls = _wp$blocks.BlockControls,
    InspectorControls = _wp$blocks.InspectorControls,
    BlockDescription = _wp$blocks.BlockDescription,
    QueryPanel = _wp$blocks.QueryPanel;

/**
 * wp.element
 */

var Component = wp.element.Component;
var __ = wp.i18n.__;
var _wp$components = wp.components,
    Spinner = _wp$components.Spinner,
    withAPIData = _wp$components.withAPIData;


console.log(withAPIData);

// const WP_Posts = new wp.api.collections.Posts();

var PostsBlock = function (_Component) {
	_inherits(PostsBlock, _Component);

	function PostsBlock() {
		_classCallCheck(this, PostsBlock);

		// const { attributes, setAttributes, className, focus } = this.props;

		// this.onSelectImage = this.onSelectImage.bind( this );

		var _this = _possibleConstructorReturn(this, (PostsBlock.__proto__ || Object.getPrototypeOf(PostsBlock)).apply(this, arguments));

		_this.state = {
			selectedImage: null
		};
		return _this;
	}

	// toggleSetting: () => 

	_createClass(PostsBlock, [{
		key: "render",
		value: function render() {
			// const { attributes, setAttributes, className, focus } = this.props;
			// console.log(setAttributes);
			return [focus && wp.element.createElement(
				"div",
				{ key: "container" },
				wp.element.createElement(
					InspectorControls,
					{ key: "inspector" },
					wp.element.createElement(
						BlockDescription,
						null,
						wp.element.createElement(
							"p",
							null,
							__('Shows a list of your site\'s most recent posts.')
						)
					),
					wp.element.createElement(
						"h3",
						null,
						__('Latest Posts Settings')
					)
				),
				__('ItalyStrap Posts.', 'italystrap')
			)];
		}
	}]);

	return PostsBlock;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (PostsBlock);

/***/ })
/******/ ]);