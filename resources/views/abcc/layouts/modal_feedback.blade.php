
   <div class="modal" id="contactForm">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               <h4 class="modal-title">{{l('Contactar con el Administrador', [], 'abcc/layouts')}}</h4>
            </div>

<form name="f_feedback" id="f_feedback" class="form" role="form">
<input type="hidden" name="feedback_info" value=""/>
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <div class="modal-body" id="modal-body">
               <div id="error"></div>
               <p class="hide">
                  {{l('Your feed-back is welcome.', [], 'layouts')}}
               </p>
               <div class="form-group">
                  <label for="notes">{{l('Your Comments', [], 'layouts')}}</label>
                  <textarea id="notes" class="form-control" name="notes" rows="6"></textarea>
               </div>
               <div class="form-group">
                  <label for="email">{{l('Email', [], 'layouts')}}</label>
                  <div class="form-control">{{ Auth::user()->email }}</div>
               </div>
               <div class="form-group">
                  <label for="name">{{l('Name', [], 'layouts')}}</label>
                  <div class="form-control">{{ Auth::user()->getFullName() }}</div>
               </div>
            </div>

<input type="hidden" id="email2" name="email2" value="dcomobject@hotmail.com">
<input type="hidden" id="email" name="email" value="{{ Auth::user()->email }}">
<input type="hidden" id="name" name="name" value="{{ Auth::user()->getFullName() }}">
<!-- input type="hidden" name="" value="">
<input type="hidden" name="" value="">
<input type="hidden" name="" value="">
<input type="hidden" name="" value="" -->

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



@section('scripts')  @parent 

 <script type="text/javascript">

     $(function(){
        $("#f_feedback").on('submit', function(e){
           e.preventDefault();

           $("#modal-footer").html('<button type="button" class="btn btn-sm btn-primary" disabled="disabled"><i class="fa fa-spinner fa-spin"></i> {{ l('Sending...', [], 'layouts') }}</button>');

           $.post("{{ route('abcc.contact') }}", $(this).serialize(), function(data){

              if (data.status == 'ERROR') {
                 $("#error").addClass("alert alert-danger");
                 $("#error").html('<a class="close" data-dismiss="alert" href="#">×</a><li class="error">{{ l('There was an error. Your message could not be sent.', [], 'layouts') }}</li><li class="error">'+data.message+'</li>');
              } else {
                  // Reset form
                  $("#notes").val('');
                  // $("#email").val('');
                  // $("#name").val('');
                  // 
                  $("#modal-body").html('<div class="alert alert-success">{{ l('Your email has been sent!', [], 'layouts') }}</div>');
                  $("#modal-footer").html('<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">{{ l('Continue', [], 'layouts') }}</button>');
              }
           });
        });
     });

 </script>

@endsection
