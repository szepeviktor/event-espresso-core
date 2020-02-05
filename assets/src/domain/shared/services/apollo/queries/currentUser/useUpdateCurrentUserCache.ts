import { WriteQueryOptions } from '../../../../eventEditor/data/queries/types';
import { CacheUpdaterFn } from '../types';
import useUpdateCache from '../useUpdateCache';
import { Viewer } from '../../../../../application/valueObjects/config/types';

const useUpdateCurrentUserCache = (
	writeQueryOptions: WriteQueryOptions<Viewer> = undefined
): CacheUpdaterFn<Viewer> => {
	return useUpdateCache<Viewer>(writeQueryOptions);
};

export default useUpdateCurrentUserCache;