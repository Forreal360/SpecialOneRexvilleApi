@extends('layouts.mails.main')

@section('title')
    Recuperación de Contraseña
@endsection

@section('user_name')
    {{ $clientName }}
@endsection

@section('message')
    Has solicitado recuperar tu contraseña. Tu código de verificación es:
@endsection

@section('content')
    @component('components.mail.otp-code', [
        'code' => $otpCode,
        'expiresIn' => $expiresIn
    ])
    @endcomponent
@endsection

@section('additional_info')
    @component('components.mail.additional-information')
        @slot('additional_info')
            <strong>Instrucciones:</strong><br>
            • Ingresa este código en la aplicación para continuar<br>
            • Este código expira en {{ $expiresIn }}<br>
            • Solo puedes usarlo una vez<br>
            • No compartas este código con nadie<br><br>
            <strong>¿No solicitaste este cambio?</strong><br>
            Si no solicitaste restablecer tu contraseña, ignora este mensaje. Tu cuenta permanece segura.
        @endslot
    @endcomponent
@endsection
