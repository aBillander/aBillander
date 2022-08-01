<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-body bg-gray disabled" style="margin-bottom: 0px !important">
				<div class="col-sm-3 col-sm-offset-6 col-xs-6 d-inline-table" style="margin-bottom: 10px;">
					<b>@lang('sale.item'):</b>&nbsp;
					<span class="total_quantity">0</span>
				</div>
				<div class="col-sm-3 col-xs-6 d-inline-table" style="margin-bottom: 10px;">
					<b>@lang('sale.total'):</b> &nbsp;
					<span class="price_total">0</span>
				</div>
				<table class="table table-condensed" 
					style="margin-bottom: 0px !important">
					<tbody>
					@php
						$col = in_array('types_of_service', $enabled_modules) ? 'col-sm-2' : 'col-sm-3';
					@endphp
					<tr>
						<td>
							<div class="{{$col}} col-xs-6 d-inline-table">
								@php
									$is_discount_enabled = $pos_settings['disable_discount'] != 1 ? true : false;
									$is_rp_enabled = session('business.enable_rp') == 1 ? true : false;
								@endphp
								<span class="@if(!$is_discount_enabled && !$is_rp_enabled) hide @endif">

								<b>
								@if($is_discount_enabled)
									@lang('sale.discount')
									@show_tooltip(__('tooltip.sale_discount'))
								@endif
								@if($is_rp_enabled)
									{{session('business.rp_name')}}
								@endif
								(-):</b> 
								<br/>
								<i class="fas fa-pen cursor-pointer" id="pos-edit-discount" title="@lang('sale.edit_discount')" aria-hidden="true" data-toggle="modal" data-target="#posEditDiscountModal"></i>
								<span id="total_discount">0</span>
								<input type="hidden" name="discount_type" id="discount_type" value="@if(empty($edit)){{'percentage'}}@else{{$transaction->discount_type}}@endif" data-default="percentage">

								<input type="hidden" name="discount_amount" id="discount_amount" value="@if(empty($edit)) {{@num_format($business_details->default_sales_discount)}} @else {{@num_format($transaction->discount_amount)}} @endif" data-default="{{$business_details->default_sales_discount}}">

								<input type="hidden" name="rp_redeemed" id="rp_redeemed" value="@if(empty($edit)){{'0'}}@else{{$transaction->rp_redeemed}}@endif">

								<input type="hidden" name="rp_redeemed_amount" id="rp_redeemed_amount" value="@if(empty($edit)){{'0'}}@else {{$transaction->rp_redeemed_amount}} @endif">

								</span>
							</div>

							<div class="{{$col}} col-xs-6 d-inline-table">

								<span class="@if($pos_settings['disable_order_tax'] != 0) hide @endif">

								<b>@lang('sale.order_tax')(+): @show_tooltip(__('tooltip.sale_tax'))</b>
								<br/>
								<i class="fas fa-pen cursor-pointer" title="@lang('sale.edit_order_tax')" aria-hidden="true" data-toggle="modal" data-target="#posEditOrderTaxModal" id="pos-edit-tax" ></i> 
								<span id="order_tax">
									@if(empty($edit))
										0
									@else
										{{$transaction->tax_amount}}
									@endif
								</span>

								<input type="hidden" name="tax_rate_id" 
									id="tax_rate_id" 
									value="@if(empty($edit)) {{$business_details->default_sales_tax}} @else {{$transaction->tax_id}} @endif" 
									data-default="{{$business_details->default_sales_tax}}">

								<input type="hidden" name="tax_calculation_amount" id="tax_calculation_amount" 
									value="@if(empty($edit)) {{@num_format($business_details->tax_calculation_amount)}} @else {{@num_format(optional($transaction->tax)->amount)}} @endif" data-default="{{$business_details->tax_calculation_amount}}">

								</span>
							</div>
							
							<!-- shipping -->
							<div class="{{$col}} col-xs-6 d-inline-table">

								<span class="@if($pos_settings['disable_discount'] != 0) hide @endif">

								<b>@lang('sale.shipping')(+): @show_tooltip(__('tooltip.shipping'))</b> 
								<br/>
								<i class="fas fa-pen cursor-pointer"  title="@lang('sale.shipping')" aria-hidden="true" data-toggle="modal" data-target="#posShippingModal"></i>
								<span id="shipping_charges_amount">0</span>
								<input type="hidden" name="shipping_details" id="shipping_details" value="@if(empty($edit)){{""}}@else{{$transaction->shipping_details}}@endif" data-default="">

								<input type="hidden" name="shipping_address" id="shipping_address" value="@if(empty($edit)){{""}}@else{{$transaction->shipping_address}}@endif">

								<input type="hidden" name="shipping_status" id="shipping_status" value="@if(empty($edit)){{""}}@else{{$transaction->shipping_status}}@endif">

								<input type="hidden" name="delivered_to" id="delivered_to" value="@if(empty($edit)){{""}}@else{{$transaction->delivered_to}}@endif">

								<input type="hidden" name="shipping_charges" id="shipping_charges" value="@if(empty($edit)){{@num_format(0.00)}} @else{{@num_format($transaction->shipping_charges)}} @endif" data-default="0.00">

								</span>
							</div>
							@if(in_array('types_of_service', $enabled_modules))
								<div class="col-sm-3 col-xs-6 d-inline-table">
									<b>@lang('lang_v1.packing_charge')(+):</b>
									<br/>
									<i class="fas fa-pen cursor-pointer service_modal_btn"></i> 
									<span id="packing_charge_text">
										0
									</span>
								</div>
							@endif
							<div class="col-sm-3 col-xs-12 d-inline-table">
								<b>@lang('sale.total_payable'):</b>
								<small class="hide" id="round_off"><br>(@lang('lang_v1.round_off'): <span id="round_off_text"></span>)</small>
								<br/>
								<input type="hidden" name="round_off_amount" 
									id="round_off_amount" value=0>
								<input type="hidden" name="final_total" 
									id="final_total_input" value=0>
								<span id="total_payable" class="text-success lead text-bold">0</span>
								@if(empty($edit))
									<button type="button" class="btn btn-danger btn-flat btn-xs pull-right" id="pos-cancel">@lang('sale.cancel')</button>
								@else
									<button type="button" class="btn btn-danger btn-flat hide btn-xs pull-right" id="pos-delete">@lang('messages.delete')</button>
								@endif
							</div>
						</td>
					</tr>

					<tr>
						<td>
							<div class="col-sm-2 col-xs-6 col-2px-padding">

								<button type="button" 
									class="btn btn-warning btn-block btn-flat @if($pos_settings['disable_draft'] != 0) hide @endif" 
									id="pos-draft">@lang('sale.draft')</button>

								<button type="button" 
									class="btn btn-info btn-block btn-flat" 
									id="pos-quotation">@lang('lang_v1.quotation')</button>
							</div>
							<div class="col-sm-3 col-xs-6 col-2px-padding">
								@if(!empty($pos_settings['show_credit_sale_button']))
									<input type="hidden" name="is_credit_sale" value="0" id="is_credit_sale">
									<button type="button" 
									class="btn bg-purple btn-block btn-flat no-print pos-express-finalize" 
									data-pay_method="credit_sale"
									title="@lang('lang_v1.tooltip_credit_sale')" >
									<div class="text-center">
										<i class="fa fa-check" aria-hidden="true"></i>
	    								<b>@lang('lang_v1.credit_sale')</b>
	    							</div>
									</button>
								@else
									<button type="button" 
									class="btn bg-maroon btn-block btn-flat no-print @if(!empty($pos_settings['disable_suspend'])) pos-express-btn btn-lg @endif pos-express-finalize @if(!array_key_exists('card', $payment_types)) hide @endif" 
									data-pay_method="card"
									title="@lang('lang_v1.tooltip_express_checkout_card')" >
									<div class="text-center">
										<i class="fa fa-check" aria-hidden="true"></i>
	    								<b>@lang('lang_v1.express_checkout_card')</b>
	    							</div>
									</button>
								@endif
								@if(empty($pos_settings['disable_suspend']))
									<button type="button" 
									class="btn bg-red btn-block btn-flat no-print pos-express-finalize" 
									data-pay_method="suspend"
									title="@lang('lang_v1.tooltip_suspend')" >
									<div class="text-center">
										<i class="fa fa-pause" aria-hidden="true"></i>
	    								<b>@lang('lang_v1.suspend')</b>
	    							</div>
									</button>
								@endif
							</div>
							<div class="col-sm-4 col-xs-12 col-2px-padding">
								<button type="button" class="btn bg-navy  btn-block btn-flat btn-lg no-print @if($pos_settings['disable_pay_checkout'] != 0) hide @endif pos-express-btn" id="pos-finalize" title="@lang('lang_v1.tooltip_checkout_multi_pay')">
								<div class="text-center">
									<i class="fa fa-check" aria-hidden="true"></i>
    								<b>@lang('lang_v1.checkout_multi_pay')</b>
    							</div>
								</button>
							</div>
							<div class="col-sm-3 col-xs-12 col-2px-padding">
								<button type="button" class="btn btn-success btn-block btn-flat btn-lg no-print @if($pos_settings['disable_express_checkout'] != 0 || !array_key_exists('cash', $payment_types)) hide @endif pos-express-btn pos-express-finalize"
								data-pay_method="cash"
								title="@lang('tooltip.express_checkout')">
								<div class="text-center">
									<i class="fa fa-check" aria-hidden="true"></i>
    								<b>@lang('lang_v1.express_checkout_cash')</b>
    							</div>
								</button>
							</div>

							<div class="div-overlay pos-processing"></div>
						</td>
					</tr>

					</tbody>
				</table>

				<!-- Button to perform various actions -->
				<div class="row">
					
				</div>
			</div>
		</div>
	</div>
</div>

@if(isset($transaction))
	@include('sale_pos.partials.edit_discount_modal', ['sales_discount' => $transaction->discount_amount, 'discount_type' => $transaction->discount_type, 'rp_redeemed' => $transaction->rp_redeemed, 'rp_redeemed_amount' => $transaction->rp_redeemed_amount, 'max_available' => !empty($redeem_details['points']) ? $redeem_details['points'] : 0])
@else
	@include('sale_pos.partials.edit_discount_modal', ['sales_discount' => $business_details->default_sales_discount, 'discount_type' => 'percentage', 'rp_redeemed' => 0, 'rp_redeemed_amount' => 0, 'max_available' => 0])
@endif

@if(isset($transaction))
	@include('sale_pos.partials.edit_order_tax_modal', ['selected_tax' => $transaction->tax_id])
@else
	@include('sale_pos.partials.edit_order_tax_modal', ['selected_tax' => $business_details->default_sales_tax])
@endif

@include('sale_pos.partials.edit_shipping_modal')
