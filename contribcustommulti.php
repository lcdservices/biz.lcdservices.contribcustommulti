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
    CRM_Core_Resources::singleton()->addScriptFile('biz.lcdservices.contribcustommulti', 'js/custom_group.js');
  }

  if (in_array($formName, array(
    'CRM_Contribute_Form_Contribution_Main',
    'CRM_Contribute_Form_Contribution_Confirm',
    'CRM_Contribute_Form_Contribution_ThankYou'
  ))) {
    $addTemplate = FALSE;
    $contribMultiCustomGroupId  = _contribcustommulti_civicrm_is_multiple_contrib(
      CRM_Core_Smarty::singleton()->get_template_vars('customPre')
    );
    if ($contribMultiCustomGroupId) {
      $profileBlock = ($formName == 'CRM_Contribute_Form_Contribution_Main') 
        ? 'custom_pre_profile-group' : 'crm-profile-view:first';
      $contribMultiInfo = array(
        'add_more_div'  => $profileBlock,
        'add_more_cgid' => $contribMultiCustomGroupId,
        'profile_id'    => $form->_values['custom_pre_id'],
      );
      $form->assign('contribCustomMulti', $contribMultiInfo);
      $addTemplate = TRUE;
      _contribcustommulti_civicrm_assign_multiple_contrib_fields(
        CRM_Core_Smarty::singleton()->get_template_vars('customPre')
      );
    }
    $contribMultiCustomGroupId  = _contribcustommulti_civicrm_is_multiple_contrib(
      CRM_Core_Smarty::singleton()->get_template_vars('customPost')
    );
    if ($contribMultiCustomGroupId) {
      $profileBlock = ($formName == 'CRM_Contribute_Form_Contribution_Main') 
        ? 'custom_post_profile-group' : 'crm-profile-view:eq(1)';
      $contribMultiInfo = array(
        'add_more_div'  => $profileBlock,
        'add_more_cgid' => $contribMultiCustomGroupId,
        'profile_id'    => $form->_values['custom_post_id'],
      );
      $form->assign('contribCustomMulti', $contribMultiInfo);
      $addTemplate = TRUE;
      _contribcustommulti_civicrm_assign_multiple_contrib_fields(
        CRM_Core_Smarty::singleton()->get_template_vars('customPost')
      );
    }
    if ($addTemplate) {
      CRM_Core_Region::instance('page-body')->add(array(
        'template' => "CRM/LCD/Form/Contribution/CustomMulti.tpl"
      ));
    }
  }

  // Contribution edit form - load more rows if present
  // TODO: Something for next phase - we assuming only 1 multi custom set at the moment. 
  if ($formName == 'CRM_Contribute_Form_Contribution') {
    $entityID = $form->getVar('_id');
    if ($entityID) {
      $groups = civicrm_api3('CustomGroup', 'get', array(
        'extends' => 'contribution',
        'is_multiple' => 1,
      ));
      if (!empty($groups['values'])) {
        foreach ($groups['values'] as $gid => $group) {
          if ($group['table_name']) {
            $sql = "select count(*) from {$group['table_name']} where entity_id = %1";
            $totalCgCount = CRM_Core_DAO::singleValueQuery($sql, array(1 => array($entityID, 'Integer')));
            if ($totalCgCount > 1) {
              CRM_Core_Region::instance('page-body')->add(array(
                'template' => "CRM/LCD/Form/Contribution/Contribution.tpl"
              ));
              CRM_Core_Resources::singleton()->addVars('contribCustomMulti', array(
                'cgid' => $gid,
                'totalcgcount' => $totalCgCount,
              ));
              break;
            }
          }
        }
      }
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
      if ($customFieldId = CRM_Core_BAO_CustomField::getKeyID($name)) {
        $customGroupId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_CustomField', $customFieldId, 'custom_group_id');
        if ($customGroupId) {
          $isMultiple = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_CustomGroup', $customGroupId, 'is_multiple');
          if ($isMultiple) {
            return $customGroupId;
          }
        }
      }
    }
  }
  return FALSE;
}

/**
 * Given custom-pre or custom-post block, build a js array of contribution multi custom fields
 * included in the profile.
 *
 * @param array $customBlock
 */
