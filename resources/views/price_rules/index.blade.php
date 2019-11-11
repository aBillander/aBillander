@extends('layouts.master')

@section('title') {{ l('Price Rules') }} @parent @stop


@section('content')



<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('pricerules/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

        <!-- button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button -->

    </div>
    <h2>
        {{ l('Price Rules') }}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Price Rules take precedence over Price Lists.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
    </h2>        
</div>



<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => 'pricerules.index', 'method' => 'GET')) !!}

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
    {!! Form::select('warehouse_id', array('0' => l('All', [], 'layouts')) + ($warehouseList=[]), null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('pricerules.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>



<div id="div_rules">
   <div class="table-responsive">

@if ($rules->count())
<table id="rules" class="table table-hover">
	<thead>
		<tr>
			<th class="text-center">{{l('ID', [], 'layouts')}}</th>
      <th>{{l('Rule Name')}}</th>
      <!-- th>{{l('Category')}}</th -->
      <th>{{l('Product')}}</th>
      <th>{{l('Customer')}}</th>
      <th>{{l('Customer Group')}}</th>
      <th>{{l('Currency')}}</th>
      <th class="text-right">{{l('Price')}}</th>
      <!-- th class="text-right">{{l('Discount Percent')}}</th>
      <th class="text-right">{{l('Discount Amount')}}</th -->
      <th class="text-center">{{l('From Quantity')}}</th>
      <th class="text-center">{{l('Extra Items')}}</th>
      <th>{{l('Date from')}}</th>
      <th>{{l('Date to')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>

	@foreach ($rules as $rule)
		<tr>
      <td class="text-center">{{ $rule->id }}</td>
      <td>{{ $rule->name }}</td>
      <!-- td>{{ optional($rule->category)->name }}</td -->
      <td>
          @if($rule->product)
            [{{ optional($rule->product)->reference }}] <a href="{{ URL::to('products/' . optional($rule->product)->id . '/edit') }}" title="{{l('View Product')}}" target="_blank">{{ optional($rule->product)->name }}</a>
          @endif
        </td>
      <td>
          @if($rule->customer)
            <a href="{{ URL::to('customers/' . optional($rule->customer)->id . '/edit') }}" title="{{l('View Customer')}}" target="_blank">{{ optional($rule->customer)->name_commercial }}</a>
          @endif
        </td>
      <td>{{ optional($rule->customergroup)->name }}</td>
      <td>{{ optional($rule->currency)->name }}</td>

@if($rule->rule_type=='price')
      <td class="text-right">{{ $rule->as_price('price') }}</td>
@else
      <td class="text-right"> </td>
@endif

{{--
@if($rule->rule_type=='discount')
      @if($rule->discount_type=='percentage')
            <td class="text-right">{{ $rule->as_percent('discount_percent') }}</td>
      @else
            <td class="text-right"> </td>
      @endif
      @if($rule->discount_type=='amount')
            <td class="text-right">{{ $rule->as_price('discount_amount') }} 
              ({{ $rule->discount_amount_is_tax_incl > 0 ? l('tax inc.') : l('tax exc.') }})
            </td>
      @else
            <td class="text-right"> </td>
      @endif
@else
      <td class="text-right"> </td>
      <td class="text-right"> </td>
@endif
--}}

      <td class="text-center">{{ $rule->as_quantity('from_quantity') }}</td>

      <td class="text-center">{{ $rule->as_quantity('extra_quantity') ?: '' }}</td>

      <td>{{ abi_date_short( $rule->date_from ) }}</td>
			<td>{{ abi_date_short( $rule->date_to   ) }}</td>



            <td class="text-right button-pad">

                <!-- a class="btn btn-sm btn-warning" href="{{ URL::to('pricerules/' . $rule->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a -->

                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('pricerules/' . $rule->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Price Rules') }} :: ({{$rule->id}}) " 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
			
      </td>
		</tr>
	@endforeach

	</tbody>
</table>
{!! $rules->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $rules->total() ], 'layouts')}} </span></li></ul>
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