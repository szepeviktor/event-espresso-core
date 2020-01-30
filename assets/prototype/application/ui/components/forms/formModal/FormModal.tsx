import React, { CSSProperties, useState } from 'react';
import classNames from 'classnames';
import { Form } from 'react-final-form';
// import {Classes, Overlay} from '@blueprintjs/core/lib/esm';
// import { Modal } from 'carbon-components-react';

import { FormModalProps } from './types';
import FormModalForm from './FormModalForm';

import './styles.css';

const FormModal: React.FC<FormModalProps> = ({
	FormComponent,
	initialValues,
	onSubmit,
	onClose,
	isOpen,
	...extraProps
}) => {
	const [loading, setLoading] = useState(false);

	const submitHandler = async (formData) => {
		console.log('%c submitHandler', 'color: LimeGreen;');
		console.log('%c 	formData', 'color: LimeGreen;', formData);
		setLoading(true);
		const results = await onSubmit(formData);
		console.log('%c 		results', 'color: LimeGreen;', results);
		setLoading(results.loading);
		onClose();
	};
	// const overlayProps = {
	// 	autoFocus: true,
	// 	canEscapeKeyClose: true,
	// 	canOutsideClickClose: true,
	// 	enforceFocus: true,
	// 	hasBackdrop: true,
	// 	usePortal: true,
	// 	useTallContent: false,
	// 	portalClassName: 'ee-form-modal',
	// };

	// const onCloseHandler = (e?: React.SyntheticEvent) => {
	// 	e.preventDefault();
	// 	e.stopPropagation();
	// 	onClose();
	// };

	// const classes = classNames(Classes.CARD, Classes.ELEVATION_4);

	// const overlayStyle: CSSProperties = {
	// 	boxSizing: 'border-box',
	// 	maxHeight: '90%',
	// 	maxWidth: '1200px',
	// 	minHeight: '50%',
	// 	minWidth: '320px',
	// 	width: '80%',
	// 	overflowY: 'scroll',
	// 	position: 'absolute' as 'absolute',
	// 	left: '50%',
	// 	top: '50%',
	// 	transform: 'translate(-50%, -50%)',
	// };

	return (
		initialValues && (
			<Form
				onSubmit={submitHandler}
				initialValues={initialValues}
				{...extraProps}
				render={({ ...formProps }) => (
					<FormModalForm
						{...formProps}
						FormComponent={FormComponent}
						isOpen={isOpen}
						loading={loading}
						onClose={onClose}
					/>
				)}
			/>
		)
	);
};

export default FormModal;
