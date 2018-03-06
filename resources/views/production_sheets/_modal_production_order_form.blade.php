
@section('modals')    @parent

<div class="modal fade" id="modalProductionOrder" tabindex="-1" role="dialog">
   <div class="modal-dialog">
       <div class="modal-content">

           <div class="modal-header">

               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

               <h4 class="modal-title" id="modalProductionOrderLabel"></h4>

           </div>

           <div class="modal-body">

               <!-- p>Some text in the modal.</p -->
                {{-- csrf_field() --}}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="production_sheet_id" id="production_sheet_id" value="{{ $sheet->id }}">
                <input type="hidden" name="due_date"            id="due_date"            value="{{ $sheet->due_date }}">
                <input type="hidden" name="product_id"          id="product_id"          value="">


        <div class="row" id="product-search-autocomplete">

                  <div class="form-group col-lg-10 col-md-10 col-sm-10">
                     {{ l('Product name') }}
                     {!! Form::text('order_autoproduct_name', null, array('class' => 'form-control', 'id' => 'order_autoproduct_name')) !!}
                  </div>

                  <!-- div class="form-group col-lg-2 col-md-2 col-sm-2 ">
                     {{ l('Product ID') }}
                     {!! Form::text('pid', null, array('class' => 'form-control', 'id' => 'pid')) !!}
                  </div -->

        </div>

        <div class="row">

                  <div class="form-group col-lg-3 col-md-3 col-sm-3 {{ $errors->has('planned_quantity') ? 'has-error' : '' }}">
                     {{ l('Quantity') }}
                     {!! Form::text('planned_quantity', null, array('class' => 'form-control', 'id' => 'planned_quantity')) !!}
                     {!! $errors->first('planned_quantity', '<span class="help-block">:message</span>') !!}
                  </div>

                 <div class="form-group col-lg-5 col-md-5 col-sm-5 {{ $errors->has('work_center_id') ? 'has-error' : '' }}">
                    {{ l('Work Center') }}
                    {!! Form::select('work_center_id', ['0' => l('-- Please, select --', [], 'layouts')] + $work_centerList, null, array('class' => 'form-control', 'id' => 'work_center_id')) !!}
                    {!! $errors->first('work_center_id', '<span class="help-block">:message</span>') !!}
                 </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('notes') ? 'has-error' : '' }}">
                     {{ l('Notes', [], 'layouts') }}
                     {!! Form::textarea('notes', null, array('class' => 'form-control', 'id' => 'notes', 'rows' => '3')) !!}
                     {!! $errors->first('notes', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>



           </div>

           <div class="modal-footer">

               <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
               <button type="submit" class="btn btn-success" name="btn-update" id="modalProductionOrderSubmit" xonclick="this.disabled=true;">
                <i class="fa fa-thumbs-up"></i>
                &nbsp; {{l('Update', [], 'layouts')}}</button>

           </div>

       </div>
   </div>
</div>

@endsection


{{-- *************************************** --}}
