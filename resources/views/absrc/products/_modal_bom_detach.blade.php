@section('modals')

@parent

<div class="modal fade" id="detach_bomModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

{!! Form::model($product, array('route' => array('absrc.products.update', $product->id), 'method' => 'PUT', 'class' => 'form')) !!}
<input type="hidden" value="detach_bom" name="tab_name">
{{-- csrf_field() --}}
<!-- input type="hidden" name="_token" value="{{ csrf_token() }}" -->

<input type="hidden" name="product_bom_id" id="product_bom_id">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="detach_bomLabel">{{ l('Detach BOM') }}</h4>
            </div>
            <div class="modal-body">
                <div class="row">

                {{-- !! Form::hidden('product_id', $product->id) !! --}}

                     <div class="form-group col-lg-1 col-md-1 col-sm-1">
                     </div>

                     <div class="form-group col-lg-10 col-md-10 col-sm-10">
                        Está a punto de quitar la Lista de Materiales de este Producto. La Lista de Materiales NO se borrará ¿Está seguro?
                     </div>

                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
                {!! Form::submit(l('Confirm', [], 'layouts'), array('class' => 'btn btn-info', 'id' => 'detach_bomModalSubmit')) !!}
            </div>

{!! Form::close() !!}
        </div>
    </div>
</div>

@stop
@section('scripts') @parent 

<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '.detach-bom-item', function(evnt) { 
 //       $('.delete-item').click(function (evnt) {
 //           $("#line_autobom_name").val('');
 //           $("#product_bom_id").val('');
 //           $("#bom_quantity").val(1);

            $('#detach_bomModal').modal({show: true});
            return false;
        });
    });

</script>

@endsection

@section('styles')    @parent

  {!! HTML::style('assets/plugins/AutoComplete/styles.css') !!}

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"></script -->

<style>

  .ui-autocomplete-loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") right center no-repeat;
  }
  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }


/* See: http://fellowtuts.com/twitter-bootstrap/bootstrap-popover-and-tooltip-not-working-with-ajax-content/ 
.modal .popover, .modal .tooltip {
    z-index:100000000;
}
 */
</style>

@endsection
