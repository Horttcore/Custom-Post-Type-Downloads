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
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__CPT_List_cb_cpt_list__ = __webpack_require__(1);


/***/ }),
/* 1 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__cb_cpt_list_block__ = __webpack_require__(2);


var __ = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;


registerBlockType('horttcore/downloads-list', {
    title: __('Downloads Listing'),
    icon: 'list-view',
    category: 'widgets',
    description: 'Display a list of a selected downloads',
    attributes: {
        posttype: {
            type: 'string',
            default: 'download'
        },
        taxonomie: {
            type: 'string',
            default: 'unkategorisiert'
        },
        term: {
            type: 'array'
        },
        amount: {
            type: 'number',
            default: '1'
        },
        orderBy: {
            type: 'string',
            default: 'id'
        },
        order: {
            type: 'string',
            default: 'ASC'
        }
    },
    edit: __WEBPACK_IMPORTED_MODULE_0__cb_cpt_list_block__["a" /* default */],
    save: function save() {
        return null;
    }
});

/***/ }),
/* 2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__cb_tax_list__ = __webpack_require__(3);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__cb_term_list__ = __webpack_require__(4);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }




var Component = wp.element.Component;
var __ = wp.i18n.__;
var InspectorControls = wp.editor.InspectorControls;
var _wp$components = wp.components,
    TextControl = _wp$components.TextControl,
    SelectControl = _wp$components.SelectControl,
    RangeControl = _wp$components.RangeControl,
    withAPIData = _wp$components.withAPIData;
var withSelect = wp.data.withSelect;

var DownloadsListBlock = function (_Component) {
    _inherits(DownloadsListBlock, _Component);

    function DownloadsListBlock() {
        _classCallCheck(this, DownloadsListBlock);

        return _possibleConstructorReturn(this, (DownloadsListBlock.__proto__ || Object.getPrototypeOf(DownloadsListBlock)).apply(this, arguments));
    }

    _createClass(DownloadsListBlock, [{
        key: 'render',
        value: function render() {
            var _props = this.props,
                attributes = _props.attributes,
                setAttributes = _props.setAttributes,
                className = _props.className,
                isSelected = _props.isSelected,
                posts = _props.posts;
            var taxonomie = attributes.taxonomie,
                term = attributes.term,
                amount = attributes.amount,
                orderBy = attributes.orderBy,
                order = attributes.order;

            var classes = (className ? className : '') + ' list-wrapper ';

            var _posts = [];
            if (posts != undefined && taxonomie != undefined && taxonomie != undefined && term != undefined && term.length != 0) {
                for (var i = 0; i < posts.length; i++) {
                    // check if we have any value set in given taxonomie cause this will be an arry of ids
                    if (posts[i][taxonomie] != undefined && posts[i][taxonomie].length > 0) {
                        _posts.push(posts[i]);
                    }
                }
            } else {
                _posts = posts;
            }

            var termList = wp.element.createElement(__WEBPACK_IMPORTED_MODULE_1__cb_term_list__["a" /* default */], {
                taxonomie: taxonomie,
                value: term,
                onChange: function onChange(newTerm) {
                    var _term = [];
                    if (Array.isArray(newTerm)) {
                        newTerm.map(function (item, i) {
                            if (_term.indexOf(item) == -1) {
                                _term.push(item);
                            }
                        });
                    } else {
                        _term.push(newTerm);
                    }
                    // @todo trigger change cause checked will stuck
                    setAttributes({ term: _term });
                }
            });
            var inspectorControls = isSelected && wp.element.createElement(
                InspectorControls,
                { key: 'inspector' },
                wp.element.createElement(__WEBPACK_IMPORTED_MODULE_0__cb_tax_list__["a" /* default */], {
                    posttype: 'download',
                    value: taxonomie,
                    onChange: function onChange(newTaxonomie) {
                        setAttributes({ taxonomie: newTaxonomie });
                        // reset other related values
                        setAttributes({ term: '' });
                    }
                }),
                termList,
                wp.element.createElement(RangeControl, {
                    label: __('Amount'),
                    value: amount,
                    onChange: function onChange(newAmount) {
                        setAttributes({ amount: newAmount });
                    },
                    min: 0,
                    max: 100
                }),
                wp.element.createElement(TextControl, {
                    label: __('Order By'),
                    value: orderBy,
                    onChange: function onChange(newOrderBy) {
                        setAttributes({ orderBy: newOrderBy });
                    }
                }),
                wp.element.createElement(SelectControl, {
                    label: __('Order'),
                    value: order,
                    options: [{ label: __('Ascending'), value: 'asc' }, { label: __('Decending'), value: 'desc' }],
                    onChange: function onChange(newOrder) {
                        setAttributes({ order: newOrder });
                    }
                })
            );

            var hasPosts = Array.isArray(_posts) && _posts.length;
            if (!hasPosts) {
                return [inspectorControls, wp.element.createElement(
                    'div',
                    { className: classes, key: 'container' },
                    __('No Downloads found')
                )];
            }
            // @todo change edit output
            return [inspectorControls, wp.element.createElement(
                'div',
                { className: classes, key: 'container' },
                _posts.map(function (post, i) {
                    return wp.element.createElement(
                        'div',
                        { 'class': 'downloads-wrapper', key: i },
                        wp.element.createElement('img', { 'class': 'downloads-image', src: post.thumbnail }),
                        post.title.rendered
                    );
                })
            )];
        }
    }]);

    return DownloadsListBlock;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (withSelect(function (select, props) {
    var _props$attributes = props.attributes,
        posttype = _props$attributes.posttype,
        taxonomie = _props$attributes.taxonomie,
        term = _props$attributes.term,
        amount = _props$attributes.amount,
        orderBy = _props$attributes.orderBy,
        order = _props$attributes.order;

    var _order = String(order).toLowerCase();
    var postsQuery = {
        per_page: amount,
        order: _order,
        orderby: orderBy,
        _fields: ['id', 'date_gmt', 'link', 'title', 'content', 'meta', 'thumbnail']
    };
    // retrieve taxonomies in fields
    if (taxonomie != undefined) {
        postsQuery._fields.push(taxonomie);
    }
    // retrieve posts with a given term
    if (taxonomie != undefined && typeof term != 'undefined' && term.length != 0) {
        if (term.indexOf('-1') != -1) {
            delete postsQuery[taxonomie];
        } else {
            postsQuery[taxonomie] = term;
        }
    }
    return {
        posts: select('core').getEntityRecords('postType', posttype, postsQuery)
    };
})(DownloadsListBlock));

