<?php
//*************************  Civirules Actions  *********************
/**
 * Action to Autocomplete a 'Pay Later' payment on a new Contribution
 * Used when an Administrator creates a Membership via the Webform
 *   to avoid additional manual action
 */
 class CRM_Civirules_LalgAutocompletePayment extends CRM_Civirules_Action {
	 
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
	* Completes the Contribution with payment equal to the value of the Contribution
	* Clears the Autocomplete flag.
	*
	* @param CRM_Civirules_TriggerData_TriggerData $triggerData	* @access public
	*
	*/
	//******************************** TODO *************************
	// Check error handling
	// Function to declare which triggers it works with
	public function processAction(CRM_Civirules_TriggerData_TriggerData $triggerData) {
//		dpm('Autocomplete action triggerred');
		try {
			// Check this is called on behalf of a Contribution
			$contribution = $triggerData -> getEntityData('contribution');
//					dpm($contribution);
			sleep(5);
			if ($contribution) {
				// Complete the Contribution
//							dpm('Completing the Contribution');
				$result = civicrm_api3('Contribution', 'completetransaction', [
					'id' => $contribution['contribution_id'],
					'total_amount' => $contribution['total_amount'],
					'financial_type' => 2,					// Membership Dues
					'payment_instrument' => [6],			// Not Tracked
				]);
//							dpm($result);
			}
		}
		catch (Exception $e) {
			dpm('LalgAutocompletePayment  Caught exception: ');
			dpm($e);
		}
	}
}
	