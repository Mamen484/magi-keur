(self["webpackChunk"] = self["webpackChunk"] || []).push([["app"],{

/***/ "./assets/app.js":
/*!***********************!*\
  !*** ./assets/app.js ***!
  \***********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _bootstrap_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./bootstrap.js */ "./assets/bootstrap.js");
/* harmony import */ var _styles_app_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./styles/app.scss */ "./assets/styles/app.scss");
/* harmony import */ var _styles_app_scss__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_styles_app_scss__WEBPACK_IMPORTED_MODULE_1__);
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'highlight.js/styles/github-dark-dimmed.css'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'lato-font/css/lato-font.css'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'bootstrap/js/dist/alert'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'bootstrap/js/dist/collapse'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'bootstrap/js/dist/dropdown'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'bootstrap/js/dist/tab'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'bootstrap/js/dist/modal'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'jquery'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
/* harmony import */ var _js_highlight_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./js/highlight.js */ "./assets/js/highlight.js");
/* harmony import */ var _js_doclinks_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./js/doclinks.js */ "./assets/js/doclinks.js");
/* harmony import */ var _js_doclinks_js__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_js_doclinks_js__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _js_flatpicker_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./js/flatpicker.js */ "./assets/js/flatpicker.js");
// start the Stimulus application





// loads the Bootstrap plugins







// loads the code syntax highlighting library


// Creates links to the Symfony documentation



/***/ }),

/***/ "./assets/bootstrap.js":
/*!*****************************!*\
  !*** ./assets/bootstrap.js ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
Object(function webpackMissingModule() { var e = new Error("Cannot find module '@symfony/stimulus-bundle'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());

var app = Object(function webpackMissingModule() { var e = new Error("Cannot find module '@symfony/stimulus-bundle'"); e.code = 'MODULE_NOT_FOUND'; throw e; }())();
// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);

/***/ }),

/***/ "./assets/js/doclinks.js":
/*!*******************************!*\
  !*** ./assets/js/doclinks.js ***!
  \*******************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


// Wraps some elements in anchor tags referencing to the Symfony documentation
__webpack_require__(/*! core-js/modules/es.array.for-each.js */ "./node_modules/core-js/modules/es.array.for-each.js");
__webpack_require__(/*! core-js/modules/es.array.join.js */ "./node_modules/core-js/modules/es.array.join.js");
__webpack_require__(/*! core-js/modules/es.object.keys.js */ "./node_modules/core-js/modules/es.object.keys.js");
__webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
__webpack_require__(/*! core-js/modules/es.regexp.constructor.js */ "./node_modules/core-js/modules/es.regexp.constructor.js");
__webpack_require__(/*! core-js/modules/es.regexp.dot-all.js */ "./node_modules/core-js/modules/es.regexp.dot-all.js");
__webpack_require__(/*! core-js/modules/es.regexp.exec.js */ "./node_modules/core-js/modules/es.regexp.exec.js");
__webpack_require__(/*! core-js/modules/es.regexp.sticky.js */ "./node_modules/core-js/modules/es.regexp.sticky.js");
__webpack_require__(/*! core-js/modules/es.regexp.to-string.js */ "./node_modules/core-js/modules/es.regexp.to-string.js");
__webpack_require__(/*! core-js/modules/es.string.match.js */ "./node_modules/core-js/modules/es.string.match.js");
__webpack_require__(/*! core-js/modules/es.string.replace.js */ "./node_modules/core-js/modules/es.string.replace.js");
__webpack_require__(/*! core-js/modules/esnext.iterator.constructor.js */ "./node_modules/core-js/modules/esnext.iterator.constructor.js");
__webpack_require__(/*! core-js/modules/esnext.iterator.for-each.js */ "./node_modules/core-js/modules/esnext.iterator.for-each.js");
__webpack_require__(/*! core-js/modules/web.dom-collections.for-each.js */ "./node_modules/core-js/modules/web.dom-collections.for-each.js");
document.addEventListener('DOMContentLoaded', function () {
  var modalElt = document.querySelector('#sourceCodeModal');
  if (!modalElt) {
    return;
  }
  var controllerCode = modalElt.querySelector('code.php');
  var templateCode = modalElt.querySelector('code.twig');
  function anchor(url, content) {
    return '<a class="doclink" target="_blank" href="' + url + '">' + content + '</a>';
  }
  function wrap(content, links) {
    return content.replace(new RegExp(Object.keys(links).join('|'), 'g'), function (token) {
      return anchor(links[token], token);
    });
  }

  // Wrap Symfony Doc urls in comments
  modalElt.querySelectorAll('.hljs-comment').forEach(function (commentElt) {
    commentElt.innerHTML = commentElt.innerHTML.replace(/https:\/\/symfony.com\/[\w/.#-]+/g, function (url) {
      return anchor(url, url);
    });
  });

  // Wraps Symfony PHP attributes in code
  var attributes = {
    'Cache': 'https://symfony.com/doc/current/http_cache.html#http-cache-expiration-intro',
    'Route': 'https://symfony.com/doc/current/routing.html#creating-routes-as-attributes',
    'IsGranted': 'https://symfony.com/doc/current/security.html#security-securing-controller-annotations'
  };
  controllerCode.querySelectorAll('.hljs-meta').forEach(function (elt) {
    elt.innerHTML = wrap(elt.textContent, attributes);
  });

  // Wraps Twig's tags
  templateCode.querySelectorAll('.hljs-template-tag + .hljs-name').forEach(function (elt) {
    var tag = elt.textContent;
    if ('else' === tag || tag.match(/^end/)) {
      return;
    }
    var url = 'https://twig.symfony.com/doc/3.x/tags/' + tag + '.html#' + tag;
    elt.innerHTML = anchor(url, tag);
  });

  // Wraps Twig's functions
  templateCode.querySelectorAll('.hljs-template-variable > .hljs-name').forEach(function (elt) {
    var func = elt.textContent;
    var url = 'https://twig.symfony.com/doc/3.x/functions/' + func + '.html#' + func;
    elt.innerHTML = anchor(url, func);
  });
});

