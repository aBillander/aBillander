
<div id="panel_bom"> 

{!! Form::model($bom, array('route' => array('productboms.update', $bom->id), 'method' => 'PUT', 'class' => 'form')) !!}
<!-- input type="hidden" value="sales" name="tab_name" id="tab_name" -->

<div class="panel panel-primary">
   <div class="panel-heading">
      <h3 class="panel-title"><!-- {{ l('Bill of Materials') }} --></h3>
   </div>
   <div class="panel-body">

<!-- BOM Description -->

        <div class="row">
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('alias') ? 'has-error' : '' }}">
                      {{ l('Alias') }}
                      {!! Form::text('alias', null, array('class' => 'form-control', 'id' => 'alias')) !!}
                      {!! $errors->first('alias', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('name') ? 'has-error' : '' }}">
                      {{ l('Name') }}
                      {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
                      {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('quantity') ? 'has-error' : '' }}">
                     {{ l('Quantity') }}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('La cantidad de los Ingredientes son para esta cantidad de Elaborado, expresada en la unidad de medida de la Lista de Materiales.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                     {!! Form::text('quantity', null, array('class' => 'form-control', 'id' => 'quantity')) !!}
                     {!! $errors->first('quantity', '<span class="help-block">:message</span>') !!}
                  </div>

                 <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('measure_unit_id') ? 'has-error' : '' }}">
                    {{ l('Measure Unit') }}
                    {!! Form::select('measure_unit_id', array('0' => l('-- Please, select --', [], 'layouts')) + $measure_unitList, null, array('class' => 'form-control')) !!}
                    {!! $errors->first('measure_unit_id', '<span class="help-block">:message</span>') !!}
                 </div>

                  <div class="form-group col-lg-7 col-md-7 col-sm-7 {{ $errors->has('notes') ? 'has-error' : '' }}">
                     {{ l('Notes', [], 'layouts') }}
                     {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '2')) !!}
                     {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
                  </div>

                  <!-- div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('cost_price') ? 'has-error' : '' }}">
                     {{ l('Measure Unit') }}
                     {!! Form::text('cost_price', null, array('class' => 'form-control', 'id' => 'cost_price', 'autocomplete' => 'off', 'onfocus' => 'this.blur()')) !!}
                     {!! $errors->first('cost_price', '<span class="help-block">:message</span>') !!}
                  </div -->
        </div>

<!-- BOM Description ENDS -->

   </div>

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>

</div>

{!! Form::close() !!}

<!-- BOM Lines -->

<div id="msg-success" class="alert alert-success alert-block" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span id="msg-success-counter" class="badge"></span>
  <strong>Registro Actualizado Correctamente</strong>
</div>

<div id="msg-error" class="alert alert-danger alert-block" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span id="msg-error-counter" class="badge"></span>
  <strong>No se ha podido añadir el Producto a la Lista</strong>
</div>

<div id="panel_bom_lines" class="loading"> &nbsp; &nbsp; &nbsp; &nbsp; {{ l('Loading...') }}
  
<!--  @ include('product_boms._panel_bom_lines') -->

</div>


@include('product_boms._modal_bom_line_form')

@include('product_boms._modal_bom_line_delete')

@include('product_boms._modal_bom_products')

<!-- BOM Lines ENDS -->

</div>


