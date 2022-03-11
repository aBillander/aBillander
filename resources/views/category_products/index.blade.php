@extends('layouts.master')

@section('title') {{ l('Categories - Products') }} @parent @endsection


@section('content') 

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="page-header">
            @if ( $parentId>0 )
            <div class="pull-right">
                <!-- a href="{{ URL::to('categories') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Product Categories') }}</a>
                <a href="{{ URL::to('categories/'.$parent->id.'/subcategories') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to :name', ['name' => $parent->name]) }}</a -->


                <div class="btn-group" style="margin-right: 72px">
                    <a href="#" class="btn xbtn-sm btn-default dropdown-toggle" style="background-color: #31b0d5;
border-color: #269abc;" data-toggle="dropdown" title="{{l('Back to', 'layouts')}}"><i class="fa fa-mail-reply"></i> &nbsp; {{l('Back to', 'layouts')}} &nbsp; <span class="caret"></span></a>
                    <ul class="dropdown-menu  pull-right">
                      <li><a href="{{ URL::to('categories/'.$parent->id.'/subcategories') }}"><i class="fa fa-level-up"></i> &nbsp;{{ l('Back to :name', ['name' => $parent->name]) }}</a></li>
                      <li class="divider"></li>
                      <li><a href="{{ URL::to('categories') }}">{{ l('Back to Product Categories') }}</a></li>
                      <!-- li><a href="#">Separated link</a></li -->
                    </ul>
                </div>


            </div>
            <h2><a class="btn btn-sm alert-success" href="{{ URL::to('categories') }}" title="{{ l('Product Categories') }}"><i class="fa fa-list"></i></a> <span style="color: #cccccc;">/</span> <a href="{{ URL::to('categories/'.$parent->id.'/subcategories') }}">{{ $parent->name }}</a> <span style="color: #cccccc;">/</span> {{ $category->name }} :: {{ l('Products') }}</h2>
            @else
            <div class="pull-right">
                <a href="{{ URL::to('categories') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Product Categories') }}</a>
            </div>
            <h2><a class="btn btn-sm alert-success" href="{{ URL::to('categories') }}" title="{{ l('Product Categories') }}"><i class="fa fa-list"></i></a> <span style="color: #cccccc;">/</span> {{ $category->name }} :: {{ l('Products') }}</h2>
            @endif
        </div>
    </div>
</div> 


<div id="div_products">
   <div class="table-responsive">

@if ($products->count())
<table id="products" class="table table-hover">
    <thead>
        <tr>
      <th>{{l('ID', [], 'layouts')}}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('Drag to Sort.', 'layouts') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a></th>
      <th>{{ l('Reference') }}</th>
      <th>{{-- l('Procurement type') --}}</th>
      <th colspan="2">{{ l('Product Name') }}</th>
      <!-- th>{{ l('Measure Unit') }}</th -->
            <th>{{ l('Stock') }}</th>
            <!-- th>{{ l('Cost Price') }}</th -->
            <th>{{ l('Customer Price') }}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" data-container="body" 
                        data-content="{{ AbiConfiguration::get('PRICES_ENTERED_WITH_TAX') ?
                                    l('Prices are entered inclusive of tax', [], 'appmultilang') :
                                    l('Prices are entered exclusive of tax', [], 'appmultilang') }}">
                    <i class="fa fa-question-circle abi-help"></i>
                 </a></th>
            <!-- th>{{ l('Tax') }}</th -->
            <!-- th>{{ l('Tax') }} (%)</th -->
            <!-- th>{{ l('Category') }}</th -->
            <!-- th>{{ l('Quantity decimals') }}</th>
            <th>{{ l('Manufacturing Batch Size') }}</th -->
            <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
            <th class="text-center">{{l('Active', [], 'layouts')}}</th>
            <th class="text-center">{{l('Publish to web?')}}</th>
      <th class="text-right"> </th>
    </tr>
  </thead>
  <tbody class="sortable ui-sortable">
  @foreach ($products as $product)
    <tr data-id="{{ $product->id }}" data-sort-order="{{ $product->position }}">
      <td>[{{ $product->id }}] {{ $product->position }}</td>
      <td title="{{ $product->id }}">@if ($product->product_type == 'combinable') <span class="label label-info">{{ l('Combinations') }}</span>
                @else 

                <a class="" href="{{ URL::to('products/' . $product->id . '/edit') }}" title="{{l('Go to', [], 'layouts')}}" target="_blank">{{ $product->reference }}</a>

                @endif</td>

      <td>{{ \App\Models\Product::getProcurementTypeName($product->procurement_type) }}<br />
        <span class="text-info">{{ \App\Models\Product::getMrpTypeName($product->mrp_type) }}</span>

      </td>

      <td>
