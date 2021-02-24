
<div class="panel panel-primary" id="panel_create_document">
   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Change Customer', 'customerdocuments') }}</h3>
   </div>

@if ( $canChangeCustomer > 0 )
      <div class="row">

         <div class="form-group col-lg-2 col-md-2 col-sm-2">
            {{-- Poor Man offset --}}
         </div>

         <div class="form-group col-lg-6 col-md-6 col-sm-6">

                <div class="alert alert-dismissible alert-warning" style="margin-top: 15px">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <h4><i class="fa fa-exclamation-triangle text-danger"></i> &nbsp;<strong>{{ l('Warning', 'layouts')}}</strong></h4>
                  <p>{{ l('The operation you are trying to do is high risk and cannot be undone.', 'layouts')}}</p>
                </div>
         </div>

	  </div>

    {!! Form::open(array('route' => [$model_path.'.update.customer', $document->id], 'id' => 'update_customer_'.$model_snake_case, 'name' => 'update_customer_'.$model_snake_case, 'class' => 'form')) !!}

            {!! Form::hidden('document_id', $document->id, array('id' => 'document_id')) !!}
 
               <div class="panel-body">

      <div class="row">

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('customer_id') ? 'has-error' : '' }}">
            {{ l('Customer Name') }} 
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('Search by Name or Identification (VAT Number).') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
            <span id="sales_equalization" class="label label-info" style="display: none;"> {{l('Equalization Tax')}} </span>
            {!! Form::text('document_autocustomer_name', old('document_autocustomer_name'), array('class' => 'form-control', 'id' => 'document_autocustomer_name')) !!}

            {!! Form::hidden('customer_id', old('customer_id'), array('id' => 'customer_id')) !!}
            {!! Form::hidden('invoicing_address_id', old('invoicing_address_id'), array('id' => 'invoicing_address_id')) !!}

            {!! $errors->first('customer_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('shipping_address_id') ? 'has-error' : '' }}">
            {{ l('Shipping Address') }}
            {!! Form::select('shipping_address_id', [], old('shipping_address_id'), array('class' => 'form-control', 'id' => 'shipping_address_id')) !!}
            {!! $errors->first('shipping_address_id', '<span class="help-block">:message</span>') !!}
         </div>

      </div>

               </div><!-- div class="panel-body" -->

               <div class="panel-footer text-right">
                  <button class="btn btn-danger" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-floppy-o"></i>
                     &nbsp; {{l('Change Customer', 'customerdocuments')}}
                  </button>
               </div>
         

    {!! Form::close() !!}

@else

      <div class="row">

         <div class="form-group col-lg-2 col-md-2 col-sm-2">
            {{-- Poor Man offset --}}
         </div>

         <div class="form-group col-lg-6 col-md-6 col-sm-6">

                <div class="alert alert-dismissible alert-danger" style="margin-top: 15px">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <h4><i class="fa fa-exclamation-triangle text-danger"></i> &nbsp;<strong>{{ l('Warning', 'layouts')}}</strong></h4>
                  <p>{{ l('It is not possible to change the Customer because the Document has Payments.', 'customerdocuments')}}</p>
                </div>
         </div>

	  </div>

@endif
</div>



@section('styles')    @parent

{{-- Auto Complete --}}

  {{-- !! HTML::style('assets/plugins/AutoComplete/styles.css') !! --}}

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"></script -->

<style>

  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }


/* See: http://fellowtuts.com/twitter-bootstrap/bootstrap-popover-and-tooltip-not-working-with-ajax-content/ 
.modal .popover, .modal .tooltip {
    z-index:100000000;
}
 */
</style>

@endsection


@section('scripts')    @parent


    <script type="text/javascript">

        $(document).ready(function() {

        @if ( ($cid = intval( old('customer_id') )) > 0 ) 

              var id = {{ $cid }};
              var url = "{{ route($model_path.'.ajax.customer.AdressBookLookup', [$cid]) }}";
              
               $.get(url, function(result){
                    $('#shipping_address_id').empty();

                    $.each(result, function(index, element){
                      $('#shipping_address_id').append('<option value="'+ index +'">'+ element +'</option>');
                    });

                    $('#shipping_address_id').val( {{ old('shipping_address_id') }} );

//                    $("[data-toggle=popover]").popover();
               });
        @endif

        });


    </script> 



{{-- Auto Complete --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">

    $(document).ready(function() {

        // To get focus;
        $("#document_autocustomer_name").focus();

        $("#document_autocustomer_name").autocomplete({
//            source : "{{ route('customers.ajax.nameLookup') }}",
            source : "{{ route($model_path.'.ajax.customerLookup') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                getCustomerData( value.item.id );

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.identification+'] ' + item.name_regular + "</div>" )
                .appendTo( ul );
            };


        function getCustomerData( customer_id )
        {
            var token = "{{ csrf_token() }}";

            $.ajax({
                url: "{{ route($model_path.'.ajax.customerLookup') }}",
                headers : {'X-CSRF-TOKEN' : token},
                method: 'GET',
                dataType: 'json',
                data: {
                    customer_id: customer_id
                },
                success: function (response) {
                    var str = '[' + response.identification+'] ' + response.name_fiscal;
                    var shipping_method_id;

                    $("#document_autocustomer_name").val(str);
                    $('#customer_id').val(response.id);

                    // https://www.youtube.com/watch?v=FHQh-IGT7KQ
                    $('#shipping_address_id').empty();

    //                $('#shipping_address_id').append('<option value="0" disable="true" selected="true">=== Select Address ===</option>');

                    $.each(response.addresses, function(index, element){
                      $('#shipping_address_id').append('<option value="'+ element.id +'">'+ element.alias +'</option>');
                    });

                    if ( response.shipping_address_id > 0 ) {
                      $('#shipping_address_id').val(response.shipping_address_id);
                    } else {
                      $('#shipping_address_id').val(response.invoicing_address_id);
                    }

                    console.log(response);
                }
            });
        }

    });

</script> 

@endsection
