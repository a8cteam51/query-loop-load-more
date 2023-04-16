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

                // Load posts via fetch from the button URL.
                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'text/html'
                    }
                }).then(function (response) {
                    if (response.ok) {
                        return response.text();
                    } else {
                        throw new Error('Network response was not ok.');
                    }
                }).then(function (data) {

                    // Get the posts container.
                    let temp = document.createElement('div');
                    temp.innerHTML = data;
                    let posts = temp.querySelector('.wp-block-post-template');

                    // Update the posts container.
                    container.insertAdjacentHTML('beforeend', posts.innerHTML);

                    // Update the window URL.
                    window.history.pushState({}, "", url);

                    // Remove button.
                    thisButton.remove();
                }).catch(function (error) {
                    console.error('Fetch error:', error);
                });
            });
        });
    }
});
