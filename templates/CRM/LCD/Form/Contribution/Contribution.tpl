{* edit contribution form - render additional contribution multi record custom fields*}
{literal}
<script type="text/javascript">
  CRM.$(function($) {
    var cgc  = 1;
    var cgid = CRM.vars.contribCustomMulti.cgid;
    var cgCount = CRM.vars.contribCustomMulti.totalcgcount;
    $(document).ajaxStop(function () {
      if (cgCount > 1 && cgc < cgCount) {
        CRM.buildCustomData('Contribution','', '', cgc, cgid, true, '');
      }
      cgc++;
    });
  });
</script>
{/literal}
