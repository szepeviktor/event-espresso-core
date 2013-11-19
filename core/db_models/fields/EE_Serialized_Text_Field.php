<?php


/**
 * Serialized text field should basically: accept either an array or serialized text as input.
 * When initally set by client code (ie, not EEMerimental_Base or children), the value should remain an array.
 * However, when inserting into the DB, it should be serialized.
 * Upon retrieval from the DB, it should be unserialized back into an array.
 */
require_once( EE_MODELS . 'fields/EE_Text_Field_Base.php' );
class EE_Serialized_Text_Field extends EE_Text_Field_Base{
	/**
	 * Value SHOULD be an array, and we want to now convert it to a serialized string
	 * @param array $value_of_field_on_model_object
	 * @return string
	 */
	function prepare_for_use_in_db($value_of_field_on_model_object) {
		return maybe_serialize($value_of_field_on_model_object);
	}
	/**
	 * Value provided should definetely be a serialized string. We should unserialize into an array
	 * @param string $value_found_in_db_for_model_object
	 * @return array
	 */
	function prepare_for_set_from_db($value_found_in_db_for_model_object) {
		$data = maybe_unserialize($value_found_in_db_for_model_object);
		//it's possible that this still has serialized data if its the session.  WP has a bug, http://core.trac.wordpress.org/ticket/26118 that doesnt' unserialize this automatically.
		$token = 'C';
		$data = is_string($data) ? trim($data) : $data;
		if ( is_string($data) && strlen($data) > 1 && $data[0] == $token  && preg_match( "/^{$token}:[0-9]+:/s", $data ) ) {
			return unserialize($data);
		} else {
			return $data;
		}
	}
}
