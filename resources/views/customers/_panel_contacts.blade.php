
<div id="panel_contacts">     



            <div class="panel panel-primary">
               <div class="panel-heading">
                  <h3 class="panel-title">{{ l('Contacts') }}</h3>
               </div>
               <div class="panel-body">


<!-- Contacts -->

<div id="panel_contacts_detail">

    <div id="div_contacts">
       <div class="table-responsive">

    @if ($contacts->count())
    <table id="contacts" class="table table-hover">
        <thead>
            <tr>
                <th class="text-left">{{l('ID', [], 'layouts')}}</th>
                <th class="text-left">{{ l('Name') }}</th>
                <th class="text-left">{{ l('Phone') }}</th>
                <th class="text-left">{{ l('Email') }}</th>
                <th class="text-left">{{ l('Address') }}</th>
                <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
                <th class="text-center">{{l('Is primary?')}}</th>
                <th class="text-center">{{l('Blocked', [], 'layouts')}}</th>
                <th class="text-center">{{l('Active', [], 'layouts')}}</th>
                <th class="text-right button-pad">

            <a href="{{ URL::to('customers/' . $customer->id . '/contacts/create') . '?back_route=' . urlencode('customers/' . $customer->id . '/edit#contacts') }}" class="btn btn-sm btn-success" 
                    title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contacts as $contact)
            <tr>
                <td>{{ $contact->id }}</td>
                <td>{{ $contact->firstname }} {{ $contact->lastname }}
@if($contact->job_title)
                    <br /><span class="text-info"><em>{{ $contact->job_title }}</em></span>
@endif
@if($contact->type)
                    <br /><span class="text-danger"><em>{{ $contact->type_name }}</em></span>
@endif
                </td>
                <td>
                    {{ $contact->phone }}<br />{{ $contact->phone_mobile }}
                </td>
                <td>
                    {{ $contact->email }}<br />{{ $contact->website }}
                </td>
                <td>
@if ($contact->address)
                    {{ $contact->address->name_commercial }}<br />
                    {{ $contact->address->address1 }} {{ $contact->address->address2 }}<br />
                    {{ $contact->address->postcode }} {{ $contact->address->city }}, {{ $contact->address->state->name }}<br />
                    {{ $contact->address->country->name }}
@endif
                </td>
                <td class="text-center">
                    @if ($contact->notes) 
                         <a href="javascript:void(0);">
                            <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                                    data-content="{{ $contact->notes }}">
                                <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                            </button>
                         </a>
                    @endif
                </td>

                <td class="text-center">
                    @if ($contact->is_primary)
                        <i class="fa fa-check-square" style="color: #38b44a;"></i>
                    @else
                        <i class="fa fa-square-o" style="color: #df382c;"></i>
                    @endif
                </td>
                <td class="text-center">
                    @if ($contact->blocked)
                        <i class="fa fa-lock" style="color: #df382c;"></i>
                    @else
                        <i class="fa fa-unlock" style="color: #38b44a;"></i>
                    @endif
                </td>
                <td class="text-center">
                    @if ($contact->active)
                        <i class="fa fa-check-square" style="color: #38b44a;"></i>
                    @else
                        <i class="fa fa-square-o" style="color: #df382c;"></i>
                    @endif
                </td>

                <td class="text-right button-pad">
                    @if (  is_null($contact->deleted_at))
                    <a class="btn btn-sm btn-blue mail-item" data-html="false" data-toggle="modal" 
                            xhref="{{ URL::to('customers/' . $customer->id) . '/mail' }}" 
                            href="{{ URL::to('mail') }}" 
                            data-to_name = "{{ $contact->firstname }} {{ $contact->lastname }}" 
                            data-to_email = "{{ $contact->email }}" 
                            data-from_name = "{{ abi_mail_from_name() }}" 
                            data-from_email = "{{ abi_mail_from_address() }}" 
                            onClick="return false;" title="{{l('Send eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>               
                    <a class="btn btn-sm btn-warning" href="{{ URL::to( 'customers/'.$customer->id.'/contacts/' . $contact->id . '/edit?back_route=' . urlencode('customers/' . $customer->id . '/edit#contacts') ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>


                    <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                            href="{{ URL::to('customers/'.$customer->id.'/contacts/' . $contact->id . '?back_route=' . urlencode('customers/' . $customer->id . '/edit#contacts') ) }}" 
                            data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                            data-title="{{ l('Contacts') }} :: ({{$contact->id}}) {{ $contact->full_name }} " 
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
            <a href="{{ URL::to('customers/' . $customer->id . '/contacts/create') . '?back_route=' . urlencode('customers/' . $customer->id . '/edit#contacts') }}" class="btn btn-sm btn-success pull-right" 
                    title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
        
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{l('No records found', [], 'layouts')}}
    </div>
    @endif

       </div>
    </div>

</div>

<!-- Contacts ENDS -->


               </div>
            </div>

</div>

{{--
    @include('layouts/modal_mail')
    @include('layouts/modal_delete')
--}}


@section('scripts')    @parent
<script type="text/javascript">

</script>
@endsection