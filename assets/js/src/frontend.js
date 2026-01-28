import domReady from '@wordpress/dom-ready';

const qllmLoadStart = new Event( 'qllmLoadStart' );
const qllmLoadEnd = new Event( 'qllmLoadEnd' );

const getUrlWithParam = ( param = '', value = '' ) => {
	// new URL based on current URL
	const url = new URL( window.location.href );

	if ( param !== '' ) {
		url.searchParams.set( param, value );
	}

	return url;
};

const intersectionObserver = new window.IntersectionObserver(
	( entries ) => {
		entries.forEach( ( entry ) => {
			// load posts
			if ( entry.isIntersecting ) {
				fetchPosts( entry.target );
			}
		} );
	},
	{
		threshold: 0.5,
		rootMargin: '0px',
	}
);

/**
 * Load page from server, extract and append new posts to button's query block.
 *
 * @param {*} target
 */
const fetchPosts = ( target ) => {
	const button = target?.closest( '.wp-load-more__button' );

	if ( ! button ) {
		return;
	}
	const container = button.closest( '.wp-block-query' );

	// return early if button is still loading or required data not found
	if (
		button.classList.contains( 'loading' ) ||
		! container ||
		! button.dataset.queryUrl ||
		! button.dataset.queryNextPage
	) {
		return;
	}

	const fetchUrl = getUrlWithParam(
		button.dataset.queryUrl,
		button.dataset.queryNextPage
	);

	//set loading text and classes
	button.classList.add( 'loading' );

	//dispatch event
	document.dispatchEvent( qllmLoadStart );

	// Load posts via fetch from the button URL.
	fetch( fetchUrl, {
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
				`.wp-block-query[data-qllm-query-region="${ containerRegion }"] .wp-block-post-template`
			);

			// append the posts
			const targetTpl = container.querySelector(
				'.wp-block-post-template'
			);
			if ( targetTpl && posts ) {
				targetTpl.insertAdjacentHTML( 'beforeend', posts.innerHTML );
			}

			const buttonElement = button.closest( '.wp-block-button' );

			if ( buttonElement ) {
				buttonElement.classList.remove( 'loading' );
			}

			const queryNextPage = +button.dataset.queryNextPage;
			const queryMaxPage = +button.dataset.queryMaxPage;

			//update URL
			if ( button.dataset.updateUrl ) {
				const newUrl = getUrlWithParam(
					button.dataset.queryUrl,
					queryNextPage
				);

				window.history.pushState( {}, '', newUrl );
			}

			//no more posts available -> remove button
			if ( queryNextPage >= queryMaxPage ) {
				if (
					button.classList.contains( 'wp-load-more__infinite-scroll' )
				) {
					intersectionObserver.unobserve( button );
				}

				button.closest( '.wp-block-buttons' )?.remove();

				return;
			}

			//update button attributes
			if ( queryNextPage < queryMaxPage ) {
				button.dataset.queryNextPage = queryNextPage + 1;

				const url = getUrlWithParam(
					button.dataset.queryUrl,
					button.dataset.queryNextPage
				);

				button.href = url.toString();
			}
		} )
		.catch( ( error ) => {
			//eslint-disable-next-line no-console
			console.error( 'Fetch error:', error );
		} )
		//cleanup
		.finally( () => {
			//reset loading text and classes
			button.classList.remove( 'loading' );

			//dispatch event
			document.dispatchEvent( qllmLoadEnd );

			if (
				button.classList.contains( 'wp-load-more__infinite-scroll' )
			) {
				const bcr = button.getBoundingClientRect();

				// fix not triggering the callback if the button is still visible
				// if button is visible - toggle observing to ensure the
				// Intersection observer triggers the callback again
				if ( bcr.bottom > 0 && bcr.top < window.innerHeight ) {
					intersectionObserver.unobserve( button );
					intersectionObserver.observe( button );
				}
			}
		} );
};

/**
 * Setup buttons and add listeners when ready
 */
domReady( () => {
	'use strict';

	// load more buttons
	// add listeners
	document.addEventListener( 'click', function ( e ) {
		const btn = e.target.closest(
			'.wp-load-more__button:not(.wp-load-more__infinite-scroll)'
		);
		if ( ! btn ) {
			return;
		}
		e.preventDefault();
		fetchPosts( btn );
	} );

	// infinite scroll
	// add listeners
	document
		.querySelectorAll( '.wp-load-more__infinite-scroll' )
		.forEach( function ( button ) {
			intersectionObserver.observe( button );
		} );
} );
