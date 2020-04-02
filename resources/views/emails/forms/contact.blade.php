@component('mail::message')
# Introduction

The body of your message.

<strong>{{ $data['message'] ?? '' }}</strong>

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
