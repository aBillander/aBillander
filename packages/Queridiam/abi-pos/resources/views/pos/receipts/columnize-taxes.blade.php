@php
	$totals = ['taxable_value' => 0];
@endphp

<table style="width:100%;">
	<thead>
		<tr>
			<td class="pull-right">
				<small class="text-muted-imp">
					@if(!empty($receipt_details->invoice_no_prefix))
						{!! $receipt_details->invoice_no_prefix !!}
					@endif

					{{$receipt_details->invoice_no}}
				</small>
			</td>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td class="text-center" style="line-height: 15px !important; padding-bottom: 10px !important">
				@if(!empty($receipt_details->header_text))
					{!! $receipt_details->header_text !!}
				@endif

				@php
					$sub_headings = implode('<br/>', array_filter([$receipt_details->sub_heading_line1, $receipt_details->sub_heading_line2, $receipt_details->sub_heading_line3, $receipt_details->sub_heading_line4, $receipt_details->sub_heading_line5]));
				@endphp

				@if(!empty($sub_headings))
					<span>{!! $sub_headings !!}</span>
				@endif

				@if(!empty($receipt_details->invoice_heading))
					<p  style="font-weight: bold; font-size: 35px !important">{!! $receipt_details->invoice_heading !!}</p>
				@endif
			</td>
		</tr>

		<tr>
			<td>

