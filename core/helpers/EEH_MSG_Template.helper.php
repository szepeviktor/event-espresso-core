<?php

if (!defined('EVENT_ESPRESSO_VERSION') )
	exit('NO direct script access allowed');

/**
 * Event Espresso
 *
 * Event Registration and Management Plugin for WordPress
 *
 * @ package			Event Espresso
 * @ author			Seth Shoultes
 * @ copyright		(c) 2008-2011 Event Espresso  All Rights Reserved.
 * @ license			http://eventespresso.com/support/terms-conditions/   * see Plugin Licensing *
 * @ link				http://www.eventespresso.com
 * @ version		 	4.0
 *
 * ------------------------------------------------------------------------
 *
 * EEH_MSG_Template
 * Utility class containing a variety of helpers related to message templates.
 *
 * @package		Event Espresso
 * @subpackage	includes/core
 * @author		Darren Ethier
 *
 * ------------------------------------------------------------------------
 */
class EEH_MSG_Template {


	private static function _set_autoloader() {
		EE_Registry::instance()->load_lib( 'Messages_Init' );
		EE_Messages_Init::set_autoloaders();
	}



	/**
	 * generate_new_templates
	 * This will handle the messenger, message_type selection when "adding a new custom template" for an event and will automatically create the defaults for the event.  The user would then be redirected to edit the default context for the event.
	 *
	 * @access protected
	 * @param  string  $messenger the messenger we are generating templates for
	 * @param array $message_types array of message types that the templates are generated for.
	 * @param int $GRP_ID If a non global template is being generated then it is expected we'll have a GRP_ID to use as the base for the new generated template.
	 * @param bool $global true indicates generating templates on messenger activation. false requires evt_id for event specific template generation.
	 * @return array|error_object array of data required for the redirect to the correct edit page or error object if encountering problems.
	 */
	public static function generate_new_templates($messenger, $message_types, $GRP_ID = 0,  $global = FALSE) {

		//make sure message_type is an array.
		$message_types = (array) $message_types;
		$templates = array();
		$success = TRUE;

		if ( empty($messenger) ) {
			throw new EE_Error( __('We need a messenger to generate templates!', 'event_espresso') );
		}

		//if we STILL have empty $message_types then we need to generate an error message b/c we NEED message types to do the template files.
		if ( empty($message_types) ) {
			throw new EE_Error( __('We need at least one message type to generate templates!', 'event_espresso') );
		}

		self::_set_autoloader();


		$MSG = new EE_messages();

		foreach ( $message_types as $message_type ) {
			//if this is global template generation. First let's determine if we already HAVE global templates for this messenger and message_type combination.  If we do then NO generation!!
			if ( $global && self::already_generated($messenger, $message_type  ) ) {
				$templates = TRUE;
				continue; //get out we've already got generated templates for this.
			}

			$new_message_template_group = $MSG->create_new_templates($messenger, $message_type, $GRP_ID, $global);

			if ( !$new_message_template_group ) {
				$success = FALSE;
				continue;
			}
			if ( $templates === TRUE ) $templates = array();
			$templates[] = $new_message_template_group;
		}

		return ($success) ? $templates : $success;
	}


	/**
	 * The purpose of this method is to determine if there are already generated templates in the database for the given variables.
	 * @param  string $messenger     messenger
	 * @param  string $message_type message type
	 * @param  int $GRP_ID        GRP ID ( if a custom template) (if not provided then we're just doing global template check)
	 * @return bool                true = generated, false = hasn't been generated.
	 */
	public static function already_generated( $messenger, $message_type, $GRP_ID = 0 ) {
		self::_set_autoloader();
		$MTP = EEM_Message_Template_Group::instance();


		//what method we use depends on whether we have an evt_id or not
		$count = !empty( $GRP_ID ) ? $MTP->get_one_by_ID( $GRP_ID ) : $MTP->get_global_message_template_by_m_and_mt( $messenger, $message_type, 'GRP_ID', 'ASC', NULL, TRUE, 'all' );

		self::_update_to_active( $messenger, $message_type );

		return ( $count > 0 ) ? TRUE : FALSE;
	}




	/**
	 * Updates all message templates matching the incoming messenger and message type to active status.
	 *
	 * @param  string $messenger    	Messenger slug
	 * @param  string $message_type  Message type slug
	 * @return  int 						count of updated records.
	 */
	public function update_to_active( $messenger, $message_type ) {
		return EEM_Message_Template_Group::instance()->update( array('MTP_is_active' => 1), array(array('MTP_messenger' => $messenger, 'MTP_message_type' => $message_type )) );
	}




	/**
	 * The purpose of this function is to return all installed message objects (messengers and message type regardless of whether they are ACTIVE or not)
	 * @return array array consisting of installed messenger objects and installed message type objects.
	 */
	public static function get_installed_message_objects($type = 'all') {
		self::_set_autoloader();
		//get all installed messengers and message_types
		$EE_MSG = new EE_messages();
		$installed_message_objects = $EE_MSG->get_installed($type);
		return $installed_message_objects;
	}

}
