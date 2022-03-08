@section('modals')

@parent

<div class="modal fade" id="ProductionOrderDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="ProductionOrderDeleteLabel">{{ l('Delete Production Order') }}: <span id="production_order_id"></span></h4>
            </div>
                {!! Form::open(array('id' => 'ProductionOrderDelete_action')) !!}
                {{-- !! Form::hidden('_method', 'DELETE') !! --}}
            <div class="modal-body">
                <div class="row">

                {!! Form::hidden('current_production_sheet_id', $sheet->id) !!}

                     <div class="form-group col-lg-1 col-md-1 col-sm-1">
                     </div>

                     <div class="form-group col-lg-10 col-md-10 col-sm-10">
                        Está a punto de eliminar esta Orden de la Hoja de Producción ¿Está seguro?
                     </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
                {!! Form::submit(l('Confirm', [], 'layouts'), array('class' => 'btn btn-danger')) !!}
            </div>
                {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection
@section('scripts') @parent 

<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '.delete-production-order', function(evnt) { 
 //       $('.delete-item').click(function (evnt) {
              var id = $(this).attr('data-oid');
              var reference = $(this).attr('data-oreference');
              var name = $(this).attr('data-oname');
            var href = $(this).attr('href');
            var message = $(this).attr('data-content');
            var title = $(this).attr('data-title');

              $('#production_order_id').text(id);

            $('#dataConfirmModal .modal-body').text(message);
            $('#ProductionOrderDelete_action').attr('action', href);
            $('#ProductionOrderDelete').modal({show: true});
            return false;
        });
    });
</script>

@endsection