<!-- Order Lines -->

               <div class="panel-body">

<div id="msg-success" class="alert alert-success alert-block" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span id="msg-success-counter" class="badge"></span>
  <strong>{!!  l('This record has been successfully created &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
</div>

<div id="msg-error" class="alert alert-danger alert-block" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span id="msg-error-counter" class="badge"></span>
  <strong>{!!  l('Unable to create this record &#58&#58 (:id) ', ['id' => l('Product not found', 'customerdocuments')], 'layouts') !!}</strong>
</div>

<div id="msg-success-delete" class="alert alert-success alert-block" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span id="msg-success-delete-counter" class="badge"></span>
  <strong>{!!  l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
</div>

<div id="panel_warehouse_shipping_slip_lines" class="loading"> &nbsp; &nbsp; &nbsp; &nbsp; {{ l('Loading...', 'layouts') }}
  
{{--  @ include('customer_orders._panel_order_lines') --}}

</div>

               </div><!-- div class="panel-body" -->

@include('warehouse_shipping_slips._modal_document_lines_quick_form')

@include('warehouse_shipping_slips._modal_document_line_form')

@include('warehouse_shipping_slips._modal_document_line_delete')

{{--
@ include('warehouse_shipping_slips._modal_product_consumption')
--}}
<!-- Order Lines ENDS -->