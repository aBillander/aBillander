@if(!empty($receipt_details->repair_checklist_label) || !empty($receipt_details->checked_repair_checklist))
	<div class="col-xs-12">
		<br>
		@if(!empty($receipt_details->repair_checklist_label))
			<b @if($receipt_details->design != 'classic') class="color-555" @endif>
				{!! $receipt_details->repair_checklist_label !!}
			</b>
		@endif <br>
		@if(!empty($receipt_details->repair_checklist))
			@php
                $checked_repair_checklist = json_decode($receipt_details->checked_repair_checklist, true);
            @endphp
		@endif
		<div class="row">
            @foreach($receipt_details->repair_checklist as $check)
                <div class="col-xs-4">
                    @if($checked_repair_checklist[$check] == 'yes')
                        <i class="fas fa-check-square text-success"></i>
                    @elseif($checked_repair_checklist[$check] == 'no')
                    	<i class="fas fa-window-close text-danger"></i>
                	@elseif($checked_repair_checklist[$check] == 'not_applicable')
                		<i class="fas fa-square"></i>
                    @endif
                    <span @if($receipt_details->design != 'classic') class="color-555" @endif>
                    	{{$check}}
                    </span>
                    <br>
                </div>
            @endforeach
        </div>
	</div>
@endif

@if(!empty($receipt_details->defects_label) || !empty($receipt_details->repair_defects))
	<div class="col-xs-12">
		<br>
		<p @if($receipt_details->design != 'classic') class="color-555" @endif>
			@if(!empty($receipt_details->defects_label))
				<strong>{!! $receipt_details->defects_label !!}</strong><br>
			@endif
			@php
                $defects = json_decode($receipt_details->repair_defects, true);
            @endphp
            @if(!empty($defects))
                @foreach($defects as $product_defect)
                    {{$product_defect['value']}}
                    @if(!$loop->last)
                        {{','}}
                    @endif
                @endforeach
            @endif
		</p>
	</div>
@endif
<!-- /.col -->