/**
 * External dependencies
 */
import { shallow } from 'enzyme';
import { noop, some } from 'lodash';
import { __ } from '@eventespresso/i18n';

/**
 * WordPress dependencies
 */
import {
	createBlock,
	getBlockType,
	registerBlockType,
	getCategories,
	setCategories
} from '@wordpress/blocks';
import { BlockEdit } from '@wordpress/editor';

function mockFunctions() {
	const original = require.requireActual( '@wordpress/blocks' );
	return {
		...original, //Pass down all the exported objects
		getCategories: jest.fn( () => [
			...original.getCategories(),
			{
				slug: 'event-espresso',
				title: __( 'Event Espresso', 'event_espresso' )
			}
		] ),
	}
}
jest.mock( '@wordpress/blocks', () => mockFunctions() );

/**
 * copy of `/gutenberg/packages/block-library/src/test/helpers/index.js`
 * but using shallow rendering
 *
 * @param {string} name
 * @param {string} settings
 * @return {string} the rendered edit block
 */
export const blockEditRender = ( name, settings ) => {
	// addEspressoBlockCategories();
	if ( ! getBlockType( name ) ) {
		registerBlockType( name, settings );
	}
	const block = createBlock( name );

	return shallow(
		<BlockEdit
			name={ name }
			isSelected={ false }
			attributes={ block.attributes }
			setAttributes={ noop }
			user={ {} }
		/>,
	);
};

/**
 * adds EE custom categories to wp.blocks categories
 */
// const addEspressoBlockCategories = () => {
// 	const categories = getCategories();
// 	if ( ! some( categories, { slug: 'event-espresso' } ) ) {
// 		categories.push(
// 			{
// 				"slug": 'event-espresso',
// 				"title": __( 'Event Espresso', 'event_espresso' )
// 			}
// 		);
// 		setCategories( categories );
// 	}
// };
