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
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__posts__ = __webpack_require__(1);
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

  [__WEBPACK_IMPORTED_MODULE_0__posts__].forEach(function (_ref) {
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
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__block_json__ = __webpack_require__(3);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__block_json___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__block_json__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__edit__ = __webpack_require__(2);
var __ = wp.i18n.__;

/**
 * Internal dependencies
 */




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
		exclude_current_post: {
			type: 'boolean',
			default: false
		},
		show_thumbnail: {
			type: 'boolean',
			default: false
		}
	},

	edit: __WEBPACK_IMPORTED_MODULE_1__edit__["a" /* default */],

	save: function save() {
		return null;
	}
};

/***/ }),
/* 2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

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
    ToggleControl = _wp$components.ToggleControl,
    ServerSideRender = _wp$components.ServerSideRender;

// console.log( "Data:" );
// console.log( wp.data );
// console.log( wp );
// console.log( withAPIData );
// console.log( wp.data.select( 'core' ) );

var select = wp.data.select;

// console.log( select( 'core' ) );

// const WP_Posts = new wp.api.collections.Posts();
// console.log(WP_Posts);

function PostsEdit(props) {

	var toggleAttribute = function toggleAttribute(attributeName) {
		return function (newValue) {
			return setAttributes(_defineProperty({}, attributeName, newValue));
		};
	};

	var name = props.name,
	    attributes = props.attributes,
	    setAttributes = props.setAttributes;
	var exclude_current_post = attributes.exclude_current_post,
	    show_thumbnail = attributes.show_thumbnail;


	var controls = [{
		key: 0,
		label: __('Exclude current post', 'italystrap'),
		checked: exclude_current_post,
		onChange: toggleAttribute("exclude_current_post")
	}, {
		key: 1,
		label: __('Show Thumbnail', 'italystrap'),
		checked: show_thumbnail,
		onChange: toggleAttribute("show_thumbnail")
	}];

	return wp.element.createElement(
		Fragment,
		null,
		wp.element.createElement(
			InspectorControls,
			{ key: 'inspector' },
			wp.element.createElement(
				PanelBody,
				{
					title: __('Posts Settings', 'italystrap')
				},
				controls.map(function (args) {
					return wp.element.createElement(ToggleControl, args);
				})
			)
		),
		wp.element.createElement(ServerSideRender, {
			block: name,
			attributes: attributes
		})
	);
}

/* harmony default export */ __webpack_exports__["a"] = (PostsEdit);

/***/ }),
/* 3 */
/***/ (function(module, exports) {

module.exports = {"name":"italystrap/posts","category":"widgets","attributes":{"exclude_current_post":{"type":"boolean","default":false},"show_thumbnail":{"type":"boolean","default":false}}}

/***/ })
/******/ ]);