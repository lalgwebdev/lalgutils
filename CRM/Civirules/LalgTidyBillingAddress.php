<?php
//*************************  Civirules Actions  *********************
/**
 * Action to tidy up (postal) Billing Address Fields when Contact is created.
 * There is a webform bug which creates a Billing Address as the Primary Location (if payment by card, cash or Cheque)
 *     in addition to the Home address which is shared from the Household.
 * So, if Billing Address is primary then make Home primary instead.
 * Also if Billing Address is empty (cash or cheque) then delete it.
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
			dpm('Tidy Billing Address called for: ' . $cid);
			
			// Get the attached Addresses
			if ($cid) {
				$result = civicrm_api3('Address', 'get', [
					'sequential' => 1,
					'contact_id' => $cid,
				]);
				dpm($result);
				if ($result['count'] == 0) {return;}
			} 
			else {return;}
				
			// Find Home and Billing address
//			dpm('Checking Addresses');
			$billing = NULL;
			$home = NULL;
			foreach ($result['values'] as $address) {
				$locn = $address['location_type_id'];
				if ($locn == 1) {$home = $address;}
				if ($locn == 5) {$billing = $address;}
			}
			
			// If Billing is Primary Location
			if ($billing && $home) {
				if (!$home['is_primary']) {
					dpm('Home is not Primary, so change it');				
					$result = civicrm_api3('Address', 'create', [
					  'id' => $home['id'],
					  'is_primary' => 1,
					]);
				}
			}
			if ($billing && !isset($billing['street_address']) && !isset($billing['postal_code'])) {
//					dpm('Billing empty, so delete it');						
				$result = civicrm_api3('Address', 'delete', [
				  'id' => $billing['id'],
				]);
//				dpm($result);
			}
						
		}
		catch (Exception $e) {
			dpm('LalgTidyBillingAddress  Caught exception: ');
			dpm($e);
		}
	}
}
	