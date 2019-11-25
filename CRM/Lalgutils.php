<?php

class CRM_Lalgutils {

  /**
   * This function clears the print flag
   * @param  mixed $cids Contact IDS - Array or comma seperated integers
   * Note - these are the ids of people, not the households.
   */
  public function clear_print_flag($cids) {
	// Get the id of the Send_Membership_Documents custom Field.  
    static $tagid;
    if (!$tagid) {
      $tagid = civicrm_api3('Tag', 'getvalue', [
        'return' => "id",
        'name' => "Print Card",
      ]);
      $tagid = CRM_Utils_Type::escape($tagid, 'Int');
    }

    if (!is_array($cids)) {
      $cids = explode(",", $cids);
    }
	
    foreach ($cids as $cid) {
		// Set printfield off
		$result = civicrm_api3('EntityTag', 'delete', [
		  'tag_id' => $tagid,
		  'entity_id' => $cid,
		]);
    }
  }

}
