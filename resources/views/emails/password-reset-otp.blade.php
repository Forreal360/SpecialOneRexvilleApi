<x-mail::message>
# Recuperación de Contraseña

Hola {{ $clientName }},

Has solicitado recuperar tu contraseña. Tu código de verificación es:

<x-mail::panel>
<div style="text-align: center; font-size: 32px; font-weight: bold; color: #007bff; letter-spacing: 8px; margin: 20px 0;">
{{ $otpCode }}
</div>
</x-mail::panel>

**Importante:**
- Este código expira en {{ $expiresIn }}
- Solo puedes usarlo una vez
- No compartas este código con nadie
- Si no solicitaste este cambio, ignora este mensaje

Si tienes problemas, puedes contactar a nuestro equipo de soporte.

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
