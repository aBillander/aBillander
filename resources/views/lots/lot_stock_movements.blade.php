@extends('layouts.master')

@section('title') {{ l('Lot Stock Movements') }} @parent @stop


@section('content')



<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
{{--
        <a href="{{ URL::to('stockmovements/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>
--}}

@if($lot->product->lot_tracking > 0)
        <button class="btn xbtn-sm alert-danger lot_stock_adjustment" type="button" style="margin-right: 32px;" title="{{l('Stock Adjustment')}}">
           <i class="fa fa-th-large"></i>
           &nbsp; {{l('Stock Adjustment')}}
        </button>
@endif

        <a href="{{ route('lot.stockmovements.export', [$lot->id] + Request::all()) }}" class="btn xbtn-sm btn-grey" style="margin-right: 32px;"  
                title="{{l('Export', [], 'layouts')}} ({{ l('Lot Stock Movements') }})"><i class="fa fa-file-excel-o"></i> {{l('Export Movements')}}</a>{{-- see: warehouses/indexProducts.blade.php --}}

        <a href="{{ URL::to('lots') }}" class="btn xbtn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Lots') }}</a>


    </div>
    <h2>
        {{ l('Lot Stock Movements') }} <span style="color: #cccccc;">::</span> <a href="{{ route( 'lots.edit', $lot->id ) }}" title="{{l('Go to', [], 'layouts')}}">{{ $lot->reference }}</a>   <span class="badge" style="background-color: #3a87ad;" title="{{ optional($lot->measureunit)->name }}"> &nbsp; {{ optional($lot->measureunit)->sign }} &nbsp; </span>
    </h2>
    <h3>
        <span class="lead well well-sm alert-warning"><a href="{{ URL::to('products/' . $lot->product->id . '/edit') }}#inventory" title="{{l('Go to', [], 'layouts')}}" target="_new">{{ $lot->product->reference }}</a></span>  {{ $lot->product->name }}
    </h3>     
</div>


<div class="container">
    <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">{{ l('Stock Summary') }} &nbsp;
                   <span class="badge" style="background-color: #3a87ad;" title="{{ optional($lot->measureunit)->name }}"> &nbsp; {{ optional($lot->measureunit)->sign }} &nbsp; </span>
               </h3>
              </div>
              <div class="panel-body">

<div id="div_stocksummary">
   <div class="table-responsive">

<table id="stocksummary" class="table table-hover">
    <thead>
        <tr>
            <th>{{l('Initial Stock')}}</th>
            <th>{{l('Current Stock')}}</th>
            <th>{{l('Allocated Stock')}}</th>
            <th>{{l('Available Stock')}}</th>
            <th> </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $lot->measureunit->quantityable($lot->quantity_initial) }}</td>
            <td>{{ $lot->measureunit->quantityable($lot->quantity) }}</td>
            <td>{{ $lot->measureunit->quantityable($lot->allocatedQuantity()) }}</td>
            <td>{{ $lot->measureunit->quantityable($lot->quantity - $lot->allocatedQuantity()) }}</td>
        </tr>

    </tbody>
</table>

   </div>
</div>
                  

              </div><!-- div class="panel-body" ENDS -->
            </div>
            </div>

    </div><!-- div class="row" ENDS -->
</div>


{{--
<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => 'stockmovements.index', 'method' => 'GET')) !!}

<!-- input type="hidden" value="0" name="search_status" id="search_status" -->
{!! Form::hidden('search_status', null, array('id' => 'search_status')) !!}

