<?php
//*************************  Civirules Actions  *********************/
/**
 * Action to clear the 'Send Email Membership Card' flag 
 * Used to tidy up after sending Email cards to online members
 * *********************************  NOTE  ********************************
 * Assumes that CiviRule Conditions are set to ensure:
 *   The Contact is an Individual, not a Household
 *   That the Email Card Required is set 
 */
 
 class CRM_Civirules_LalgClearEmailFlag extends CRM_Civirules_Action {

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
 * Sets 'Email Sent' flag on current Contact	
 * Checks whether all Contacts who derive membership from this Membership object	
 * have yet sent the email.	 
 * If not do nothing - someone else yet to come.	
 * If yes then clear 'Send Email' flag on the Household and 	
 * clear all 'Email Sent' flags on the Contacts, ready for next time.	
 *	
 * @param CRM_Civirules_TriggerData_TriggerData $triggerData	
 * @access public	
 *	
 */

 /******************************** TODO *************************/	 
 // Check error handling	 
 // Not sure if the logic is safe against timing differences on different threads	 
 //    between different contacts, or different objects associated with a contact.
	
  public function processAction(CRM_Civirules_TriggerData_TriggerData $triggerData) {
	try {		
		// Get the Contact Id
		$contactId = $triggerData->getContactId();
	    //dpm('Clear Send by Email flag for Id: ' . $contactId);
		
		// Set the Send Card by Email flag to None.
		$result = civicrm_api3('CustomValue', 'create', [
			'sequential' => 1, 
			'entity_id' => $contactId, 
			'custom_User_Fields:Email_Membership_Receipt' => 0,
		]); 
		//dpm($result);
	}
	catch (Exception $e) {
		dpm('LalgClearEmailFlag  Caught exception: '.  $e->getMessage());
	}

  }
}
