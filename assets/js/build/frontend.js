/******/ (function() { // webpackBootstrap
var __webpack_exports__ = {};
/*!***********************************!*\
  !*** ./assets/js/src/frontend.js ***!
  \***********************************/
document.addEventListener("DOMContentLoaded", function () {
  "use strict";

  let buttons = document.querySelectorAll('.wp-load-more__button');
  if (buttons.length) {
    buttons.forEach(function (button) {
      button.addEventListener('click', function (e) {
        e.preventDefault();
        let thisButton = e.target,
          container = thisButton.closest('.wp-block-query').querySelector('.wp-block-post-template'),
          url = thisButton.getAttribute('href');

        // Update button text.
        thisButton.innerText = thisButton.getAttribute('data-loading-text');

        // Load posts via AJAX from the button URL.
        let xhr = new XMLHttpRequest();
        xhr.open('GET', url);
        xhr.onload = function () {
          if (xhr.status === 200) {
            // Get the posts container.
            let temp = document.createElement('div');
            temp.innerHTML = xhr.response;
            let posts = temp.querySelector('.wp-block-post-template');

            // Update the posts container.
            container.insertAdjacentHTML('beforeend', posts.innerHTML);

            // Update the window URL.
            window.history.pushState({}, "", url);
          } else {
            console.error(xhr.statusText);
          }
        };
        xhr.onerror = function () {
          console.error(xhr.statusText);
        };
        xhr.send();

        // Remove button.
        thisButton.remove();
      });
    });
  }
});
/******/ })()
;
//# sourceMappingURL=frontend.js.map