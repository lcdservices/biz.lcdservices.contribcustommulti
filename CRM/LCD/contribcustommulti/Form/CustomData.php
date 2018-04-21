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
 
class CRM_Contrib_Form_CustomData {

  /**
   * @param CRM_Core_Form $form
   * @param null|string $subName
   * @param null|string $subType
   * @param null|int $groupCount
   * @param string $type
   * @param null|int $entityID
   * @param null $onlySubType
   */
  public static function preProcess(
    &$form, $subName = NULL, $subType = NULL,
    $groupCount = NULL, $type = NULL, $entityID = NULL, $onlySubType = NULL
  ) {
    if ($type) {
      $form->_type = $type;
    }
    else {
      $form->_type = CRM_Utils_Request::retrieve('type', 'String', $form);
    }

    if (isset($subType)) {
      $form->_subType = $subType;
    }
    else {
      $form->_subType = CRM_Utils_Request::retrieve('subType', 'String', $form);
    }

    if ($form->_subType == 'null') {
      $form->_subType = NULL;
    }

    if (isset($subName)) {
      $form->_subName = $subName;
    }
    else {
      $form->_subName = CRM_Utils_Request::retrieve('subName', 'String', $form);
    }

    if ($form->_subName == 'null') {
      $form->_subName = NULL;
    }

    if ($groupCount) {
      $form->_groupCount = $groupCount;
    }
    else {
      $form->_groupCount = CRM_Utils_Request::retrieve('cgcount', 'Positive', $form);
    }

    $form->assign('cgCount', $form->_groupCount);

    //carry qf key, since this form is not inhereting core form.
    if ($qfKey = CRM_Utils_Request::retrieve('qfKey', 'String')) {
      $form->assign('qfKey', $qfKey);
    }

    if ($entityID) {
      $form->_entityId = $entityID;
    }
    else {
      $form->_entityId = CRM_Utils_Request::retrieve('entityID', 'Positive', $form);
    }

    $typeCheck = CRM_Utils_Request::retrieve('type', 'String');
    $urlGroupId = CRM_Utils_Request::retrieve('groupID', 'Positive');
    if (isset($typeCheck) && $urlGroupId) {
      $form->_groupID = $urlGroupId;
    }
    else {
      $form->_groupID = CRM_Utils_Request::retrieve('groupID', 'Positive', $form);
    }

    $gid = (isset($form->_groupID)) ? $form->_groupID : NULL;
    $getCachedTree = isset($form->_getCachedTree) ? $form->_getCachedTree : TRUE;

    $subType = $form->_subType;
    if (!is_array($subType) && strstr($subType, CRM_Core_DAO::VALUE_SEPARATOR)) {
      $subType = str_replace(CRM_Core_DAO::VALUE_SEPARATOR, ',', trim($subType, CRM_Core_DAO::VALUE_SEPARATOR));
    }

    self::setGroupTree($form, $subType, $gid, $onlySubType, $getCachedTree);
  }

  /**
   * @param CRM_Core_Form $form
   *
   * @return array
   */
  public static function setDefaultValues(&$form) {
    $defaults = array();
    CRM_Core_BAO_CustomGroup::setDefaults($form->_groupTree, $defaults, FALSE, FALSE, $form->get('action'));
    return $defaults;
  }

  /**
   * @param CRM_Core_Form $form
   */
  public static function buildQuickForm(&$form) {
    $form->addElement('hidden', 'hidden_custom', 1);
    $form->addElement('hidden', "hidden_custom_group_count[{$form->_groupID}]", $form->_groupCount);
    CRM_Core_BAO_CustomGroup::buildQuickForm($form, $form->_groupTree);
  }

  /**
   * @param $form
   * @param $subType
   * @param $gid
   * @param $onlySubType
   * @param $getCachedTree
   *
   * @return array
   */
  public static function setGroupTree(&$form, $subType, $gid, $onlySubType = NULL, $getCachedTree = FALSE) {
    $singleRecord = NULL;
    if (!empty($form->_groupCount) && !empty($form->_multiRecordDisplay) && $form->_multiRecordDisplay == 'single') {
      $singleRecord = $form->_groupCount;
    }
    $mode = CRM_Utils_Request::retrieve('mode', 'String', $form);
    // when a new record is being added for multivalued custom fields.
    if (isset($form->_groupCount) && $form->_groupCount == 0 && $mode == 'add' &&
      !empty($form->_multiRecordDisplay) && $form->_multiRecordDisplay == 'single') {
      $singleRecord = 'new';
    }

    $groupTree = CRM_Core_BAO_CustomGroup::getTree($form->_type,
      NULL,
      $form->_entityId,
      $gid,
      $subType,
      $form->_subName,
      $getCachedTree,
      $onlySubType,
      FALSE,
      TRUE,
      $singleRecord
    );

    if (property_exists($form, '_customValueCount') && !empty($groupTree)) {
      $form->_customValueCount = CRM_Core_BAO_CustomGroup::buildCustomDataView($form, $groupTree, TRUE, NULL, NULL, NULL, $form->_entityId);
    }
    // we should use simplified formatted groupTree
    $groupTree = self::formatGroupTree($groupTree, $form->_groupCount, $form);
    if (isset($form->_groupTree) && is_array($form->_groupTree)) {
      $keys = array_keys($groupTree);
      foreach ($keys as $key) {
        $form->_groupTree[$key] = $groupTree[$key];
      }
      return array($form, $groupTree);
    }
    else {
      $form->_groupTree = $groupTree;
      return array($form, $groupTree);
    }
  }

