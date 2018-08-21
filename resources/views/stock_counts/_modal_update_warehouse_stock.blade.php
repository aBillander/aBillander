@section('modals')

@parent

<div class="modal fade" id="dataSetDefaultModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalSetDefaultLabel"></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
                {!! Form::open(array('class' => 'pull-right', 'id' => 'actionSetDefault')) !!}

                {!! Form::submit(l('Confirm', [], 'layouts'), array('class' => 'btn btn-success')) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@stop
@section('scripts') @parent 

<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '.update-warehouse-stock', function(evnt) { 
 //       $('.delete-item').click(function (evnt) {
            var href = $(this).attr('href');
            var message = $(this).attr('data-content');
            var title = $(this).attr('data-title');
            $('#myModalSetDefaultLabel').text(title);
            $('#dataSetDefaultModal .modal-body').text(message);
            $('#actionSetDefault').attr('action', href);
            $('#dataSetDefaultModal').modal({show: true});
            return false;
        });
    });
</script>

@stop