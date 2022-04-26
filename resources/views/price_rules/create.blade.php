@extends('layouts.master')

@section('title') {{ l('Price Rules - Create') }} @parent @endsection


@section('content')

<div class="container-fluid" style="margin-top: 50px">
   <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-3">

          {{-- Poor man offset --}}

      </div>
      
      <div class="col-lg-8 col-md-8 col-sm-8">

          @include('price_rules._panel_create_rule')

      </div>
   </div>
</div>





@endsection

@section('scripts')    @parent


{{-- Auto Complete --}}


<script type="text/javascript">

    $(document).ready(function() {

        // Forget Product
        $("#product_query").val('');
        $('#product_id').val('');

        // Forget Customer
        $("#autocustomer_name").val('');
        $('#customer_id').val('');

        // Redirect to the right tab
        if ( $('#rule_type').val() == 'pack' )
        {
              $('.nav-tabs a[href="#tab3default"]').tab('show');

        } else
        if ( $('#rule_type').val() == 'promo' )
        {
              $('.nav-tabs a[href="#tab2default"]').tab('show');
        } 

        // To get focus;
        $("#autocustomer_name").focus();

        $("#autocustomer_name").autocomplete({
//            source : "{{ route('customers.ajax.nameLookup') }}",
            source : "{{ route('customerinvoices.ajax.customerLookup') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                getCustomerData( value.item.id );

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.identification+'] ' + item.name_fiscal + "</div>" )
                .appendTo( ul );
            };


        function getCustomerData( customer_id )
        {
            var token = "{{ csrf_token() }}";

            $.ajax({
                url: "{{ route('customerinvoices.ajax.customerLookup') }}",
                headers : {'X-CSRF-TOKEN' : token},
                method: 'GET',
                dataType: 'json',
                data: {
                    customer_id: customer_id
                },
                success: function (response) {
                    var str = '[' + response.identification+'] ' + response.name_fiscal;
                    var shipping_method_id;

                    $("#autocustomer_name").val(str);
                    $('#customer_id').val(response.id);
                    $('#customer_group_id').val('');

                    if (response.sales_equalization > 0) {
                        $('#sales_equalization').show();
                    } else {
                        $('#sales_equalization').hide();
                    }

    //                $('#sequence_id').val(response.work_center_id);
                    $('#document_date_form').val('{{ abi_date_form_short( 'now' ) }}');
                    $('#delivery_date_form').val('');

                    if ( response.payment_method_id > 0 ) {
                      $('#payment_method_id').val(response.payment_method_id);
                    } else {
                      $('#payment_method_id').val({{ AbiConfiguration::getInt('DEF_CUSTOMER_PAYMENT_METHOD') }});
                    }

                    $('#currency_id').val(response.currency_id);
                    $('#currency_conversion_rate').val(response.currency.conversion_rate);
                    $('#down_payment').val('0.0');

                    $('#invoicing_address_id').val(response.invoicing_address_id);

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

                    $('#warehouse_id').val({{ intval(AbiConfiguration::get('DEF_WAREHOUSE'))}});

                    shipping_method_id = response.shipping_method_id;
                    if (shipping_method_id == null) {
                        shipping_method_id = "{{ AbiConfiguration::getInt('DEF_SHIPPING_METHOD') }}";
                    }
                    $('#shipping_method_id').val( shipping_method_id );

                    $('#sales_rep_id').val(response.sales_rep_id);

                    console.log(response);
                }
            });
        }

    });

    $("#customer_group_id").change(function(){
        $("#autocustomer_name").val('');
        $('#customer_id').val('');
    }); 

    function sanitize_data()
    {
        if ( $("#price").val() == '' )
        {
            $("#price").val('0.0');
        }
    }

</script> 



