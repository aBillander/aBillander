

<div class="row">
    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('from_quantity_promo', l('From Quantity')) !!}
        {!! Form::text('from_quantity_promo', old('from_quantity_promo', 1), array('class' => 'form-control')) !!}
    </div>
    <div class="form-group col-lg-3 col-md-3 col-sm-3">
        {!! Form::label('extra_quantity', l('Extra Items')) !!}
                 <!-- a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                                    data-content="{{ l('If you fill this field, the value in "Price" will not take efect.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a -->
        {!! Form::text('extra_quantity', old('extra_quantity', '0'), array('class' => 'form-control')) !!}
    </div>
</div>


               <div class="panel-footer text-right">
                  <a class="btn btn-link" href="{{ URL::to('pricerules') }}">{{l('Cancel', [], 'layouts')}}</a>
                  <button class="btn btn-info" type="submit" onclick="this.disabled=true;$('#rule_type').val('promo');this.form.submit();">
                     <i class="fa fa-floppy-o"></i>
                     &nbsp; {{l('Save', [], 'layouts')}}
                  </button>
               </div>
