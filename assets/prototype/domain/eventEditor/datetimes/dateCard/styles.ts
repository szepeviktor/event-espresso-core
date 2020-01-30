import { CSSProperties } from 'react';

export const btnStyle: CSSProperties = {
	margin: '0 0 0 .5rem',
};

export const cardStyle: CSSProperties = {
	boxShadow: '0 5px 5px -5px rgba(0,0,0,.9), 0 0 25px -15px rgba(0,0,0,.45)',
	margin: '0 0 2.5rem',
	minWidth: '360px',
	overflow: 'hidden',
	position: 'relative' as 'relative', // cast value to type
	textAlign: 'center' as 'center',
	width: '32%',
};

export const cardDetailsStype: CSSProperties = {
	boxSizing: 'border-box',
	height: '100%',
	minHeight: '13.5rem',
	padding: '0 2rem 0 120px',
	position: 'relative' as 'relative',
};

export const idStyle: CSSProperties = {
	color: 'grey',
	fontSize: '9px',
	left: '.75em',
	position: 'absolute' as 'absolute',
	top: '-1.25em',
};

export const menuStyle: CSSProperties = {
	display: 'flex',
	flexFlow: 'column nowrap',
	justifyContent: 'space-between',
	float: 'right',
	height: '100%x',
	position: 'relative' as 'relative',
	right: '-16px',
	top: '-16px',
	zIndex: 1,
};

export const pickerStyle: CSSProperties = {
	height: 0,
	left: '-16px',
	// margin: '0 0 0 50%',
	overflow: 'hidden',
	// position: 'absolute' as 'absolute',
	position: 'relative' as 'relative',
	top: '-16px',
	width: 0,
	zIndex: 2,
};

export const dateStyle: CSSProperties = {
	boxSizing: 'border-box',
	color: '#fff',
	left: 0,
	height: '1000px',
	padding: '.5rem 1rem',
	position: 'absolute' as 'absolute',
	top: 0,
	verticalAlign: 'bottom',
	width: '120px',
	zIndex: 2,
};
