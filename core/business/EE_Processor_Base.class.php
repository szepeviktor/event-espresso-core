<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) { exit('No direct script access allowed'); }
/**
 * Class EE_Processor_Base
 *
 * Description
 *
 * @package 			Event Espresso
 * @subpackage 	core
 * @author 				Brent Christensen
 * @since 				4.6
 *
 */

class EE_Processor_Base {

	/**
	 * Used to set whether SPCO is being revisited by registrant or not.
	 *
	 * @var bool
	 */
	protected $_revisit = FALSE;



	/**
	 * Allows external class (usually checkout) to set whether SPCO is being revisited by registrant or not.
	 *
	 * @param bool $revisit
	 * @return void
	 */
	public function set_revisit( $revisit = FALSE ) {
		$this->_revisit = filter_var( $revisit, FILTER_VALIDATE_BOOLEAN );
	}



	/**
	 * debug
	 *
	 * @param string $class
	 * @param string $func
	 * @param string $line
	 * @param \EE_Transaction $transaction
	 * @param array $info
	 * @param bool $display_request
	 */
	protected function log( $class = '', $func = '', $line = '', EE_Transaction $transaction, $info = array(), $display_request = false ) {
		EE_Registry::instance()->load_helper( 'Debug_Tools' );
		if ( WP_DEBUG ) {
			if ( $transaction instanceof EE_Transaction ) {
				// don't serialize objects
				$info = EEH_Debug_Tools::strip_objects( $info );
				if ( $transaction->ID() ) {
					$info[ 'TXN_status' ] = $transaction->status_ID();
					$info[ 'TXN_reg_steps' ] = $transaction->reg_steps();
					$index = 'EE_Transaction: ' . $transaction->ID();
					EEH_Debug_Tools::log( $class, $func, $line, $info, $display_request, $index );
				}
			}
		}

	}



}
// End of file EE_Processor_Base.class.php
// Location: /EE_Processor_Base.class.php