<?php

//*************************  Civirules Actions  *********************/

/**
 * Conditions applicable to the 'Send Email Membership' Activity 
 * Checks the Send by Email flag is set.
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
		$result = civicrm_api3('Contact', 'get', [
			'sequential' => 1, 
			'id' => $contactId,
		]);
		$contact = $result['values'][0];
		if ($contact['contact_type'] != 'Individual') return FALSE;
		
		// Check that the Send Card by Email flag is set.
		//dpm('Checking that the Send Card by Email flag is set');
		$result = civicrm_api3('CustomValue', 'get', [
			'sequential' => 1, 
			'entity_id' => $contactId, 
			'return.User_Fields:Send_Membership_Documents' => 1,
		]);
		//dpm($result);
		if ($result['count'] == 0) return FALSE;
		if ($result['values'][0]['0'] != 1) return FALSE;

		//dpm('All OK');
		return TRUE;
	  } 
	  catch (Exception $e) {
		dpm('LalgEmailCardConditions  Caught exception: '.  $e->getMessage());
	  }
	}
}
