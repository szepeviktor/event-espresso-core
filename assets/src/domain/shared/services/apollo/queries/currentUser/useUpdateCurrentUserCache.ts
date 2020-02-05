import { WriteQueryOptions } from '../../../../../eventEditor/services/apollo/queries/types';
import { CacheUpdaterFn } from '../types';
import useUpdateCache from '../useUpdateCache';
import { Viewer } from '../../../../../../application/valueObjects/types';

const useUpdateCurrentUserCache = (
	writeQueryOptions: WriteQueryOptions<Viewer> = undefined
): CacheUpdaterFn<Viewer> => {
	return useUpdateCache<Viewer>(writeQueryOptions);
};

export default useUpdateCurrentUserCache;
