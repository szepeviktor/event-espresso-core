import React, { useEffect, useState, CSSProperties } from 'react';
import { Modal } from 'antd';
import 'antd/dist/antd.css';

import { FormModalFormProps } from './types';
import { EspressoButton, EspressoButtonStyle } from '../../input/espressoButton';

const formStyle: CSSProperties = {
	boxSizing: 'border-box',
	padding: '1em 2em',
};

const btnRowStyle: CSSProperties = {
	boxSizing: 'border-box',
	padding: '1em 2em',
	textAlign: 'right',
};

const dataStyle: CSSProperties = {
	borderRadius: '5px',
	boxSizing: 'border-box',
	padding: '1em 2em',
	color: '#a9ce47',
	backgroundColor: '#26203d',
};

const FormModalForm: React.FC<FormModalFormProps> = ({
	FormComponent,
	form,
	values,
	handleSubmit,
	submitting,
	pristine,
	isOpen,
	loading,
	onClose,
	...formProps
}) => {
	// boolean for communicating to form child components whether form needs to be reset
	const [formReset, setFormReset] = useState(false);
	// clear form data and set formReset back to false
	// after form child components have had a chance to reset
	// after receiving a positive formReset prop
	useEffect(() => {
		if (formReset) {
			form.reset();
			setFormReset(false);
		}
	});

	const onCancel = (click) => {
		click.preventDefault();
		setFormReset(true);
		onClose();
	};

	const modalProps = {
		visible: isOpen,
		okText: 'save',
		onOk: () => form.submit(),
		cancelText: 'reset',
		onCancel,
		confirmLoading: loading,
		destroyOnClose: true,
		width: 900,
		okButtonProps: {
			icon: 'save',
		},
	};

	return (
		<Modal {...modalProps}>
			<form onSubmit={handleSubmit}>
				<div style={formStyle}>
					<FormComponent
						form={form}
						values={values}
						submitting={submitting}
						pristine={pristine}
						formReset={formReset}
						{...formProps}
					/>
				</div>
				<pre style={dataStyle}>{JSON.stringify(values, null, 2)}</pre>
			</form>
		</Modal>
	);
};

export default FormModalForm;
