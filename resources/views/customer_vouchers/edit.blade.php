@extends('layouts.master')

@section('title') {{ l('Customer Vouchers - Edit') }} @parent @stop


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">{{ l('Edit Customer Voucher') }} :: {{ l('Invoice') }}: {{ $payment->paymentable->document_reference }} . {{ l('Due Date') }}: {{ abi_date_short($payment->due_date) }}</h3>
		        <h3 class="panel-title" style="margin-top:10px;">{{ l('Amount') }}: {{ $payment->as_price('amount') }} {{ $payment->currency->name }}</h3>
		    </div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($payment, array('method' => 'PATCH', 'route' => array('customervouchers.update', $payment->id), 'onsubmit' => 'return checkFields();')) !!}

@if ( $action == 'pay' )
          @include('customer_vouchers._form_pay')
@elseif ( $action == 'bounce' )
          @include('customer_vouchers._form_bounce')
@else
          @include('customer_vouchers._form_edit')
@endif

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@stop

@section('styles')

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

@stop

@section('scripts')

<script>

   $(document).ready(function() {
/*
      checkFields();

      if ($("#action").val()=='pay') {
          $("#voucher_payment_date").show();
          
        $(".makepay").addClass("btn-success");
        $(".makepay").removeClass("btn-lightblue");
      }
*/
          $("#due_date_next_form").val( $("#due_date_form").val() );
   });

function checkFields() 
{
  var amount = parseFloat($("#amount").val());
  var amount_initial = parseFloat($("#amount_initial").val());

  amount = +amount || 0; // See: https://stackoverflow.com/questions/7540397/convert-nan-to-0-in-javascript

          $("#amount_check").hide();
          $("#voucher_next").hide();

          $("#amount_next").val(0);

   if ( (amount<0.0) || (amount > amount_initial) ) 
   {
      $("#amount_check").show();
      return false;
   } else {

      if (amount < amount_initial) {
          $("#amount_next").val(amount_initial - amount);
          $("#voucher_next").show();

      } // else {

          return true;

  //    }
   }
}

function make_payment()
{
    $("#voucher_payment_date").toggle();
    $("#action").val('pay');

    if ( !$('#voucher_payment_date').is(':visible') ) {
       $("#payment_date").val('');
       $("#action").val('');
        $(".makepay").addClass("btn-lightblue");
        $(".makepay").removeClass("btn-success");
    } else {
        $(".makepay").addClass("btn-success");
        $(".makepay").removeClass("btn-lightblue");
    }
}
  
</script>


 {{-- Date Picker --}}

<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.\App\Context::getContext()->language->iso_code.'.js'); !!}

<script>

  $(function() {
    $( "#due_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#due_date_next_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });

  $(function() {
    $( "#payment_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ \App\Context::getContext()->language->date_format_lite_view }}"
    });
  });
  
</script>


@stop