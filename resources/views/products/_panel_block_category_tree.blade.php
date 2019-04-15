
    <div xclass="page-header" style="border-bottom: 0px solid #eeeeee;">
        <h3>
            <span style="color: #dd4814;">{{ l('Categories', 'categories') }}</span> <!-- span style="color: #cccccc;">/</span> {{ '' }} -->
        </h3>        
    </div>

    <div id="div_bom_tree">
       <div class="table-responsive">

    <table id="bom_tree" class="table table-hover tree"  xclass="table table-hover tree tree-2 table-bordered table-striped table-condensed">
        <thead>
            <tr>
                <th class="text-left">{{-- l('Position', [], 'layouts') --}}</th>
                <th class="text-left">{{-- l('Product') --}}</th>
                <th class="text-left"> </th>
            </tr>
        </thead>
        <tbody>


    @if ($bom)

 @php

 global $node_id;

 $parent_node_id = 0;

 $node_id = 1;

 @endphp           


            @include('products._panel_block_category_level', ['bom' => $bom, 'level' => 0, 'node_id' => $node_id, 'parent_node_id' => $parent_node_id])


    @else
    <tr><td colspan="7">
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{l('No records found', [], 'layouts')}}
    </div>
    </td></tr>
    @endif


        </tbody>
    </table>

       </div>
    </div>



@section('scripts')    @parent

{{-- http://maxazan.github.io/jquery-treegrid/  --}}


  {!! HTML::script('assets/plugins/jQuery-TreeGrid/js/jquery.treegrid.js') !!}
  {!! HTML::script('assets/plugins/jQuery-TreeGrid/js/jquery.treegrid.bootstrap3.js') !!}

{{--
    // https://www.google.com/search?client=ubuntu&hs=06Q&channel=fs&q=some+glyphicons+don%27t+show&spell=1&sa=X&ved=0ahUKEwjw5M34m53hAhXI1-AKHYwcCMAQBQgqKAA&biw=1366&bih=641
    // https://stackoverflow.com/questions/28237406/some-glyphicons-showing-some-not-bootstrap-3
    // https://stackoverflow.com/questions/18369036/bootstrap-3-glyphicons-are-not-working/21382680
--}}

<script type="text/javascript">
    $(document).ready(function() {
        $('.tree').treegrid({
//                    expanderExpandedClass: 'glyphicon glyphicon-minus',
//                    expanderCollapsedClass: 'glyphicon glyphicon-plus'
                }).treegrid('collapseAll');

    // Expand active leaf
    @if ( count($breadcrumb) )
        $("#node-{{ $breadcrumb[0]->id }}").treegrid('expand');
    @endif
//        @ if ( count($breadcrumb) )
//            $("treegrid-parent-" . $breadcrumb[0]->id).treegrid('expandRecursive');
//        @ endif
    });

</script>


@endsection


@section('styles')    @parent

{{-- http://maxazan.github.io/jquery-treegrid/  --}}


<!-- link href="bootstrap-3.0.0/css/bootstrap.css" rel="stylesheet" -->
  {!! HTML::style('assets/plugins/jQuery-TreeGrid/css/jquery.treegrid.css') !!}

@endsection
