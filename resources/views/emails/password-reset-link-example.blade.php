@extends('layouts.mails.main')

@section('title')
    Restablecer Contraseña
@endsection

@section('user_name')
    {{ $notifiable->name ?? '' }}
@endsection

@section('message')
    Has recibido este correo porque recibimos una solicitud de restablecimiento de contraseña para tu cuenta.
@endsection

@section('content')
    @component('components.mail.action-button', [
        'url' => $url,
        'text' => 'Restablecer Contraseña'
    ])
    @endcomponent
@endsection

@section('additional_info')
    @component('components.mail.additional-information')
        @slot('additional_info')
            Este enlace de restablecimiento de contraseña expirará en {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutos.<br><br>
            Si no solicitaste un restablecimiento de contraseña, no es necesario realizar ninguna acción.
        @endslot
    @endcomponent
@endsection
