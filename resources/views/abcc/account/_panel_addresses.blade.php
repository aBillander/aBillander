
<div id="panel_addressbook">

         {!! Form::model($customer, array('route' => array('abcc.customer.addresses.default'), 'xmethod' => 'PUT', 'class' => 'form')) !!}

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
                  <button class="btn xbtn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{l('Save & Complete', [], 'layouts')}}
                  </button>
               </div>
                  @endif
            </div>
         {!! Form::close() !!}


<!-- Address Book --><!-- http://www.w3schools.com/bootstrap/bootstrap_ref_js_collapse.asp -->

<div id="panel_addressbook_detail">

    <div class="page-header">
        <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ route('abcc.customer.addresses.create') }}" class="btn btn-sm btn-success" 
                    title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
        </div>
        <h3>
            <span style="color: #dd4814;">{{ l('Address Book') }}</span>
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
                <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
                <th class="text-right"> </th>
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

                <td class="text-left">
                    @if (  is_null($addr->deleted_at))
                    <!-- a class="btn btn-sm btn-blue mail-item" data-html="false" data-toggle="modal" 
                            xhref="{{ URL::to('customers/' . $customer->id) . '/mail' }}" 
                            href="{{ URL::to('mail') }}" 
                            data-to_name = "{{ $addr->firstname }} {{ $addr->lastname }}" 
                            data-to_email = "{{ $addr->email }}" 
                            data-from_name = "{{ \App\Context::getContext()->user->getFullName() }}" 
                            data-from_email = "{{ \App\Context::getContext()->user->email }}" 
                            onClick="return false;" title="{{l('Send eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a -->               
                    <a class="btn btn-sm btn-warning" href="{{ route( 'abcc.customer.addresses.edit', $addr->id ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

                      @if ( $customer->invoicing_address_id != $addr->id )
                    <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                            href="{{ route( 'abcc.customer.addresses.destroy', $addr->id ) }}" 
                            data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                            data-title="{{ l('Address Book') }} :: ({{$addr->id}}) {{ $addr->alias }} " 
                            onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                      @endif
                    @else
                    <a class="btn btn-warning" href="{{ URL::to('customers/' . $customer->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                    <a class="btn btn-danger" href="{{ URL::to('customers/' . $customer->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                    @endif
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

@include('abcc.layouts.modal_mail')
@include('abcc.layouts.modal_delete')

</div>
<!-- Address Book ENDS -->

</div>


@section('scripts')    @parent
<script type="text/javascript">

</script>
@endsection
