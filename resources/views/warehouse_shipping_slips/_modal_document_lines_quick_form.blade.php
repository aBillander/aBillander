
@section('modals')    @parent

<div class="modal" id="modal_document_lines_quick_form" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-lg" xstyle="width: 99%; max-width: 1000px;">
      <div class="modal-content" id="document_lines_quick_form">




         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal_document_lines_quick_Label">{{ l('Quick Add Lines') }}</h4>
         </div>

         <div class="modal-body">


            {{-- csrf_field() --}}
            {!! Form::token() !!}

            {{ Form::hidden('row_count',             0, array('id' => 'row_count'         )) }}
            {{ Form::hidden('row_product_id',     null, array('id' => 'row_product_id'    )) }}
            {{ Form::hidden('row_combination_id', null, array('id' => 'row_combination_id')) }}



          <div class="row" id="product-search-autocomplete">

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{-- $errors->has('row_reference') ? 'has-error' : '' --}}">
                     {{ l('Reference') }}
                     {!! Form::text('row_reference', null, ['class' => 'form-control', 'id' => 'row_reference',  'onfocus' => 'this.blur()' ]) !!}
                     {{-- !! $errors->first('row_reference', '<span class="help-block">:message</span>') !! --}}
                  </div>

                  <div class="form-group col-lg-7 col-md-7 col-sm-7">
                     {{ l('Product Name') }}
                          <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Type a Product Name or Product Reference.') }}">
                                <i class="fa fa-question-circle abi-help"></i>
                          </a>
                     {!! Form::text('row_autoproduct_name', null, array('class' => 'form-control', 'id' => 'row_autoproduct_name', 'onclick' => 'this.select()')) !!}
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('row_quantity') ? 'has-error' : '' }}">
                     {{ l('Quantity') }}
                     {!! Form::text('row_quantity', null, array('class' => 'form-control', 'id' => 'row_quantity', 'onclick' => 'this.select()', 'onfocus' => 'this.select()', 'autocomplete' => 'off')) !!}
                     {!! $errors->first('row_quantity', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-1 col-md-1 col-sm-1">
                       <br />
                       <button id="add_new_row" name="add_new_row" class="btn btn-md btn-success" type="button" title="{{l('Add', [], 'layouts')}}">
                     <i class="fa fa-plus"></i></button>
                  </div>

          </div>

          <div class="row" style="margin-top: 20px;">

                  <div class="form-group col-lg-2 col-md-2 col-sm-2">
                  </div>

                  <div class="form-group col-lg-8 col-md-8 col-sm-8">




    <div id="div_document_lines_quick_form" style="display:none;">
       <div class="table-responsive">

    <table id="quick_document_lines" class="table table-hover">
        <thead>
            <tr>
               <th class="text-left">{{ l('Reference') }}
               <th class="text-left">{{ l('Product Name') }}</th>
               <th class="text-right">{{ l('Quantity') }}</th>
               <th class="text-right"> </th>
            </tr>
        </thead>
        <tbody>

{{--
            <tr>
                <td></td>
                <td></td>
                <td class="text-right"></td>
                <td class="text-right">
                      

                  <button class="btn btn-md btn-danger delete-quick-document-line" type="button" title="{{l('Delete', [], 'layouts')}}">
                   <i class="fa fa-trash"></i></button>

                </td>
            </tr>



        <tr><td colspan="9">
        <div class="alert alert-warning alert-block">
            <i class="fa fa-warning"></i>
            {{l('No records found', [], 'layouts')}}
        </div>
        </td></tr>
    
--}}    

        </tbody>
    </table>


       </div>
    </div>




                  </div>








          </div>

         </div><!-- div class="modal-body" ENDS -->

           <div class="modal-footer">

               <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
               <button type="submit" class="btn btn-magick" name="modal_document_lines_quick_formSubmit" id="modal_document_lines_quick_formSubmit">
                <i class="fa fa-superpowers"></i>
                &nbsp; {{l('Update', [], 'layouts')}}</button>

           </div>





      </div>
   </div>
</div>

@endsection


@section('scripts')    @parent

<script type="text/javascript">

$(document).ready(function() {

          $(document).on('click', '.lines_quick_form', function(evnt) {

 /*          
               var panel = $("#order_line_form");
               var url = "{ { route('customeror derline.productform', ['create']) } }";

               panel.addClass('loading');

               $.get(url, {}, function(result){
                     panel.html(result);
                     panel.removeClass('loading');

                     $("[data-toggle=popover]").popover();
                     // sortableCustomerOrderLines();
               }, 'html').done( function() { 

                    var selector = "#line_autoproduct_name";
                    var next = $('#next_line_sort_order').val();

                    $('#line_type').val('product');

                    auto_product_line( selector );

                    $('#line_id').val('');
//                    $('#line_type').val('');
                    $('#line_sort_order').val(next);
                    $('#line_quantity').val(1);
                    $('#line_measure_unit_id').val(0);

                    $('#line_price').val(0.0);

                    $('#line_discount_percent').val(0.0);
                    $('#line_discount_amount_tax_incl').val(0.0);
                    $('#line_discount_amount_tax_excl').val(0.0);

                    if ($('#sales_equalization').val()>0) {
                        $('input:radio[name=line_is_sales_equalization]').val([1]);
                        $('#line_sales_equalization').show();
                    }


                    calculate_line_product();

                    $('#line_sales_rep_id').val( $('#sales_rep_id').val() );
                    $('#line_commission_percent').val( 0.0 );   // Use default

                    $('#line_notes').val('');

                    $("#line_autoproduct_name").val('');
                    $("#line_reference").val('');
                    $('#line_product_id').val('');
                    $('#line_combination_id').val('');
*/


                    auto_product_row();

                    $('#modal_document_lines_quick_form').modal({show: true});

                    $('#quick_document_lines tbody').html('');
                    $("#div_document_lines_quick_form").hide();

                    reset_row_form();

 //               });

              return false;
          });



        $("body").on('click', "#modal_document_lines_quick_formSubmit", function() {

              quick_formSubmit();

        });



    $('#add_new_row').on('click', function() {

            add_new_row();

     });


     $(document).on('click', '.delete-quick-document-line', function() {
         
          $(this).closest("tr").remove();

          if( $('#quick_document_lines tbody').children('tr').length == 0 )
          {
                $("#div_document_lines_quick_form").hide("slow");
          }

      });


     // Reset counter
     $('#row_count').val(0);


});


function reset_row_form()
{
    $('#row_product_id').val('');
    $('#row_combination_id').val('');

    $('#row_reference').val('');
    $('#row_autoproduct_name').val('');
    $('#row_quantity').val('1');

    $('#row_autoproduct_name').focus();

}


function add_new_row()
{

           if ( ($('#row_product_id').val().trim().length == 0) || ($('#row_quantity').val().trim().length == 0) )
           {
                reset_row_form();

                return ;
           } 

              var row = '';
              var data = '';
              var button_delete = '<button class="btn btn-md btn-danger delete-quick-document-line" type="button" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash"></i></button>';
              //var count = +1.0+parseInt($('#row_count').val());
              var count = +1.0+ +$('#row_count').val(); // Won't work: +1.0++$('#row_count').val()  <= taken as increment operator!!!

              data += '<td>'+$('#row_reference').val()+'<input type="hidden" name="product_id_values['+count+']" value="'+$('#row_product_id').val()+'"></td>';
              data += '<td>'+$('#row_autoproduct_name').val()+'<input type="hidden" name="combination_id_values['+count+']" value="'+$('#row_combination_id').val()+'"></td>';
              data += '<td>'+$('#row_quantity').val()+'<input type="hidden" name="quantity_values['+count+']" value="'+$('#row_quantity').val()+'"></td>';
              data += '<td class="text-right">'+button_delete+'</td>';

              row = '<tr>'+data+'</tr>';

              $("#quick_document_lines").append(row);

              $('#row_count').val(count);

              $("#div_document_lines_quick_form").show("slow");

              reset_row_form();

}


function quick_formSubmit()
{
            var count = $('#quick_document_lines tbody').children('tr').length;

           // Nothing to submit!
           if ( count == 0 )
           {
                return ;
           } 


            var document_id = {{ $document->id }};
            var url = "{{ route('warehouseshippingslips.quickaddlines', [$document->id]) }}";
            var token = "{{ csrf_token() }}";

            var p_ids = $('#quick_document_lines').find('input[name^="product_id_values"]').serialize();
            var c_ids = $('#quick_document_lines').find('input[name^="combination_id_values"]').serialize();
            var qts = $('#quick_document_lines').find('input[name^="quantity_values"]').serialize();

            var payload = { 
                              document_id : {{ $document->id }},
                              product_id_values : p_ids,
                              combination_id_values : c_ids,
                              quantity_values : qts
                          };



//    pload = pload + "&customer_id="+$("#customer_id").val();
//    pload = pload + "&currency_id="+$("#currency_id").val()+"&conversion_rate="+$("#currency_conversion_rate").val();
//    pload = pload + "&_token="+$('[name="_token"]').val();

  //          alert(payload);

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function(result){

                    $(function () {  $('[data-toggle="tooltip"]').tooltip()});
//                    $("[data-toggle=popover]").popover();

                    $('#modal_document_lines_quick_form').modal('toggle');

                    console.log(result);

                    showAlertDivWithDelay("#msg-success");

                    loadDocumentlines();
                }
            });

}
          

          $(document).on('keydown','#row_autoproduct_name', function(e){
        
            if (e.keyCode == 13) {
                 // console.log("put function call here");
                 e.preventDefault();

                 if ( $('#row_autoproduct_name').val().trim().length == 0 )
                 {
                      // Submit form
                      quick_formSubmit();
                 } else {
                      // Continue with the form 
                      $('#row_quantity').focus();
                 }
                 
                 return false;
            }

          });
          

          $(document).on('keydown','#row_quantity', function(e){
        
            if (e.keyCode == 13) {
                 // console.log("put function call here");
                 e.preventDefault();

                 if ( $('#row_quantity').val().trim().length == 0 )
                 {
                      // Do nothing so far
                 } else {
                      // Submit data 
                      add_new_row();
                 }
                 
                 return false;
            }

          });


        function auto_product_row( selector = "#row_autoproduct_name" ) {

            $( selector ).autocomplete({
                source : "{{ route('warehouseshippingslips.searchproduct') }}",    // ?customer_id="+$('#customer_id').val()+"&currency_id="+$('#currency_id').val(),
                minLength : 1,
                appendTo : "#modal_document_lines_quick_form",

                select : function(key, value) {
                    var str = '[' + value.item.reference+'] ' + value.item.name;

                    $('#row_reference').val(value.item.reference);
                    $("#row_autoproduct_name").val(value.item.name);
                    $('#row_product_id').val(value.item.id);
                    $('#row_combination_id').val(0);

                    // getProductData( $('#line_product_id').val(), $('#line_combination_id').val() );

                    $('#row_quantity').focus();

                    return false;
                }
            }).data('ui-autocomplete')._renderItem = function( ul, item ) {
                  return $( "<li></li>" )
                    .append( '<div>[' + item.id + '] [' + item.reference + '] ' + item.name + "</div>" )
                    .appendTo( ul );
                };
        }


</script>

@endsection
