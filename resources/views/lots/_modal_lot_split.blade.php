@section('modals')

@parent

<div class="modal fade" id="SplitLot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog xmodal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="SplitLotLabel">{{ l('Split Lot') }} <br /><span id="production_order_id_finish"></span></h4>
            </div>
                {!! Form::open(array('id' => 'SplitLot_action')) !!}

            <div class="modal-body">
                <div class="row">

                {!! Form::hidden('production_sheet_id', $lot->production_sheet_id) !!}
                {!! Form::hidden('finish_production_order_id', '', ['id' => 'finish_production_order_id']) !!}
                {!! Form::hidden('finish_production_order_product_id', '', ['id' => 'finish_production_order_product_id']) !!}

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('quantity') ? 'has-error' : '' }}">
                     <br />
                     {{ l('Quantity') }}
                             <a href="javascript:void(0);" data-toggle="popover" 
                                          data-placement="top" data-container="body" data-html="true" 
                                          data-content="{{ l('New Lot Quantity.') }}">
                                    <i class="fa fa-question-circle abi-help"></i>
                             </a>
                     {!! Form::text('quantity', null, array('class' => 'form-control', 'id' => 'quantity')) !!}
                     {!! $errors->first('quantity', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('lot_reference') ? 'has-error' : '' }}" id="div-lot_reference">
                     <br />
                     {{ l('Lot Number') }}
                     {!! Form::text('lot_reference', null, array('class' => 'form-control', 'id' => 'lot_reference', 'xdisabled' => "")) !!}
                     {!! $errors->first('lot_reference', '<span class="help-block">:message</span>') !!}
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
                {!! Form::submit(l('Confirm', [], 'layouts'), array('class' => 'btn btn-danger',  'onclick' => 'this.disabled=true;this.form.submit();', 'id' => 'SplitLot_submit')) !!}
            </div>
                {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection
@section('scripts') @parent 

<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '.split-lot', function(evnt) { 
 //       $('.finish-item').click(function (evnt) {
              var id = $(this).attr('data-oid');
              var product_id = $(this).attr('data-oproduct');
              var reference = $(this).attr('data-oreference');
              var name = $(this).attr('data-oname');
              var quantity  = $(this).attr('data-oquantity');
              var lot_reference  = $(this).attr('data-olot_reference');

            var href = $(this).attr('href');
            var message = $(this).attr('data-content');
            var title = $(this).attr('data-title');

            var warehouse_id = $(this).attr('data-owarehouse');

            var label = '';

            label = id+' :: ['+reference+'] '+name;

             // $('#production_order_id_finish').text(label);

              var lottracking = $(this).attr('data-olottracking');
              var expiry_time  = $(this).attr('data-oexpirytime');
              var expirydate  = $(this).attr('data-oexpirydate');           // Lets say this is an expiry DATE

              $('#finish_production_order_id').val(id);
              $('#finish_production_order_product_id').val(product_id);

              $('#quantity').val(quantity);
              $('#lot_reference').val(lot_reference);

              $('#warehouse_id option').each(function() {
                  if($(this).val() == warehouse_id) {
                      $(this).prop("selected", true);
                  } else {
                      $(this).prop("selected", false);
                  }
              });

            $('#dataConfirmModal .modal-body').text(message);
            $('#SplitLot_action').attr('action', href);
            $('#SplitLot_submit').disabled = false;
            $('#SplitLot').modal({show: true});
            return false;
        });
    });


</script>

@endsection