@php
	$is_mobile = 0 && "isMobile()";
@endphp

<div class="row">
	<div class="pos-form-actions">
		<div class="col-md-12">

			<button type="button" class="btn bg-info text-white btn-default btn-flat " id="pos-draft">
				<i class="fas fa-edit"></i> {{ l('Draft') }}
			</button>

			<button type="button" class="btn btn-default bg-yellow btn-flat" id="pos-quotation">
				<i class="fas fa-edit"></i> {{ l('Quotation') }}
			</button>

			<button type="button" class="btn bg-red btn-default btn-flat no-print pos-express-finalize" data-pay_method="suspend" title="{{ l('Suspend Sales (pause)') }}" >
				<i class="fas fa-pause" aria-hidden="true"></i> {{ l('Suspend') }}
			</button>

			<input type="hidden" name="is_credit_sale" value="0" id="is_credit_sale">
			<button type="button" class="btn bg-purple btn-default btn-flat no-print pos-express-finalize" data-pay_method="credit_sale"
			title="{{ l('Checkout as Credit Sale') }}" >
				<i class="fas fa-check" aria-hidden="true"></i> {{ l('Credit Sale') }}
			</button>

			<button type="button" class="btn bg-maroon btn-default btn-flat no-print  pos-express-finalize" data-pay_method="card" title="{{ l('Credit Card Payment') }}" >
				<i class="fas fa-credit-card" aria-hidden="true"></i> {{ l('Credit Card') }}
			</button>

			<button type="button" class="btn bg-navy btn-default btn-flat no-print" id="pos-finalize" title="{{ l('Checkout using multiple Payment Methods') }}">
				<i class="fas fa-money-check-alt" aria-hidden="true"></i> {{ l('Multiple Pay') }}
			</button>

@php
	$pos_settings['disable_express_checkout'] = 0;
@endphp

			<button type="button" class="btn btn-success btn-flat no-print pos-express-finalize" data-pay_method="cash" title="{{ l('Cash Payment') }}">
				<i class="fas fa-money-bill-alt" aria-hidden="true"></i> {{ l('Cash') }}
			</button>

			@if(empty($edit))
				<button type="button" class="btn btn-danger btn-flat btn-xs" id="pos-cancel"> <i class="fas fa-window-close"></i> {{ l('Cancel') }}</button>
			@else
				<button type="button" class="btn btn-danger btn-flat hide btn-xs" id="pos-delete"> <i class="fas fa-trash-alt"></i> {{ l('Delete') }}</button>
			@endif


			<div class="bg-navy pos-total text-white">
				<span class="text">{{ l('Total Payable') }}</span>
				<input type="hidden" name="final_total" id="final_total_input" value="0">
				<span id="total_payable" class="number">0</span>
			</div>


			<button type="button" class="pull-right btn btn-primary btn-flat" data-toggle="modal" data-target="#recent_transactions_modal" id="recent-transactions"> 
				<i class="fas fa-clock"></i> {{ l('Recent Transactions') }}
			</button>

			
			
		</div>
	</div>
</div>
@if(isset($transaction))
	@include('pos::pos.partials.edit_discount_modal', ['sales_discount' => $transaction->discount_amount, 'discount_type' => $transaction->discount_type, 'rp_redeemed' => $transaction->rp_redeemed, 'rp_redeemed_amount' => $transaction->rp_redeemed_amount, 'max_available' => !empty($redeem_details['points']) ? $redeem_details['points'] : 0])
@else
	@include('pos::pos.partials.edit_discount_modal', ['sales_discount' => "$ business_details->default_sales_discount", 'discount_type' => 'percentage', 'rp_redeemed' => 0, 'rp_redeemed_amount' => 0, 'max_available' => 0])
@endif

@if(isset($transaction))
	@include('pos::pos.partials.edit_order_tax_modal', ['selected_tax' => $transaction->tax_id])
@else
	@include('pos::pos.partials.edit_order_tax_modal', ['selected_tax' => '$business_details->default_sales_tax'])
@endif

@include('pos::pos.partials.edit_shipping_modal')