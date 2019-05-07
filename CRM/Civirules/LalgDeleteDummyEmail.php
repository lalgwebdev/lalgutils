<?php
//*************************  Civirules Actions  *********************
/**
 * Action to delete dummy email fields from a Contact, that were added
 * by the Webform to enable Cash/Cheque Payment Processors to function properly
 */
 class CRM_Civirules_LalgDeleteDummyEmail extends CRM_Civirules_Action {
	 
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
	* Triggered by a Membership being added or changed.
	* Goes to the associated Contact, and deletes any attached dummy Email addresses
	*
	* @param CRM_Civirules_TriggerData_TriggerData $triggerData	* @access public
	*
	*/
	//******************************** TODO *************************
	// Function to declare which triggers it works with
	
	public function processAction(CRM_Civirules_TriggerData_TriggerData $triggerData) {
//		dpm('Delete Email action triggered');
		try {	
			// Get the Membership that called this action
			$memb = $triggerData -> getEntityData('membership');			
//			dpm($memb);
			$cid = $memb['contact_id'];

			// Get the attached Email Addresses
			if ($cid) {
				$result = civicrm_api3('Email', 'get', [
					'sequential' => 1,
					'contact_id' => $cid,
				]);
//				dpm($result);
			}
			
			// Delete all dummy addresses
			// Will automatically leave 'Home' address as Primary if it exists
//			dpm('Deleting emails');
			foreach ($result['values'] as $email) {
				if ($email['email'] == 'ccpp-dummy@lalg.org.uk') {
					$result = civicrm_api3('Email', 'delete', [
						'id' => $email['id'],
					]);
//					dpm($result);
				}
			}
		}
		catch (Exception $e) {
			dpm('LalgDeleteDummyEmail  Caught exception: ');
			dpm($e);
		}
	}
}
	