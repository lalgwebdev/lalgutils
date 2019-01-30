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
/**
 * Implements hook_action_info()
 * Creates a Drupal Action to Complete a Pay Later Contribution (to be invoked by VBO).
 */
function lalgutils_action_info() {
	dpm('Action Hook called');
  return array(
    'lalgutils_complete_pay_later_contribution' => array(
      'type' => 'entity',
      'label' => t('Complete payment for a Pending Contribution'),
      'behavior' => array('changes_property'),
      'configurable' => FALSE,
      'vbo_configurable' => FALSE,
      'triggers' => array('any'),
    ),
  );
}

/**
 * Carries out the Complete Payment Action
 */
function lalgutils_complete_pay_later_contribution(&$entity, $context) {
	dpm('VBO Action');
	dpm($entity);
	dpm($context);
	
	if ($context['entity_type'] != 'civicrm_contribution' ) return;
	if ($entity -> contribution_status != 'Pending' ) return;

	dpm('VBO Completing the Contribution');
	$result = civicrm_api3('Contribution', 'completetransaction', [
		'id' => $entity -> contribution_id,
		'total_amount' => $entity -> total_amount,
		'financial_type' => 2,									// Membership Dues
//		'payment_instrument' => 6,								// Not Tracked
	]);
	dpm($result);
}

/************************************************************/
/**
 * Adds the hook to Print Membership Cards
 */
function lalgutils_civicrm_searchTasks( $objectName, &$tasks ){
  if($objectName == 'contact'){
    $tasks[] = [
      'title' => 'LALG - Print Membership Cards',
      'class' => 'CRM_Contact_Form_Task_LalgPrintCards'
    ];
  }
//	dpm($tasks);
}

