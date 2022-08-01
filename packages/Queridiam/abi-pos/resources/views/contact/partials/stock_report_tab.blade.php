<div class="row">
	<div class="col-md-4">
	    <div class="form-group">
	        {!! Form::label('sr_location_id',  __('purchase.business_location') . ':') !!}

	        {!! Form::select('sr_location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%']); !!}
	    </div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
            <table class="table table-bordered table-striped" 
            id="supplier_stock_report_table" width="100%">
                <thead>
                    <tr>
                        <th>@lang('sale.product')</th>
                        <th>@lang('product.sku')</th>
                        <th>@lang('purchase.purchase_quantity')</th>
                        <th>@lang('lang_v1.total_sold')</th>
                        <th>@lang('lang_v1.total_unit_transfered')</th>
                        <th>@lang('lang_v1.total_returned')</th>
                        <th>@lang('report.current_stock')</th>
                        <th>@lang('lang_v1.total_stock_price')</th>
                    </tr>
                </thead>
            </table>
        </div>
	</div>
</div>