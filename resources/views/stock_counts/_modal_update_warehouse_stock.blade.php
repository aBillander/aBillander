@section('modals')

@parent

<div class="modal fade" id="stockCountUpdateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalSetDefaultLabel"></h4>
            </div>

            {{-- Form::hidden('line_type',           null, array('id' => 'line_type'          )) --}}

            {{ Form::hidden('href',           null, array('id' => 'href'          )) }}
            {{ Form::hidden('wsname',           null, array('id' => 'wsname'          )) }}

            <div id="stockCountUpdateModal_modal-body" class="modal-body"></div>
            <div id="stockCountUpdateModal_postbox" class="modal-body hidden">
                <div id="process_log"></div>
                <h3>{{l('Progress', [], 'layouts')}} <span id="process_progress_percent">0</span>%</h3>
                <progress id="process_progress" style="width:100%;" max="100" value="0"></progress>
            </div>


{{--

    <div id="fsx_progress" style="display: none;">
        <p id="process_run"><span id="process_spinner" class="spinner" style="float:left;"></span> El Catálogo se está actualizando.</p>
        <p id="process_done" style="display: none;"></span> <b>El Catálogo se ha actualizado.</b></p>
        <p id="process_exceeded" style="display: none;"></span> <b>El Catálogo NO se ha actualizado completamente, porque se ha alcanzado el número máximo de ciclos permitido (< ? php echo intval(get_option('FSX_MAX_ROUNDCYCLES')); ? >).</b></p>
        <div class="postbox" style="margin:1em 0 0 0;">
            <div class="inside">
                <div id="import_log">
                </div>
                <progress id="fsx_import_progress" style="width:100%;" max="100" value="0"></progress>
            </div>
        </div>
    </div>

--}}


            <div class="modal-footer" id="footer-regular">

                <button type="button" class="btn btn-link" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>

               <button type="submit" class="btn btn-success" name="modal_update-warehouse-stockSubmit" id="modal_update-warehouse-stockSubmit">
                <i class="fa fa-thumbs-up"></i>
                &nbsp; {{l('Update', [], 'layouts')}}</button>

            </div>
            <div class="modal-footer hidden" id="footer-running">

               <div id="loading" class="loading text-left"> &nbsp; &nbsp; &nbsp; &nbsp; {{ l('Processing...', 'layouts') }}</div>

            </div>
            <div class="modal-footer hidden" id="footer-done">

                <button type="button" class="btn btn-link" data-dismiss="modal" id="modal_Cancel">{{l('Cancel', [], 'layouts')}}</button>

                <a class="btn btn-success" href="" id="modal_update-warehouse-stockRedirect"><i class="fa fa-external-link"></i>
                &nbsp; {{l('Show Log', 'layouts')}}</a>

            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts') @parent 

<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '.update-warehouse-stock', function(evnt) { 
 //       $('.delete-item').click(function (evnt) {
            var href = $(this).attr('href');
            var message = $(this).attr('data-content');
            var title = $(this).attr('data-title');
            var wsname = $(this).attr('data-wsname');
            $('#myModalSetDefaultLabel').text(title);
            $('#stockCountUpdateModal_modal-body').html(message);
            $('#href').val(href);
            $('#wsname').val(wsname);
            $('#stockCountUpdateModal').modal({show: true, backdrop: 'static', keyboard: false});
            // See: https://stackoverflow.com/questions/22207377/disable-click-outside-of-bootstrap-modal-area-to-close-modal/30658435
            return false;
        });


        $("body").on('click', "#modal_update-warehouse-stockSubmit", function() {

            $('#footer-regular').slideUp();
            $('#footer-running').removeClass('hidden');

            var payload = { 
                              
                          };

            $('#stockCountUpdateModal_postbox').removeClass('hidden');

            doAjaxStockUpdate(payload);
//            e.preventDefault();

        });
    });