function _contribcustommulti_civicrm_assign_multiple_contrib_fields($customBlock = array()) {
  $mFields = array();
  foreach ($customBlock as $name => $field) {
    if ($field['field_type'] == 'Contribution') {
      if ($customFieldId = CRM_Core_BAO_CustomField::getKeyID($name)) {
        $customGroupId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_CustomField', $customFieldId, 'custom_group_id');
        if ($customGroupId) {
          $isMultiple = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_CustomGroup', $customGroupId, 'is_multiple');
          if ($isMultiple) {
            $mFields[] = $name; 
          }
        }
      }
    }
  }
  CRM_Core_Resources::singleton()->addVars('contribCustomMulti', array(
    'profileFields' => $mFields
  ));
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
  /*Civi::log()->debug('postProcess', array(
    'formName' => $formName,
    '$_REQUEST' => $_REQUEST,
  ));*/

  //handle contrib custom multi fields
  if ($formName == 'CRM_Contribute_Form_Contribution') {
    $fieldIds = _contribcustommulti_getContribCustomMulti();
    $entityID = $form->getVar('_id');
    $customFieldExtends = 'Contribution';
    //extract custom data from $_REQUEST
    $customRows = array();
    foreach ($_REQUEST as $field => $value) {
      // $form->_params will have and store only first row. Here we try to store only additional rows.
      if (strpos($field, 'custom_') !== FALSE && !array_key_exists($field, $form->_params)) {
        $parts = explode('_', $field);
        // Civi::log()->debug('contribcustommulti_civicrm_postProcess', array('$parts' => $parts));
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

  // For contribution with profile - additional contrib multi rows won't be present in form.
  // Here we filter and save them in session, so we pass onto Confirm step
  if ($formName == 'CRM_Contribute_Form_Contribution_Main') {
    $params = $form->controller->exportValues('Main');
    $contribFields = CRM_Core_BAO_CustomField::getFieldsForImport('Contribution', false, false, false, true, true);
    $customGroupID = NULL;
    $contribMultiParams = array();
    foreach ($_POST as $key => $val) {
      if ($customFieldID = CRM_Core_BAO_CustomField::getKeyID($key)) {
        if (array_key_exists("custom_{$customFieldID}", $contribFields) && !array_key_exists($key, $params)) {
          if (!$customGroupID) {
            $customGroupID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_CustomField', $customFieldID, 'custom_group_id');
          }
          $contribMultiParams[$key] = $val;
        }
      }
    }
    $form->set('contribMultiParams', $contribMultiParams);
    $form->set('contribMultiCustomGroupId', $customGroupID);
  }
}

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 */
function contribcustommulti_civicrm_preProcess($formName, &$form) {
  // on ajax calls to inject custom sets on online contribution pages using profile,
  // filter grouptree to remove any fields not included in profile.
  if ($formName == 'CRM_Custom_Form_CustomDataByType') {
    $gId = $form->_groupID;
    $profileId = CRM_Utils_Request::retrieve('profileID', 'Positive');
    if ($profileId && $gId) {
      $form->assign('profileID', $profileId);
      $profileFields = CRM_Core_BAO_UFGroup::getFields($profileId, FALSE, CRM_Core_Action::ADD, NULL, NULL, FALSE,
        NULL, FALSE, NULL, CRM_Core_Permission::CREATE, NULL
      );
      $groupTree =& $form->_groupTree;
      if (!empty($groupTree[$gId]) && $groupTree[$gId]['is_multiple'] == 1) {
        foreach ($groupTree[$gId]['fields'] as $fid => $values) {
          if (!array_key_exists("custom_{$fid}", $profileFields)) {
            unset($groupTree[$gId]['fields'][$fid]);
          }
        }
      }
    }
  }
  // Get ready with number of initial rows to display, and set defaults if using 
  // prev and next buttons.
  if (in_array($formName, array(
    'CRM_Contribute_Form_Contribution_Main',
  ))) {
    $contribMultiParams = $form->get('contribMultiParams');
    $cgcount = 1;
    if (!empty($contribMultiParams)) {
      $cgids = array();
      foreach($contribMultiParams as $key => $val) {
        if ($customFieldID = CRM_Core_BAO_CustomField::getKeyID($key)) {
          $cgids[$customFieldID] = empty($cgids[$customFieldID]) ? 1 : ++$cgids[$customFieldID];
        }
      }
      $cgcount = max($cgids);
    }
    $form->assign('contribMultiCgcount', $cgcount);
    CRM_Core_Resources::singleton()->addVars('contribCustomMulti', array(
      'cgcount'  => $cgcount,
      'defaults' => $contribMultiParams
    ));
  }

  // For Confirm and Thankyou - display just display element values
  if (in_array($formName, array(
    'CRM_Contribute_Form_Contribution_Confirm',
    'CRM_Contribute_Form_Contribution_ThankYou'
  ))) {
    $contribMultiParams = $form->get('contribMultiParams');
    if (!empty($contribMultiParams)) {
      $rows = array();
      $rowsHeader = array();
      $customGroupId = $form->get('contribMultiCustomGroupId');
      if ($customGroupId) {
        $groupTree = CRM_Core_BAO_CustomGroup::getTree('Contribution', array(), NULL, $customGroupId);
        foreach($contribMultiParams as $key => $val) {
          if (list($customFieldID, $index) = CRM_Core_BAO_CustomField::getKeyID($key, TRUE)) {
            $display = CRM_Core_BAO_CustomField::displayValue($val, $key);
            $rows[$index][$customFieldID] = $display;
            $rowsHeader[$customFieldID]   = !empty($groupTree[$customGroupId]['fields'][$customFieldID]['label']) ? 
              $groupTree[$customGroupId]['fields'][$customFieldID]['label'] : '';
          }
        }
      }
      $form->assign('contribMultiRows', $rows);
      $form->assign('contribMultiRowsHeader', $rowsHeader);
    }
  }

  // on confirm set params in session, so injected contrib multi params gets picked up
  // and stored in DB
  if ($formName == 'CRM_Contribute_Form_Contribution_Confirm') {
    $params = $form->get('params');
    $contribParams = $form->get('contribMultiParams');
    if (!empty($contribParams)) {
      $params = array_merge($params, $contribParams);
      $form->_params = $params;
      $form->set('params', $params);
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
