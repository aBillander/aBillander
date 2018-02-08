
   <div class="modal" id="contactForm">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               <h4 class="modal-title">{{l('Ask, Report an error or give us an Idea', [], 'layouts')}}</h4>
            </div>

<form name="f_feedback" id="f_feedback" class="form" role="form">
<input type="hidden" name="feedback_info" value="{system_info()}"/>
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <div class="modal-body" id="modal-body">
               <div id="error"></div>
               <p>
                  {{l('Your feed-back is welcome.', [], 'layouts')}}
               </p>
               <div class="form-group">
                  <label for="notes">{{l('Your Comments', [], 'layouts')}}</label>
                  <textarea id="notes" class="form-control" name="notes" rows="6">{{-- if condition="$fsc->empresa"}{$fsc->empresa->email_firma}{/if --}}</textarea>
               </div>
               <div class="form-group">
                  <label for="email">{{l('Email', [], 'layouts')}}</label>
                  <input type="email" class="form-control" id="email" name="email" value=""/>
               </div>
               <div class="form-group">
                  <label for="name">{{l('Name', [], 'layouts')}}</label>
                  <input class="form-control" id="name" name="name" value=""/>
               </div>
            </div>
            <div class="modal-footer" id="modal-footer">
               <button type="submit" class="btn btn-sm btn-primary">
                  <i class="fa fa-send"></i>
                  &nbsp; {{l('Send', [], 'layouts')}}
               </button>
            </div>
</form>

         </div>
      </div>
   </div>
