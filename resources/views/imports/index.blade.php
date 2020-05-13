@extends('layouts.master')

@section('title') {{ l('Import', 'layouts') }} @parent @stop


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right" style="padding-right: 159px">

        <a href="{{ route('categories.import') }}" class="btn btn-success" 
                title="{{l('Import', [], 'layouts')}}"><i class="fa fa-ticket"></i> {{l('Categories')}}</a>

<a href="{{ route('products.import') }}" class="btn btn-success" title="{{l('Import', [], 'layouts')}}"><i class="fa fa-file-excel-o"></i> &nbsp; {{l('Products', [], 'layouts')}}</a>


        <a href="{{ route('customers.import') }}" class="btn btn-success" 
                title="{{l('Import', [], 'layouts')}}"><i class="fa fa-users"></i> {{l('Customers', [], 'layouts')}}</a>


        <a href="{{ route('suppliers.import') }}" class="btn btn-warning" 
                title="{{l('Import', [], 'layouts')}}"><i class="fa fa-user-times"></i> {{l('Suppliers', [], 'layouts')}}</a>

{{-- 
                <a class="btn btn-sm btn-grey" href="{{ URL::route('pricelists.import', [$pricelist->id] ) }}" title="{{l('Import', [], 'layouts')}}"><i class="fa fa-upload"></i></a>

                <a class="btn btn-sm btn-grey" href="{{ URL::route('stockcounts.import', [$stockcount->id] ) }}" title="{{l('Import', [], 'layouts')}}"><i class="fa fa-upload"></i></a>
--}}

            </div>
            <h2>{{ l('Import', 'layouts') }}</h2>
        </div>
    </div>
</div>

<div class="container-fluid">
   <div class="row">

   </div>
</div>
@endsection

@section('scripts') 
<script type="text/javascript">

   $(document).ready(function() {
      //
   });

</script>

@endsection


@section('styles')

@endsection