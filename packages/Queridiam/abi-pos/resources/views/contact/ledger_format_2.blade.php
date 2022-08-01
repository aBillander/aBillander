<!-- app css -->
@if(!empty($for_pdf))
	<link rel="stylesheet" href="{{ asset('css/app.css?v='.$asset_v) }}">
@endif
<div class="col-md-6 col-sm-6 col-xs-6 @if(!empty($for_pdf)) width-50 f-left @endif" style="margin-top: 20px;">
	<p><strong>{{$contact->business->name}}</strong><br>
		@if(!empty($location))
    		{!! $location->location_address !!}
    	@else
    		{!! $contact->business->business_address !!}
    	@endif
	</p>

	<table class="table">
		<tr>
			<th style="text-align: left;">@lang('lang_v1.to')</th>
		</tr>
		<tr>
			<td>
				<p><strong>{{$contact->name}}</strong><br> {!! $contact->contact_address !!} @if(!empty($contact->email)) <br>@lang('business.email'): {{$contact->email}} @endif
					<br>@lang('contact.mobile'): {{$contact->mobile}}
					@if(!empty($contact->tax_number)) <br>@lang('contact.tax_no'): {{$contact->tax_number}} @endif
				</p>
			</td>
		</tr>
	</table>	
</div>
<div class="col-md-6 col-sm-6 col-xs-6 @if(!empty($for_pdf)) width-50 f-right @endif" style="margin-top: 20px;">
		
	<table style="margin: auto;">
		<tr><th class="text-center" style="border-top:hidden; font-size: 22px;">{{__('lang_v1.statement')}}</th></tr>
		<tr><th class="text-center">@lang('lang_v1.date')</th></tr>
		<tr><td class="text-center">{{$ledger_details['start_date']}} @lang('lang_v1.to') {{$ledger_details['end_date']}}</td></tr>
	</table>
</div>
<div class="col-md-12 col-sm-12 @if(!empty($for_pdf)) width-100 @endif">
	@php
		$amount_due = 0;
		$current_due = 0;
		$due_1_30_days = 0;
		$due_30_60_days = 0;
		$due_60_90_days = 0;
		$due_over_90_days = 0;
	@endphp
	@if(!empty($for_pdf))
	<br>
	@endif
	<table class="table table-striped @if(!empty($for_pdf)) table-pdf td-border @endif" id="ledger_table">
		<thead>
			<tr class="row-border">
				<th>@lang('lang_v1.date')</th>
				<th>@lang('lang_v1.transaction')</th>
				<th>@lang('sale.amount')</th>
				<th>@lang('lang_v1.balance')</th>
			</tr>
		</thead>
		<tbody>
			@foreach($ledger_details['ledger'] as $data)
				@php
					if(empty($data['total_due'])) {
						continue;
					}
					if($data['payment_status'] != 'paid' && !empty($data['due_date'])) {

						$amount_due += $data['total_due'];

						$days_diff = $data['due_date']->diffInDays();
						if($days_diff == 0){
							$current_due += $data['total_due'];
						} elseif ($days_diff > 0 && $days_diff <= 30) {
							$due_1_30_days += $data['total_due'];
						} elseif ($days_diff > 30 && $days_diff <= 60) {
							$due_30_60_days += $data['total_due'];
						} elseif ($days_diff > 60 && $days_diff <= 90) {
							$due_60_90_days += $data['total_due'];
						} elseif ($days_diff > 90) {
							$due_over_90_days += $data['total_due'];
						}
					}
				@endphp
				<tr @if(!empty($for_pdf) && $loop->iteration % 2 == 0) class="odd" @endif style="border:hidden;">
					<td class="row-border">{{@format_datetime($data['date'])}}</td>
					<td>@if($loop->index == 0) {{$data['type']}} @endif {{$data['ref_no']}} @if(!empty($data['due_date']) && $data['payment_status'] != 'paid') <br>@lang('lang_v1.due') {{@format_date($data['due_date'])}} @endif</td>
					<td>@format_currency($data['final_total'])</td>
					<td>@format_currency($data['total_due'])</td>
				</tr>
			@endforeach
			@if(count($ledger_details['ledger']) < 5)
				<tr style="border:hidden;"><td colspan="4">&nbsp;</td></tr>
				<tr style="border:hidden;"><td colspan="4">&nbsp;</td></tr>
				<tr style="border:hidden;"><td colspan="4">&nbsp;</td></tr>
				<tr style="border:hidden;"><td colspan="4">&nbsp;</td></tr>
				<tr style="border:hidden;"><td colspan="4">&nbsp;</td></tr>
				<tr style="border:hidden;"><td colspan="4">&nbsp;</td></tr>
				<tr style="border:hidden;"><td colspan="4">&nbsp;</td></tr>
			@endif
		</tbody>
	</table>
	<table class="table" style="margin-top: 0;">
		<tr>
			<th style="font-size: 12px;">@lang('lang_v1.current')</th>
			<th style="color: #2dce89 !important;font-size: 12px;">{{strtoupper(__('lang_v1.1_30_days_past_due'))}}</th>
			<th style="color: #ffd026 !important;font-size: 12px;">{{strtoupper(__('lang_v1.30_60_days_past_due'))}}</th>
			<th style="color: #ffa100 !important;font-size: 12px;">{{strtoupper(__('lang_v1.60_90_days_past_due'))}}</th>
			<th style="color: #f5365c !important;font-size: 12px;">{{strtoupper(__('lang_v1.over_90_days_past_due'))}}</th>
			<th style="font-size: 12px;">{{strtoupper(__('lang_v1.amount_due'))}}</th>
		</tr>
		<tr>
			<td style="text-align: center;">
				@format_currency($current_due)
			</td>
			<td style="color: #2dce89 !important; text-align: center;">
				@format_currency($due_1_30_days)
			</td>
			<td style="color: #ffd026 !important; text-align: center;">
				@format_currency($due_30_60_days)
			</td>
			<td style="color: #ffa100 !important;text-align: center;">
				@format_currency($due_60_90_days)
			</td>
			<td style="color: #f5365c !important; text-align: center;">
				@format_currency($due_over_90_days)
			</td>
			<td style="text-align: center;">
				@format_currency($amount_due)
			</td>
		</tr>
	</table>
</div>