@section('scripts')    @parent

    <!-- script src="https://code.jquery.com/jquery-1.12.4.js"></script -->
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script -->
    {{-- See: Laravel 5.4 ajax todo project: Autocomplete search #7 --}}

    <script type="text/javascript">

        $(document).ready(function() {

//          loadBOMlines();

  //            alert('id');

          $(document).on('click', '.create-bom-line', function(evnt) {
              var url = "{{ route('productbom.storeline', [$bom->id]) }}";
              var next = $('#next_line_sort_order').val();
              var label = '';

                    label = "{{ l('Add new Item to BOM') }}";
                    $('#modalBOMlineLabel').text(label);

                    $('#line_id').val('');
                    $('#line_sort_order').val(next);
                    $('#line_quantity').val(1);
                    $('#line_measure_unit_id').val('');
                    $('#line_scrap').val(0.0);
                    $('#line_notes').val('');

                $('#product-search-autocomplete').show();
                $("#line_autoproduct_name").val('');
                $('#line_product_id').val('');

              $('#modalBOMline').modal({show: true});

                // See: https://stackoverflow.com/questions/12190119/how-to-set-the-focus-for-a-particular-field-in-a-bootstrap-modal-once-it-appear
                // $("#line_autoproduct_name").focus();
                setTimeout(function() { $("#line_autoproduct_name").focus(); }, 500); 
              return false;
          });

          $(document).on('click', '.edit-bom-line', function(evnt) {
              var id = $(this).attr('data-id');
              var url = "{{ route('productbom.getline', [$bom->id, '']) }}/"+id;
              var label = '';

               $.get(url, function(result){
                    label = '('+result.product_id+') ['+result.product.reference+'] '+result.product.name;
                    $('#modalBOMlineLabel').text(label);

                    $('#line_id').val(result.id);
                    $('#line_sort_order').val(result.line_sort_order);
                    $('#line_product_id').val(result.product_id);
                    $('#line_quantity').val(result.quantity);

                populateMeasureUnitsByProductID( result.product_id, result.measure_unit_id );
                    
                    $('#line_measure_unit_id').val(result.measure_unit_id);

                    $('#line_scrap').val(result.scrap);
                    $('#line_notes').val(result.notes);

//                    console.log(result);
               });

              $('#product-search-autocomplete').hide();
              $("#line_autoproduct_name").val('');
              $('#modalBOMline').modal({show: true});
              setTimeout(function() { $("#line_quantity").select(); }, 500);
              return false;
          });

          loadBOMlines();



        });     // ENDS      $(document).ready(function() {


        function loadBOMlines() {
           
           var panel = $("#panel_bom_lines");
           var url = "{{ route('productbom.getlines', $bom->id) }}";

           panel.addClass('loading');

           $.get(url, {}, function(result){
                 panel.html(result);
                 panel.removeClass('loading');

                 $("[data-toggle=popover]").popover();
                 sortableBOMlines();
           }, 'html');

        }

        function sortableBOMlines() {

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
                url: "{{ route('productbom.sortlines') }}",
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

        $("#modalBOMlineSubmit").click(function() {

 //         alert('etgwer');

            var id = $('#line_id').val();
            var url = "{{ route('productbom.updateline', ['']) }}/"+id;
            var token = "{{ csrf_token() }}";

            if ( id == '' )
                url = "{{ route('productbom.storeline', [$bom->id]) }}";
            else
                url = "{{ route('productbom.updateline', ['']) }}/"+id;

  //        alert(url);

            var payload = { 
                              line_sort_order : $('#line_sort_order').val(),
                              product_id : $('#line_product_id').val(),
                              quantity : $('#line_quantity').val(),
                              measure_unit_id : $('#line_measure_unit_id').val(),
                              scrap : $('#line_scrap').val(),
                              notes : $('#line_notes').val()
                          };

  //          alert(payload);

            $.ajax({
                url : url,
                headers : {'X-CSRF-TOKEN' : token},
                type : 'POST',
                dataType : 'json',
                data : payload,

                success: function(response){
                    loadBOMlines();
                    $(function () {  $('[data-toggle="tooltip"]').tooltip()});
//                    $("[data-toggle=popover]").popover();

console.log(response);

                    $('#modalBOMline').modal('toggle');
                    if (response.msg=='OK')
                      showAlertDivWithDelay("#msg-success");
                    else
                      showAlertDivWithDelay("#msg-error");
                }
            });

/*            $(function () {  $('[data-toggle="tooltip"]').tooltip()});
            $("[data-toggle=popover]").popover();
            $(function () {
  $('[data-toggle="popover"]').popover()
})
*/
        });

        $("#line_autoproduct_name").autocomplete({
            source : "{{ route('productbom.searchproduct') }}",
            minLength : 1,
            appendTo : "#modalBOMline",

            select : function(key, value) {
                var str = '[' + value.item.reference+'] ' + value.item.name;

                $("#line_autoproduct_name").val(str);
                $('#line_product_id').val(value.item.id);
//                $('#pid').val(value.item.id);

                populateMeasureUnitsByProductID( value.item.id, value.item.measure_unit_id );

                // $('#line_measure_unit_id').val(value.item.measure_unit_id);

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function( ul, item ) {
              return $( "<li></li>" )
                .append( '<div>[' + item.reference+'] ' + item.name + "</div>" )
                .appendTo( ul );
            };



    function populateMeasureUnitsByProductID( productID, unitID = 0 )
    {
        $.get('{{ url('/') }}/product/' + productID + '/getmeasureunits', function (units) {
            

            $('select[name="BOMline[measure_unit_id]"]').empty();
//            $('select[name="measure_unit_id"]').append('<option value=0>{{ l('-- Please, select --', [], 'layouts') }}</option>');
            $.each(units, function (key, value) {
                $('select[name="BOMline[measure_unit_id]"]').append('<option value=' + value.id + '>' + value.name + '</option>');
            });

        }).done( function() { 

        $('select[name="BOMline[measure_unit_id]').val(unitID);

        $('#measure_unit_id').val(unitID);

        // See: https://stackoverflow.com/questions/17863432/how-select-default-option-of-dynamically-added-dropdown-list

      });
    }

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
