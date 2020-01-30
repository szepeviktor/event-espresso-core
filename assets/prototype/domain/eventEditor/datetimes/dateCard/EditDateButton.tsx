import React, { useCallback } from 'react';
import { Button } from 'antd';
import { EditItemButtonProps } from '../../types';
import { useEditorModal } from '../../../../application/ui/components/layout/editorModal';
type fn = () => void;
const EditDateButton: React.FC<EditItemButtonProps> = ({ id, ...rest }) => {
	const { openEditor } = useEditorModal();
	const onClick = useCallback<fn>(
		() =>
			openEditor({
				editorId: 'editDatetime',
				entityId: id,
			}),
		[id]
	);
	return <Button icon={'edit'} type={'default'} onClick={onClick} {...rest} />;
};
export default EditDateButton;
