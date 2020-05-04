@section('modals')

@parent

<div class="modal fade" id="modal-confirm-submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modal-confirm-label"></h4>
            </div>
            <div class="modal-body">
            <div class="modal-body-message">
            </div>

    <div class="xform-group" id="submit-confirmation_email" style="margin-top: 15px;">

      <div class="row">

      <div class="col-lg-6 col-md-6 col-sm-6">
        
        <div class="checkbox">
          <label>
            {!! Form::checkbox('confirmation_email', '1', false, ['id' => 'confirmation_email']) !!} {{ l('Send me a confirmation email') }}
          </label>
        </div>
      </div>

         <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('reference') ? 'has-error' : '' }}">
            <label for="sales_equalization" class="control-label">{{ l('My Reference / Project') }}</label>
            {!! Form::text('reference', old('reference'), array('class' => 'form-control', 'id' => 'reference')) !!}
            {!! $errors->first('reference', '<span class="help-block">:message</span>') !!}
         </div>
    </div>

      </div>
                
            </div>
            <div class="modal-footer">
                <input id="form_id" name="form_id" type="hidden" value="">

                <button type="button" class="btn btn-link" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
                <button type="button" id="submit-" class="btn btn-success success"><i class="fa fa-thumbs-up"></i> &nbsp; {{ l('Confirm', [], 'layouts' )}}<//button>

                <button type="button" id="submit-ko" class="btn btn-default" data-dismiss="modal">{{l('Continue', [], 'layouts')}}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts') @parent 

<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '.confirm-', function(evnt) {
            var form_id = $(this).parents('form').attr('id');
            var message_ok = "{{l('You are going to Confirm your Order. Are you sure?')}}";    // $(this).attr('data-content');
            var message_ko = "{{l('You can not Confirm your Order at this moment.')}}";
            var title = $(this).attr('data-title');

            $('#form_id').val(form_id);

            $('#modal-confirm-label').text(title);

            if ( $('#is_billable').val() > 0 )
            {
                    $('#modal-confirm-submit .modal-body-message').text(message_ok);

                    $('#confirmation_email').prop('checked', false);
                    $('#submit-confirmation_email').show();

                    $('#submit-').show();
                    $('#submit-ko').hide();

            } else {

                    $('#modal-confirm-submit .modal-body-message').html(message_ko+' '+$("#can_min_order")[0].outerHTML);

                    $('#submit-confirmation_email').hide();

                    $('#submit-').hide();
                    $('#submit-ko').show();

            }

            $('#modal-confirm-submit').modal({show: true});
            return false;
        });

        $('#submit-').click(function(){
             /* when the submit button in the modal is clicked, submit the form */
            // alert($('#form_id').val());
            $(this).prop('disabled', true);

            $('#send_confirmation_email').val($('#confirmation_email').prop('checked'));
            $('#reference_customer').val($('#reference').val());

            $('#'+$('#form_id').val()).submit();
        });

    });
</script>

{{-- See: https://www.jqueryscript.net/other/Lightweight-jQuery-Confirmation-Modal-For-Bootstrap.html --}}

@endsection
