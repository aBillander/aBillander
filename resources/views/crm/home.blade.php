@extends('layouts.master')

@section('title') {{ l('Welcome') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right hide" style="padding-top: 4px;">
        <a href="{{ URL::to('jennifer/home') }}" class="btn btn-sm btn-success" 
            title="{{l('Jennifer', [], 'layouts')}}"><i class="fa fa-user-secret"></i> {{l('Jennifer', [], 'layouts')}}</a>
    </div>
    <h2>
         
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
            {{ Auth::user()->getFullName() }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>

         <!-- a href="{{ URL::to('auth/logout') }}">{{ Auth::user()->getFullName() }}</a --> <span style="color: #cccccc;">/</span> {{ l('microCRM', 'layouts') }} <span style="color: #cccccc;">/</span> {{ l('Home', 'layouts') }}
    </h2>
</div>



<div xclass="container">
    <div class="row">
        <div xclass="col-lg-8 col-md-8">

            <div class="col-lg-8 col-md-8">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">{{ l('Lead Lines', 'leadlines') }}</h3>
              </div>
              <div class="panel-body">

                  @include('crm._block_lead_lines')
                  
              </div>
            </div>
            </div>
        </div>
    </div>


</div>



{{-- ***************************************************** --}}


<div class="container-fluid">
   <div class="row">

      <div class="col-lg-2 col-md-2 col-sm-2">
         <!-- div class="list-group">
            <a id="b_main_data" href="#" class="list-group-item active">
               <i class="fa fa-asterisk"></i>
               &nbsp; {{ l('Updates') }}
            </a>
         </div -->
      </div>

      
      <div class="col-lg-9 col-md-9 col-sm-10">
      <div class="jumbotron" style="background: no-repeat url('{{URL::to('/assets/theme/images/Dashboard.jpg')}}'); background-size: 100% auto;min-height: 200px; margin-top: 40px;">


      </div>
      </div>

   </div>
</div>




{{--
<div class="jumbotron">

  <!-- h1>Jumbotron</h1>
  <p>This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
  <p><a class="btn btn-primary btn-lg">Learn more</a></p -->
<img src="{{URL::to('/assets/theme/images/Dashboard.jpg')}}" 
                    title=""
                    class="center-block"
                    style=" border: 1px solid #dddddd;
                            border-radius: 18px;
                            -moz-border-radius: 18px;
                            -khtml-border-radius: 18px;
                            -webkit-border-radius: 18px;">
 HTML::image('img/picture.jpg', 'a picture', array('class' => 'thumb'))
</div>
 --}}

@endsection


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


{{-- Auto Complete --}}

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script type="text/javascript">

    $(document).ready(function() {

        $("#autocustomer_name").val('');
        $("#autoproduct_name").val('');
        $("#autocustomerorder_name").val('');
        $("#autocustomershippingslip_name").val('');
        $("#autocustomerinvoice_name").val('');

        // To get focus;
        $("#autocustomer_name").focus();

        $("#autocustomer_name").autocomplete({
            source : "{{ route('home.searchcustomer') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                customer_id = value.item.id;

                redirect = "{{ URL::to('customers') }}/"+customer_id+"/edit";

                window.location.href = redirect;

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.identification+'] ' + item.name_regular + "</div>" )
                .appendTo( ul );
            };



        $("#autoproduct_name").autocomplete({
            source : "{{ route('home.searchproduct') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                product_id = value.item.id;

                redirect = "{{ URL::to('products') }}/"+product_id+"/edit";

                window.location.href = redirect;

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.reference+'] ' + item.name + "</div>" )
                .appendTo( ul );
            };



        $("#autocustomerorder_name").autocomplete({
            source : "{{ route('home.searchcustomerorder') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                customerorder_id = value.item.id;

                redirect = "{{ URL::to('customerorders') }}/"+customerorder_id+"/edit";

                window.location.href = redirect;

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>'+item.id+' / ' + (item.document_reference || "{{ l('Draft', 'layouts') }}") +' - ' + item.document_date.split(" ")[0] + ' - '+(item.reference_external || '')+'</div>' )
                .appendTo( ul );
            };



        $("#autocustomershippingslip_name").autocomplete({
            source : "{{ route('home.searchcustomershippingslip') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                customershippingslip_id = value.item.id;

                redirect = "{{ URL::to('customershippingslips') }}/"+customershippingslip_id+"/edit";

                window.location.href = redirect;

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>'+item.id+' / ' + (item.document_reference || "{{ l('Draft', 'layouts') }}") +' - ' + item.document_date.split(" ")[0] + ' - '+(item.reference_external || '')+'</div>' )
                .appendTo( ul );
            };



        $("#autocustomerinvoice_name").autocomplete({
            source : "{{ route('home.searchcustomerinvoice') }}",
            minLength : 1,
//            appendTo : "#modalProductionOrder",

            select : function(key, value) {

                customerinvoice_id = value.item.id;

                redirect = "{{ URL::to('customerinvoices') }}/"+customerinvoice_id+"/edit";

                window.location.href = redirect;

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>'+item.id+' / ' + (item.document_reference || "{{ l('Draft', 'layouts') }}") +' - ' + item.document_date.split(" ")[0] + ' - '+(item.reference_external || '')+'</div>' )
                .appendTo( ul );
            };

    });

</script> 

@endsection