@extends('layouts.master')

@section('title') {{ l('Stock Counts - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading">

                <a class="pull-right btn btn-xs btn-blue" href="{{ URL::to('stockcounts/' . $stockcount->id . '/stockcountlines') }}" title="{{l('Stock Count Lines')}}"><i class="fa fa-folder-open-o"></i></a>

            	<h3 class="panel-title">{{ l('Edit Stock Count') }} :: ({{$stockcount->id}}) {{$stockcount->name}}</h3>
            </div>

			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($stockcount, array('method' => 'PATCH', 'route' => array('stockcounts.update', $stockcount->id))) !!}

					@include('stock_counts._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection