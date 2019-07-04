@extends('layouts.master')

@section('title')  {{ l('DB Backups') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" xstyle="padding-top: 4px;">
        <a href="{{ URL::to('dbbackups/job/edit') }}" class="btn xbtn-sm btn-success" xstyle="margin-right: 32px;" 
                title="{{l('DB Backup Job')}}"><i class="fa fa-cog"></i> &nbsp;{{l('Cron Job')}}</a>

        <a href="{{ URL::to('dbbackups/process') }}" class="btn xbtn-sm btn-primary" style="margin-right: 32px;" 
                title="{{l('Create Data Base Backup')}}"><i class="fa fa-database"></i> &nbsp;{{l('New DB Backup')}}</a>
    </div>
    <h2>
        {{ l('DB Backups') }} <span style="color: #cccccc;">::</span> <span class="lead well well-sm alert-warning"> {{ $bk_folder }} </span> <button class="btn btn-grey" style="margin-left: 22px">{{l('Found :nbr record(s)', [ 'nbr' => count($listing) ], 'layouts')}}</button>
    </h2>
</div>


<div class="container-fluid">
   <div class="row">

      <div class="col-lg-3 col-md-3 col-sm-3">
        {{-- Poor man offeset --}}
         <!-- div class="list-group">
            <a id="b_main_data" href="#" class="list-group-item active">
               <i class="fa fa-asterisk"></i>
               &nbsp; {{ l('Updates') }}
            </a>
         </div -->
      </div>

      
      <div class="col-lg-6 col-md-6 col-sm-6">



<div id="div_loggers">
   <div class="table-responsive">

@if (count($listing))
<table id="loggers" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th class="text-left">{{l('File Name')}}</th>
            <th class="text-left">{{l('Date')}}</th>
            <th class="text-left">{{l('Time')}}</th>
            <th class="text-left"> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($listing as $line)
        <tr>
            <td> </td>
            <td>{{ $line->getFilename() }}</td>
            <td>{{ abi_date_short( \Carbon\Carbon::createFromTimestamp($line->getMTime()) ) }}</td>

            <td>{{ abi_date_short( \Carbon\Carbon::createFromTimestamp($line->getMTime()), 'H:i:s' ) }}</td>

            <td class="text-right button-pad">
{{--
                      <a class="btn btn-sm btn-success" href="{{ URL::to('activityloggers/' . $logger->id) }}" title="{{l('View', [], 'layouts')}}"><i class="fa fa-eye"></i></a>
                      <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                          href="{{ URL::to('activityloggers/' . $logger->id ) }}" 
                          data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                          data-title="{{ l('aBillander LOG') }} :: ({{$logger->id}}) {{ $logger->name }}" 
                          onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
--}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{--
{!! $listing->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $listing->total() ], 'layouts')}} </span></li></ul>
--}}
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>


      </div>

   </div>
</div>

@endsection


{{-- @include('layouts/modal_delete') --}}


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


