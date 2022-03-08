@if ( isset($errors) && (count($errors) > 0) )        
{{-- Google this: "why $errors is not defined" --}}
<div class="alert alert-danger alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>{!! l('Error', [], 'layouts') !!}: </strong>
    {!! l('Please check the form below for errors', [], 'layouts') !!}

    @foreach ($errors->all('<div class="alert alert-danger">:message</div>') as $error)
        {!! $error !!}
    @endforeach

</div>
@endif

@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>{!! l('Success', [], 'layouts') !!}: </strong>
    @if(is_array($message))
        <ul>
        @foreach ($message as $m)
            <li>{!! $m !!}</li>
        @endforeach
        </ul>
    @else
        {!! $message !!}
    @endif
</div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>{!! l('Error', [], 'layouts') !!}: </strong>
    @if(is_array($message))
        <ul>
        @foreach ($message as $m)
            <li>{!! $m !!}</li>
        @endforeach
        </ul>
    @else
    {!! $message !!}
    @endif
</div>
@endif

@if ( ($message = Session::get('warning')) ?? ($message = (isset($warning) AND count($warning)) ? $warning : '') )
<div class="alert alert-warning alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>{!! l('Warning', [], 'layouts') !!}: </strong>
    @if(is_array($message))
        <ul>
        @foreach ($message as $m)
            <li>{!! $m !!}</li>
        @endforeach
        </ul>
    @else
    {!! $message !!}
    @endif
</div>
@endif

@if ($message = Session::get('info'))
<div class="alert alert-info alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>{!! l('Info', [], 'layouts') !!}: </strong>
    @if(is_array($message))
        <ul>
        @foreach ($message as $m)
            <li>{!! $m !!}</li>
        @endforeach
        </ul>
    @else
    {!! $message !!}
    @endif
</div>
@endif

@if ($message = Session::get('notice'))
<div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>{!! l('Success', [], 'layouts') !!}: </strong>
    @if(is_array($message))
        <ul>
        @foreach ($message as $m)
            <li>{!! $m !!}</li>
        @endforeach
        </ul>
    @else
    {!! $message !!}
    @endif
</div>
@endif

{{--

    @foreach ($errors->all('<div class="alert alert-danger">:message</div>') as $error)
        {!! $error !!}
    @endforeach

    @if (session()->has('error'))
        <div class="alert alert-danger">{!! session('error') !!}</div>
    @endif

    @if (session()->has('alert'))
        <div class="alert alert-warning">{!! session('alert') !!}</div>
    @endif

    @if (session()->has('alertSuccess'))
        <div class="alert alert-success">{!! session('alertSuccess') !!}</div>
    @endif

    @if (session()->has('alertInfo'))
        <div class="alert alert-info">{!! session('alertInfo') !!}</div>
    @endif

--}}
