@extends('layouts.master')

@section('title') {{ l('Price List Lines - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">{{ l('Edit Price List Line') }} :: ({{$supplier->id}}) {{$supplier->name_regular}} / [{{ $line->product->reference }}] {{ $line->product->name }}</h3></div>
			<div class="panel-body">
				{!! Form::model($line, array('method' => 'PATCH', 'route' => array('suppliers.supplierpricelistlines.update', $supplier->id, $line->id))) !!}

                    {!! Form::hidden('supplier_id', $supplier->id, array('id' => 'supplier_id')) !!}

					@include('supplier_price_list_lines._form')

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection
@section('scripts')    @parent

    <script type="text/javascript">

        $(document).ready(function() {

        	$("#price").focus();

        });     // ENDS      $(document).ready(function() {

    </script>

@endsection