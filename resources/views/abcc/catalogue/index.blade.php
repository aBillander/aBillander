@extends('abcc.layouts.master')

@section('title') {{ l('Catalogue') }} @parent @endsection


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">

{!! Form::model(Request::all(), array('route' => 'abcc.catalogue', 'method' => 'GET', 
"class"=>"navbar-form navbar-left", "role"=>"search", "style"=>"margin-top: 0px !important; margin-bottom: 0px !important;")) !!}
           
                      <div class="form-group">

           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                  data-content="{{ l('Use terms of three (3) characters or more', 'layouts') }}">
              <i class="fa fa-question-circle abi-help"></i>
           </a>
                        {!! Form::text('term', null, array('class' => 'form-control', "placeholder"=>l("Search terms", 'layouts'))) !!}
                      </div>

                <button class="btn xbtn-sm btn-default" xstyle="margin-right: 152px" type="submit" title="{{l('Search', [], 'layouts')}}">
                   <i class="fa fa-search"></i>
                   &nbsp; {{l('Search', [], 'layouts')}}
                </button>
     
{!! Form::close() !!}


                <button  name="b_search_filter" id="b_search_filter" class="btn xbtn-sm btn-success" style="margin-right: 152px" type="button" title="{{l('Filter', [], 'layouts')}}">
                   <i class="fa fa-filter"></i>
                   &nbsp; {{l('Filter', [], 'layouts')}}
                </button>

                <!-- Button trigger modal -->
                <!-- button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal_new_address" title=" Nueva Dirección Postal ">
                  <i class="fa fa-plus"></i> Dirección
                </button -->
                <!-- a href="{{ URL::to('invoices/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Documento</a --> 
                <!-- div class="btn-group">
                    <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown" title="{{l('Add Document', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Document', [], 'layouts')}} &nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="{{ route('customerorders.create.withcustomer', 1) }}">{{l('Order', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                    </ul>
                </div>
                <a href="{{ URL::to('customers') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Customers') }}</a -->
            </div>
            <h2><!-- a href="{{ URL::to('customers') }}">{{ l('Shopping Cart') }}</a> <span style="color: #cccccc;">/</span --> {{ l('Catalogue') }}</h2>
        </div>



<div name="search_filter" id="search_filter" @if( Request::has('search_status') AND (Request::input('search_status')==1) ) style="display:block" @else style="display:none" @endif>
<div class="row" style="padding: 0 20px">
    <div class="col-md-12 xcol-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">{{ l('Search Records', [], 'layouts') }}</h3></div>
            <div class="panel-body">

                {!! Form::model(Request::all(), array('route' => 'abcc.catalogue', 'method' => 'GET')) !!}

<!-- input type="hidden" value="0" name="search_status" id="search_status" -->
{!! Form::hidden('search_status', null, array('id' => 'search_status')) !!}

<div class="row">
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('reference', l('Reference').' / EAN13') !!}
    {!! Form::text('reference', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('name', l('Product Name')) !!}
    {!! Form::text('name', null, array('class' => 'form-control')) !!}
</div>
<div class="form-group col-lg-2 col-md-2 col-sm-2" style="display: none">
    {!! Form::label('stock', l('Stock')) !!}
    {!! Form::select('stock', array('-1' => l('All', [], 'layouts'),
                                          '0'  => l('No' , [], 'layouts'),
                                          '1'  => l('Yes', [], 'layouts'),
                                          ), null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('category_id', l('Category')) !!}
    {!! Form::select('category_id', array('0' => l('All', [], 'layouts')) + $categoryList, null, array('class' => 'form-control')) !!}
</div>

<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('manufacturer_id', l('Manufacturer')) !!}
    {!! Form::select('manufacturer_id', array('0' => l('All', [], 'layouts')) + $manufacturerList, null, array('class' => 'form-control')) !!}
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
{!! link_to_route('abcc.catalogue', l('Reset', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
</div>

</div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>




    </div>
</div>

<div class="container-fluid">
   <div class="row">

      <div class="col-lg-3 col-md-3 col-sm-3">

          @include('abcc.catalogue._panel_tree')

      </div><!-- div class="col-lg-4 col-md-4 col-sm-4" -->
      
      <div class="col-lg-9 col-md-9 col-sm-9">

          @include('abcc.catalogue._panel_products' . $vparams['display_with_taxes'])

      </div><!-- div class="col-lg-8 col-md-8 col-sm-8" -->

   </div>
</div>
@endsection




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
