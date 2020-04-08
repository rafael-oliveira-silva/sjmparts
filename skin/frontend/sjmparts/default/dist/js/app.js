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

/***/ "./src/scss/app.scss":
/*!***************************!*\
  !*** ./src/scss/app.scss ***!
  \***************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvc2Nzcy9hcHAuc2Nzcz9lMzI0Il0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBIiwiZmlsZSI6Ii4vc3JjL3Njc3MvYXBwLnNjc3MuanMiLCJzb3VyY2VzQ29udGVudCI6WyIvLyByZW1vdmVkIGJ5IGV4dHJhY3QtdGV4dC13ZWJwYWNrLXBsdWdpbiJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./src/scss/app.scss\n");

/***/ }),

/***/ "./src/ts/app.ts":
/*!***********************!*\
  !*** ./src/ts/app.ts ***!
  \***********************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\nObject.defineProperty(exports, \"__esModule\", { value: true });\n__webpack_require__(/*! ./components/lightbox */ \"./src/ts/components/lightbox.ts\");\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvdHMvYXBwLnRzP2VkOGUiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6Ijs7QUFBQSxvRkFBK0IiLCJmaWxlIjoiLi9zcmMvdHMvYXBwLnRzLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0ICcuL2NvbXBvbmVudHMvbGlnaHRib3gnO1xuIl0sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./src/ts/app.ts\n");

/***/ }),

