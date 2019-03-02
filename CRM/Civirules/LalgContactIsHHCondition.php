<?php

/**
 * CiviRules Condition to check that the call relates to a Household, not an Individual
 */
class CRM_Civirules_LalgContactIsHHCondition extends CRM_Civirules_Condition {

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
	 * @param CRM_Civirules_TriggerData_TriggerData $triggerData
	 * @return bool
	 * @access public
	 *
	 */	 
	public function isConditionValid(CRM_Civirules_TriggerData_TriggerData $triggerData) {
	  try {	
		// Check this is called on behalf of a Household, not Individual
		$contactId = $triggerData->getContactId();
	    //dpm('Condition called for Id: ' . $contactId);
		$result = civicrm_api3('Contact', 'get', ['sequential' => 1, 'id' => $contactId,]);
		$contact = $result['values'][0];
		return $contact['contact_type'] == 'Household';
	  } 
	  catch (Exception $e) {
		dpm('LalgEmailCardConditions  Caught exception: '.  $e->getMessage());
	  }
	}
	
	  
}