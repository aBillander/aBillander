@section('modals')

@parent

   <div class="modal" id="mailInviteModal" style="display:none">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               <h4 class="modal-title">{{l('Send an Invitation Email')}}</h4>
            </div>

<form name="f_sendInvitationEmail" id="f_sendInvitationEmail" class="form" role="form">
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <div class="modal-body" id="modal-body-invitation-mail">

                <div class="alert alert-danger print-invitation-error-msg" style="display:none">
                    <ul></ul>
                </div>

      <div class="row">
         <div class="col-md-6">
               <div class="form-group">
                  <label for="invitation_to_name">{{l('To (name)', [], 'layouts')}}</label>
                  <input class="form-control" id="invitation_to_name" name="invitation_to_name" value=""/>
               </div>
         </div>
         <div class="col-md-6">
               <div class="form-group">
                  <label for="invitation_to_email">{{l('To (email)', [], 'layouts')}}</label>
                  <input class="form-control" id="invitation_to_email" name="invitation_to_email" value=""/>
               </div>
         </div>
      </div>

               <div class="form-group">
                  <label for="invitation_subject">{{l('Subject', [], 'layouts')}}</label>
                  <input class="form-control" id="invitation_subject" name="invitation_subject" value=""/>
               </div>
               <div class="form-group">
                  <label for="invitation_message">{{l('Your Message', [], 'layouts')}}</label>
                  <textarea id="invitation_message" class="form-control" name="invitation_message" rows="6"></textarea>
               </div>

      <div class="row">
         <div class="col-md-6">
               <div class="form-group">
                  <label for="invitation_from_name">{{l('From (name)', [], 'layouts')}}</label>
                  <input class="form-control" id="invitation_from_name" name="invitation_from_name" value=""/>
               </div>
         </div>
         <div class="col-md-6">
               <div class="form-group">
                  <label for="invitation_from_email">{{l('From (email)', [], 'layouts')}}</label>
                  <input type="email" class="form-control" id="invitation_from_email" name="invitation_from_email" value=""/>
               </div>
         </div>
      </div>

            </div>
            <div class="modal-footer" id="modal-footer-invitation-mail">
               <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
               <button type="submit" name="invitation_submit_mail" id="invitation_submit_mail" class="btn btn-sm btn-primary" onclick="this.disabled=true;">
                  <i class="fa fa-send"></i>
                  &nbsp; {{l('Send', [], 'layouts')}}
               </button>
            </div>
</form>
            <div class="modal-body" id="modal-body-invitation-mail-success" style="display:none">
                <div class="alert alert-success">{{ l('Your email has been sent!', [], 'layouts') }}</div>
            </div>
            <div class="modal-footer" id="modal-footer-invitation-mail-success" style="display:none">
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
        $('.invite-customer').click(function (evnt) {
            var href = $(this).attr('href');
            var message = $(this).attr('data-content');
            var to_email = $(this).attr('data-to_email');
            var to_name  = $(this).attr('data-to_name');
            var from_email = $(this).attr('data-from_email');
            var from_name  = $(this).attr('data-from_name');

            var subject = '{!! l(' :_> :company invites you to his Customer Center', ['company' => \App\Context::getcontext()->company->name_fiscal], [], 'layouts') !!}';
            
            var invitation_message = '';

            // invitation_message = '\n\n'+ invitation_message;
            
            $('#invitation_to_email').val(to_email);
            $('#invitation_to_name').val(to_name);
            $('#invitation_subject').val(subject);
            $('#invitation_message').val(invitation_message);
            $('#invitation_from_email').val(from_email);
            $('#invitation_from_name').val(from_name);
            $('#f_sendInvitationEmail').attr('action', href);

            $(".print-invitation-error-msg").find("ul").html('');
            $(".print-invitation-error-msg").hide();

            $("#invitation_subject").removeClass('loading');
//            $("#submit_mail").attr('disabled','disabled');
            $("#invitation_submit_mail").removeAttr('disabled');
            
            $("#modal-body-invitation-mail-success").hide();
            $("#modal-footer-invitation-mail-success").hide();
            $("#modal-body-invitation-mail").show();
            $("#modal-footer-invitation-mail").show();

            $('#mailInviteModal').modal({show: true});
            document.f_sendInvitationEmail.invitation_subject.focus();
            return false;
        });
    });


        // See master.blade.php
        $(function(){
           $("#f_sendInvitationEmail").on('submit', function(e){  // ToDo: check fields before submit
              e.preventDefault();
              $("#invitation_subject").addClass('loading');
              $.post( $('#f_sendInvitationEmail').attr('action') , $(this).serialize(), function(data){
                 $("#invitation_subject").removeClass('loading');

                  if($.isEmptyObject(data.error)){

                    console.log(data.success);

                     $("#modal-body-invitation-mail").hide();
                     $("#modal-footer-invitation-mail").hide();
                     $("#modal-body-invitation-mail-success").show();
                     $("#modal-footer-invitation-mail-success").show();

                  } else {

                     printInvitationErrorMsg(data.error);

                  }

                 $("#invitation_submit_mail").removeAttr('disabled');
              });
           });

                  function printInvitationErrorMsg (msg) {

                        $(".print-invitation-error-msg").find("ul").html('');

                        $(".print-invitation-error-msg").css('display','block');

                        $.each( msg, function( key, value ) {

                          $(".print-invitation-error-msg").find("ul").append('<li>'+value+'</li>');

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