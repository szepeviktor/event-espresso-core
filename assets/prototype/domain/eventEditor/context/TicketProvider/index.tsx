/**
 * External dependencies
 */
import React, { createContext } from 'react';

/**
 * Internal dependencies
 */
import { EntityId } from '../../data/types';
import {
	ContextProps,
	EditorIds,
} from '../../../../application/ui/components/layout/editor-modal/useEditorModalState/types';
import useEditorModalState from '../../../../application/ui/components/layout/editor-modal/useEditorModalState';
import useTicketEditorId from './useTicketEditorId';

export const TicketContext = createContext({} as ContextProps);

interface TicketProviderProps {
	children: React.ReactChildren;
	id: EntityId;
}

const TicketProvider = ({ children, id }: TicketProviderProps) => {
	const { closeAllEditors, currentlyOpenEditor, getIsOpen, onClose, setIsOpen } = useEditorModalState(id);
	const editorIds: EditorIds = {
		calculator: useTicketEditorId('price-calculator', id),
		editForm: useTicketEditorId('ticket-editor', id),
		relations: useTicketEditorId('ticket-relations', id),
	};

	const editorState = {
		closeAllEditors,
		currentlyOpenEditor,
		getIsOpen,
		onClose,
		setIsOpen,
	};

	const value = {
		editorIds,
		editorState,
		id,
	};

	return <TicketContext.Provider value={value}>{children}</TicketContext.Provider>;
};

export default TicketProvider;
