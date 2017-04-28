@component('mail::message')
#Redefinição de senha

@component('mail::button', ['url' => $url])
Alterar senha
@endcomponent

Esse email foi enviado automaticamente, por favor não responda.<br>
{{ config('app.name') }}
@endcomponent
