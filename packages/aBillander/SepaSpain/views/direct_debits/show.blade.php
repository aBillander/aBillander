@extends('layouts.master')

@section('title') {{ l('SEPA Direct Debits - Show') }} @parent @stop


@section('content')

<div class="row">
    <div class="col-md-12">

<div class="page-header">
    <div class="pull-right" xstyle="padding-top: 4px;">

        <a href="{{ URL::to('productionsheets/'.$directdebit->id.'/calculate') }}" class="btn btn-magick"><i class="fa fa-file-code-o"></i> &nbsp;{{ l('SEPA XML file') }}</a>
        
        <a class="btn xbtn btn-info create-production-order" title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-money"></i> &nbsp;{{l('Set as Paid')}}</a>

        <a href="{{ route('sepasp.directdebits.index') }}" class="btn xbtn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to SEPA Direct Debits') }}</a>
    </div>
    <h2>
        <a href="{{ route('sepasp.directdebits.index') }}">{{ l('SEPA Direct Debits') }}</a> <span style="color: #cccccc;">/</span> {{$directdebit->document_reference}} 

        <span class="lead well well-sm">
        <a title=" {{ l('Bank') }} " href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-success text-center" data-toggle="popover" data-placement="right" title="" data-content="{{ optional($directdebit->bankaccount)->bank_name }}" xdata-original-title="Datos de Facturación">
                        <i class="fa fa-info-circle"></i>
                    </button>
                 </a> 
        </span>
        <span style="color: #cccccc;">::</span> {{ abi_date_short($directdebit->document_date) }} 


              <button type="button" class="btn btn-sm alert-danger" title="{{l('Need Update')}}">
                  <i class="fa fa-hand-stop-o"></i> {{$directdebit->status}}
              </button>

        <span class="badge" style="background-color: #3a87ad;" title="">{{ $directdebit->scheme }}</span>
    </h2>        
</div>

    </div>
</div> 


<div class="container-fluid">
 

   <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-1">
         {{-- Poor man offset --}}
      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-10">
            <div class="panel panel-success" id="panel_production_orders">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-th-list"></i> &nbsp; {{ l('Customer Vouchers') }}</h3>
               </div>
                    @include('sepa_es::direct_debits._panel_customer_vouchers')
            </div>
      </div>
   </div>

</div>


@endsection


@section('scripts') @parent 

<!-- script type="text/javascript">

$(document).ready(function() {
   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });
});

</script -->

@endsection
