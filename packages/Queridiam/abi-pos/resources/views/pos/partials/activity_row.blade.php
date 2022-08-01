@php
    $changes = $activity->changes;
    $attributes = $changes['attributes'] ?? null;
    $old = $changes['old'] ?? null;
    $status = $attributes['status'] ?? '';
    $payment_status = $attributes['payment_status'] ?? '';
    $sub_status = $attributes['sub_status'] ?? '';
    $shipping_status = $attributes['shipping_status'] ?? '';
    $status = in_array($sub_status, ['quotation', 'proforma']) ? $sub_status : $status;
    $final_total = $attributes['final_total'] ?? 0;

    $old_status = $old['status'] ?? '';
    $old_sub_status = $old['sub_status'] ?? '';
    $old_shipping_status = $old['shipping_status'] ?? '';
    $old_status = in_array($old_sub_status, ['quotation', 'proforma']) ? $old_sub_status : $old_status;
    $old_final_total = $old['final_total'] ?? 0;
    $old_payment_status = $old['payment_status'] ?? '';
    $update_note = $activity->getExtraProperty('update_note');
@endphp
<table class="no-border table table-slim mb-0">
@if(!empty($status) && $status != $old_status)
    <tr>
        <th class="width-50">@lang('sale.status'): </th> 
        <td class="width-50 text-left">
            @if(!empty($old_status))
                <span class="label bg-info">{{$statuses[$old_status] ?? ''}}</span> --> 
            @endif
            <span class="label bg-info">{{$statuses[$status] ?? ''}}</span>
         </td>
    </tr>
@endif

@if(!empty($shipping_status) && $shipping_status != $old_shipping_status)
    <tr>
        <th class="width-50">@lang('lang_v1.shipping_status'): </th> 
        <td class="width-50 text-left">
            @if(!empty($old_shipping_status))
                <span class="label bg-info">{{$shipping_statuses[$old_shipping_status] ?? ''}}</span> -->
            @endif
            <span class="label bg-info">{{$shipping_statuses[$shipping_status] ?? ''}}</span>
        </td>
     </tr>
@endif

@if(!empty($final_total) && $final_total != $old_final_total)
    <tr>
    <th class="width-50">@lang('sale.total'): </th> 
    <td class="width-50 text-left">
        @if(!empty($old_final_total))
            <span class="label bg-info">@format_currency($old_final_total)</span> --> 
        @endif
         <span class="label bg-info">@format_currency($final_total)</span>
     </td>
    </tr>
@endif

@if(!empty($payment_status) && $payment_status != $old_payment_status)
    <tr>
        <th class="width-50">@lang('sale.payment_status'): </th> 
        <td class="width-50 text-left">
            @if(!empty($old_payment_status))
                <span class="label bg-info">@lang('lang_v1.' . $old_payment_status)</span> --> 
            @endif
                <span class="label bg-info">@lang('lang_v1.' . $payment_status)</span>
        </td>
    </tr>
@endif

@if(!empty($update_note))
    @if(!is_array($update_note))
        <tr><td colspan="2">{{$update_note}}</td></tr>
    @endif
@endif
@if(!empty($activity->getExtraProperty('from')) && !empty($activity->getExtraProperty('to')))
    <tr>
        <td colspan="2">
            @if($activity->getExtraProperty('from') != 'completed')
                <span class="label {{$status_color_in_activity[$activity->getExtraProperty('from')]['class']}}" >
                    {{$status_color_in_activity[$activity->getExtraProperty('from')]['label']}}
                </span>
            @else
                <span class="label {{$status_color_in_activity[$activity->getExtraProperty('from')]['class']}}" >
                    {{$status_color_in_activity[$activity->getExtraProperty('from')]['label']}}
                </span>
            @endif
                &nbsp; -->
            @if($activity->getExtraProperty('to') != 'completed')
                <span class="label {{$status_color_in_activity[$activity->getExtraProperty('to')]['class']}}" >
                    {{$status_color_in_activity[$activity->getExtraProperty('to')]['label']}}
                </span>
            @else
                <span class="label {{$status_color_in_activity[$activity->getExtraProperty('to')]['class']}}" >
                    {{$status_color_in_activity[$activity->getExtraProperty('to')]['label']}}
                </span>
            @endif
        </td>
    </tr>
@endif
</table>