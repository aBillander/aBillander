@extends('layouts.master')

@section('title') {{ l('Price List Lines - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Supplier Reference') }} :: ({{$supplier->id}}) {{$supplier->name_regular}} / [{{ $line->product->reference }}] {{ $line->product->name }}</h3></div>
			<div class="panel-body">

{{--
	Does not work: throw 404 error. Why??? Maybe model binding???

				{!! Form::model($line, array('method' => 'PATCH', 'route' => array('supplier.product.update.reference', $supplier->id, $line->product_id))) !!}
--}}

				<form action="{{ route('supplier.product.update.reference', [$supplier->id, $line->product_id]) }}" method="POST">

					<input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {!! Form::hidden('supplier_id', $supplier->id, array('id' => 'supplier_id')) !!}

					@include('supplier_price_list_lines._form_reference')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection
@section('scripts')    @parent

    <script type="text/javascript">

        $(document).ready(function() {

        	$("#supplier_reference").focus();

        });     // ENDS      $(document).ready(function() {

    </script>

@endsection