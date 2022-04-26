@extends('layouts.master')

@section('title') {{ l('Product Categories') }} @parent @endsection


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        @if ( $parentId>0 )
        <a href="{{ URL::to('categories') }}" class="btn btn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Product Categories') }}</a>
        @else
            @if ( 0 && AbiConfiguration::get('ALLOW_PRODUCT_SUBCATEGORIES') && $categories->count() )
                <a xhref="{{ URL::to('categories') }}" class="btn btn-sm btn-success toggle-children"><i class="fa fa-sitemap"></i> {{ l('Expand / Collapse') }}</a>
            @endif
        @endif
        <a href="{{ URL::to('categories/'.$parentId.'/subcategories/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>

        @if ( $parentId==0 )
        <a href="{{ route('categories.import') }}" class="btn btn-sm btn-warning" 
                title="{{l('Import', [], 'layouts')}}"><i class="fa fa-ticket"></i> {{l('Import', [], 'layouts')}}</a>

        <a href="{{ route('categories.export') }}" class="btn btn-sm btn-grey" 
                title="{{l('Export', [], 'layouts')}}"><i class="fa fa-file-excel-o"></i> {{l('Export', [], 'layouts')}}</a>
        @endif

    </div>
    <h2>
        @if ( $parentId>0 )
        <a class="btn btn-sm alert-success" href="{{ URL::to('categories') }}" title="{{ l('Product Categories') }}"><i class="fa fa-list"></i></a> <span style="color: #cccccc;">/</span> {{ $parent->name }}
        @else
        {{ l('Product Categories') }}
        @endif
    </h2>        
</div>

<div id="div_categories">
   <div class="table-responsive">

@if ($categories->count())
<table id="categories" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left button-pad" style="width: 35px">{{l('ID', [], 'layouts')}}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('Drag to Sort.', 'layouts') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a></th>
            <th class="text-left" style="width: 35px"> </th>
            <th class="text-left">{{l('Category Name')}}</th>
            <th class="text-left">{{l('Webshop ID')}}</th>
            <th class="text-center">{{l('Publish to web?')}}</th>
            <th class="text-center">{{l('Active', [], 'layouts')}}</th>
            <th class="text-right"> </th>
        </tr>
    </thead>
    <tbody class="sortable ui-sortable">
        @foreach ($categories as $category)
        <tr data-id="{{ $category->id }}" data-sort-order="{{ $category->position }}">
            <td class="button-pad">[{{ $category->id }}] {{ $category->position }}</td>
            <td> </td>
            <td>{{ $category->name }}</td>
            
            <td>{{ $category->webshop_id }}</td>
            
            <td class="text-center">@if ($category->publish_to_web) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
            
            <td class="text-center">@if ($category->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

            <td class="text-right">
                @if (  is_null($category->deleted_at) )
                @if (  AbiConfiguration::get('ALLOW_PRODUCT_SUBCATEGORIES') && $parentId==0 )
                <a class="btn btn-sm btn-blue" href="{{ URL::to('categories/' . $category->id . '/subcategories') }}" title="{{l('Show Sub-Categories')}}"><i class="fa fa-folder-open-o"></i></a>
                @endif
                @if (  AbiConfiguration::get('ALLOW_PRODUCT_SUBCATEGORIES') && $parentId>0 )
                <a class="btn btn-sm btn-lightblue" href="{{ route('category.products', $category->id) }}" title="{{l('Show Products')}}"><i class="fa fa-cubes"></i></a>
                @endif
                <a class="btn btn-sm btn-warning" href="{{ URL::to('categories/' . $parentId . '/subcategories/' . $category->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('categories/' . $parentId . '/subcategories/' . $category->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Categories') }} :: ({{$category->id}}) {{ $category->name }} " 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('categories/' . $category->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('categories/' . $category->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
            </td>
        </tr>



        @endforeach
    </tbody>
</table>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

@endsection

@include('layouts/modal_delete')


@section('scripts')  @parent 

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">

    $(document).ready(function() {
        //
        sortableCategories();

    });


    function sortableCategories() {

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
            url: "{{ route('categories.sortlines') }}",
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
