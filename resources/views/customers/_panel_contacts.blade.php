
<div id="panel_contacts">     

         {!! Form::model($customer, array('route' => array('customers.update', $customer->id), 'method' => 'PUT', 'class' => 'form')) !!}

            <div class="panel panel-primary">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Contacts') }}</h3>
               </div>
               <div class="panel-body">


<!-- Address Book --><!-- http://www.w3schools.com/bootstrap/bootstrap_ref_js_collapse.asp -->

<div id="panel_contacts_detail">

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
                    @if (  is_null($addr->deleted_at))
                    <a class="btn btn-sm btn-blue mail-item" data-html="false" data-toggle="modal" 
                            xhref="{{ URL::to('customers/' . $customer->id) . '/mail' }}" 
                            href="{{ URL::to('mail') }}" 
                            data-to_name = "{{ $addr->firstname }} {{ $addr->lastname }}" 
                            data-to_email = "{{ $addr->email }}" 
                            data-from_name = "{{ abi_mail_from_name() }}" 
                            data-from_email = "{{ abi_mail_from_address() }}" 
                            onClick="return false;" title="{{l('Send eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>               
                    <a class="btn btn-sm btn-warning" href="{{ URL::to( 'customers/'.$customer->id.'/addresses/' . $addr->id . '/edit?back_route=' . urlencode('customers/' . $customer->id . '/edit#contacts') ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>


                    <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                            href="{{ URL::to('customers/'.$customer->id.'/addresses/' . $addr->id . '?back_route=' . urlencode('customers/' . $customer->id . '/edit#contacts') ) }}" 
                            data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                            data-title="{{ l('Address Book') }} :: ({{$addr->id}}) {{ $addr->alias }} " 
                            onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>

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

</div>
<!-- Address Book ENDS -->


               </div>

                  @if ( $aBook->count()>1 )
               <div class="panel-footer text-right">
                  <input type="hidden" value="contacts" name="tab_name" id="tab_name">
                  <button class="btn xbtn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-hdd-o"></i>
                     &nbsp; {{l('Save & Complete', [], 'layouts')}}
                  </button>
               </div>
                  @endif

            </div>
         {!! Form::close() !!}




</div>

@include('layouts/modal_mail')
@include('layouts/modal_delete')


@section('scripts')    @parent
<script type="text/javascript">

</script>
@endsection