/***/ }),
/* 3 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

//import { filter, includes } from 'lodash';

var __ = wp.i18n.__;
var SelectControl = wp.components.SelectControl;
var withSelect = wp.data.withSelect;


function TaxList(_ref) {
    var taxonomies = _ref.taxonomies,
        posttype = _ref.posttype,
        value = _ref.value,
        onChange = _ref.onChange;

    var _taxonomies = [];
    if (taxonomies != undefined) {
        var arr = Object.values(taxonomies);
        arr.forEach(function (element) {
            if (element.types.includes(posttype)) {
                _taxonomies.push({ label: element.name, value: element.slug });
            }
        });
    }
    var hasTaxs = Array.isArray(_taxonomies) && _taxonomies.length;
    if (!hasTaxs) {
        return [wp.element.createElement('div', null)];
    }

    _taxonomies.unshift({ label: __('All'), value: '-1' });
    return wp.element.createElement(SelectControl, _extends({ onChange: onChange }, {
        label: __('Custom Posttypes Taxonomies'),
        options: _taxonomies,
        value: value
    }));
}

/* harmony default export */ __webpack_exports__["a"] = (withSelect(function (select, props) {
    var taxonomies = select('core').getTaxonomies();
    //const postTypeTaxonomies = filter( taxonomies, (taxonomy) => includes(taxonomy.types, props.posttype));
    //const visiblePostTypeTaxonomies = filter( postTypeTaxonomies, (taxonomy) => taxonomy.visibility.show_ui );
    return {
        taxonomies: taxonomies //visiblePostTypeTaxonomies,
    };
})(TaxList));

/***/ }),
/* 4 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var __ = wp.i18n.__;
var SelectControl = wp.components.SelectControl;
var withSelect = wp.data.withSelect;


function TermList(_ref) {
    var terms = _ref.terms,
        value = _ref.value,
        onChange = _ref.onChange,
        checked = _ref.checked;

    var _terms = [];
    if (terms != undefined) {
        var arr = Object.values(terms);
        arr.forEach(function (element) {
            if (element.name != undefined && element.id != undefined) {
                _terms.push({ label: element.name, value: element.id });
            }
        });
    }
    var hasTerms = Array.isArray(_terms) && _terms.length;
    if (!hasTerms) {
        return [wp.element.createElement('div', null)];
    }
    if (!Array.isArray(checked)) {
        checked = [];
    }
    _terms.unshift({ label: 'All', value: '-1' });
    return [wp.element.createElement(SelectControl, _extends({ onChange: onChange }, {
        multiple: true,
        label: __('CPT Tax Terms'),
        options: _terms.map(function (term, i) {
            return {
                value: term.value,
                label: term.label
            };
        }),
        value: value
    }))];
}
/* harmony default export */ __webpack_exports__["a"] = (withSelect(function (select, props) {
    var taxonomie = props.taxonomie;

    return {
        terms: select('core').getEntityRecords('taxonomy', taxonomie)
    };
})(TermList));

/***/ })
/******/ ]);