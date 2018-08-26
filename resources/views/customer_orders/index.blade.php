@extends('layouts.master')

@section('title') {{ l('Customer Orders') }} @parent @stop


@section('content')

@if ( \App\Configuration::isTrue('ENABLE_FSOL_CONNECTOR') )

<div class="alert alert-block alert-info" style="display:none">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>Info: </strong>
            {{ \App\Configuration::get('FSOL_CBDCFG') }} 
</div>

@if ( $anyClient > 0 )
<div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>Aviso: </strong>
            Hay <b>{{$anyClient}}</b> ficheros en la Carpeta de descarga de <b>Clientes</b>. Debe importarlos a FactuSOL, o borrarlos. 

                <a style="color: #e95420; text-decoration: none;" class="btn btn-sm btn-grey" href="{{ route('fsxorders.deletecustomerfiles') }}" title="{{l('Eliminar Ficheros')}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i> Eliminar Ficheros</a>
</div>
@endif

@if ( $anyOrder > 0 )
<div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>Aviso: </strong>
            Hay <b>{{$anyOrder}}</b> ficheros en la Carpeta de descarga de <b>Pedidos</b>. Debe importarlos a FactuSOL, o borrarlos. 

                <a style="color: #e95420; text-decoration: none;" class="btn btn-sm btn-grey" href="{{ route('fsxorders.deleteorderfiles') }}" title="{{l('Eliminar Ficheros')}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i> Eliminar Ficheros</a>
</div>
@endif
@endif

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <a href="{{ URL::to('customerorders/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

        <a href="{{ route('chart.customerorders.monthly') }}" class="btn btn-sm btn-warning" 
                title="{{l('Reports', [], 'layouts')}}"><i class="fa fa-bar-chart-o"></i> {{l('Reports', [], 'layouts')}}</a>

        <a class="btn btn-sm btn-grey" xstyle="margin-right: 152px" href="{{ route('fsxconfigurationkeys.index') }}" title="{{l('Configuration', [], 'layouts')}} {{l('Enlace FactuSOL', 'layouts')}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i> {{l('Configuration', [], 'layouts')}}</a> 

    </div>
    <h2>
        {{ l('Customer Orders') }}
    </h2>        
</div>

<div id="div_customer_orders">

{!! Form::open( ['route' => ['productionsheet.addorders', '0'], 'method' => 'POST', 'id' => 'form-import'] ) !!}
{{-- !! csrf_field() !! --}}

   <div class="table-responsive">

@if ($customer_orders->count())
<table id="customer_orders" class="table table-hover">
    <thead>
        <tr>
            <th class="text-center">{!! Form::checkbox('', null, false, ['id' => 'ckbCheckAll']) !!}</th>
            <th class="text-left">{{ l('Order #') }}</th>
            <th class="text-left">{{ l('Date') }}</th>
            <th class="text-left">{{ l('Production Date')}}</th>
            <th class="text-left">{{ l('Export to FS')}}</th>
            <!-- th class="text-left">{{ l('Delivery Date') }}</th -->
            <th class="text-left">{{ l('Customer') }}</th>
            <th class="text-left">{{ l('Payment Method') }}</th>
            <th class="text-left">{{ l('Created via') }}</th>
            <th class="text-right"">{{ l('Total') }}</th>
            <th> </th>
        </tr>
    </thead>
    <tbody id="order_lines">
        @foreach ($customer_orders as $order)
        <tr>
            @if ( $order->production_sheet_id )
              <td> </td>
            @else
              <td class="text-center warning">{!! Form::checkbox('corders[]', $order->id, false, ['class' => 'case checkbox']) !!}</td>
            @endif
            <td>{{ $order->id }} / 
                @if ($order->document_id>0)
                {{ $order->document_reference }}
                @else
                <span class="label label-default" title="{{ l('Draft') }}">{{ l('Draft') }}</span>
                @endif</td>
            <td>{{ abi_date_short($order->document_date) }}</td>
            <!-- td>{{ abi_date_short($order->delivery_date) }}</td -->

              <td>
              
        @if ($order->production_sheet_id)
                        {{ abi_date_form_short($order->productionsheet->due_date) }} 
                        <a class="btn btn-xs btn-warning" href="{{ URL::to('productionsheets/' . $order->production_sheet_id) }}" title="{{l('Go to Production Sheet')}}"><i class="fa fa-external-link"></i></a>
        @endif
              </td>

              <td>
              
        @if ($order->export_date)
                        {{ abi_date_short($order->export_date) }}
        @endif
              </td>
            
            <td><a class="" href="{{ URL::to('customers/' .$order->customer->id . '/edit') }}" title="{{ l('Show Customer') }}" target="_new">
            	{{ $order->customer->name_fiscal }}
            	</a>
            </td>
            <td>{{ $order->paymentmethod->name ?? '' }}
            </td>
            <td>{{ $order->created_via }}
            </td>
            <td class="text-right">{{ $order->as_money_amount('total_tax_incl') }}</td>
            <td class="text-right">
                <!--
                <a class="btn btn-sm btn-blue"    href="{{ URL::to('customerorders/' . $order->id . '/mail') }}" title="{{l('Send by eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>               
                <a class="btn btn-sm btn-success" href="{{ URL::to('customerorders/' . $order->id) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a>               
                -->
