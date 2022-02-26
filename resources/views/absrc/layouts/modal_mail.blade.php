@section('modals')

@parent

   <div class="modal" id="mailConfirmModal" style="display:none">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               <h4 class="modal-title">{{l('Send an Email', [], 'layouts')}}</h4>
            </div>

<form name="f_sendEmail" id="f_sendEmail" class="form" role="form">
<input type="hidden" name="feedback_info" value="{system_info()}"/>
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <div class="modal-body" id="modal-body-mail">

                <div class="alert alert-danger print-error-msg" style="display:none">
                    <ul></ul>
                </div>

      <div class="row">
         <div class="col-md-6">
               <div class="form-group">
                  <label for="name">{{l('To (name)', [], 'layouts')}}</label>
                  <input class="form-control" id="to_name" name="to_name" value=""/>
               </div>
         </div>
         <div class="col-md-6">
               <div class="form-group">
                  <label for="email">{{l('To (email)', [], 'layouts')}}</label>
                  <input type="email" class="form-control" id="to_email" name="to_email" value=""/>
               </div>
         </div>
      </div>

               <div class="form-group">
                  <label for="subject">{{l('Subject', [], 'layouts')}}</label>
                  <input class="form-control" id="subject" name="subject" value=""/>
               </div>
               <div class="form-group">
                  <label for="message">{{l('Your Message', [], 'layouts')}}</label>
                  <textarea id="message" class="form-control" name="message" rows="6"></textarea>
               </div>

      <div class="row">
         <div class="col-md-6">
               <div class="form-group">
                  <label for="name">{{l('From (name)', [], 'layouts')}}</label>
                  <input class="form-control" id="from_name" name="from_name" value=""/>
               </div>
         </div>
         <div class="col-md-6">
               <div class="form-group">
                  <label for="email">{{l('From (email)', [], 'layouts')}}</label>
                  <input type="email" class="form-control" id="from_email" name="from_email" value=""/>
               </div>
         </div>
      </div>

            </div>
            <div class="modal-footer" id="modal-footer-mail">
               <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
               <button type="submit" name="submit_mail" id="submit_mail" class="btn btn-sm btn-primary" onclick="this.disabled=true;">
                  <i class="fa fa-send"></i>
                  &nbsp; {{l('Send', [], 'layouts')}}
               </button>
            </div>
</form>
            <div class="modal-body" id="modal-body-mail_success" style="display:none">
                <div class="alert alert-success">{{ l('Your email has been sent!', [], 'layouts') }}</div>
            </div>
            <div class="modal-footer" id="modal-footer-mail_success" style="display:none">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">{{ l('Continue', [], 'layouts') }}</button>
            </div>

         </div>
      </div>
   </div>


@endsection
@section('scripts')

@parent

<script type="text/javascript">
    $(document).ready(function () {
        $('.mail-item').click(function (evnt) {
            var href = $(this).attr('href');
            var message = $(this).attr('data-content');
            var to_email = $(this).attr('data-to_email');
            var to_name  = $(this).attr('data-to_name');
            var from_email = $(this).attr('data-from_email');
            var from_name  = $(this).attr('data-from_name');
            
            $('#to_email').val(to_email);
            $('#to_name').val(to_name);
            $('#subject').val('');
            $('#message').val('');
            $('#from_email').val(from_email);
            $('#from_name').val(from_name);
            $('#f_sendEmail').attr('action', href);

            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").hide();

            $("#subject").removeClass('loading');
//            $("#submit_mail").attr('disabled','disabled');
            $("#submit_mail").removeAttr('disabled');
            
            $("#modal-body-mail_success").hide();
            $("#modal-footer-mail_success").hide();
            $("#modal-body-mail").show();
            $("#modal-footer-mail").show();

            $('#mailConfirmModal').modal({show: true});
            document.f_sendEmail.subject.focus();
            return false;
        });
    });


        // See master.blade.php
        $(function(){
           $("#f_sendEmail").on('submit', function(e){  // ToDo: check fields before submit
              e.preventDefault();
              $("#subject").addClass('loading');
              $.post("{{ URL::to('absrc/mail') }}", $(this).serialize(), function(data){
                 $("#subject").removeClass('loading');

                  if($.isEmptyObject(data.error)){

                    console.log(data.success);

                     $("#modal-body-mail").hide();
                     $("#modal-footer-mail").hide();
                     $("#modal-body-mail_success").show();
                     $("#modal-footer-mail_success").show();

                  } else {

                     printErrorMsg(data.error);

                  }

                 $("#submit_mail").removeAttr('disabled');
              });
           });

                  function printErrorMsg (msg) {

                        $(".print-error-msg").find("ul").html('');

                        $(".print-error-msg").css('display','block');

                        $.each( msg, function( key, value ) {

                          $(".print-error-msg").find("ul").append('<li>'+value+'</li>');

                        });

                }
        });
</script>

@endsection

@section('styles')
@parent

<style>
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }
</style>

@endsection