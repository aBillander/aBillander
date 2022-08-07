<div class="modal-dialog" role="document">
	{!! Form::open(['url' => action('SellController@updateShipping', [$transaction->id]), 'method' => 'put', 'id' => 'edit_shipping_form' ]) !!}
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">@lang('lang_v1.edit_shipping') - @if($transaction->type == 'purchase_order') {{$transaction->ref_no}} @else {{$transaction->invoice_no}} @endif</h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-6">
			        <div class="form-group">
			            {!! Form::label('shipping_details', __('sale.shipping_details') . ':*' ) !!}
			            {!! Form::textarea('shipping_details', !empty($transaction->shipping_details) ? $transaction->shipping_details : '', ['class' => 'form-control','placeholder' => __('sale.shipping_details'), 'required' ,'rows' => '4']); !!}
			        </div>
			    </div>

			    <div class="col-md-6">
			        <div class="form-group">
			            {!! Form::label('shipping_address', __('lang_v1.shipping_address') . ':' ) !!}
			            {!! Form::textarea('shipping_address',!empty($transaction->shipping_address) ? $transaction->shipping_address : '', ['class' => 'form-control','placeholder' => __('lang_v1.shipping_address') ,'rows' => '4']); !!}
			        </div>
			    </div>

			    <div class="col-md-6">
			        <div class="form-group">
			            {!! Form::label('shipping_status', __('lang_v1.shipping_status') . ':' ) !!}
			            {!! Form::select('shipping_status',$shipping_statuses, !empty($transaction->shipping_status) ? $transaction->shipping_status : null, ['class' => 'form-control','placeholder' => __('messages.please_select')]); !!}
			        </div>
			    </div>

			    <div class="col-md-6">
			        <div class="form-group">
			            {!! Form::label('delivered_to', __('lang_v1.delivered_to') . ':' ) !!}
			            {!! Form::text('delivered_to', !empty($transaction->delivered_to) ? $transaction->delivered_to : null, ['class' => 'form-control','placeholder' => __('lang_v1.delivered_to')]); !!}
			        </div>
			    </div>
			    @php
			        $custom_labels = json_decode(session('business.custom_labels'), true);

			        $shipping_custom_label_1 = !empty($custom_labels['shipping']['custom_field_1']) ? $custom_labels['shipping']['custom_field_1'] : '';

			        $is_shipping_custom_field_1_required = !empty($custom_labels['shipping']['is_custom_field_1_required']) && $custom_labels['shipping']['is_custom_field_1_required'] == 1 ? true : false;

			        $shipping_custom_label_2 = !empty($custom_labels['shipping']['custom_field_2']) ? $custom_labels['shipping']['custom_field_2'] : '';

			        $is_shipping_custom_field_2_required = !empty($custom_labels['shipping']['is_custom_field_2_required']) && $custom_labels['shipping']['is_custom_field_2_required'] == 1 ? true : false;

			        $shipping_custom_label_3 = !empty($custom_labels['shipping']['custom_field_3']) ? $custom_labels['shipping']['custom_field_3'] : '';
			        
			        $is_shipping_custom_field_3_required = !empty($custom_labels['shipping']['is_custom_field_3_required']) && $custom_labels['shipping']['is_custom_field_3_required'] == 1 ? true : false;

			        $shipping_custom_label_4 = !empty($custom_labels['shipping']['custom_field_4']) ? $custom_labels['shipping']['custom_field_4'] : '';
			        
			        $is_shipping_custom_field_4_required = !empty($custom_labels['shipping']['is_custom_field_4_required']) && $custom_labels['shipping']['is_custom_field_4_required'] == 1 ? true : false;

			        $shipping_custom_label_5 = !empty($custom_labels['shipping']['custom_field_5']) ? $custom_labels['shipping']['custom_field_5'] : '';
			        
			        $is_shipping_custom_field_5_required = !empty($custom_labels['shipping']['is_custom_field_5_required']) && $custom_labels['shipping']['is_custom_field_5_required'] == 1 ? true : false;
		        @endphp

		        @if(!empty($shipping_custom_label_1))
		        	@php
		        		$label_1 = $shipping_custom_label_1 . ':';
		        		if($is_shipping_custom_field_1_required) {
		        			$label_1 .= '*';
		        		}
		        	@endphp

		        	<div class="col-md-6">
				        <div class="form-group">
				            {!! Form::label('shipping_custom_field_1', $label_1 ) !!}
				            {!! Form::text('shipping_custom_field_1', !empty($transaction->shipping_custom_field_1) ? $transaction->shipping_custom_field_1 : null, ['class' => 'form-control','placeholder' => $shipping_custom_label_1, 'required' => $is_shipping_custom_field_1_required]); !!}
				        </div>
				    </div>
		        @endif
		        @if(!empty($shipping_custom_label_2))
		        	@php
		        		$label_2 = $shipping_custom_label_2 . ':';
		        		if($is_shipping_custom_field_2_required) {
		        			$label_2 .= '*';
		        		}
		        	@endphp

		        	<div class="col-md-6">
				        <div class="form-group">
				            {!! Form::label('shipping_custom_field_2', $label_2 ) !!}
				            {!! Form::text('shipping_custom_field_2', !empty($transaction->shipping_custom_field_2) ? $transaction->shipping_custom_field_2 : null, ['class' => 'form-control','placeholder' => $shipping_custom_label_2, 'required' => $is_shipping_custom_field_2_required]); !!}
				        </div>
				    </div>
		        @endif
		        @if(!empty($shipping_custom_label_3))
		        	@php
		        		$label_3 = $shipping_custom_label_3 . ':';
		        		if($is_shipping_custom_field_3_required) {
		        			$label_3 .= '*';
		        		}
		        	@endphp

		        	<div class="col-md-6">
				        <div class="form-group">
				            {!! Form::label('shipping_custom_field_3', $label_3 ) !!}
				            {!! Form::text('shipping_custom_field_3', !empty($transaction->shipping_custom_field_3) ? $transaction->shipping_custom_field_3 : null, ['class' => 'form-control','placeholder' => $shipping_custom_label_3, 'required' => $is_shipping_custom_field_3_required]); !!}
				        </div>
				    </div>
		        @endif
		        @if(!empty($shipping_custom_label_4))
		        	@php
		        		$label_4 = $shipping_custom_label_4 . ':';
		        		if($is_shipping_custom_field_4_required) {
		        			$label_4 .= '*';
		        		}
		        	@endphp

		        	<div class="col-md-6">
				        <div class="form-group">
				            {!! Form::label('shipping_custom_field_4', $label_4 ) !!}
				            {!! Form::text('shipping_custom_field_4', !empty($transaction->shipping_custom_field_4) ? $transaction->shipping_custom_field_4 : null, ['class' => 'form-control','placeholder' => $shipping_custom_label_4, 'required' => $is_shipping_custom_field_4_required]); !!}
				        </div>
				    </div>
		        @endif
		        @if(!empty($shipping_custom_label_5))
		        	@php
		        		$label_5 = $shipping_custom_label_5 . ':';
		        		if($is_shipping_custom_field_5_required) {
		        			$label_5 .= '*';
		        		}
		        	@endphp

		        	<div class="col-md-6">
				        <div class="form-group">
				            {!! Form::label('shipping_custom_field_5', $label_5 ) !!}
				            {!! Form::text('shipping_custom_field_5', !empty($transaction->shipping_custom_field_5) ? $transaction->shipping_custom_field_5 : null, ['class' => 'form-control','placeholder' => $shipping_custom_label_5, 'required' => $is_shipping_custom_field_5_required]); !!}
				        </div>
				    </div>
		        @endif
		        <div class="clearfix"></div>
		        <div class="col-md-12">
			        <div class="form-group">
			            {!! Form::label('shipping_note', __('lang_v1.shipping_note') . ':' ) !!}
			            {!! Form::textarea('shipping_note', null, ['class' => 'form-control','placeholder' => __('lang_v1.shipping_note') ,'rows' => '4']); !!}
			        </div>
			    </div>
		        <div class="col-md-12">
		        	<div class="form-group">
                        <label for="fileupload">
                            @lang('lang_v1.shipping_documents'):
                        </label>
                        <div class="dropzone" id="shipping_documents_dropzone"></div>
                        {{-- params for media upload --}}
					    <input type="hidden" id="media_upload_url" value="{{route('attach.medias.to.model')}}">
					    <input type="hidden" id="model_id" value="{{$transaction->id}}">
					    <input type="hidden" id="model_type" value="App\Transaction">
					    <input type="hidden" id="model_media_type" value="shipping_document">
                    </div>
		        </div>
		        <div class="col-md-12">
		        	@php
                    	$medias = $transaction->media->where('model_media_type', 'shipping_document')->all();
                    @endphp
                    @include('sell.partials.media_table', ['medias' => $medias, 'delete' => true])
		        </div>
			</div>
			@if(!empty($activities))
			  <div class="row">
			    <div class="col-md-12">
			          <strong>{{ __('lang_v1.activities') }}:</strong><br>
			          @includeIf('activity_log.activities', ['activity_type' => 'sell'])
			      </div>
			  </div>
			  @endif
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary">@lang('messages.update')</button>
		    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.cancel')</button>
		</div>
		{!! Form::close() !!}
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->