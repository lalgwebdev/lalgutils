<?php
//*************************  Civirules Actions  *********************
/**
 * Actions to tidy up Email Fields whenever Contact is changed.
 * If Billing Address exists, and Home is empty or missing,
 * Copy Billing to Home, and make Home Primary.
 * Needed because de-duplication when Drupal Account registered puts email in Billing.
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
	*	Method processAction to execute the action
	*
	* Triggered by a Contact being added or changed.
	*
	* @param CRM_Civirules_TriggerData_TriggerData $triggerData	* @access public
	*/
	//******************************** TODO *************************
	// Function to declare which triggers it works with
	
	public function processAction(CRM_Civirules_TriggerData_TriggerData $triggerData) {
		try {	
			// Get the Contact that called this action
			$cid = $triggerData -> getEntityData('contact')['id'];			
			//dpm('Tidy Email called for: ' . $cid);
			
			// Get the attached Email Addresses
			if ($cid) {
				$result = civicrm_api3('Email', 'get', [
					'sequential' => 1,
					'contact_id' => $cid,
				]);
//				dpm($result);
			} 
			else {return;}
				
			// Find Home and Billing address
			//dpm('Copying Billing to Home');
			foreach ($result['values'] as $email) {
				if ($email['location_type_id'] == 1) {
					$home = $email;
				}
				if ($email['location_type_id'] == 5) {
					$billing = $email;
				}
			}
			// If Billing and Not Home
			if ($billing && !$home['email']) {
				//dpm('Do the copy');
				$result = civicrm_api3('Email', 'create', [
				  'contact_id' => $cid,
				  'email' => $billing['email'],
				  'location_type_id' => "Home",
				  'is_primary' => 1,
				]);
				//dpm($result);
			}			
		}
		catch (Exception $e) {
			dpm('LalgTidyBillingEmail  Caught exception: ');
			dpm($e);
		}
	}
}
	