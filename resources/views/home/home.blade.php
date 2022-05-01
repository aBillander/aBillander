@extends('layouts.master')

@section('title') {{ l('Welcome') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right hide" style="padding-top: 4px;">
        <a href="{{ URL::to('jennifer/home') }}" class="btn btn-sm btn-success" 
            title="{{l('Jennifer', [], 'layouts')}}"><i class="fa fa-user-secret"></i> {{l('Jennifer', [], 'layouts')}}</a>
    </div>
    <h2>
         
        <a class="btn btn-sm alert-warning" href="#" onclick="return false;" title=" _ninette_ ">
            <i class="fa fa-key"></i>
        </a> 

        <span style="color: #cccccc;">/</span> 

        <a href="#" onclick="return false;">
            {{ Auth::user()->getFullName() }}
        </a>

         <span style="color: #cccccc;">/</span> {{ l('Home') }}
    </h2>
</div>



<div class="container xhide">
    <div class="row">
        <div xclass="col-lg-3 col-md-6">

            <div class="col-lg-3 col-md-6">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Buscar Cliente</h3>
              </div>
              <div class="panel-body">

                  <div class="row">

                     <div class="form-group col-lg-12">
                        {!! Form::text('autocustomer_name', null, array('class' => 'form-control', 'id' => 'autocustomer_name')) !!}

                        {!! Form::hidden('customer_id', null, array('id' => 'customer_id')) !!}
                     </div>

                  </div>
                  
              </div>
            </div>
            </div>

            <div class="col-lg-3 col-md-6">
            <div class="panel panel-success">
              <div class="panel-heading">
                <h3 class="panel-title">Buscar Producto</h3>
              </div>
              <div class="panel-body">

                  <div class="row">

                     <div class="form-group col-lg-12">
                        {!! Form::text('autoproduct_name', null, array('class' => 'form-control', 'id' => 'autoproduct_name')) !!}

                        {!! Form::hidden('product_id', null, array('id' => 'product_id')) !!}
                     </div>

                  </div>

              </div>
            </div>
            </div>

            <div class="col-lg-3 col-md-6">
            </div>

            <div class="col-lg-3 col-md-6">
            <div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Copia de Seguridad</h3>
              </div>
              <div class="panel-body">

                  <div class="row">

                     <div class="form-group col-lg-12">
                        Última: 
                        <span class="xlead well xwell-sm alert-info" style="padding: 3px; border-radius: 3px;">
@if ($last_backup)
                            {{ abi_date_full( \Carbon\Carbon::createFromTimestamp($last_backup->getMTime()) ) }}
@else
                            -- nunca --
@endif                        </span>

                            <a class="btn btn-xs alert-success" href="{{ URL::to('dbbackups') }}" title="{{l('Go to', [], 'layouts')}}"><i class="fa fa-eye"></i></a>
                        <br/>
                        Tamaño: 
                        <span class="text-info">
@if ($last_backup)
                            &nbsp; {{ abi_formatBytes( $last_backup->getSize() ) }}
@else
                            &nbsp; --
@endif                        </span>
                     </div>

                  </div>

              </div>
            </div>
            </div>
{{-- 
            <div class="col-lg-3 col-md-6">
            <div class="panel panel-danger">
              <div class="panel-heading">
                <h3 class="panel-title">Panel danger</h3>
              </div>
              <div class="panel-body">
                Panel content
              </div>
            </div>
            </div>

            <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Panel primary</h3>
              </div>
              <div class="panel-body">
                Panel content
              </div>
            </div>
            </div>

            <div class="col-lg-3 col-md-6">
            <div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Panel warning</h3>
              </div>
              <div class="panel-body">
                Panel content
              </div>
            </div>
            </div>
--}}
        </div>
    </div>


    <div class="row">

            <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Pedidos</h3>
              </div>
              <div class="panel-body">

                  <div class="row">

                     <div class="form-group col-lg-12 text-center">
                        
                        <a href="{{ route('customerorders.for.today') }}" class="btn xbtn-sm btn-success" 
                                title=" Pedidos de hoy "><i class="fa fa-shopping-bag"></i> Pedidos de hoy</a>

                     </div>

                  </div>

                  <div class="row">

                     <div class="form-group col-lg-12">

                        <label for="autocustomerorder_name" class="control-label">Buscar</label>
                        {!! Form::text('autocustomerorder_name', null, array('class' => 'form-control', 'id' => 'autocustomerorder_name')) !!}

                        {!! Form::hidden('customerorder_id', null, array('id' => 'customerorder_id')) !!}

                     </div>

                  </div>
                  
              </div>
            </div>
            </div>

            <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Albaranes</h3>
              </div>
              <div class="panel-body">

                  <div class="row">

                     <div class="form-group col-lg-12 text-center">
                        
                        <a href="{{ route('customershippingslips.for.today') }}" class="btn xbtn-sm btn-success" 
                                title=" Albaranes de hoy "><i class="fa fa-truck"></i> Albaranes de hoy</a>

                     </div>

                  </div>

                  <div class="row">

                     <div class="form-group col-lg-12">

                        <label for="autocustomershippingslip_name" class="control-label">Buscar</label>
                        {!! Form::text('autocustomershippingslip_name', null, array('class' => 'form-control', 'id' => 'autocustomershippingslip_name')) !!}

                        {!! Form::hidden('customershippingslip_id', null, array('id' => 'customershippingslip_id')) !!}

                     </div>

                  </div>
                  
              </div>
            </div>
            </div>

            <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Facturas</h3>
              </div>
              <div class="panel-body">

                  <!-- div class="row">

                     <div class="form-group col-lg-12 text-center">
                        
                        <a href="{ { route('customerinvoices.for.today') } }" class="btn xbtn-sm btn-success" 
                                title=" Albaranes de hoy "><i class="fa fa-truck"></i> Albaranes de hoy</a>

                     </div>

                  </div -->

                  <div class="row">

                     <div class="form-group col-lg-12">

                        <label for="autocustomerinvoice_name" class="control-label">Buscar</label>
                        {!! Form::text('autocustomerinvoice_name', null, array('class' => 'form-control', 'id' => 'autocustomerinvoice_name')) !!}

                        {!! Form::hidden('customerinvoice_id', null, array('id' => 'customerinvoice_id')) !!}

                     </div>

                  </div>
                  
              </div>
            </div>
            </div>

    </div><!-- div class="row" ENDS -->
</div>



{{-- ***************************************************** --}}


<div class="container-fluid hidden">
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




{{--
<div class="jumbotron">

  <!-- h1>Jumbotron</h1>
  <p>This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
  <p><a class="btn btn-primary btn-lg">Learn more</a></p -->
<img src="{{URL::to('/assets/theme/images/Dashboard.jpg')}}" 
                    title=""
                    class="center-block"
                    style=" border: 1px solid #dddddd;
                            border-radius: 18px;
                            -moz-border-radius: 18px;
                            -khtml-border-radius: 18px;
                            -webkit-border-radius: 18px;">
 HTML::image('img/picture.jpg', 'a picture', array('class' => 'thumb'))
</div>
 --}}

@endsection


@section('styles')    @parent

{{-- Auto Complete --}}

  {{-- !! HTML::style('assets/plugins/AutoComplete/styles.css') !! --}}

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"></script -->

<style>

  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }


/* See: http://fellowtuts.com/twitter-bootstrap/bootstrap-popover-and-tooltip-not-working-with-ajax-content/ 
.modal .popover, .modal .tooltip {
    z-index:100000000;
}
 */
</style>

@endsection


@section('scripts')    @parent


{{-- Auto Complete --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script type="text/javascript">

    $(document).ready(function() {

        $("#autocustomer_name").val('');
        $("#autoproduct_name").val('');
        $("#autocustomerorder_name").val('');
        $("#autocustomershippingslip_name").val('');
        $("#autocustomerinvoice_name").val('');

        // To get focus;
        $("#autocustomer_name").focus();

        $("#autocustomer_name").autocomplete({
            source : "{{ route('home.searchcustomer') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                customer_id = value.item.id;

                redirect = "{{ URL::to('customers') }}/"+customer_id+"/edit";

                window.location.href = redirect;

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.identification+'] ' + item.name_regular + "</div>" )
                .appendTo( ul );
            };



        $("#autoproduct_name").autocomplete({
            source : "{{ route('home.searchproduct') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                product_id = value.item.id;

                redirect = "{{ URL::to('products') }}/"+product_id+"/edit";

                window.location.href = redirect;

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.reference+'] ' + item.name + "</div>" )
                .appendTo( ul );
            };



        $("#autocustomerorder_name").autocomplete({
            source : "{{ route('home.searchcustomerorder') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                customerorder_id = value.item.id;

                redirect = "{{ URL::to('customerorders') }}/"+customerorder_id+"/edit";

                window.location.href = redirect;

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>'+item.id+' / ' + (item.document_reference || "{{ l('Draft', 'layouts') }}") +' - ' + item.document_date.split(" ")[0] + ' - '+(item.reference_external || '')+'</div>' )
                .appendTo( ul );
            };



        $("#autocustomershippingslip_name").autocomplete({
            source : "{{ route('home.searchcustomershippingslip') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                customershippingslip_id = value.item.id;

                redirect = "{{ URL::to('customershippingslips') }}/"+customershippingslip_id+"/edit";

                window.location.href = redirect;

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>'+item.id+' / ' + (item.document_reference || "{{ l('Draft', 'layouts') }}") +' - ' + item.document_date.split(" ")[0] + ' - '+(item.reference_external || '')+'</div>' )
                .appendTo( ul );
            };



        $("#autocustomerinvoice_name").autocomplete({
            source : "{{ route('home.searchcustomerinvoice') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                customerinvoice_id = value.item.id;

                redirect = "{{ URL::to('customerinvoices') }}/"+customerinvoice_id+"/edit";

                window.location.href = redirect;

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>'+item.id+' / ' + (item.document_reference || "{{ l('Draft', 'layouts') }}") +' - ' + item.document_date.split(" ")[0] + ' - '+(item.reference_external || '')+'</div>' )
                .appendTo( ul );
            };

    });

</script> 

@endsection