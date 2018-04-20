{* FILE: CRM/LCD/Custom/Form/group.tpl to remove the display style for contribution style*}

{literal}
<script type="text/javascript">
  CRM.$(function($) {
    var tabOption;
    var inlineOption;
    var tabWithTableOption;
    var used_for = $( "#extends_0" ).val();
    showStyle();
    $('input#is_multiple').change(showStyle);
    function showStyle(onFormLoad) {
      if( ( $("#is_multiple").is(':checked') || $('#is_multiple').val() != '')  && used_for === 'Contribution') {
        if (onFormLoad !== true) {
          $("select#style").append(inlineOption);
          $("select#style").val('Inline');
          tabOption = $("select#style option[value='Tab']").detach();
          tabWithTableOption = $("select#style option[value='Tab with table']").detach();
        }
      }
      
    }
  });
</script>
{/literal}