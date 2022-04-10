@extends('layouts.master')

@section('title') {{ l('Actions - Create', 'actions') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-8 col-md-offset-2" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">{{ l('New Action', 'actions') }}</h3>
          		<h3 class="panel-title" style="margin-top:10px;">{{ l('Owned by', 'actions') }}: ({{$customer->id}}) {{$customer->name_regular}}</h3>
			</div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::open(array('route' => array('customers.actions.store', $customer->id), 'name' => 'create_action', 'class' => 'form')) !!}

					@include('customer_actions._form')

				{!! Form::close() !!}
				
			</div>
		</div>
	</div>
</div>

@endsection

@section('scripts')    @parent

<script type="text/javascript">


    $(document).ready(function() 
    {

        // Set sensible defauts
@if ($customer->sales_rep_id)
        $( "#sales_rep_id" ).val( '{{ old('sales_rep_id', $customer->sales_rep_id) }}' );
@endif

@if ($customer_primary_contact = $customer->getPrimaryContact())
        $( "#contact_id" ).val( '{{ old('contact_id', $customer_primary_contact->id) }}' );
@endif


    });

</script>

@endsection
