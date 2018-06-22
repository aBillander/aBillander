@if ($v_keys)
<table id="translations" class="table table-hover">
  <thead>
    <tr>
      <th width="35%">{{l('Key')}}</th>
      <th width="35%">{{l('Translation')}}</th>
      <!-- th xwidth="35%">{{l('Is null?')}}</th -->
      <th width="20%">{{l('View files')}}</th>
      <th width="15%">{{l('Source file')}}</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($v_keys as $k => $v_key)
 <?php  
 if (!$k) continue;
 $is_translated = isset($v_key['local']) && $v_key['local'];
 $is_forcefile  = isset($v_key['forcefile']) && $v_key['forcefile'];
 $is_infile     = isset($v_key['infile']) && $v_key['infile']; ?>
    <tr>
      <td>{{ $k }}</td>
      <td class="{{ (!$is_translated && !$is_forcefile) ? 'danger' : '' }}">

          @if ($is_forcefile && ($v_key['forcefile'] != $id) )
            <!-- /td><td -->
          @else
            {!! Form::text('val['.md5($k).']', ( $is_translated ? $v_key['local'] : '' ), array('class' => 'form-control')) !!} 
            <input type="hidden" name="key[{{md5($k)}}]" value="{{ $k }}">
            <!-- /td><td -->
            {{-- Form::checkbox('nul['.md5($k).']', 1, true) --}}
          @endif</td>
      <td class="{{ !$is_infile ? 'danger' : '' }}">
          {{ isset($v_key['infile']) ? $v_key['infile'] : '' }} </td>
      <td class="text-right">{{ $is_forcefile ? $v_key['forcefile'] : '' }} 
          @if ( !$is_infile )<button type="button"  class="removebutton btn btn-sm btn-danger delete-item" title="{{l('Delete', [], 'layouts')}}">X</button>
          @endif
          </td>
    </tr>
  @endforeach
  </tbody>
</table>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif


{!! Form::submit(l('Save', [], 'layouts'), array('class' => 'btn btn-success')) !!}
{!! link_to_route('translations.index', l('Cancel', [], 'layouts'), null, array('class' => 'btn btn-warning')) !!}
