// Keep a record of the page we have loaded, per query.
const pages = {};

/**
 * Gets the query and page from a URL string
 * @param {string} str The query string for url
 * @returns {query: number, page: number} | null
 */
const extractQueryParams = ( str ) => {
	// Default values
	let query = null;
	let page = null;

	// Regular expressions to match query and page
	const queryMatch = str.match( /query-(\d+)/ );
	const pageMatch = str.match( /page=(\d+)/ );

	// Extract and convert to integer if matches are found
	if ( queryMatch ) {
		query = parseInt( queryMatch[ 1 ], 10 );
	}

	if ( pageMatch ) {
		page = parseInt( pageMatch[ 1 ], 10 );
	}

	// If either are null, return null
	if ( query === null || page === null ) {
		return null;
	}

	return { query, page };
};

/**
 * Checks if a given query and page is in the pages object
 *
 * @param {number} query The query ID
 * @param {number} page The page number
 *
 * @returns {boolean}
 */
const isPageLoaded = ( query, page ) => {
	if ( pages[ query ] && pages[ query ].includes( page ) ) {
		return true;
	} else {
		return false;
	}
};

/**
 * Logs a given query and page to the pages object
 *
 * @param {number} query The query ID
 * @param {number} page The page number
 *
 * @returns {void}
 */
const logPage = ( query, page ) => {
	if ( ! pages[ query ] ) {
		pages[ query ] = [];
	}

	pages[ query ].push( page );
};

const intersectionObserver = new IntersectionObserver( ( entries ) => {
	// If intersectionRatio is 0, the target is out of view.
	if ( entries[ 0 ].intersectionRatio <= 0 ) return;

	const $url = entries[ 0 ].target.href,
		$container = entries[ 0 ].target
			.closest( '.wp-block-query' )
			.querySelector( '.wp-block-post-template' ),
		$clickedButton = entries[ 0 ].target;

	// Get the query and page from the button href.
	const queryLoopParams = extractQueryParams(
		$clickedButton.getAttribute( 'href' )
	);

	// If we have a page and its not already in the pages array, add it.
	if (
		queryLoopParams &&
		isPageLoaded( queryLoopParams.query, queryLoopParams.page ) === false
	) {
		logPage( queryLoopParams.query, queryLoopParams.page );
		fetchPosts( $url, $container, $clickedButton );
	}
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

			const $button = clickedButton.closest('.wp-block-button');

			console.log( $button );

			if ( $button ) {
				$button.classList.remove( 'loading' );
			}

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

				const $button = thisButton.closest('.wp-block-button');

				if ( $button ) {
					$button.classList.add( 'loading' );
				}

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
