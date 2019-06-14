
@section('modals')    @parent

<div class="modal fade" id="modalBOMline" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-lg">
       <div class="modal-content">

           <div class="modal-header">

               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

               <h4 class="modal-title" id="modalBOMlineLabel"></h4>

           </div>

           <div class="modal-body">

               <!-- p>Some text in the modal.</p -->
                {{-- csrf_field() --}}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="line_id">
                <input type="hidden" id="line_product_id">


        <div class="row" id="product-search-autocomplete">

                  <div class="form-group col-lg-10 col-md-10 col-sm-10">
                     {{ l('Product name') }}
                     {!! Form::text('line_autoproduct_name', null, array('class' => 'form-control', 'id' => 'line_autoproduct_name')) !!}
                  </div>

                  <!-- div class="form-group col-lg-2 col-md-2 col-sm-2 ">
                     {{ l('Product ID') }}
                     {!! Form::text('pid', null, array('class' => 'form-control', 'id' => 'pid')) !!}
                  </div -->

        </div>

        <div class="row">
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('line_sort_order') ? 'has-error' : '' }}" style="display: none;">
                     {{ l('Position') }}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                      data-content="{{ l('Use multiples of 10. Use other values to interpolate.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                     {!! Form::text('BOMline[line_sort_order]', null, array('class' => 'form-control', 'id' => 'line_sort_order')) !!}
                     {!! $errors->first('line_sort_order', '<span class="help-block">:message</span>') !!}
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2">
                  </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('quantity') ? 'has-error' : '' }}">
                     {{ l('Quantity') }}
                     {!! Form::text('BOMline[quantity]', null, array('class' => 'form-control', 'id' => 'line_quantity')) !!}
                     {!! $errors->first('quantity', '<span class="help-block">:message</span>') !!}
                  </div>

                 <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('measure_unit_id') ? 'has-error' : '' }}">
                    {{ l('Measure Unit') }}
                    {!! Form::select('BOMline[measure_unit_id]', [], null, array('class' => 'form-control', 'id' => 'line_measure_unit_id', 'xonFocus' => 'this.blur()')) !!}
                    {!! $errors->first('measure_unit_id', '<span class="help-block">:message</span>') !!}
                 </div>

                  <div class="form-group col-lg-2 col-md-2 col-sm-2 {{ $errors->has('scrap') ? 'has-error' : '' }}">
                     {{ l('Scrap (%)') }}
                           <a href="javascript:void(0);" data-toggle="popover" data-placement="bottom" 
                                      data-container="body" 
                                      data-content="{{ l('Percent. When the components are ready to be consumed in a released production order, this percentage will be added to the expected quantity in the Consumption Quantity field in a production journal.') }}">
                                  <i class="fa fa-question-circle abi-help"></i>
                           </a>
                     {!! Form::text('BOMline[scrap]', null, array('class' => 'form-control', 'id' => 'line_scrap')) !!}
                     {!! $errors->first('scrap', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('notes') ? 'has-error' : '' }}">
                     {{ l('Notes', [], 'layouts') }}
                     {!! Form::textarea('BOMline[notes]', null, array('class' => 'form-control', 'id' => 'line_notes', 'rows' => '3')) !!}
                     {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>



           </div>

           <div class="modal-footer">

               <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
               <button type="submit" class="btn btn-success" name="btn-update" id="modalBOMlineSubmit" xonclick="this.disabled=true;">
                <i class="fa fa-thumbs-up"></i>
                &nbsp; {{l('Update', [], 'layouts')}}</button>

           </div>

       </div>
   </div>
</div>

@endsection


{{-- *************************************** --}}


@section('scripts')    @parent 

<script>

    $(document).ready(function () {
          // $(document).on("click", ".open-AddBookDialog", function() {
            $('.open-AddBookDialog').click(function (evnt) {

               var href = $(this).attr('href');
               var myBookId = $(this).attr('data-id');
               var myBookStatus = $(this).attr('data-status');
               var myBookStatusname = $(this).attr('data-statusname');

               $('#change_woo_order_status').attr('action', href);
               $(".modal-body #bookId").val(myBookId);
               $(".modal-body #bookStatus").val(myBookStatusname);
               $(".modal-body #order_status").val(myBookStatus);

               // https://blog.revillweb.com/jquery-disable-button-disabling-and-enabling-buttons-with-jquery-5e3ffe669ece
               // $('#btn-update').prop('disabled', false);

               $('#myModalOrder').modal({show: true});

               return false;

           });
    });

</script>

@endsection
