



               <!-- br><h4  class="text-info" xclass="panel-title">
                   {{l('Stock Availability')}}
               </h4><br -->
              <div xclass="page-header">
                  <h3>
                      <span style="color: #dd4814;">{{l('Stock Availability')}}</span> <!-- span style="color: #cccccc;">/</span>  -->
                       
                  </h3><br>        
              </div>


    <div id="div_document_availability_details">
       <div class="table-responsive">

    <table id="document_lines_availability" class="table table-hover">
        <thead>
            <tr>
               <th class="text-left">{{l('Line #')}}</th>
                        <th class="text-center">{{l('Quantity')}}</th>
                        <th class="text-left">{{l('Reference')}}</th>
               			<th class="text-left">{{l('Description')}}</th>

                        <th class="text-right">{{l('On hand')}}</th>
                        <th class="text-right">{{l('On order')}}</th>
                        <th class="text-right">{{l('Allocated')}}</th>
                        <th class="text-right">{{l('Available')}}</th>
                        <th class="text-right">{{l('-')}}</th>
                      </tr>
                    </thead>

        <tbody>

    @if ($document->lines->count())
            <!-- tr style="color: #3a87ad; background-color: #d9edf7;" -->
            

            @foreach ($document->lines->where('line_type', 'product') as $line)
            <tr>
                <td>{{$line->line_sort_order }}</td>
                <td class="text-center">{{ $line->as_quantity('quantity') }}</td>
                <td>
                <a href="{{ URL::to('products/' . $line->product_id . '/edit') }}" title="{{l('View Product')}}" target="_blank">{{ $line->reference }}</a></td>
                <td>
                {{ $line->name }}</td>

                <td class="text-right">{{ $line->as_quantityable( $line->product->quantity_onhand    ) }}</td>
                <td class="text-right">{{ $line->as_quantityable( $line->product->quantity_onorder   ) }}</td>
                <td class="text-right">{{ $line->as_quantityable( $line->product->quantity_allocated ) }}</td>
                <td class="text-right">{{ $line->as_quantityable( $line->product->quantity_available ) }}</td>
                <td class="text-right">{{ '-' }}</td>

            </tr>
            
            @endforeach

    @else
    <tr><td colspan="9">
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{l('No records found', [], 'layouts')}}
    </div>
    </td></tr>
    @endif

        </tbody>
    </table>

       </div>
    </div>


    @if ($document->lines->count())


{!! Form::open( ['method' => 'POST', 'id' => 'form-quantity-selector'] ) !!}

{!! Form::hidden('document_id', $document->id, array('id' => 'document_id')) !!}
{!! Form::hidden('customer_id', $document->customer_id, array('id' => 'customer_id')) !!}



              <div class="panel-body well" style="margin-top: 30px">

                  <h3>
                      <span style="color: #dd4814;">{{l('Create Order')}}</span> <!-- span style="color: #cccccc;">/</span>  -->
                       
                  </h3>

<div class="row">

         <div class="col-lg-4 col-md-4 col-sm-4 {{ $errors->has('order_date') ? 'has-error' : '' }}">
            <div class="form-group">
               {{ l('Date') }}
               {!! Form::text('order_date_form', abi_date_form_short( 'now' ), array('class' => 'form-control', 'id' => 'order_date_form', 'autocomplete' => 'off')) !!}
               {!! $errors->first('order_date', '<span class="help-block">:message</span>') !!}
            </div>
         </div>

         <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('order_template_id') ? 'has-error' : '' }}">
            {{ l('Template') }}
            {!! Form::select('order_template_id', $templateList, old('order_template_id', AbiConfiguration::getInt('DEF_CUSTOMER_ORDER_TEMPLATE')), array('class' => 'form-control', 'id' => 'order_template_id')) !!}
            {!! $errors->first('order_template_id', '<span class="help-block">:message</span>') !!}
         </div>

         <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('order_sequence_id') ? 'has-error' : '' }}">
            {{ l('Sequence') }}
            {!! Form::select('order_sequence_id', $sequenceList, old('order_sequence_id', AbiConfiguration::getInt('DEF_CUSTOMER_ORDER_SEQUENCE')), array('class' => 'form-control', 'id' => 'order_sequence_id')) !!}
            {!! $errors->first('order_sequence_id', '<span class="help-block">:message</span>') !!}
         </div>

</div>

{{--
<div class="row">

         <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-backorder">
           {!! Form::label('backorder', l('Create Back-Order?'), ['class' => 'control-label']) !!}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('A new Customer Order will be created if Quantity on-hand is less than Order Quantity.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
           <div>
             <div class="radio-inline">
               <label>
                 {!! Form::radio('backorder', '1', AbiConfiguration::isTrue('ALLOW_CUSTOMER_BACKORDERS'), ['id' => 'backorder_on']) !!}
                 {!! l('Yes', [], 'layouts') !!}
               </label>
             </div>
             <div class="radio-inline">
               <label>
                 {!! Form::radio('backorder', '0', AbiConfiguration::isFalse('ALLOW_CUSTOMER_BACKORDERS'), ['id' => 'backorder_off']) !!}
                 {!! l('No', [], 'layouts') !!}
               </label>
             </div>
           </div>
         </div>

</div>
--}}

                  <div class="xpanel-footer pull-right" style="margin-top: 10px">

                        <a class="btn btn-info" href="javascript:void(0);" title="{{l('Confirm', [], 'layouts')}}" onclick = "this.disabled=true;$('#form-quantity-selector').attr('action', '{{ route( 'customerquotation.single.order' )}}');$('#form-quantity-selector').submit();return false;"><i class="fa fa-shopping-bag"></i> &nbsp;{{l('Confirm', 'layouts')}}</a>
                  
                  </div>

                  </div>


{!! Form::close() !!}

    @endif
