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
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/*! no exports provided */
/***/ (function(module, exports) {

throw new Error("Module build failed (from ./node_modules/babel-loader/lib/index.js):\nSyntaxError: /Users/alexortuno/git/onlyFet/resources/js/app.js: Unexpected token, expected \",\" (310:43)\n\n\u001b[0m \u001b[90m 308 | \u001b[39m          forceTLS\u001b[33m:\u001b[39m\u001b[36mfalse\u001b[39m\u001b[33m,\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m 309 | \u001b[39m          authEndpoint\u001b[33m:\u001b[39m\u001b[32m'/api/broadcasting/auth'\u001b[39m\u001b[33m,\u001b[39m\u001b[0m\n\u001b[0m\u001b[31m\u001b[1m>\u001b[22m\u001b[39m\u001b[90m 310 | \u001b[39m           enabledTransports\u001b[33m:\u001b[39m [\u001b[32m'ws'\u001b[39m\u001b[33m,\u001b[39m \u001b[32m'wss'\u001b[39m \u001b[32m'flash'\u001b[39m]\u001b[33m,\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m     | \u001b[39m                                           \u001b[31m\u001b[1m^\u001b[22m\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m 311 | \u001b[39m          auth\u001b[33m:\u001b[39m {\u001b[0m\n\u001b[0m \u001b[90m 312 | \u001b[39m            headers\u001b[33m:\u001b[39m {\u001b[0m\n\u001b[0m \u001b[90m 313 | \u001b[39m              \u001b[33mAuthorization\u001b[39m\u001b[33m:\u001b[39m \u001b[32m`Bearer `\u001b[39m\u001b[33m+\u001b[39m token\u001b[0m\n    at Parser._raise (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:790:17)\n    at Parser.raiseWithData (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:783:17)\n    at Parser.raise (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:777:17)\n    at Parser.unexpected (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:9095:16)\n    at Parser.expect (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:9081:28)\n    at Parser.parseExprList (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:11229:14)\n    at Parser.parseArrayLike (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:11133:26)\n    at Parser.parseExprAtom (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:10434:23)\n    at Parser.parseExprSubscripts (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:10094:23)\n    at Parser.parseUpdate (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:10074:21)\n    at Parser.parseMaybeUnary (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:10063:17)\n    at Parser.parseExprOps (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:9933:23)\n    at Parser.parseMaybeConditional (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:9907:23)\n    at Parser.parseMaybeAssign (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:9870:21)\n    at /Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:9837:39\n    at Parser.allowInAnd (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:11510:12)\n    at Parser.parseMaybeAssignAllowIn (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:9837:17)\n    at Parser.parseObjectProperty (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:11055:101)\n    at Parser.parseObjPropValue (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:11080:100)\n    at Parser.parsePropertyDefinition (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:11004:10)\n    at Parser.parseObjectLike (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:10894:25)\n    at Parser.parseExprAtom (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:10445:23)\n    at Parser.parseExprSubscripts (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:10094:23)\n    at Parser.parseUpdate (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:10074:21)\n    at Parser.parseMaybeUnary (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:10063:17)\n    at Parser.parseExprOps (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:9933:23)\n    at Parser.parseMaybeConditional (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:9907:23)\n    at Parser.parseMaybeAssign (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:9870:21)\n    at /Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:9837:39\n    at Parser.allowInAnd (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:11510:12)\n    at Parser.parseMaybeAssignAllowIn (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:9837:17)\n    at Parser.parseExprListItem (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:11271:18)\n    at Parser.parseExprList (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:11241:22)\n    at Parser.parseNewArguments (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:10821:25)\n    at Parser.parseNew (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:10815:10)\n    at Parser.parseNewOrNewTarget (/Users/alexortuno/git/onlyFet/node_modules/@babel/parser/lib/index.js:10801:17)");

/***/ }),

/***/ "./resources/sass/app.scss":
/*!*********************************!*\
  !*** ./resources/sass/app.scss ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!*************************************************************!*\
  !*** multi ./resources/js/app.js ./resources/sass/app.scss ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /Users/alexortuno/git/onlyFet/resources/js/app.js */"./resources/js/app.js");
module.exports = __webpack_require__(/*! /Users/alexortuno/git/onlyFet/resources/sass/app.scss */"./resources/sass/app.scss");


/***/ })

/******/ });