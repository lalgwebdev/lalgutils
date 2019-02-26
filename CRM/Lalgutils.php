<?php

class CRM_Lalgutils {
  public function clear_print_flag($cids) {
    static $printfield;
    if (!$printfield) {
      $printfield = "custom_" . civicrm_api3('CustomField', 'getvalue', [
        'return' => "id",
        'name' => "Printed_card_required",
      ]);
    }

    if (!is_array($cids)) {
      $cids = explode(",", $cids);
    }

    // Get primary membership ids
    $result = civicrm_api3('Membership', 'get', [
      'sequential' => 1,
      'contact_id' => ['IN' => $cids],
      'return' => ["owner_membership_id"],
    ]);
    $mids = [];
    foreach ($result['values'] as $membership) {
      $mids[] = $membership['owner_membership_id'];
    }

    // Get contact ids of primary memberships
    $result = civicrm_api3('Membership', 'get', [
      'squential' => 1,
      'id' => ['IN' => array_unique($mids)],
      'return' => ["contact_id"],
    ]);

    // Set printfield off
    foreach ($result['values'] as $membership) {
      $result = civicrm_api3('Contact', 'create', [
        'sequential' => 1,
        'id' => $membership['contact_id'],
        $printfield => 0,
      ]);
    }
  }

}
