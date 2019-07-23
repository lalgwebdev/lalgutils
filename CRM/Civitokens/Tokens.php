<?php

/**
* This file contains Custom Tokens, by implementing two hooks
* to declare and define the necessary values.
*/
   
class CRM_Civitokens_Tokens {
	
/** 
* Implements the hook_civicrm_tokens
* Params - Array of tokens to be declared
*/
	public function civicrm_tokens(&$tokens) {
		$tokens['paylater'] = array(
			'paylater.membership_type' => 'Pay Later - Membership Type',
			'paylater.membership_fee' => 'Pay Later - Membership Fee',
		);
	}
	
/** 
* Implements the hook_civicrm_tokens
* Params - 
*	$values	- Values of tokens to be returned
*	$cids	- Contact Ids requiring Token values
*	$tokens	- Tokens for which values required
*/
	public function civicrm_tokenValues(&$values, $cids, $job = null, $tokens = array(), $context = null) {
//dpm($cids);
//dpm($tokens);
		// Exit if Pay Later tokens not wanted
		if (empty($tokens['paylater'])) {return;}
   
 //   civicrm_initialize();
		// Cycle through the Contacts list
		foreach ($cids as $cid) {
			// Get Pending Contributions
				
			$result = civicrm_api3('Contribution', 'get', [
			  'sequential' => 1,
			  'contact_id' => $cid,
			  'contribution_status_id' => 2, 	// Pending
			]);
			if (empty($result['values'])) {
				CRM_Core_Session::setStatus('Contact Reference ' . $cid . ' has no Pending Contribution. Skipped.',
											'Warning');
				continue;						// Move to next Contact
			}
			// Get last result if more than one
			foreach ($result['values'] as $contrib) {
				$contribId = $contrib['contribution_id'];
			}
//dpm($result);			
			
			
			// Get related Line Items
			$result = civicrm_api3('LineItem', 'get', [
				'sequential' => 1,
				'contribution_id' => $contribId,
				'entity_table' => "civicrm_membership",
			]);
			if (empty($result['values'])) {
				CRM_Core_Session::setStatus('Cannot find Pending Membership for Contact Reference ' . $cid . '. Skipped.',
											'Warning');
				continue;						// Move to next Contact
			}
			$membFee = $result['values'][0]['unit_price'];
			$membId = $result['values'][0]['entity_id'];
//dpm($result);
			
			// Get Membership record
			$result = civicrm_api3('Membership', 'get', [
				'sequential' => 1,
				'id' => 157,
			]);
//dpm($result);
			
			// Set return values
			$values[$cid]['paylater.membership_type'] = $result['values'][0]['membership_name']; 
			$values[$cid]['paylater.membership_fee'] = $membFee;
//dpm($values);  
		}   
	}	
}