<!-- business information here -->
<div class="row invoice-info">

	<div class="col-md-6 invoice-col width-50">

		<div class="text-right font-23">
			@if(!empty($receipt_details->invoice_no_prefix))
				<span class="pull-left">{!! $receipt_details->invoice_no_prefix !!}</span>
			@endif

			{{$receipt_details->invoice_no}}
		</div>

		<!-- Total Due-->
		@if(!empty($receipt_details->total_due) && !empty($receipt_details->total_due_label))
			<div class="bg-light-blue-active text-right font-23 padding-5">
				<span class="pull-left bg-light-blue-active">
					{!! $receipt_details->total_due_label !!}
				</span>

				{{$receipt_details->total_due}}
			</div>
		@endif

		@if(!empty($receipt_details->all_due))
			<div class="bg-light-blue-active text-right font-23 padding-5">
				<span class="pull-left bg-light-blue-active">
					{!! $receipt_details->all_bal_label !!}
				</span>

				{{$receipt_details->all_due}}
			</div>
		@endif
		
		<!-- Total Paid-->
		@if(!empty($receipt_details->total_paid))
			<div class="text-right font-23 ">
				<span class="pull-left">{!! $receipt_details->total_paid_label !!}</span>
				{{$receipt_details->total_paid}}
			</div>
		@endif
		<!-- Date-->
		@if(!empty($receipt_details->date_label))
			<div class="text-right font-23 ">
				<span class="pull-left">
					{{$receipt_details->date_label}}
				</span>

				{{$receipt_details->invoice_date}}
			</div>
		@endif
		@if(!empty($receipt_details->due_date_label))
			<div class="text-right font-23 ">
				<span class="pull-left">
					{{$receipt_details->due_date_label}}
				</span>

				{{$receipt_details->due_date ?? ''}}
			</div>
		@endif

		<div class="word-wrap">
			@if(!empty($receipt_details->customer_label))
				<b>{{ $receipt_details->customer_label }}</b><br/>
			@endif

			<!-- customer info -->
			@if(!empty($receipt_details->customer_info))
				{!! $receipt_details->customer_info !!}
			@endif
			@if(!empty($receipt_details->client_id_label))
				<br/>
				<strong>{{ $receipt_details->client_id_label }}</strong> {{ $receipt_details->client_id }}
			@endif
			@if(!empty($receipt_details->customer_tax_label))
				<br/>
				<strong>{{ $receipt_details->customer_tax_label }}</strong> {{ $receipt_details->customer_tax_number }}
			@endif
			@if(!empty($receipt_details->customer_custom_fields))
				<br/>{!! $receipt_details->customer_custom_fields !!}
			@endif
			@if(!empty($receipt_details->sales_person_label))
				<br/>
				<strong>{{ $receipt_details->sales_person_label }}</strong> {{ $receipt_details->sales_person }}
			@endif
			@if(!empty($receipt_details->commission_agent_label))
				<br/>
				<strong>{{ $receipt_details->commission_agent_label }}</strong> {{ $receipt_details->commission_agent }}
			@endif

			@if(!empty($receipt_details->customer_rp_label))
				<br/>
				<strong>{{ $receipt_details->customer_rp_label }}</strong> {{ $receipt_details->customer_total_rp }}
			@endif

			<!-- Display type of service details -->
			@if(!empty($receipt_details->types_of_service))
				<span class="pull-left text-left">
					<strong>{!! $receipt_details->types_of_service_label !!}:</strong>
					{{$receipt_details->types_of_service}}
					<!-- Waiter info -->
					@if(!empty($receipt_details->types_of_service_custom_fields))
						<br>
						@foreach($receipt_details->types_of_service_custom_fields as $key => $value)
							<strong>{{$key}}: </strong> {{$value}}@if(!$loop->last), @endif
						@endforeach
					@endif
				</span>
			@endif

		</div>

	</div>

	<div class="col-md-6 invoice-col width-50 ">
		
		<!-- Logo -->
		@if(!empty($receipt_details->logo))
			<img style="max-height: 120px; width: auto;" src="{{$receipt_details->logo}}" class="img center-block">
			<br/>
		@endif

		<!-- Shop & Location Name  -->
		<p>
			@if(!empty($receipt_details->display_name))
				{{$receipt_details->display_name}}
				<br/>
			@endif
			@if(!empty($receipt_details->address))
				<br/>{!! $receipt_details->address !!}
			@endif

			@if(!empty($receipt_details->contact))
				<br/>{!! $receipt_details->contact !!}
			@endif

			@if(!empty($receipt_details->website))
				<br/>{{ $receipt_details->website }}
			@endif

			@if(!empty($receipt_details->tax_info1))
				<br/>{{ $receipt_details->tax_label1 }} {{ $receipt_details->tax_info1 }}
			@endif

			@if(!empty($receipt_details->tax_info2))
				<br/>{{ $receipt_details->tax_label2 }} {{ $receipt_details->tax_info2 }}
			@endif

			@if(!empty($receipt_details->location_custom_fields))
				<br/>{{ $receipt_details->location_custom_fields }}
			@endif
		</p>
		

		<!-- Table information-->
        @if(!empty($receipt_details->table_label) || !empty($receipt_details->table))
        	<p>
				@if(!empty($receipt_details->table_label))
					{!! $receipt_details->table_label !!}
				@endif
				{{$receipt_details->table}}
			</p>
        @endif

		<!-- Waiter info -->
		@if(!empty($receipt_details->service_staff_label) || !empty($receipt_details->service_staff))
        	<p>
				@if(!empty($receipt_details->service_staff_label))
					{!! $receipt_details->service_staff_label !!}
				@endif
				{{$receipt_details->service_staff}}
			</p>
        @endif



        <div class="word-wrap">

			<p class="text-right ">

			@if(!empty($receipt_details->brand_label) || !empty($receipt_details->repair_brand))
				@if(!empty($receipt_details->brand_label))
					<span class="pull-left">
						<strong>{!! $receipt_details->brand_label !!}</strong>
					</span>
				@endif
				{{$receipt_details->repair_brand}}<br>
	        @endif


	        @if(!empty($receipt_details->device_label) || !empty($receipt_details->repair_device))
				@if(!empty($receipt_details->device_label))
					<span class="pull-left">
						<strong>{!! $receipt_details->device_label !!}</strong>
					</span>
				@endif
				{{$receipt_details->repair_device}}<br>
	        @endif
		        
			@if(!empty($receipt_details->model_no_label) || !empty($receipt_details->repair_model_no))
				@if(!empty($receipt_details->model_no_label))
					<span class="pull-left">
						<strong>{!! $receipt_details->model_no_label !!}</strong>
					</span>
				@endif
				{{$receipt_details->repair_model_no}} <br>
	        @endif

			@if(!empty($receipt_details->serial_no_label) || !empty($receipt_details->repair_serial_no))
				@if(!empty($receipt_details->serial_no_label))
					<span class="pull-left">
						<strong>{!! $receipt_details->serial_no_label !!}</strong>
					</span>
				@endif
				{{$receipt_details->repair_serial_no}}<br>
	        @endif
			@if(!empty($receipt_details->repair_status_label) || !empty($receipt_details->repair_status))
				@if(!empty($receipt_details->repair_status_label))
					<span class="pull-left">
						<strong>{!! $receipt_details->repair_status_label !!}</strong>
					</span>
				@endif
				{{$receipt_details->repair_status}}<br>
	        @endif
	        
	        @if(!empty($receipt_details->repair_warranty_label) || !empty($receipt_details->repair_warranty))
				@if(!empty($receipt_details->repair_warranty_label))
					<span class="pull-left">
						<strong>{!! $receipt_details->repair_warranty_label !!}</strong>
					</span>
				@endif
				{{$receipt_details->repair_warranty}}
				<br>
	        @endif
	        </p>
		</div>
	</div>
