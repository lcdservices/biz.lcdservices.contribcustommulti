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
 | CiviCRM is distributed in the hope that it will be useful, but     |
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
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2018
 */

/**
 * Form helper class for an Demographics object.
 */
class CRM_contribcustommulti_Form_Edit_CustomData {

   /**
   * Build all the data structures needed to build the form.
   *
   * @param CRM_Core_Form $form
   */
  public static function preProcess(&$form) {
    $form->_type = CRM_Utils_Request::retrieve('type', 'String');
    $form->_subType = CRM_Utils_Request::retrieve('subType', 'String');

    //build the custom data as other blocks.
    //$form->assign( "addBlock", false );
    if ($form->_type) {
      $form->_addBlockName = 'CustomData';
      $form->assign("addBlock", TRUE);
      $form->assign("blockName", $form->_addBlockName);
    }

    CRM_Custom_Form_CustomData::preProcess($form, NULL, $form->_contributionType, NULL,
      ($form->_type) ? $form->_type : 'Contribution'
    );

    //assign group tree after build.
    $form->assign('groupTree', $form->_groupTree);
  }

  /**
   * Build the form object elements for CustomData object.
   *
   * @param CRM_Core_Form $form
   *   Reference to the form object.
   */
  public static function buildQuickForm(&$form) {
    if (!empty($form->_submitValues)) {
      if ($customValueCount = CRM_Utils_Array::value('hidden_custom_group_count', $form->_submitValues)) {
        if (is_array($customValueCount)) {
          if (array_key_exists(0, $customValueCount)) {
            unset($customValueCount[0]);
          }
          $form->_customValueCount = $customValueCount;
          $form->assign('customValueCount', $customValueCount);
        }
      }
    }
    CRM_Custom_Form_CustomData::buildQuickForm($form);

    //build custom data.
    $contactSubType = NULL;
    if (!empty($_POST["hidden_custom"]) && !empty($_POST['financial_type_id'])) {
      $contactSubType = $_POST['financial_type_id'];
    }
    else {
      $contactSubType = CRM_Utils_Array::value('financial_type_id', $form->_values);
    }
    $form->assign('contactType', 'Contribution');
    $form->assign('contactSubType', $contactSubType);
  }

  /**
   * Set default values for the form. Note that in edit/view mode
   * the default values are retrieved from the database
   *
   *
   * @param CRM_Core_Form $form
   * @param array $defaults
   */
  public static function setDefaultValues(&$form, &$defaults) {
    $defaults += CRM_Custom_Form_CustomData::setDefaultValues($form);
  }

}