

         <div class="modal-header xalert-info">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ l('Add Lots to Line') }} :: <span  id="modal_product_document_line_Label"></span></h4>
         </div>

      <form id="document_line_lots">

         <div class="modal-body">


            {{-- csrf_field() --}}
            {!! Form::token() !!}
            <!-- input type="hidden" name="_token" value="{ { csrf_token() } }" -->
            <!-- input type="hidden" id="" -->
            {{ Form::hidden('line_id',         null, array('id' => 'line_id'        )) }}
            {{ Form::hidden('line_sort_order', null, array('id' => 'line_sort_order')) }}

            {{ Form::hidden('line_product_id',     null, array('id' => 'line_product_id'    )) }}
            {{ Form::hidden('line_combination_id', null, array('id' => 'line_combination_id')) }}
            {{ Form::hidden('line_reference',      null, array('id' => 'line_reference'     )) }}

            {{ Form::hidden('line_type',           null, array('id' => 'line_type'          )) }}

                <div class="alert alert-danger" id="error-msg-box" style="display:none">
    
                </div>
               


        <div class="row  hide ">

                  <div class="form-group col-lg-12 col-md-12 col-sm-12 {{ $errors->has('line_lot_references') ? 'has-error' : '' }}">
                     {{ l('Lot Number(s)', 'customerdocuments') }}
                              <a href="javascript:void(0);" data-toggle="popover" data-placement="right" data-container="body" data-html="true" 
                                        data-content="{{ l('Comma separated list, as in: lot1:expiry1:quantity1,lot2:expiry2:quantity2.<br />Lot Expiration Date and Lot Quantity are optional, but a colon (:) must be still present, as in: lot1: :,lot2: : .', 'customerdocuments') }}">
                                    <i class="fa fa-question-circle abi-help"></i>
                              </a>
                     {!! Form::text('line_lot_references', null, array('class' => 'form-control', 'id' => 'line_lot_references')) !!}
                     {!! $errors->first('line_lot_references', '<span class="help-block">:message</span>') !!}
                  </div>

        </div>

        <div class="row">

                  <div class="form-group col-lg-12 col-md-12 col-sm-12">
                        <h4 style="color: #dd4814;">{{ l('Available Lots', 'customerdocuments') }}</h4>

                        <div id="product_available_lots">
                        </div>

                  </div>

        </div>

         </div><!-- div class="modal-body" ENDS -->

           <div class="modal-footer">

               <button type="button" class="btn xbtn-sm btn-warning" data-dismiss="modal">{{l('Cancel', [], 'layouts')}}</button>
               <button type="submit" class="btn btn-info" name="modal_edit_document_line_product_lotsSubmit" id="modal_edit_document_line_product_lotsSubmit" title="{{l('Save', 'layouts')}}">
                <i class="fa fa-hdd-o"></i>
                &nbsp; {{l('Update', [], 'layouts')}}</button>

           </div>

      </form>
