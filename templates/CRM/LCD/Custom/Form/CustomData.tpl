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
{* Custom Data form*}
{assign var="length" value=$cd_edit.fields|@count}
  {if $cd_edit.help_pre}
    <div class="messages help">{$cd_edit.help_pre}</div>
  {/if}
  <table class="form-layout-compressed">
  {assign var="x" value=1}
    {foreach from=$cd_edit.fields item=element key=field_id}
      <tr class="custom_field-row custom_{$x}-row">
        {foreach from=$element item=field_element key=field_element_id}
          {include file="CRM/LCD/Custom/Form/CustomField.tpl"}
        {/foreach}
       </tr>
    {assign var=x value=$x+1}
    {/foreach}
    <tr id="hidden_custom_group_count_{$group_id}_{$x}"></tr>
  </table>
  {if $cd_edit.is_multiple and ( ( $cd_edit.max_multiple eq '' )  or ( $cd_edit.max_multiple > 0 and $cd_edit.max_multiple >= $x ) ) }
    <div id="add-more-link-{$x}" class="add-more-link-{$group_id} add-more-link-{$group_id}-{$x}">
      <a href="#" class="crm-hover-button" onclick="CRM.addmoreCustomData('{$cd_edit.extends}',{if $cd_edit.subtype}'{$cd_edit.subtype}'{else}'{$cd_edit.extends_entity_column_id}'{/if}, '', {$x}, {$group_id}, true ); return false;">
        <i class="crm-i fa-plus-circle"></i>
        {ts 1=$cd_edit.title}Another %1 record{/ts}
      </a>
    </div>
  {/if}
  <div class="spacer"></div>
  {if $cd_edit.help_post}
    <div class="messages help">{$cd_edit.help_post}</div>
  {/if}

{include file="CRM/Form/attachmentjs.tpl"}
