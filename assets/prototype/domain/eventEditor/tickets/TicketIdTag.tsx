import React from 'react';
import { Badge } from 'antd';

import useTicketItem from '../data/queries/tickets/useTicketItem';
import { ListItemProps } from '../types';

const TicketIdTag: React.FC<ListItemProps> = ({ id }): JSX.Element => {
	const { dbId } = useTicketItem({ id }) || {};
	return dbId ? <Badge count={dbId} style={{ backgroundColor: '#aaaba6', margin: '0 .2rem' }} /> : null;
};

export default TicketIdTag;
