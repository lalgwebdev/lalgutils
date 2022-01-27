<?php
//*************************  Civirules Conditions  *********************
/**
 * Condition to check if anyone in Household has Email address.
 * Used when scheduling a Postal Reminder, since if someone does, then they will get the reminder that way.
 */
 class CRM_Civirules_LalgIfNoEmailInHousehold extends CRM_Civirules_Condition {
	 
	/**
	* Method to return the url for additional form processing for action
	* and return false if none is needed
	*	* @param int $ruleActionId
	* @return bool
	* @access public
	*/
	public function getExtraDataInputUrl($ruleActionId) {
	  return FALSE;
	}

	/**
	/**
	*	Method isConditionValid() to test the Condition
	*
	* @param CRM_Civirules_TriggerData_TriggerData $triggerData
	* @return bool
	* @access public
	*/
	public function isConditionValid(CRM_Civirules_TriggerData_TriggerData $triggerData) {
		try {	
			// Get the Contact that called this action
			$cid = $triggerData -> getContactId();
//dpm('Condition If No Emails In Household called for: ' . $cid);
			
			// Get Relationship to Household
			$relationships = civicrm_api4('Relationship', 'get', [
			  'select' => [
				'contact_id_b',
			  ],
			  'where' => [
				['contact_id_a', '=', $cid],
			  ],
			]);
					
			// Get Household Relationship(s) to Contacts
			$relationships = civicrm_api4('Relationship', 'get', [
			  'select' => [
				'contact_id_a',
			  ],
			  'where' => [
				['contact_id_b', '=',$relationships[0]["contact_id_b"]],
			  ],
			]);				
			
			// For each Contact get number of emails - exit if any exist.
			foreach($relationships as $reln) {
				$cid2 = $reln["contact_id_a"];
				$emails = civicrm_api4('Email', 'get', [
				  'select' => [
					'row_count',
				  ],
				  'where' => [
					['contact_id', '=', $cid2],
				  ],
				]);					
				if ($emails -> count() > 0) { return FALSE; }	// Someone has an email, so condition fails.
			}			
			return TRUE;										// No one does, so condition satisfied.
		}
		catch (Exception $e) {
			dpm('LalgIfNoEmailsInHousehold  Caught exception: ');
			dpm($e);
		}
	}
}
	