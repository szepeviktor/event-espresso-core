/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * External dependencies
 */
import { __ } from '@eventespresso/i18n';

/**
 * Internal dependencies
 */
import './style.css';

registerBlockType(
	'eventespresso/widgets-events-list',
	{
		title: __( 'Events List', 'event_espresso' ),

		description: __( 'Displays a list of events', 'event_espresso' ),

		icon: 'calendar-alt',

		category: 'widgets',

		keywords: [
			__( 'events', 'event_espresso' ),
			__( 'list', 'event_espresso' ),
		],

		attributes: {},

		edit() {
			return __( 'events list editor', 'event_espresso' );
		},

		save() {
			return null;
		},
	},
);