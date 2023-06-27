const intersectionObserver = new IntersectionObserver( ( entries ) => {
	// If intersectionRatio is 0, the target is out of view.
	if ( entries[ 0 ].intersectionRatio <= 0 ) return;

	const $url = entries[ 0 ].target.href,
		$container = entries[ 0 ].target
			.closest( '.wp-block-query' )
			.querySelector( '.wp-block-post-template' ),
		$clickedButton = entries[ 0 ].target;

	fetchPosts( $url, $container, $clickedButton );
} );

/**
 *
 * @param {*} url
 * @param {*} container
 * @param {*} clickedButton
 */
const fetchPosts = ( url, container, clickedButton ) => {
	showLoader();

	// Load posts via fetch from the button URL.
	fetch( url, {
		method: 'GET',
		headers: {
			'Content-Type': 'text/html',
		},
	} )
		.then( function ( response ) {
			if ( response.ok ) {
				return response.text();
			}
			throw new Error( 'Network response was not ok.' );
		} )
		.then( function ( data ) {
			// Get the posts container.
			const temp = document.createElement( 'div' );
			temp.innerHTML = data;
			const posts = temp.querySelector( '.wp-block-post-template' );

			// Update the posts container.
			container.insertAdjacentHTML( 'beforeend', posts.innerHTML );

			// Update the window URL.
			window.history.pushState( {}, '', url );

			// Remove button.
			clickedButton.remove();

			hideLoader();
		} )
		.catch( function ( error ) {
			console.error( 'Fetch error:', error );
		} );
};

/**
 * Show the infinite scroll loader.
 */
const showLoader = () => {
	const $loader = document.querySelectorAll(
		'.wp-load-more__infinite-scroll'
	);

	if ( ! $loader?.length ) {
		return;
	}

	$loader[ 0 ].classList.add( 'loading' );
};

/**
 * Hide the infinite scroll loader.
 */
const hideLoader = () => {
	const $loader = document.querySelectorAll(
		'.wp-load-more__infinite-scroll'
	);

	if ( ! $loader?.length ) {
		return;
	}

	$loader[ 0 ].classList.remove( 'loading' );
	intersectionObserver.observe(
		document.querySelector( '.wp-load-more__button' )
	);
};

/**
 *
 */
document.addEventListener( 'DOMContentLoaded', function () {
	'use strict';

	// Cache selectors.
	const buttons = document.querySelectorAll( '.wp-load-more__button' );
	const infiniteScroll = document.querySelectorAll(
		'.wp-load-more__infinite-scroll'
	);

	// Attach handlers all to all load more buttons.
	if ( buttons?.length ) {
		buttons.forEach( function ( button ) {
			button.addEventListener( 'click', function ( e ) {
				e.preventDefault();

				const thisButton = e.target,
					container = thisButton
						.closest( '.wp-block-query' )
						.querySelector( '.wp-block-post-template' ),
					url = thisButton.getAttribute( 'href' );

				// Update button text.
				thisButton.innerText =
					thisButton.getAttribute( 'data-loading-text' );

				fetchPosts( url, container, thisButton );
			} );
		} );
	}

	// Add infinite scroll watchers if infinite scroll is enabled.
	if ( infiniteScroll?.length ) {
		// start observing
		intersectionObserver.observe(
			document.querySelector( '.wp-load-more__button' )
		);
	}
} );
