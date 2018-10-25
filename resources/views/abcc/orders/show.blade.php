@extends('abcc.layouts.master')

@section('title') {{ l('Customer Order - Show') }} @parent @stop


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
                <!-- Button trigger modal -->
                <!-- button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal_new_address" title=" Nueva Dirección Postal ">
                  <i class="fa fa-plus"></i> Dirección
                </button -->
                <!-- a href="{{ URL::to('invoices/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Documento</a --> 
                <!-- div class="btn-group">
                    <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown" title="{{l('Add Document', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Document', [], 'layouts')}} &nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="{{ route('customer.createorder', 1) }}">{{l('Order', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                    </ul>
                </div>
                <a href="{{ URL::to('customers') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Customers') }}</a -->

                <a class="btn xbtn-sm btn-grey" href="{{ route('abcc.order.pdf', [$order->id]) }}" title="{{l('PDF Export', [], 'layouts')}}" style="margin-right: 72px"><i class="fa fa-file-pdf-o"></i> {{l('PDF Export', [], 'layouts')}}</a>

                <a href="{{ URL::to('abcc/orders') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{l('Back to History')}}</a>
            </div>
            <h2>
            	<a href="{{ route('abcc.orders.index') }}">{{l('Order History', [], 'abcc/layouts')}}</a>  &nbsp; <span style="color: #cccccc;">/</span>  &nbsp; {{ $order->document_reference }} &nbsp; <span class="badge xpull-right" style="background-color: #3a87ad; margin-right: 72px; xmargin-top: 8px;" title="{{ '' }}">{{ $order->currency->iso_code }}</span></h2>
        </div>
    </div>
</div>

<div class="container-fluid">
   <div class="row">

      <div class="col-lg-4 col-md-4 col-sm-4">

          @include('abcc.orders._panel_order')

      </div><!-- div class="col-lg-4 col-md-4 col-sm-4" -->
      
      <div class="col-lg-8 col-md-8 col-sm-8">

          @include('abcc.orders._panel_order_lines')

      </div><!-- div class="col-lg-8 col-md-8 col-sm-8" -->

   </div>
</div>
@endsection


{{--
@section('scripts')     @parent
<script type="text/javascript">
   
   $(document).ready(function() {

   });

</script>
@endsection
--}}