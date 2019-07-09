@section('modals')

@parent

<!-- Modal -->
<div class="modal fade" id="customeruserModal" tabindex="-1" role="dialog" aria-labelledby="customeruserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-draggable modal-dialog-help" role="document">
    <div class="modal-content">
      <div class="modal-header alert-info">
        <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="customeruserModalLabel"><span id="customeruserModalTitle"></span></h4>
      </div>
      <div class="modal-body" id="price_rule">

<input type="hidden" value="{{$customer->id}}" name="customeruser_customer_id" id="customeruser_customer_id">
<input type="hidden" value="" name="customeruser_id" id="customeruser_id">
<input type="hidden" value="" name="customeruser_action" id="customeruser_action">
{{-- <input type="hidden" value="customeruser" name="tab_name" id="tab_name"> --}}


    <div id="msg-customeruser-error" class="alert alert-danger" style="display:none">

        <ul></ul>

    </div>


  <div class="row">
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('customeruser_firstname', l('Name', 'customerusers')) !!}
    {!! Form::text('customeruser_firstname', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('customeruser_lastname', l('Surname', 'customerusers')) !!}
    {!! Form::text('customeruser_lastname', null, array('class' => 'form-control')) !!}
</div><br />
</div>

<div class="row">
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('customeruser_email', l('Email', 'customerusers')) !!}
    {!! Form::text('customeruser_email', null, array('xplaceholder' => l('your@email.com'), 'class' => 'form-control', 'required' => 'required')) !!}
</div>
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('password', l('Password', 'customerusers')) !!}
    {!! Form::text('password', null, array('id' => 'password', 'class' => 'form-control',  "autocomplete" => "off", 'class' => 'form-control', 'required' => 'required')) !!}
</div>
</div>

<div class="row">
<!-- div class="form-group col-lg-4 col-md-4 col-sm-4">
    {!! Form::label('language_id', l('Language')) !!}
    {!! Form::select('language_id', $languageList=[], null, array('class' => 'form-control')) !!}
</div -->

           <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-active">
             {!! Form::label('customeruser_active', l('Allow Customer Center access?', 'customerusers'), ['class' => 'control-label']) !!}
             <div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('customeruser_active', '1', true, ['id' => 'customeruser_active_on']) !!}
                   {!! l('Yes', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('customeruser_active', '0', false, ['id' => 'customeruser_active_off']) !!}
                   {!! l('No', [], 'layouts') !!}
                 </label>
               </div>
             </div>
           </div>

@if ( $aBook->count() > 1 ) 
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('location_id') ? 'has-error' : '' }}">
                      <label class="control-label">{{ l('Location / Address', 'customerusers') }}</label>

                    <select class="form-control" name="location_id" id="location_id">
                        <option selected="selected" value="">{{ l('-- All --', [], 'layouts') }}</option>
                        @foreach ($aBook as $country)
                        <option value="{{ $country->id }}">{{ $country->alias }}</option>
                        @endforeach
                    </select>

                    {!! $errors->first('location_id', '<span class="help-block">:message</span>') !!}
                  </div>
@else
                  <input type="hidden" value="" name="location_id" id="location_id">
@endif

</div>

