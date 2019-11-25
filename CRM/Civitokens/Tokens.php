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
			// Set return value
				$values[$cid]['paylater.membership_fee'] = $contrib['total_amount'];
			}
//dpm($result);			
			
		}   
	}	
}