@if(!empty($module_form_parts))
  @foreach($module_form_parts as $key => $value)
    @if(!empty($value['template_path']))
      @php
        $template_data = $value['template_data'] ?: [];
      @endphp
      @include($value['template_path'], $template_data)
    @endif
  @endforeach
@endif