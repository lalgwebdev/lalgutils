<?php

/**
 * TEMPORARY CiviRules Action to extend Membership Renewals by 3 Months
 * for use in Webforms.
 *
 *********************************  NOTE  ********************************
 * Assumes that CiviRule Conditions are set to ensure:
 *   The Contact is an Individual
 *   Correct Membership Type for the Extension
 *   Is Renewal, not Re-Join
 *   Genuine Renewal etc. not spurious change of status
 *
 * IMPORTANT NOTE.
 *	 This Action must be run on a timed delay, since it fails if run within the context of the Webform.
 */
class CRM_Civirules_Lalg3MonthExtension extends CRM_Civirules_Action {

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
		// Get Individual Contact Id
		$cid = $triggerData->getContactId();
//dpm('Action called for Id: ' . $cid);
		
		// Check still within the agreed period.
		if (date("Y-m-d") > "2021-04-30") { return;	}

		// Get the related Membership 
		$result = civicrm_api3('Membership', 'get', [
		  'sequential' => 1, 
		  'contact_id' => $cid,
		]);
//dpm($result['values'][0]);
		
		// Get the Primary Membership
		$primaryId = $result['values'][0]['owner_membership_id'];
		$result = civicrm_api3('Membership', 'get', [
		  'sequential' => 1, 
		  'id' => $primaryId,
		]);
		$membership = $result['values'][0];
//dpm($membership);
				
		//Calculate 14 months from now.  Max non-extended period is 13 months.
		$date = strtotime('+14 months');
//dpm('Threshold: ' . date("Y-m-d", $date));
		
		// Bail out if End Date already extended
		if ($membership['end_date'] > date("Y-m-d", $date)) { return; }
		
		// Else change End Date
		$newDate = strtotime($membership['end_date']);
		$newDate = strtotime("+3 Months", $newDate);;
//dpm(date("Y-m-d", $newDate));		
		$result = civicrm_api3('Membership', 'create', [
		  'id' => $membership['id'],
		  'end_date' => date("Y-m-d", $newDate),
		]);
//dpm($result);	
	  } 
	  catch (Exception $e) {
		dpm('LalgUpdateHHMshipInfo  Caught exception: '.  $e->getMessage());
	  }
	}	  
}
