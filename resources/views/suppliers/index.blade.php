@extends('layouts.master')

@section('title')  {{ l('Suppliers') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('suppliers/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

        <a href="{{ route('suppliers.import') }}" class="btn btn-sm btn-warning" 
                title="{{l('Import', [], 'layouts')}}"><i class="fa fa-ticket"></i> {{l('Import', [], 'layouts')}}</a>

        <a href="{{ route('suppliers.export') }}" class="btn btn-sm btn-grey" 
                title="{{l('Export', [], 'layouts')}}"><i class="fa fa-file-excel-o"></i> {{l('Export', [], 'layouts')}}</a>

        <a href="{{ route('suppliers.products.export') }}" class="btn btn-sm btn-grey" 
                title="{{l('Products by Supplier')}}"><i class="fa fa-file-excel-o"></i> {{l('Products')}}</a>
    </div>
    <h2>
        {{ l('Suppliers') }}
    </h2>        
</div>


<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => 'suppliers.index', 'method' => 'GET')) !!}

<!-- input type="hidden" value="0" name="search_status" id="search_status" -->
{!! Form::hidden('search_status', null, array('id' => 'search_status')) !!}

<div class="row">
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('name', l('Name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
</div>

<div class=" hidden form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('reference_external', l('External Reference')) !!}
    {!! Form::text('reference_external', null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('identification', l('Identification')) !!}
    {!! Form::text('identification', null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('email', l('Email')) !!}
    {!! Form::text('email', null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2" style="display: none">
    {!! Form::label('active', l('Active?', [], 'layouts'), ['class' => 'control-label']) !!}
    {!! Form::select('active', array('-1' => l('All', [], 'layouts'),
                                          '0'  => l('No' , [], 'layouts'),
                                          '1'  => l('Yes', [], 'layouts'),
                                          ), null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2" style="padding-top: 22px">
{!! Form::submit(l('Filter', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('suppliers.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>




<div id="div_suppliers">
   <div class="table-responsive">

@if ($suppliers->count())
<table id="suppliers" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th class="text-left">{{l('Fiscal Name')}}</th>
            <th class="text-left">{{l('Identification')}}</th>
            <th class="text-left">{{ l('Email') }}</th>
            <th class="text-left">{{ l('Phone') }}</th>
            <th class="text-center">{{l('Active', [], 'layouts')}}</th>
            <th class="text-center">{{ l('Blocked', [], 'layouts') }}</th>
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
            <th class="text-right"> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($suppliers as $supplier)
        <tr>
            <td>{{ $supplier->id }}</td>
            <td>{{ $supplier->name_fiscal }}</td>
            <td>{{ $supplier->identification }}</td>
            <td>{{ $supplier->address->email }}</td>
            <td>{{ $supplier->address->phone }}</td>

            <td class="text-center">@if ($supplier->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

            <td class="text-center">@if ($supplier->blocked) <i class="fa fa-lock" style="color: #df382c;"></i> @else <i class="fa fa-unlock" style="color: #38b44a;"></i> @endif</td>

            <td class="text-center">
                @if ($supplier->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $supplier->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>

            <td class="text-right">
                @if (  is_null($supplier->deleted_at))
                
                <a class="btn btn-sm btn-blue mail-item" data-html="false" data-toggle="modal" 
                        xhref="{{ URL::to('suppliers/' . $supplier->id) . '/mail' }}" 
                        href="{{ URL::to('mail') }}" 
                        data-to_name = "{{ $supplier->address->firstname }} {{ $supplier->address->lastname }}" 
                        data-to_email = "{{ $supplier->address->email }}" 
                        data-from_name = "{{ abi_mail_from_name() }}" 
                        data-from_email = "{{ abi_mail_from_address() }}" 
                        onClick="return false;" title="{{l('Send eMail', [], 'layouts')}}"><i class="fa fa-envelope"></i></a>
{{--
                <div class="btn-group">
                    <a href="#" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" title="{{l('Add Document', [], 'layouts')}}"><i class="fa fa-plus"></i> { {-- l('Document', [], 'layouts') --} } &nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu  pull-right"">
                      <li style="
color: #3a87ad;
background-color: #d9edf7;
border-color: #bce8f1;
padding: 3px 20px;
line-height: 1.42857143;
                        ">{{l('Add Document', [], 'layouts')}}</li>
                      <li class="divider"></li>
                      <li><a href="{{ route('supplierorders.create.withsupplier', $supplier->id) }}">{{l('Order', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                      <li><a href="{{ route('suppliershippingslips.create.withsupplier', $supplier->id) }}">{{l('Shipping Slip', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                      <li><a href="{{ route('supplierinvoices.create.withsupplier', $supplier->id) }}">{{l('Invoice', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                      <li><a href="{{ route('supplierquotations.create.withsupplier', $supplier->id) }}">{{l('Quotation', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                      <!-- li><a href="#">Separated link</a></li -->
                    </ul>
                </div>
--}}
                <a class="btn btn-sm btn-warning" href="{{ URL::to('suppliers/' . $supplier->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('suppliers/' . $supplier->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Suppliers') }} :: ({{$supplier->id}}) {{ $supplier->name_commercial }}" 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('suppliers/' . $supplier->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('suppliers/' . $supplier->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{!! $suppliers->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $suppliers->total() ], 'layouts')}} </span></li></ul>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

@endsection


@section('styles')    @parent

{{-- 
 - Fix drop down button menu scroll
 - https://stackoverflow.com/questions/26018756/bootstrap-button-drop-down-inside-responsive-table-not-visible-because-of-scroll
--}}

<style>
    .table-responsive {
      overflow-x: visible !important;
      overflow-y: visible !important;
    }
</style>

@endsection

@include('layouts/modal_mail')
@include('layouts/modal_delete')

@section('scripts') @parent 

<script type="text/javascript">

$(document).ready(function() {
   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });
});

</script>

@endsection
