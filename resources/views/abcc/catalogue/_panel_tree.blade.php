<div class="panel panel-primary" id="panel_tree">

   <!-- div class="panel-heading">
      <h3 class="panel-title" data-toggle="collapse" data-target="#header_data" aria-expanded="true">{{ l('Categories') }}</h3>
   </div -->

   <div class="panel-body">

<div id="catalogue_tree" style="margin-top: -20px">


    <div xclass="page-header">
        <h2>
            <span style="color: #dd4814;">{{ l('Categories') }}</span>
        </h2>        
    </div>



<div id="div_categories">
   <div class="table-responsive">

@if ($categories->count())
<table id="categories" class="table table-hover">
  <thead>
        <tr>
            <!-- th class="text-left" xstyle="width: 35px">{{l('ID', [], 'layouts')}}</th -->
            <th class="text-left">{{-- l('Category Name') --}}</th>
            <th class="text-right"> </th>
        </tr>
  </thead>
  <tbody>
        @foreach ($categories as $category)
        <tr class="parent success xactive xinfo">
            <!-- td>[{{ $category->id }}]</td -->
            <td>{{ $category->name }}</td>

      <td class="text-right">
                @if (  \App\Configuration::isFalse('ALLOW_PRODUCT_SUBCATEGORIES') )
                <a class="btn btn-sm btn-success" href="{{ URL::to('categories/' . $category->parent_id . '/subcategories/' . $category->id . '/edit') }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-eye"></i></a>
                @endif

      </td>
    </tr>

    @if ( \App\Configuration::isTrue('ALLOW_PRODUCT_SUBCATEGORIES') && $category->children->count())
    @foreach ($category->children as $child)

    <tr class="child xwarning {{ $child->id == $category_id ? 'warning' : '' }}">
      <!--td><span class="treegrid-expander"></span>[{{ $child->id }}]</td -->
      <td style="padding-left: 32px"><!-- span class="treegrid-indent"></span><span class="treegrid-expander xglyphicon xglyphicon-plus"></span -->{{ $child->name }} <span class="badge" title="{{l('Products in this Category')}}">{{ $child->customerproducts()->count() }}</span></td>

      <td class="text-right">
                <!-- a class="btn btn-sm btn-warning" href="" title="{{l('Import', [], 'layouts')}}"><i class="fa fa-refresh"></i> &nbsp; {{l('Import', [], 'layouts')}}</a -->
                @if ( $child->parent_id>0 )
                <a class="btn btn-sm btn-lightblue" href="{{ route('abcc.catalogue', ['search_status' => 0, 'category_id' => $child->id]) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-share-square-o"></i></a>
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

{{--
<p style="padding:0px; margin:10px 0px 10px 0px;">En total: &nbsp; <button class="btn btn-sm btn-default"><span class="badge">{{$sec_total}}</span> Seccion(es)</button> &nbsp; <button class="btn btn-sm btn-warning"><span class="badge">{{$fam_total}}</span> Familia(s)</button> &nbsp; <button class="btn btn-sm btn-success"><span class="badge">{{$art_total}}</span> Artículo(s)</button><!-- sup> *</sup --></p>
--}}
</div>


   </div><!-- div class="panel-body" -->
</div>



@section('styles') @parent

<style>

.treegrid-indent {
    width: 16px;
    height: 16px;
    display: inline-block;
    position: relative;
}

.treegrid-expander {
    width: 16px;
    height: 16px;
    display: inline-block;
    position: relative;
    xcursor: pointer;
}

</style>

@endsection


{{-- 
@section('scripts') @parent

<script type="text/javascript">

  $(function() {

  });
  
</script>

@endsection
 --}}

