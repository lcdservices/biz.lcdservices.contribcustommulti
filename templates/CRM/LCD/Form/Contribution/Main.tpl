{if $contrib_multi_add_more_cgid}
  <span id="custom_group_{$contrib_multi_add_more_cgid}">
    {section name=cgcount start=1 loop=$contribMultiCgcount+1}
      <div id="custom_group_{$contrib_multi_add_more_cgid}_{$smarty.section.cgcount.index}"></div>
    {/section}
    {*include custom data js file*}
    {include file="CRM/LCD/common/customData.tpl"}
    {if $contribMultiCgcount lte 1}
    <div id="add-more-link-1" class="add-more-link-{$contrib_multi_add_more_cgid} add-more-link-{$contrib_multi_add_more_cgid}-1">
      <a href="#" class="crm-hover-button" onclick="CRM.buildCustomData('Contribution','', '', 1, {$contrib_multi_add_more_cgid}, true, '', {$profile_id}); return false;">
        <i class="crm-i fa-plus-circle"></i>
        {ts 1=$contrib_multi_add_more_cg_title}Another %1 record{/ts}
      </a>
    </div>
    {/if}
  </span>
{literal}
<script type="text/javascript">
  CRM.$(function($) {
    var cgid = {/literal}'{$contrib_multi_add_more_cgid}'{literal};
    var formName = {/literal}'{$form.formName}'{literal};
    var profileID  = {/literal}'{$profile_id}'{literal};
    var profileBlk  = {/literal}'.{$contrib_multi_add_more_div}'{literal};
    var customWithMoreBlk  = {/literal}'#custom_group_{$contrib_multi_add_more_cgid}'{literal};
    $(customWithMoreBlk).appendTo(profileBlk);

    var cgCount = CRM.vars.contribCustomMulti.cgcount;
    if (cgCount > 1) {
      for (var cgc = 1; cgc < cgCount; cgc++) {
        CRM.buildCustomData('Contribution','', '', cgc, cgid, true, '', profileID);
      }
    }
    var defaults = CRM.vars.contribCustomMulti.defaults;
    $(document).ajaxStop(function () {
      $.each(defaults, function(ele, value){
        if ($('#' + ele).length) {
          $('#' + ele).val(value);
          // for selects trigger select2 as well
          $("select[id='"+ele+"']").val(value).trigger('change');
        } else {
          // elements with different id but same name, e.g radio
          $("input:radio[name='"+ ele +"'][id^='CIVICRM_QFID_"+ value   +"']").attr('checked', true);
        }
      });
      // for confirm hide all add-more links
      cgCount = (formName == 'Confirm') ? ++cgCount : cgCount; 
      // hide all add-more links
      for (var cgc = 1; cgc < cgCount; cgc++) {
        $('#add-more-link-' + cgc).hide();
      }
      // on confirm page make fields read only
      if (formName == 'Confirm') {
        $(customWithMoreBlk).find("input,textarea,select").attr("disabled", "disabled");
      }
      $(customWithMoreBlk + ' .crm-accordion-body').css('border','0');
    });
  });
</script>
{/literal}
{/if}
