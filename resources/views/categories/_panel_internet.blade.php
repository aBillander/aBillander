
<div id="panel_internet">

{!! Form::model($category, array('route' => array('categories.update', $category->id), 'method' => 'PUT', 'class' => 'form')) !!}
<input type="hidden" value="internet" name="tab_name" id="tab_name">

<div class="panel panel-primary">
   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Internet') }}</h3>
   </div>
   <div class="panel-body">

<!-- Internet -->
@if(!$category->webshop_id)
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

<!-- Internet ENDS -->

   </div>

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>

@else

   </div>

    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{l('This record has been already published with id=:id', ['id' => $category->webshop_id], 'layouts')}}
    </div>
    <br /><br />

@endif

</div>

{!! Form::close() !!}

<!-- webShop data -->

@if($category->publish_to_web AND !$category->webshop_id)

{!! Form::model($category, array('route' => array('categories.publish', $category->id), 'method' => 'POST', 'class' => 'form')) !!}
<input type="hidden" value="internet" name="tab_name" id="tab_name">

<div class="panel panel-info">
   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Web Shop Data') }}</h3>
   </div>
   <div class="panel-body">

<!-- Internet -->

        <div class="row">
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('name') ? 'has-error' : '' }}">
                     {{ l('Name') }}
                     {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
                     {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('description') ? 'has-error' : '' }}">
                     {{ l('Description') }}
                     {!! Form::textarea('description', null, array('class' => 'form-control', 'id' => 'description', 'rows' => '3')) !!}
                     {!! $errors->first('description', '<span class="help-block">:message</span>') !!}
                  </div>
                 <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('link_rewrite') ? 'has-error' : '' }}">
                    {{ l('Friendly URL') }}
                     {!! Form::text('link_rewrite', null, array('class' => 'form-control', 'id' => 'link_rewrite')) !!}
                     {!! $errors->first('link_rewrite', '<span class="help-block">:message</span>') !!}
                 </div>
        </div>

        <div class="row">
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('meta_title') ? 'has-error' : '' }}">
                     {{ l('META Title') }}
                     {!! Form::text('meta_title', null, array('class' => 'form-control', 'id' => 'meta_title')) !!}
                     {!! $errors->first('meta_title', '<span class="help-block">:message</span>') !!}
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('meta_description') ? 'has-error' : '' }}">
                     {{ l('META Description') }}
                     {!! Form::text('meta_description', null, array('class' => 'form-control', 'id' => 'meta_description')) !!}
                     {!! $errors->first('meta_description', '<span class="help-block">:message</span>') !!}
                  </div>
                 <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('meta_keywords') ? 'has-error' : '' }}">
                    {{ l('META Keywords') }}
                     {!! Form::text('meta_keywords', null, array('class' => 'form-control', 'id' => 'meta_keywords')) !!}
                     {!! $errors->first('meta_keywords', '<span class="help-block">:message</span>') !!}
                 </div>
        </div>

        <div class="row">

                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-active">
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

<!-- webShop data ENDS -->

   </div>

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-success" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-cloud"></i>
         &nbsp; {{l('Publish', [], 'layouts')}}
      </button>
   </div>

</div>

{!! Form::close() !!}

@endif

</div>
