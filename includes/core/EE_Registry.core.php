<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) { exit('No direct script access allowed'); }
/**
 * 
 * Event Espresso
 *
 * Event Registration and Ticketing Management Plugin for WordPress
 *
 * @ package			Event Espresso
 * @ author			Seth Shoultes
 * @ copyright		(c) 2008-2011 Event Espresso  All Rights Reserved.
 * @ license			http://eventespresso.com/support/terms-conditions/   * see Plugin Licensing *
 * @ link					http://www.eventespresso.com
 * @ version		 	$VID:$
 *
 * ------------------------------------------------------------------------
 *
 * EE_Registry Class
 *
 * Centralized Application Data Storage and Management
 *
 * @package				Event Espresso
 * @subpackage		core
 * @author					Brent Christensen
 *
 * ------------------------------------------------------------------------
 */
class EE_Registry {

   /**
     * 	EE_Registry Object
     * 	@private _instance
	 * 	@private 	protected
     */
	private static $_instance = NULL;

   /**
     * array for storing library classes in
     * @private _libraries
     */
	private $LIB = array();

   /**
     * 	EE_Session Object
	 * 	@access 	public
	 *	@var 	object	
     */
	public $EE_Session = NULL;

   /**
     * 	EE_Config Object
	 * 	@access 	public
	 *	@var 	object	
     */
	public $CFG = NULL;

	/**
	 * 	EE_Request_Handler Object
	 * 	@access 	public
	 *	@var 	object	
	 */
	public $REQ = NULL;

   /**
     * 	EE_Data_Mapper Object
	 * 	@access 	public
	 *	@var 	object	
     */
	public $DMP = NULL;

	/**
	 * 	$shortcodes
	 * 	@access 	public
	 *	@var 	array	$shortcodes
	 */
	public $shortcodes = array();

	/**
	 * 	$modules
	 * 	@access 	public
	 *	@var 	array	$modules
	 */
	public $modules = array();

   /**
     * 	$i18n_js_strings - internationalization for JS strings
     *  usage:   EE_Registry::i18n_js_strings['string_key'] = __( 'string to translate.', 'event_espresso' );
     *  in js file:  var translatedString = eei18n.string_key;
     * 	
	 * 	@access 	public
	 *	@var 	array	
     */
	public static $i18n_js_strings = array();

   /**
     * 	$main_file - path to espresso.php
     * 	
	 * 	@access 	public
	 *	@var 	array	
     */
	public $main_file;

	
	

	/**
	 *private constructor to prevent direct creation
	 *@Constructor
	 *@access private
	 *@return void
	 */	
	private function __construct() {
//		echo '<h3>'. __CLASS__ . '->' . __FUNCTION__ . ' <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h3>';
		// load EE_Config
		$this->_load_configuration();
		$this->REQ = new EE_Request_Handler();	
		/*EE_Data_Mapper $data_mapper*/
		// Get current page protocol
		$protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
		// Output admin-ajax.php URL with same protocol as current page
		self::$i18n_js_strings['ajax_url'] = admin_url( 'admin-ajax.php', $protocol );	
	}



	/**
	 *@ singleton method used to instantiate class object
	 *@ access public
	 *@ return class instance
	 */	
	public  function instance() {
		// check if class object is instantiated
		if ( self::$_instance === NULL  or ! is_object( self::$_instance ) or ! is_a( self::$_instance, __CLASS__ )) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}




	/**
	 *	_load_configuration
	 * 
	 *@access private
	 *@return void
	 */
	private function _load_configuration () {
		// load org options
		add_filter( 'FHEE_load_org_options', '__return_true' );
		// does the EE_Config file exist ?
		if ( is_readable( EE_CORE . 'EE_Config.core.php' )) {
			require_once( EE_CORE . 'EE_Config.core.php' );
			$this->CFG = EE_Config::instance();
		} else {
			$msg = __( 'An error has occured. The EE_Config.core.php file could not be loaded.', 'event_espresso' );
			EE_Error::add_error( $msg, __FILE__, __FUNCTION__, __LINE__ );
		}
	}



	/**
	 *	getter for data mapper object
	 * 
	 *	@return instantiated class object
	 */
//	public function mapper () {
//		return $this->mapper;
//	}



	/**
	 *	loads core classes - must be singletons
	 * 
	 *	@param string $class_name - simple class name ie: session
	 *	@return instantiated class object
	 */	
	public function load_core ( $class_name, $pass_REG = FALSE ) {
		// retreive instantiated class
		return $this->_load( EE_CORE, 'EE_' , $class_name, 'core', $pass_REG );
	}





	/**
	 *	loads object creating classes - must be singletons
	 * 
	 *	@param string $class_name - simple class name ie: attendee
	 *	@return instantiated class object
	 */
	public function load_class ( $class_name, $pass_REG = FALSE ) {
		// retreive instantiated class
		return $this->_load( EE_CLASSES, 'EE_' , $class_name, 'class', $pass_REG );
	}



