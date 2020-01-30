import React from 'react';
import classNames from 'classnames';
import { format, isValid } from 'date-fns';

interface DateRangeDisplayProps {
	className?: string;
	range: [Date, Date];
}

interface DateTagProps {
	date: Date;
}

export const DateTag: React.FC<DateTagProps> = ({ date }): JSX.Element => {
	if (isValid(date)) {
		return (
			<>
				<div style={{ fontSize: '.75rem', letterSpacing: '1px', lineHeight: '1rem' }}>
					{format(date, 'eeee')}
				</div>
				<div style={{ fontSize: '1.1rem', letterSpacing: '1px', lineHeight: '1.1rem' }}>
					{format(date, 'MMMM')}
				</div>
				<div style={{ fontSize: '2.3rem', fontWeight: 'bold', lineHeight: '2.4rem' }}>{format(date, 'dd')}</div>
				<div style={{ fontSize: '1rem', fontWeight: 'bold', letterSpacing: '1px', lineHeight: '1.2rem' }}>
					{format(date, 'yyyy')}
				</div>
				<div style={{ fontSize: '.75rem', letterSpacing: '1px', lineHeight: '1rem' }}>
					@ {format(date, 'H:mm a')}
				</div>
			</>
		);
	}
	return <span>{'no date'}</span>;
};

export const DateRangeDisplay: React.FC<DateRangeDisplayProps> = ({ className, range: [start, end] }): JSX.Element => {
	return (
		<div className={classNames('docs-date-range', className)} style={{ textAlign: 'center' }}>
			<DateTag date={start} />
			<span style={{ fontSize: '.75em', lineHeight: '1.5rem' }}>{'to'}</span>
			<DateTag date={end} />
		</div>
	);
};
