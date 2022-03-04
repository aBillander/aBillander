@section('modals')

@parent

<div class="modal fade" id="bom_createModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

{!! Form::model($product, array('route' => array('products.update', $product->id), 'method' => 'PUT', 'class' => 'form')) !!}
<input type="hidden" value="bom_create" name="tab_name">
{{-- csrf_field() --}}
<input type="hidden" name="_token" value="{{ csrf_token() }}">

<input type="hidden" name="product_id" value="{{ $product->id }}">
<input type="hidden" name="measure_unit_id" value="{{ $product->measure_unit_id }}">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="bom_createLabel">{{ l('Create BOM') }}</h4>
            </div>
            <div class="modal-body">

        <div class="row">
{{--
                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('quantity') ? 'has-error' : '' }}">
                     {{ l('Quantity') }}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="bottom" 
                                      data-container="body" 
                                      data-content="{{ l('Ejemplo: Si la Receta (BOM) es para fabricar una unidad, y el Producto contiene cuatro unidades, deberá poner 4.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                     {!! Form::text('quantity', null, array('class' => 'form-control', 'id' => 'bom_create_quantity')) !!}
                     {!! $errors->first('quantity', '<span class="help-block">:message</span>') !!}
                  </div>
--}}
                  <input type="hidden" value="1" name="quantity">

        </div>

        <div class="row">

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('alias') ? 'has-error' : '' }}">
                      {{ l('Alias') }}
                      {!! Form::text('alias', null, array('class' => 'form-control', 'id' => 'bom_create_alias')) !!}
                      {!! $errors->first('alias', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-9 col-md-9 col-sm-9 {{ $errors->has('name') ? 'has-error' : '' }}">
                      {{ l('BOM Name') }}
                      {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'bom_create_name')) !!}
                      {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                  </div>

        </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
                {!! Form::submit(l('Confirm', [], 'layouts'), array('class' => 'btn btn-info', 'id' => 'bom_createModalSubmit')) !!}
            </div>

{!! Form::close() !!}
        </div>
    </div>
</div>

@endsection
@section('scripts') @parent 

    <!-- script src="https://code.jquery.com/jquery-1.12.4.js"></script -->
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script -->
    {{-- See: Laravel 5.4 ajax todo project: Autocomplete search #7 --}}

<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '.create-bom-item', function(evnt) { 
 //       $('.delete-item').click(function (evnt) {
            $("#bom_create_alias").val("{{ $product->reference . '-BOM'}}");
            $("#bom_create_name").val("{{ '[BOM]-' . $product->name}}");
            $("#bom_create_quantity").val(1);

            $('#bom_createModal').modal({show: true});

            // Gorrino style:
            setTimeout(function() { $("#bom_create_alias").focus(); }, 700);
            return false;
        });
    });


        $("#xbom_createModalSubmit").click(function() {

 //         alert('etgwer');

            var bom_id = $('#product_bom_id').val();
            var bom_quantity = $('#bom_quantity').val();
            var url = "{ { route('product.attachbom', [$product->id]) } }";
            var token = "{{ csrf_token() }}";

  //        alert(url);

            var payload = { 
                              product_id : "{{ $product->id }}",
                              product_bom_id : bom_id,
                              quantity : bom_quantity
                          };

  //          alert(payload);

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function(){
                    loadBOMlines();
                    $(function () {  $('[data-toggle="tooltip"]').tooltip()});
//                    $("[data-toggle=popover]").popover();

                    $('#modalBOMline').modal('toggle');
                    $("#msg-success").fadeIn();
                }
            });

/*            $(function () {  $('[data-toggle="tooltip"]').tooltip()});
            $("[data-toggle=popover]").popover();
            $(function () {
  $('[data-toggle="popover"]').popover()
})
*/
        });

        $("#line_autobom_name").autocomplete({
            source : "{{ route('product.searchbom') }}",
            minLength : 1,
            appendTo : "#bom_createModal",

            select : function(key, value) {
                var str = '[' + value.item.alias+'] ' + value.item.name;

                $("#line_autobom_name").val(str);
                $('#product_bom_id').val(value.item.id);

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.alias+'] ' + item.name + "</div>" )
                .appendTo( ul );
            };

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
