@section('modals')

@parent

<div class="modal fade" id="CustomerOrderMove" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="CustomerOrderMoveLabel">{{ l('Move Customer Order') }}: <span id="customer_order_id"></span></h4>
            </div>
                {!! Form::open(array('id' => 'CustomerOrderMove_action')) !!}
                {{-- !! Form::hidden('_method', 'DELETE') !! --}}
            <div class="modal-body">
                <div class="row">

                    <div class="form-group col-lg-6 col-md-6 col-sm-6">
                        {!! Form::label('production_sheet_id', l('Production Sheet')) !!}
                        {!! Form::select('production_sheet_id', $availableProductionSheetList, $sheet->id, array('class' => 'form-control', 'id' => 'production_sheet_id')) !!}
                    </div>

                {!! Form::hidden('current_production_sheet_id', $sheet->id) !!}

                   <div class="form-group col-lg-6 col-md-6 col-sm-6" id="div-stay_current_sheet">
                     {!! Form::label('stay_current_sheet', l('¿Permanecer en la Hoja actual?'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('stay_current_sheet', '1', true, ['id' => 'stay_current_sheet_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('stay_current_sheet', '0', false, ['id' => 'stay_current_sheet_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
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
        $('body').on('click', '.move-customer-order', function(evnt) { 
 //       $('.delete-item').click(function (evnt) {
              var id = $(this).attr('data-oid');
              var reference = $(this).attr('data-oreference');
            var href = $(this).attr('href');
            var message = $(this).attr('data-content');
            var title = $(this).attr('data-title');

              $('#customer_order_id').text(reference);

            $('#dataConfirmModal .modal-body').text(message);
            $('#CustomerOrderMove_action').attr('action', href);
            $('#CustomerOrderMove').modal({show: true});
            return false;
        });
    });
</script>

@endsection