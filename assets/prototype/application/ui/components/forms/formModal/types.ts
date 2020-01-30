import React from 'react';
import { FormApi } from 'final-form';
import { FormProps, FormRenderProps } from 'react-final-form';

import { MutationResult } from '../../../../../application/services/apollo/mutations/types';

export interface FormModalProps extends FormProps {
	FormComponent: React.FC;
	onSubmit: (e?: any) => Promise<MutationResult>;
	onClose: (e?: any) => void;
	isOpen: boolean;
}

export interface FormComponentProps {
	id?: string;
	title?: string;
	form?: FormApi;
	values?: any;
	submitting?: boolean;
	pristine?: boolean;
	formReset?: boolean;
}

export interface FormModalFormProps extends FormRenderProps {
	FormComponent: React.FC<FormComponentProps>;
	onClose: (e?: any) => void;
	isOpen: boolean;
	loading: boolean;
}
