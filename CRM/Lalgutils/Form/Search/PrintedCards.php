<?php
use CRM_Lalgutils_ExtensionUtil as E;

/**
 * ************  LALG Custome Search   ****************
 * Finds Individual Contacts whose Household has the 'Printed Card Required flag set.
 */
class CRM_Lalgutils_Form_Search_PrintedCards extends CRM_Contact_Form_Search_Custom_Base implements CRM_Contact_Form_Search_Interface {

  /**
   * Class constructor.
   *
   * @param array $formValues
   */
 public function __construct(&$formValues) {
    parent::__construct($formValues);

     if (!isset($formValues['state_province_id'])) {
      $this->_stateID = CRM_Utils_Request::retrieve('stateID', 'Integer');
      if ($this->_stateID) {
        $formValues['state_province_id'] = $this->_stateID;
      }
    }

    $this->_columns = array(
      // ts('Contact ID') => 'contact_id',
      // ts('Contact Type') => 'contact_type',
      // ts('Name') => 'sort_name',
      // ts('State') => 'state_province',

      E::ts('Contact Id') => 'contact_id',
      E::ts('Name') => 'sort_name',
      E::ts('Street Address') => 'street_address',
	  E::ts('City') => 'city',
    );
}

  /**
   * Prepare a set of search fields
   *
   * @param CRM_Core_Form $form modifiable
   * @return void
   */
  function buildForm(&$form) {
    CRM_Utils_System::setTitle(E::ts('Printed Cards Required'));
  return;
  }

  /**
   * Get a list of summary data points
   *
   * @return mixed; NULL or array with keys:
   *  - summary: string
   *  - total: numeric
   */
  function summary() {
    return NULL;
    // return array(
    //   'summary' => 'This is a summary',
    //   'total' => 50.0,
    // );
  }

  /**
   * Get a list of displayable columns
   *
   * @return array, keys are printable column headers and values are SQL column names
   */
/*  function &columns() {
    // return by reference
    $columns = array(
 //     E::ts('Contact Id') => 'contact_id',
      E::ts('Name') => 'sort_name',
      E::ts('Street Address') => 'street_address',
	  E::ts('City') => 'city',
    );
    return $columns;
  }
*/

  /**
   * @param int $offset
   * @param int $rowcount
   * @param null $sort
   * @param bool $returnSQL
   *
   * @return string
   */
  public function contactIDs($offset = 0, $rowcount = 0, $sort = NULL, $returnSQL = FALSE) {
    return $this->all($offset, $rowcount, $sort, FALSE, TRUE);
  }
 

  /**
   * Construct a full SQL query which returns one page worth of results
   *
   * @param int $offset
   * @param int $rowcount
   * @param null $sort
   * @param bool $includeContactIDs
   * @param bool $justIDs
   * @return string, sql
   */
  function all($offset = 0, $rowcount = 0, $sort = NULL, $includeContactIDs = FALSE, $justIDs = FALSE) {
    // delegate to $this->sql(), $this->select(), $this->from(), $this->where(), etc.
    return $this->sql($this->select($justIDs), $offset, $rowcount, $sort, $includeContactIDs, NULL);
  }

  /**
   * Construct a SQL SELECT clause
   *
   * @return string, sql fragment with SELECT arguments
   */
  function select($justIDs) {
	if($justIDs) {
	  return "
      contact_a.id           as contact_id  
    ";
	} else {
    return "
      contact_a.id           as contact_id  ,
	  contact_a.display_name as display_name,
      contact_a.contact_type as contact_type,
      contact_a.sort_name    as sort_name,
      address.street_address as street_address,
	  address.city			 as city
    ";
	}
  }

  /**
   * Construct a SQL FROM clause
   *
   * @return string, sql fragment with FROM and JOIN clauses
   */
  function from() {
	// **************** The numbers in the flag fields are field Ids and will change.
	// **************** Really needs more Joins to select by name.
    return "
      FROM      civicrm_contact 			AS contact_a
      LEFT JOIN civicrm_address 			AS address 	ON address.contact_id = contact_a.id 
      LEFT JOIN civicrm_email           				ON civicrm_email.contact_id = contact_a.id  
      INNER JOIN civicrm_relationship 		AS reln 	ON reln.contact_id_a = contact_a.id
	  INNER JOIN civicrm_relationship_type 	AS relntype	ON relntype.id = reln.relationship_type_id
	  INNER JOIN civicrm_value_household_fie_8 AS flag  ON flag.entity_id = reln.contact_id_b	
    ";
  }

  /**
   * Construct a SQL WHERE clause
   *
   * @param bool $includeContactIDs
   * @return string, sql fragment with conditional expressions
   */
  function where($includeContactIDs = FALSE) {
	$where = " address.is_primary = 1 AND
				civicrm_email.is_primary = 1 AND
				relntype.name_a_b = 'Household Member of' AND
				flag.printed_card_required_18 = 1";
	
	return $where;
  }

  /**
   * Determine the Smarty template for the search screen
   *
   * @return string, template path (findable through Smarty template path)
   */
  function templateFile() {
    return 'CRM/Contact/Form/Search/Custom.tpl';
  }

  /**
   * Modify the content of each row
   *
   * @param array $row modifiable SQL result row
   * @return void
   */
  // function alterRow(&$row) {
    // $row['sort_name'] .= ' ( altered )';
  // }
}