@if (       config('app.url') =='http://abimfg.laextranatural.es' 
         || config('app.url') =='http://abimfg-test.laextranatural.es')

@else
                <a class="btn btn-sm btn-info" href="{{ URL::to('customerorders/' . $order->id . '/pdf') }}" title="{{l('PDF Export', [], 'layouts')}}"><i class="fa fa-file-pdf-o"></i></a>
@endif

                @if ($order->export_date)
                <a class="btn btn-sm btn-default" href="javascript:void(0);" title="{{$order->export_date}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i></a>
                @else
                <a class="btn btn-sm btn-grey" href="{{ URL::route('fsxorders.export', [$order->id] ) }}" title="{{l('Exportar a FactuSOL')}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i></a>
                @endif

                <a class="btn btn-sm btn-success" href="{{ URL::to('customerorders/' . $order->id . '/duplicate') }}" title="{{l('Copy Order')}}"><i class="fa fa-copy"></i></a>

                <a class="btn btn-sm btn-warning" href="{{ URL::to('customerorders/' . $order->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                @if( $order->editable )
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('customerorders/' . $order->id ) }}" 
                    data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Customer Orders') }} :: ({{$order->id}}) {{ $order->document_reference }} " 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

   </div><!-- div class="table-responsive" ENDS -->

{{ $customer_orders->appends( Request::all() )->render() }}
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $customer_orders->total() ], 'layouts')}} </span></li></ul>


<div name="search_filter" id="search_filter">
<div class="row" style="padding: 0 20px">

    <div class="col-md-2 xcol-md-offset-3">
    </div>





    <div class="col-md-4 xcol-md-offset-3" xstyle="display:none">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Add Orders to Production Sheet') }}</h3></div>
            <div class="panel-body">

@if ( count( $availableProductionSheetList ) )
<div class="row">
<!-- div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('after', l('Date from')) !!}
    {!! Form::text('after', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('before', l('Date to')) !!}
    {!! Form::text('before', null, array('class' => 'form-control')) !!}
</div -->
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('production_sheet_id', l('Production Sheet')) !!} {{-- \Carbon\Carbon::now() --}}
    {!! Form::select('production_sheet_id', $availableProductionSheetList, null, array('class' => 'form-control', 'id' => 'production_sheet_id')) !!}
</div>

<div class="form-group col-lg-6 col-md-6 col-sm-6" style="padding-top: 22px">
{!! Form::submit(l('Add'), array('class' => 'btn btn-success', 'onclick' => "this.disabled=true;this.form.submit();")) !!}
</div>

</div>

@else

<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No active Production Sheet found.')}}
</div>

@endif

            </div>
        </div>
    </div>

    <div class="col-md-6 xcol-md-offset-1" xstyle="display:none">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Add Orders to NEW Production Sheet') }}</h3></div>
            <div class="panel-body">

<div class="row">

         <div class="col-lg-3 col-md-3 col-sm-3 {{ $errors->has('due_date') ? 'has-error' : '' }}">
            <div class="form-group">
               {{ l('Date') }}
               {!! Form::text('due_date', null, array('class' => 'form-control', 'id' => 'due_date', 'autocomplete' => 'off')) !!}
               {!! $errors->first('due_date', '<span class="help-block">:message</span>') !!}
            </div>
         </div>

         <div class="form-group col-lg-5 col-md-5 col-sm-5 {{ $errors->has('name') ? 'has-error' : '' }}">
            {{ l('Name') }}
            {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
            {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
         </div>

<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
<input type="hidden" id="production_sheet_mode" name="production_sheet_mode" value="existing" />
{!! Form::submit(l('Add'), array('class' => 'btn btn-success', 'onclick' => "this.disabled=true;$('#production_sheet_mode').val('new');this.form.submit();")) !!}


</div>

</div>
<div class="row">

         <div class="form-group col-lg-8 col-md-8 col-sm-8 {{ $errors->has('notes') ? 'has-error' : '' }}">
            {{ l('Notes', [], 'layouts') }}
            {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '2')) !!}
            {{ $errors->first('notes', '<span class="help-block">:message</span>') }}
         </div>

</div>

            </div>
        </div>
    </div>

</div>
</div>


{!! Form::close() !!}

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

</div><!-- div id="div_customer_orders" ENDS -->

@endsection

@include('layouts/modal_delete')


{{-- *************************************** --}}


@section('scripts') @parent 

<script>

// check box selection -->
// See: http://www.dotnetcurry.com/jquery/1272/select-deselect-multiple-checkbox-using-jquery

$(function () {
    var $tblChkBox = $("#order_lines input:checkbox");
    $("#ckbCheckAll").on("click", function () {
        $($tblChkBox).prop('checked', $(this).prop('checked'));
    });
});

$("#order_lines").on("change", function () {
    if (!$(this).prop("checked")) {
        $("#ckbCheckAll").prop("checked", false);
    }
});

// check box selection ENDS -->


    $(document).ready(function () {

          // Select first element
          $('#production_sheet_id option:first-child').attr("selected", "selected");
    });

</script>

@endsection


{{-- *************************************** --}}


@section('scripts') @parent 

{{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>

  $(function() {
    $( "#due_date" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });
  
</script>

@endsection




@section('styles') @parent

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>

@endsection