  /**
   * Function returns formatted groupTree, sothat form can be easily build in template
   *
   * @param array $groupTree
   * @param int $groupCount
   *   Group count by default 1, but can varry for multiple value custom data.
   * @param object $form
   *
   * @return array
   */
  public static function formatGroupTree(&$groupTree, $groupCount = 1, &$form = NULL) {
    $formattedGroupTree = array();
    $uploadNames = $formValues = array();

    // retrieve qf key from url
    $qfKey = CRM_Utils_Request::retrieve('qf', 'String');

    // fetch submitted custom field values later use to set as a default values
    if ($qfKey) {
      $submittedValues = CRM_Core_BAO_Cache::getItem('custom data', $qfKey);
    }
    $getParams = array(
      'entityID' => $form->_id,
      'entityType' => 'Contribution',
    );
    $result = self::getValues($getParams);
    // Convert multi-value strings to arrays
    $sp = CRM_Core_DAO::VALUE_SEPARATOR;
    foreach ($result as $gid => $field_value) {
      
      if(is_numeric($gid)){
        $group_id = $gid;
        $values[$gid] = array();
        
        foreach($field_value as $id => $value){
          $field_name = $id;
          if (strpos($value, $sp) !== FALSE) {
            $value = explode($sp, trim($value, $sp));
          }

          $idArray = explode('_', $id);
          if ($idArray[0] != 'custom') {
            continue;
          }
          $fieldNumber = $idArray[1];
          $customFieldInfo = CRM_Core_BAO_CustomField::getNameFromID($fieldNumber);
          $info = array_pop($customFieldInfo);

          if (empty($idArray[2])) {
            $n = 0;
            $id = $fieldNumber;
          }
          else {
            $n = $idArray[2];
            $id = $fieldNumber . "." . $idArray[2];
          }
          if (!empty($getParams['format.field_names'])) {
            $id = $info['field_name'];
          }
          else {
            $id = $fieldNumber;
          }
         
          //set 'latest' -useful for multi fields but set for single for consistency
          $values[$gid][$n][$id]['id'] = $id;
          $values[$gid][$n][$id]['entity_id'] = $getParams['entityID'];
          $values[$gid][$n][$id]['element_name'] = $field_name;
          $values[$gid][$n][$id]['element_value'] = $value;
          $values[$gid][$n][$id][$n] = $value;
        }
      }
    }
    foreach ($groupTree as $key => $value) {
      if ($key === 'info') {
        continue;
      }
        
      
      // add group information
      $formattedGroupTree[$key]['name'] = CRM_Utils_Array::value('name', $value);
      $formattedGroupTree[$key]['title'] = CRM_Utils_Array::value('title', $value);
      $formattedGroupTree[$key]['help_pre'] = CRM_Utils_Array::value('help_pre', $value);
      $formattedGroupTree[$key]['help_post'] = CRM_Utils_Array::value('help_post', $value);
      $formattedGroupTree[$key]['collapse_display'] = CRM_Utils_Array::value('collapse_display', $value);
      $formattedGroupTree[$key]['collapse_adv_display'] = CRM_Utils_Array::value('collapse_adv_display', $value);
      $formattedGroupTree[$key]['style'] = CRM_Utils_Array::value('style', $value);

      // this params needed of bulding multiple values
      $formattedGroupTree[$key]['is_multiple'] = CRM_Utils_Array::value('is_multiple', $value);
      $formattedGroupTree[$key]['extends'] = CRM_Utils_Array::value('extends', $value);
      $formattedGroupTree[$key]['extends_entity_column_id'] = CRM_Utils_Array::value('extends_entity_column_id', $value);
      $formattedGroupTree[$key]['extends_entity_column_value'] = CRM_Utils_Array::value('extends_entity_column_value', $value);
      $formattedGroupTree[$key]['subtype'] = CRM_Utils_Array::value('subtype', $value);
      $formattedGroupTree[$key]['max_multiple'] = CRM_Utils_Array::value('max_multiple', $value);

      // add field information
      foreach($values as $gid=>$fields){
        foreach($fields as $rowKey=>$rowNumber){
          foreach($rowNumber as $fieldID=>$fieldValue){
            if($fieldValue['id'] == $value['fields'][$fieldID]['id']){
              $merge = array_merge($fieldValue, $value['fields'][$fieldID]);
              $values[$gid][$rowKey][$fieldID] = $merge;
              
            }
          }
        }
      }
      $formattedGroupTree[$key]['fields'] = $values[$key];
    }
    if ($form) {
      if (count($formValues)) {
        $qf = $form->get('qfKey');
        $form->assign('qfKey', $qf);
        CRM_Core_BAO_Cache::setItem($formValues, 'custom data', $qf);
      }

      // hack for field type File
      $formUploadNames = $form->get('uploadNames');
      if (is_array($formUploadNames)) {
        $uploadNames = array_unique(array_merge($formUploadNames, $uploadNames));
      }

      $form->set('uploadNames', $uploadNames);
    }

    return $formattedGroupTree;
  }
  /**
   * Take in an array of entityID, custom_ID
   * and gets the value from the appropriate table.
   *
   * To get the values of custom fields with IDs 13 and 43 for contact ID 1327, use:
   * $params = array( 'entityID' => 1327, 'custom_13' => 1, 'custom_43' => 1 );
   *
   * Entity Type will be inferred by the custom fields you request
   * Specify $params['entityType'] if you do not supply any custom fields to return
   * and entity type is other than Contact
   *
   * @array $params
   *
   * @param array $params
   *
   * @throws Exception
   * @return array
   */
  public static function &getValues(&$params) {
    if (empty($params)) {
      return NULL;
    }
    if (!isset($params['entityID']) ||
      CRM_Utils_Type::escape($params['entityID'],
        'Integer', FALSE
      ) === NULL
    ) {
      return CRM_Core_Error::createAPIError(ts('entityID needs to be set and of type Integer'));
    }

    // first collect all the ids. The format is:
    // custom_ID
    $fieldIDs = array();
    foreach ($params as $n => $v) {
      $key = $idx = NULL;
      if (substr($n, 0, 7) == 'custom_') {
        $idx = substr($n, 7);
        if (CRM_Utils_Type::escape($idx, 'Integer', FALSE) === NULL) {
          return CRM_Core_Error::createAPIError(ts('field ID needs to be of type Integer for index %1',
            array(1 => $idx)
          ));
        }
        $fieldIDs[] = (int ) $idx;
      }
    }

    $default = array('Contact', 'Individual', 'Household', 'Organization');
    if (!($type = CRM_Utils_Array::value('entityType', $params)) ||
      in_array($params['entityType'], $default)
    ) {
      $type = NULL;
    }
    else {
      $entities = CRM_Core_SelectValues::customGroupExtends();
      if (!array_key_exists($type, $entities)) {
        if (in_array($type, $entities)) {
          $type = $entities[$type];
          if (in_array($type, $default)) {
            $type = NULL;
          }
        }
        else {
          return CRM_Core_Error::createAPIError(ts('Invalid entity type') . ': "' . $type . '"');
        }
      }
    }

    $values = self::getEntityValues($params['entityID'],
      $type,
      $fieldIDs
    );
    if (empty($values)) {
      // note that this behaviour is undesirable from an API point of view - it should return an empty array
      // since this is also called by the merger code & not sure the consequences of changing
      // are just handling undoing this in the api layer. ie. converting the error back into a success
      $result = array(
        'is_error' => 1,
        'error_message' => 'No values found for the specified entity ID and custom field(s).',
      );
      return $result;
    }
    else {
      $result = array(
        'is_error' => 0,
        'entityID' => $params['entityID'],
      );
      foreach ($values as $gid => $value) {
        foreach($value as $id => $field_value){
          $result[$gid]["custom_{$id}"] = $field_value;
        }
      }
      return $result;
    }
  }
  