@php
  $img = $product->getFeaturedImage();
@endphp
@if ($img)
              <a class="view-image" data-html="false" data-toggle="modal" 
                     href="{{ URL::to( \App\Models\Image::pathProducts() . $img->getImageFolder() . $img->filename . '-large_default' . '.' . $img->extension ) }}"
                     data-content="{{l('You are going to view a record. Are you sure?')}}" 
                     data-title="{{ l('Product Images') }} :: {{ $product->name }} " 
                     data-caption="({{$img->filename}}) {{ $img->caption }} " 
                     onClick="return false;" title="{{l('View Image')}}">

                      <img src="{{ URL::to( \App\Models\Image::pathProducts() . $img->getImageFolder() . $img->filename . '-mini_default' . '.' . $img->extension ) . '?'. 'time='. time() }}" style="border: 1px solid #dddddd;">
              </a>
@endif
      </td>

      <td>{{ $product->name }}</td>
      <!-- td>{{ $product->measureunit->name }}</td -->
            <td>{{ $product->as_quantity('quantity_onhand') }}</td>
            <!-- td>{{ $product->as_price('cost_price') }}</td -->
            <td>{{ $product->displayPrice }}</td>
            <!-- td>{{ $product->tax->name }}</td -->
            <!-- td>{{ $product->as_percentable($product->tax->percent) }}</td -->
            <!-- td>@if (isset($product->category)) {{-- $product->category->name --}} @else - @endif</td -->
            <!-- td>{{ $product->quantity_decimal_places }}</td>
            <td>{{ $product->manufacturing_batch_size }}</td -->
            <td class="text-center">
                @if ($product->notes)
                 <a href="javascript:void(0);">
                    <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                            data-content="{{ $product->notes }}">
                        <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
                    </button>
                 </a>
                @endif</td>
            <td class="text-center">@if ($product->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
            <td class="text-center">@if ($product->publish_to_web) <i class="fa fa-check-square" style="color: #2780e3;"></i> @else <i class="fa fa-square-o" style="color: #2780e3;"></i> @endif</td>
           <td class="text-right button-pad">
            </td>
    </tr>
  @endforeach
    </tbody>
</table>
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $products->count() ], 'layouts')}} </span></li></ul>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>


@include('layouts/back_to_top_button')

@endsection


@include('products._modal_view_image')


@section('scripts')  @parent 

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">

    $(document).ready(function() {
        //
        sortableProducts();

    });


    function sortableProducts() {

      // Sortable :: http://codingpassiveincome.com/jquery-ui-sortable-tutorial-save-positions-with-ajax-php-mysql
      // See: https://stackoverflow.com/questions/24858549/jquery-sortable-not-functioning-when-ajax-loaded
      $('.sortable').sortable({
          cursor: "move",
          update:function( event, ui )
          {
              $(this).children().each(function(index) {
                  if ( $(this).attr('data-sort-order') != ((index+1)*10) ) {
                      $(this).attr('data-sort-order', (index+1)*10).addClass('updated');
                  }
              });

              saveNewPositions();
          }
      });

    }

    function saveNewPositions() {
        var positions = [];
        var token = "{{ csrf_token() }}";

        $('.updated').each(function() {
            positions.push([$(this).attr('data-id'), $(this).attr('data-sort-order')]);
            $(this).removeClass('updated');
        });

        $.ajax({
            url: "{{ route('category.sortproducts') }}",
            headers : {'X-CSRF-TOKEN' : token},
            method: 'POST',
            dataType: 'json',
            data: {
                positions: positions
            },
            success: function (response) {
                console.log(response);
            }
        });
    }


</script>

@endsection

