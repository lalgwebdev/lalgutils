<?php
//*************************  Civirules Actions  *********************/
/**
 * Test Action which just displays the $triggerData parameter
 */
 
 class CRM_Civirules_LalgDisplayTriggerData extends CRM_Civirules_Action {

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
 */

  public function processAction(CRM_Civirules_TriggerData_TriggerData $triggerData) {
	dpm($triggerData);
  }
}