   /**
   * Return an array of all custom values associated with an entity.
   *
   * @param int $entityID
   *   Identification number of the entity.
   * @param string $entityType
   *   Type of entity that the entityID corresponds to, specified.
   *                                   as a string with format "'<EntityName>'". Comma separated
   *                                   list may be used to specify OR matches. Allowable values
   *                                   are enumerated types in civicrm_custom_group.extends field.
   *                                   Optional. Default value assumes entityID references a
   *                                   contact entity.
   * @param array $fieldIDs
   *   Optional list of fieldIDs that we want to retrieve. If this.
   *                                   is set the entityType is ignored
   *
   * @param bool $formatMultiRecordField
   * @param array $DTparams - CRM-17810 dataTable params for the multiValued custom fields.
   *
   * @return array
   *   Array of custom values for the entity with key=>value
   *                                   pairs specified as civicrm_custom_field.id => custom value.
   *                                   Empty array if no custom values found.
   */
  public static function &getEntityValues($entityID, $entityType = NULL, $fieldIDs = NULL, $formatMultiRecordField = FALSE, $DTparams = NULL) {
    if (!$entityID) {
      // adding this here since an empty contact id could have serious repurcussions
      // like looping forever
      CRM_Core_Error::fatal('Please file an issue with the backtrace');
      return NULL;
    }

    $cond = array();
    if ($entityType) {
      $cond[] = "cg.extends IN ( '$entityType' )";
    }
    if ($fieldIDs &&
      is_array($fieldIDs)
    ) {
      $fieldIDList = implode(',', $fieldIDs);
      $cond[] = "cf.id IN ( $fieldIDList )";
    }
    if (empty($cond)) {
      $cond[] = "cg.extends IN ( 'Contact', 'Individual', 'Household', 'Organization' )";
    }
    $cond = implode(' AND ', $cond);

    $limit = $orderBy = '';
    if (!empty($DTparams['rowCount']) && $DTparams['rowCount'] > 0) {
      $limit = " LIMIT " . CRM_Utils_Type::escape($DTparams['offset'], 'Integer') . ", " . CRM_Utils_Type::escape($DTparams['rowCount'], 'Integer');
    }
    if (!empty($DTparams['sort'])) {
      $orderBy = ' ORDER BY ' . CRM_Utils_Type::escape($DTparams['sort'], 'String');
    }

    // First find all the fields that extend this type of entity.
    $query = "
SELECT cg.table_name,
       cg.id as groupID,
       cg.is_multiple,
       cf.column_name,
       cf.id as fieldID,
       cf.data_type as fieldDataType
FROM   civicrm_custom_group cg,
       civicrm_custom_field cf
WHERE  cf.custom_group_id = cg.id
AND    cg.is_active = 1
AND    cf.is_active = 1
AND    $cond
";
    $dao = CRM_Core_DAO::executeQuery($query);
    $select = $fields = $isMultiple = array();

    while ($dao->fetch()) {
      if (!array_key_exists($dao->table_name, $select)) {
        $fields[$dao->table_name] = array();
        $select[$dao->table_name] = array();
      }
      $fields[$dao->table_name][] = $dao->fieldID;
      $fields[$dao->table_name]['groupID'] = $dao->groupID;
      $select[$dao->table_name][] = "{$dao->column_name} AS custom_{$dao->fieldID}";
      $isMultiple[$dao->table_name] = $dao->is_multiple ? TRUE : FALSE;
      $file[$dao->table_name][$dao->fieldID] = $dao->fieldDataType;
    }

    $result = $sortedResult = array();
    foreach ($select as $tableName => $clauses) {
      if (!empty($DTparams['sort'])) {
        $query = CRM_Core_DAO::executeQuery("SELECT id FROM {$tableName} WHERE entity_id = {$entityID}");
        $count = 1;
        while ($query->fetch()) {
          $sortedResult["{$query->id}"] = $count;
          $count++;
        }
      }

      $query = "SELECT SQL_CALC_FOUND_ROWS id, " . implode(', ', $clauses) . " FROM $tableName WHERE entity_id = $entityID {$orderBy} {$limit}";
      $dao = CRM_Core_DAO::executeQuery($query);
      if (!empty($DTparams)) {
        $result['count'] = CRM_Core_DAO::singleValueQuery('SELECT FOUND_ROWS()');
      }
      while ($dao->fetch()) {
        foreach ($fields[$tableName] as $key => $fieldID) {
        
        $group_id = $fields[$tableName]['groupID'];
          if(is_numeric($key) ){
            $fieldName = "custom_{$fieldID}";
            if ($isMultiple[$tableName]) {
              if ($formatMultiRecordField) {
                $result[$group_id]["{$dao->id}"]["{$fieldID}"] = $dao->$fieldName;
              }
              else {
                $result[$group_id]["{$fieldID}_{$dao->id}"] = $dao->$fieldName;
              }
            }
            else {
              $result[$group_id][$fieldID] = $dao->$fieldName;
            }
          }
          
        }
      }
    }
    if (!empty($sortedResult)) {
      $result['sortedResult'] = $sortedResult;
    }
    return $result;
  }

}
