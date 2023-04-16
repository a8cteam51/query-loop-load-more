/**
 * Import dependencies.
 */
import { __ } from '@wordpress/i18n';
import {
	BlockControls,
	InspectorControls
} from '@wordpress/block-editor';
import {
	ToolbarGroup,
	ToolbarButton,
	ToggleControl,
	PanelBody,
	TextControl
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
	createHigherOrderComponent((BlockEdit) => {

		return (props) => {

			// Only add controls to the Query Pagination block.
			if (props.name !== 'core/query-pagination') {
				return <BlockEdit {...props} />;
			}

			const { attributes, setAttributes } = props;

			const updateLoadMore = () => {
				if (attributes.loadMore) {
					props.attributes.className = props.attributes.className.replace('load-more', '');
				} else {
					props.attributes.className = props.attributes.className + ' load-more';
				}

				setAttributes({ loadMore: !attributes.loadMore });
			}

			return (
				<>
					<BlockControls>
						<ToolbarGroup>
							<ToolbarButton
								icon={loop}
								label={__('Use load more button?', 'wp-load-more')}
								onClick={() => updateLoadMore()}
								className={attributes.loadMore && "is-pressed"}
							/>
						</ToolbarGroup>
					</BlockControls>
					<BlockEdit {...props} />

					{attributes.loadMore &&
						<div className={ 'is-layout-flex wp-block-buttons load-more-button-wrap is-content-justification-' + attributes.layout.justifyContent }>
							<div className="wp-block-button">
								<a className="wp-block-button__link" href="#">{attributes.loadMoreText}</a>
							</div>
						</div>
					}

					<InspectorControls key="setting">
						<PanelBody>
							<ToggleControl
								label={__('Use load more button?', 'wp-load-more')}
								checked={attributes.loadMore}
								onChange={() => updateLoadMore()}
							/>
							{attributes.loadMore &&
								<>
									<TextControl
										label={__('Load more button text', 'wp-load-more')}
										value={attributes.loadMoreText}
										onChange={(value) => setAttributes({ loadMoreText: value })}
									/>
									<TextControl
										label={__('Loading text', 'wp-load-more')}
										value={attributes.loadingText}
										onChange={(value) => setAttributes({ loadingText: value })}
									/>
								</>
							}
						</PanelBody>
					</InspectorControls>
				</>
			);

		};

	})
);