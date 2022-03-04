@extends('layouts.master')

@section('title') {{ l('Production Orders') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <!-- a href="{{ URL::to('products/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a -->

        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>
    </div>
    <h2>
        {{ l('Production Orders') }}
    </h2>        
</div>


<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => 'productionorders.index', 'method' => 'GET')) !!}

<!-- input type="hidden" value="0" name="search_status" id="search_status" -->
{!! Form::hidden('search_status', null, array('id' => 'search_status')) !!}

<div class="row">
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('reference', l('Reference')) !!}
    {!! Form::text('reference', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('name', l('Product Name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('due_date_form', l('Due Date')) !!}
    {!! Form::text('due_date_form', null, array('id' => 'due_date_form', 'class' => 'form-control')) !!}
</div>

<!-- div class="form-group col-lg-2 col-md-2 col-sm-2">
    {{-- !! Form::label('category_id', l('Category')) !! }
        { !! Form::select('category_id', array('0' => l('All', [], 'layouts')) + $categoryList, null, array('class' => 'form-control')) !! --}}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('active', l('Active?', [], 'layouts'), ['class' => 'control-label']) !!}
    {!! Form::select('active', array('-1' => l('All', [], 'layouts'),
                                          '0'  => l('No' , [], 'layouts'),
                                          '1'  => l('Yes', [], 'layouts'),
                                          ), null, array('class' => 'form-control')) !!}
</div -->

<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('productionorders.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>


<div id="div_productionorders">
   <div class="table-responsive">

@if ($orders->count())
<table id="productionorders" class="table table-hover">
    <thead>
        <tr>
			<th>{{l('ID', [], 'layouts')}}</th>
            <th>{{l('Due Date')}}</th>
            <th>{{l('Product Name')}}</th>
            <th>{{l('Quantity')}}</th>
            <th> </th>
            <th>{{l('Work Center')}}</th>
            <th>{{l('Provenience')}}</th>
            <th>{{l('Status', [], 'layouts')}}</th>
            <th> </th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
            <th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($orders as $order)
		<tr>
            <td>{{ $order->poid }}</td>
            <td>{{ abi_date_short($order->due_date) }}
              
        @if ($order->production_sheet_id)
                        {{-- abi_date_form_short($document->productionsheet->due_date) --}} 
                        <a class="btn btn-xs btn-warning" href="{{ URL::to('productionsheets/' . $order->production_sheet_id) }}" title="{{l('Go to Production Sheet')}}"><i class="fa fa-external-link"></i></a>
        @endif
              </td>
            <td>[<a href="{{ URL::to('products/' . $order->product->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_new">{{ $order->product->reference }}</a>] 
                {{ $order->product->name }}</td>
            <td class="text-right">{{ $order->as_quantityable($order->planned_quantity) }}</td>
            <td class="text-left">
                <span class="badge" style="background-color: #3a87ad;" title="{{ optional($order->measureunit)->name }}"> &nbsp; {{ optional($order->measureunit)->sign }} &nbsp; </span>
            </td>
            <td>{{ $order->workcenter->name ?? '' }}</td>
            <td>{{ $order->created_via }}</td>
            <td>
@if ( $order->status != 'finished' )
              <span class="label label-success" style="opacity: 0.75;">{{ $order->status_name }}</span></td><td>
@else
              <span class="label label-info" style="opacity: 0.75;">{{ $order->status_name }}</span></td><td>
              <span class="text-success" title="{{ l('Finish Date') }}"><xstrong>{{ abi_date_short($order->finish_date) }}</xstrong></span>
@endif
      </td>
            <td class="text-center">
                @if ($order->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $order->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif
                </td>

            <td class="text-right button-pad">
                @if (  is_null($order->deleted_at))
                       
                <a class="btn btn-sm btn-warning " href="{{ URL::to('productionorders/' . $order->poid . '/edit') }}"  title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

@if( $order->status != 'finished' )
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                        href="{{ URL::to('productionorders/' . $order->poid ) }}" 
                        data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                        data-title="{{ l('Lots') }} ::  ({{$order->poid}}) {{ $order->reference }}" 
                        onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
@endif
                @else
                <a class="btn btn-warning" href="{{ URL::to('productionorders/' . $order->poid. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('productionorders/' . $order->poid. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
            </td>
		</tr>
	@endforeach
    </tbody>
</table>
{!! $orders->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $orders->total() ], 'layouts')}} </span></li></ul>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

@endsection


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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>
  $(document).ready(function() {

    $( "#due_date_form" ).datepicker({
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
  .ui-datepicker{ z-index: 9999 !important;}


/* Undeliver dropdown effect */
   .hover-item:hover {
      background-color: #d3d3d3 !important;
    }

</style>

@endsection
