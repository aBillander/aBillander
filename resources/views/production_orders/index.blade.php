@extends('layouts.master')

@section('title') {{ l('Production Orders') }} @parent @stop


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
            <th>{{l('Product Reference')}}</th>
            <th>{{l('Product Name')}}</th>
            <th>{{l('Quantity')}}</th>
            <th>{{l('Work Center')}}</th>
            <th>{{l('Provenience')}}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
		</tr>
	</thead>
	<tbody>
	@foreach ($orders as $order)
		<tr>
            <td>{{ $order->poid }}</td>
            <td>{{ $order->productionsheet->due_date }}</td>
            <td>{{ $order->product_reference }}</td>
            <td>{{ $order->product_name }}</td>
            <td>{{ $order->planned_quantity }}</td>
            <td>{{ $order->workcenter->name ?? '' }}</td>
            <td>{{ $order->created_via }}</td>
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

@stop


@section('scripts') @parent 

<script type="text/javascript">

$(document).ready(function() {
   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });
});

</script>

@stop