	/**
	 * 	loads model classes - must be singletons
	 * 
	 *	@param string $class_name - simple class name ie: price
	 *	@return instantiated class object
	 */	
	public function load_model ( $class_name, $pass_REG = FALSE ) {
		// retreive instantiated class
		return $this->_load( EE_MODELS, 'EEM_' , $class_name, 'model', $pass_REG );
	}





	/**
	 * 	loads helper classes - must be singletons
	 * 
	 *	@param string $class_name - simple class name ie: price
	 *	@return instantiated class object
	 */	
	public function load_helper ( $class_name, $pass_REG = FALSE ) {
		// retreive instantiated class
		return $this->_load( EE_HELPERS, 'EE_', $class_name, 'helper', $pass_REG );
	}




	/**
	 *	generic class loader
	 * 
	 *	@param string $path_to_file - directory path to file location, not including filename
	 *	@param string $class_name - full class name  ie:  My_Class
	 *	@param string $type - file type - core? class? helper? model?
	 *	@return instantiated class object
	 */	
	public function load_file ( $path_to_file, $class_name, $type = 'class', $pass_REG = FALSE ) {
		// set path to class file
		$path_to_file = rtrim( $path_to_file, '/' ) . '/';
		$type = trim( $type, '.' );
		// retreive instantiated class
		return $this->_load( $path_to_file, '', $class_name, $type  );
	}





	/**
	 *	loads and tracks classes
	 * 
	 *	@param string $file_path - file path including file name
	 *	@param string $class_prefix - EE  or EEM or... ???
	 *	@param string $class_name - $class name
	 *	@param string $type - file type - core? class? helper? model?
	 *	@param boolean $pass_REG - whether to pass $this (EE_Registry) to the instantiated class
	 *	@return instantiated class object
	 */	
	private function _load ( $file_path = FALSE, $class_prefix = 'EE_', $class_name = FALSE, $type = 'class', $pass_REG = FALSE ) {
		// make sure $class name is lowercase
		$class_name = strtoupper( trim( $class_prefix )) . ucwords( strtolower( trim( $class_name )));
		$type = '.' . trim( $type, '.' );
		$file_path = $file_path ? $file_path : EE_CLASSES;
		$file_path = rtrim( $file_path, '/' ) . '/' . $class_name . $type . '.php';
		// check if class has already been loaded, and return it if it has been	
		if ( isset ( $this->{$class_name} )) {
			return $this->{$class_name};
		} else if ( isset ( $this->_libraries[ $class_name ] )) {
			return $this->_libraries[ $class_name ];
		}
		// don't give up! you gotta...
		try {
			
			//does the file exist and can be read ?
			if ( ! is_readable( $file_path )) {
				// so sorry, can't find the file'
				throw new EE_Error ( 
					sprintf (
						__('An error occured. The %s file %s could not be located or is not readable due to file permissions. Please ensure that the following filepath is correct: %s','event_espresso'), 
						$type, 
						$class_name, 
						$file_path 
					)
				);
			}
			// if the class isn't already declared somewhere, then get the file
			if ( class_exists( $class_name, FALSE ) === FALSE ) {
				require( $file_path );
			} else {
				// so sorry, not a class
				throw new EE_Error( 
					sprintf(
						__('An error occured. The %s file %s does not appear to contain the %s Class.','event_espresso'), 
						$type, 
						$class_name, 
						$class_name 
					)
				);
			}

		} catch ( EE_Error $e ) {
			$e->get_error();
		}

			
		// don't give up! you gotta...
		try {
			
			// create reflection
			$reflector = new ReflectionClass( $class_name );
			// are we passing $this (EE_Registry) to class ?)
			if( $pass_REG ) {
				// instantiate the class and add to the _libraries array for tracking
				$class_obj = $reflector->isInstantiable() ? $reflector->newInstance( $this ) : call_user_func( array( $class_name, 'instance' ), $this );
			} else {
				// instantiate the class and add to the _libraries array for tracking
				$class_obj = $reflector->isInstantiable() ? $reflector->newInstance() : call_user_func( array( $class_name, 'instance' ));
			}

		} catch ( Exception $e ) {
			$e->getMessage();
		}
			
		// return newly instantiated class
		if ( property_exists( $this, $class_name )) {
			$this->{$class_name} = $class_obj;
		} else {
			$this->_libraries[ $class_name ] = $class_obj;
		}

		return $class_obj;
		
	}




	/**
	 *		@ override magic methods
	 *		@ return void
	 */
	final function __destruct() {}
	final function __call($a,$b) {}
	final function __get($a) {}
	final function __set($a,$b) {}
	final function __isset($a) {}
	final function __unset($a) {}
	final function __sleep() {}
	final function __wakeup() {}
	final function __toString() {}
	final function __invoke() {}
	final function __set_state() {}
	final function __clone() {}
	final static function __callStatic($a,$b) {}


 
}
// End of file EE_Registry.core.php
// Location: ./core/EE_Registry.core.php