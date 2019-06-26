

<!-- Customer Users --><!-- http://www.w3schools.com/bootstrap/bootstrap_ref_js_collapse.asp -->

<div id="panel_customer_users_detail">

    <div class="page-header">
        <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('customers/' . $customer->id . '/addresses/create') . '?back_route=' . urlencode('customers/' . $customer->id . '/edit#addressbook') }}" class=" hide btn btn-sm btn-success" 
                    title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
        </div>
        <h3>
            <span style="color: #dd4814;">{{ l('Users', 'customerusers') }}</span> <span style="color: #cccccc;">/</span> {{ $customer->name_regular }}
        </h3>        
    </div>

    <div id="div_aBook">
       <div class="table-responsive">

    @if ($customer->users->count())
    <table id="aBook" class="table table-hover">
        <thead>
            <tr>
                <th class="text-left">{{l('ID', [], 'layouts')}}</th>
                <th class="text-left">{{ l('Name', 'customerusers') }}</th>
                <th class="text-left">{{ l('Surname', 'customerusers') }}</th>
                <th class="text-left">{{ l('Email', 'customerusers') }}</th>
                <th class="text-left">{{ l('Address', 'customerusers') }}</th>
                <th class="text-center">{{l('Is Principal?', 'customerusers')}}</th>
                <th class="text-center">{{l('Active', [], 'layouts')}}</th>
                <th class="text-right"> </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customer->users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->firstname }}</td>
                <td>{{ $user->lastname }}</td>
                <td>{{ $user->email }}</td>
                <td>
@if ( $user->address )
                    {{ $user->address->alias }}<br />
                    {{ $user->address->name_commercial }}<br />
                    {{ $user->address->address1 }} {{ $user->address->address2 }}<br />
                    {{ $user->address->postcode }} {{ $user->address->city }}, {{ $user->address->state->name }}<br />
                    {{ $user->address->country->name }}
@endif
                </td>
                <td class="text-center">@if ($user->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
                <td class="text-center">@if ($user->is_principal) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
                <td class="text-left">

                    <a class="btn xbtn-sm alert-info" href="{{ route('customer.impersonate', [$customer->user->id]) }}" title="{{ l('Impersonate', 'customerusers') }}" target="_blank"><i class="fa fa-clock-o alert-success"></i> {{ l('Impersonate', 'customerusers') }}</a>
{{--
                    @if (  is_null($user->deleted_at))
                    <a class="btn btn-sm btn-blue mail-item" data-html="false" data-toggle="modal" 
                            xhref="{{ URL::to('customers/' . $customer->id) . '/mail' }}" 
                            href="{{ URL::to('mail') }}" 
                            data-to_name = "{{ $user->firstname }} {{ $user->lastname }}" 
                            data-to_email = "{{ $user->email }}" 
                            data-from_name = "{{ abi_mail_from_name() }}" 
                            data-from_email = "{{ abi_mail_from_address() }}" 
                            onClick="return false;" title="{{l('Send eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>               
                    <a class="btn btn-sm btn-warning" href="{{ URL::to( 'customers/'.$customer->id.'/addresses/' . $user->id . '/edit?back_route=' . urlencode('customers/' . $customer->id . '/edit#addressbook') ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

                      @if ( $customer->invoicing_address_id != $user->id )
                    <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                            href="{{ URL::to('customers/'.$customer->id.'/addresses/' . $user->id . '?back_route=' . urlencode('customers/' . $customer->id . '/edit#addressbook') ) }}" 
                            data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                            data-title="{{ l('Address Book') }} :: ({{$user->id}}) {{ $user->alias }} " 
                            onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                      @endif
                    @else
                    <a class="btn btn-warning" href="{{ URL::to('customers/' . $customer->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                    <a class="btn btn-danger" href="{{ URL::to('customers/' . $customer->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                    @endif
--}}
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
<!-- Customer Users ENDS -->


@section('scripts')    @parent
<script type="text/javascript">

</script>
@endsection