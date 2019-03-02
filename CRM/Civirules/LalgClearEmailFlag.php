<?php
//*************************  Civirules Actions  *********************/
/**
 * Action to clear the 'Send Email Membership Card' flag on a Household entity
 * Used to tidy up after sending Email cards to online members
 * *********************************  NOTE  ********************************
 * Assumes that CiviRule Conditions are set to ensure:
 *   The Contact is an Individual, not a Household
 *   That the Email Card Required is set on the associated Household
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
	// Get Contact Id	
	$contactId = $triggerData->getContactId();
	//dpm('Action called for Id: ' . $contactId);
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
	
	// Set 'Mail Sent' on this Contact
	//dpm('Setting Mail Sent Flag for Id: ' . $contactId);
	$params = array('entity_id' => $contactId, 'custom_User_Fields:Email_Card_Sent' => 1,);
	$result = civicrm_api3('CustomValue', 'create', $params);
	// dpm($result);
	
	// Getting all members of this Household
	//dpm('Getting all members of the Household');
	$result = civicrm_api3('Relationship', 'get', ['sequential' => 1, 'contact_id_b' => $hhId,]);
	$relationships = $result['values'];
	// dpm($result);
	
	// Check if all Email Sent flags are set.
	foreach ($relationships as $relationship) {
		//dpm('Getting Mail Sent flag for Id: ' . $relationship['contact_id_a']);
		$params = array('sequential' => 1, 'entity_id' => $relationship['contact_id_a'], 'return.User_Fields:Email_Card_Sent' => 1,);
		$result = civicrm_api3('CustomValue', 'get', $params);
		//dpm($result);
		if ($result['values'][0]['0'] == 0) return;
	}
	
	// All Emails sent, so clear all flags
	//dpm('Clearing flags');
	foreach ($relationships as $relationship) {
		$params = array('sequential' => 1, 'entity_id' => $relationship['contact_id_a'], 'custom_User_Fields:Email_Card_Sent' => 0,);
		civicrm_api3('CustomValue', 'create', $params);
	}
	
	// Now the flag on the Household
	//dpm('Now the flag on the HH Membership');
	$params = array('sequential' => 1, 'entity_id' => $hhId, 'custom_Household_Fields:Email_Card_Required' => 0,);
	$result = civicrm_api3('CustomValue', 'create', $params);
	//dpm($result);
}
	
catch (Exception $e) {
	dpm('LalgClearEmailFlag  Caught exception: '.  $e->getMessage());
}

}
}
