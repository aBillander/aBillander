@if($errors->any())
	<div class="alert alert-danger">
		<a href="#" class="close" data-dismiss="alert">&times;</a>
		{!! implode('', $errors->all('<li class="error">:message</li>')) !!}
	</div>
@endif