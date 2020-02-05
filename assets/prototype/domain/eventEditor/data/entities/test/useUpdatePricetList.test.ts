import { useApolloClient } from '@apollo/react-hooks';
import { renderHook, act } from '@testing-library/react-hooks';

import useCacheRehydration from '../../initialization/useCacheRehydration';
import useUpdatePriceList from '../useUpdatePriceList';
import usePriceQueryOptions from '../../queries/prices/usePriceQueryOptions';
import usePrices from '../../queries/prices/usePrices';
import { ApolloMockedProvider } from '../../../context/TestContext';

const timeout = 5000; // milliseconds
describe('useUpdatePriceList', () => {
	it('checks for prices cache update', async () => {
		const wrapper = ApolloMockedProvider();
		const { result, waitForNextUpdate } = renderHook(
			() => {
				useCacheRehydration();
				return {
					queryOptions: usePriceQueryOptions(),
					pricelist: usePrices(),
					cacheUpdater: useUpdatePriceList(),
					client: useApolloClient(),
				};
			},
			{
				wrapper,
			}
		);

		const pricelist = result.current.pricelist;

		const price = { ...pricelist[0], id: pricelist[0].id + '-alpha' };

		// add price to the list.
		const nodes = [...pricelist, price];

		act(() => {
			result.current.cacheUpdater({
				...result.current.queryOptions,
				data: {
					espressoPrices: {
						__typename: 'EspressoRootQueryPricesConnection',
						nodes,
					},
				},
			});
		});
		await waitForNextUpdate({ timeout });

		const cache = result.current.client.extract();
		const { result: cacheResult, waitForNextUpdate: waitForUpdate } = renderHook(
			() => {
				const client = useApolloClient();
				// restore the cache from previous render
				client.restore(cache);
				return usePrices();
			},
			{
				wrapper,
			}
		);
		await waitForUpdate({ timeout });

		const cachedPriceIds = cacheResult.current.map(({ id }) => id);

		expect(cachedPriceIds.length).toBe(pricelist.length + 1);

		expect(cachedPriceIds).toContain(price.id);
	});
});