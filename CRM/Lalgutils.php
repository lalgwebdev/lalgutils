<?php

class CRM_Lalgutils {

  /**
   * This function clears the print flag
   * @param  mixed $cids Contact IDS - Array or comma seperated integers
   * Note - these are the ids of people, not the households.
   */
  public function clear_print_flag($cids) {
	// Get the id of the Send_Membership_Documents custom Field.  
    static $printfield;
    if (!$printfield) {
      $printfield = "custom_" . civicrm_api3('CustomField', 'getvalue', [
        'return' => "id",
        'name' => "Printed_Card_Required",
      ]);
    }

    if (!is_array($cids)) {
      $cids = explode(",", $cids);
    }

    // Set printfield off
    foreach ($cids as $cid) {
      $result = civicrm_api3('Contact', 'create', [
        'sequential' => 1,
        'id' => $cid,
        $printfield => 0,				// 'None'
      ]);
    }
  }

}
