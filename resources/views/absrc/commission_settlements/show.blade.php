@extends('absrc.layouts.master')

@section('title') {{ l('Commission Settlements - Show') }} @parent @stop


@section('content')

<div class="row">
    <div class="col-md-12">

<div class="page-header">
    <div class="pull-right" xstyle="padding-top: 4px;">

        <a href="{{ route('absrc.commissionsettlements.index') }}" class="btn xbtn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Commission Settlements') }}</a>
    </div>
    <h2>
        <a href="{{ route('absrc.commissionsettlements.index') }}">{{ l('Commission Settlements') }}</a> <span style="color: #cccccc;">/</span> {{$settlement->name}} 

        <span class=" hide lead well well-sm">
        <a title=" {{ l('Bank') }} " href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-success text-center" data-toggle="popover" data-placement="right" title="" data-content="{{ optional($settlement->bankaccount)->bank_name }}" xdata-original-title="Datos de Facturación">
                        <i class="fa fa-info-circle"></i>
                    </button>
                 </a> 
        </span>
        <!-- span style="color: #cccccc;">::</span --> {{ abi_date_short($settlement->document_date) }} 


@if ( $settlement->status == "pending" )
              <button type="button" class="btn btn-sm alert-danger" title="{{l('')}}">
                  <i class="fa fa-hand-stop-o"></i> {{$settlement->status_name}}
              </button>
@endif
@if ( $settlement->paid_documents_only > 0 )
              <span class="badge" style="background-color: #3a87ad;" title="{{l('Paid Documents only')}}"><i class="fa fa-money"></i></span>
@endif


        <span class="badge" style="background-color: #3a87ad;" title="">{{ $settlement->scheme }}</span>

@if ( $settlement->group_vouchers )
              <button type="button" class="btn btn-xs btn-info" title="{{l('One Voucher for Customer and Date')}}">
                  <i class="fa fa-object-group"></i>
              </button>
@endif

    </h2>


    <h2>
        {{$settlement->salesrep->name}} <span class="btn btn-xs btn-grey" title="{{l('Commission Percent')}}">{{ $settlement->as_percentable( $settlement->salesrep->commission_percent ) }}%</span>  <span style="color: #cccccc;">::</span> 

        <span class=" hide lead well well-sm">
        <a title=" {{ l('Bank') }} " href="javascript:void(0);">
                    <button type="button" class="btn btn-xs btn-success text-center" data-toggle="popover" data-placement="right" title="" data-content="{{ optional($settlement->bankaccount)->bank_name }}" xdata-original-title="Datos de Facturación">
                        <i class="fa fa-info-circle"></i>
                    </button>
                 </a> 
        </span>
        <!-- span style="color: #cccccc;"> :: </span --> <span style="font-size: 24px; xfont-size: 18px;">{{ l('Settlements Period') }}:</span> {{ abi_date_short($settlement->date_from) }} - {{ abi_date_short($settlement->date_to) }} 


        <span class="badge" style="background-color: #3a87ad;" title="">{{ $settlement->scheme }}</span>

@if ( $settlement->group_vouchers )
              <button type="button" class="btn btn-xs btn-info" title="{{l('One Voucher for Customer and Date')}}">
                  <i class="fa fa-object-group"></i>
              </button>
@endif

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
                  <h3 class="panel-title"><i class="fa fa-th-list"></i> &nbsp; {{ l('Documents') }}</h3>
               </div>
                    @include('absrc.commission_settlements._panel_customer_documents')
            </div>
      </div>
   </div>

</div>


@endsection


@section('scripts') @parent 

<script type="text/javascript">

$(document).ready(function() {

      // Capture the "click" event of the link
      // https://www.bootply.com/WAkbhdKmeb
      $('.dropdown-menu>form').click(function(e){
        e.stopPropagation();
      });

});

</script>

@endsection

@section('styles')    @parent

<style>

  .dropdown-menu form {
    padding:10px;
  }

</style>

@endsection