<div class="row">

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('date_from_form', l('Date from')) !!}
        {!! Form::text('date_from_form', null, array('id' => 'date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('date_to_form', l('Date to')) !!}
        {!! Form::text('date_to_form', null, array('id' => 'date_to_form', 'class' => 'form-control')) !!}
    </div>

<div class="form-group col-lg-1 col-md-1 col-sm-1">
    {!! Form::label('reference', l('Reference')) !!}
    {!! Form::text('reference', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('name', l('Product Name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('warehouse_id', l('Warehouse')) !!}
    {!! Form::select('warehouse_id', array('0' => l('All', [], 'layouts')) + $warehouseList, null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('stockmovements.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>

<div class="row">

    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('movement_type_id', l('Movement type')) !!}
        {!! Form::select('movement_type_id', array('' => l('-- All --', [], 'layouts')) + $movement_typeList, null, array('class' => 'form-control')) !!}
    </div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('document_reference', l('Document')) !!}
    {!! Form::text('document_reference', null, array('class' => 'form-control')) !!}
</div>

@if ( \App\Configuration::isTrue('ENABLE_LOTS') )

<div class="form-group col-lg-1 col-md-1 col-sm-1">
    { {-- Poor ma offset --} }
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('lot_reference', l('Lot Number', 'lots')) !!}
    {!! Form::text('lot_reference', null, array('class' => 'form-control')) !!}
</div>
@endif

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>
--}}


<div id="div_stockmovements">
   <div class="table-responsive">

@if ($stockmovements->count())
<table id="stockmovements" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
			<th>{{l('Date')}}</th>
			<th>{{l('Type')}}</th>
			<th>{{l('Warehouse')}}</th>
            <th class="text-right">{{l('Quantity')}}
              <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="{{ l('A positive value means stock increases.') }}">
                    <i class="fa fa-question-circle abi-help"></i>
              </a>
            </th>
            <th class="text-right">{{l('Stock after')}}</th>
			<th class="text-right">{{l('Document')}}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>

	@foreach ($stockmovements as $stockmovement)
		<tr>
			<td>{{ $stockmovement->id }}</td>
			<td>{{ abi_date_short( $stockmovement->date ) }}</td>
      <td>[{{ $stockmovement->movement_type_id }}] - 
           {{ \App\StockMovement::getTypeName($stockmovement->movement_type_id) }}
      </td>

			<td>{{ $stockmovement->warehouse->alias }}</td>
            <td class="text-right">{{ $stockmovement->as_quantityable( $stockmovement->quantity_after_movement - $stockmovement->quantity_before_movement ) }}</td>
            <td class="text-right">{{ $stockmovement->as_quantity( 'lot_quantity_after_movement' ) }}</td>
			<td class="text-right">

@if ( $route = $stockmovement->getStockmovementableDocumentRoute() )
{{-- optional(optional($stockmovement->stockmovementable)->document)->id --} }
        <!-- a href="{{ route($route.'.edit', ['0']).'?document_reference='.$stockmovement->document_reference }}" title="{{l('Open Document', [], 'layouts')}}" target="_new" -->  --}}
        <a href="{{ route($route.'.edit', [optional(optional($stockmovement->stockmovementable)->document)->id]) }}" title="{{l('Go to', [], 'layouts')}}" target="_new">{{ $stockmovement->document_reference }}</a>
    @if ( !optional(optional($stockmovement->stockmovementable)->document)->id ) 
        <i class="fa fa-exclamation-triangle btn-xs btn-danger" title="{{l('Document ID not found', 'layouts')}}"></i>
    @endif
@else
      {{ $stockmovement->document_reference }}
@endif

      </td>
            <td class="text-center">
                @if ($stockmovement->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $stockmovement->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>

            <td class="text-right">
                @if (  is_null($stockmovement->deleted_at))
                <!-- a class="btn btn-sm btn-success" href="{{ URL::to('stockmovements/' . $stockmovement->id) }}" title=" Ver "><i class="fa fa-eye"></i></a>               
                <a class="btn btn-sm btn-warning" href="{{ URL::to('stockmovements/' . $stockmovement->id . '/edit') }}" title=" Modificar "><i class="fa fa-pencil"></i></a -->
                <!-- a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('stockmovements/' . $stockmovement->id ) }}" 
                		data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Stock Movements') }} ::  ({{$stockmovement->id}}) {{ $stockmovement->date }}" 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a -->
                @else
                <a class="btn btn-warning" href="{{ URL::to('stockmovements/' . $stockmovement->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('stockmovements/' . $stockmovement->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
			</td>
		</tr>
	@endforeach

	</tbody>
</table>
{!! $stockmovements->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $stockmovements->total() ], 'layouts')}} </span></li></ul>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

@include('lots/lot_stock_allocations')

@include('lots/_modal_lot_stock_adjustment')

@endsection

@include('layouts/modal_delete')

@section('scripts') @parent 

<script type="text/javascript">

$(document).ready(function() {
   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });
});

</script>

{{-- Date Picker :: http://api.jqueryui.com/datepicker/ --}}

<!-- script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>
  $(function() {
    $( "#date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });
</script>

@endsection

@section('styles')    @parent

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

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

@endsection