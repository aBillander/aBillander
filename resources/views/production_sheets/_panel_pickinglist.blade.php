

@if ($sheet->customerorders()->count())
@foreach ($sheet->customerorders as $order)
            <div class="panel panel-info" id="panel_customer_orders">
               <div class="panel-heading">
                  <h3 class="panel-title">
                    <a href="{{ URL::to('customerorders/' . $order->id . '/edit') }}" title="{{l('View Order')}}" target="_blank"> {{ $order->document_reference }} </a> 
                    <span class="label label-success" title="">{{ abi_toLocale_date_short($order->created_at) }}</span> 
                     <i class="fa fa-user"></i> <a href="{{ URL::to('customers/' . $order->customer->id . '/edit') }}" title=" {{l('View Customer')}} " target="_blank">{!! $order->customerInfo() !!}</a>
                       <a href="javascript:void(0);">
                          <button type="button" class="btn btn-xs btn-grey" data-toggle="popover" data-placement="top" 
                                  data-content="{{ $order->customerCardFull() }}">
                              <i class="fa fa-address-card-o"></i>
                          </button>
                       </a>
                    <span class="label label-warning pull-right" title="">{{ $order->shippingmethod->name ?? '' }}</span>
                    </h3>
               </div>



<div class="panel-body" id="div_customer_orders">
   <div class="table-responsive">


@if ($order->customerorderlines->count())
<table id="sheets" class="table table-hover">
    <thead>
        <tr>
      <!-- th>{{l('ID', [], 'layouts')}}</th -->
      <th>{{l('Product ID')}}</th>
      <th>{{l('Product Reference')}}</th>
      <th>{{l('Product Name')}}</th>
      <th>{{l('Quantity')}}</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($order->customerorderlines as $line)
    <tr>
      <!-- td>{{ $line->id }}</td -->
      <td>{{ $line->product_id }}</td>
      <td>{{ $line->reference }}</td>
      <td>{{ $line->name }}</td>
      <td>{{ $line->as_quantity('quantity') }}</td>
    </tr>
  @endforeach
    </tbody>
</table>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>



<div class="row">

         <div class="form-group col-lg-8 col-md-8 col-sm-8 {{ $errors->has('notes') ? 'has-error' : '' }}">
            <label for="notes">{{ l('Notes', 'layouts') }}</label>
            {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '2')) !!}
            {{ $errors->first('notes', '<span class="help-block">:message</span>') }}
         </div>

</div>

</div><!-- div class="panel-body" -->




            </div>
@endforeach


<div class="panel-footer text-right">
    <a href="{{ URL::to('productionsheets/'.$sheet->id) }}" class="btn xbtn-sm btn-success"><i class="fa fa-mail-reply"></i> {{ l('Back', 'layouts') }}</a>
</div>

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

<!-- div class="panel-footer text-right">
    <a href="{{ URL::to('productionsheets/'.$sheet->id) }}" class="btn xbtn-sm btn-success"><i class="fa fa-mail-reply"></i> {{ l('Back', 'layouts') }}</a>
</div -->

