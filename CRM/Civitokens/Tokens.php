<?php

/**
* This file contains Custom Tokens, by implementing two hooks
* to declare and define the necessary values.
*/
   
class CRM_Civitokens_Tokens {
	
/** 
* Implements the hook_civicrm_tokens
* Params - Array of tokens to be declared
*/
	public static function civicrm_tokens(&$tokens) {
		$tokens['reminder'] = array(
			'reminder.latest_subject' => 'Latest Scheduled Reminder Subject',
		);
		$tokens['template'] = array(
			'template.how_to_pay' => 'Template - How to Pay',
		);		
	}
	
/** 
* Implements the hook_civicrm_tokens
* Params - 
*	$values	- Values of tokens to be returned
*	$cids	- Contact Ids requiring Token values
*	$tokens	- Tokens for which values required
*/
	public static function civicrm_tokenValues(&$values, $cids, $job = null, $tokens = array(), $context = null) {
//dpm($cids);
//dpm($tokens);
		
		// Activity tokens if these are required
		if (!empty($tokens['reminder'])) {  
		// Cycle through the Contacts list
			foreach ($cids as $cid) {
				// Get latest Activities
				$result = civicrm_api4('Activity', 'get', [
				  'join' => [
					['ActivityContact AS activity_contact', 'LEFT', ['id', '=', 'activity_contact.activity_id']],
				  ],
				  'where' => [
					['activity_type_id:name', '=', 'Print Postal Reminder'], 
					['activity_contact.contact_id', '=', $cid], 
					['activity_contact.record_type_id', '=', 3], 	// Contact is Activity Target
					['status_id:name', '=', 'Scheduled'],			// Scheduled
				  ],
				  'orderBy' => [ 'created_date' => 'DESC' ],		// Most recent First
				  'limit' => 1,										// Get only the latest
				]);
//dpm($result[0]);
				if (empty($result[0])) {
					CRM_Core_Session::setStatus('Contact Reference ' . $cid . ' has no Scheduled Reminder. Skipped.', 'Warning');
					continue;						// Move to next Contact
				}
				// Set return value
				$values[$cid]['reminder.latest_subject'] = $result[0]['subject'];
			}
//dpm($values);
		}
		
		// Template tokens if these are required
		if (!empty($tokens['template'])) {  
			STATIC $how_to_pay;									// Persistent variable
			// Get the How to Pay template
			if (!$how_to_pay) {
				$result = civicrm_api4('MessageTemplate', 'get', [
				  'select' => [
					'msg_html',
				  ],
				  'where' => [
					['msg_title', '=', 'Sys - How to Pay'],
				  ],
				]);
//dpm($result[0]);
				$how_to_pay = $result[0]['msg_html'];
			}
			
			// Cycle through the Contacts list
			foreach ($cids as $cid) {
				$values[$cid]['template.how_to_pay'] = $how_to_pay;
			}	
		}
	}	
}