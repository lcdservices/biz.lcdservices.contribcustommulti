<?php

require_once 'contribcustommulti.civix.php';

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
      'template' => "CRM/LCD/group.tpl"
    ));
  }
  if ($formName == 'CRM_Contribute_Form_Contribution_Main') {
    $addTemplate = FALSE;
    $contribMultiCustomGroupId  = _contribcustommulti_civicrm_is_multiple_contrib(
      CRM_Core_Smarty::singleton()->get_template_vars('customPre')
    );
    if ($contribMultiCustomGroupId) {
      $form->assign('contrib_multi_add_more_div', 'custom_pre_profile-group');
      $form->assign('contrib_multi_add_more_cgid', $contribMultiCustomGroupId);
      $customGroupName = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_CustomGroup', $contribMultiCustomGroupId, 'title');
      $form->assign('contrib_multi_add_more_cg_title', $customGroupName);
      $addTemplate = TRUE;
    }
    $contribMultiCustomGroupId  = _contribcustommulti_civicrm_is_multiple_contrib(
      CRM_Core_Smarty::singleton()->get_template_vars('customPost')
    );
    if ($contribMultiCustomGroupId) {
      $form->assign('contrib_multi_add_more_div', 'custom_post_profile-group');
      $form->assign('contrib_multi_add_more_cgid', $contribMultiCustomGroupId);
      $customGroupName = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_CustomGroup', $contribMultiCustomGroupId, 'title');
      $form->assign('contrib_multi_add_more_cg_title', $customGroupName);
      $addTemplate = TRUE;
    }
    if ($addTemplate) {
      CRM_Core_Region::instance('page-body')->add(array(
        'template' => "CRM/LCD/Form/Contribution/Main.tpl"
      ));
    }
  }
}

/**
 * Given custom-pre or custom-post block, identify if they contain any contribution
 * multi record custom fields.
 *
 * @param array $customBlock
 */
function _contribcustommulti_civicrm_is_multiple_contrib($customBlock = array()) {
  $customGroupId = NULL;
  foreach ($customBlock as $name => $field) {
    if ($field['field_type'] == 'Contribution') {
      if (CRM_Core_BAO_CustomField::getKeyID($name) && $field['group_id']) {
        $isMultiple = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_CustomGroup', $field['group_id'], 'is_multiple');
        if ($isMultiple) {
          $customGroupId = $field['group_id'];
          return $customGroupId;
        }
      }
    }
  }
  return FALSE;
}

/**
 * Implements hook_civicrm_alterMenu().
 *
 * @param string $formName
 * @param CRM_Core_Form $form
 */
function contribcustommulti_civicrm_alterTemplateFile($formName, &$form, $context, &$tplName) {
  if( $formName == 'CRM_Custom_Form_CustomDataByType' && ($form->getVar( '_type' ) == 'Contribution') ) {
    $possibleTpl = 'CRM/LCD/Custom/Form/CustomDataByType.tpl';
    $template = CRM_Core_Smarty::singleton();
    if ($template->template_exists($possibleTpl)) {
      $tplName = $possibleTpl;
    }
  }
}

function contribcustommulti_civicrm_postProcess($formName, &$form) {
  Civi::log()->debug('postProcess', array(
    'formName' => $formName,
    '$_REQUEST' => $_REQUEST,
  ));

  //handle contrib custom multi fields
  if ($formName == 'CRM_Contribute_Form_Contribution') {
    $fieldIds = _contribcustommulti_getContribCustomMulti();
	$values = $form->getVar('_values');
	$entityID = $values['id'];
	$customFieldExtends = 'Contribution';
    //extract custom data from $_REQUEST
    $customRows = array();
    foreach ($_REQUEST as $field => $value) {
      if (strpos($field, 'custom_') !== FALSE) {		
        $parts = explode('_', $field);
        Civi::log()->debug('contribcustommulti_civicrm_postProcess', array('$parts' => $parts));
        if (in_array($parts[1], $fieldIds)) {
          $customRows[$parts[2]][$field] = $value;
        }
      }
    }		
    //Civi::log()->debug('contribcustommulti_civicrm_postProcess', array('$customRows' => $customRows));

    //cycle through custom rows and save to the contact	
    foreach ($customRows as $key=>$row) {
      $customData = CRM_Core_BAO_CustomField::postProcess($row,
			  $entityID,
			  $customFieldExtends
			);
      $entityTable = 'Contribution';
      if (!empty($customData)) {
        CRM_Core_BAO_CustomValueTable::store($customData, $entityTable, $entityID);
      }
    }
  }
}

function contribcustommulti_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  /*Civi::log()->debug('post', array(
    '$op' => $op,
    '$objectName' => $objectName,
    '$objectId' => $objectId,
    '$objectRef' => $objectRef,
  ));*/
}


function _contribcustommulti_getContribCustomMulti() {
  $fieldIds = array();

  try {
    $groups = civicrm_api3('CustomGroup', 'get', array(
      'extends' => 'contribution',
      'is_multiple' => 1,
    ));
    //Civi::log()->debug('_contribcustommulti_getContribCustomMulti', array('$groups' => $groups));

    if ($groups['count']) {
      foreach ($groups['values'] as $group) {
        $fields = civicrm_api3('CustomField', 'get', array(
          'custom_group_id' => $group['id'],
          'is_active' => 1,
        ));
        //Civi::log()->debug('_contribcustommulti_getContribCustomMulti', array('$fields' => $fields));

        foreach ($fields['values'] as $field) {
          $fieldIds[] = $field['id'];
        }
      }
    }
  }
  catch (CRM_API3_Exception $e) {}

  return $fieldIds;
}
