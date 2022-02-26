@section('modals')

@parent

<div class="modal fade" id="dataConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
                {!! Form::open(array('class' => 'pull-right', 'id' => 'action')) !!}

                <input type="hidden" value="" name="delete_previous_anchor" id="delete_previous_anchor">
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
        $('body').on('click', '.confirm-duplicate', function(evnt) { 
 //       $('.delete-item').click(function (evnt) {
            var href = $(this).attr('href');
            var oid = $(this).attr('oid');
            var previous_anchor = $(this).attr('data-previous_anchor');
            var message = $(this).attr('data-content');
            var title = $(this).attr('data-title');

            $('#myModalLabel').html(title);
            $('#dataConfirmModal .modal-body').html(message);
            $('#action').attr('action', href);
            $('#delete_previous_anchor').val(previous_anchor);
            $('#dataConfirmModal').modal({show: true});
            return false;
        });
    });
</script>

@endsection