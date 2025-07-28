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
	BaseControl,
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
			const {
				loadMore,
				infiniteScroll,
				loadMoreText,
				loadingText,
				updateUrl,
				infiniteScrollColor,
				layout,
				paginationArrow,
			} = attributes;
			const className = attributes.className || '';
			const justifyContentClass = layout?.justifyContent
				? `is-content-justification-${ layout?.justifyContent }`
				: '';

			// Update the load more attributes and add class to block.
			const updateLoadMore = () => {
				let newClassName;

				if ( loadMore ) {
					newClassName = className
						.split( ' ' )
						.filter(
							( item ) => item !== 'load-more' && item.trim()
						)
						.join( ' ' );
				} else {
					newClassName = className.trim() + ' load-more';
				}

				setAttributes( {
					loadMore: ! loadMore,
					className: newClassName,
				} );
			};

			// Update the infinite scroll attributes and add class to block.
			const updateInfiniteScroll = () => {
				let newClassName;

				if ( infiniteScroll ) {
					newClassName = className
						.split( ' ' )
						.filter(
							( item ) =>
								item !== 'infinite-scroll' && item.trim()
						)
						.join( ' ' );
				} else {
					newClassName = className.trim() + ' infinite-scroll';
				}

				setAttributes( {
					infiniteScroll: ! infiniteScroll,
					className: newClassName,
				} );
			};

			// Arrows that can be used for the load more button.
			const arrowMap = {
				none: '',
				arrow: '→',
				chevron: '»',
			};

			const displayArrow = arrowMap[ paginationArrow ];

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
								className={ loadMore && 'is-pressed' }
							/>
						</ToolbarGroup>
					</BlockControls>
					<BlockEdit { ...props } />

					{ /* If load more is selected, show a preview of either the button, or infinite scroll animation. */ }
					{ loadMore &&
						( ! infiniteScroll ? (
							<div
								className={ `is-layout-flex wp-block-buttons load-more-button-wrap ${ justifyContentClass }` }
							>
								<div className="wp-block-button">
									{ /* eslint-disable-next-line jsx-a11y/anchor-is-valid */ }
									<a
										className="wp-block-button__link wp-load-more__button"
										href="#"
									>
										{ loadMoreText }
										{ displayArrow && (
											<span
												className={ `wp-block-query-pagination-next-arrow is-arrow-${ paginationArrow }` }
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
								className={ `is-layout-flex wp-load-more__infinite-scroll  ${ justifyContentClass }` }
							>
								<div
									className="animation-wrapper"
									style={ {
										borderColor: infiniteScrollColor,
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
								checked={ loadMore }
								onChange={ () => updateLoadMore() }
							/>
							{ loadMore && (
								<>
									<ToggleControl
										label={ __(
											'Use infinite scroll?',
											'wp-load-more'
										) }
										checked={ infiniteScroll }
										onChange={ () =>
											updateInfiniteScroll()
										}
									/>
									{ infiniteScroll && (
										<>
											<BaseControl
												label={ __(
													'Loading animation color',
													'wp-load-more'
												) }
												id="infinite-scroll-color"
											/>
											<ColorPicker
												color={ infiniteScrollColor }
												onChange={ ( value ) =>
													setAttributes( {
														infiniteScrollColor:
															value,
													} )
												}
												enableAlpha
												defaultValue="#000"
											/>
										</>
									) }
									{ ! infiniteScroll && (
										<>
											<TextControl
												label={ __(
													'Loading text',
													'wp-load-more'
												) }
												value={ loadingText }
												onChange={ ( value ) =>
													setAttributes( {
														loadingText: value,
													} )
												}
											/>
										</>
									) }

									<TextControl
										label={ __(
											'Load more button text',
											'wp-load-more'
										) }
										help={ __(
											'Text to display on the load more button. Also used as the button text for screen readers when infinite scroll is enabled.',
											'wp-load-more'
										) }
										value={ loadMoreText }
										onChange={ ( value ) =>
											setAttributes( {
												loadMoreText: value,
											} )
										}
									/>

									<ToggleControl
										label={ __(
											'Update URL',
											'wp-load-more'
										) }
										help={ __(
											'Updates the URL when loading more posts. This will display the latest added posts when reloading the page.',
											'wp-load-more'
										) }
										checked={ updateUrl }
										onChange={ ( value ) =>
											setAttributes( {
												updateUrl: value,
											} )
										}
									/>
								</>
							) }
						</PanelBody>
					</InspectorControls>
				</>
			);
		};
	} )
);
