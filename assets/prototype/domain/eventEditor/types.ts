import { RelationalData } from '../../application/services/apollo/relations/types';
import { JsDataProps } from '../../application/services/config/types';
import { DatetimeEdge, TicketEdge, PriceEdge, PriceTypeEdge } from './data/types';

export interface EditorDOMData {
	eventId: number;
}

export interface GQLDOMData {
	datetimes?: DatetimeEdge;
	tickets?: TicketEdge;
	prices?: PriceEdge;
	priceTypes?: PriceTypeEdge;
	relations?: RelationalData;
}

export interface EEJSData {
	data: JsDataProps;
}

declare global {
	interface Window {
		eeEditorEventData: EditorDOMData;
		eeEditorGQLData: GQLDOMData;
		eejsdata: EEJSData;
		graphqlEndpoint: string;
	}
}