/***/ }),

/***/ "./assets/js/flatpicker.js":
/*!*********************************!*\
  !*** ./assets/js/flatpicker.js ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_array_index_of_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.array.index-of.js */ "./node_modules/core-js/modules/es.array.index-of.js");
/* harmony import */ var core_js_modules_es_array_index_of_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_index_of_js__WEBPACK_IMPORTED_MODULE_0__);
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'flatpickr'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'flatpickr/dist/flatpickr.min.css'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'flatpickr/dist/l10n'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());




flatpickr.defaultConfig.animate = window.navigator.userAgent.indexOf('MSIE') === -1;
var lang = document.documentElement.getAttribute('lang') || 'en';
var Locale = Object(function webpackMissingModule() { var e = new Error("Cannot find module 'flatpickr/dist/l10n'"); e.code = 'MODULE_NOT_FOUND'; throw e; }())["".concat(lang)] || Object(function webpackMissingModule() { var e = new Error("Cannot find module 'flatpickr/dist/l10n'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
flatpickr.localize(Locale);
var configs = {
  standard: {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    allowInput: true,
    time_24hr: true,
    defaultHour: 24,
    parseDate: function parseDate(datestr, format) {
      return flatpickr.parseDate(datestr, format);
    },
    formatDate: function formatDate(date, format, locale) {
      return flatpickr.formatDate(date, format);
    }
  }
};
var flatpickrs = document.querySelectorAll(".flatpickr");
for (var i = 0; i < flatpickrs.length; i++) {
  var element = flatpickrs[i];
  var configValue = configs[element.getAttribute("data-flatpickr-class")] || {};
  // Overrides the default format with the one sent by data attribute
  configValue.dateFormat = element.getAttribute("data-date-format") || 'Y-m-d H:i';
  // ...and then initialize the flatpickr
  flatpickr(element, configValue);
}

/***/ }),

/***/ "./assets/js/highlight.js":
/*!********************************!*\
  !*** ./assets/js/highlight.js ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'highlight.js/lib/core'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'highlight.js/lib/languages/php'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'highlight.js/lib/languages/twig'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());



Object(function webpackMissingModule() { var e = new Error("Cannot find module 'highlight.js/lib/core'"); e.code = 'MODULE_NOT_FOUND'; throw e; }())('php', Object(function webpackMissingModule() { var e = new Error("Cannot find module 'highlight.js/lib/languages/php'"); e.code = 'MODULE_NOT_FOUND'; throw e; }()));
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'highlight.js/lib/core'"); e.code = 'MODULE_NOT_FOUND'; throw e; }())('twig', Object(function webpackMissingModule() { var e = new Error("Cannot find module 'highlight.js/lib/languages/twig'"); e.code = 'MODULE_NOT_FOUND'; throw e; }()));
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'highlight.js/lib/core'"); e.code = 'MODULE_NOT_FOUND'; throw e; }())();

/***/ }),

