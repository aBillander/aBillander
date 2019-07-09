
<div id="panel_customeruser">     

<div class="panel panel-info">

   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Customer Center Access') }}</h3>
   </div>
               <div class="panel-body">


<div id="msg-customeruser-success" class="alert alert-success alert-block" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span id="msg-customeruser-success-counter" class="badge"></span>
  <strong>{!!  l('This record has been successfully created &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
</div>




<!-- Customer Users --><!-- http://www.w3schools.com/bootstrap/bootstrap_ref_js_collapse.asp -->

<div class="content_customerusers"></div>

<!-- Customer Users ENDS -->

               </div><!-- div class="panel-body" -->



</div><!-- div class="panel panel-info" -->

</div><!-- div id="panel_customeruser" -->


@include('customers/_modal_customeruser_create')

@include('customers/_modal_customeruser_cart')


@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {


        $(document).on('click', '.create-customeruser', function(evnt) {

            var id = $(this).attr('data-id');

          	// Initialize
            $('#customeruser_action').val('create');
            $('#customeruser_id').val(id);

            $('#customeruser_firstname').val('');
            $('#customeruser_lastname').val('');
            $('#customeruser_email').val('');
            $('#password').val('{{ \App\Configuration::get('ABCC_DEFAULT_PASSWORD') }}');
            $('#location_id').val('');
            $('#min_order_value').val('{{ \App\Configuration::get('ABCC_MIN_ORDER_VALUE') }}');

            $("input[name='customeruser_active'][value='1']").prop('checked', true);
            $("input[name='enable_quotations'][value='-1']").prop('checked', true);
            $("input[name='enable_min_order'][value='-1']").prop('checked', true);
            $("input[name='allow_abcc_access'][value='1']").prop('checked', true);
            $("input[name='display_prices_tax_inc'][value='-1']").prop('checked', true);
            $("input[name='notify_customer'][value='1']").prop('checked', true);

          	// Open popup
            $('#customeruserModalTitle').html('{{ l('Create User') }} :: {{ $customer->name_commercial }}');
            $("#msg-customeruser-error").find("ul").empty(  );
            $("#msg-customeruser-error").css('display','none');

            $('#customeruserModal').modal({show: true});
            $("#customeruser_firstname").focus();


            return false;
        });


        $(document).on('click', '.update-customeruser', function(evnt) {

            var id = $(this).attr('data-id');

            var data = getCustomerUserData( id );

            return false;
        });
      

   });

   		$(window).on('hashchange',function(){
			page = window.location.hash.replace('#','');
			if (page == 'customerusers') getCustomerUsers();
		});


    function getCustomerUsers(){

      $.ajax({
        url: '{{ route( 'customer.getusers', [$customer->id] ) }}',
        data: {
          
        }
      }).done(function(data){
        $('.content_customerusers').html(data);
      });
    }




    function getCustomerUserData( id ){

      var url = "{{ route('customeruser.getuser', ':id') }}";
      url = url.replace(':id', id);
      
      $.ajax({
        url: url,
        data: {
          
        }
      }).done(function(data){
            console.log(data);
            user = data.user;

            // Initialize
            $('#customeruser_action').val('update');
            $('#customeruser_id').val(user.id);

            $('#customeruser_firstname').val(user.firstname);
            $('#customeruser_lastname').val(user.lastname);
            $('#customeruser_email').val(user.email);
            $('#password').val('');
            $('#location_id').val(user.address_id);
            $('#min_order_value').val(user.min_order_value);

            $("input[name='customeruser_active'][value='"+user.active+"']").prop('checked', true);
            $("input[name='enable_quotations'][value='"+user.enable_quotations+"']").prop('checked', true);
            $("input[name='enable_min_order'][value='"+user.enable_min_order+"']").prop('checked', true);
            $("input[name='allow_abcc_access'][value='"+user.allow_abcc_access+"']").prop('checked', true);
            $("input[name='display_prices_tax_inc'][value='"+user.display_prices_tax_inc+"']").prop('checked', true);
//            $("input[name='notify_customer'][value='"+user.+"']").prop('checked', true);
            $("#div-notify_customer").css('display','none');

            // Open popup
            $('#customeruserModalTitle').html('{{ l('Update User') }} :: {{ $customer->name_commercial }}');
            $("#msg-customeruser-error").find("ul").empty(  );
            $("#msg-customeruser-error").css('display','none');

            $('#customeruserModal').modal({show: true});
            $("#customeruser_firstname").focus();
      });

      // return false;
    }



</script>
@endsection