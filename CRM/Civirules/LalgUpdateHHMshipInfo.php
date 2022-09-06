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
	 * Method processAction to execute the action.  
	 * Updates the status fields for the whole Household - 
	 *    the Household Contact and its Individual members.
	 *
	 * @param CRM_Civirules_TriggerData_TriggerData $triggerData
	 * @access public
	 *
	 */
	public function processAction(CRM_Civirules_TriggerData_TriggerData $triggerData) {
	  try {	
		// Get Household Id
		$hhId = $triggerData->getContactId();
		// Get the list of Individual members
		$relationships = civicrm_api4('Relationship', 'get', [
		  'select' => [
			'contact_id_a',
		  ],
		  'where' => [
			['contact_id_b', '=', $hhId],
		  ],
		]);
		
		// Get the related Membership 
		$result = civicrm_api3('Membership', 'get', [
			'sequential' => 1, 
			'contact_id' => $hhId,
			'options' => ['sort' => "end_date DESC"],	// Ensure use most recent if there are two for any reason
		]);
		$membership = $result['values'][0];
		
		// Format Membership Expiry Date
		$endDate = DateTime::createFromFormat('Y-m-d', $membership['end_date']);
		$endDate = $endDate->format('F jS, Y');
		//dpm($endDate);
		// Set End Date field
		$result = civicrm_api3('CustomValue', 'create', [
		  'entity_id' => $hhId, 
		  'custom_Household_Fields:Membership_End_Date' => $endDate,
		]);
		foreach ($relationships as $rel) {
			$result = civicrm_api3('CustomValue', 'create', [
			  'entity_id' => $rel['contact_id_a'], 
			  'custom_User_Fields:Membership_End_Date' => $endDate,
			]);						
		}
		
		// Get Membership Type
		$membershipType = $membership['membership_name'];
		// Get Status
		$result = civicrm_api3('MembershipStatus', 'get', [
		  'sequential' => 1,
		  'id' => $membership['status_id'],
		]);

		$status = $result['values'][0];
		if(!$status['is_current_member']) {
			$membershipType = $status['label'];
		}
		
		// Set Membership Type custom field
		$result = civicrm_api3('CustomValue', 'create', [
		  'entity_id' => $hhId, 
		  'custom_Household_Fields:Membership_Type' => $membershipType,
		]);
		foreach ($relationships as $rel) {
			$result = civicrm_api3('CustomValue', 'create', [
			  'entity_id' => $rel['contact_id_a'], 
			  'custom_User_Fields:Membership_Type' => $membershipType,
			]);						
		}
		// Set Membership Status custom field
		$result = civicrm_api3('CustomValue', 'create', [
		  'entity_id' => $hhId, 
		  'custom_Household_Fields:Membership_Status' => $status['label'],
		]);		
		foreach ($relationships as $rel) {
			$result = civicrm_api3('CustomValue', 'create', [
			  'entity_id' => $rel['contact_id_a'], 
			  'custom_User_Fields:Membership_Status' => $status['label'],
			]);						
		}
		
	  } 
	  catch (Exception $e) {
		dpm('LalgUpdateHHMshipInfo  Caught exception: '.  $e->getMessage());
	  }
	}	  
}
