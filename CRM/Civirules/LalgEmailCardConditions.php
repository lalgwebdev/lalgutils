<?php

//*************************  Civirules Actions  *********************/

/**
 * Conditions applicable to the 'Send Email Membership Card' flag on a Membership entity
 * Used to tidy up after sending Email cards to online members
 */
class CRM_Civirules_LalgEmailCardConditions extends CRM_Civirules_Condition {

	/**
	 * Returns a redirect url to extra data input from the user after adding a condition
	 *
	 * Return false if you do not need extra data input
	 *
	 * @param int $ruleConditionId
	 * @return bool|string
	 * @access public
	 * @abstract
	 */
	public function getExtraDataInputUrl($ruleConditionId) {
	  return FALSE;
	}

	/**
	 * Method isConditionValid to check the condition
	 * 
	 * Check the call is related to an Individual, not a Household
	 * Check the Card Required Flag is set
	 *
	 * @param CRM_Civirules_TriggerData_TriggerData $triggerData
	 * @return bool
	 * @access public
	 *
	 */
	 
	 /******************************** TODO *************************/
	 // Check error handling
	 
	public function isConditionValid(CRM_Civirules_TriggerData_TriggerData $triggerData) {
	  try {	
		// Check this is called on behalf of an Individual not a Household
		$contactId = $triggerData->getContactId();
	    //dpm('Email Card Condition called for Id: ' . $contactId);
		$result = civicrm_api3('Contact', 'get', ['sequential' => 1, 'id' => $contactId,]);
		$contact = $result['values'][0];
		if ($contact['contact_type'] != 'Individual') return FALSE;
		
		// Get the related Household Membership & Household Id
		//dpm('Getting the related Household Membership');
		$result = civicrm_api3('Membership', 'get', ['sequential' => 1, 'contact_id' => $contactId,]);
		$membership = $result['values'][0];
		$params = array('sequential' => 1, 'id' => $membership['owner_membership_id'],);
		$result = civicrm_api3('Membership', 'get', $params);
		$hhMembership = $result['values'][0];
		//dpm($hhMembership);
		$hhId = $hhMembership['contact_id'];
		//dpm('HH Id: ' . $hhId);
		
		// Check that the Card Required flag is set.
		//dpm('Checking that the Card Required flag is set');
		$params = array('sequential' => 1, 'entity_id' => $hhId, 'return.Household_Fields:Email_Card_Required' => 1,);
		$result = civicrm_api3('CustomValue', 'get', $params);
		//dpm($result);
		if ($result['count'] == 0) return FALSE;
		if ($result['values'][0]['0'] == 0) return FALSE;
		
		//dpm('All OK');
		return TRUE;
	  } 
	  catch (Exception $e) {
		dpm('LalgEmailCardConditions  Caught exception: '.  $e->getMessage());
	  }
	}
}
