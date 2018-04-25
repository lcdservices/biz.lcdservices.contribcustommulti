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
{foreach from=$_element item=field_element key=field_element_id}
{assign var="element_name" value=$field_element.element_name}
  <td class="label">{$form.$element_name.label}{if $element.help_post}{help id=$element.id file="CRM/Custom/Form/CustomField.hlp" title=$element.label}{/if}</td>
    <td class="html-adjust">
        {$form.$element_name.html}&nbsp;
        {if $element.data_type eq 'File'}
            {if $element.element_value.data}
              <div class="crm-attachment-wrapper crm-entity" id="file_{$element_name}">
                <span class="html-adjust"><br />
                    &nbsp;{ts}Attached File{/ts}: &nbsp;
                    {if $element.element_value.displayURL}
                        <a href="{$element.element_value.displayURL}" class='crm-image-popup crm-attachment'>
                          <img src="{$element.element_value.displayURL}"
                               height = "{$element.element_value.imageThumbHeight}"
                               width="{$element.element_value.imageThumbWidth}">
                        </a>
                    {else}
                        <a class="crm-attachment" href="{$element.element_value.fileURL}">{$element.element_value.fileName}</a>
                    {/if}
                    {if $element.element_value.deleteURL}
                           <a href="#" class="crm-hover-button delete-attachment" data-filename="{$element.element_value.fileName}" data-args="{$element.element_value.deleteURLArgs}" title="{ts}Delete File{/ts}"><span class="icon delete-icon"></span></a>
                    {/if}
                </span>
              </div>
            {/if}
        {elseif $element.html_type eq 'Autocomplete-Select'}
          {if $element.data_type eq 'ContactReference'}
            {include file="CRM/Custom/Form/ContactReference.tpl"}
          {/if}
        {/if}
    </td>
{/foreach}