function doAjaxStockUpdate(payload)
{

            // var id = $('#line_id').val();
            var url = $('#href').val();
            var token = "{{ csrf_token() }}";

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function(response){

                    var newPayload = response;

                    console.log(response);

                    $('#modal_update-warehouse-stockRedirect').attr('href', response.url_to);

                    $('#process_progress').val(newPayload.progress);
                    $('#process_progress_percent').html(newPayload.progress);

            if (newPayload.messages.informations && newPayload.messages.informations.length > 0) {
                $.each(newPayload.messages.informations, function( index, value ) {
                    $('#process_log').append('<div class="alert xalert-info alert-block" style="color: #333333; background-color: #f5f5f5; border-color: #dddddd;"> '+value+' </div>');
                });
            }
            if (newPayload.messages.warnings && newPayload.messages.warnings.length > 0) {
                $.each(newPayload.messages.warnings, function( index, value ) {
                    $('#process_log').append('<div class="alert alert-warning alert-block"> '+value+' </div>');
                });
            }
            if (newPayload.messages.errors && newPayload.messages.errors.length > 0) {
                $.each(newPayload.messages.errors, function( index, value ) {
                    $('#process_log').append('<div class="alert alert-danger alert-block"> '+value+' </div>');
                });
            }

            // = 1  Terminado
            if (newPayload.iamdone == 1)
            {
                // jQuery('#process_run').slideUp();
                // jQuery('#process_done').show();
                // location.reload(true);

                    if (response.errors>0) 
                    {
                        $('#modal_Cancel').addClass('hidden');
                        $('#modal_update-warehouse-stockRedirect').removeClass('btn-success');
                        $('#modal_update-warehouse-stockRedirect').addClass('btn-danger');
                    }

                    // If Cancel, page should refresh. Mmmmm! Better go to LOG
                    $('#modal_Cancel').addClass('hidden');
                    
                    $('#footer-running').addClass('hidden');

                    if (response.errors>0) 
                    {
                        $('#stockCountUpdateModal_modal-body').append('<div class="alert alert-danger alert-block">{!! l('This record has been updated with errors &#58&#58 (:id) Check the Log ', ['id' => ''], 'layouts') !!}'+$('#wsname').val()+'</div>');
                    }

                    if (response.warnings>0) 
                    {
                        $('#stockCountUpdateModal_modal-body').append('<div class="alert alert-warning alert-block">{!! l('This record has been updated with warnings &#58&#58 (:id) Check the Log ', ['id' => ''], 'layouts') !!}'+$('#wsname').val()+'</div>');
                    }
                    
                    if (response.errors==0 && response.warnings==0)
                    {
                        $('#stockCountUpdateModal_modal-body').append('<div class="alert alert-info alert-block">{!! l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}'+$('#wsname').val()+'</div>');
                    }

                    $('#footer-done').removeClass('hidden');

            } 
            // = -1  máximos ciclos excedidos (aborted)
            else if (newPayload.iamdone == -1)
            {
                $('#process_run').slideUp();
                $('#process_exceeded').show();
                // location.reload(true);
            } 
            // The show must go on
            else
            {
                // newPayload.action = 'update_catalogue';
                doAjaxStockUpdate(newPayload);
            } 

                    
                },
                error: function(data)
                {
                    console.log(data);
                    // alert(data.error);
                    $('#process_log').prepend('<div class="alert alert-danger alert-block">'+ data.responseJSON.message +'</div>');
                    $('#process_log').prepend('<div class="alert alert-danger alert-block"><h2>Something went wrong. The stack trace is printed below</h2></div>');

                    $('#modal_update-warehouse-stockRedirect').removeClass('btn-success');
                    $('#modal_update-warehouse-stockRedirect').addClass('btn-warning');

                    if ( $('#modal_update-warehouse-stockRedirect').attr('href') == '' )
                        $('#modal_update-warehouse-stockRedirect').addClass('hidden');

                    // If Cancel, page should refresh. Mmmmm! Better go to LOG
                    // $('#modal_Cancel').addClass('hidden');
                    
                    $('#footer-running').addClass('hidden');
                    $('#footer-done').removeClass('hidden');
                }
            });
}
</script>

@endsection


@section('styles')    @parent

<style>

  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }

</style>

@endsection
