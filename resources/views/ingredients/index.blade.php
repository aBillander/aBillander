@extends('layouts.master')

@section('title') {{ l('Ingredients') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('ingredients/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>
    </div>
    <h2>
        {{ l('Ingredients') }}
    </h2>        
</div>


<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => 'ingredients.index', 'method' => 'GET')) !!}

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
    {{-- !! Form::label('category_id', l('Category')) !! }
        { !! Form::select('category_id', array('0' => l('All', [], 'layouts')) + $categoryList, null, array('class' => 'form-control')) !! --}}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('active', l('Active?', [], 'layouts'), ['class' => 'control-label']) !!}
    {!! Form::select('active', array('-1' => l('All', [], 'layouts'),
                                          '0'  => l('No' , [], 'layouts'),
                                          '1'  => l('Yes', [], 'layouts'),
                                          ), null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('ingredients.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>


<div id="div_ingredients">
   <div class="table-responsive">

@if ($products->count())
<table id="ingredients" class="table table-hover">
    <thead>
        <tr>
			<th>{{l('ID', [], 'layouts')}}</th>
			<th>{{ l('Reference') }}</th>
			<th>{{ l('Product Name') }}</th>
			<th>{{ l('Measure Unit') }}</th>
            <th>{{ l('Quantity decimals') }}</th>
            <!-- th>{{ l('Manufacturing Batch Size') }}</th -->
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
            <!-- th>{{ l('Category') }}</th -->
			<th class="text-center">{{l('Active', [], 'layouts')}}</th>
			<th class="text-right"> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($products as $product)
		<tr>
			<td>{{ $product->id }}</td>
			<td>@if ($product->product_type == 'combinable') <span class="label label-info">{{ l('Combinations') }}</span>
                @else {{ $product->reference }}
                @endif</td>
			<td>{{ $product->name }}</td>
			<td>{{ $product->measureunit->name }}</td>
            <td>{{ $product->quantity_decimal_places }}</td>
            <!-- td>{{ $product->manufacturing_batch_size }}</td -->
            <td class="text-center">
                @if ($product->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $product->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>
            <!-- td>@ if (isset($product->category->name)) { { $product->category->name } } @ endif</td -->
			<td class="text-center">@if ($product->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
           <td class="text-right">
                @if (  is_null($product->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('ingredients/' . $product->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('ingredients/' . $product->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Ingredients') }} :: ({{$product->id}}) {{ $product->name }}" 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('ingredients/' . $product->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('ingredients/' . $product->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
            </td>
		</tr>
	@endforeach
    </tbody>
</table>
{!! $products->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $products->total() ], 'layouts')}} </span></li></ul>
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

@endsection
