<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) exit('No direct script access allowed');
/**
 * Event Espresso
 *
 * Event Registration and Management Plugin for WordPress
 *
 * @ package	Event Espresso
 * @ author		Seth Shoultes
 * @ copyright	(c) 2008-2011 Event Espresso  All Rights Reserved.
 * @ license	{@link http://eventespresso.com/support/terms-conditions/}   * see Plugin Licensing *
 * @ link		{@link http://www.eventespresso.com}
 * @ since		4.0
 *
 * ------------------------------------------------------------------------
 *
 * Transactions Table class
 *
 * @package				Event Espresso
 * @subpackage			includes/admin_screens/Transactions_List_Table.class.php
 * @author					Brent Christensen 
 *
 * ------------------------------------------------------------------------
 */


class EE_Admin_Transactions_List_Table extends EE_Admin_List_Table {    

	private $_status;


	/**
	 * 		constructor
	*/ 
   function __construct( $admin_page ){

   		parent::__construct($admin_page);
   		$this->_status = $this->_admin_page->get_transaction_status_array();
   
	}





	protected function _setup_data() {
		$this->_per_page = $this->get_items_per_page( $this->_screen . '_per_page' );
		$this->_data = $this->_admin_page->get_transactions( $this->_per_page );
		$this->_all_data_count = $this->_admin_page->get_transactions( $this->_per_page, TRUE );
	}








	protected function _set_properties() {
		$this->_wp_list_args = array(
			'singular' => __('transaction', 'event_espresso'),
			'plural' => __('transactions', 'event_espresso'),
			'ajax' => TRUE,
			'screen' => $this->_admin_page->get_current_screen()->id
			);

		$this->_columns = array(
            'TXN_ID' => __( 'ID', 'event_espresso' ),
			'TXN_timestamp'	=> __( 'Transaction Date', 'event_espresso' ),
            'STS_ID' => __( 'Status', 'event_espresso' ),
            'TXN_total' => __( 'Total', 'event_espresso' ),
            'TXN_paid' => __( 'Paid', 'event_espresso' ),
			'ATT_fname' => __( 'Primary Registrant', 'event_espresso' ),
			'ATT_email' => __( 'Email Address', 'event_espresso' ),
			'event_name' => __( 'Event', 'event_espresso' ),
            'actions' => __( 'Actions', 'event_espresso' )
        );

        $this->_sortable_columns = array(
          	'TXN_ID' => array( 'TXN_ID' => FALSE ),     
            'STS_ID' => array( 'STS_ID'=> FALSE ),
            'event_name' => array( 'event_name'=> FALSE ),
            'ATT_fname'	=> array( 'ATT_fname'=> FALSE ),
           	'TXN_timestamp'	=> array( 'TXN_timestamp'=> TRUE ) //true means its already sorted
        	);

        $this->_hidden_columns = array();
	}






	protected function _get_table_filters() {
		$filters = array();
		$start_date = isset( $this->_req_data['txn-filter-start-date'] ) ? wp_strip_all_tags( $this->_req_data['txn-filter-start-date'] ) : date( 'D M j, Y', strtotime( '-10 year' ));
		$end_date = isset( $this->_req_data['txn-filter-end-date'] ) ? wp_strip_all_tags( $this->_req_data['txn-filter-end-date'] ) : date( 'D M j, Y' );
		ob_start();
		?>
		<label for="txn-filter-start-date">Display Transactions from </label>
		<input id="txn-filter-start-date" class="datepicker" type="text" value="<?php echo $start_date; ?>" name="txn-filter-start-date" size="15"/>	
		<label for="txn-filter-end-date"> until </label>
		<input id="txn-filter-end-date" class="datepicker" type="text" value="<?php echo $end_date; ?>" name="txn-filter-end-date" size="15"/>	
		<?php
		$filters[] = ob_get_contents();
		ob_end_clean();
		return $filters;
	}





	protected function _add_view_counts() {
		$this->_views['all']['count'] = $this->_all_data_count;
	}





