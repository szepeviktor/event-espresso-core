import { useQuery } from '@apollo/react-hooks';
import { GET_GENERAL_SETTINGS } from '.';
import { FetchEntitiesResult } from '../../../../../eventEditor/services/apollo/queries/types';
import { GeneralSettingsData } from '../../../../../../application/valueObjects/types';

const useFetchGeneralSettings = (): FetchEntitiesResult<GeneralSettingsData> => {
	const { data, error, loading } = useQuery<GeneralSettingsData>(GET_GENERAL_SETTINGS);

	return {
		data,
		error,
		loading,
	};
};

export default useFetchGeneralSettings;
