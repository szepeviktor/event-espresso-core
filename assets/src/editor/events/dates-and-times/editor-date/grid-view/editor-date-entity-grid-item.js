/**
 * External imports
 */
import classNames from 'classnames';
import { EntityPaperFrame } from '@eventespresso/components';
import { ifValidDateEntity } from '@eventespresso/editor-hocs';
import PropTypes from 'prop-types';

/**
 * Internal dependencies
 */
import EditorDateEntityDetails from './editor-date-entity-details';
import EventDateCalendarDate from './event-date-calendar-date';
import EditorDateEntityActionsMenu
	from '../actions-menu/editor-date-entity-actions-menu';

/**
 * EditorDateGridItem
 * Component for displaying an Event Date as a visual block in grid views
 *
 * @function
 * @param {Object} dateEntity   the Event Date
 * @param {Object} eventEntity   the Event
 * @param {string} showDate
 * @param {string} showDesc
 * @param {boolean} showVenue
 * @return {Object} rendered date grid item
 */
const EditorDateEntityGridItem = ( {
	dateEntity,
	eventEntity,
	showDate,
	showDesc,
	showVenue,
} ) => {
	const dateStyleClass = classNames(
		'ee-editor-date-main',
		{
			'ee-editor-date-range': showDate === 'both',
			'ee-editor-date-single': showDate !== 'both',
		}
	);
	return (
		<EntityPaperFrame>
			<div className={ dateStyleClass }>
				<EventDateCalendarDate
					eventDate={ dateEntity }
					showDate={ showDate }
				/>
				<EditorDateEntityDetails
					eventEntity={ eventEntity }
					dateEntity={ dateEntity }
					showDesc={ showDesc }
					showVenue={ showVenue }
				/>
			</div>
			<EditorDateEntityActionsMenu
				eventEntity={ eventEntity }
				dateEntity={ dateEntity }
			/>
		</EntityPaperFrame>
	);
};

EditorDateEntityGridItem.propTypes = {
	dateEntity: PropTypes.object.isRequired,
	eventEntity: PropTypes.object.isRequired,
	showDate: PropTypes.string,
	showDesc: PropTypes.string,
	showVenue: PropTypes.bool,
};

EditorDateEntityGridItem.defaultProps = {
	showDate: 'start',
	showDesc: 'excerpt',
	showVenue: true,
};

export default ifValidDateEntity( EditorDateEntityGridItem );