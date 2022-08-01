<table class='table table-condensed table-striped'>
	<tr>
	    <th>@lang('business.operations')</th>
	    <th>@lang('business.keyboard_shortcut')</th>
	</tr>

	@if($pos_settings['disable_express_checkout'] == 0)
		<tr>
		    <td>@lang('sale.express_finalize'):</td>
		    <td>
			    @if(!empty($shortcuts["pos"]["express_checkout"]))
			    	{{ $shortcuts["pos"]["express_checkout"] }}
			    @endif
		    </td>
		</tr>
	@endif

	@if($pos_settings['disable_pay_checkout'] == 0)
		<tr>
		    <td>@lang('sale.finalize'):</td>
		    <td>
		    	@if(!empty($shortcuts["pos"]["pay_n_ckeckout"]))
			    	{{ $shortcuts["pos"]["pay_n_ckeckout"] }}
			    @endif
		    </td>
		</tr>
	@endif

	@if($pos_settings['disable_draft'] == 0)
		<tr>
		    <td>@lang('sale.draft'):</td>
		    <td>
		    	@if(!empty($shortcuts["pos"]["draft"]))
			    	{{ $shortcuts["pos"]["draft"] }}
			    @endif
		    </td>
		</tr>
	@endif

	<tr>
	    <td>@lang('messages.cancel'):</td>
	    <td>
	    	@if(!empty($shortcuts["pos"]["cancel"]))
		    	{{ $shortcuts["pos"]["cancel"] }}
		    @endif
	    </td>
	</tr>

	@if($pos_settings['disable_discount'] == 0)
		<tr>
		    <td>@lang('sale.edit_discount'):</td>
		    <td>
		    	@if(!empty($shortcuts["pos"]["edit_discount"]))
			    	{{ $shortcuts["pos"]["edit_discount"] }}
			    @endif
		    </td>
		</tr>
	@endif

	@if($pos_settings['disable_order_tax'] == 0)
		<tr>
		    <td>@lang('sale.edit_order_tax'):</td>
		    <td>
		    	@if(!empty($shortcuts["pos"]["edit_order_tax"]))
			    	{{ $shortcuts["pos"]["edit_order_tax"] }}
			    @endif
		    </td>
		</tr>
	@endif

	@if($pos_settings['disable_pay_checkout'] == 0)
		<tr>
		    <td>@lang('sale.add_payment_row'):</td>
		    <td>
		    	@if(!empty($shortcuts["pos"]["add_payment_row"]))
			    	{{ $shortcuts["pos"]["add_payment_row"] }}
			    @endif
		    </td>
		</tr>
	@endif

	@if($pos_settings['disable_pay_checkout'] == 0)
		<tr>
		    <td>@lang('sale.finalize_payment'):</td>
		    <td>
		    	@if(!empty($shortcuts["pos"]["finalize_payment"]))
			    	{{ $shortcuts["pos"]["finalize_payment"] }}
			    @endif
		    </td>
		</tr>
	@endif
	
	<tr>
	    <td>@lang('lang_v1.recent_product_quantity'):</td>
	    <td>
	    	@if(!empty($shortcuts["pos"]["recent_product_quantity"]))
		    	{{ $shortcuts["pos"]["recent_product_quantity"] }}
		    @endif
	    </td>
	</tr>

	<tr>
	    <td>@lang('lang_v1.add_new_product'):</td>
	    <td>
	    	@if(!empty($shortcuts["pos"]["add_new_product"]))
		    	{{ $shortcuts["pos"]["add_new_product"] }}
		    @endif
	    </td>
	</tr>
	
	@if(isset($pos_settings['enable_weighing_scale']) && $pos_settings['enable_weighing_scale'] == 1)
		<tr>
		    <td>@lang('lang_v1.weighing_scale'):</td>
		    <td>
		    	@if(!empty($shortcuts["pos"]["weighing_scale"]))
			    	{{ $shortcuts["pos"]["weighing_scale"] }}
			    @endif
		    </td>
		</tr>
	@endif
	
</table>