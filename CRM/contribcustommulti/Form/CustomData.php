<?php
/**
 * @file
 */

require_once 'CRM/Core/BAO/CustomGroup.php';

/**
 * Form controller class.
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
 
class CRM_contribcustommulti_Form_CustomData extends CRM_Core_Form {

  /**
   * Preprocess function.
   */
  public function preProcess() {

    $this->_type = $this->_cdType = CRM_Utils_Request::retrieve('type', 'String', CRM_Core_DAO::$_nullObject, TRUE);
    $this->_subType = CRM_Utils_Request::retrieve('subType', 'String');
    $this->_subName = CRM_Utils_Request::retrieve('subName', 'String');
    $this->_groupCount = CRM_Utils_Request::retrieve('cgcount', 'Positive');
    $this->_entityId = CRM_Utils_Request::retrieve('entityID', 'Positive');
    $this->_groupID = CRM_Utils_Request::retrieve('groupID', 'Positive');
    $this->_onlySubtype = CRM_Utils_Request::retrieve('onlySubtype', 'Boolean');
    $this->assign('cdType', FALSE);
    $this->assign('cgCount', $this->_groupCount);

    $contactTypes = CRM_Contact_BAO_ContactType::contactTypeInfo();
    if (array_key_exists($this->_type, $contactTypes)) {
      $this->assign('contactId', $this->_entityId);
    }
    if (!is_array($this->_subType) && strstr($this->_subType, CRM_Core_DAO::VALUE_SEPARATOR)) {
      $this->_subType = str_replace(CRM_Core_DAO::VALUE_SEPARATOR, ',', trim($this->_subType, CRM_Core_DAO::VALUE_SEPARATOR));
    }
    CRM_Custom_Form_CustomData::setGroupTree($this, $this->_subType, $this->_groupID, $this->_onlySubtype);

    $this->assign('suppressForm', TRUE);
    $this->controller->_generateQFKey = FALSE;
    
    $field_params = array(
      'custom_group_id' => $this->_groupID ,
    );
    $custom_field = civicrm_api3('CustomField', 'get', $field_params);
    if( isset($custom_field['values']) ) {
      foreach( $custom_field['values'] as $fieldkey=>$field_value){
        $required = CRM_Utils_Array::value('is_required', $field_value);
        if ($field_value['data_type'] == 'File') {
          if (!empty($field_value['element_value']['data'])) {
            $required = 0;
          }
        }
        $fieldId = $field_value['id'];
        $elementName = 'custom_'.$fieldId;
        $custom_field['values'][$fieldkey]['element_name'] = $elementName;
        CRM_Core_BAO_CustomField::addQuickFormElement($this, $elementName, $fieldId, $required);
        
      }
      $this->assign('_element', $custom_field['values']);
      $this->_element = $custom_field['values'];
    }
  }

  /**
   * Set defaults.
   *
   * @return array
   */
  public function setDefaultValues() {
    $defaults = array();
    CRM_Core_BAO_CustomGroup::setDefaults($this->_groupTree, $defaults, FALSE, FALSE, $this->get('action'));
    return $defaults;
  }

  /**
   * Build quick form.
   */
  public function buildQuickForm() {
    
  }

}
