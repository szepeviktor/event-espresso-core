/**
 * External dependencies
 */
import { __ } from '@eventespresso/i18n';
// import { filter, identity, includes } from 'lodash';

/**
 * WordPress dependencies
 **/
// import { TreeSelect } from '@wordpress/components';
// import { withSelect } from '@wordpress/data';
import { PostTaxonomies } from '@wordpress/editor';
// import { compose, Fragment } from '@wordpress/element';
// import { buildTermsTree } from '@wordpress/utils';
// import { PostTaxonomies } from 'wp-content/plugins/gutenberg/editor/components/post-taxonomies';

/**
 * Internal dependencies
 */
// import './style.scss';

export function EventCategorySelect( { selectedCategory } ) {
	return (
		<PostTaxonomies
			postType={ selectedCategory }
		/>
	);
}


