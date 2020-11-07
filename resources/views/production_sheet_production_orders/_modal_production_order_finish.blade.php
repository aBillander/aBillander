@section('modals')

@parent

<div class="modal fade" id="ProductionOrderFinish" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog xmodal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="ProductionOrderFinishLabel">{{ l('Finish Production Order') }}: <br /><span id="production_order_id_finish"></span></h4>
            </div>
                {!! Form::open(array('id' => 'ProductionOrderFinish_action')) !!}

            <div class="modal-body">
                <div class="row">

                {!! Form::hidden('production_sheet_id', $sheet->id) !!}
                {!! Form::hidden('finish_production_order_id', '', ['id' => 'finish_production_order_id']) !!}

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('quantity') ? 'has-error' : '' }}">
                     <br />
                     {{ l('Quantity') }}
                     {!! Form::text('quantity', null, array('class' => 'form-control', 'id' => 'quantity')) !!}
                     {!! $errors->first('quantity', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('lot_reference') ? 'has-error' : '' }}" id="div-lot_reference">
                     <br />
                     {{ l('Lot Number') }}
                     {!! Form::text('lot_reference', null, array('class' => 'form-control', 'id' => 'lot_reference')) !!}
                     {!! $errors->first('lot_reference', '<span class="help-block">:message</span>') !!}
                  </div>

                 <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('finish_date_form') ? 'has-error' : '' }}">
                     {{ l('Finish Date') }}
                     {!! Form::text('finish_date_form', null, array('class' => 'form-control', 'id' => 'finish_date_form')) !!}
                     {!! $errors->first('finish_date_form', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('expiry_time') ? 'has-error' : '' }}" id="div-expiry_time">
                     {{ l('Expiry Time') }}
                             <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body"
                                                data-content="{{ l('Number of Days before expiry.') }}">
                                    <i class="fa fa-question-circle abi-help"></i>
                             </a>
                     {!! Form::text('expiry_time', null, array('class' => 'form-control', 'id' => 'expiry_time')) !!}
                     {!! $errors->first('expiry_time', '<span class="help-block">:message</span>') !!}
                  </div>
         
         <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('warehouse_id') ? 'has-error' : '' }}">
            <br />
            {{ l('Warehouse') }}
            {!! Form::select('warehouse_id', $warehouseList, null, array('class' => 'form-control', 'id' => 'warehouse_id')) !!}
            {!! $errors->first('warehouse_id', '<span class="help-block">:message</span>') !!}
         </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
                {!! Form::submit(l('Confirm', [], 'layouts'), array('class' => 'btn btn-danger',  'onclick' => 'this.disabled=true;this.form.submit();', 'id' => 'ProductionOrderFinish_submit')) !!}
            </div>
                {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection
@section('scripts') @parent 

<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '.finish-production-order', function(evnt) { 
 //       $('.finish-item').click(function (evnt) {
              var id = $(this).attr('data-oid');
              var reference = $(this).attr('data-oreference');
              var name = $(this).attr('data-oname');
              var quantity  = $(this).attr('data-oquantity');

            var href = $(this).attr('href');
            var message = $(this).attr('data-content');
            var title = $(this).attr('data-title');

            var warehouse_id = $(this).attr('data-owarehouse');

            var label = '';

            label = id+' :: ['+reference+'] '+name;

              $('#production_order_id_finish').text(label);

              var lottracking = $(this).attr('data-olottracking');
              var expiry_time  = $(this).attr('data-oexpirytime');
              var expirydate  = $(this).attr('data-oexpirydate');           // Lets say this is an expiry DATE

              $('#finish_production_order_id').val(id);

              $('#quantity').val(quantity);
              $('#lot_reference').val('');
              $('#finish_date_form').val('{{ abi_date_short( \Carbon\Carbon::now() ) }}');
              $('#expiry_time').val(expiry_time);

              $('#warehouse_id option').each(function() {
                  if($(this).val() == warehouse_id) {
                      $(this).prop("selected", true);
                  } else {
                      $(this).prop("selected", false);
                  }
              });

              if ( lottracking > 0 ) {
                  $('#div-lot_reference').show();
                  $('#div-expiry_time').show();
              } else {
                  $('#div-lot_reference').hide();
                  $('#div-expiry_time').hide();
              }

            $('#dataConfirmModal .modal-body').text(message);
            $('#ProductionOrderFinish_action').attr('action', href);
            $('#ProductionOrderFinish_submit').disabled = false;
            $('#ProductionOrderFinish').modal({show: true});
            return false;
        });
    });
</script>

@endsection