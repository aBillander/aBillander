@extends('layouts.master')

@section('title') {{ l('FSx-Connector - Importar Productos') }} @parent @stop


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">

        <a class="btn xbtn-sm btn-grey" style="margin-right: 21px" href="{{ route('fsxconfigurationkeys.index') }}" title="{{l('Configuration', [], 'layouts')}} {{l('Enlace FactuSOL', 'layouts')}}"><i class="fa fa-foursquare" style="color: #ffffff; background-color: #df382c; border-color: #df382c; font-size: 16px;"></i> {{l('Configuration', [], 'layouts')}}</a> 

                <a href="{{ URL::to('products') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Products') }}</a>

            </div>
            <h2><a href="{{ URL::to('products') }}">{{ l('Products') }}</a> <span style="color: #cccccc;">/</span> {{ l('Importar de FactuSOL') }}</h2>
        </div>
    </div>
</div>

<div class="container-fluid">
   <div class="row">

      @include('fsx_connector::fsx_products._left_menu')
      
      <div class="col-lg-10 col-md-10 col-sm-9">

@if ($tab_index==7)
          @include('fsx_connector::fsx_products._panel_import_products')
          @include('fsx_connector::fsx_products._panel_catalogo_factusol')
@else
          {{-- Default --}}
          @include('fsx_connector::fsx_products._panel_load_fsweb')
@endif

      </div>

   </div>
</div>
@stop

@section('scripts') 
<script type="text/javascript">

   $(document).ready(function() {

   });

</script>

@endsection

@section('styles') 

@endsection