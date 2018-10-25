{include file="CRM/LCD/Custom/Form/CustomData.tpl"}
{literal}

  <script type="text/javascript" >
  CRM.$(function($) {
    // Handle delete of multi-record custom data
    $('#crm-container').on('click', '.crm-custom-value-del', function(e) {
      e.preventDefault();
      e.stopPropagation();
      e.stopImmediatePropagation();
      var $el = $(this),
        msg = '{/literal}{ts escape="js"}The record will be deleted immediately. This action cannot be undone.{/ts}{literal}';
      CRM.confirm({title: $el.attr('title'), message: msg})
        .on('crmConfirm:yes', function() {
          var url = CRM.url('civicrm/ajax/multicustomvalue');
          var request = $.post(url, $el.data('post'));
          CRM.status({success: '{/literal}{ts escape="js"}Record Deleted{/ts}{literal}'}, request);
          var addClass = '.add-more-link-' + $el.data('post').groupID;
        });
    });
  });

</script>
{/literal}