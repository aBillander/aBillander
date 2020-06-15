
<div id="panel_addressbook">     
{{--
         {!! Form::model($customer, array('route' => array('customers.update', $customer->id), 'method' => 'PUT', 'class' => 'form')) !!}
--}}
            <div class="panel panel-primary">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Special Addresses') }}</h3>
               </div>
               <div class="panel-body">


        <div class="row">
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('invoicing_address_id') ? 'has-error' : '' }}">
                      <label class="control-label">{{ l('Fiscal (main) Address') }}</label>
                    <select class="form-control" name="invoicing_address_id" id="invoicing_address_id" @if ( $aBook->count()<=1 ) disabled="disabled" @endif >
                        <option {{ $customer->invoicing_address_id <= 0 ? 'selected="selected"' : '' }} value="0">{{ l('-- Please, select --', [], 'layouts') }}</option>
                        @foreach ($aBook as $country)
                        <option {{ $customer->invoicing_address_id == $country->id ? 'selected="selected"' : '' }} value="{{ $country->id }}">{{ $country->alias }}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('invoicing_address_id', '<span class="help-block">:message</span>') !!}
                    <p>{{ l('This Address will appear on Invoices') }}</p>
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('shipping_address_id') ? 'has-error' : '' }}">
                      <label class="control-label">{{ l('Shipping Address') }}</label>
                    <select class="form-control" name="shipping_address_id" id="shipping_address_id" @if ( $aBook->count()<=1 ) disabled="disabled" @endif >
                        <option {{ $customer->shipping_address_id <= 0 ? 'selected="selected"' : '' }} value="0">{{ l('-- Please, select --', [], 'layouts') }}</option>
                        @foreach ($aBook as $country)
                        <option {{ $customer->shipping_address_id == $country->id ? 'selected="selected"' : '' }} value="{{ $country->id }}">{{ $country->alias }}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('shipping_address_id', '<span class="help-block">:message</span>') !!}
                    <p>{{ l('Default Shipping Address') }}</p>
                  </div>
        </div>


               </div>
                  @if ( $aBook->count()>1 )
               <div class="panel-footer text-right">
                  <input type="hidden" value="addressbook" name="tab_name" id="tab_name">
{{--                   <button class="btn xbtn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{l('Save & Complete', [], 'layouts')}}
                  </button>
--}}               </div>
                  @endif
            </div>
{{--         {!! Form::close() !!}    --}}


<!-- Address Book --><!-- http://www.w3schools.com/bootstrap/bootstrap_ref_js_collapse.asp -->

<div id="panel_addressbook_detail">

    <div class="page-header">
{{--         <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('customers/' . $customer->id . '/addresses/create') . '?back_route=' . urlencode('customers/' . $customer->id . '/edit#addressbook') }}" class="btn btn-sm btn-success" 
                    title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
        </div>
--}}        <h3>
            <span style="color: #dd4814;">{{ l('Address Book') }}</span> <span style="color: #cccccc;">/</span> {{ $customer->name_regular }}
        </h3>        
    </div>

    <div id="div_aBook">
       <div class="table-responsive">

    @if ($aBook->count())
    <table id="aBook" class="table table-hover">
        <thead>
            <tr>
                <th class="text-left">{{l('ID', [], 'layouts')}}</th>
                <th class="text-left">{{ l('Alias') }}</th>
                <th class="text-left">{{ l('Address') }}</th>
                <th class="text-left">{{ l('Contact') }}</th>
                <th class="text-left">{{ l('Shipping Method') }}</th>
                <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
                <th class="text-center">{{l('Active', [], 'layouts')}}</th>
                <th class="text-right button-pad"> </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($aBook as $addr)
            <tr>
                <td>{{ $addr->id }}</td>
                <td>{{ $addr->alias }}<br />
                      @if ( $customer->invoicing_address_id == $addr->id )<span class="label label-success">{{ l('Fiscal') }}</span>@endif
                      @if ( $customer->shipping_address_id  == $addr->id )<span class="label label-warning">{{ l('Shipping') }}</span>@endif
                </td>
                <td>{{ $addr->name_commercial }}<br />
                    {{ $addr->address1 }} {{ $addr->address2 }}<br />
                    {{ $addr->postcode }} {{ $addr->city }}, {{ $addr->state->name }}<br />
                    {{ $addr->country->name }}
                </td>
                <td>{{ $addr->firstname }} {{ $addr->lastname }}<br />
                    {{ $addr->phone }} &nbsp; {{ $addr->phone_mobile }}<br />
                    {{ $addr->email }}
                </td>
                <td>{{ optional($addr->shippingmethod)->name }}<br />{{-- optional($addr->getShippingMethod())->name --}}</td>
                <td class="text-center">
                    @if ($addr->notes) 
                         <a href="javascript:void(0);">
                            <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                                    data-content="{{ $addr->notes }}">
                                <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                            </button>
                         </a>
                    @endif
                </td>
                <td class="text-center">@if ($addr->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
                <td class="text-right button-pad">
                </td>
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
    </div>
{{--
@include('layouts/modal_mail')
@include('layouts/modal_delete')
--}}
</div>
<!-- Address Book ENDS -->

</div>


@section('scripts')    @parent
<script type="text/javascript">

</script>
@endsection