@section('modals')

@parent

   <div class="modal" id="feedbackForm">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               <h4 class="modal-title">{{l('Ask, Report an error or give us an Idea', [], 'layouts')}}</h4>
            </div>

<form name="f_feedback" id="f_feedback" class="form" role="form">
<input type="hidden" name="feedback_info" value="{system_info()}"/>
<input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-body" id="modal-body-feedback">

                <div class="alert alert-danger print-error-msg-feedback" style="display:none">
                    <ul></ul>
                </div>

               <p>
                  {{l('Your feed-back is welcome.', [], 'layouts')}}
               </p>
               <div class="form-group">
                  <label for="notes_feedback">{{l('Your Comments', [], 'layouts')}}</label>
                  <textarea id="notes_feedback" class="form-control" name="notes_feedback" rows="6"></textarea>
               </div>
               <div class="form-group">
                  <label for="email_feedback">{{l('Email', [], 'layouts')}}</label>
                  <input type="email" class="form-control" id="email_feedback" name="email_feedback" value=""/>
               </div>
               <div class="form-group">
                  <label for="name_feedback">{{l('Name', [], 'layouts')}}</label>
                  <input class="form-control" id="name_feedback" name="name_feedback" value=""/>
               </div>
            </div>
            <div class="modal-footer" id="modal-footer-feedback">
               <button type="submit" name="submit_feedback" id="submit_feedback" class="btn btn-sm btn-primary" onclick="this.disabled=true;">
                  <i class="fa fa-send"></i>
                  &nbsp; {{l('Send', [], 'layouts')}}
               </button>
            </div>
</form>
            <div class="modal-body" id="modal-body-feedback_success" style="display:none">
                <div class="alert alert-success">{{ l('Your email has been sent!', [], 'layouts') }}</div>
            </div>
            <div class="modal-footer" id="modal-footer-feedback_success" style="display:none">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">{{ l('Continue', [], 'layouts') }}</button>
            </div>

         </div>
      </div>
   </div>


@endsection
@section('scripts')

@parent

<script type="text/javascript">

        // See master.blade.php
        $(function(){
           $("#f_feedback").on('submit', function(e){  // ToDo: check fields before submit
              e.preventDefault();
              $("#name_feedback").addClass('loading');
              $.post("{{ URL::to('mail/feedback') }}", $(this).serialize(), function(data){
                 $("#name_feedback").removeClass('loading');

                  if($.isEmptyObject(data.error)){

                    console.log(data.success);

                     $("#modal-body-feedback").hide();
                     $("#modal-footer-feedback").hide();
                     $("#modal-body-feedback_success").show();
                     $("#modal-footer-feedback_success").show();

                  } else {

                     printErrorMsg(data.error);

                  }

                 $("#submit_feedback").removeAttr('disabled');
              });
           });

                  function printErrorMsg (msg) {

                        $(".print-error-msg-feedback").find("ul").html('');

                        $(".print-error-msg-feedback").css('display','block');

                        $.each( msg, function( key, value ) {

                          $(".print-error-msg-feedback").find("ul").append('<li>'+value+'</li>');

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
