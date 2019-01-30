<?php

//*************************  Civirules Actions  *********************/

/**
 * Action to clear the 'Send Printed Membership Card' flag on a Household entity
 * Used to tidy up after sending Printed cards to offline members
 *
 *********************************  NOTE  ********************************
 * Assumes that CiviRule Conditions are set to ensure:
 *   The Contact is an Individual, not a Household
 *   That the Email Card Required is set on the associated Household
 */
class CRM_Civirules_LalgClearPrintFlag extends CRM_Civirules_Action {

	/**
	 * Method to return the url for additional form processing for action
	 * and return false if none is needed
	 *
	 * @param int $ruleActionId
	 * @return bool
	 * @access public
	 */
	public function getExtraDataInputUrl($ruleActionId) {
	  return FALSE;
	}

	/**
	 * Method processAction to execute the action
	 * 
	 * Finds the Individual associated with teh Activity, then the related Household
	 * Clears the flag
	 *
	 * @param CRM_Civirules_TriggerData_TriggerData $triggerData
	 * @access public
	 *
	 */
	 
	 /******************************** TODO *************************/
	 // Check error handling
	 
	public function processAction(CRM_Civirules_TriggerData_TriggerData $triggerData) {
	  try {	
		// Get Contact Id
		$contactId = $triggerData->getContactId();
	    dpm('Action called for Id: ' . $contactId);
		
		// Get the related Household Id
		//dpm('Getting the related Household');
		$result = civicrm_api3('Membership', 'get', ['sequential' => 1, 'contact_id' => $contactId,]);
		$membership = $result['values'][0];
		$params = array('sequential' => 1, 'id' => $membership['owner_membership_id'],);
		$result = civicrm_api3('Membership', 'get', $params);
		$hhMembership = $result['values'][0];
//		dpm($hhMembership);
		$hhId = $hhMembership['contact_id'];
//		dpm('HH Id: ' . $hhId);
		
		// Now the flag on the Household
//		dpm('Clear the flag on the Household ');
		$params = array('sequential' => 1, 'entity_id' => $hhId, 'custom_Household_Fields:Printed_Card_Required' => 0,);
		$result = civicrm_api3('CustomValue', 'create', $params);

//		dpm($result);
	  } 
	  catch (Exception $e) {
		dpm('LalgClearPrintFlag  Caught exception: '.  $e->getMessage());
	  }
	}
	
	  
}