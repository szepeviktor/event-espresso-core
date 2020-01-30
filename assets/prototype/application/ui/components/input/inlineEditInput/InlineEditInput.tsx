import React, { useState, useEffect } from 'react';

interface InlineEditInputProps {
	component: React.ReactType;
	defaultValue: string | number;
	value: string | number;
}

const InlineEditInput: React.FC<InlineEditInputProps> = ({ component: Component, defaultValue, value, ...rest }) => {
	const [currentValue, setcurrentValue] = useState(defaultValue);

	useEffect(() => {
		if (value !== currentValue) {
			setcurrentValue(value);
		}
	}, [value]);

	return <Component value={currentValue} onChange={(newValue: string) => setcurrentValue(newValue)} {...rest} />;
};

export default InlineEditInput;
