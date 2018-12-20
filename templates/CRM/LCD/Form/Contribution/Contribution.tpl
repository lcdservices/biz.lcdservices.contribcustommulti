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

    // Handle delete of multi-record custom data
    $('#customData')
      .off('.customValueDel')
      .on('click.customValueDel', '.crm-custom-value-del', function(e) {
        e.preventDefault();
        var $el = $(this),
          msg = '{/literal}{ts escape="js"}The record will be deleted immediately. This action cannot be undone.{/ts}{literal}';
        CRM.confirm({title: $el.attr('title'), message: msg})
          .on('crmConfirm:yes', function() {
            var url = CRM.url('civicrm/ajax/multicustomvalue');
            var request = $.post(url, $el.data('post'))
              .done(function() {
                $el.closest('div.crm-accordion-body').hide();
              });
            CRM.status({success: '{/literal}{ts escape="js"}Record Deleted{/ts}{literal}'}, request);
          });
      });
  });
</script>
{/literal}
