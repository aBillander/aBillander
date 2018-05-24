
@section('modals')

@parent

<div class="modal fade" id="invoiceConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myinvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myinvoiceModalLabel"></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button> 
                <button type="button" id="submitForm" class="btn btn-danger">{{l('Confirm', [], 'layouts')}}</button>
            </div>
        </div>
    </div>
</div>

@stop
@section('scripts') @parent 

<script type="text/javascript">
    $(document).ready(function () {
        $('.save-invoice').click(function (evnt) {
//            var href = $(this).attr('href');
            var message = $(this).attr('data-content');
            var title = $(this).attr('data-title');
            var id = $(this).attr('data-id');
            $('#myinvoiceModalLabel').text(title);
            $('#invoiceConfirmModal .modal-body').text(message);
//            $('#action').attr('action', href);
            $('#invoiceConfirmModal').modal({show: true});
            return false;
        });

        $("#submitForm").on('click', function() {
 //           alert($('#save_as').val());
            $(this).addClass('disabled');
            $('#save_as').val('invoice');
//            alert($('#save_as').val());
            $("#f_new_order").submit();
        });
    });

</script>

@stop