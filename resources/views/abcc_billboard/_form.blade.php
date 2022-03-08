
@php    

    $activeList = [
        'both'  => l('Image and Text'),
        'image' => l('Image only'),
        'text'  => l('Text only'),
        'none'  => l('None'),
    ];

@endphp


{!! Form::model($billboard, array('route' => array('abccbillboard.update'), 'method' => 'POST', 'class' => 'form', 'files' => true)) !!}

<div class="panel panel-primary" id="panel_main_data">
   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Main Data') }}</h3>
   </div>
   <div class="panel-body">


<!-- Main Data -->

<div class="row">
    <div class="form-group col-lg-4 col-md-4 col-sm-4 {{ $errors->has('data_file') ? 'has-error' : '' }}"
      xstyle="background-image: url('{{ URL::to( abi_tenant_local_path( 'images_bb/' ) . \App\Configuration::get('ABCC_BB_IMAGE') ) }}'); background-size: cover; backdrop-filter: blur(8px);">
       {!! Form::label('data_file', l('Upload Image', [], 'layouts')) !!}
                 <a href="javascript:void(0);" data-toggle="popover" data-placement="top" 
                        data-content="{{ l('File should be an Image.') }}">
                        <i class="fa fa-question-circle abi-help"></i>
                 </a>
       {{-- Form::file('image', null, array('required', 'class'=>'form-control')) --}}

            <div class="input-group">
                <label class="input-group-btn">
                    <span class="btn btn-blue">
                        {{ l('Browse', [], 'layouts') }}&hellip; <input type="file" name="data_file" id="data_file" style="display: none;" multiple>
                    </span>
                </label>
                <input type="text" class="form-control" readonly>
            </div>

                    {!! $errors->first('data_file', '<span class="help-block">:message</span>') !!}

            <div class="form-group" style="margin-top: 12px">

@if( \App\Configuration::get('ABCC_BB_IMAGE') )
              <img width="50%" src="{{ URL::to( abi_tenant_local_path( 'images_bb/' ) . \App\Configuration::get('ABCC_BB_IMAGE') ) }}" class="img-responsive pull-right xcenter-block" style="border: 1px solid #dddddd;">
@endif
              {!! Form::label('', l('Current image').': &nbsp; ', ['class' => 'control-label pull-right']) !!}
            </div>

    </div>

          <div class="form-group col-lg-6 col-md-6 col-sm-6 {{ $errors->has('caption') ? 'has-error' : '' }}">
              {!! Form::label('caption', l('Caption'), ['class' => 'control-label']) !!}
             {!! Form::textarea('caption', null, array('class' => 'form-control', 'id' => 'caption', 'rows' => '3')) !!}
             {!! $errors->first('caption', '<span class="help-block">:message</span>') !!}
          </div>

             <div class="form-group col-lg-2 col-md-2 col-sm-2" id="div-active">
                {!! Form::label('active', l('Active?', [], 'layouts'), ['class' => 'control-label']) !!}
                {!! Form::select('active', $activeList, null, array('class' => 'form-control')) !!}
                {!! $errors->first('active', '<span class="help-block">:message</span>') !!}
             </div>

</div>

<div class="row">
    <div class="form-group col-lg-4 col-md-4 col-sm-4">

      

    </div>
</div>


<!--  Main Data ENDS -->


   </div>

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Save', [], 'layouts')}}
      </button>
   </div>

</div>

{!! Form::close() !!}

