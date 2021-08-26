
@section('modals')    @parent

<div class="modal" id="modal_document_line" tabindex="-1" role="dialog">
   <div class="modal-dialog xmodal-lg" style="width: 99%; max-width: 1000px;">
      <div class="modal-content" id="document_line_form">




      </div>
   </div>
</div>

@endsection


@section('scripts')    @parent

<script type="text/javascript">


// $(document).ready(function() {

// });


function calculate_line_product() {

   return ;
}


function calculate_service_price()
{
   calculate_line_product();

   return ;
}


function calculate_comment_price()
{
   return ;
}



function get_tax_percent_by_id(tax_id, se = 0) 
{
   // http://stackoverflow.com/questions/18910939/how-to-get-json-key-and-value-in-javascript
   // var taxes = $.parseJSON( '{ { json_encode( $taxpercentList ) } }' );

/*   var taxes = { ! ! json_encode( $customer->sales_equalization
                                  ? $taxeqpercentList 
                                  : $taxpercentList 
                              ) ! ! } ;
*/
//   var se;
   var taxes   = '' ;
   var retaxes = '' ;

   // Skip sales equalization
   // se = 0;

   if (typeof taxes[tax_id] == "undefined")   // or if (taxes[tax_id] === undefined) {
   {
        // variable is undefined
        alert('Tax code ['+tax_id+'] not found!');

        return false;
   }

   if (se>0)
        return parseFloat(retaxes[tax_id]);
   else
        return parseFloat(taxes[tax_id]);
}


function nbr_decimals(number)
{
    // See: https://stackoverflow.com/questions/10454518/javascript-how-to-retrieve-the-number-of-decimals-of-a-string-number
    if($.type(number) !== "string") 
        value = number.toString();
    else 
        value = number;

    if ( value.indexOf('.') >= 0 ) return value.split('.')[1].length;

    return 0;
}

</script>

@endsection
