/**
 * Import dependencies.
 */
import { __ } from '@wordpress/i18n';
import { BlockControls, InspectorControls } from '@wordpress/block-editor';
import {
	ToolbarGroup,
	ToolbarButton,
	ToggleControl,
	PanelBody,
	TextControl,
	ColorPicker,
} from '@wordpress/components';
import { loop } from '@wordpress/icons';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';

import '../../css/src/editor.scss';
import '../../css/src/style.scss';

/**
 * Add additional controls to block toolbar.
 */
addFilter(
	'editor.BlockEdit',
	'wp-load-more/custom-controls',
	createHigherOrderComponent( ( BlockEdit ) => {
		return ( props ) => {
			// Only add controls to the Query Pagination block.
			if ( props.name !== 'core/query-pagination' ) {
				return <BlockEdit { ...props } />;
			}

			const { attributes, setAttributes } = props;

			// Update the load more attributes and add class to block.
			const updateLoadMore = () => {
				if ( attributes.loadMore ) {
					props.attributes.className =
						props.attributes.className.replace( 'load-more', '' );
				} else {
					props.attributes.className =
						props.attributes.className + ' load-more';
				}

				setAttributes( { loadMore: ! attributes.loadMore } );
			};

			// Update the infinite scroll attributes and add class to block.
			const updateInfiniteScroll = () => {
				if ( attributes.infiniteScroll ) {
					props.attributes.className =
						props.attributes.className.replace(
							'infinite-scroll',
							''
						);
				} else {
					props.attributes.className =
						props.attributes.className + ' infinite-scroll';
				}

				setAttributes( {
					infiniteScroll: ! attributes.infiniteScroll,
				} );
			};

			// Arrows that can be used for the load more button.
			const arrowMap = {
				none: '',
				arrow: '→',
				chevron: '»',
			};

			const displayArrow = arrowMap[ attributes.paginationArrow ];

			return (
				<>
					<BlockControls>
						<ToolbarGroup>
							<ToolbarButton
								icon={ loop }
								label={ __(
									'Use load more button?',
									'wp-load-more'
								) }
								onClick={ () => updateLoadMore() }
								className={
									attributes.loadMore && 'is-pressed'
								}
							/>
						</ToolbarGroup>
					</BlockControls>
					<BlockEdit { ...props } />

					{ /* If load more is selected, show a preview of either the button, or infinite scroll animation. */ }
					{ attributes.loadMore &&
						( ! attributes.infiniteScroll ? (
							<div
								className={
									'is-layout-flex wp-block-buttons load-more-button-wrap is-content-justification-' +
									attributes.layout?.justifyContent
								}
							>
								<div className="wp-block-button">
									<a
										className="wp-block-button__link wp-load-more__button"
										href="#"
									>
										{ attributes.loadMoreText }
										{ displayArrow && (
											<span
												className={ `wp-block-query-pagination-next-arrow is-arrow-${ attributes.paginationArrow }` }
												aria-hidden={ true }
											>
												{ displayArrow }
											</span>
										) }
									</a>
								</div>
							</div>
						) : (
							<div
								className={
									'is-layout-flex wp-load-more__infinite-scroll is-content-justification-' +
									attributes.layout?.justifyContent
								}
							>
								<div
									className="animation-wrapper"
									style={ {
										borderColor:
											attributes.infiniteScrollColor,
									} }
								>
									<div></div>
									<div></div>
								</div>
							</div>
						) ) }

					<InspectorControls key="setting">
						<PanelBody>
							<ToggleControl
								label={ __(
									'Use load more button?',
									'wp-load-more'
								) }
								checked={ attributes.loadMore }
								onChange={ () => updateLoadMore() }
							/>
							{ attributes.loadMore && (
								<>
									<ToggleControl
										label={ __(
											'Use infinite scroll?',
											'wp-load-more'
										) }
										checked={ attributes.infiniteScroll }
										onChange={ () =>
											updateInfiniteScroll()
										}
									/>
									{ attributes.infiniteScroll && (
										<ColorPicker
											color={
												attributes.infiniteScrollColor
											}
											onChange={ ( value ) =>
												setAttributes( {
													infiniteScrollColor: value,
												} )
											}
											enableAlpha
											defaultValue="#000"
										/>
									) }
									{ ! attributes.infiniteScroll && (
										<>
											<TextControl
												label={ __(
													'Load more button text',
													'wp-load-more'
												) }
												value={
													attributes.loadMoreText
												}
												onChange={ ( value ) =>
													setAttributes( {
														loadMoreText: value,
													} )
												}
											/>
											<TextControl
												label={ __(
													'Loading text',
													'wp-load-more'
												) }
												value={ attributes.loadingText }
												onChange={ ( value ) =>
													setAttributes( {
														loadingText: value,
													} )
												}
											/>
										</>
									) }
								</>
							) }
						</PanelBody>
					</InspectorControls>
				</>
			);
		};
	} )
);
