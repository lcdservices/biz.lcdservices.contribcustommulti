<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 5                                                  |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2018                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but   |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
 */

/**
 * This class generates form components for processing a contribution.
 */
class CRM_contribcustommulti_Form_Contribution extends CRM_Contribute_Form_Contribution {
  
  public $_customValueCount;
  protected $_editOptions = array();
 
  /**
   * Set variables up before form is built.
   */
  public function preProcess() {
    if (CRM_Utils_System::isNull($this->_editOptions)) {

      $this->set('CustomData', $this->_editOptions);

    }
    
    if (!empty($_POST['hidden_custom'])) {
      $customGroupCount = CRM_Utils_Array::value('hidden_custom_group_count', $_POST);

      if ($contribSubType = CRM_Utils_Array::value('financial_type_id', $_POST)) {
        $paramSubType = implode(',', $contribSubType);
      }

      $this->_getCachedTree = FALSE;
      unset($customGroupCount[0]);
      foreach ($customGroupCount as $groupID => $groupCount) {
        if ($groupCount > 1) {
          $this->set('groupID', $groupID);
          //loop the group
          for ($i = 0; $i <= $groupCount; $i++) {
            CRM_Custom_Form_CustomData::preProcess($this, NULL, $contribSubType,
              $i, 'Contribution', $this->_id
            );
            CRM_contribcustommulti_Form_Edit_CustomData::buildQuickForm($this);
          }
        }
      }

      //reset all the ajax stuff, for normal processing
      if (isset($this->_groupTree)) {
        $this->_groupTree = NULL;
      }
      $this->set('groupID', NULL);
      $this->_getCachedTree = TRUE;
    }
    // execute preProcess dynamically by js else execute normal preProcess
    if (array_key_exists('CustomData', $this->_editOptions)) {
      // execute preProcess dynamically by js else execute normal preProcess
      //assign a parameter to pass for sub type multivalue
      //custom field to load
      if ($this->_contributionType || isset($paramSubType)) {
        $paramSubType = (isset($paramSubType)) ? $paramSubType :
          str_replace(CRM_Core_DAO::VALUE_SEPARATOR, ',', trim($this->_contributionType, CRM_Core_DAO::VALUE_SEPARATOR));

        $this->assign('paramSubType', $paramSubType);
      }

      if (CRM_Utils_Request::retrieve('type', 'String')) {
        CRM_contribcustommulti_Form_Edit_CustomData::preProcess($this);
      }
      else {
        $contribSubType = $this->_contributionType;
        // need contact sub type to build related grouptree array during post process
        if (!empty($_POST['financial_type_id'])) {
          $contribSubType = $_POST['financial_type_id'];
        }
        //only custom data has preprocess hence directly call it
        CRM_Custom_Form_CustomData::preProcess($this, NULL, $contribSubType,
          1, 'Contribution', $this->_id
        );
        $this->assign('customValueCount', $this->_customValueCount);
      }
    }
    
    parent::preProcess();
  }

  /**
   * Set default values.
   *
   * @return array
   */
  public function setDefaultValues() {
    parent::setDefaultValues();
    $defaults = $this->_values;
    return $defaults;
  }

  /**
   * Build the form object.
   */
  public function buildQuickForm() {
    // build Custom data if Custom data present in edit option
    $buildCustomData = 'noCustomDataPresent';
    if (array_key_exists('CustomData', $this->_editOptions)) {
      $buildCustomData = "customDataPresent";
    }
    // build edit blocks ( custom data, demographics, communication preference, notes, tags and groups )
    foreach ($this->_editOptions as $name => $label) {
      $className = 'CRM_contribcustommulti_Form_Edit_' . $name;
      $className::buildQuickForm($this);
    }
    parent::buildQuickForm();    
  }
  
  /**

   * Form submission of new/edit contrib is processed.

   */

  public function postProcess() {
    $params = $this->controller->exportValues($this->_name);
   
    $customFieldExtends = (CRM_Utils_Array::value('financial_type_id', $params)) ? $params['financial_type_id'] : 'Contribution';
    
    $params['custom'] = CRM_Core_BAO_CustomField::postProcess($params,
      $this->_id,
      $customFieldExtends,
      TRUE
    );
    
     echo '<pre>';
    print_r($params);
    echo '</pre>';
    die;
    
  }
}


