

{!! Form::open(array('route' => array('cheques.attachment.store', $cheque->id), 'xtitle' => l('Upload an Attach Files', 'layouts'), 'class' => '', 'id' => 'add-attachment-action', 'files' => true)) !!}
<input type="hidden" value="attachments" name="tab_name" id="tab_name">

                      <input type="hidden" value="App\Cheque"     name="model_class"     id="model_class">
                      <input type="hidden" value="{{ $cheque->id }}"                 name="model_id"        id="model_id">
                      <input type="hidden" value="{{ $cheque->document_number ?: $cheque->id }}" name="model_reference" id="model_reference">
                      <input type="hidden" value="#attachments"     name="previous_anchor"     id="previous_anchor">

<div class="panel panel-primary" id="panel_attachments">
   <div class="panel-heading">
      <h3 class="panel-title">{{ l('Attachments', 'layouts') }}</h3>
   </div>
   <div class="panel-body">

<!-- Attachments -->
<div class="row">
    <div class="form-group col-lg-6 col-md-6 col-sm-6">
       {!! Form::label('attachment_file', l('Upload File', 'layouts')) !!}
       {{-- Form::file('image', null, array('required', 'class'=>'form-control')) --}}

            <div class="input-group {{ $errors->has('attachment_file') ? 'has-error' : '' }}" style="margin-top: 10px; margin-bottom: 10px;">
                <label class="input-group-btn">
                    <span class="btn btn-blue xbtn-sm">
                        {{ l('Browse', [], 'layouts') }}&hellip; <input type="file" name="attachment_file" id="attachment_file" style="display: none;" multiple>
                    </span>
                </label>
                <input type="text" class="form-control xinput-sm" readonly>
            </div>

    </div>
</div>
<div class="row">

    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        {!! Form::label('attachment_name', l('Description')) !!}
        {!! Form::text('attachment_name', null, array('class' => 'form-control xinput-sm', 'xstyle' => 'margin-top: 10px; margin-bottom: 10px;', 'id' => 'attachment_name')) !!}
    </div>

</div>
  

   <div class="panel-footer text-right">
      <button class="btn btn-sm btn-info" type="submit" onclick="this.disabled=true;this.form.submit();">
         <i class="fa fa-hdd-o"></i>
         &nbsp; {{l('Upload File', 'layouts')}}
      </button>
   </div>

        <div class="row">
         <hr style="height:2px; border: 1px solid #eeeeee;">
        </div>
        <div class="row">
              <div class="form-group col-lg-10 col-md-10 col-sm-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">

    <div id="div_attachments">
       <div class="table-responsive">

    <table id="attachments" class="table table-hover">
      <thead>
        <tr>
          <th>{{l('ID', [], 'layouts')}}</th>
          <th>{{ l('Description') }}</th>
          <th>{{l('File Name', 'layouts')}}</th>
          <th class="text-right"> </th>
        </tr>
      </thead>
      <tbody>
      @if ($cheque->attachments->count())
         @foreach ($cheque->attachments as $attachment)
           <tr style="vertical-align:middle;">
               <td>{{ $attachment->id }}</td>
               <td>{{ $attachment->name }}</td>
               <td>
                      <a href="{{ route( 'cheques.attachment.show', [$cheque->id, $attachment->id] ) }}" title="{{l('View Document', 'layouts')}}">

                          {{ $attachment->filename }}

                      </a> 
               </td>

               <td class="text-right">
                
                        <a class="btn btn-xs alert-danger delete-item" data-html="false" data-toggle="modal" 
                        data-id="{{$attachment->id}}" 
                        href="{{ route( 'cheques.attachment.destroy', [$cheque->id, $attachment->id] ) }}" 
                        data-previous_anchor="#attachments" 
                        data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                        data-title="{{ '('.$attachment->id.') '.$attachment->filename }}" 
                        onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>

                </td>
           </tr>
         @endforeach
      @else
           <tr style="vertical-align:middle;">
               <td colspan=5>
                 <div class="alert alert-warning alert-block">
                     <i class="fa fa-warning"></i>
                     {{l('No records found', [], 'layouts')}}
                 </div>
                </td>
           </tr>
      @endif
        </tbody>
    </table>

       </div>
    </div>




              </div>
        </div>

        <div class="row">
        </div>

<!-- Attachments ENDS -->

   </div>

</div>

{!! Form::close() !!}

  @include('model_attachments/_form_attachments')
