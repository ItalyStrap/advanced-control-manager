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
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__posts_index__ = __webpack_require__(1);
/**
 * WordPress dependencies
 */
var registerBlockType = wp.blocks.registerBlockType;

/**
 * Internal dependencies
 */



/**
 * Register blocks
 */
var registerItalyStrapBlocks = function registerItalyStrapBlocks() {

  [__WEBPACK_IMPORTED_MODULE_0__posts_index__].forEach(function (_ref) {
    var name = _ref.name,
        settings = _ref.settings;

    registerBlockType(name, settings);
  });
};

registerItalyStrapBlocks();

/***/ }),
/* 1 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "name", function() { return name; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "settings", function() { return settings; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__edit__ = __webpack_require__(2);


var __ = wp.i18n.__;


var name = 'italystrap/posts';

var settings = {

	title: __('ItalyStrap Posts', 'italystrap'),
	icon: 'universal-access-alt',
	category: 'widgets',
	keywords: [__('posts', 'italystrap')],

	supports: {
		html: false
		// customClassName: false,
	},

	getEditWrapperProps: function getEditWrapperProps(attributes) {
		// console.log("Attributes");
		// console.log(attributes);
		// const { align } = attributes;
		// if ( 'left' === align || 'right' === align || 'wide' === align || 'full' === align ) {
		// 	return { 'data-align': align };
		// }
	},


	attributes: {
		url: {
			type: 'string',
			source: 'attribute',
			selector: 'img',
			attribute: 'src'
		}
	},

	edit: __WEBPACK_IMPORTED_MODULE_0__edit__["a" /* default */],

	save: function save() {
		return null;
	}
};

/***/ }),
/* 2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * wp.element
 */
var _wp$element = wp.element,
    Component = _wp$element.Component,
    Fragment = _wp$element.Fragment;

// console.log(wp.element);

/**
 * wp.blocks
 */
// const {
// 	AlignmentToolbar,
// 	BlockDescription,
// 	QueryPanel,
// } = wp.blocks;

// console.log(wp.blocks);

var _wp$editor = wp.editor,
    InspectorControls = _wp$editor.InspectorControls,
    BlockAlignmentToolbar = _wp$editor.BlockAlignmentToolbar;

// console.log(wp.editor);

var __ = wp.i18n.__;
var _wp$components = wp.components,
    PanelBody = _wp$components.PanelBody,
    ToggleControl = _wp$components.ToggleControl;

// console.log( "Data:" );
// console.log( wp.data );
// console.log( wp );
// console.log( withAPIData );
// console.log( wp.data.select( 'core' ) );

var select = wp.data.select;

// console.log( select( 'core' ) );

// const WP_Posts = new wp.api.collections.Posts();
// console.log(WP_Posts);

var PostsEdit = function (_Component) {
	_inherits(PostsEdit, _Component);

	function PostsEdit() {
		_classCallCheck(this, PostsEdit);

		// console.log(this.props);

		var _this = _possibleConstructorReturn(this, (PostsEdit.__proto__ || Object.getPrototypeOf(PostsEdit)).apply(this, arguments));

		var _this$props = _this.props,
		    setAttributes = _this$props.setAttributes,
		    className = _this$props.className,
		    focus = _this$props.focus;

		// this.onSelectImage = this.onSelectImage.bind( this );

		_this.state = {
			selectedImage: null
		};

		setAttributes({
			key: 'value'
		});
		return _this;
	}

	_createClass(PostsEdit, [{
		key: "toggleDisplayPostDate",
		value: function toggleDisplayPostDate() {
			var displayPostDate = this.props.attributes.displayPostDate;
			var setAttributes = this.props.setAttributes;


			setAttributes({ displayPostDate: !displayPostDate });
		}

		// toggleSetting: () => 

	}, {
		key: "render",
		value: function render() {
			var name = this.props.name;


			console.log("this.props");
			console.log(this.props);
			var _props = this.props,
			    attributes = _props.attributes,
			    setAttributes = _props.setAttributes;


			console.log(attributes);

			var displayPostDate = attributes.displayPostDate;


			return wp.element.createElement(
				Fragment,
				null,
				wp.element.createElement(
					InspectorControls,
					{ key: "inspector" },
					wp.element.createElement(PanelBody, {
						title: __('Posts Settings', 'italystrap')
					}),
					wp.element.createElement(ToggleControl, {
						label: __('Display post date'),
						checked: displayPostDate,
						onChange: this.toggleDisplayPostDate
					})
				),
				wp.element.createElement(
					"div",
					{ key: "container" },
					wp.element.createElement(
						"h1",
						null,
						name
					)
				)
			);
		}
	}]);

	return PostsEdit;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (PostsEdit);

/***/ })
/******/ ]);