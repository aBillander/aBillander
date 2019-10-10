@section('modals')

@parent

<!-- Modal -->
<div class="modal fade" id="salesrepuserModal" tabindex="-1" role="dialog" aria-labelledby="salesrepuserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-draggable modal-dialog-help" role="document">
    <div class="modal-content">
      <div class="modal-header alert-info">
        <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="salesrepuserModalLabel"><span id="salesrepuserModalTitle"></span></h4>
      </div>
      <div class="modal-body" id="price_rule">

<input type="hidden" value="{{$salesrep->id}}" name="salesrepuser_salesrep_id" id="salesrepuser_salesrep_id">
<input type="hidden" value="" name="salesrepuser_id" id="salesrepuser_id">
<input type="hidden" value="" name="salesrepuser_action" id="salesrepuser_action">
{{-- <input type="hidden" value="salesrepuser" name="tab_name" id="tab_name"> --}}


    <div id="msg-salesrepuser-error" class="alert alert-danger" style="display:none">

        <ul></ul>

    </div>


  <div class="row">
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('salesrepuser_firstname', l('Name', 'salesrepusers')) !!}
    {!! Form::text('salesrepuser_firstname', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('salesrepuser_lastname', l('Surname', 'salesrepusers')) !!}
    {!! Form::text('salesrepuser_lastname', null, array('class' => 'form-control')) !!}
</div><br />
</div>

<div class="row">
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('salesrepuser_email', l('Email', 'salesrepusers')) !!}
    {!! Form::text('salesrepuser_email', null, array('xplaceholder' => l('your@email.com'), 'class' => 'form-control', 'required' => 'required')) !!}
</div>
<div class="form-group col-lg-6 col-md-6 col-sm-6">
    {!! Form::label('password', l('Password', 'salesrepusers')) !!}
    {!! Form::text('password', null, array('id' => 'password', 'class' => 'form-control',  "autocomplete" => "off", 'class' => 'form-control', 'required' => 'required')) !!}
</div>
</div>

<div class="row">

           <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-active">
             {!! Form::label('salesrepuser_active', l('Allow Sales Representative Center access?', 'salesrepusers'), ['class' => 'control-label']) !!}
             <div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('salesrepuser_active', '1', true, ['id' => 'salesrepuser_active_on']) !!}
                   {!! l('Yes', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('salesrepuser_active', '0', false, ['id' => 'salesrepuser_active_off']) !!}
                   {!! l('No', [], 'layouts') !!}
                 </label>
               </div>
             </div>
           </div>

</div>

<div class="row" style="border-top: 1px solid #dddddd; padding-top: 15px;">

      <div class="form-group col-lg-4 col-md-4 col-sm-4">
          {!! Form::label('salesrepuser_language_id', l('Language', 'salesrepusers')) !!}
          {!! Form::select('salesrepuser_language_id', $languageList, null, array('class' => 'form-control')) !!}
      </div>

      <div class="form-group col-lg-4 col-md-4 col-sm-4">
          {!! Form::label('allow_abcc_access', l('Allow give Customers ABCC access?', 'salesrepusers'), ['class' => 'control-label']) !!}
             <div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('allow_abcc_access', '1', NULL, ['id' => 'allow_abcc_access_on']) !!}
                   {!! l('Yes', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('allow_abcc_access', '0', NULL, ['id' => 'allow_abcc_access_off']) !!}
                   {!! l('No', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('allow_abcc_access', '-1', NULL, ['id' => 'allow_abcc_access_default']) !!}
                   {!! l('Default', [], 'layouts') !!}
                 </label>
               </div>
             </div>
      </div>


                   <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-notify_salesrep">
                     {!! Form::label('notify_salesrep', l('Notify Sales Representative? (by email)'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('notify_salesrep', '1', true, ['id' => 'notify_salesrep_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('notify_salesrep', '0', false, ['id' => 'notify_salesrep_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>
        </div>  




      </div>

      <div class="modal-footer">

               <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
               <button type="submit" class="btn btn-success" name="modal_salesrepuserSubmit" id="modal_salesrepuserSubmit">
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



          $("body").on('click', "#modal_salesrepuserSubmit", function() {

            var id = $('#salesrepuser_id').val();

            var action = $('#salesrepuser_action').val();
//            var url = "{{ route('salesrepusers.store') }}";
            var token = "{{ csrf_token() }}";

            // Prevent double submit
            $("#modal_salesrepuserSubmit").attr( {"disabled" : "disabled"} );

            switch( action ) {
              case 'create':
                // code block
                method = 'POST';
                url = "{{ route('salesrepusers.store') }}";
                break;
              case 'update':
                // code block
                method = 'PATCH';
                url = "{{ route('salesrepusers.update', ':id') }}";
                url = url.replace(':id', id);
                break;
              default:
                // code block
                displayErrorMsg(['{{ l('No action is taken &#58&#58 (:id) ', ['id' => ''], 'layouts') }}']);

                return ;
            } 

            var payload = {
                              action : action,
                              sales_rep_id : $('#salesrepuser_salesrep_id').val(),
                              salesrep_user_id : $('#salesrepuser_id').val(),

                              firstname : $('#salesrepuser_firstname').val(),
                              lastname :  $('#salesrepuser_lastname').val(),
                              email :     $('#salesrepuser_email').val(),
                              password :  $('#password').val(),

                              language_id :     $('#salesrepuser_language_id').val(),

                              active : $("input[name='salesrepuser_active']:checked").val(),
                              allow_abcc_access : $("input[name='allow_abcc_access']:checked").val(),

                              notify_salesrep : $("input[name='notify_salesrep']:checked").val(),
                          };

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : method,
                dataType : 'json',
                data : payload,

                success: function(data){
                    
                    if ( $.isEmptyObject(data.error) ) {

                        getSalesRepUsers();

                        $(function () {  $('[data-toggle="tooltip"]').tooltip()});

                        $('#salesrepuserModal').modal('toggle');

                        showAlertDivWithDelay("#msg-salesrepuser-success");
                    
                    } else {

                        displayErrorMsg(data.error);

                    }
                    // Re-enable
                    // https://stackoverflow.com/questions/54376603/jquery-ajax-disable-submit-button-until-form-has-been-completed
                    $("#modal_salesrepuserSubmit").prop( "disabled", false );
                }
            });

        });


        function displayErrorMsg (msg) {

            $("#msg-salesrepuser-error").find("ul").empty(  );

            if (typeof msg === 'string' || msg instanceof String)
            {
                // it's a string
                $("#msg-salesrepuser-error").find("ul").append('<li>'+msg+'</li>');

            } else {
                // it's something else
                $.each( msg, function( key, value ) {

                    $("#msg-salesrepuser-error").find("ul").append('<li>'+value+'</li>');

                });
            }

            $("#msg-salesrepuser-error").css('display','block');

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




