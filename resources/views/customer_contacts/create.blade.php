@extends('layouts.master')

@section('title') {{ l('Contacts - Create', 'contacts') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-8 col-md-offset-2" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">{{ l('New Contact', 'contacts') }}</h3>
          		<h3 class="panel-title" style="margin-top:10px;">{{ l('Owned by', 'contacts') }}: ({{$customer->id}}) {{$customer->name_regular}}</h3>
			</div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::open(array('route' => array('customers.contacts.store', $customer->id), 'name' => 'create_contact', 'class' => 'form')) !!}

					@include('customer_contacts._form')

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

        // Set sensible defaut
        $( "#address_id" ).val( '{{ old('address_id', $customer->invoicing_address_id) }}' );


    });

</script>

@endsection
