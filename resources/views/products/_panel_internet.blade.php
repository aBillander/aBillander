
{!! Form::model($product, array('route' => array('products.update', $product->id), 'method' => 'PUT', 'class' => 'form')) !!}
<input type="hidden" value="internet" name="tab_name" id="tab_name">

<div class="panel panel-primary" id="panel_internet">
   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Internet') }}</h3>
   </div>
   <div class="panel-body">

<!-- Internet -->

        <div class="row">

                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-publish_to_web">
                     {!! Form::label('publish_to_web', l('Publish to web?'), ['class' => 'control-label']) !!}
                     <div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('publish_to_web', '1', true, ['id' => 'publish_to_web_on']) !!}
                           {!! l('Yes', [], 'layouts') !!}
                         </label>
                       </div>
                       <div class="radio-inline">
                         <label>
                           {!! Form::radio('publish_to_web', '0', false, ['id' => 'publish_to_web_off']) !!}
                           {!! l('No', [], 'layouts') !!}
                         </label>
                       </div>
                     </div>
                   </div>
        </div>

        <hr />

        <div class="row">
                  <div class="form-group col-lg-9 col-md-9 col-sm-9 {{ $errors->has('description_short') ? 'has-error' : '' }}">
                     {{ l('Short Description') }}
                     {!! Form::textarea('description_short', null, array('class' => 'form-control', 'id' => 'description_short', 'rows' => '3')) !!}
                     {!! $errors->first('description_short', '<span class="help-block">:message</span>') !!}
                  </div>
        </div>

        <div class="row">
        </div>

        <div class="row">
        </div>

<!-- Internet ENDS -->

   </div>

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>

</div>

{!! Form::close() !!}
