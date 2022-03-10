@extends('layouts.master')

@section('title') {{ l('Currency Converter') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <!-- a href="{{ URL::to('currencies/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

        <a href="{{ URL::to('currencies/create') }}" class="btn btn-sm btn-navy" xstyle="margin-right: 72px; margin-left: 72px; "
        		title="{{l('Currency Converter', [], 'layouts')}}" target="_currency"><i class="fa fa-exchange"></i> {{l('Currency Converter', [], 'layouts')}}</a -->
    </div>
    <h2>
        {{ l('Currency Converter') }}
    </h2>        
</div>
{{-- l('culo_1') }} {{ l('culo_2', 'layouts') }} {{ l('culo_3', [], 'layouts') }} {{ l('culo_4', []) --}}
<div id="div_currency_converter">

	<form method="post" id="currency-form"> 		
		<div class="form-group">
		<label>{{ l('From') }}</label>
			<select name="from_currency">
				<option value="USD" selected="1">US Dollar</option>
				<option value="EUR">Euro</option>
				<option value="CNY">Chinese Yuan</option>
			</select>	
			 <label>{{ l('Amount') }}</label>	
			<input type="text" placeholder="" name="amount" id="amount" value="1"/>			
			 <label>{{ l('To') }}</label>
			<select name="to_currency">
				<option value="USD" selected="1">US Dollar</option>
				<option value="EUR">Euro</option>
				<option value="CNY">Chinese Yuan</option>
			</select>			
			  <button type="submit" name="convert" id="convert" class="btn btn-success">{{ l('Convert') }}</button>			
		</div>			
	</form>	
	
	<div class="form-group" id="converted_rate"></div>	
	<div id="converted_amount"></div>
				
	<div class=" hide " style="margin:50px 0px 0px 0px;">
		<a class="btn btn-default read-more" style="background:#3399ff;color:white" href="http://www.phpzag.com/convert-currency-using-google-api/" title="">Back to Tutorial</a>			
	</div>		

</div>


@endsection


@section('scripts')    @parent

<script>

	@include('currencies.js.jquery-validation.js')

</script>

<script type="text/javascript">

$('document').ready(function() { 
	/* handling Currency Conversion Form validation */
	$("#currency-form").validate({
		rules: {
			amount: {
				required: true,
			},
		},
		messages: {
			amount:{
			  required: ""
			 },			
		},
		submitHandler: handleCurrencyConvert	
	});

	/* Handling Currency Convert functionality */
	function handleCurrencyConvert() {		
		var data = $("#currency-form").serialize();				
		$.ajax({				
			type : 'POST',
			url  : '{{ route('currencies.converter.result') }}',
            headers : {'X-CSRF-TOKEN' : "{{ csrf_token() }}"},
			dataType:'json',
			data : data,
			beforeSend: function(){	
				$("#converted_rate").html("");
				$("#converted_amount").html("");
				$("#convert").html('<span class="glyphicon glyphicon-transfer"></span> &nbsp; {{ l('converting ...') }}');
			},
			success : function(response){				
				console.log(response);
				if(response.error == 1){	
					$("#converted_rate").html('<span class="form-group has-error">{{ l('Error: Please select different Currency') }}</span>'); 
					$("#converted_amount").html("");
					$("#convert").html('{{ l('Convert') }}');
					$("#converted_rate").show();	 
				} else if(response.error == 2){	
					$("#converted_rate").html('<span class="form-group has-error">{{ l('Error: Please try again') }}</span>'); 
					$("#converted_amount").html("");
					$("#convert").html('{{ l('Convert') }}');
					$("#converted_rate").show();	 
				} else if(response.error == 3){	
					$("#converted_rate").html('<span class="form-group has-error">{{ l('Error: API Key missing') }}</span>'); 
					$("#converted_amount").html("");
					$("#convert").html('{{ l('Convert') }}');
					$("#converted_rate").show();	 
				} else if(response.error == 4){	
					$("#converted_rate").html('<span class="form-group has-error">{{ l('Error: Bad API Key') }}</span>'); 
					$("#converted_amount").html("");
					$("#convert").html('{{ l('Convert') }}');
					$("#converted_rate").show();	 
				} else if(response.exhangeRate){									
					$("#converted_rate").html("<strong>{{ l('Exchange Rate') }} ("+response.toCurrency+"</strong>) : "+response.exhangeRate);
					$("#converted_rate").show();
					$("#converted_amount").html("<strong>{{ l('Converted Amount') }} ("+response.toCurrency+"</strong>) : "+response.convertedAmount);
					$("#converted_amount").show();
					$("#convert").html('{{ l('Convert') }}');
				} else {	
					$("#converted_rate").html("{{ l('No Result') }}");	
					$("#converted_rate").show();	
					$("#converted_amount").html("");
				}
			}
		});
		return false;
	}   
});

</script> 

@endsection