</div>
@if(!empty($receipt_details->shipping_custom_field_1_label) || !empty($receipt_details->shipping_custom_field_2_label))
	<div class="row">
		<div class="col-xs-6">
			@if(!empty($receipt_details->shipping_custom_field_1_label))
				<strong>{!!$receipt_details->shipping_custom_field_1_label!!} :</strong> {!!$receipt_details->shipping_custom_field_1_value ?? ''!!}
			@endif
		</div>
		<div class="col-xs-6">
			@if(!empty($receipt_details->shipping_custom_field_2_label))
				<strong>{!!$receipt_details->shipping_custom_field_2_label!!}:</strong> {!!$receipt_details->shipping_custom_field_2_value ?? ''!!}
			@endif
		</div>
	</div>
@endif
@if(!empty($receipt_details->shipping_custom_field_3_label) || !empty($receipt_details->shipping_custom_field_4_label))
	<div class="row">
		<div class="col-xs-6">
			@if(!empty($receipt_details->shipping_custom_field_3_label))
				<strong>{!!$receipt_details->shipping_custom_field_3_label!!} :</strong> {!!$receipt_details->shipping_custom_field_3_value ?? ''!!}
			@endif
		</div>
		<div class="col-xs-6">
			@if(!empty($receipt_details->shipping_custom_field_4_label))
				<strong>{!!$receipt_details->shipping_custom_field_4_label!!}:</strong> {!!$receipt_details->shipping_custom_field_4_value ?? ''!!}
			@endif
		</div>
	</div>
@endif
@if(!empty($receipt_details->shipping_custom_field_5_label))
	<div class="row">
		<div class="col-xs-6">
			@if(!empty($receipt_details->shipping_custom_field_5_label))
				<strong>{!!$receipt_details->shipping_custom_field_5_label!!} :</strong> {!!$receipt_details->shipping_custom_field_5_value ?? ''!!}
			@endif
		</div>
	</div>
@endif
@if(!empty($receipt_details->sale_orders_invoice_no) || !empty($receipt_details->sale_orders_invoice_date))
	<div class="row">
		<div class="col-xs-6">
			<strong>@lang('restaurant.order_no'):</strong> {!!$receipt_details->sale_orders_invoice_no ?? ''!!}
		</div>
		<div class="col-xs-6">
			<strong>@lang('lang_v1.order_dates'):</strong> {!!$receipt_details->sale_orders_invoice_date ?? ''!!}
		</div>
	</div>
@endif
<div class="row">
	@includeIf('sale_pos.receipts.partial.common_repair_invoice')
