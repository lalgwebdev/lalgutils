<?php
//*************************  Civirules Actions  *********************
/**
 * Action to tidy up (postal) Billing Address Fields after card payment.
 * Stripe payment leaves the billing address Null, rather than absent, confuses some test code.
 */
 class CRM_Civirules_LalgTidyBillingAddress extends CRM_Civirules_Action {
	 
	/**
	* Method to return the url for additional form processing for action
	* and return false if none is needed
	*	* @param int $ruleActionId
	* @return bool
	* @access public
	*/
	public function getExtraDataInputUrl($ruleActionId) {	  return FALSE;	}
	
	/**
	* Method processAction to execute the action
	* Triggered by a Membership being added or changed.
	*
	* @param CRM_Civirules_TriggerData_TriggerData $triggerData	* @access public
	*/
	//******************************** TODO *************************
	// Function to declare which triggers it works with
	
	public function processAction(CRM_Civirules_TriggerData_TriggerData $triggerData) {
		try {	
			// Get the Contact that called this action
			$cid = $triggerData -> getContactId();

//dpm('Tidy Billing Address called for: ' . $cid);
			
			// Get the attached Addresses
			if ($cid) {
				$result = civicrm_api3('Address', 'get', [
					'sequential' => 1,
					'contact_id' => $cid,
				]);
//dpm($result);
				if ($result['count'] == 0) {return;}
			} 
			else {return;}
			
			// Delete Billing addresses
			// Will automatically leave 'Home' address as Primary if it exists
//dpm('Deleting Addresses');
			foreach ($result['values'] as $address) {
				if ($address['location_type_id'] == 5) {
					$result = civicrm_api3('Address', 'delete', [
						'id' => $address['id'],
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
	