/***/ "./src/ts/components/lightbox.ts":
/*!***************************************!*\
  !*** ./src/ts/components/lightbox.ts ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\nvar Lightbox = /** @class */ (function () {\n    function Lightbox() {\n        this.settings = {\n            target: 'lightbox-container'\n        };\n    }\n    Object.defineProperty(Lightbox.prototype, \"containers\", {\n        get: function () {\n            var targets = document.querySelectorAll(\".\" + this.settings.target);\n            return targets;\n        },\n        enumerable: true,\n        configurable: true\n    });\n    Lightbox.prototype.init = function () {\n        var _this = this;\n        this.containers.forEach(function (target) { return _this.attachEvents(target); });\n    };\n    Lightbox.prototype.attachEvents = function (target) {\n        var _this = this;\n        target.addEventListener('click', function (e) { return _this.closeOnClickOut(e.target); });\n        var closeButton = target.querySelector('button');\n        if (closeButton === null) {\n            return;\n        }\n        closeButton.addEventListener('click', function (e) {\n            var element = e.currentTarget;\n            if (!(element instanceof HTMLElement)) {\n                return;\n            }\n            var parentContainer = element.closest(\".\" + _this.settings.target);\n            if (parentContainer === null) {\n                return;\n            }\n            _this.hide(parentContainer);\n        });\n    };\n    /**\n     * Identify if event is valid for closing lightbox container\n     *\n     * @param {(EventTarget|null)} eventTarget\n     * @returns {void}\n     * @memberof Lightbox\n     */\n    Lightbox.prototype.closeOnClickOut = function (eventTarget) {\n        var element = eventTarget;\n        if (!(element instanceof HTMLElement)) {\n            return;\n        }\n        var isValid = element.classList.contains(this.settings.target);\n        if (isValid) {\n            this.hide(element);\n        }\n    };\n    /**\n     * Hide lightbox container by element\n     *\n     * @param {HTMLElement} target\n     * @memberof Lightbox\n     */\n    Lightbox.prototype.hide = function (target) {\n        target.classList.add('hidden');\n    };\n    /**\n     * Show lightbox by parent element\n     *\n     * @param {HTMLElement} target\n     * @memberof Lightbox\n     */\n    Lightbox.prototype.show = function (target) {\n        target.classList.add('hidden');\n    };\n    return Lightbox;\n}());\nvar lightbox = new Lightbox();\nlightbox.init();\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvdHMvY29tcG9uZW50cy9saWdodGJveC50cz9iMzdmIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiI7QUFBQTtJQUFBO1FBQ1ksYUFBUSxHQUFxQjtZQUNqQyxNQUFNLEVBQUUsb0JBQW9CO1NBQy9CLENBQUM7SUEyRU4sQ0FBQztJQXpFRyxzQkFBSSxnQ0FBVTthQUFkO1lBQ0ksSUFBTSxPQUFPLEdBQStCLFFBQVEsQ0FBQyxnQkFBZ0IsQ0FBQyxNQUFJLElBQUksQ0FBQyxRQUFRLENBQUMsTUFBUSxDQUFDLENBQUM7WUFFbEcsT0FBTyxPQUFPLENBQUM7UUFDbkIsQ0FBQzs7O09BQUE7SUFFRCx1QkFBSSxHQUFKO1FBQUEsaUJBRUM7UUFERyxJQUFJLENBQUMsVUFBVSxDQUFDLE9BQU8sQ0FBQyxVQUFDLE1BQU0sSUFBSyxZQUFJLENBQUMsWUFBWSxDQUFDLE1BQU0sQ0FBQyxFQUF6QixDQUF5QixDQUFDLENBQUM7SUFDbkUsQ0FBQztJQUVELCtCQUFZLEdBQVosVUFBYSxNQUFtQjtRQUFoQyxpQkF1QkM7UUF0QkcsTUFBTSxDQUFDLGdCQUFnQixDQUFDLE9BQU8sRUFBRSxVQUFDLENBQVEsSUFBSyxZQUFJLENBQUMsZUFBZSxDQUFDLENBQUMsQ0FBQyxNQUFNLENBQUMsRUFBOUIsQ0FBOEIsQ0FBQyxDQUFDO1FBRS9FLElBQU0sV0FBVyxHQUE2QixNQUFNLENBQUMsYUFBYSxDQUFDLFFBQVEsQ0FBQyxDQUFDO1FBQzdFLElBQUksV0FBVyxLQUFLLElBQUksRUFBRTtZQUN0QixPQUFPO1NBQ1Y7UUFFRCxXQUFXLENBQUMsZ0JBQWdCLENBQUMsT0FBTyxFQUFFLFVBQUMsQ0FBUTtZQUMzQyxJQUFNLE9BQU8sR0FBRyxDQUFDLENBQUMsYUFBYSxDQUFDO1lBRWhDLElBQUksQ0FBQyxDQUFDLE9BQU8sWUFBWSxXQUFXLENBQUMsRUFBRTtnQkFDbkMsT0FBTzthQUNWO1lBRUQsSUFBTSxlQUFlLEdBQTBCLE9BQU8sQ0FBQyxPQUFPLENBQUMsTUFBSSxLQUFJLENBQUMsUUFBUSxDQUFDLE1BQVEsQ0FBQyxDQUFDO1lBRTNGLElBQUksZUFBZSxLQUFLLElBQUksRUFBRTtnQkFDMUIsT0FBTzthQUNWO1lBRUQsS0FBSSxDQUFDLElBQUksQ0FBQyxlQUFlLENBQUMsQ0FBQztRQUMvQixDQUFDLENBQUMsQ0FBQztJQUNQLENBQUM7SUFFRDs7Ozs7O09BTUc7SUFDSCxrQ0FBZSxHQUFmLFVBQWdCLFdBQStCO1FBQzNDLElBQU0sT0FBTyxHQUFHLFdBQVcsQ0FBQztRQUM1QixJQUFJLENBQUMsQ0FBQyxPQUFPLFlBQVksV0FBVyxDQUFDLEVBQUU7WUFDbkMsT0FBTztTQUNWO1FBRUQsSUFBTSxPQUFPLEdBQUcsT0FBTyxDQUFDLFNBQVMsQ0FBQyxRQUFRLENBQUMsSUFBSSxDQUFDLFFBQVEsQ0FBQyxNQUFNLENBQUMsQ0FBQztRQUNqRSxJQUFJLE9BQU8sRUFBRTtZQUNULElBQUksQ0FBQyxJQUFJLENBQUMsT0FBTyxDQUFDLENBQUM7U0FDdEI7SUFDTCxDQUFDO0lBRUQ7Ozs7O09BS0c7SUFDSCx1QkFBSSxHQUFKLFVBQUssTUFBbUI7UUFDcEIsTUFBTSxDQUFDLFNBQVMsQ0FBQyxHQUFHLENBQUMsUUFBUSxDQUFDLENBQUM7SUFDbkMsQ0FBQztJQUVEOzs7OztPQUtHO0lBQ0gsdUJBQUksR0FBSixVQUFLLE1BQW1CO1FBQ3BCLE1BQU0sQ0FBQyxTQUFTLENBQUMsR0FBRyxDQUFDLFFBQVEsQ0FBQyxDQUFDO0lBQ25DLENBQUM7SUFDTCxlQUFDO0FBQUQsQ0FBQztBQUVELElBQU0sUUFBUSxHQUFHLElBQUksUUFBUSxFQUFFLENBQUM7QUFDaEMsUUFBUSxDQUFDLElBQUksRUFBRSxDQUFDIiwiZmlsZSI6Ii4vc3JjL3RzL2NvbXBvbmVudHMvbGlnaHRib3gudHMuanMiLCJzb3VyY2VzQ29udGVudCI6WyJjbGFzcyBMaWdodGJveCB7XG4gICAgcHJpdmF0ZSBzZXR0aW5nczogTGlnaHRib3hTZXR0aW5ncyA9IHtcbiAgICAgICAgdGFyZ2V0OiAnbGlnaHRib3gtY29udGFpbmVyJ1xuICAgIH07XG5cbiAgICBnZXQgY29udGFpbmVycygpOiBOb2RlTGlzdE9mPEhUTUxEaXZFbGVtZW50PiB7XG4gICAgICAgIGNvbnN0IHRhcmdldHM6IE5vZGVMaXN0T2Y8SFRNTERpdkVsZW1lbnQ+ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbChgLiR7dGhpcy5zZXR0aW5ncy50YXJnZXR9YCk7XG5cbiAgICAgICAgcmV0dXJuIHRhcmdldHM7XG4gICAgfVxuXG4gICAgaW5pdCgpOiB2b2lkIHtcbiAgICAgICAgdGhpcy5jb250YWluZXJzLmZvckVhY2goKHRhcmdldCkgPT4gdGhpcy5hdHRhY2hFdmVudHModGFyZ2V0KSk7XG4gICAgfVxuXG4gICAgYXR0YWNoRXZlbnRzKHRhcmdldDogSFRNTEVsZW1lbnQpOiB2b2lkIHtcbiAgICAgICAgdGFyZ2V0LmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgKGU6IEV2ZW50KSA9PiB0aGlzLmNsb3NlT25DbGlja091dChlLnRhcmdldCkpO1xuXG4gICAgICAgIGNvbnN0IGNsb3NlQnV0dG9uOiBIVE1MQnV0dG9uRWxlbWVudCB8IG51bGwgPSB0YXJnZXQucXVlcnlTZWxlY3RvcignYnV0dG9uJyk7XG4gICAgICAgIGlmIChjbG9zZUJ1dHRvbiA9PT0gbnVsbCkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG5cbiAgICAgICAgY2xvc2VCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoZTogRXZlbnQpID0+IHtcbiAgICAgICAgICAgIGNvbnN0IGVsZW1lbnQgPSBlLmN1cnJlbnRUYXJnZXQ7XG5cbiAgICAgICAgICAgIGlmICghKGVsZW1lbnQgaW5zdGFuY2VvZiBIVE1MRWxlbWVudCkpIHtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGNvbnN0IHBhcmVudENvbnRhaW5lcjogSFRNTERpdkVsZW1lbnQgfCBudWxsID0gZWxlbWVudC5jbG9zZXN0KGAuJHt0aGlzLnNldHRpbmdzLnRhcmdldH1gKTtcblxuICAgICAgICAgICAgaWYgKHBhcmVudENvbnRhaW5lciA9PT0gbnVsbCkge1xuICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgdGhpcy5oaWRlKHBhcmVudENvbnRhaW5lcik7XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIElkZW50aWZ5IGlmIGV2ZW50IGlzIHZhbGlkIGZvciBjbG9zaW5nIGxpZ2h0Ym94IGNvbnRhaW5lclxuICAgICAqXG4gICAgICogQHBhcmFtIHsoRXZlbnRUYXJnZXR8bnVsbCl9IGV2ZW50VGFyZ2V0XG4gICAgICogQHJldHVybnMge3ZvaWR9XG4gICAgICogQG1lbWJlcm9mIExpZ2h0Ym94XG4gICAgICovXG4gICAgY2xvc2VPbkNsaWNrT3V0KGV2ZW50VGFyZ2V0OiBFdmVudFRhcmdldCB8IG51bGwpOiB2b2lkIHtcbiAgICAgICAgY29uc3QgZWxlbWVudCA9IGV2ZW50VGFyZ2V0O1xuICAgICAgICBpZiAoIShlbGVtZW50IGluc3RhbmNlb2YgSFRNTEVsZW1lbnQpKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICBjb25zdCBpc1ZhbGlkID0gZWxlbWVudC5jbGFzc0xpc3QuY29udGFpbnModGhpcy5zZXR0aW5ncy50YXJnZXQpO1xuICAgICAgICBpZiAoaXNWYWxpZCkge1xuICAgICAgICAgICAgdGhpcy5oaWRlKGVsZW1lbnQpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogSGlkZSBsaWdodGJveCBjb250YWluZXIgYnkgZWxlbWVudFxuICAgICAqXG4gICAgICogQHBhcmFtIHtIVE1MRWxlbWVudH0gdGFyZ2V0XG4gICAgICogQG1lbWJlcm9mIExpZ2h0Ym94XG4gICAgICovXG4gICAgaGlkZSh0YXJnZXQ6IEhUTUxFbGVtZW50KTogdm9pZCB7XG4gICAgICAgIHRhcmdldC5jbGFzc0xpc3QuYWRkKCdoaWRkZW4nKTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBTaG93IGxpZ2h0Ym94IGJ5IHBhcmVudCBlbGVtZW50XG4gICAgICpcbiAgICAgKiBAcGFyYW0ge0hUTUxFbGVtZW50fSB0YXJnZXRcbiAgICAgKiBAbWVtYmVyb2YgTGlnaHRib3hcbiAgICAgKi9cbiAgICBzaG93KHRhcmdldDogSFRNTEVsZW1lbnQpOiB2b2lkIHtcbiAgICAgICAgdGFyZ2V0LmNsYXNzTGlzdC5hZGQoJ2hpZGRlbicpO1xuICAgIH1cbn1cblxuY29uc3QgbGlnaHRib3ggPSBuZXcgTGlnaHRib3goKTtcbmxpZ2h0Ym94LmluaXQoKTtcblxuaW50ZXJmYWNlIExpZ2h0Ym94U2V0dGluZ3Mge1xuICAgIHRhcmdldDogc3RyaW5nO1xufVxuIl0sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./src/ts/components/lightbox.ts\n");

/***/ }),

/***/ 0:
/*!*************************************************!*\
  !*** multi ./src/ts/app.ts ./src/scss/app.scss ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /Users/rafael.oliveira/www/sjm-parts-loja/skin/frontend/sjmparts/default/src/ts/app.ts */"./src/ts/app.ts");
module.exports = __webpack_require__(/*! /Users/rafael.oliveira/www/sjm-parts-loja/skin/frontend/sjmparts/default/src/scss/app.scss */"./src/scss/app.scss");


/***/ })

/******/ });