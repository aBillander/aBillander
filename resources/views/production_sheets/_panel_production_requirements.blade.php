
<div class="panel-body" id="div_production_requirements">

<div id="msg-success" class="alert alert-success alert-block" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span id="msg-success-counter" class="badge"></span>
  <strong>{!!  l('This record has been successfully created &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
</div>

<div id="msg-error" class="alert alert-danger alert-block" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span id="msg-error-counter" class="badge"></span>
  <strong>{!!  l('Unable to create this record &#58&#58 (:id) ', ['id' => l('Product not found', 'customerdocuments')], 'layouts') !!}</strong>
</div>

<div id="msg-success-delete" class="alert alert-success alert-block" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span id="msg-success-delete-counter" class="badge"></span>
  <strong>{!!  l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => ''], 'layouts') !!}</strong>
</div>

 <div id="div_production_requirements_content">


                    @include('production_sheets._panel_production_requirements_content')


 </div><!-- div id="div_production_requirements_content" -->
</div><!-- div class="panel-body" -->

<div class="panel-footer text-right">

  <a class="btn btn-sm btn-info  lines_quick_form " title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add Production Requirements')}}</a>
</div>
