<?php

require_once 'lalgutils.civix.php';
use CRM_Lalgutils_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function lalgutils_civicrm_config(&$config) {
  _lalgutils_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function lalgutils_civicrm_xmlMenu(&$files) {
  _lalgutils_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function lalgutils_civicrm_install() {
  _lalgutils_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function lalgutils_civicrm_postInstall() {
  _lalgutils_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function lalgutils_civicrm_uninstall() {
  _lalgutils_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function lalgutils_civicrm_enable() {
  _lalgutils_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function lalgutils_civicrm_disable() {
  _lalgutils_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function lalgutils_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _lalgutils_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function lalgutils_civicrm_managed(&$entities) {
  _lalgutils_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function lalgutils_civicrm_caseTypes(&$caseTypes) {
  _lalgutils_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function lalgutils_civicrm_angularModules(&$angularModules) {
  _lalgutils_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function lalgutils_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _lalgutils_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function lalgutils_civicrm_entityTypes(&$entityTypes) {
  _lalgutils_civix_civicrm_entityTypes($entityTypes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function lalgutils_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function lalgutils_civicrm_navigationMenu(&$menu) {
  _lalgutils_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _lalgutils_civix_navigationMenu($menu);
} // */

/************************************************************/
/*     LALG Functions added manually                        */
/************************************************************/

/************************************************************/
// General hooks relating to several functions 
// 	Print Membership Cards
//	Print Labels
//	Delete members
/************************************************************/
/**
 * Implements hook_civicrm_searchTasks().
 * Adds tasks for printing membership cards etc.
 */
function lalgutils_civicrm_searchTasks($objectName, &$tasks) {
  if ($objectName == 'contact') {
    $tasks[] = [
      'title' => 'LALG - Print Membership Cards',
      'class' => 'CRM_Contact_Form_Task_LalgPrintCards'
    ];
    $tasks[] = [
      'title' => 'LALG - Print Labels',
      'class' => 'CRM_Contact_Form_Task_LalgPrintLabels'
    ];
    $tasks[] = [
      'title' => 'LALG - Delete Members',
      'class' => 'CRM_Contact_Form_Task_LalgDeleteMembers'
    ];
  }
}
/**
 * Implements hook_civicrm_buildForm().
 * Adds js to our form
 */
function lalgutils_civicrm_buildForm($formName, &$form) {
	  if ($formName == "CRM_Contact_Form_Task_LalgPrintCards") {
		Civi::resources()->addScriptFile(E::LONG_NAME, 'js/printcards.js');
	  }
	  if ($formName == "CRM_Contact_Form_Task_LalgPrintLabels") {
		Civi::resources()->addScriptFile(E::LONG_NAME, 'js/printlabels.js');
	  }  
	  if (strpos($_SERVER['REQUEST_URI'], "lalgwf=2" ) !== false) {
		Civi::resources()->addScriptFile(E::LONG_NAME, 'js/searchlabels.js');
	  }	  
	  if (strpos($_SERVER['REQUEST_URI'], "civicrm/dataprocessor_activity_search/membership_postal_reminders" ) !== false) {
		Civi::resources()->addScriptFile(E::LONG_NAME, 'js/searchreminders.js');
	  }	
}

/************************************************************/
// Batch Printing Membership Cards
/************************************************************/
/**
 * Implements hook_civicrm_postProcess().
 * Clears the printing flag if the upload button
 * (labelled "Download and clear flags") was used
 */
function lalgutils_civicrm_postProcess($formName, &$form) {
  if ($formName == "CRM_Contact_Form_Task_LalgPrintCards") {
    $buttonName = $form->controller->getButtonName();
    if ($buttonName == '_qf_LalgPrintCards_upload') {
      CRM_Lalgutils::clear_print_flag($form->_contactIds);
    }
  }
}

/************************************************************/
// LALG Custom Tokens
/************************************************************/
/**
 * Implements hook_civicrm_tokens and hook_civicrm_tokenValues.
 */
function lalgutils_civicrm_tokens(&$tokens) {
//dpm('hook_civicrm_tokens called');
	CRM_Civitokens_Tokens::civicrm_tokens($tokens);
}
function lalgutils_civicrm_tokenValues(&$values, $cids, $job = null, $tokens = array(), $context = null) {
//dpm('hook_civicrm_tokenValues called');
	CRM_Civitokens_Tokens::civicrm_tokenValues($values, $cids, $job, $tokens, $context);
}

/************************************************************/
// LALG Three Month Extension 
/************************************************************/
/**
 * Pre-hook checks Membership details and adds 3 months to end date if appropriate.
 */
function lalgutils_civicrm_pre($op, $objectName, $id, &$params) {
//	if ($objectName == 'Membership') {
// dpm($op . ':  ' . $objectName);
// dpm('Id:  ' . $id);
// dpm($params);
//	}
	
	// Only proceed if this is a Membership Edit
	if ($objectName != 'Membership' || $op != 'edit') { return; }
	
	$contact = civicrm_api3('Contact', 'getsingle', [
		'id' => $params['contact_id'],
	]);
		
	// Only proceed if this is the Household Contact
	if ($contact["contact_type"] != "Household") { return; }
	
	// Check Membership Type and Status
	$mType = $params['membership_type_id'];
	if ($mType != '7' && $mType != '8') { return; }			// Must be Membership OR With Newsletter
	$mStatus = $params['status_id'];
	if ($mStatus != '1' && $mStatus != '2') { return; }		// Must be New OR Current
	
	// Get First Related Individual Contact
	$result = civicrm_api3('Relationship', 'get', [
	  'sequential' => 1,
	  'contact_id_b' => $params['contact_id'],
	  'relationship_type_id' => 8,
	]);
	$cid = $result['values'][0]['contact_id_a'];

	// Get Tags for the Contact
	$result = civicrm_api3('EntityTag', 'get', [
		'sequential' => 1,
		'entity_table' => "civicrm_contact",
		'entity_id' => $cid,
	]);
	$found = false;
	foreach ($result['values'] as $tag){ 
		if ($tag['tag_id'] == '13') { $found = true; }		// Look for 'Membership Requested'		
	}
	if (!$found) { return; }
	
	// Get the Latest Membership Action
	$result = civicrm_api3('CustomValue', 'getsingle', [
		'sequential' => 1,
		'entity_id' => $cid,
		'return.custom_30' => 1
	]);
	if ($result['latest'] != '2') { return; }				// Value 2 = 'Renew'.  Exit otherwise.
	
	// Check still within the agreed period.
	if (date("Y-m-d") > "2021-04-30") { return;	}
		
	// Change End Date
	$newDate = strtotime($params['end_date']);
	$newDate = strtotime("+3 Months", $newDate);;
	$params['end_date'] = date("Ymd", $newDate);

}	
 
/************************************************************/
// TESTING ONLY
/************************************************************/
// function lalgutils_civicrm_pre($op, $objectName, $id, &$params) {
		// dpm('Pre ' . $op . '  :  ' . $objectName);
	// if ($objectName == 'Individual' || $objectName == 'Contribution') {
		// dpm($params);
		  // $trace = debug_backtrace();
		  // $backtrace_lite = array();
		  // foreach( $trace as $call ) {
			// $backtrace_lite[] = $call['function']."    ".$call['file']."    line ".$call['line'];
		  // }
		  // debug( $backtrace_lite, "TRACE Pre: " . $op . ' ' . $objectName, true );		  
	// }
// }

// function lalgutils_civicrm_post($op, $objectName, $objectId, &$objectRef) {
		// dpm('Post ' . $op . '  :  ' . $objectName);
		  // $trace = debug_backtrace();
		  // $backtrace_lite = array();
		  // foreach( $trace as $call ) {
			// $backtrace_lite[] = $call['function']."    ".$call['file']."    line ".$call['line'];
		  // }
		  // debug( $backtrace_lite, "TRACE Post: " . $op . ' ' . $objectName, true );
// }





