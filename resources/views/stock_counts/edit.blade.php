@extends('layouts.master')

@section('title') {{ l('Stock Counts - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading">

                <h3 class="panel-title">{{ l('Edit Stock Count') }} :: ({{$stockcount->id}}) {{$stockcount->name}}</h3>
            </div>

			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($stockcount, array('method' => 'PATCH', 'route' => array('stockcounts.update', $stockcount->id))) !!}

					@include('stock_counts._form', ['with_lines' => true])

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection