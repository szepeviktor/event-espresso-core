/**
 * Internal dependencies
 */
import { filterFn } from '../percentSoldAtOrAbove';
import { Ticket } from '../../../../../../eventEditor/data/types';

const soldOutOnly = (tickets: Ticket[]): Ticket[] => {
	return tickets.filter((ticket) => {
		return ticket.isSoldOut || filterFn({ percentage: 100, ticket });
	});
};

export default soldOutOnly;