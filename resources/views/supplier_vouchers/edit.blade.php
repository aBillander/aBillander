@extends('layouts.master')

@section('title') {{ l('Supplier Vouchers - Edit') }} @parent @endsection


@section('content')

<div class="row">
	<div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">

@if ( $action == 'pay' )
          {{ l('Pay Supplier Voucher') }}
@else
         {{ l('Edit Supplier Voucher') }}
@endif

@if ( $payment->paymentable )
           :: {{ l('Invoice') }}: {{ $payment->paymentable->document_reference }}

@elseif ($payment->is_down_payment)
           :: {{ l('Down Payment', 'supplierdownpayments') }}
@endif
            . {{ l('Due Date') }}: {{ abi_date_short($payment->due_date) }}</h3>
		        <h3 class="panel-title" style="margin-top:10px;">{{ l('Amount') }}: {{ $payment->as_price('amount') }} {{ $payment->currency->name }}

@if($payment->payment_date)
               . &nbsp; {{ l('Payment Date') }}: {{ abi_date_short($payment->payment_date) }}
@endif
            </h3>

		    </div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::model($payment, array('method' => 'PATCH', 'route' => array('suppliervouchers.update', $payment->id), 'onsubmit' => 'return checkFields();')) !!}

@if ( $action == 'pay' )
          @include('supplier_vouchers._form_pay')
@elseif ( $action == 'bounce' )
          @include('supplier_vouchers._form_bounce')
@else
          @if ( $payment->status == 'pending' )
              @include('supplier_vouchers._form_edit')
          @else
              @include('supplier_vouchers._form_edit_notes')
          @endif
@endif

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection

@section('styles')

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

@endsection

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


          $('#show_extra_fields').click(function() {
            $('.extrafield').toggle("slide");
            return false;
          });
   });

function checkFields() 
{
  // https://blog.abelotech.com/posts/number-currency-formatting-javascript/

  var amount = parseFloat($("#amount").val().replace(',', '.'));
  var amount_initial = parseFloat($("#amount_initial").val());

  amount = +amount || 0; // See: https://stackoverflow.com/questions/7540397/convert-nan-to-0-in-javascript

          $("#amount_check").hide();
          $("#voucher_next").hide();

          $("#amount_next").val(0);

// Check amount

// Positive amount
if ( amount_initial > 0 )
{
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
} // Positive amount ENDS


// Negative amount
if ( amount_initial < 0 )
{
   if ( (amount>0.0) || (amount < amount_initial) ) 
   {
      $("#amount_check").show();
      return false;
   } else {

      if (amount > amount_initial) {
          $("#amount_next").val(amount_initial - amount);
          $("#voucher_next").show();

      } // else {

          return true;

  //    }
   }
} // Negative amount ENDS

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


@endsection