@section('modals')

@parent

<!-- Modal -->
<div class="modal fade" id="packitemModal" tabindex="-1" role="dialog" aria-labelledby="packitemModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-draggable modal-dialog-help" role="document">
    <div class="modal-content">
      <div class="modal-header alert-info">
        <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="packitemModalLabel">{{ l('Add Product to this Pack') }} :: [{{ $product->reference }}] {{ $product->name }}</h4>
      </div>
      <div class="modal-body" id="price_rule">
          {!! Form::hidden('product_id', $product->id, array('id' => 'product_id')) !!}

    
<div class="row">
</div>

    
<div class="row">

    <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('packitem_line_sort_order') ? 'has-error' : '' }}">
        {!! Form::label('line_sort_order', l('Position')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="right" 
                                    data-content="{{ l('Use multiples of 10. Use other values to interpolate.') }}">
                      <i class="fa fa-question-circle abi-help"></i>
               </a>
        {!! Form::text('packitem_line_sort_order', null, array('class' => 'form-control', 'id' => 'packitem_line_sort_order')) !!}
        {!! $errors->first('packitem_line_sort_order', '<span class="help-block">:message</span>') !!}
    </div>

    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('autopackitem_name', l('Product Name', 'pricerules')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Search by Name or Reference') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>

        {!! Form::text('autopackitem_name', null, array('id' => 'autopackitem_name', 'autocomplete' => 'off', 'class' => 'form-control', 'onclick' => 'this.select()')) !!}

        {!! Form::hidden('packitem_product_id', null, array('id' => 'packitem_product_id')) !!}

        {!! $errors->first('packitem_product_id', '<span class="help-block">:message</span>') !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('packitem_quantity', l('Quantity')) !!}
        {!! Form::text('packitem_quantity', 1, array('class' => 'form-control', 'onclick' => 'this.select()')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('packitem_measureunit_name', l('Unit')) !!}
        <div class="form-control" id="packitem_measureunit_name"></div>

        {!! Form::hidden('packitem_measureunit_id', null, array('id' => 'packitem_measureunit_id')) !!}
    </div>

</div>

<div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('packitem_notes') ? 'has-error' : '' }}">
                     {{ l('Notes', [], 'layouts') }}
                     {!! Form::textarea('packitem_notes', null, array('class' => 'form-control', 'id' => 'packitem_notes', 'rows' => '3')) !!}
                     {!! $errors->first('packitem_notes', '<span class="help-block">:message</span>') !!}
                  </div>
</div>



      </div>

      <div class="modal-footer">

               <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
               <button type="submit" class="btn btn-success" name="modal_packitemSubmit" id="modal_packitemSubmit">
                <i class="fa fa-thumbs-up"></i>
                &nbsp; {{l('Save', [], 'layouts')}}</button>

      </div>

    </div>
  </div>
</div>

@endsection


@section('scripts')    @parent


{{-- Auto Complete --}}


<script type="text/javascript">

    $(document).ready(function() {

        // To get focus;        
        $('#packitemModal').on('shown.bs.modal', function () {
            $("#autopackitem_name").focus();
        })

    });

</script> 


{{-- AutoComplete :: https://jqueryui.com/autocomplete/--}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>


  $(function() {


        $("#autopackitem_name").autocomplete({

            source : function(request, response) {
                $.get("{{ route('home.searchproduct') }}", {
                    term : request.term
                }, function (data) {
                    // Filter data here
                    var final_data = $(data).filter( function( i, n ) {

                        return n.product_type != "grouped";
                    });

                    // Better: $.grep( [{"name":"Lenovo Thinkpad 41A4298","website":"google"},{"name":"Lenovo Thinkpad 41A2222","website":"google"}], function( n, i ) {
                    //  return n.website==='google';
                    // });
                    // https://stackoverflow.com/questions/23720988/how-to-filter-json-data-in-javascript-or-jquery

                    response(final_data);
                });
            },

            minLength : 1,
            appendTo : "#packitemModal",

            select : function(key, value) {

                // getProductPackItemData( value.item.id ); // To retrieve measure unit ???

                var str = '[' + value.item.reference+'] ' + value.item.name;

                $("#autopackitem_name").val(str);
                $('#packitem_product_id').val(value.item.id);
                $('#packitem_measureunit_name').html(value.item.measureunit.name);
                $("#packitem_measureunit_id").val(value.item.measureunit.id);
                $('#packitem_quantity').focus();
                $('#packitem_quantity').select();

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.reference+'] ' + item.name + "</div>" )
                .appendTo( ul );
            };


  });


        // Function to do, if necessary
        function getProductPackItemData( packitem_product_id )
        {
            var token = "{{ csrf_token() }}";

            $.ajax({
                url: "{{ route('customerinvoices.ajax.customerLookup') }}",
                headers : {'X-CSRF-TOKEN' : token},
                method: 'GET',
                dataType: 'json',
                data: {
                    packitem_product_id: packitem_product_id
                },
                success: function (response) {
                    var str = '[' + response.identification+'] ' + response.name_fiscal;
                    var shipping_method_id;

                    $("#autopackitem_name").val(str);
                    $('#packitem_product_id').val(response.id);
                    if (response.sales_equalization > 0) {
                        $('#sales_equalization').show();
                    } else {
                        $('#sales_equalization').hide();
                    }

    //                $('#sequence_id').val(response.work_center_id);
                    $('#document_date_form').val('{{ abi_date_form_short( 'now' ) }}');
                    $('#delivery_date_form').val('');

                    if ( response.payment_method_id > 0 ) {
                      $('#payment_method_id').val(response.payment_method_id);
                    } else {
                      $('#payment_method_id').val({{ intval(\App\Configuration::get('DEF_CUSTOMER_PAYMENT_METHOD'))}});
                    }

                    $('#currency_id').val(response.currency_id);
                    $('#currency_conversion_rate').val(response.currency.conversion_rate);
                    $('#down_payment').val('0.0');

                    $('#invoicing_address_id').val(response.invoicing_address_id);

                    // https://www.youtube.com/watch?v=FHQh-IGT7KQ
                    $('#shipping_address_id').empty();

    //                $('#shipping_address_id').append('<option value="0" disable="true" selected="true">=== Select Address ===</option>');

                    $.each(response.addresses, function(index, element){
                      $('#shipping_address_id').append('<option value="'+ element.id +'">'+ element.alias +'</option>');
                    });

                    if ( response.shipping_address_id > 0 ) {
                      $('#shipping_address_id').val(response.shipping_address_id);
                    } else {
                      $('#shipping_address_id').val(response.invoicing_address_id);
                    }

                    $('#warehouse_id').val({{ intval(\App\Configuration::get('DEF_WAREHOUSE'))}});

                    shipping_method_id = response.shipping_method_id;
                    if (shipping_method_id == null) {
                        shipping_method_id = "{{ intval(\App\Configuration::get('DEF_SHIPPING_METHOD'))}}";
                    }
                    $('#shipping_method_id').val( shipping_method_id );

                    $('#sales_rep_id').val(response.sales_rep_id);

                    console.log(response);
                }
            });
        }


{{-- *************************************** --}}



          $("body").on('click', "#modal_packitemSubmit", function() {

            var url = "{{ route('products.packitems.store', $product->id) }}";
            var token = "{{ csrf_token() }}";
            var payload = {
                              line_sort_order : $('#packitem_line_sort_order').val(),

                              item_product_id : $('#packitem_product_id').val(),
                              quantity : $('#packitem_quantity').val(),

                              measure_unit_id : $('#packitem_measureunit_id').val(),
                              notes : $('#packitem_notes').val()
                          };

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function(){
                    
                    // getProductPriceRules();

                    $(function () {  $('[data-toggle="tooltip"]').tooltip()});
//                    $("[data-toggle=popover]").popover();

                    $('#packitemModal').modal('toggle');

            // $('#priceruleModal').modal({show: true});

                    showAlertDivWithDelay("#msg-packitem-success");

                    getProductPackItems();
                }
            });

        });

</script>

@endsection


@section('styles')    @parent

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

{{-- Auto Complete --}}

  {{-- !! HTML::style('assets/plugins/AutoComplete/styles.css') !! --}}

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">


<style>
  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }
  {{-- See: https://stackoverflow.com/questions/6762174/jquery-uis-autocomplete-not-display-well-z-index-issue
            https://stackoverflow.com/questions/7033420/jquery-date-picker-z-index-issue
    --}}
  .ui-datepicker{ z-index: 9999 !important;}
</style>

<style>

.modal-content {
  overflow:hidden;
}

/*
See: https://coreui.io/docs/components/buttons/ :: Brand buttons
*/
.btn-behance {
    color: #fff;
    background-color: #1769ff;
    border-color: #1769ff;
}

</style>

@endsection




