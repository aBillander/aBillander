@extends('layouts.master')

@section('title') {{ l('Price Lists') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('pricelists/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Price Lists') }}
    </h2>        
</div>

<div id="div_pricelists">
   <div class="table-responsive">

@if ($pricelists->count())
<table id="pricelists" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th class="text-left">{{l('Price List Name')}}</th>
            <th class="text-left">{{l('Currency')}}</th>
            <th class="text-left">{{l('Price List Type')}}</th>
            <th class="text-left">{{l('Price is Tax Included?')}}</th>
            <th class="text-right"> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pricelists as $pricelist)
        <tr>
            <td>{{ $pricelist->id }}</td>
            <td>{{ $pricelist->name }}</td>
            <td>{{ $pricelist->currency->name }}</td>
		    <td>{{ $pricelist->getTypeVerbose() }}</td>
            <td>@if ($pricelist->price_is_tax_inc) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
            
            <td class="text-right">
                @if (  is_null($pricelist->deleted_at))

                <a class="btn btn-sm btn-blue" href="{{ URL::to('pricelists/' . $pricelist->id . '/pricelistlines') }}" title="{{l('Show Price List Lines')}}"><i class="fa fa-folder-open-o"></i></a>

                <a class="btn btn-sm btn-grey" href="{{ URL::route('pricelists.import', [$pricelist->id] ) }}" title="{{l('Import', [], 'layouts')}}"><i class="fa fa-upload"></i></a>

                <a class="btn btn-sm btn-info set-default-prices" data-html="false" data-toggle="modal" 
                        href="{{ URL::route('pricelist.default', [$pricelist->id] ) }}" 
                        data-content="{{l('You are going to CHANGE all Product default Prices. Are you sure?')}}" 
                        data-title="{{ l('Price Lists') }} :: ({{$pricelist->id}}) {{ $pricelist->name }}" 
                        onClick="return false;" title="{{l('Set these Prices as Default')}}"><i class="fa fa-superpowers"></i></a>
                
                <!-- div class="btn-group">
                  <a href="#" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-plus"></i> {{l('Actions', [], 'layouts')}} &nbsp;<span class="caret"></span></a>
                  <ul class="dropdown-menu" xstyle="color: #333333; background-color: #ffffff;">
                    <li><a href="#" xstyle="color: #333333; background-color: #ffffff;"><i class="fa fa-chevron-circle-up"></i> {{l('Export', [], 'layouts')}}</a></li>
                    <li><a href="{{ URL::route( 'pricelists.import', [$pricelist->id] ) }}"><i class="fa fa-chevron-circle-down"></i> {{l('Import', [], 'layouts')}}</a></li>
                    <li><a href="#"><i class="fa fa-undo"></i> {{l('Reset', [], 'layouts')}}</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div -->

                <a class="btn btn-sm btn-warning" href="{{ URL::to('pricelists/' . $pricelist->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('pricelists/' . $pricelist->id ) }}" 
                		data-content="{{l('You are going to PERMANENTLY delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Price Lists') }} :: ({{$pricelist->id}}) {{ $pricelist->name }}" 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('pricelists/' . $pricelist->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('pricelists/' . $pricelist->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
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




@include('price_lists/_modal_set_prices')

@include('layouts/modal_delete')