<div class="row" style="border-top: 1px solid #dddddd; padding-top: 15px;">

      <div class="form-group col-lg-4 col-md-4 col-sm-4">
          {!! Form::label('enable_quotations', l('Enable Quotations', 'customerusers'), ['class' => 'control-label']) !!}
             <div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('enable_quotations', '1', NULL, ['id' => 'enable_quotations_on']) !!}
                   {!! l('Yes', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('enable_quotations', '0', NULL, ['id' => 'enable_quotations_off']) !!}
                   {!! l('No', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('enable_quotations', '-1', NULL, ['id' => 'enable_quotations_default']) !!}
                   {!! l('Default', [], 'layouts') !!}
                 </label>
               </div>
             </div>
      </div>

      <div class="form-group col-lg-4 col-md-4 col-sm-4">
          {!! Form::label('enable_min_order', l('Enable minimum Order', 'customerusers')) !!}
             <div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('enable_min_order', '1', NULL, ['id' => 'enable_min_order_on']) !!}
                   {!! l('Yes', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('enable_min_order', '0', NULL, ['id' => 'enable_min_order_off']) !!}
                   {!! l('No', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('enable_min_order', '-1', NULL, ['id' => 'enable_min_order_default']) !!}
                   {!! l('Default', [], 'layouts') !!}
                 </label>
               </div>
             </div>
      </div>

      <div class="form-group col-lg-4 col-md-4 col-sm-4">
          {!! Form::label('min_order_value', l('Minimum Order Value', 'customerusers')) !!}
          {!! Form::text('min_order_value', null, array('class' => 'form-control')) !!}
      </div>

</div>

        <div class="row">

                   <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-display_prices_tax_inc">
                     {!! Form::label('display_prices_tax_inc', l('Display Prices tax inc?', 'customerusers'), ['class' => 'control-label']) !!}
                     <div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('display_prices_tax_inc', '1', NULL, ['id' => 'display_prices_tax_inc_on']) !!}
                   {!! l('Yes', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('display_prices_tax_inc', '0', NULL, ['id' => 'display_prices_tax_inc_off']) !!}
                   {!! l('No', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('display_prices_tax_inc', '-1', NULL, ['id' => 'display_prices_tax_inc_default']) !!}
                   {!! l('Default', [], 'layouts') !!}
                 </label>
               </div>
                     </div>
                   </div>

                   <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-notify_customer">
                     {!! Form::label('notify_customer', l('Notify Customer? (by email)'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('notify_customer', '1', true, ['id' => 'notify_customerb_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('notify_customer', '0', false, ['id' => 'notify_customer_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>
        </div>  




      </div>

      <div class="modal-footer">

               <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
               <button type="submit" class="btn btn-success" name="modal_customeruserSubmit" id="modal_customeruserSubmit">
                <i class="fa fa-thumbs-up"></i>
                &nbsp; {{l('Save', [], 'layouts')}}</button>

      </div>

    </div>
  </div>
</div>

@endsection


@section('scripts')    @parent


{{-- Auto Complete --}}


<script type="text/javascript">

    $(document).ready(function() {

        // To get focus;
        $( "#firstname" ).focus();


    });



          $("body").on('click', "#modal_customeruserSubmit", function() {

            var id = $('#customeruser_id').val();

            var action = $('#customeruser_action').val();
//            var url = "{{ route('customerusers.store') }}";
            var token = "{{ csrf_token() }}";

            switch( action ) {
              case 'create':
                // code block
                method = 'POST';
                url = "{{ route('customerusers.store') }}";
                break;
              case 'update':
                // code block
                method = 'PATCH';
                url = "{{ route('customerusers.update', ':id') }}";
                url = url.replace(':id', id);
                break;
              default:
                // code block
                displayErrorMsg(['{{ l('No action is taken &#58&#58 (:id) ', ['id' => ''], 'layouts') }}']);

                return ;
            } 

            var payload = {
                              action : action,
                              customer_id : $('#customeruser_customer_id').val(),
                              customer_user_id : $('#customeruser_id').val(),
                              address_id  : $('#location_id').val(),

                              firstname : $('#customeruser_firstname').val(),
                              lastname :  $('#customeruser_lastname').val(),
                              email :     $('#customeruser_email').val(),
                              password :  $('#password').val(),

                              active : $("input[name='customeruser_active']:checked").val(),
                              enable_quotations : $("input[name='enable_quotations']:checked").val(),
                              enable_min_order  : $("input[name='enable_min_order']:checked").val(),
                              min_order_value   : $('#min_order_value').val(),

                              display_prices_tax_inc : $("input[name='display_prices_tax_inc']:checked").val(),
                          };

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : method,
                dataType : 'json',
                data : payload,

                success: function(data){
                    
                    if ( $.isEmptyObject(data.error) ) {

                        getCustomerUsers();

                        $(function () {  $('[data-toggle="tooltip"]').tooltip()});

                        $('#customeruserModal').modal('toggle');

                        showAlertDivWithDelay("#msg-customeruser-success");
                    
                    } else {

                        displayErrorMsg(data.error);

                    }
                }
            });

        });


        function displayErrorMsg (msg) {

            $("#msg-customeruser-error").find("ul").empty(  );

            $.each( msg, function( key, value ) {

                $("#msg-customeruser-error").find("ul").append('<li>'+value+'</li>');

            });

            $("#msg-customeruser-error").css('display','block');

        }

</script>

@endsection


@section('styles')    @parent

<style>

/*
See: https://coreui.io/docs/components/buttons/ :: Brand buttons
*/
.btn-behance {
    color: #fff;
    background-color: #1769ff;
    border-color: #1769ff;
}

</style>

@endsection




