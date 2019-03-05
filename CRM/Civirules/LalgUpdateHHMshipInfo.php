<?php

/**
 * CiviRules Action to set selected Membership Fields in Household Custom Fields
 * for use in Webforms.
 *
 *********************************  NOTE  ********************************
 * Assumes that CiviRule Conditions are set to ensure:
 *   The Contact is a Household
 */
class CRM_Civirules_LalgUpdateHHMshipInfo extends CRM_Civirules_Action {

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
	 * @param CRM_Civirules_TriggerData_TriggerData $triggerData
	 * @access public
	 *
	 */
	public function processAction(CRM_Civirules_TriggerData_TriggerData $triggerData) {
	  try {	
		// Get Household Id
		$contactId = $triggerData->getContactId();
	    //dpm('Action called for Id: ' . $contactId);
		
		// Get the related Membership 
		//dpm('Getting the related Household Membership');
		$result = civicrm_api3('Membership', 'get', ['sequential' => 1, 'contact_id' => $contactId,]);
		$membership = $result['values'][0];
		//dpm($membership);
		
		// Format Membership Expiry Date
		$endDate = DateTime::createFromFormat('Y-m-d', $membership['end_date']);
		$endDate = $endDate->format('F jS, Y');
		//dpm($endDate);
		// Set End Date field
		$result = civicrm_api3('CustomValue', 'create', [
		  'entity_id' => $contactId, 
		  'custom_Household_Fields:Membership_End_Date' => $endDate,
		]);
		
		// Get Membership Type
		$membershipType = $membership['membership_name'];
		// Get Status
		$result = civicrm_api3('MembershipStatus', 'get', [
		  'sequential' => 1,
		  'id' => $membership['status_id'],
		]);
		//dpm($result);
		$status = $result['values'][0];
		if(!$status['is_current_member']) {
			$membershipType = $status['label'];
		}
		
		// Set Membership Type custom field
		$result = civicrm_api3('CustomValue', 'create', [
		  'entity_id' => $contactId, 
		  'custom_Household_Fields:Membership_Type' => $membershipType,
		]);
		
	  } 
	  catch (Exception $e) {
		dpm('LalgUpdateHHMshipInfo  Caught exception: '.  $e->getMessage());
	  }
	}	  
}
