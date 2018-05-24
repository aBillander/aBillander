
 <tr id="line_{{ $i }}" @if ( $line->locked ) class="locked" @endif >

    <td><input id="lines[{{ $i }}][line_sort_order]" name="lines[{{ $i }}][line_sort_order]" value="{{ ($i+1)*10 }}" class="form-control" type="text"></td>

    <td><input id="lines[{{ $i }}][lineid]"          name="lines[{{ $i }}][lineid]"          value="{{ $i }}" type="hidden">
        
        <input id="lines[{{ $i }}][line_type]"       name="lines[{{ $i }}][line_type]"       value="{{ $line->line_type }}"      type="hidden">
        <input id="lines[{{ $i }}][product_id]"      name="lines[{{ $i }}][product_id]"      value="{{ $line->product_id }}"     type="hidden">
        <input id="lines[{{ $i }}][combination_id]"  name="lines[{{ $i }}][combination_id]"  value="{{ $line->combination_id }}" type="hidden">
        <input id="lines[{{ $i }}][reference]"       name="lines[{{ $i }}][reference]"       value="{{ $line->reference }}"      type="hidden">

        <input id="lines[{{ $i }}][locked]"          name="lines[{{ $i }}][locked]"          value="{{ $line->locked }}"         type="hidden">
        <input id="lines[{{ $i }}][notes]"           name="lines[{{ $i }}][notes]"           value="{{ $line->notes }}"          type="hidden">

        <input id="lines[{{ $i }}][cost_price]"          name="lines[{{ $i }}][cost_price]"          value="{{ $line->cost_price }}" type="hidden">
        <input id="lines[{{ $i }}][unit_price]"          name="lines[{{ $i }}][unit_price]"          value="{{ $line->unit_price }}" type="hidden">
        <input id="lines[{{ $i }}][unit_customer_price]" name="lines[{{ $i }}][unit_customer_price]" value="{{ $line->unit_customer_price }}" type="hidden">
        
        <input id="lines[{{ $i }}][sales_rep_id]"        name="lines[{{ $i }}][sales_rep_id]"        value="{{ $line->sales_rep_id }}"       type="hidden"/>
        <input id="lines[{{ $i }}][commission_percent]"  name="lines[{{ $i }}][commission_percent]"  value="{{ $line->commission_percent }}" type="hidden"/>

        <input id="lines[{{ $i }}][tax_id]"              name="lines[{{ $i }}][tax_id]"              value="{{ $line->tax_id }}" type="hidden"/>

        <input id="lines[{{ $i }}][tax_percent]"         name="lines[{{ $i }}][tax_percent]"         value="{{ $line->tax_percent }}" type="hidden">
        <input id="lines[{{ $i }}][total_tax]"           name="lines[{{ $i }}][total_tax]"           value="" type="hidden">

        <!-- input id="lines[{{ $i }}][sales_equalization]"  name="lines[{{ $i }}][sales_equalization]"  value="{{ $line->sales_equalization }}" type="hidden" -->

        <input id="lines[{{ $i }}][discount_amount_tax_incl]" name="lines[{{ $i }}][discount_amount_tax_incl]" value="{{ $line->discount_amount_tax_incl }}" type="hidden">
        <input id="lines[{{ $i }}][discount_amount_tax_excl]" name="lines[{{ $i }}][discount_amount_tax_excl]" value="{{ $line->discount_amount_tax_excl }}" type="hidden">
        
@php
    $theURL    = $line->product_id > 0 ? URL::to('products').'/'.$line->product_id.'/edit' : 'javascript:void(0)' ;
    $theTarget = $line->product_id > 0 ? 'target="_blank"' : '' ;
@endphp
       <div class="form-control">@if ( $line->locked ) <i class="fa fa-lock"></i> @endif <a {{ $theTarget }} href="{{ $theURL }}">{{ $line->reference }}</a></div></td>

    <td><!-- input class="form-control" id="lines[{{ $i }}][name]" name="lines[{{ $i }}][name]" value="{{ $line->name }}" type="text" -->
    {!! Form::textarea('lines['.$i.'][name]', $line->name, array('class' => 'form-control', 'id' => 'lines['.$i.'][name]', 'rows' => '1')) !!}
    </td>

    <td><input id="lines[{{ $i }}][quantity]" name="lines[{{ $i }}][quantity]" class="form-control text-right" onkeyup="calculate_line({{ $i }})" onchange="calculate_line({{ $i }})" onclick="this.select()" autocomplete="off" value="{{ $line->quantity }}" type="text"></td>

    <td>@if ( !$line->locked )
        <button class="btn btn-md btn-danger" type="button" onclick="$('#line_{{ $i }}').remove();calculate_document();">
            <span class="fa fa-trash"></span></button>
        @endif
    </td>



    <td><input id="lines[{{ $i }}][unit_final_price]" name="lines[{{ $i }}][unit_final_price]" value="{{ $line->unit_final_price }}" class="form-control text-right" onkeyup="calculate_line_price({{ $i }})" onclick="this.select()" autocomplete="off" type="text"></td>

    <td><input id="lines[{{ $i }}][unit_final_price_tax_inc]" name="lines[{{ $i }}][unit_final_price_tax_inc]" value="{{ $line->unit_final_price_tax_inc }}" class="form-control text-right" onkeyup="calculate_line_price_tax_inc({{ $i }})" onclick="this.select()" autocomplete="off" type="text"></td>

    <td><input id="lines[{{ $i }}][discount_percent]" name="lines[{{ $i }}][discount_percent]" value="{{ $line->discount_percent }}" class="form-control text-right" onkeyup="calculate_line({{ $i }})" onclick="this.select()" autocomplete="off" type="text"></td>

    <td id="unit_net_price_{{ $i }}" class="text-center">
    {{ $line->unit_final_price*(1.0 - $line->discount_percent/100.0)}}<br />({{ $line->unit_final_price_tax_inc*(1.0 - $line->discount_percent/100.0)}})</td>

    <td id="discount_amount_{{ $i }}" class="text-center">
    {{ $line->discount_amount_tax_excl }}<br />({{ $line->discount_amount_tax_incl }})</td>

    <td>  
      {{ $line->tax->name }} ({{ $line->tax_percent }}%)</td>

    <td><input class="form-control text-right" id="lines[{{ $i }}][total_tax_excl]" name="lines[{{ $i }}][total_tax_excl]" value="" type="hidden">
    <input class="form-control text-right" id="lines[{{ $i }}][total_tax_incl]" name="lines[{{ $i }}][total_tax_incl]" value="" onfocus="this.blur()" type="text"></td>

    <td>
        {{ Form::checkbox('lines['. $i .'][sales_equalization]', 1, (bool) $line->sales_equalization, ['class' => 'field']) }}
    </td>

</tr>
