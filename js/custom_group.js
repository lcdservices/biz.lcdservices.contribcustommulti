// js to remove the display style for custom set extending contributions
CRM.$(function($) {
  var tabOption;
  var tabWithTableOption;
  showStyle();
  $('input#is_multiple').change(showStyle);
  function showStyle() {
    if($( "#extends_0" ).val() === 'Contribution') {
      $("select#style").val('Inline');//always inline for Contribution
      if($("#is_multiple").is(':checked') || $("input:hidden#is_multiple").val() == '1') {
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
