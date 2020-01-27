@extends('layouts.master')

@section('title') {{ l('Product Categories') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        @if ( $parentId>0 )
        <a href="{{ URL::to('categories') }}" class="btn btn-sm btn-default"><i class="fa fa-mail-reply"></i> {{ l('Back to Product Categories') }}</a>
        @else
            @if ( \App\Configuration::get('ALLOW_PRODUCT_SUBCATEGORIES') && $categories->count() )
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
            <th class="text-left" style="width: 35px">{{l('ID', [], 'layouts')}}</th>
            <th class="text-left" style="width: 35px"> </th>
            <th class="text-left">{{l('Category Name')}}</th>
            <th class="text-left">{{l('Webshop ID')}}</th>
            <th class="text-center">{{l('Publish to web?')}}</th>
            <th class="text-center">{{l('Active', [], 'layouts')}}</th>
            <th class="text-right"> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $category)
        <tr class="parent">
            <td>{{ $category->id }}</td>
            <td> </td>
            <td>{{ $category->name }}</td>
            
            <td>{{ $category->webshop_id }}</td>
            
            <td class="text-center">@if ($category->publish_to_web) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
            
            <td class="text-center">@if ($category->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

            <td class="text-right">
                @if (  is_null($category->deleted_at) )
                @if (  \App\Configuration::get('ALLOW_PRODUCT_SUBCATEGORIES') && $parentId==0 )
                <a class="btn btn-sm btn-blue" href="{{ URL::to('categories/' . $category->id . '/subcategories') }}" title="{{l('Show Sub-Categories')}}"><i class="fa fa-folder-open-o"></i></a>
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

@if ( \App\Configuration::get('ALLOW_PRODUCT_SUBCATEGORIES') && $category->parent_id==0 )
        @foreach ($category->children as $child)

        <tr class="child warning" style="display: none;">
            <td> </td>
            <td>{{ $child->id }}</td>
            <td><span style="padding-left: 35px;">{{ $child->name }}</span></td>
            
            <td>{{ $child->webshop_id }}</td>
            
            <td class="text-center">@if ($child->publish_to_web) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>
            
            <td class="text-center">@if ($child->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

            <td class="text-right">
                @if (  is_null($child->deleted_at) )
                <a class="btn btn-sm btn-warning" href="{{ URL::to('categories/' . $child->parent_id . '/subcategories/' . $child->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('categories/' . $child->parent_id . '/subcategories/' . $child->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Categories') }} :: ({{$child->id}}) {{ $child->name }} " 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('categories/' . $child->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('categories/' . $child->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
            </td>
        </tr>

        @endforeach
@endif

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

@stop

@include('layouts/modal_delete')


@section('scripts')  @parent 

    <script type="text/javascript">
    
        function createCookie(name,value,days) {
            if (days) {
                var date = new Date();
                date.setTime(date.getTime()+(days*24*60*60*1000));
                var expires = "; expires="+date.toGMTString();
            }
            else var expires = "";
            document.cookie = name+"="+value+expires+"; path=/";
        }

        function readCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for(var i=0;i < ca.length;i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1,c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
            }
            return null;
        }

        function eraseCookie(name) {
            createCookie(name,"",-1);
        }

    </script>

    <script type="text/javascript">
        
        $(".toggle-children").click(function(){ 

          var c = readCookie( 'tree' );

          $(".child").toggle("slow");

          if ( c != 'expanded' )
        {
            createCookie('tree','expanded',7);
                $("tr.parent").addClass("info");
        }
          else
        {
            eraseCookie('tree');
                $("tr.parent").removeClass("info");
        }

        });

        var c = readCookie( 'tree' );

        if ( c == 'expanded' )
        {
                $(".child").toggle("slow");
                $("tr.parent").addClass("info");
        }
    </script>

@endsection
