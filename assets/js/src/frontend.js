import domReady from '@wordpress/dom-ready';

const intersectionObserver = new window.IntersectionObserver( ( entries ) => {
	entries.forEach( ( entry ) => {
		// If intersectionRatio is 0, the target is out of view.
		if ( entry.intersectionRatio <= 0 ) {
			return;
		}

		// load posts
		fetchPosts( entry.target );
	} );
} );

/**
 * Load page from server, extract and append new posts to button's query block.
 *
 * @param {*} button
 */
const fetchPosts = ( button ) => {
	const url = button.href;
	const container = button
		.closest( '.wp-block-query' )
		?.querySelector( '.wp-block-post-template' );

	// return early if button is still loading or required data not found
	if ( button.classList.contains( 'loading' ) || ! container || ! url ) {
		return;
	}

	//set loading text and classes
	button.classList.add( 'loading' );
	if ( ! button.classList.contains( 'wp-load-more__infinite-scroll' ) ) {
		button.innerText = button.dataset.loadingText;
	}

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
			// create temporary container to load fetched HTML
			const temp = document.createElement( 'div' );
			temp.innerHTML = data;

			// get region from container
			const containerRegion = container.dataset.qllmQueryRegion || '';

			// find container in fetched HTML matching container's region
			const posts = temp.querySelector(
				`.wp-block-post-template[data-qllm-query-region="${ containerRegion }"]`
			);

			// append the posts
			if ( posts ) {
				container.insertAdjacentHTML( 'beforeend', posts.innerHTML );
			}

			const $button = button.closest( '.wp-block-button' );

			if ( $button ) {
				$button.classList.remove( 'loading' );
			}
		} )
		.catch( ( error ) => {
			//eslint-disable-next-line no-console
			console.error( 'Fetch error:', error );
		} )
		//cleanup
		.finally( () => {
			//update button attributes
			if (
				button.dataset.queryNextPage >= button.dataset.queryNextPage
			) {
				button.dataset.queryNextPage =
					+button.dataset.queryNextPage + 1;
				button.href =
					button.dataset.queryUrl + button.dataset.queryNextPage;
			}

			//reset loading text and classes
			button.classList.remove( 'loading' );

			if (
				! button.classList.contains( 'wp-load-more__infinite-scroll' )
			) {
				button.innerText = button.dataset.loadMoreText;
			}
		} );
};

/**
 * Setup buttons and add listeners when ready
 */
domReady( () => {
	'use strict';

	//load more buttons
	// prepare buttons and add listeners
	document
		.querySelectorAll(
			'.wp-load-more__button:not(.wp-load-more__infinite-scroll)'
		)
		.forEach( function ( button ) {
			// store load more text
			if ( button.dataset.loadMoreText === undefined ) {
				button.dataset.loadMoreText = button.innerText;
			}

			//add listener
			button.addEventListener( 'click', function ( e ) {
				e.preventDefault();

				fetchPosts( e.target );
			} );
		} );

	// infinite scroll
	// add listeners
	document
		.querySelectorAll( '.wp-load-more__infinite-scroll' )
		.forEach( function ( button ) {
			intersectionObserver.observe( button );
		} );
} );
