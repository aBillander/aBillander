@extends('layouts.master')

@section('title') {{ l('Documents') }} @parent @stop


@section('content')


<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">

{{--
        <button  name="b_search_filter" id="b_search_filter" class="btn btn-sm btn-success" type="button" title="{{l('Filter Records', [], 'layouts')}}">
           <i class="fa fa-filter"></i>
           &nbsp; {{l('Filter', [], 'layouts')}}
        </button>
--}}

{{--
        <div class="btn-group xopen">
          <a href="{{ route($model_path.'.index') }}" class="btn alert-success btn-sm" title="{{l('Filter Records', [], 'layouts')}}"><i class="fa fa-money"></i> &nbsp;{{l('All', [], 'layouts')}}</a>

          <a href="#" class="btn alert-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><span class="caret"></span></a>

          <ul class="dropdown-menu">
            <li><a href="{{ route($model_path.'.index', 'invoiced_not') }}"><i class="fa fa-exclamation-triangle text-danger"></i> &nbsp; {{l('Not Invoiced')}}</a>
            </li>

            <li><a href="{{ route($model_path.'.index', 'invoiced') }}"><i class="fa fa-money text-muted"></i> &nbsp; {{l('Invoiced')}}</a>
            </li>

            <li class="divider"></li>
          </ul>
        </div>
--}}

{{--
        <a href="{{ URL::to($model_path.'/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
--}}


    </div>
    <h2>
        {{ l('Documents') }}
        @if (Request::has('invoiced'))
                    <span class="lead well well-sm alert-warning"> {{ l('Invoiced') }} </span>
        @endif
        @if (Request::has('invoiced_not'))
                    <span class="lead well well-sm alert-warning"> {{ l('Not Invoiced') }} </span>
        @endif
    </h2>        
</div>






<div id="div_documents">

   <div class="table-responsive">

<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>


</div><!-- div id="div_documents" ENDS -->

@include('layouts/back_to_top_button')

@endsection