</div>
<div class="row ">
	<div class="col-xs-12">
		<br/>
		<table class="table table-bordered table-no-top-cell-border table-slim">
			<thead>
				<tr style="background-color: #357ca5 !important; color: white !important; font-size: 15px !important font-weight: bold;" class="table-no-side-cell-border table-no-top-cell-border text-center">
					<td style="background-color: #357ca5 !important; color: white !important;">#</td>
					
					<td style="background-color: #357ca5 !important; color: white !important;" class="text-left" width="30%">
						{!! $receipt_details->table_product_label !!}
					</td>

					@if($receipt_details->show_cat_code == 1)
						<td style="background-color: #357ca5 !important; color: white !important;" class="text-right">{!! $receipt_details->cat_code_label !!}</td>
					@endif
					
					<td style="background-color: #357ca5 !important; color: white !important;" class="text-right">
						{!! $receipt_details->table_qty_label !!}
					</td>
					<td style="background-color: #357ca5 !important; color: white !important;" class="text-right">
						{!! $receipt_details->table_unit_price_label !!} <span class="small color-white"> ({{$receipt_details->currency['symbol']}})</span>
					</td>
					@if(!empty($receipt_details->discounted_unit_price_label))
					<td style="background-color: #357ca5 !important; color: white !important;">
						{{$receipt_details->discounted_unit_price_label}}
					</td>
					@endif
					@if(!empty($receipt_details->item_discount_label))
					<td style="background-color: #357ca5 !important; color: white !important;">
						{!! $receipt_details->item_discount_label !!}
					</td>
					@endif
					<td style="background-color: #357ca5 !important; color: white !important;" class="text-right">
						Taxable Value <span class="small color-white"> ({{$receipt_details->currency['symbol']}})</span>
					</td>

					@if(!empty($receipt_details->table_tax_headings))
					
						@foreach($receipt_details->table_tax_headings as $tax_heading)
							<td style="background-color: #357ca5 !important; color: white !important;" class="word-wrap text-right">
								{{$tax_heading}} <span class="small color-white"> ({{$receipt_details->currency['symbol']}})</span>
							</td>

							@php
								$totals[$tax_heading] = 0;
							@endphp
						@endforeach

					@endif
					
					<td style="background-color: #357ca5 !important; color: white !important;" class="text-right">
						{!! $receipt_details->table_subtotal_label !!}  <span class="small color-white"> ({{$receipt_details->currency['symbol']}})</span>
					</td>
				</tr>
			</thead>
			<tbody>
				@foreach($receipt_details->lines as $line)
					<tr>
						<td class="text-center">
							{{$loop->iteration}}
						</td>
						<td class="text-left" style="word-break: break-all;">
							@if(!empty($line['image']))
								<img src="{{$line['image']}}" alt="Image" width="50" style="float: left; margin-right: 8px;">
							@endif
                            {{$line['name']}} {{$line['product_variation']}} {{$line['variation']}} 
                            @if(!empty($line['sub_sku'])), {{$line['sub_sku']}} @endif @if(!empty($line['brand'])), {{$line['brand']}} @endif
                            @if(!empty($line['sell_line_note']))
                            <br>
                            <small class="text-muted">
                            {!!$line['sell_line_note']!!}
                        	</small>
                            @endif
                            @if(!empty($line['lot_number']))<br> {{$line['lot_number_label']}}:  {{$line['lot_number']}} @endif 
                            @if(!empty($line['product_expiry'])), {{$line['product_expiry_label']}}:  {{$line['product_expiry']}} @endif 

                            @if(!empty($line['warranty_name'])) <br><small>{{$line['warranty_name']}} </small>@endif @if(!empty($line['warranty_exp_date'])) <small>- {{@format_date($line['warranty_exp_date'])}} </small>@endif
                            @if(!empty($line['warranty_description'])) <small> {{$line['warranty_description'] ?? ''}}</small>@endif

                            @if($receipt_details->show_base_unit_details && $line['quantity'] && $line['base_unit_multiplier'] !== 1)
                            <br><small>
                            	1 {{$line['units']}} = {{$line['base_unit_multiplier']}} {{$line['base_unit_name']}} <br>
                            	{{$line['unit_price_inc_tax']}} x {{$line['quantity']}} = {{$line['line_total']}}
                            </small>
                            @endif
                        </td>

						@if($receipt_details->show_cat_code == 1)
	                        <td class="text-right">
	                        	@if(!empty($line['cat_code']))
	                        		{{$line['cat_code']}}
	                        	@endif
	                        </td>
	                    @endif

						<td class="text-right">
							{{$line['quantity']}} {{$line['units']}}

							@if($receipt_details->show_base_unit_details && $line['quantity'] && $line['base_unit_multiplier'] !== 1)
                            <br><small>
                            	{{$line['quantity']}} x {{$line['base_unit_multiplier']}} = {{$line['orig_quantity']}} {{$line['base_unit_name']}}
                            </small>
                            @endif
						</td>
						<td class="text-right">
							{{$line['unit_price_before_discount']}}
						</td>
						@if(!empty($receipt_details->discounted_unit_price_label))
							<td class="text-right">
								{{$line['unit_price_inc_tax']}} 
							</td>
						@endif
						@if(!empty($receipt_details->item_discount_label))
						<td class="text-right">
							{{$line['total_line_discount'] ?? 00}}

							@if(!empty($line['line_discount_percent']))
								 ({{$line['line_discount_percent']}}%)
							@endif
						</td>
						@endif
						<td class="text-right">
							<span class="display_currency" data-currency_symbol="false">
								{{$line['price_exc_tax']}}
							</span>

							@php
								$totals['taxable_value'] += $line['price_exc_tax'];
							@endphp
						</td>

						@if(!empty($receipt_details->table_tax_headings))
					
						@foreach($receipt_details->table_tax_headings as $tax_heading)
							<td class="text-right word-wrap">
								@if(!empty($line['group_tax_details']))
								
								@foreach($line['group_tax_details'] as $tax_detail)
									@if(strpos($tax_detail['name'], $tax_heading) !== FALSE)
										
										@php
											$totals[$tax_heading] += $tax_detail['calculated_tax'];
										@endphp

										<span class="display_currency" data-currency_symbol="false">
										{{$tax_detail['calculated_tax']}}
										</span>
										<br/>
										<span class="small">
											{{$tax_detail['amount']}}%
										</span>
									@endif
								@endforeach

								@else
									@if(strpos($line['tax_name'], $tax_heading) !== FALSE)

									@php
										$totals[$tax_heading] += ($line['tax_unformatted'] * $line['quantity_uf']);
									@endphp

									<span class="display_currency" data-currency_symbol="false">
									{{$line['tax_unformatted'] * $line['quantity_uf']}}
									</span>
									<br/>
									<span class="small">
										{{$line['tax_percent']}}%
									</span>
									@endif
								@endif
							</td>
						@endforeach

						@endif

						<!-- @if(!empty($line->group_tax_details))
					
						@foreach($line->group_tax_details as $tax_detail)
							<td class="text-right">
								{{$line['line_discount']}}
							</td>
						@endforeach

						@endif -->

						<td class="text-right">
							{{$line['line_total']}}
						</td>
					</tr>
					{{-- @if(!empty($line['modifiers']))
						@foreach($line['modifiers'] as $modifier)
							<tr>
								<td class="text-center">
									&nbsp;
								</td>
								<td>
		                            {{$modifier['name']}} {{$modifier['variation']}} 
		                            @if(!empty($modifier['sub_sku'])), {{$modifier['sub_sku']}} @endif 
		                            @if(!empty($modifier['sell_line_note']))({!!$modifier['sell_line_note']!!}) @endif 
		                        </td>

								@if($receipt_details->show_cat_code == 1)
			                        <td>
			                        	@if(!empty($modifier['cat_code']))
			                        		{{$modifier['cat_code']}}
			                        	@endif
			                        </td>
			                    @endif

								<td class="text-right">
									{{$modifier['quantity']}} {{$modifier['units']}}
								</td>
								<td class="text-right">
									&nbsp;
								</td>
								<td class="text-center">
									&nbsp;
								</td>
								<td class="text-center">
									&nbsp;
								</td>
								<td class="text-center">
									{{$modifier['unit_price_exc_tax']}}
								</td>
								<td class="text-right">
									{{$modifier['line_total']}}
								</td>
							</tr>
						@endforeach
					@endif --}}
				@endforeach

				@php
					$lines = count($receipt_details->lines);
				@endphp

				@for ($i = $lines; $i < 5; $i++)
    				<tr>
    					<td>&nbsp;</td>
    					<td>&nbsp;</td>
    					<td>&nbsp;</td>
    					<td>&nbsp;</td>
    					<td>&nbsp;</td>
    					<td>&nbsp;</td>
    					@if(!empty($receipt_details->discounted_unit_price_label))
							<td>&nbsp;</td>
						@endif
    					@if(!empty($receipt_details->item_discount_label))
    					<td>&nbsp;</td>
    					@endif
    					@if(!empty($receipt_details->table_tax_headings))
						@foreach($receipt_details->table_tax_headings as $tax_heading)
							<td>&nbsp;</td>
						@endforeach
						@endif
    					
    					@if($receipt_details->show_cat_code == 1)
    						<td>&nbsp;</td>
    					@endif
    				</tr>
				@endfor
				<tr>
					@php
						$colspan = 4;
					@endphp
					@if($receipt_details->show_cat_code == 1)
						@php
							$colspan += 1;
						@endphp
					@endif

					@if(!empty($receipt_details->discounted_unit_price_label))
						@php
							$colspan += 1;
						@endphp
					@endif
					
					@if(!empty($receipt_details->item_discount_label))
						@php
							$colspan += 1;
						@endphp
					@endif
					<th colspan="{{$colspan}}" class="text-right" 
						style="background-color: #d2d6de !important;">
						Total
					</th>
					<th class="text-right" style="background-color: #d2d6de !important;">
						<span class="display_currency" data-currency_symbol="false">
							{{$totals['taxable_value']}}
						</span>
					</th>
					
					<!-- <td>&nbsp;</td> -->

					@if(!empty($receipt_details->table_tax_headings))
					@foreach($receipt_details->table_tax_headings as $tax_heading)
						<th class="text-right" style="background-color: #d2d6de !important;">
							<span class="display_currency" data-currency_symbol="false">
							{{$totals[$tax_heading]}}
							</span>
						</th>
					@endforeach
					@endif

					<th class="text-right" style="background-color: #d2d6de !important;">
						<span class="display_currency" data-currency_symbol="false">
							{{$receipt_details->subtotal_unformatted}}
						</span>
					</th>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<div class="row invoice-info " style="page-break-inside: avoid !important">
	<div class="col-md-6 invoice-col width-50">
		<table class="table table-slim">
			@if(!empty($receipt_details->payments))
				@foreach($receipt_details->payments as $payment)
					<tr>
						<td>{{$payment['method']}}</td>
						<td>{{$payment['amount']}}</td>
						<td>{{$payment['date']}}</td>
					</tr>
				@endforeach
			@endif
		</table>
		<b class="pull-left">@lang('lang_v1.authorized_signatory')</b>
	</div>

	<div class="col-md-6 invoice-col width-50">
		<table class="table-no-side-cell-border table-no-top-cell-border width-100 table-slim">
			<tbody>
				@if(!empty($receipt_details->total_quantity_label))
					<tr >
						<td style="width:50%">
							{!! $receipt_details->total_quantity_label !!}
						</td>
						<td class="text-right">
							{{$receipt_details->total_quantity}}
						</td>
					</tr>
				@endif
				@if(!empty($receipt_details->total_items_label))
					<tr >
						<td style="width:50%">
							{!! $receipt_details->total_items_label !!}
						</td>
						<td class="text-right">
							{{$receipt_details->total_items}}
						</td>
					</tr>
				@endif
				<tr >
					<td style="width:50%">
						{!! $receipt_details->subtotal_label !!}
					</td>
					<td class="text-right">
						{{$receipt_details->subtotal}}
					</td>
				</tr>
				
				<!-- Shipping Charges -->
				@if(!empty($receipt_details->shipping_charges))
					<tr >
						<td style="width:50%">
							{!! $receipt_details->shipping_charges_label !!}
						</td>
						<td class="text-right">
							{{$receipt_details->shipping_charges}}
						</td>
					</tr>
				@endif

				<!-- Packing Charges -->
				@if(!empty($receipt_details->packing_charge))
					<tr >
						<td style="width:50%">
							{!! $receipt_details->packing_charge_label !!}
						</td>
						<td class="text-right">
							{{$receipt_details->packing_charge}}
						</td>
					</tr>
				@endif

				<!-- Discount -->
				@if( !empty($receipt_details->discount) )
					<tr >
						<td>
							{!! $receipt_details->discount_label !!}
						</td>

						<td class="text-right">
							(-) {{$receipt_details->discount}}
						</td>
					</tr>
				@endif

				@if( !empty($receipt_details->total_line_discount) )
					<tr >
						<td>
							{!! $receipt_details->line_discount_label !!}
						</td>

						<td class="text-right">
							(-) {{$receipt_details->total_line_discount}}
						</td>
					</tr>
				@endif

				@if( !empty($receipt_details->additional_expenses) )
					@foreach($receipt_details->additional_expenses as $key => $val)
						<tr >
							<td>
								{{$key}}:
							</td>

							<td class="text-right">
								(+) {{$val}}
							</td>
						</tr>
					@endforeach
				@endif

				@if( !empty($receipt_details->reward_point_label) )
					<tr >
						<td>
							{!! $receipt_details->reward_point_label !!}
						</td>

						<td class="text-right">
							(-) {{$receipt_details->reward_point_amount}}
						</td>
					</tr>
				@endif

				@if(!empty($receipt_details->group_tax_details))
					@foreach($receipt_details->group_tax_details as $key => $value)
						<tr >
							<td>
								{!! $key !!}
							</td>
							<td class="text-right">
								(+) {{$value}}
							</td>
						</tr>
					@endforeach
				@else
					@if( !empty($receipt_details->tax) )
						<tr >
							<td>
								{!! $receipt_details->tax_label !!}
							</td>
							<td class="text-right">
								(+) {{$receipt_details->tax}}
							</td>
						</tr>
					@endif
				@endif
				
				@if( $receipt_details->round_off_amount > 0)
					<tr >
						<td>
							{!! $receipt_details->round_off_label !!}
						</td>
						<td class="text-right">
							{{$receipt_details->round_off}}
						</td>
					</tr>
				@endif

				<!-- Total -->
				<tr>
					<th style="background-color: #357ca5 !important; color: white !important" class="font-23 padding-10">
						{!! $receipt_details->total_label !!}
					</th>
					<td class="text-right font-23 padding-10" style="background-color: #357ca5 !important; color: white !important">
						{{$receipt_details->total}}
					</td>
				</tr>
				@if(!empty($receipt_details->total_in_words))
				<tr>
					<td colspan="2" class="text-right">
						<small>({{$receipt_details->total_in_words}})</small>
					</td>
				</tr>
				@endif
			</tbody>
        </table>
	</div>
