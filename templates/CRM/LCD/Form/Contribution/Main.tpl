{if $contrib_multi_add_more_cgid}
  <div id="custom_group_{$contrib_multi_add_more_cgid}_1"></div>
  {*include custom data js file*}
  {include file="CRM/common/customData.tpl"}
  <div id="add-more-link-1" class="add-more-link-{$contrib_multi_add_more_cgid} add-more-link-{$contrib_multi_add_more_cgid}-1">
    <a href="#" class="crm-hover-button" onclick="CRM.buildCustomData('Contribution','', '', 1, {$contrib_multi_add_more_cgid}, true, ''); return false;">
      <i class="crm-i fa-plus-circle"></i>
      {ts 1=$contrib_multi_add_more_cg_title}Another %1 record{/ts}
    </a>
  </div>
{literal}
<script type="text/javascript">
  CRM.$(function($) {
    console.log('main.tpl custom');
    var addMoreBlk  = {/literal}'#custom_group_{$contrib_multi_add_more_cgid}_1'{literal};
    var addMoreLink = {/literal}'.add-more-link-{$contrib_multi_add_more_cgid}-1'{literal};
    var profileBlk  = {/literal}'.{$contrib_multi_add_more_div}'{literal};
    $(addMoreBlk).appendTo(profileBlk);
    $(addMoreLink).appendTo(profileBlk);
  });
</script>
{/literal}
{/if}
