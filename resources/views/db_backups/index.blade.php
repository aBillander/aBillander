@extends('layouts.master')

@section('title')  {{ l('DB Backups') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" xstyle="padding-top: 4px;">
        <a href="{{ URL::to('dbbackups/job/edit') }}" class="btn xbtn-sm btn-success" xstyle="margin-right: 32px;" 
                title="{{l('DB Backup Job')}}"><i class="fa fa-cog"></i> &nbsp;{{l('Cron Job')}}</a>

        <a href="{{ URL::to('dbbackups/process') }}?notnotify" class="btn xbtn-sm btn-primary" style="margin-right: 32px;" 
                title="{{l('Create Data Base Backup')}}"><i class="fa fa-database"></i> &nbsp;{{l('New DB Backup')}}</a>
    </div>
    <h2>
        {{ l('DB Backups') }} <span style="color: #cccccc;">::</span> <span class=" hide lead well well-sm alert-warning"> {{ $bk_folder }} </span> <button class="btn btn-grey" style="margin-left: 22px">{{l('Found :nbr record(s)', [ 'nbr' => count($listing) ], 'layouts')}}</button>
    </h2>
</div>


<div class="container-fluid">
   <div class="row">

      <div class="col-lg-2 col-md-2 col-sm-2">
        {{-- Poor man offeset --}}

{!! Form::open(array('route' => 'dbbackups.configurations.update', 'class' => 'form')) !!}

<div class="panel panel-info" id="panel_purchases">
   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Configuration') }}</h3>
   </div>
   <div class="panel-body">

<!-- Backups Configs -->

        <div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('MAX_DB_BACKUPS') ? 'has-error' : '' }}">
                     {{ l('Max Backups') }}
                     {!! Form::text('MAX_DB_BACKUPS', old('MAX_DB_BACKUPS', $MAX_DB_BACKUPS), array('class' => 'form-control', 'id' => 'MAX_DB_BACKUPS')) !!}
                     {!! $errors->first('MAX_DB_BACKUPS', '<span class="help-block">:message</span>') !!}
                  </div>

        </div>

        <div class="row">

             <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('MAX_DB_BACKUPS_ACTION') ? 'has-error' : '' }}">
                {{ l('Max Backups Action') }}
                {!! Form::select('MAX_DB_BACKUPS_ACTION', $actions, old('MAX_DB_BACKUPS_ACTION', $MAX_DB_BACKUPS_ACTION), array('class' => 'form-control', 'id' => 'MAX_DB_BACKUPS_ACTION')) !!}
                {!! $errors->first('MAX_DB_BACKUPS_ACTION', '<span class="help-block">:message</span>') !!}
             </div>
        </div>

        <div class="row">
           <div class="form-group col-lg-12 col-md-12 col-sm-12" id="div-compress">
                {{ l('Compress Backups?') }}
             <div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('DB_COMPRESS_BACKUP', '1', (bool) $DB_COMPRESS_BACKUP == true, ['id' => 'DB_COMPRESS_BACKUP_on']) !!}
                   {!! l('Yes', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('DB_COMPRESS_BACKUP', '0', (bool) $DB_COMPRESS_BACKUP == false, ['id' => 'DB_COMPRESS_BACKUP_off']) !!}
                   {!! l('No', [], 'layouts') !!}
                 </label>
               </div>
             </div>
           </div>
        </div>

        <div class="row">
           <div class="form-group col-lg-12 col-md-12 col-sm-12" id="div-notify">
                {{ l('Notify by email?') }}
             <div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('DB_EMAIL_NOTIFY', '1', (bool) $DB_EMAIL_NOTIFY == true, ['id' => 'DB_EMAIL_NOTIFY_on']) !!}
                   {!! l('Yes', [], 'layouts') !!}
                 </label>
               </div>
               <div class="radio-inline">
                 <label>
                   {!! Form::radio('DB_EMAIL_NOTIFY', '0', (bool) $DB_EMAIL_NOTIFY == false, ['id' => 'DB_EMAIL_NOTIFY_off']) !!}
                   {!! l('No', [], 'layouts') !!}
                 </label>
               </div>
             </div>
           </div>
        </div>

        <div class="row">
        </div>

<!-- Backups Configs ENDS -->

   </div>

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" xonclick="this.disabled=true;$('#tab_name_purchases').val('purchases');this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>

</div>

{!! Form::close() !!}

      </div>

      
      <div class="col-lg-8 col-md-8 col-sm-8">



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
            <th class="text-left">{{l('Size')}}</th>
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

            <td class="text-right">{{  abi_formatBytes( $line->getSize() ) }}</td>

            <td class="text-right button-pad">

                      <a class="btn btn-sm alert-success" href="{{ URL::to('dbbackups/' . $line->getFilename() .  '/download') }}" title="{{l('Download')}}"><i class="fa fa-download"></i></a>

                      <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                          href="{{ URL::to('dbbackups/' . $line->getFilename() . '/delete') }}" 
                          data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                          data-title="{{ l('DB Backup') }} :: {{$line->getFilename() }}" 
                          onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>

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


@include('layouts/modal_delete')


@section('styles')    @parent

<style>
  .log-showoff-format {
      color: #3a87ad;
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