	/**
	 * 		column_default
	*/ 
   function column_default($item, $column_name){
        switch($column_name){
            case 'TXN_ID':
            	$view_lnk_url = EE_Admin_Page::add_query_args_and_nonce( array( 'action'=>'view_transaction', 'TXN_ID'=>$item->ID() ), TXN_ADMIN_URL );
				return '<a href="' . $view_lnk_url . '" title="Go to Transaction Details">' . $item->ID() . '</a>';
             default:
				return ( isset( $item->$column_name )) ? $item->$column_name : '';
        }
    }







	/**
	 * 		column_cb
	*/ 
    function column_cb($item){
        return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'],  $item->ID());
    }





	/**
	 * 		column_TXN_timestamp
	*/ 
    function column_TXN_timestamp($item){
		$view_lnk_url = EE_Admin_Page::add_query_args_and_nonce( array( 'action'=>'view_transaction', 'TXN_ID'=>$item->ID() ), TXN_ADMIN_URL );
		$txn_date = '<a href="'.$view_lnk_url.'" title="' . __( 'View Transaction Details for TXN #', 'event_espresso' ) . $item->ID() . '">' . $item->get_datetime('TXN_timestamp', 'D M j, Y', 'g:i:s a') .  '</a>'; 
		return $txn_date;
	}




	/**
	 * 		column_STS_ID
	*/ 
    function column_STS_ID($item){
		return '<span class="status-'. $item->get('STS_ID') .'">' . __( $this->_status[ $item->get('STS_ID') ], 'event_espresso' ) . '</span>';
	}





	/**
	 * 		column_TXN_total
	*/ 
    function column_TXN_total($item){
		if ( $item->get('TXN_total') > 0 ) {
			return '<span class="txn-pad-rght">' . $item->get_pretty('TXN_total') . '</span>';	
		} else {
			return '<span class="txn-overview-free-event-spn">' . __( 'free', 'event_espresso' ) . '</span>';
		}
		
	}





	/**
	 * 		column_TXN_paid
	*/ 
    function column_TXN_paid($item){
    	$transaction_total = $item->get('TXN_total');
    	$transaction_paid = $item->get('TXN_paid');
		
		if (( $transaction_total > 0 ) && ( $transaction_paid >= $transaction_total )) {
			// paid in full
			$span_class = 'txn-overview-full-payment-spn';
			
		} elseif (( $transaction_total > 0 ) && ( $transaction_paid > 0 )) {
			// monies owing
			$span_class = 'txn-overview-part-payment-spn';
			
		} elseif (( $transaction_total > 0 ) && ( $transaction_paid == 0 )) {
			// no payments made
			$span_class = 'txn-overview-no-payment-spn';
			
		} else {
			$span_class = 'txn-overview-free-event-spn';
			$transaction_paid = 0;
		}
		
		return '<span class="' . $span_class . ' txn-pad-rght">' . $transaction_paid !== 0 ? $item->get_pretty('TXN_paid') : $transaction_paid . '</span>';
		
	}




	/**
	 * 		column_ATT_fname
	*/ 
    function column_ATT_fname($item){
    	$primary_reg = $item->primary_registration();
    	$attendee = $primary_reg->get_first_related('Attendee');
    	if ( !empty( $attendee ) ) {
			$edit_lnk_url = EE_Admin_Page::add_query_args_and_nonce( array( 'action'=>'view_registration', '_REG_ID'=>$primary_reg->ID() ), REG_ADMIN_URL );
			return '<a href="'.$edit_lnk_url.'" title="' . __( 'View Registration Details', 'event_espresso' ) . '">' . $attendee->full_name() . '</a>';
		}
		return __('Could be something wrong with the primary registration associated with this transaction in the db', 'event_espresso');
	}




	/**
	 * 		column_ATT_email
	*/ 
    function column_ATT_email($item){
    	$attendee = $item->primary_registration()->get_first_related('Attendee');
    	if ( !empty( $attendee ) )
			return '<a href="mailto:' . $attendee->get('ATT_email') . '">' . $attendee->get('ATT_email') . '</a>'; 
		else
			return __('Could be somethign wrong with the primary registrationa ssociated with this transaction in the db', 'event_espresso');
	}





