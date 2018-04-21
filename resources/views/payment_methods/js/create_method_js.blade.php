
<script type="text/javascript">

$(document).ready(function() {
   $("#i_new_line").click(function() {
      $("#i_new_line").val("");
      add_line_to_method();
   });
});


function add_line_to_method()
{
  var nbrlines = parseInt($("#nbrlines").val());
  var pl = 0, plc = 0, lastline = 0;
  
   for(var i=0; i<nbrlines; i++)
   {
      if($("#line_"+i).length > 0)
      {         
         pl += parseFloat( $("#percentage_"+i).val() );
         lastline = i;
      }
   }

   plc = 100.0-pl;
   if (plc<0) plc = 0;

   vlc = parseFloat($("#slot_"+lastline).val()) + parseFloat($("#slot_0").val());

   $("#method_lines").append('<tr id="line_'+nbrlines+'">\n\
    \n\
        <td><input type="hidden" id="lineid_'+nbrlines+'" name="lineid_'+nbrlines+'" value="'+nbrlines+'"/>\n\
        <input type="text" name="slot_'+nbrlines+'" id="slot_'+nbrlines+'" value="'+vlc+'" '+
          'onclick="this.select()" class="form-control" autocomplete="off"/></td>\n\
    \n\
        <td><input type="text" name="percentage_'+nbrlines+'" id="percentage_'+nbrlines+'" value="'+plc+'" '+
          'onclick="this.select()" onkeyup="checkFields()" onchange="checkFields()" class="form-control text-right" autocomplete="off"/></td>\n\
    \n\
        <td><button class="btn btn-md btn-danger" type="button" onclick="$(\'#line_'+nbrlines+'\').remove();checkFields();" title="{{l('Delete', [], 'layouts')}}">\n\
         <i class="fa fa-trash"></i></button></td>\n\
      </tr>');
  
  $("#nbrlines").val(nbrlines+1);
  checkFields();
}

function checkFields() 
{
  var nbrlines = $("#nbrlines").val();
  var pl = 0, plc = 0, err = 0;

   for(var i=0; i<nbrlines; i++)
   {
      if($("#line_"+i).length > 0)
      {         
         plc = $("#percentage_"+i).val();
         if ( (plc>100) || (plc<=0) ) { err = 1; }
         pl += parseFloat( plc );
      }
   }

   if (err==1) 
   {
      $("#percentages_check").show();
      $("#percentages_check_sum").hide();
      return false;
   } else {
      $("#percentages_check").hide();
   }

   if (pl != 100.0) 
   {
      $("#percentages_check_sum").show();
      return false;
   } else {
      $("#percentages_check_sum").hide();
   }

   return true;
}

</script>
