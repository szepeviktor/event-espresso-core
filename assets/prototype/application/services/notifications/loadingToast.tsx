import React from 'react';
import { ToastNotification } from 'carbon-components-react';

import { LoadingToastProps } from '../toaster/types';

const LoadingToast = ({ message }: LoadingToastProps): JSX.Element => {
	return <ToastNotification title={message} kind={'info'} />;
};

export default LoadingToast;
