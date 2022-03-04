@extends('layouts.master')

@section('title') {{ l('Product Images - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Product Image') }} :: ({{$image->id}}) {{$image->caption}}</h3></div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($image, array('method' => 'PATCH', 'route' => array('products.images.update', $product->id, $image->id))) !!}

					@include('absrc.products.images._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection