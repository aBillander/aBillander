@extends('layouts.master')

@section('title') {{ l('Lots') }} @parent @endsection


@section('content')



<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('lots/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

        <a href="{{ route('lots.export', Request::all()) }}" class="btn btn-sm btn-grey" 
                title="{{l('Export', [], 'layouts')}}"><i class="fa fa-file-excel-o"></i> {{l('Export', [], 'layouts')}}</a>

    </div>
    <h2>
        {{ l('Lots') }}
    </h2>        
</div>



<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => 'lots.index', 'method' => 'GET')) !!}

<!-- input type="hidden" value="0" name="search_status" id="search_status" -->
{!! Form::hidden('search_status', null, array('id' => 'search_status')) !!}

<div class="row">

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('reference', l('Lot Number')) !!}
    {!! Form::text('reference', null, array('class' => 'form-control')) !!}
</div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('date_from_form', l('Date from', 'layouts')) !!}
                       <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                              data-content="{!! l('Manufacture Date') !!}">
                          <i class="fa fa-question-circle abi-help"></i>
                       </a>
        {!! Form::text('date_from_form', null, array('id' => 'date_from_form', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group col-lg-2 col-md-2 col-sm-2">
        {!! Form::label('date_to_form', l('Date to', 'layouts')) !!}
        {!! Form::text('date_to_form', null, array('id' => 'date_to_form', 'class' => 'form-control')) !!}
    </div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('product_reference', l('Product Reference')) !!}
    {!! Form::text('product_reference', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('product_name', l('Product Name')) !!}
    {!! Form::text('product_name', null, array('class' => 'form-control')) !!}
</div>
{{--
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('warehouse_id', l('Warehouse')) !!}
    {!! Form::select('warehouse_id', array('0' => l('All', [], 'layouts')) + $warehouseList, null, array('class' => 'form-control')) !!}
</div>
--}}

<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('lots.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>

<div class="row">
         
         <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('warehouse_id') ? 'has-error' : '' }}">
            {!! Form::label('warehouse_id', l('Warehouse')) !!}
            {!! Form::select('warehouse_id', ['0' => l('-- All --', [], 'layouts')] + $warehouseList, null, array('class' => 'form-control', 'id' => 'warehouse_id')) !!}
            {!! $errors->first('warehouse_id', '<span class="help-block">:message</span>') !!}
         </div>

        <div class="form-group col-lg-2 col-md-2 col-sm-2">
            {!! Form::label('quantity', l('Quantity')) !!}

            <div class="input-group select-group">

                {!! Form::select('quantity_prefix', $quantity_prefixList, 'eq', array('class' => 'form-control input-group-addon', 'id' => 'quantity_prefix')) !!}
            
                {!! Form::text('quantity', null, array('class' => 'form-control')) !!}

            </div>

        </div>

{{-- Very difficult to filter by allocated quantity...

        <div class="form-group col-lg-2 col-md-2 col-sm-2">
            {!! Form::label('quantity', l('Allocated Quantity')) !!}

            <div class="input-group select-group">

                {!! Form::select('allocated_quantity_prefix', $quantity_prefixList, 'eq', array('class' => 'form-control input-group-addon', 'id' => 'quantity_prefix')) !!}
            
                {!! Form::text('allocated_quantity', null, array('class' => 'form-control')) !!}

            </div>

        </div>

        <div class="form-group col-lg-2 col-md-2 col-sm-2">
            {!! Form::label('allocated_quantity', l('Allocated Quantity')) !!}
            {!! Form::text('allocated_quantity', null, array('class' => 'form-control')) !!}
        </div>
--}}

{{--
    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('movement_type_id', l('Movement type')) !!}
        {!! Form::select('movement_type_id', array('' => l('-- All --', [], 'layouts')) + $movement_typeList, null, array('class' => 'form-control')) !!}
    </div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('document_reference', l('Document')) !!}
    {!! Form::text('document_reference', null, array('class' => 'form-control')) !!}
</div>
--}}

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>



<div id="div_lots">
   <div class="table-responsive">

@if ($lots->count())
<table id="lots" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{l('Lot Number')}}</th>
            <th>{{l('Warehouse')}}</th>
            <th>{{l('Product')}}</th>
            <th class="text-right">{{l('Quantity')}}
              <!-- a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="{{ l('A positive value means stock increases.') }}">
                    <i class="fa fa-question-circle abi-help"></i>
              </a -->
            </th>
            <th class="text-right">{{l('Allocated Quantity')}}</th>
            <th>{{l('Measure Unit')}}</th>
            <th>{{l('Weight')}} (<span class="text-success">{{ optional($weight_unit)->sign }}</span>)</th>
            <th>{{l('Manufacture Date')}}</th>
            <th>{{l('Expiry Date')}}</th>
            <th class="text-center">{{ l('Blocked', [], 'layouts') }}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
            <th class="text-center">{{l('Attachments')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>

	@foreach ($lots as $lot)
		<tr>
      <td>{{ $lot->id }}</td>
      <td>{{ $lot->reference }}</td>
      <td title="{{ $lot->warehouse->alias_name ?? '-' }}">{{ $lot->warehouse->alias ?? '-' }}</td>
      <td>[<a href="{{ URL::to('products/' . $lot->product->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_new">{{ $lot->product->reference }}</a>] {{ $lot->product->name }}
{{--
                    @if ( $lot->combination_id > 0 )
                        {{ $lot->combination->reference }}
                    @else
                        {{ $lot->product->reference }}
                    @endif
--}}
            </td>
      <td class="text-right">{{ $lot->as_quantity('quantity') }}</td>
      <td class="text-right 

@if( ($lot_allocated_qty = $lot->allocatedQuantity()) > 0.0 )
    @if( $lot_allocated_qty < $lot->quantity )
        alert-warning
    @elseif ( $lot_allocated_qty > $lot->quantity )
        btn-info
    @else
        alert-danger
    @endif
@endif
      ">{{ $lot->as_quantityable( $lot_allocated_qty ) }}</td>
      <td>{{ optional($lot->measureunit)->sign }}</td>

      <td class="text-center">{{ $lot->getWeight() }}</td>

      <td>{{ abi_date_short( $lot->manufactured_at ) }}</td>
      <td>{{ abi_date_short( $lot->expiry_at ) }}</td>

            <td class="text-center">@if ($lot->blocked) <i class="fa fa-lock" style="color: #df382c;"></i> @else <i class="fa fa-unlock" style="color: #38b44a;"></i> @endif</td>

            <td class="text-center">
                @if ($lot->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $lot->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>


            <td class="text-center">
                @if ($lot->attachments->count()>0)
                      <a class="btn btn-xs btn-blue" href="{{ URL::to('lots/' . $lot->id . '/edit') }}"  title="{{l('Show', [], 'layouts')}}"><i class="fa fa-copy"></i></a>
                @endif</td>

            <td class="text-right button-pad">
                @if (  is_null($lot->deleted_at))
                <a class="btn btn-sm alert-info" href="{{ route( 'lot.stockmovements', [$lot->id] ) }}" title="{{ l('Lot Stock Movements') }}" target="_stockmovements"><i class="fa fa-outdent"></i></a>
                       
                <a class="btn btn-sm btn-info" href="{{ route( 'stockmovements.index', ['search_status' => 1, 'lot_id' => $lot->id, 'lot_reference' => $lot->reference] ) }}" title="{{ l('Stock Movements') }}" target="_stockmovements"><i class="fa fa-outdent"></i></a>
                       
                <a class="btn btn-sm btn-warning " href="{{ URL::to('lots/' . $lot->id . '/edit') }}"  title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

                <a class=" hide btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('lots/' . $lot->id ) }}" 
                		data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Lots') }} ::  ({{$lot->id}}) {{ $lot->reference }}" 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('lots/' . $lot->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('lots/' . $lot->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
			</td>
		</tr>
	@endforeach

	</tbody>
</table>
{!! $lots->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $lots->total() ], 'layouts')}} </span></li></ul>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

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
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.AbiContext::getContext()->language->iso_code.'.js'); !!}

<script>
  $(function() {
    $( "#date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
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


  {{-- Quantity prefix selector --}}
    .select-group input.form-control{ width: 65%}
    .select-group select.input-group-addon { width: 35%; }

</style>

@endsection