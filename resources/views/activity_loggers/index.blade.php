@extends('layouts.master')

@section('title')  {{ l('aBillander LOG') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <!-- a href="{{ URL::to('activitylogges/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a -->
    </div>
    <h2>
        {{ l('aBillander LOG') }}
    </h2>        
</div>

<div id="div_loggers">
   <div class="table-responsive">

@if ($loggers->count())
<table id="loggers" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th class="text-left">{{l('User ID')}}</th>
            <th class="text-left">{{l('LOG Name')}}</th>
            <th class="text-left">{{l('Description')}}</th>
            <th class="text-left">{{l('Date/Time')}}</th>
            <th class="text-left"> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($loggers as $logger)
        <tr>
            <td>{{ $logger->id }}</td>
            <td>{{ $logger->user_id }} [{{ $logger->user->getFullName() }}]</td>
            <td>{{ $logger->name }}</td>
            <td>{{ $logger->description }}</td>
            <td>{{ $logger->created_at }}</td>

            <td class="text-right button-pad">

                      <a class="btn btn-sm btn-success" href="{{ URL::to('activityloggers/' . $logger->id) }}" title="{{l('View', [], 'layouts')}}"><i class="fa fa-eye"></i></a>
                      <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                          href="{{ URL::to('activityloggers/' . $logger->id ) }}" 
                          data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                          data-title="{{ l('aBillander LOG') }} :: ({{$logger->id}}) {{ $logger->name }}" 
                          onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{!! $loggers->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $loggers->total() ], 'layouts')}} </span></li></ul>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

@endsection


@include('layouts/modal_delete')


@section('styles')    @parent

<style>
  .log-showoff-format {
      color: #3a87ad;;
      font-weight: bold;
      font-style: italic;
  }

  .log-INFO-format {
      color: black;
      font-weight: bold;
  }
  .log-WARNING-format {
      color: #e7a413;
      font-weight: bold;
  }
  .log-ERROR-format {
      color: red;
      font-weight: bold;
  }
  .log-TIMER-format {
      color: blue;
      font-weight: bold;
  }
</style>

@endsection


