import React, { useCallback } from 'react';
import { Button } from 'antd';

import { useEntityMutator, EntityType } from '../../../../application/services/apollo/mutations';
import { ListItemProps } from '../../types';

type fn = () => void;

const DeleteDateButton: React.FC<ListItemProps> = ({ id, ...rest }) => {
	const { deleteEntity } = useEntityMutator(EntityType.Datetime, id);

	const onClick = useCallback<fn>(() => {
		deleteEntity();
	}, []);

	return <Button icon={'delete'} type={'default'} onClick={onClick} {...rest} />;
};

export default DeleteDateButton;