</div>

<div class="border-bottom col-md-12">
    @if(empty($receipt_details->hide_price) && !empty($receipt_details->tax_summary_label) )
        <!-- tax -->
        @if(!empty($receipt_details->taxes))
        	<table class="table table-slim table-bordered">
        		<tr>
        			<th colspan="2" class="text-center">{{$receipt_details->tax_summary_label}}</th>
        		</tr>
        		@foreach($receipt_details->taxes as $key => $val)
        			<tr>
        				<td class="text-center"><b>{{$key}}</b></td>
        				<td class="text-center">{{$val}}</td>
        			</tr>
        		@endforeach
        	</table>
        @endif
    @endif
</div>

@if(!empty($receipt_details->additional_notes))
	<div class="row ">
		<div class="col-xs-12">
			<br>
			<p>{!! nl2br($receipt_details->additional_notes) !!}</p>
		</div>
	</div>
@endif

<div class="row">
		@if(!empty($receipt_details->footer_text))
		<div class="@if($receipt_details->show_barcode || $receipt_details->show_qr_code) col-xs-8 @else col-xs-12 @endif">
			{!! $receipt_details->footer_text !!}
		</div>
		@endif
		@if($receipt_details->show_barcode || $receipt_details->show_qr_code)
			<div class="@if(!empty($receipt_details->footer_text)) col-xs-4 @else col-xs-12 @endif text-center">
				{{-- Barcode --}}
				@if($receipt_details->show_barcode)
					<img class="center-block" src="data:image/png;base64,{{DNS1D::getBarcodePNG($receipt_details->invoice_no, 'C128', 2,30,array(39, 48, 54), true)}}">
				@endif

				@if($receipt_details->show_qr_code && !empty($receipt_details->qr_code_text))
					<img class="center-block mt-5" src="data:image/png;base64,{{DNS2D::getBarcodePNG($receipt_details->qr_code_text, 'QRCODE', 3, 3, [39, 48, 54])}}">
				@endif
			</div>
		@endif
	</div>

			</td>
		</tr>
	</tbody>
</table>

<style type="text/css">
	body {
		color: #000000;
	}
</style>