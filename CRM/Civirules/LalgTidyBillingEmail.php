<?php
//*************************  Civirules Actions  *********************
/**
 * Action to remove Billing Email Fields when payment has been made.
 * Part of a workaround to allow admins to take a card payment over the phone without an email.
 */
 class CRM_Civirules_LalgTidyBillingEmail extends CRM_Civirules_Action {
	 
	/**
	* Method to return the url for additional form processing for action
	* and return false if none is needed
	*	* @param int $ruleActionId
	* @return bool
	* @access public
	*/
	public function getExtraDataInputUrl($ruleActionId) {	  return FALSE;	}
	
	/**
	/**
	*	Method processAction to execute the action
	*
	* Triggered by a Membership being added or changed.
	* Goes to the associated Contact, and deletes any attached Billing Email addresses
	*
	* @param CRM_Civirules_TriggerData_TriggerData $triggerData	* @access public
	*/
	//******************************** TODO *************************
	// Function to declare which triggers it works with
	
	public function processAction(CRM_Civirules_TriggerData_TriggerData $triggerData) {
		try {	
			// Get the Contact that called this action
			$cid = $triggerData -> getContactId();

//dpm('Tidy Billing email called for: ' . $cid);
			
			// Get the attached Email Addresses
			if ($cid) {
				$result = civicrm_api3('Email', 'get', [
					'sequential' => 1,
					'contact_id' => $cid,
				]);
//dpm($result);
				if ($result['count'] == 0) {return;}
			} 
			else {return;}
			
			// Delete Billing Email addresses
			// Will automatically leave 'Home' address as Primary if it exists
//dpm('Deleting emails');
			foreach ($result['values'] as $email) {
				if ($email['location_type_id'] == 5) {
					$result = civicrm_api3('Email', 'delete', [
						'id' => $email['id'],
					]);
//dpm($result);
				}
			}		
		}
		catch (Exception $e) {
			dpm('LalgTidyBillingAddress  Caught exception: ');
			dpm($e);
		}
	}
}
	