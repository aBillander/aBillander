<br>
@lang('lang_v1.allowed_file'):
	@foreach(config('constants.document_upload_mimes_types') as $key => $value)
		@if(!$loop->last)
			{{$value . ','}}
		@else
			{{$value}}
	    @endif
	@endforeach