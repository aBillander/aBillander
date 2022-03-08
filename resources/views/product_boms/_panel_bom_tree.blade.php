
    <div class="page-header">
        <h3>
            <span style="color: #dd4814;">{{ l('BOM tree') }}</span> <!-- span style="color: #cccccc;">/</span> {{ $bom->name }} -->
        </h3>        
    </div>

    <div id="div_bom_tree">
       <div class="table-responsive">

    <table id="bom_tree" class="table table-hover tree tree-2 table-bordered table-striped table-condensed">
        <tbody>

    @if ($bom->BOMlines->count())
            <!-- tr style="color: #3a87ad; background-color: #d9edf7;" -->
            

            @foreach ($bom->BOMlines as $line)
            
            @endforeach

                    <tr class="treegrid-1">
                        <td>Root node</td><td>Additional info</td>
                    </tr>
                    <tr class="treegrid-2 treegrid-parent-1">
                        <td>Node 1-1</td><td>Additional info</td>
                    </tr>
                    <tr class="treegrid-3 treegrid-parent-1">
                        <td>Node 1-2</td><td>Additional info</td>
                    </tr>
                    <tr class="treegrid-4 treegrid-parent-3">
                        <td>Node 1-2-1</td><td>Additional info</td>
                    </tr>

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



@section('scripts')    @parent

{{-- http://maxazan.github.io/jquery-treegrid/  --}}


  {!! HTML::script('assets/plugins/jQuery-TreeGrid/js/jquery.treegrid.js') !!}
  {!! HTML::script('assets/plugins/jQuery-TreeGrid/js/jquery.treegrid.bootstrap3.js') !!}

<script type="text/javascript">
    $(document).ready(function() {
        $('.tree').treegrid({
                    expanderExpandedClass: 'glyphicon glyphicon-minus',
                    expanderCollapsedClass: 'glyphicon glyphicon-plus'
                });
    });
</script>


@endsection


@section('styles')    @parent

{{-- http://maxazan.github.io/jquery-treegrid/  --}}


<!-- link href="bootstrap-3.0.0/css/bootstrap.css" rel="stylesheet" -->
  {!! HTML::style('assets/plugins/jQuery-TreeGrid/css/jquery.treegrid.css') !!}

@endsection
