
<div id="panel_internet">

{{-- !! Form::model($category, array('route' => array('categories.update', $category->id), 'method' => 'PUT', 'class' => 'form')) !! --}}

{!! Form::model($category, array('method' => 'PATCH', 'route' => array('categories.subcategories.update', $parentId, $category->id))) !!}
<input type="hidden" value="internet" name="tab_name" id="tab_name">

<div class="panel panel-primary">
   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Web Shop') }}</h3>
   </div>
   <div class="panel-body">

<!-- Internet -->

        <div class="row">

                  <div class="col-lg-3 col-md-3 col-sm-3">
                      <div class="form-group {{ $errors->has('webshop_id') ? 'has-error' : '' }}">
                          {!! Form::label('webshop_id', l('Webshop ID'), ['class' => 'control-label']) !!}
                          <div class="col-lg-6 col-md-6 col-sm-6">
                            {!! Form::text('webshop_id', null, array('class' => 'form-control', 'id' => 'webshop_id')) !!}
                            {!! $errors->first('webshop_id', '<span class="help-block">:message</span>') !!}
                          </div>
                      </div>
                  </div>

@if( $category->webshop_id > 0 )

                   <div class="form-group col-lg-2 col-md-2 col-sm-2">

                        <a class="btn xbtn-sm alert-info view-webshop-data" href="javascript::void(0);" title="{{l('View', [], 'layouts')}}"><i class="fa fa-eye"></i> {{l('View Data', [], 'layouts')}}</a>

                   </div>

                   <div class="form-group col-lg-2 col-md-2 col-sm-2">

                        <a class="btn xbtn-sm btn-blue" href="{{ URL::route('wcategories.fetch', $category->webshop_id ) }}" title="{{l('Fetch', [], 'layouts')}}" target="_blank"><i class="fa fa-eyedropper"></i> {{l('Fetch Data', [], 'layouts')}}</a>

                   </div>

@else

                   <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-publish_to_web_1">

                        <a class="btn xbtn-sm btn-lightblue" href="javascript:void(0);"
                                onclick="event.preventDefault();
                                         document.getElementById('publish-category-form').submit();" title="{{l('Publish', [], 'layouts')}}"><i class="fa fa-cloud-upload"></i> {{l('Publish', [], 'layouts')}}</a>
{{-- See end of file
                        <form id="publish-category-form" action="{{ route('wcategories.store') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                            {!! Form::hidden('abi_category_id', $category->id, array('id' => 'abi_category_id')) !!}
                        </form>
--}}

                   </div>
@endif

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



{{--
<!-- webShop data -->

@if( 0 && $category->publish_to_web AND !$category->webshop_id )

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
--}}

</div>



<div id="category-webshop-data">

    <div id="category-webshop-data-content"></div>

</div>




{{-- Extra Form --}}

                        <form id="publish-category-form" action="{{ route('wcategories.store') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                            {!! Form::hidden('abi_category_id', $category->id, array('id' => 'abi_category_id')) !!}
                        </form>


@section('scripts') @parent

<script type="text/javascript">
   $(document).ready(function() {

          $(document).on('click', '.view-webshop-data', function(evnt) {
           
               var panel = $("#category-webshop-data");
               var url = "{{ route('wcategories.show', [$category->webshop_id, 'embed']) }}";

               panel.html(" &nbsp; &nbsp; &nbsp; &nbsp; {{ l('Loading...', 'layouts') }}").addClass('loading');

               $.get(url, {}, function(result){
                     panel.html(result);
                     panel.removeClass('loading');

                     $("[data-toggle=popover]").popover();

               }, 'html').done( function() { 

                    // var selector = "#line_autoproduct_name";
                    // var next = $('#next_line_sort_order').val();

                    // $('#modal_document_line').modal({show: true});
                    // $("#line_autoproduct_name").focus();

                });

              return false;
          });

   });
</script>

@endsection


@section('styles')    @parent

<style>

  .loading{
    background: white url("{{ asset('assets/theme/images/ui-anim_basic_16x16.gif') }}") left center no-repeat;
  }

</style>

@endsection

