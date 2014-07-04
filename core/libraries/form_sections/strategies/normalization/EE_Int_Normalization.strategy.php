<?php

if (!defined('EVENT_ESPRESSO_VERSION'))
	exit('No direct script access allowed');

/**
 * Event Espresso
 *
 * Event Registration and Management Plugin for WordPress
 *
 * @ package			Event Espresso
 * @ author			Seth Shoultes
 * @ copyright		(c) 2008-2011 Event Espresso  All Rights Reserved.
 * @ license			http://eventespresso.com/support/terms-conditions/   * see Plugin Licensing *
 * @ link					http://www.eventespresso.com
 * @ version		 	4.3
 *
 * ------------------------------------------------------------------------
 *
 * EE_Int_Normalization
 * Casts the string to an int. If the user inputs anything but numbers, we growl at them
 *
 * @package			Event Espresso
 * @subpackage
 * @author				Mike Nelson
 *
 * ------------------------------------------------------------------------
 */
class EE_Int_Normalization extends EE_Normalization_Strategy_Base{
	public function normalize($value_to_normalize) {
		$value_string_with_only_numbers = preg_replace( "/[^0-9]/", "",$value_to_normalize);
		if(strlen($value_to_normalize) > strlen($value_string_with_only_numbers)){
			throw new EE_Validation_Error(sprintf(__("Only numeric characters, please!", "event_espresso")), 'numeric_only');
		}else{
			return intval($value_string_with_only_numbers);
		}
	}

	/**
	 * Converst the int into a string for use in teh html form
	 * @param int $normalized_value
	 * @return string
	 */
	public function unnormalize( $normalized_value ) {
		if( empty( $normalized_value ) ){
			return '0';
		}else{
			return "$normalized_value";
		}
	}
}

// End of file EE_Int_Normalization.strategy.php