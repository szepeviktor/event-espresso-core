/**
 * Internal dependencies
 */
// import { nodes as dates } from '../../../../../eventEditor/data/queries/datetimes/test/data';
import { nodes as dates } from '../../../../eventEditor/data/queries/datetimes/test/data';
import { nodes as tickets } from '../../../../eventEditor/data/queries/tickets/test/data';
import { entityDbId } from './index';

describe.only('entityDbId', () => {
	it('should lowercase first letter of a string', () => {
		entityDbId('ds');

		expect(lcFirst('Test')).toBe('test');
	});
});
