@extends('layouts.master')

@section('title') {{ l('Customers') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>

    </div>
    <h2>
        {{ l('Customers') }}
    </h2>        
</div>


<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Filter Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => 'accounting.customers.index', 'method' => 'GET')) !!}

<!-- input type="hidden" value="0" name="search_status" id="search_status" -->
{!! Form::hidden('search_status', null, array('id' => 'search_status')) !!}

<div class="row">
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('name', l('Name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('accounting_id', l('Accounting ID')) !!}
    {!! Form::text('accounting_id', null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('identification', l('Identification')) !!}
    {!! Form::text('identification', null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('email', l('Email')) !!}
    {!! Form::text('email', null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('customer_group_id', l('Customer Group')) !!}
    {!! Form::select('customer_group_id', array('0' => l('All', [], 'layouts')) + $customer_groupList, null, array('class' => 'form-control')) !!}
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
{!! link_to_route('customers.index', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>



<div id="div_customers">
   <div class="table-responsive">

@if ($customers->count())
<table id="customers" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{ l('ID', [], 'layouts') }}</th>
            <th class="text-left">{{ l('Accounting ID') }}</th>
            <th class="text-left">{{ l('Name') }}</th>
            <th class="text-left">{{ l('Identification') }}</th>
            <th class="text-left">{{ l('Email') }}</th>
            <th class="text-left">{{ l('Phone') }}</th>
            <th class="text-left">{{ l('Outstanding Amount') }}</th>
            <th class="text-center">{{ l('Blocked', [], 'layouts') }}</th>
            <th class="text-right"> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customers as $customer)
        <tr>
            <td>{{ $customer->id }}</td>
            <td>{{ $customer->accounting_id }}</td>
            <td>{{ $customer->name_regular }}<!-- br / -->{{-- $customer->name_commercial --}}</td>
            <td>{{ $customer->identification }}</td>
            <td>{{ $customer->address->email }}</td>
            <td>{{ $customer->address->phone }}</td>
            <td @if ( ($customer->outstanding_amount - $customer->outstanding_amount_allowed)>0 ) class="alert alert-danger" @endif>{{ $customer->as_money('outstanding_amount', $customer->currency) }}</td>
            <td class="text-center">@if ($customer->blocked) <i class="fa fa-lock" style="color: #df382c;"></i> @else <i class="fa fa-unlock" style="color: #38b44a;"></i> @endif</td>
            <td class="text-right button-pad">

                <a class="btn btn-sm btn-warning" href="{{ route('accounting.customers.edit', $customer->id ) }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{!! $customers->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $customers->total() ], 'layouts')}} </span></li></ul>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

@include('layouts/back_to_top_button')

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

@include('customers/_modal_help')

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
