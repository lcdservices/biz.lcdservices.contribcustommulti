<?php

require_once 'contribcustommulti.civix.php';
require_once 'CRM/LCD/Custom/Form/CustomData.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function contribcustommulti_civicrm_config(&$config) {
  _contribcustommulti_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function contribcustommulti_civicrm_xmlMenu(&$files) {
  _contribcustommulti_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function contribcustommulti_civicrm_install() {
  _contribcustommulti_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function contribcustommulti_civicrm_uninstall() {
  _contribcustommulti_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function contribcustommulti_civicrm_enable() {
  _contribcustommulti_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function contribcustommulti_civicrm_disable() {
  _contribcustommulti_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function contribcustommulti_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _contribcustommulti_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function contribcustommulti_civicrm_managed(&$entities) {
  _contribcustommulti_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function contribcustommulti_civicrm_caseTypes(&$caseTypes) {
  _contribcustommulti_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function contribcustommulti_civicrm_angularModules(&$angularModules) {
_contribcustommulti_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function contribcustommulti_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _contribcustommulti_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function contribcustommulti_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function contribcustommulti_civicrm_navigationMenu(&$menu) {
  _contribcustommulti_civix_insert_navigation_menu($menu, NULL, array(
    'label' => ts('The Page', array('domain' => 'biz.lcdservices.contribcustommulti')),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _contribcustommulti_civix_navigationMenu($menu);
} // */

/**
 * Implements hook_civicrm_buildForm().
 *
 * @param string $formName
 * @param CRM_Core_Form $form
 */
function contribcustommulti_civicrm_buildForm($formName, &$form) {
  if( $formName == 'CRM_Custom_Form_Group' ) {
    $contactTypes = array('Contact', 'Individual', 'Household', 'Organization', 'Contribution');
    $form->assign('contactTypes', json_encode($contactTypes));
    //add template to run jquery for Display Style option on Custom Data set form
    CRM_Core_Region::instance('page-body')->add(array(
      'template' => "CRM/LCD/Custom/Form/group.tpl"
    ));
  }
  //add template to or Display Style option on Custom Data set form on Contribution page
  if( $formName == 'CRM_Contribute_Form_Contribution' ) {
    $custom_default = CRM_Core_BAO_CustomGroup::setDefaults($form->_groupTree, $defaults, FALSE, FALSE, $form->get('action'));
    $form->setDefaults($custom_default);
    CRM_Core_Region::instance('page-body')->add(array(
      'template' => "CRM/LCD/Custom/Form/CustomDataType.tpl"
    ));  
  }
  if( $formName == 'CRM_Custom_Form_CustomDataByType' && ( $form->getVar( '_type' ) == 'Contribution' )) {
    $group = $form->getVar('_groupTree');
    $form->setVar( '_groupTree', NULL );
  }
}
/**
 * Implements hook_civicrm_preProcess().
 *
 * @param string $formName
 * @param CRM_Core_Form $form
 */
function contribcustommulti_civicrm_preProcess($formName, &$form) {
  if ( is_a( $form, 'CRM_Contribute_Form_Contribution' ) ) {
    $data = CRM_Contrib_Form_CustomData::preProcess($form, NULL, $form->_contributionType, NULL,
      ($form->_type) ? $form->_type : 'Contribution'
    );    
    
    $form->assign('_groupTree', $form->_groupTree);
    foreach($form->_groupTree as $key => $cached_tree){
      foreach($cached_tree['fields'] as $field_key => $cached_tree_fields){
        foreach($cached_tree_fields as $element_key=>$field_value){
          $required = CRM_Utils_Array::value('is_required', $field_value);
          if ($field_value['data_type'] == 'File') {
            if (!empty($field_value['element_value']['data'])) {
              $required = 0;
            }
          }
          $fieldId = $field_value['id'];
          $elementName = $field_value['element_name'];
          CRM_Core_BAO_CustomField::addQuickFormElement($form, $elementName, $fieldId, $required);
          $defaults[$elementName] = $field_value['element_value'];
        }
      }
    }
    $form->setDefaults($defaults);
  }
}
/**
 * Implements hook_civicrm_postProcess().
 *
 * @param string $formName
 * @param CRM_Core_Form $form
 */
function contribcustommulti_civicrm_postProcess($formName, &$form) {
  if ( is_a( $form, 'CRM_Contribute_Form_Contribution' ) ) {
    $data = $form->getVar('_submitValues');
    $id = $form->getVar('_id');
    foreach($data as $key=>$value){
      if(stripos($key, 'custom') === 0) {
      $params = array('id' => $id, 'entity_table' => 'Contribution', $key => $value);
      $result = civicrm_api3('Contribution', 'create', $params);
      }
    }
  }
}