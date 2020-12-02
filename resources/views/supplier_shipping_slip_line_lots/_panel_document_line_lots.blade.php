

      <div class="modal-body">

    <div id="div_lot_lines">
       <div class="table-responsive">
@php
    $pending = $document_line->quantity - $document_line->lots->sum('quantity');
@endphp

            {!! Form::hidden('line_lotable_line_id',  $document_line->id,       array('id' => 'line_lotable_line_id' )) !!}
            {!! Form::hidden('line_lotable_quantity', $document_line->quantity, array('id' => 'line_lotable_quantity')) !!}
            {!! Form::hidden('line_lotable_pending',  $pending,                 array('id' => 'line_lotable_pending' )) !!}

    <table id="document_lines" class="table table-hover">
        <thead>
            <tr>
               <th>{{l('ID', [], 'layouts')}}</th>
               <th class="text-left">{{l('Lot Number')}}</th>
               <th class="text-left">{{l('Expiry Date')}}</th>
               <th class="text-left">{{l('Quantity')}}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('Quantity referred to the Measure Unit of the Line.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a></th>
               <th class="text-right"></th>
        </thead>
        <tbody>

    @if ($document_line->lots->count())
            <!-- tr style="color: #3a87ad; background-color: #d9edf7;" -->
            

            @foreach ($document_line->lots as $lot)

            <tr>
                <td class="text-left">{{ $lot->id  }}</td>
                <td class="text-left">{{ $lot->reference  }}</td>
                <td class="text-left">
                        {{ abi_date_short($lot->expiry_at)  }}
                </td>
                <td class="text-left">
                        {{ $document_line->measureunit->quantityable($lot->quantity)  }}
                </td>
                <td class="text-right">
                    <button class="btn btn-md btn-danger remove-line-lot" data-lot_id="{{ $lot->id  }}" type="button" title="{{l('Delete', [], 'layouts')}}">
                   <i class="fa fa-trash"></i></button>
                </td>
            </tr>
            
            @endforeach

    @else
    <tr><td colspan="4">
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{l('No records found', [], 'layouts')}}
    </div>
    </td>
    <td></td></tr>
    @endif

            @if ( $pending > 0.0 )

            <tr>
                <td class="text-left">
                </td>
                <td class="text-left">
                    {!! Form::text('lot_reference', null, array('class' => 'form-control', 'id' => 'lot_reference')) !!}
                </td>
                <td class="text-left">
                        {!! Form::text('lot_expiry_at_form', null, array('class' => 'form-control', 'id' => 'lot_expiry_at_form')) !!}
                    <span class="help-block hide" id="lot_expiry_at_form_error">{{l('Must be a valid Date')}}</span>
                </td>
                <td class="text-left">
                    {!! Form::text('lot_quantity', $document_line->measureunit->quantityable( $pending ), array('class' => 'form-control', 'id' => 'lot_quantity', 'onclick' => 'this.select()')) !!}
                    <span class="help-block hide" id="lot_quantity_error">{{l('Must be less than :qty', ['qty' => $document_line->measureunit->quantityable( $pending )])}}</span>
                </td>
                <td class="text-right">
                    <button id="i_new_line" class="btn btn-md btn-success add-line-lot" type="button" title="{{l('Add New Item', [], 'layouts')}}">
                   <i class="fa fa-plus"></i></button>
                </td>
            </tr>
            
            @endif


        </tbody>
    </table>



       </div>

<div class="alert alert-dismissible alert-danger hide" id="div-lot-error">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong><span id="lot-error-messages"></span></strong>
</div>

    </div><!-- div id="div_lot_lines" ENDS -->

      </div>