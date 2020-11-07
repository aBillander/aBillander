@section('modals')

@parent

<div class="modal fade" id="modalDocumentUndoInvoice" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modalDocumentUndoInvoiceLabel"></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <input type="hidden" id="delete_line_id">

                <button type="button" class="btn btn-link" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
                {!! Form::open(array('class' => 'pull-right', 'id' => 'undo-action')) !!}
                {!! Form::submit(l('Confirm', [], 'layouts'), array('class' => 'btn btn-danger')) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts') @parent 

<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '.undo-invoice-document', function(evnt) { 
            var href = $(this).attr('data-href');
            var message = $(this).attr('data-content');
            var title = $(this).attr('data-title');

            $('#modalDocumentUndoInvoiceLabel').text(title);
            $('#modalDocumentUndoInvoice .modal-body').text(message);
            $('#undo-action').attr('action', href);
            $('#modalDocumentUndoInvoice').modal({show: true});
            return false;
        });
    });

</script>

@endsection