	/**
	 * 		column_event_name
	*/ 
    function column_event_name($item){
    	$actions = array();
		$event = $item->primary_registration()->get_first_related('Event');
		if ( !empty( $event ) ) {
			$edit_event_url = EE_Admin_Page::add_query_args_and_nonce( array( 'action'=>'edit', 'post'=>$event->ID() ), EVENTS_ADMIN_URL );
			$event_name = $event->get('EVT_name');

			//filter this view by transactions for this event
			$txn_by_event_lnk = EE_Admin_Page::add_query_args_and_nonce( array( 'action' => 'default', 'EVT_ID' => $event->ID() ) );
			$actions['filter_by_event'] = '<a href="' . $txn_by_event_lnk . '" title="' . __('Filter transactions by this event', 'event_espresso') . '">' . __('View Transactions for this event', 'event_espresso') . '</a>';

			return sprintf('%1$s %2$s', '<a href="' . $edit_event_url . '" title="' . sprintf( __( 'Edit Event: %s', 'event_espresso' ), $event->get('EVT_name') ) .'">' .  wp_trim_words( $event_name, 30, '...' ) . '</a>', $this->row_actions($actions) ); 
		} else {
			return __('The event associated with this transaction via the primary registration cannot be retrieved.', 'event_espresso');
		}
	}






	/**
	 * 		column_actions
	*/ 
    function column_actions($item){

    	$registration = $item->primary_registration();

        //Build row actions	
		$view_lnk_url = EE_Admin_Page::add_query_args_and_nonce( array( 'action'=>'view_transaction', 'TXN_ID'=>$item->ID() ), TXN_ADMIN_URL );
		$dl_invoice_lnk_url = $registration->invoice_url();
		$view_reg_lnk_url = EE_Admin_Page::add_query_args_and_nonce( array( 'action'=>'view_registration', '_REG_ID'=>$registration->ID() ), REG_ADMIN_URL );
		$send_pay_lnk_url = EE_Admin_Page::add_query_args_and_nonce( array( 'action'=>'send_payment_reminder', 'TXN_ID'=>$item->ID() ), TXN_ADMIN_URL );
		
        //Build row actions
        $view_lnk = '
		<li>
			<a href="'.$view_lnk_url.'" title="' . __( 'View Transaction Details', 'event_espresso' ) . '">
				<img width="16" height="16" alt="' . __( 'View Transaction Details', 'event_espresso' ) . '" src="'. EE_GLOBAL_ASSETS_URL .'/images/magnifier.png">
			</a>
		</li>';
		
         $dl_invoice_lnk = '
		<li>
			<a title="Download Invoice" target="_blank" href="'.$dl_invoice_lnk_url.'">
				<img width="16" height="16" alt="Download Invoice" src="'. EE_GLOBAL_ASSETS_URL .'/images/invoice-1-16x16.png">
			</a>
		</li>';
      
	       $send_pay_lnk = '
		<li>
			<a href="'.$send_pay_lnk_url.'" title="' . __( 'Send Payment Reminder', 'event_espresso' ) . '">
				<img width="20" height="20" alt="' . __( 'Send Payment Reminder', 'event_espresso' ) . '" src="'. EE_GLOBAL_ASSETS_URL .'/images/payment-reminder-20x20.png">
			</a>
		</li>';
		$send_pay_lnk = $item->get('STS_ID') != EEM_Transaction::complete_status_code && $item->get('STS_ID') != EEM_Transaction::overpaid_status_code ? $send_pay_lnk : '';
		
	        $view_reg_lnk = '
		<li>
			<a href="'.$view_reg_lnk_url.'" title="' . __( 'View Registration Details', 'event_espresso' ) . '">
				<img width="13" height="13" alt="' . __( 'View Registration Details', 'event_espresso' ) . '" src="'. EE_GLOBAL_ASSETS_URL .'/images/edit.png">
			</a>
		</li>';

			$actions = '
		<ul class="txn-overview-actions-ul">' . 
		$view_lnk . $dl_invoice_lnk . $send_pay_lnk . $view_reg_lnk . '
		</ul>';
			
			return $actions;
    }

    
}