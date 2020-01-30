// @ts-nocheck
import React, { useState } from 'react';
import moment from 'moment';
import { Button, Card, DatePicker, RangePickerValue } from 'antd';

import DeleteDatetimeButton from './DeleteDateButton';
import EditDateButton from './EditDateButton';

import { DatetimeProvider } from '../../context/DatetimeContext';
import useDatetimeItem from '../../data/queries/datetimes/useDatetimeItem';
import TicketIdTag from '../../tickets/TicketIdTag';

import { DateRangeDisplay } from '../../../shared/dateRangeInput/dateDisplay';
import { PLUS_ONE_MONTH, PLUS_TWO_MONTHS } from '../../../shared/defaultDates';

import { useEntityMutator, EntityType } from '../../../../application/services/apollo/mutations';
import useRelations from '../../../../application/services/apollo/relations/useRelations';
import { useStatus, TypeName } from '../../../../application/services/apollo/status';
import { ListItemProps } from '../../types';
import { menuStyle, cardStyle, cardDetailsStype, dateStyle, idStyle, pickerStyle } from './styles';

const { RangePicker } = DatePicker;
const dateFormat = 'YYYY/MM/DD';

const statusBgColor = (date: Datetime): string => {
	if (date.isTrashed) {
		return '#4a4843';
	}
	if (date.isExpired) {
		return '#838182';
	}
	if (date.isSoldOut) {
		return '#795d9d';
	}
	if (date.isActive) {
		return '#91ab30';
	}
	return '#0182c0';
};

const DateCard: React.FC<ListItemProps> = ({ id }): JSX.Element => {
	const [open, setOpen] = useState(0);
	const date = useDatetimeItem({ id });
	const calDateStype = { ...dateStyle, background: statusBgColor(date) };
	const { isLoaded } = useStatus();
	const { updateEntity } = useEntityMutator(EntityType.Datetime, id);
	const { getRelations } = useRelations();

	// get related ticket IDs for this datetime
	const relatedTicketIds = getRelations({
		entity: 'datetimes',
		entityId: id,
		relation: 'tickets',
	});

	const startDate: Date = moment(date.startDate) || moment(PLUS_ONE_MONTH);
	const endDate: Date = moment(date.endDate) || moment(PLUS_TWO_MONTHS);
	const defaultRangeValues: RangePickerValue = [startDate, endDate];
	const [range, setRange] = useState<RangePickerValue>(defaultRangeValues);

	const ticketsLoaded = isLoaded(TypeName.tickets);

	return date ? (
		<DatetimeProvider id={date.id}>
			<Card
				cover={
					<div style={calDateStype}>
						<DateRangeDisplay range={[range[0].toDate(), range[1].toDate()]} />
					</div>
				}
				style={cardStyle}
			>
				<div>
					<div style={menuStyle}>
						<EditDateButton id={date.id} style={{ marginBottom: '.5rem' }} />
						<Button icon='calendar' type={'default'} onClick={(): void => setOpen(2)} />
						<DeleteDatetimeButton id={date.id} disabled={!ticketsLoaded} style={{ marginTop: '.5rem' }} />
					</div>
					<div style={pickerStyle}>
						<RangePicker
							open={open > 0}
							onOk={updateEntity}
							defaultValue={range}
							onCalendarChange={(dateRange: RangePickerValue): void => setRange(dateRange)}
							format={dateFormat}
							onOpenChange={(): void => setOpen(open - 1)}
						/>
					</div>
					<div style={idStyle}>
						{date.dbId} {':'} {date.id}
					</div>
					<div style={cardDetailsStype}>
						<h3 style={{ fontWeight: 'bold' }}>{date.name}</h3>
						<h5>{date.description}</h5>
						<br />
						<div>
							{ticketsLoaded && (
								<>
									{'Related Tickets: '}{' '}
									{relatedTicketIds.filter(Boolean).map((ticketId) => (
										<TicketIdTag key={ticketId} id={ticketId} />
									))}
								</>
							)}
						</div>
					</div>
				</div>
			</Card>
		</DatetimeProvider>
	) : null;
};

export default DateCard;
