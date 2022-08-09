<div class="row pos_form_totals">
	<div class="col-md-12">
		<table class="table table-condensed">
			<tr>
				<td><b>{{ l('Items') }}:</b>&nbsp;
					<span class="total_quantity">0</span></td>
				<td>
					<b>{{ l('Total') }}:</b> &nbsp;
					<span class="price_total">0</span>
				</td>
			</tr>
			<tr>
				<td>
					<b>
						@if($is_discount_enabled)
							{{ l('Discount') }}
							@show_tooltip( l('Add/Edit Discount.') )
						@endif
						@if($is_rp_enabled)
							{{session('business.rp_name')}} {{-- reward_point --}}
						@endif
						@if($is_discount_enabled)
							(-):

							<i class="fas fa-edit cursor-pointer" id="pos-edit-discount" title="{{ l('Edit Discount') }}" aria-hidden="true" data-toggle="modal" data-target="#posEditDiscountModal"></i>
						
							<span id="total_discount">0</span>
						@endif
							<input type="hidden" name="discount_type" id="discount_type" value="@if(empty($edit)){{'percentage'}}@else{{$transaction->discount_type}}@endif" data-default="percentage">

							<input type="hidden" name="discount_amount" id="discount_amount" value="@ if(empty($edit)) { {@ num_format($business_details->default_sales_discount)}} @ else { {@ num_format($transaction->discount_amount)}} @ endif" data-default="{ {$business_details->default_sales_discount}}">

							<input type="hidden" name="rp_redeemed" id="rp_redeemed" value="@if(empty($edit)){{'0'}}@else{{$transaction->rp_redeemed}}@endif">

							<input type="hidden" name="rp_redeemed_amount" id="rp_redeemed_amount" value="@if(empty($edit)){{'0'}}@else {{$transaction->rp_redeemed_amount}} @endif">

							</span>
					</b> 
				</td>
				<td class="">
					<span>
						<b>{{ l('Taxes') }}(+): @show_tooltip( l('Click on the edit icon below to add/update Order Tax.'))</b>
						<i class="fas fa-edit cursor-pointer" title="{ l('Edit Taxes') }}" aria-hidden="true" data-toggle="modal" data-target="#posEditOrderTaxModal" id="pos-edit-tax" ></i> 
						<span id="order_tax">
							@if(empty($edit))
								0
							@else
								{{$transaction->tax_amount}}
							@endif
						</span>

						<input type="hidden" name="tax_rate_id" 
							id="tax_rate_id" 
							value="@if(empty($edit)) { {$business_details->default_sales_tax} } @else { {$transaction->tax_id} } @endif" 
							data-default="{ {$business_details->default_sales_tax} }">

						<input type="hidden" name="tax_calculation_amount" id="tax_calculation_amount" 
							value="@ if(empty($edit)) { {@ num_format($business_details->tax_calculation_amount)}} @ else { {@ num_format(optional($transaction->tax)->amount)}} @ endif" data-default="{ {$business_details->tax_calculation_amount}}">

					</span>
				</td>
				<td>
					<span>

						<b>{{ l('Shipping') }}(+): @show_tooltip( l('Set Shipping Details and Shipping Charges. Click on the edit icon below to add/edit Shipping Details and Charges.') )</b> 
						<i class="fas fa-edit cursor-pointer"  title="{{ l('Shipping') }}" aria-hidden="true" data-toggle="modal" data-target="#posShippingModal"></i>
						<span id="shipping_charges_amount">0</span>

						<input type="hidden" name="shipping_details" id="shipping_details" value="@if(empty($edit)){{''}}@else{{$transaction->shipping_details}}@endif" data-default="">

						<input type="hidden" name="shipping_address" id="shipping_address" value="@if(empty($edit)){{''}}@else{{$transaction->shipping_address}}@endif">

						<input type="hidden" name="shipping_status" id="shipping_status" value="@if(empty($edit)){{''}}@else{{$transaction->shipping_status}}@endif">

						<input type="hidden" name="delivered_to" id="delivered_to" value="@if(empty($edit)){{''}}@else{{$transaction->delivered_to}}@endif">

						<input type="hidden" name="shipping_charges" id="shipping_charges" value="@ if(empty($edit)){ {@ num_format(0.00)}} @ else{ {@ num_format($transaction->shipping_charges)}} @ endif" data-default="0.00">
					</span>
				</td>
				@if(1||in_array('types_of_service', $enabled_modules))
					<td class="col-sm-3 col-xs-6 d-inline-table">
						<b>{{ l('Packing Charge') }}(+):</b>
						<i class="fas fa-edit cursor-pointer service_modal_btn"></i> 
						<span id="packing_charge_text">
							0
						</span>
					</td>
				@endif
				@if(1||!empty($pos_settings['amount_rounding_method']) && $pos_settings['amount_rounding_method'] > 0)
				<td>
					<b id="round_off">{{ l('Round Off') }}:</b> <span id="round_off_text">0</span>								
					<input type="hidden" name="round_off_amount" id="round_off_amount" value=0>
				</td>
				@endif
			</tr>
		</table>
	</div>
</div>