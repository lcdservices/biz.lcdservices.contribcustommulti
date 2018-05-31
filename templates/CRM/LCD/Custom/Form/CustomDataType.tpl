{*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.7                                                |
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
<div id="contrib_custom_set">
{foreach from=$_groupTree item=cd_edit key=group_id}
  {if $cd_edit.is_multiple eq 1}
    {assign var=tableID value=$cd_edit.table_id}
    {assign var=divName value=$group_id|cat:"_$tableID"}
    <div></div>
    <div
     class="crm-accordion-wrapper crm-custom-accordion {if $cd_edit.collapse_display and !$skipTitle}collapsed{/if}">
  {else}
    <div id="{$cd_edit.name}"
       class="crm-accordion-wrapper crm-custom-accordion {if $cd_edit.collapse_display}collapsed{/if}">
  {/if}
    <div class="crm-accordion-header">
      {$cd_edit.title}
    </div>

    <div id="customData{$group_id}" class="crm-accordion-body">
      {include file="CRM/LCD/Custom/Form/CustomData.tpl" formEdit=true}
    </div>
    <!-- crm-accordion-body-->
  </div>
  {/foreach}
</div>

{literal}
<script type="text/javascript">
  (function($) {
    $('#contrib_custom_set').insertBefore('div#softCredit:first');
  })(CRM.$);
</script>
{/literal}

{literal}
<script type="text/javascript">
  (function($) {
    CRM.addmoreCustomData = function (type, subType, subName, cgCount, groupID, isMultiple, maxMultiple) {
      var dataUrl = CRM.url('civicrm/contrib/contribcustommulti', {type: type}),
        prevCount = 1,
        fname = '#contrib_custom_set',
        storage = {};

      if (subType) {
        dataUrl += '&subType=' + subType;
      }
      if (subName) {
        dataUrl += '&subName=' + subName;
        $('#contrib_custom_set' + subName).show();
      }
      else {
        $('#contrib_custom_set').show();
      }
      if (groupID) {
        dataUrl += '&groupID=' + groupID;
      }

      {/literal}
      {if $groupID}
        dataUrl += '&groupID=' + '{$groupID}';
      {/if}
      {if $entityID}
        dataUrl += '&entityID=' + '{$entityID}';
      {/if}
      {if $qfKey}
        dataUrl += '&qf=' + '{$qfKey}';
      {/if}
      {literal}
       
       
       dataUrl += '&cgcount=' + cgCount;
       
      if (!cgCount) {
        cgCount = 1;
      }
      else if (cgCount >= 1) {
        prevCount = cgCount;
        cgCount++;
      }

      


      if (isMultiple) {
        fname = '#hidden_custom_group_count_' + groupID + '_' + prevCount;
        if( maxMultiple === '' || maxMultiple > prevCount){
          $('div#customData'+ groupID +' table > tbody').append('<tr id="hidden_custom_group_count_'+ groupID +'_'+ cgCount + '"></tr>');
        }
        else {
          $("#add-more-link-" + prevCount).hide();
        }
        var div_id = $(".add-more-link-" + groupID + "-" + prevCount).attr('id');
        $("#add-more-link-" + prevCount + ' a').attr('onclick',"CRM.addmoreCustomData('"+ type +"','', '', "+ cgCount +", "+ groupID +", true, "+ maxMultiple+"); return false;");
        var updated_divID = 'add-more-link-'+cgCount;
        $(".add-more-link-" + groupID + "-" + prevCount).attr('id', updated_divID);
      }
      else if (subName && subName != 'null') {
        fname += subName;
      }

      return CRM.loadPage(dataUrl, {target: fname});
    };
  })(CRM.$);
</script>
{/literal}
