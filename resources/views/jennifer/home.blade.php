@extends('layouts.master')

@section('title') {{ l('Welcome') }} @parent @endsection


@section('content')

<div class="page-header">
    <h2>
         
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
            {{ Auth::user()->getFullName() }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>

         <!-- a href="{{ URL::to('auth/logout') }}">{{ Auth::user()->getFullName() }}</a --> <span style="color: #cccccc;">/</span> {{ l('Reports', [], 'layouts') }} 
         <span style="color: #cccccc;">/</span> {{ l('Accounting', [], 'layouts') }}
    </h2>
</div>


<div class="container">
    <div class="row">

            <div class="col-lg-6 col-md-6">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-money"></i> Facturas</h3>
              </div>


{!! Form::open(array('route' => 'jennifer.reports.invoices', 'class' => 'form')) !!}

              <div class="panel-body">

                  <div class="row">

    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('invoices_date_from_form', 'Fecha desde') !!}
        {!! Form::text('invoices_date_from_form', null, array('id' => 'invoices_date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('invoices_date_to_form', 'Fecha hasta') !!}
        {!! Form::text('invoices_date_to_form', null, array('id' => 'invoices_date_to_form', 'class' => 'form-control')) !!}
    </div>

                  <!-- /div>

                  <div class="row" -->

    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('invoices_id_from', l('From ID', 'layouts')) !!}
        {!! Form::text('invoices_id_from', null, array('id' => 'invoices_id_from', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('invoices_id_to', l('To ID', 'layouts')) !!}
        {!! Form::text('invoices_id_to', null, array('id' => 'invoices_id_to', 'class' => 'form-control')) !!}
    </div>

                  </div>

                  <div class="row">

    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('invoices_report_format', 'Formato') !!}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-container="body" 
                                      data-content="{{ l('El formato "Amplio" utiliza más columnas para mostrar la información. El formato "Compacto" muestra lo mismo, pero usando más filas y menos columnas.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
        {!! Form::select('invoices_report_format', $invoices_report_formatList, 'loose', array('class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('invoices_customer_id') ? 'has-error' : '' }}">
       {!! Form::label('invoices_autocustomer_name', l('Customer', 'customerorders')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Search by Name or Identification (VAT Number).', 'customerorders') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
        {!! Form::text('invoices_autocustomer_name', null, array('class' => 'form-control', 'id' => 'invoices_autocustomer_name')) !!}
        {!! $errors->first('invoices_customer_id', '<span class="help-block">:message</span>') !!}

        {!! Form::hidden('invoices_customer_id', null, array('id' => 'invoices_customer_id')) !!}
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
                     &nbsp; Ver Listado
                  </button>
               </div>

{!! Form::close() !!}

            </div>

            </div>



    <!-- /div>< ! -- div class="row" ENDS - - >
    <div class="row" -->

            <div class="col-lg-3 col-md-3 col-sm-3">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bank"></i> Remesas</h3>
              </div>


{!! Form::open(array('route' => 'jennifer.reports.bankorders', 'class' => 'form')) !!}

              <div class="panel-body">

                  <div class="row">

    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('bank_order_from', 'Remesa desde') !!}
        {!! Form::text('bank_order_from', null, array('id' => 'bank_order_from', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('bank_order_to', 'Remesa hasta') !!}
        {!! Form::text('bank_order_to', null, array('id' => 'bank_order_to', 'class' => 'form-control')) !!}
    </div>

                  </div>
                  <div class="row">

    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('bank_order_date_from_form', 'Fecha desde') !!}
        {!! Form::text('bank_order_date_from_form', null, array('id' => 'bank_order_date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('bank_order_date_to_form', 'Fecha hasta') !!}
        {!! Form::text('bank_order_date_to_form', null, array('id' => 'bank_order_date_to_form', 'class' => 'form-control')) !!}
    </div>

                  </div>

              </div>

               <div class="panel-footer text-right">
                  <button class="btn btn-success" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-file-text-o"></i>
                     &nbsp; Ver Listado
                  </button>

            </div>

{!! Form::close() !!}

            </div>
            </div>



    <!-- /div>< ! -- div class="row" ENDS - - >
    <div class="row" -->

            <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-th"></i> Inventario</h3>
              </div>


{!! Form::open(array('route' => 'jennifer.reports.inventory', 'class' => 'form')) !!}

              <div class="panel-body">

                  <div class="row">

    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('inventory_date_to_form', 'Inventario a Fecha') !!}
        {!! Form::text('inventory_date_to_form', null, array('id' => 'inventory_date_to_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('valuation_method') ? 'has-error' : '' }}">
        {!! Form::label('valuation_method', 'Método de Valoración') !!}
        {!! Form::select('valuation_method', $valuation_methodList, null, array('class' => 'form-control')) !!}
       {!! $errors->first('valuation_method', '<span class="help-block">:message</span>') !!}
    </div>

                  </div>

              </div>

               <div class="panel-footer text-right">
                  <button class="btn btn-success" type="submit" onclick="this.disabled=false;this.form.submit();">
                     <i class="fa fa-file-text-o"></i>
                     &nbsp; Ver Listado
                  </button>

            </div>

{!! Form::close() !!}

            </div>
            </div>



    <!-- /div>< ! -- div class="row" ENDS - - >
    <div class="row" -->



    </div><!-- div class="row" ENDS -->





    <div class="row">

            <div class="col-lg-3 col-md-6">
            <div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-euro"></i> Saldo de Clientes</h3>
              </div>


{!! Form::open(array('route' => 'jennifer.reports.customersbalance', 'class' => 'form')) !!}

              <div class="panel-body">

                  <div class="row">

    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('balance_date_to_form', 'Saldo a Fecha') !!}
        {!! Form::text('balance_date_to_form', null, array('id' => 'balance_date_to_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-6 col-md-6 col-sm-64 {{ $errors->has('balance_customer_id') ? 'has-error' : '' }}">
       {!! Form::label('balance_autocustomer_name', l('Customer', 'customerorders')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Search by Name or Identification (VAT Number).', 'customerorders') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
        {!! Form::text('balance_autocustomer_name', null, array('class' => 'form-control', 'id' => 'balance_autocustomer_name')) !!}
        {!! $errors->first('balance_customer_id', '<span class="help-block">:message</span>') !!}

        {!! Form::hidden('balance_customer_id', null, array('id' => 'balance_customer_id')) !!}
    </div>

                  </div>

              </div>

               <div class="panel-footer text-right">
                  <button class="btn btn-success" type="submit" onclick="this.disabled=false;this.form.submit();">
                     <i class="fa fa-file-text-o"></i>
                     &nbsp; Ver Listado
                  </button>

            </div>

{!! Form::close() !!}

            </div>
            </div>

            <div class="col-lg-3 col-md-6">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-files-o"></i> Acumulados Modelo 347</h3>
              </div>


{!! Form::open(array('route' => 'jennifer.reports.index347', 'class' => 'form')) !!}

              <div class="panel-body">

                  <div class="row">

    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('mod347_year', 'Año') !!}
        {!! Form::text('mod347_year', \Carbon\Carbon::now()->year, array('id' => 'mod347_year', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('mod347_clave', 'Clave') !!}
        {!! Form::select('mod347_clave', $mod347_claveList, 'B', array('class' => 'form-control')) !!}
    </div>

                  </div>

              </div>

               <div class="panel-footer text-right">
                  <button class="btn btn-success" type="submit" onclick="this.disabled=false;this.form.submit();">
                     <i class="fa fa-file-text-o"></i>
                     &nbsp; Ver Listado
                  </button>

            </div>

{!! Form::close() !!}

            </div>
            </div>


    </div><!-- div class="row" ENDS -->

</div>






{{-- ********************************************************** --}}




{{-- ***************************************************** --}}


<div class="container-fluid">
   <div class="row">

      <div class="col-lg-2 col-md-2 col-sm-2">
         <!-- div class="list-group">
            <a id="b_main_data" href="#" class="list-group-item active">
               <i class="fa fa-asterisk"></i>
               &nbsp; {{ l('Updates') }}
            </a>
         </div -->
      </div>

      
      <div class="col-lg-9 col-md-9 col-sm-10">
      <div class="jumbotron" style="background: no-repeat url('{{URL::to('/assets/theme/images/Dashboard.jpg')}}'); background-size: 100% auto;min-height: 200px; margin-top: 40px;">


      </div>
      </div>

   </div>
</div>

@endsection


@section('styles')    @parent

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>

@endsection


@section('scripts')    @parent


    <script type="text/javascript">

        $(document).ready(function() {

          $('#invoices_date_from_form').val( '' );
          $('#invoices_date_to_form'  ).val( '' );
          $("#invoices_autocustomer_name").val('');
          $("#invoices_customer_id").val('');

          $('#bank_order_date_from_form').val( '' );
          $('#bank_order_date_to_form'  ).val( '' );
          $('#bank_order_from').val( '' );
          $('#bank_order_to'  ).val( '' );

          $('#inventory_date_to_form').val( '' );
          
          $('#balance_date_to_form'  ).val( '' );
          $("#balance_autocustomer_name").val('');
          $("#balance_customer_id").val('');


        $("#invoices_autocustomer_name").autocomplete({
//            source : "{{ route('customers.ajax.nameLookup') }}",
            source : "{{ route('customerorders.ajax.customerLookup') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {
                // var str = '[' + value.item.identification+'] ' + value.item.name_regular + ' [' + value.item.reference_external +']';
                var str = value.item.name_regular + ' [' + value.item.reference_external +']';

                // ( value.item.id );

                $("#invoices_autocustomer_name").val(str);
                $('#invoices_customer_id').val(value.item.id );

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + (item.identification ? item.identification : '--')+'] ' + item.name_regular + ' [' + (item.reference_external ? item.reference_external : '--') +']' + "</div>" )
                .appendTo( ul );
            };


        $("#balance_autocustomer_name").autocomplete({
//            source : "{{ route('customers.ajax.nameLookup') }}",
            source : "{{ route('customerorders.ajax.customerLookup') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {
                // var str = '[' + value.item.identification+'] ' + value.item.name_regular + ' [' + value.item.reference_external +']';
                var str = value.item.name_regular + ' [' + value.item.reference_external +']';

                // ( value.item.id );

                $("#balance_autocustomer_name").val(str);
                $('#balance_customer_id').val(value.item.id );

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + (item.identification ? item.identification : '--')+'] ' + item.name_regular + ' [' + (item.reference_external ? item.reference_external : '--') +']' + "</div>" )
                .appendTo( ul );
            };


        function getCustomerData( customer_id, drawee_bank_id = 0 )
        {
            var token = "{{ csrf_token() }}";

            $.ajax({
                url: "{{ route('customerorders.ajax.customerLookup') }}",
                headers : {'X-CSRF-TOKEN' : token},
                method: 'GET',
                dataType: 'json',
                data: {
                    customer_id: customer_id
                },
                success: function (response) {
                    var str = '[' + response.identification+'] ' + response.name_fiscal + ' [' + response.reference_external +']';
                    var shipping_method_id;

                    $("#document_autocustomer_name").val(str);
                    $('#customer_id').val(response.id);
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
                      $('#payment_method_id').val({{ intval(AbiConfiguration::get('DEF_CUSTOMER_PAYMENT_METHOD'))}});
                    }

                    $('#currency_id').val(response.currency_id);
                    $('#currency_conversion_rate').val(response.currency.conversion_rate);
                    $('#down_payment').val('0.0');

                    $('#invoicing_address_id').val(response.invoicing_address_id);
{{--
                    // https://www.youtube.com/watch?v=FHQh-IGT7KQ
                    $('#drawee_bank_id').empty();

    //                $('#drawee_bank_id').append('<option value="0" disable="true" selected="true">=== Select Address ===</option>');

                    $.each(response.addresses, function(index, element){
                      $('#drawee_bank_id').append('<option value="'+ element.id +'">'+ element.document_number +'</option>');
                    });

                    if ( response.drawee_bank_id > 0 ) {
                      $('#drawee_bank_id').val(response.drawee_bank_id);
                    } else {
                      $('#drawee_bank_id').val(response.invoicing_address_id);
                    }

                    if ( drawee_bank_id > 0 )
                      $("#drawee_bank_id").val( drawee_bank_id );

                    $('#warehouse_id').val({{ intval(AbiConfiguration::get('DEF_WAREHOUSE'))}});

                    shipping_method_id = response.shipping_method_id;
                    if (shipping_method_id == null) {
                        shipping_method_id = "{{ intval(AbiConfiguration::get('DEF_SHIPPING_METHOD'))}}";
                    }
                    $('#shipping_method_id').val( shipping_method_id );
--}}
                    $('#sales_rep_id').val(response.sales_rep_id);

                    console.log(response);
                }
            });
        }



        });

    </script> 



{{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.AbiContext::getContext()->language->iso_code.'.js'); !!}

<script>

  $(function() {
    $( "#invoices_date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#invoices_date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#bank_order_date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#bank_order_date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#inventory_date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#balance_date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });
  
</script>

@endsection
