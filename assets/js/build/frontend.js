/******/ (function() { // webpackBootstrap
var __webpack_exports__ = {};
/*!***********************************!*\
  !*** ./assets/js/src/frontend.js ***!
  \***********************************/
jQuery(document).ready(function () {
  "use strict";

  let $buttons = jQuery('.wp-load-more__button');
  if ($buttons.length) {
    $buttons.on('click', function (e) {
      e.preventDefault();
      let $this = jQuery(this),
        $container = $this.parents('.wp-block-query').find('.wp-block-post-template'),
        $url = $this.attr('href');

      // Update button text.
      $this.text($this.attr('data-loading-text'));

      // Load posts via AJAX from the button URL.
      jQuery.ajax({
        url: $url,
        type: 'GET',
        dataType: 'html',
        success: function (data) {
          // Get the posts container.
          let $posts = jQuery(data).find('.wp-block-post-template');

          // Update the posts container.
          $container.append($posts.html());

          // Update the window URL.
          window.history.pushState({}, "", $url);
        },
        complete: function () {
          $this.remove();
        }
      });
    });
  }
});
/******/ })()
;
//# sourceMappingURL=frontend.js.map