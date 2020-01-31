@section('modals')

@parent

<div class="modal fade" id="dataConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalDeleteLabel"></h4>
            </div>
                {!! Form::open(array('xclass' => 'pull-right', 'id' => 'action')) !!}
            <div class="modal-body message"></div>
            <div class="modal-body {{ $document->created_via == 'aggregate_orders' ? '' : 'hide' }}">

        <div class="row hide">

                   <div class="form-group col-lg-12 col-md-12 col-sm-12" id="div-open_parents">
                     {!! Form::label('open_parents', l('Open parent Documents?'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('open_parents', '1', false, ['id' => 'open_parents_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('open_parents', '0', true, ['id' => 'open_parents_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>
        </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
                {!! Form::hidden('_method', 'DELETE') !!}
                {!! Form::submit(l('Confirm', [], 'layouts'), array('class' => 'btn btn-danger')) !!}
            </div>
                {!! Form::close() !!}
        </div>
    </div>
</div>

@stop
@section('scripts') @parent 

<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '.delete-item', function(evnt) { 
 //       $('.delete-item').click(function (evnt) {
            var href = $(this).attr('href');
            var message = $(this).attr('data-content');
            var title = $(this).attr('data-title');
            $('#myModalDeleteLabel').text(title);
            $('#dataConfirmModal .message').text(message);
            $('#action').attr('action', href);
            $('#dataConfirmModal').modal({show: true});
            return false;
        });
    });
</script>

@stop