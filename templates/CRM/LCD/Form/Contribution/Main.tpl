{if $contrib_multi_add_more_cgid}
  <span id="custom_group_{$contrib_multi_add_more_cgid}">
    <div id="custom_group_{$contrib_multi_add_more_cgid}_0"></div>
    {section name=cgcount start=1 loop=$contribMultiCgcount}
      <div id="custom_group_{$contrib_multi_add_more_cgid}_{$smarty.section.cgcount.index}" {if $smarty.section.cgcount.index eq 1}class="custom_group_{$contrib_multi_add_more_cgid}_0"{/if}></div>
    {/section}
    {*include custom data js file*}
    {include file="CRM/LCD/common/customData.tpl"}
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

    // load first set of contrib custom set row
    CRM.buildCustomData('Contribution','', '', 0, cgid, true, '', profileID);

    // hide contrib multi custom fields from profile, so they could be displayed in tabular format
    $.each(CRM.vars.contribCustomMulti.profileFields, function(index, element) {
      $('#editrow-'+element).hide();
    });

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
      // when there are more than 1 rows to be rendered - 
      // hide all add-more links, other than the last one.
      for (var cgc = 1; cgc < cgCount; cgc++) {
        $('#add-more-link-' + cgc).hide();
      }
      // on confirm page make fields read only
      if (formName == 'Confirm' || formName == 'ThankYou') {
        $(customWithMoreBlk).find("input,textarea,select").attr("disabled", "disabled");
      }
      //$(customWithMoreBlk + ' .crm-accordion-body').css('border','0');
    });
  });
</script>
{/literal}
{/if}
