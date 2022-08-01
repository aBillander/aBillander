<!-- Edit discount Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="recurringInvoiceModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">@lang('lang_v1.subscribe') @if(!empty($transaction->subscription_no)) - {{$transaction->subscription_no}} @endif</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
				        <div class="form-group">
				        	{!! Form::label('recur_interval', __('lang_v1.subscription_interval') . ':*' ) !!}
				        	<div class="input-group">
				               {!! Form::number('recur_interval', !empty($transaction->recur_interval) ? $transaction->recur_interval : null, ['class' => 'form-control', 'required', 'style' => 'width: 50%;']); !!}
				               
				                {!! Form::select('recur_interval_type', ['days' => __('lang_v1.days'), 'months' => __('lang_v1.months'), 'years' => __('lang_v1.years')], !empty($transaction->recur_interval_type) ? $transaction->recur_interval_type : 'days', ['class' => 'form-control', 'required', 'style' => 'width: 50%;', 'id' => 'recur_interval_type']); !!}
				                
				            </div>
				        </div>
				    </div>

				    <div class="col-md-6">
				        <div class="form-group">
				        	{!! Form::label('recur_repetitions', __('lang_v1.no_of_repetitions') . ':' ) !!}
				        	{!! Form::number('recur_repetitions', !empty($transaction->recur_repetitions) ? $transaction->recur_repetitions : null, ['class' => 'form-control']); !!}
					        <p class="help-block">@lang('lang_v1.recur_repetition_help')</p>
				        </div>
				    </div>
				    @php
if (! function_exists('str_ordinal')) {
    /**
     * Append an ordinal indicator to a numeric value.
     *
     * @param  string|int  $value
     * @param  bool  $superscript
     * @return string
     */
    function str_ordinal($value, $superscript = false)
    {
        $number = abs($value);
 
        $indicators = ['th','st','nd','rd','th','th','th','th','th','th'];
 
        $suffix = $superscript ? '<sup>' . $indicators[$number % 10] . '</sup>' : $indicators[$number % 10];
        if ($number % 100 >= 11 && $number % 100 <= 13) {
            $suffix = $superscript ? '<sup>th</sup>' : 'th';
        }
 
        return number_format($number) . $suffix;
    }
}

				    	$repetitions = [];
				    	for ($i=1; $i <= 30; $i++) { 
				    		$repetitions[$i] = str_ordinal($i);
				        }
				    @endphp
				    <div class="subscription_repeat_on_div col-md-6 @if(empty($transaction->recur_interval_type)) hide @elseif(!empty($transaction->recur_interval_type) && $transaction->recur_interval_type != 'months') hide @endif">
				        <div class="form-group">
				        	{!! Form::label('subscription_repeat_on', __('lang_v1.repeat_on') . ':' ) !!}
				        	{!! Form::select('subscription_repeat_on', $repetitions, !empty($transaction->subscription_repeat_on) ? $transaction->subscription_repeat_on : null, ['class' => 'form-control', 'placeholder' => __('messages.please_select')]); !!}
				        </div>
				    </div>

				</div>
			</div>
			<div class="modal-footer">
			    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->