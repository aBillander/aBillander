
               <div class="panel-body">

               <!-- input name="parent_id" value="{ { $parentId } }" type="hidden" -->
                
        <div class="row">
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('name') ? 'has-error' : '' }}">
                     {{ l('Category Name') }}
                     {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
                     {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                  </div>

            <div class="form-group col-lg-2 col-md-2 col-sm-2 {!! $errors->has('reference_external') ? 'has-error' : '' !!}">
              {{ l('External Reference') }}
              {!! Form::text('reference_external', null, array('class' => 'form-control', 'id' => 'reference_external')) !!}
              {!! $errors->first('reference_external', '<span class="help-block">:message</span>') !!}
            </div>

                   <div class="form-group col-lg-4 col-md-4 col-sm-4" id="div-active">
                     {!! Form::label('active', l('Active?', [], 'layouts'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('active', '1', true, ['id' => 'active_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('active', '0', false, ['id' => 'active_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>
        </div>


               </div><!-- div class="panel-body" -->

               <div class="panel-footer text-right">
                  <a class="btn btn-link" data-dismiss="modal" href="{!! URL::to('categories/'.$parentId.'/subcategories') !!}">{{l('Cancel', [], 'layouts')}}</a>
                  <button class="btn btn-primary" type="submit" onclick="this.disabled=true;this.form.submit();">
                     <i class="fa fa-floppy-o"></i>
                     &nbsp; {{l('Save', [], 'layouts')}}
                  </button>
               </div>
