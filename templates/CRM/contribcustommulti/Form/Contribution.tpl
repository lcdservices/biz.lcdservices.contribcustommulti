{*
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
*}
{* this template is used for adding/editing/deleting contributions and pledge payments *}
{include file="CRM/Contribute/Form/Contribution.tpl"}
<div id='customData'>{include file="CRM/contribcustommulti/Form/Edit/CustomData.tpl"}</div>

{literal}

  <script type="text/javascript" >
  CRM.$(function($) {

    var values = $("#financial_type_id").val();
      CRM.buildCustomData({/literal}"{$customDataType}"{literal}, values).one('crmLoad', function() {
        loadMultiRecordFields(values);
    });

    function loadMultiRecordFields(subTypeValues) {
      if (subTypeValues === false) {
        subTypeValues = null;
      }
      else if (!subTypeValues) {
        subTypeValues = {/literal}"{$paramSubType}"{literal};
      }
      function loadNextRecord(i, groupValue, groupCount) {
        if (i < groupCount) {
          CRM.buildCustomData({/literal}"{$customDataType}"{literal}, subTypeValues, null, i, groupValue, true).one('crmLoad', function() {
            loadNextRecord(i+1, groupValue, groupCount);
          });
        }
      }
      {/literal}
      {foreach from=$customValueCount item="groupCount" key="groupValue"}
      {if $groupValue}{literal}
        loadNextRecord(1, {/literal}{$groupValue}{literal}, {/literal}{$groupCount}{literal});
      {/literal}
      {/if}
      {/foreach}
      {literal}
    }

    loadMultiRecordFields();
  });

</script>
{/literal}