{{-- Date Picker :: http://api.jqueryui.com/datepicker/ --}}

<!-- script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! HTML::script('assets/plugins/jQuery-UI/datepicker/datepicker-'.AbiContext::getContext()->language->iso_code.'.js'); !!}

<script>
  $(function() {
    $( "#date_from_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });
  $(function() {
    $( "#date_to_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });
</script>


{{-- AutoComplete :: https://jqueryui.com/autocomplete/--}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>

// http://stackoverflow.com/questions/21627170/laravel-tokenmismatchexception-in-ajax-request
// var _globalObj = {{ json_encode(array('_token'=> csrf_token())) }};

  $(function() {
    $( "#product_query" ).autocomplete({
      source: "{{ route('products.ajax.nameLookup') }}",
      minLength: 1,
      appendTo : "#price_rule",
      select: function( event, ui ) {
      //  alert( ui.item ?
      //    "Selected: " + ui.item.value + " aka " + ui.item.id :
      //    "Nothing selected, input was " + this.value );

        $( "#product_query" ).val(ui.item.name);
        $( "#reference" ).html( '<div class="form-control">'+ui.item.reference+'</div>' );
        $( "#product_id" ).val( ui.item.id );

        // Cost & Price stuff (to be done)
        // Measure Units
        getProductData( ui.item.id );

        // Product has combinations?
        $("#product_options").addClass('loading');

           var token = "{{ csrf_token() }}";        // See also: http://words.weareloring.com/development/laravel/laravel-4-csrf-tokens-when-using-jquerys-ajax/
           $.ajax({
              type: 'POST',
              url: "{{ route('products.ajax.optionsLookup') }}",
              dataType: 'html',
              data: "product_id="+ui.item.id+"&_token="+token,
              success: function(data) {
                 $("#product_options").html(data);
                 $("#product_options").removeClass('loading');

                 if ( data == '' ) 
                 {
                      $( "#div_product_options" ).hide();
                      $( "#quantity" ).focus();
                 } else {
                      $( "#div_product_options" ).show();
                      $( "#product_query" ).blur();
                      // ...and/or set focus on first select field
                 }
              }
           });
          
          return false;
      }
    })
    // http://stackoverflow.com/questions/9887032/how-to-highlight-input-words-in-autocomplete-jquery-ui
    .data("ui-autocomplete")._renderItem = function (ul, item) {
//        var newText = String(item.value).replace(
//                new RegExp(this.term, "gi"),
//                "<span class='ui-state-highlight' style='color: #dd4814;'><strong>$&</strong></span>");

        return $("<li></li>")
//            .data("item.autocomplete", item)
//            .append("<a>" + newText + "</a>")
            .append( '<div>[' + item.id + '] [' + item.reference + '] ' + item.name + "</div>" )
            .appendTo(ul);
    };
  });





        function getProductData( product_id )
        {
            var token = "{{ csrf_token() }}";

            $("#product_infos").fadeOut();

            $.ajax({
                url: "{{ route('products.ajax.nameLookup') }}",
                headers : {'X-CSRF-TOKEN' : token},
                method: 'GET',
                dataType: 'json',
                data: {
                    product_id: product_id
                },
                success: function (response) {
                    // var str = '[' + response.identification+'] ' + response.name_fiscal;
                    var conversion;

                    $('#measure_unit_id').empty();

    //                $('#shipping_address_id').append('<option value="0" disable="true" selected="true">=== Select Address ===</option>');

                    $.each(response.product.extra_measureunits, function(index, element){
                      conversion = ' - '+Math.round(element.conversion_rate)+' x '+response.product.measureunit.name;
                      $('#measure_unit_id').append('<option value="'+ element.id +'">'+ element.name +conversion+'</option>');
                    });

                    // Nice transition (Ihope!)
                    $("#product_infos").html(response.infos);
                    $("#product_infos").fadeIn();

                    console.log(response);
                }
            });
        }

    // See: https://stackoverflow.com/questions/20705905/bootstrap-3-jquery-event-for-active-tab-change
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      var target = $(e.target).attr("href") // activated tab
      if (target == '#tab3default')
      {
                    // $('#measure_unit_id').empty();

                    // $('#measure_unit_id').append('<option value="0">{{ l('-- Please, select --', 'layouts') }}</option>');
      }
      /*
      if ($(target).is(':empty')) {
        $.ajax({
          type: "GET",
          url: "/article/",
          error: function(data){
            alert("There was a problem");
          },
          success: function(data){
            $(target).html(data);
          }
      })
     }
     */
    });

    // Active tab:
    // var id = $('.tab-content .active');
    // https://stackoverflow.com/questions/33743432/bootstrap-tabpanel-which-tab-is-selected




</script>

@endsection

@section('styles')    @parent

{{-- Date Picker --}}

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

{{-- Auto Complete --}}

  {{-- !! HTML::style('assets/plugins/AutoComplete/styles.css') !! --}}

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">


<style>
  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }
  {{-- See: https://stackoverflow.com/questions/6762174/jquery-uis-autocomplete-not-display-well-z-index-issue
            https://stackoverflow.com/questions/7033420/jquery-date-picker-z-index-issue
    --}}
  .ui-datepicker{ z-index: 9999 !important;}
</style>

@endsection
