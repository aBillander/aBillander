
<div class="container">
    <div class="row">

            <div xclass="col-lg-3 col-md-6">
            <div class="panel panel-info">
              <div class="panel-heading" xstyle="color: #ffffff;
background-color: #772953;
border-color: #772953;">
                <h3 class="panel-title"><i class="fa fa-truck"></i> Transportistas</h3>
              </div>


{!! Form::open(array('route' => 'helferin.reports.carriers', 'id' => 'carriers_report_form', 'class' => 'form')) !!}

              <div class="panel-body">

                  <div class="row">

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('carriers_date_from_form', 'Fecha desde') !!}
        {!! Form::text('carriers_date_from_form', null, array('id' => 'carriers_date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('carriers_date_to_form', 'Fecha hasta') !!}
        {!! Form::text('carriers_date_to_form', null, array('id' => 'carriers_date_to_form', 'class' => 'form-control')) !!}
    </div>

     <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('carriers_autocarrier_name', 'Transportista') !!}
        {!! Form::text('carriers_autocarrier_name', null, array('class' => 'form-control', 'id' => 'carriers_autocarrier_name')) !!}

        {!! Form::hidden('carriers_carrier_id', null, array('id' => 'carriers_carrier_id')) !!}
     </div>

                  </div>
{{--
                  <div class="row">

                     <div class="form-group col-lg-12 text-center" xstyle="padding-top: 22px">
                          {!! Form::submit('Ver Listado', array('class' => 'btn btn-success')) !!}
                    </div>

                  </div>
--}}

              </div>

               <div class="panel-footer text-right">
                  <button class="btn btn-success" type="submit" onclick="this.disabled=false;this.form.submit();">
                     <i class="fa fa-file-text-o"></i>
                     &nbsp; {!! l('Export', [], 'layouts') !!}
                  </button>
               </div>

{!! Form::close() !!}

            </div>

            </div>



    <!-- /div>< ! -- div class="row" ENDS - - >
    <div class="row" -->





    <!-- /div>< ! -- div class="row" ENDS - - >
    <div class="row" -->





    </div><!-- div class="row" ENDS -->

</div>


@section('scripts')    @parent


    <script type="text/javascript">

        $(document).ready(function() {

          $('#carriers_date_from_form').val( '' );
          $('#carriers_date_to_form'  ).val( '' );


        $("#carriers_autocustomer_name").val('');

        // To get focus;
        // $("#autocarrier_name").focus();

        $("#carriers_autocarrier_name").autocomplete({
            source : "{{ route('carriers.ajax.carrierLookup') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                getCarrierData( value.item.id );

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.alias+'] ' + item.name + "</div>" )
                .appendTo( ul );
            };


        $("#carriers_report_form").on("submit", function(){
           //Code: 
           if ( $("#carriers_autocarrier_name").val().trim() == '' )
              $('#carriers_carrier_id').val('');

           return true;
         });


        });


        function getCarrierData( carrier_id )
        {
            var token = "{{ csrf_token() }}";

            $.ajax({
                url: "{{ route('carriers.ajax.carrierLookup') }}",
                headers : {'X-CSRF-TOKEN' : token},
                method: 'GET',
                dataType: 'json',
                data: {
                    carrier_id: carrier_id
                },
                success: function (response) {
                    var str = '[' + response.alias+'] ' + response.name;
                    var shipping_method_id;

                    $("#carriers_autocarrier_name").val(str);
                    $('#carriers_carrier_id').val(response.id);
/*
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
*/
                    console.log(response);
                }
            });
        }



    </script> 


{{-- Date Picker --}}

<script>

  $(function() {
    $( "#carriers_date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#carriers_date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

</script>

@endsection
