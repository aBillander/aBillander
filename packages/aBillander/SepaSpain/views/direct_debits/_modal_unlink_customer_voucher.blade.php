@section('modals')

@parent

<div class="modal fade" id="CustomerOrderUnlink" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="CustomerOrderUnlinkLabel">{{ l('Unlink Customer Order') }}: <span id="customer_order_id"></span></h4>
            </div>
                {!! Form::open(array('id' => 'CustomerOrderUnlink_action')) !!}
                {{-- !! Form::hidden('_method', 'DELETE') !! --}}
            <div class="modal-body">
                <div class="row">

                {!! Form::hidden('current_production_sheet_id', $sheet->id) !!}

                     <div class="form-group col-lg-1 col-md-1 col-sm-1">
                     </div>

                     <div class="form-group col-lg-10 col-md-10 col-sm-10">
                        Está a punto de eliminar este Pedido de la Hoja de Producción. Si sigue adelante, este Pedido no estará disponible para ninguna Hoja de Producción; si lo necesita en un futuro, deberá volver a recuperarlo de la Tienda Online ¿Está seguro?
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

@stop
@section('scripts') @parent 

<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '.unlink-customer-order', function(evnt) { 
 //       $('.delete-item').click(function (evnt) {
              var id = $(this).attr('data-oid');
              var reference = $(this).attr('data-oreference');
            var href = $(this).attr('href');
            var message = $(this).attr('data-content');
            var title = $(this).attr('data-title');

              $('#customer_order_id').text(reference);

            $('#dataConfirmModal .modal-body').text(message);
            $('#CustomerOrderUnlink_action').attr('action', href);
            $('#CustomerOrderUnlink').modal({show: true});
            return false;
        });
    });
</script>

@stop