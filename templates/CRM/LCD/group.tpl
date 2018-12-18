{* FILE: CRM/LCD/Custom/Form/group.tpl to remove the display style for contribution style*}

{literal}
<script type="text/javascript">
  CRM.$(function($) {
    var tabOption;
    var tabWithTableOption;
    $('input#is_multiple').change(showStyle);
    function showStyle() {
      if($( "#extends_0" ).val() === 'Contribution') {
        if($("#is_multiple").is(':checked')) {
          $("select#style").val('Inline');
          $("tr#style_row").show();
          $("tr#multiple_row").show();

          tabOption = $("select#style option[value='Tab']").detach();
          tabWithTableOption = $("select#style option[value='Tab with table']").detach();
        } else {
          $("select#style").append(tabWithTableOption);
          $("select#style").append(tabOption);
          $("tr#style_row").hide();
          $("tr#multiple_row").hide();
        }
      }
    }
  });
</script>
{/literal}
