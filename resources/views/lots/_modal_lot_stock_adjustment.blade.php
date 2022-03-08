@section('modals')

@parent

<!-- Modal -->
<div class="modal fade" id="LotStockAdjustment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog xmodal-lg modal-draggable modal-dialog-help" role="document">
        <div class="modal-content">
            <div class="modal-header alert-danger">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="LotStockAdjustmentLabel">{{ l('Stock Adjustment') }} :: 
                    <span class="lead well well-sm alert-warning">{{ $lot->reference }}</span>
                    {{l('Current Stock')}}: 
                    {{ $lot->measureunit->quantityable($lot->quantity) }} <span class="badge" style="background-color: #3a87ad;" title="{{ optional($lot->measureunit)->name }}"> &nbsp; {{ optional($lot->measureunit)->sign }} &nbsp; </span>
                </h4>
            </div>
                {!! Form::open(array('route' => ['lots.update.quantity', $lot->id], 'name' => 'lot_stock_adjustment_form', 'class' => 'form')) !!}

            <div class="modal-body">
                <div class="row">

                 <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('adjustment_date_form') ? 'has-error' : '' }}">
                     {{ l('Date') }}
                     {!! Form::text('adjustment_date_form', null, array('class' => 'form-control', 'id' => 'adjustment_date_form')) !!}
                     {!! $errors->first('adjustment_date_form', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('adjustment_quantity') ? 'has-error' : '' }}">
                     {{ l('Quantity') }}
                     {!! Form::text('adjustment_quantity', null, array('class' => 'form-control', 'id' => 'adjustment_quantity')) !!}
                     {!! $errors->first('adjustment_quantity', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('adjustment_notes') ? 'has-error' : '' }}">
                     {{ l('Notes', [], 'layouts') }}
                     {!! Form::textarea('adjustment_notes', null, array('class' => 'form-control', 'id' => 'adjustment_notes', 'rows' => '3')) !!}
                     {!! $errors->first('adjustment_notes', '<span class="help-block">:message</span>') !!}
                  </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
                {!! Form::submit(l('Confirm', [], 'layouts'), array('class' => 'btn btn-danger',  'xonclick' => 'this.disabled=true;this.form.submit();', 'id' => 'LotStockAdjustment_submit')) !!}
            </div>

                {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection
@section('scripts') @parent 

<script type="text/javascript">
    $(document).ready(function () {


  $(function() {
    $( "#adjustment_date_form" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "{{ AbiContext::getContext()->language->date_format_lite_view }}"
    });
  });



    $('#adjustment_date_form').val('{{ abi_date_form_short( 'now' ) }}');


        $('body').on('click', '.lot_stock_adjustment', function(evnt) { 
{{--
              var id = $(this).attr('data-oid');
              var product_id = $(this).attr('data-oproduct');
              var reference = $(this).attr('data-oreference');
              var name = $(this).attr('data-oname');
              var adjustment_quantity  = $(this).attr('data-oadjustment_quantity');
              var lot_reference  = $(this).attr('data-olot_reference');

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
              $('#finish_production_order_product_id').val(product_id);

              $('#adjustment_quantity').val(adjustment_quantity);
              $('#lot_reference').val(lot_reference);
              $('#adjustment_date_form').val('{{ abi_date_short( \Carbon\Carbon::now() ) }}');
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
            $('#LotStockAdjustment_action').attr('action', href);
            $('#LotStockAdjustment_submit').disabled = false;
--}}
            $('#LotStockAdjustment').modal({show: true});
            return false;
        });
    });




</script>

@endsection

@section('styles')    @parent

<style>

{{-- Keeps popup window corners rounded! --}}
.modal-content {
  overflow:hidden;
}

</style>

@endsection