/***/ "./assets/styles/app.scss":
/*!********************************!*\
  !*** ./assets/styles/app.scss ***!
  \********************************/
/***/ (() => {

throw new Error("Module parse failed: Unexpected character '@' (1:0)\nYou may need an appropriate loader to handle this file type, currently no loaders are configured to process this file. See https://webpack.js.org/concepts#loaders\n> @import \"variables\";\n| \n| @import \"../../vendor/twbs/bootstrap/scss/bootstrap.scss\";");

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["vendors-node_modules_core-js_modules_es_array_for-each_js-node_modules_core-js_modules_es_arr-6c7f01"], () => (__webpack_exec__("./assets/app.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,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U0FBUyxDQUFDQyxPQUFPLEVBQUVDLE1BQU0sQ0FBQztJQUMvQyxDQUFDO0lBQ0RDLFVBQVUsRUFBRSxTQUFaQSxVQUFVQSxDQUFHQyxJQUFJLEVBQUVGLE1BQU0sRUFBRUcsTUFBTSxFQUFLO01BQ2xDLE9BQU96QixTQUFTLENBQUN1QixVQUFVLENBQUNDLElBQUksRUFBRUYsTUFBTSxDQUFDO0lBQzdDO0VBQ0o7QUFDSixDQUFDO0FBRUQsSUFBTUksVUFBVSxHQUFHdEQsUUFBUSxDQUFDaUIsZ0JBQWdCLENBQUMsWUFBWSxDQUFDO0FBQzFELEtBQUssSUFBSXNDLENBQUMsR0FBRyxDQUFDLEVBQUVBLENBQUMsR0FBR0QsVUFBVSxDQUFDRSxNQUFNLEVBQUVELENBQUMsRUFBRSxFQUFFO0VBQ3hDLElBQUlFLE9BQU8sR0FBR0gsVUFBVSxDQUFDQyxDQUFDLENBQUM7RUFDM0IsSUFBSUcsV0FBVyxHQUFHakIsT0FBTyxDQUFDZ0IsT0FBTyxDQUFDcEIsWUFBWSxDQUFDLHNCQUFzQixDQUFDLENBQUMsSUFBSSxDQUFDLENBQUM7RUFDN0U7RUFDQXFCLFdBQVcsQ0FBQ2QsVUFBVSxHQUFHYSxPQUFPLENBQUNwQixZQUFZLENBQUMsa0JBQWtCLENBQUMsSUFBSSxXQUFXO0VBQ2hGO0VBQ0FULFNBQVMsQ0FBQzZCLE9BQU8sRUFBRUMsV0FBVyxDQUFDO0FBQ25DLEM7Ozs7Ozs7Ozs7Ozs7OztBQ2hDeUM7QUFDUTtBQUNFO0FBRW5EQyxvSkFBcUIsQ0FBQyxLQUFLLEVBQUVDLDZKQUFHLENBQUM7QUFDakNELG9KQUFxQixDQUFDLE1BQU0sRUFBRUUsOEpBQUksQ0FBQztBQUVuQ0Ysb0pBQWlCLENBQUMsQ0FBQyxDIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2FwcC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvYm9vdHN0cmFwLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9kb2NsaW5rcy5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvZmxhdHBpY2tlci5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvaGlnaGxpZ2h0LmpzIl0sInNvdXJjZXNDb250ZW50IjpbIi8vIHN0YXJ0IHRoZSBTdGltdWx1cyBhcHBsaWNhdGlvblxuaW1wb3J0ICcuL2Jvb3RzdHJhcC5qcyc7XG5pbXBvcnQgJy4vc3R5bGVzL2FwcC5zY3NzJztcbmltcG9ydCAnaGlnaGxpZ2h0LmpzL3N0eWxlcy9naXRodWItZGFyay1kaW1tZWQuY3NzJztcbmltcG9ydCAnbGF0by1mb250L2Nzcy9sYXRvLWZvbnQuY3NzJztcblxuLy8gbG9hZHMgdGhlIEJvb3RzdHJhcCBwbHVnaW5zXG5pbXBvcnQgJ2Jvb3RzdHJhcC9qcy9kaXN0L2FsZXJ0JztcbmltcG9ydCAnYm9vdHN0cmFwL2pzL2Rpc3QvY29sbGFwc2UnO1xuaW1wb3J0ICdib290c3RyYXAvanMvZGlzdC9kcm9wZG93bic7XG5pbXBvcnQgJ2Jvb3RzdHJhcC9qcy9kaXN0L3RhYic7XG5pbXBvcnQgJ2Jvb3RzdHJhcC9qcy9kaXN0L21vZGFsJztcbmltcG9ydCAnanF1ZXJ5J1xuXG4vLyBsb2FkcyB0aGUgY29kZSBzeW50YXggaGlnaGxpZ2h0aW5nIGxpYnJhcnlcbmltcG9ydCAnLi9qcy9oaWdobGlnaHQuanMnO1xuXG4vLyBDcmVhdGVzIGxpbmtzIHRvIHRoZSBTeW1mb255IGRvY3VtZW50YXRpb25cbmltcG9ydCAnLi9qcy9kb2NsaW5rcy5qcyc7XG5cbmltcG9ydCAnLi9qcy9mbGF0cGlja2VyLmpzJztcblxuIiwiaW1wb3J0IHsgc3RhcnRTdGltdWx1c0FwcCB9IGZyb20gJ0BzeW1mb255L3N0aW11bHVzLWJ1bmRsZSc7XG5cbmNvbnN0IGFwcCA9IHN0YXJ0U3RpbXVsdXNBcHAoKTtcbi8vIHJlZ2lzdGVyIGFueSBjdXN0b20sIDNyZCBwYXJ0eSBjb250cm9sbGVycyBoZXJlXG4vLyBhcHAucmVnaXN0ZXIoJ3NvbWVfY29udHJvbGxlcl9uYW1lJywgU29tZUltcG9ydGVkQ29udHJvbGxlcik7XG4iLCIndXNlIHN0cmljdCc7XG5cbi8vIFdyYXBzIHNvbWUgZWxlbWVudHMgaW4gYW5jaG9yIHRhZ3MgcmVmZXJlbmNpbmcgdG8gdGhlIFN5bWZvbnkgZG9jdW1lbnRhdGlvblxuZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignRE9NQ29udGVudExvYWRlZCcsIGZ1bmN0aW9uKCkge1xuICAgIGNvbnN0IG1vZGFsRWx0ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignI3NvdXJjZUNvZGVNb2RhbCcpO1xuICAgIGlmICghbW9kYWxFbHQpIHtcbiAgICAgICAgcmV0dXJuO1xuICAgIH1cbiAgICBjb25zdCBjb250cm9sbGVyQ29kZSA9IG1vZGFsRWx0LnF1ZXJ5U2VsZWN0b3IoJ2NvZGUucGhwJyk7XG4gICAgY29uc3QgdGVtcGxhdGVDb2RlID0gbW9kYWxFbHQucXVlcnlTZWxlY3RvcignY29kZS50d2lnJyk7XG5cbiAgICBmdW5jdGlvbiBhbmNob3IodXJsLCBjb250ZW50KSB7XG4gICAgICAgIHJldHVybiAnPGEgY2xhc3M9XCJkb2NsaW5rXCIgdGFyZ2V0PVwiX2JsYW5rXCIgaHJlZj1cIicgKyB1cmwgKyAnXCI+JyArIGNvbnRlbnQgKyAnPC9hPic7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gd3JhcChjb250ZW50LCBsaW5rcykge1xuICAgICAgICByZXR1cm4gY29udGVudC5yZXBsYWNlKFxuICAgICAgICAgICAgbmV3IFJlZ0V4cChPYmplY3Qua2V5cyhsaW5rcykuam9pbignfCcpLCAnZycpLFxuICAgICAgICAgICAgdG9rZW4gPT4gYW5jaG9yKGxpbmtzW3Rva2VuXSwgdG9rZW4pXG4gICAgICAgICk7XG4gICAgfVxuXG4gICAgLy8gV3JhcCBTeW1mb255IERvYyB1cmxzIGluIGNvbW1lbnRzXG4gICAgbW9kYWxFbHQucXVlcnlTZWxlY3RvckFsbCgnLmhsanMtY29tbWVudCcpLmZvckVhY2goKGNvbW1lbnRFbHQpID0+IHtcbiAgICAgICAgY29tbWVudEVsdC5pbm5lckhUTUwgPSBjb21tZW50RWx0LmlubmVySFRNTC5yZXBsYWNlKC9odHRwczpcXC9cXC9zeW1mb255LmNvbVxcL1tcXHcvLiMtXSsvZywgKHVybCkgPT4gYW5jaG9yKHVybCwgdXJsKSk7XG4gICAgfSk7XG5cbiAgICAvLyBXcmFwcyBTeW1mb255IFBIUCBhdHRyaWJ1dGVzIGluIGNvZGVcbiAgICBjb25zdCBhdHRyaWJ1dGVzID0ge1xuICAgICAgICAnQ2FjaGUnOiAnaHR0cHM6Ly9zeW1mb255LmNvbS9kb2MvY3VycmVudC9odHRwX2NhY2hlLmh0bWwjaHR0cC1jYWNoZS1leHBpcmF0aW9uLWludHJvJyxcbiAgICAgICAgJ1JvdXRlJzogJ2h0dHBzOi8vc3ltZm9ueS5jb20vZG9jL2N1cnJlbnQvcm91dGluZy5odG1sI2NyZWF0aW5nLXJvdXRlcy1hcy1hdHRyaWJ1dGVzJyxcbiAgICAgICAgJ0lzR3JhbnRlZCc6ICdodHRwczovL3N5bWZvbnkuY29tL2RvYy9jdXJyZW50L3NlY3VyaXR5Lmh0bWwjc2VjdXJpdHktc2VjdXJpbmctY29udHJvbGxlci1hbm5vdGF0aW9ucydcbiAgICB9O1xuICAgIGNvbnRyb2xsZXJDb2RlLnF1ZXJ5U2VsZWN0b3JBbGwoJy5obGpzLW1ldGEnKS5mb3JFYWNoKChlbHQpID0+IHtcbiAgICAgICAgZWx0LmlubmVySFRNTCA9IHdyYXAoZWx0LnRleHRDb250ZW50LCBhdHRyaWJ1dGVzKTtcbiAgICB9KTtcblxuICAgIC8vIFdyYXBzIFR3aWcncyB0YWdzXG4gICAgdGVtcGxhdGVDb2RlLnF1ZXJ5U2VsZWN0b3JBbGwoJy5obGpzLXRlbXBsYXRlLXRhZyArIC5obGpzLW5hbWUnKS5mb3JFYWNoKChlbHQpID0+IHtcbiAgICAgICAgY29uc3QgdGFnID0gZWx0LnRleHRDb250ZW50O1xuICAgICAgICBpZiAoJ2Vsc2UnID09PSB0YWcgfHwgdGFnLm1hdGNoKC9eZW5kLykpIHtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuICAgICAgICBjb25zdCB1cmwgPSAnaHR0cHM6Ly90d2lnLnN5bWZvbnkuY29tL2RvYy8zLngvdGFncy8nICsgdGFnICsgJy5odG1sIycgKyB0YWc7XG4gICAgICAgIGVsdC5pbm5lckhUTUwgPSBhbmNob3IodXJsLCB0YWcpO1xuICAgIH0pO1xuXG4gICAgLy8gV3JhcHMgVHdpZydzIGZ1bmN0aW9uc1xuICAgIHRlbXBsYXRlQ29kZS5xdWVyeVNlbGVjdG9yQWxsKCcuaGxqcy10ZW1wbGF0ZS12YXJpYWJsZSA+IC5obGpzLW5hbWUnKS5mb3JFYWNoKChlbHQpID0+IHtcbiAgICAgICAgY29uc3QgZnVuYyA9IGVsdC50ZXh0Q29udGVudDtcbiAgICAgICAgY29uc3QgdXJsID0gJ2h0dHBzOi8vdHdpZy5zeW1mb255LmNvbS9kb2MvMy54L2Z1bmN0aW9ucy8nICsgZnVuYyArICcuaHRtbCMnICsgZnVuYztcbiAgICAgICAgZWx0LmlubmVySFRNTCA9IGFuY2hvcih1cmwsIGZ1bmMpO1xuICAgIH0pO1xufSk7XG4iLCJpbXBvcnQgJ2ZsYXRwaWNrcic7XG5pbXBvcnQgJ2ZsYXRwaWNrci9kaXN0L2ZsYXRwaWNrci5taW4uY3NzJztcbmltcG9ydCBsMTBuIGZyb20gXCJmbGF0cGlja3IvZGlzdC9sMTBuXCI7XG5cbmZsYXRwaWNrci5kZWZhdWx0Q29uZmlnLmFuaW1hdGUgPSB3aW5kb3cubmF2aWdhdG9yLnVzZXJBZ2VudC5pbmRleE9mKCdNU0lFJykgPT09IC0xO1xubGV0IGxhbmcgPSBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuZ2V0QXR0cmlidXRlKCdsYW5nJykgfHwgJ2VuJztcbmNvbnN0IExvY2FsZSA9IGwxMG5bYCR7bGFuZ31gXSB8fCBsMTBuLmRlZmF1bHQ7XG5mbGF0cGlja3IubG9jYWxpemUoTG9jYWxlKTtcbmNvbnN0IGNvbmZpZ3MgPSB7XG4gICAgc3RhbmRhcmQ6IHtcbiAgICAgICAgZW5hYmxlVGltZTogdHJ1ZSxcbiAgICAgICAgZGF0ZUZvcm1hdDogXCJZLW0tZCBIOmlcIixcbiAgICAgICAgYWxsb3dJbnB1dDogdHJ1ZSxcbiAgICAgICAgdGltZV8yNGhyOiB0cnVlLFxuICAgICAgICBkZWZhdWx0SG91cjogMjQsXG4gICAgICAgIHBhcnNlRGF0ZTogKGRhdGVzdHIsIGZvcm1hdCkgPT4ge1xuICAgICAgICAgICAgcmV0dXJuIGZsYXRwaWNrci5wYXJzZURhdGUoZGF0ZXN0ciwgZm9ybWF0KTtcbiAgICAgICAgfSxcbiAgICAgICAgZm9ybWF0RGF0ZTogKGRhdGUsIGZvcm1hdCwgbG9jYWxlKSA9PiB7XG4gICAgICAgICAgICByZXR1cm4gZmxhdHBpY2tyLmZvcm1hdERhdGUoZGF0ZSwgZm9ybWF0KTtcbiAgICAgICAgfVxuICAgIH1cbn07XG5cbmNvbnN0IGZsYXRwaWNrcnMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKFwiLmZsYXRwaWNrclwiKTtcbmZvciAobGV0IGkgPSAwOyBpIDwgZmxhdHBpY2tycy5sZW5ndGg7IGkrKykge1xuICAgIGxldCBlbGVtZW50ID0gZmxhdHBpY2tyc1tpXTtcbiAgICBsZXQgY29uZmlnVmFsdWUgPSBjb25maWdzW2VsZW1lbnQuZ2V0QXR0cmlidXRlKFwiZGF0YS1mbGF0cGlja3ItY2xhc3NcIildIHx8IHt9O1xuICAgIC8vIE92ZXJyaWRlcyB0aGUgZGVmYXVsdCBmb3JtYXQgd2l0aCB0aGUgb25lIHNlbnQgYnkgZGF0YSBhdHRyaWJ1dGVcbiAgICBjb25maWdWYWx1ZS5kYXRlRm9ybWF0ID0gZWxlbWVudC5nZXRBdHRyaWJ1dGUoXCJkYXRhLWRhdGUtZm9ybWF0XCIpIHx8ICdZLW0tZCBIOmknO1xuICAgIC8vIC4uLmFuZCB0aGVuIGluaXRpYWxpemUgdGhlIGZsYXRwaWNrclxuICAgIGZsYXRwaWNrcihlbGVtZW50LCBjb25maWdWYWx1ZSk7XG59XG4iLCJpbXBvcnQgaGxqcyBmcm9tICdoaWdobGlnaHQuanMvbGliL2NvcmUnO1xuaW1wb3J0IHBocCBmcm9tICdoaWdobGlnaHQuanMvbGliL2xhbmd1YWdlcy9waHAnO1xuaW1wb3J0IHR3aWcgZnJvbSAnaGlnaGxpZ2h0LmpzL2xpYi9sYW5ndWFnZXMvdHdpZyc7XG5cbmhsanMucmVnaXN0ZXJMYW5ndWFnZSgncGhwJywgcGhwKTtcbmhsanMucmVnaXN0ZXJMYW5ndWFnZSgndHdpZycsIHR3aWcpO1xuXG5obGpzLmhpZ2hsaWdodEFsbCgpO1xuIl0sIm5hbWVzIjpbInN0YXJ0U3RpbXVsdXNBcHAiLCJhcHAiLCJyZXF1aXJlIiwiZG9jdW1lbnQiLCJhZGRFdmVudExpc3RlbmVyIiwibW9kYWxFbHQiLCJxdWVyeVNlbGVjdG9yIiwiY29udHJvbGxlckNvZGUiLCJ0ZW1wbGF0ZUNvZGUiLCJhbmNob3IiLCJ1cmwiLCJjb250ZW50Iiwid3JhcCIsImxpbmtzIiwicmVwbGFjZSIsIlJlZ0V4cCIsIk9iamVjdCIsImtleXMiLCJqb2luIiwidG9rZW4iLCJxdWVyeVNlbGVjdG9yQWxsIiwiZm9yRWFjaCIsImNvbW1lbnRFbHQiLCJpbm5lckhUTUwiLCJhdHRyaWJ1dGVzIiwiZWx0IiwidGV4dENvbnRlbnQiLCJ0YWciLCJtYXRjaCIsImZ1bmMiLCJsMTBuIiwiZmxhdHBpY2tyIiwiZGVmYXVsdENvbmZpZyIsImFuaW1hdGUiLCJ3aW5kb3ciLCJuYXZpZ2F0b3IiLCJ1c2VyQWdlbnQiLCJpbmRleE9mIiwibGFuZyIsImRvY3VtZW50RWxlbWVudCIsImdldEF0dHJpYnV0ZSIsIkxvY2FsZSIsImNvbmNhdCIsImxvY2FsaXplIiwiY29uZmlncyIsInN0YW5kYXJkIiwiZW5hYmxlVGltZSIsImRhdGVGb3JtYXQiLCJhbGxvd0lucHV0IiwidGltZV8yNGhyIiwiZGVmYXVsdEhvdXIiLCJwYXJzZURhdGUiLCJkYXRlc3RyIiwiZm9ybWF0IiwiZm9ybWF0RGF0ZSIsImRhdGUiLCJsb2NhbGUiLCJmbGF0cGlja3JzIiwiaSIsImxlbmd0aCIsImVsZW1lbnQiLCJjb25maWdWYWx1ZSIsImhsanMiLCJwaHAiLCJ0d2lnIiwicmVnaXN0ZXJMYW5ndWFnZSIsImhpZ2hsaWdodEFsbCJdLCJzb3VyY2VSb290IjoiIn0=