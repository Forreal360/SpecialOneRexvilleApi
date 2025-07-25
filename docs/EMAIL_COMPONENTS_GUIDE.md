# Guía de Componentes de Email - Hyundai de Rexville

Esta guía describe cómo usar el sistema de plantillas y componentes de correo electrónico mejorado.

## 🏗️ Estructura de Plantillas

### Plantilla Base: `layouts.mails.main`

La plantilla principal utiliza un sistema de secciones flexible:

```blade
@extends('layouts.mails.main')

@section('title')
    Título del Correo
@endsection

@section('user_name')
    {{ $userName }}
@endsection

@section('message')
    Mensaje principal del correo
@endsection

@section('content')
    Contenido principal (componentes, texto, etc.)
@endsection

@section('additional_info')
    Información adicional o notas
@endsection
```

### Secciones Disponibles

| Sección | Descripción | Requerida |
|---------|-------------|-----------|
| `title` | Título principal del email | ✅ |
| `user_name` | Nombre del destinatario | ❌ |
| `message` | Mensaje introductorio | ❌ |
| `content` | Contenido principal | ❌ |
| `additional_info` | Información adicional | ❌ |

## 🧩 Componentes Disponibles

### 1. Botón de Acción

**Componente:** `components.mail.action-button`

Crea un botón destacado con URL de enlace.

```blade
@component('components.mail.action-button', [
    'url' => 'https://example.com/reset-password',
    'text' => 'Restablecer Contraseña'
])
@endcomponent
```

**Características:**
- Diseño responsive
- Colores de marca Hyundai
- URL de respaldo para problemas de visualización
- Target `_blank` automático

---

### 2. Información Adicional

**Componente:** `components.mail.additional-information`

Caja destacada para información importante.

```blade
@component('components.mail.additional-information')
    @slot('additional_info')
        Este enlace expira en 60 minutos.<br>
        Si no solicitaste este cambio, ignora este mensaje.
    @endslot
@endcomponent
```

**Características:**
- Borde lateral azul distintivo
- Fondo gris claro
- Tipografía optimizada para legibilidad

---

### 3. Código OTP

**Componente:** `components.mail.otp-code`

Componente especializado para mostrar códigos de verificación.

```blade
@component('components.mail.otp-code', [
    'code' => '123456',
    'expiresIn' => '15 minutos'
])
@endcomponent
```

**Parámetros:**
- `code` (requerido): Código OTP a mostrar
- `expiresIn` (opcional): Tiempo de expiración

**Características:**
- Código destacado con fuente monoespaciada
- Advertencia de seguridad incluida
- Diseño centrado y visible

---

### 4. Caja de Información

**Componente:** `components.mail.info-box`

Caja personalizable para diferentes tipos de información.

```blade
@component('components.mail.info-box', [
    'title' => 'Información Importante',
    'icon' => '⚠️',
    'bgColor' => '#fef3c7',
    'borderColor' => '#f59e0b',
    'titleColor' => '#92400e',
    'textColor' => '#78350f'
])
    @slot('content')
        Tu cita ha sido confirmada para mañana a las 10:00 AM.
    @endslot
@endcomponent
```

**Parámetros:**
- `title` (opcional): Título de la caja
- `icon` (opcional): Emoji o símbolo
- `bgColor` (opcional): Color de fondo (default: #f0f9ff)
- `borderColor` (opcional): Color del borde (default: #0080ff)
- `titleColor` (opcional): Color del título (default: #003468)
- `textColor` (opcional): Color del texto (default: #374151)
- `content` (slot): Contenido de la caja

## 📧 Ejemplos de Uso

### Email de Recuperación de Contraseña con OTP

```blade
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
            • Ingresa este código en la aplicación<br>
            • Este código expira en {{ $expiresIn }}<br>
            • No compartas este código con nadie
        @endslot
    @endcomponent
@endsection
```

### Email de Confirmación de Cita

```blade
@extends('layouts.mails.main')

@section('title')
    Confirmación de Cita
@endsection

@section('user_name')
    {{ $clientName }}
@endsection

@section('message')
    Tu cita de servicio ha sido confirmada exitosamente.
@endsection

@section('content')
    @component('components.mail.info-box', [
        'title' => 'Detalles de tu Cita',
        'icon' => '📅',
        'bgColor' => '#f0fdf4',
        'borderColor' => '#22c55e',
        'titleColor' => '#15803d'
    ])
        @slot('content')
            <strong>Fecha:</strong> {{ $appointmentDate }}<br>
            <strong>Hora:</strong> {{ $appointmentTime }}<br>
            <strong>Servicio:</strong> {{ $serviceName }}<br>
            <strong>Vehículo:</strong> {{ $vehicleInfo }}
        @endslot
    @endcomponent

    @component('components.mail.action-button', [
        'url' => $appointmentUrl,
        'text' => 'Ver Detalles de la Cita'
    ])
    @endcomponent
@endsection

@section('additional_info')
    @component('components.mail.additional-information')
        @slot('additional_info')
            Por favor llega 15 minutos antes de tu cita.<br>
            Si necesitas reprogramar, contacta nuestro centro de servicio.
        @endslot
    @endcomponent
@endsection
```

### Email de Bienvenida

```blade
@extends('layouts.mails.main')

@section('title')
    ¡Bienvenido a Hyundai de Rexville!
@endsection

@section('user_name')
    {{ $clientName }}
@endsection

@section('message')
    Gracias por registrarte en nuestra plataforma. Estamos emocionados de tenerte como parte de nuestra familia Hyundai.
@endsection

@section('content')
    @component('components.mail.action-button', [
        'url' => $activationUrl,
        'text' => 'Activar mi Cuenta'
    ])
    @endcomponent

    @component('components.mail.info-box', [
        'title' => '¿Qué puedes hacer ahora?',
        'icon' => '✨',
        'bgColor' => '#f8fafc',
        'borderColor' => '#64748b'
    ])
        @slot('content')
            • Agendar citas de servicio<br>
            • Ver el historial de tu vehículo<br>
            • Recibir promociones exclusivas<br>
            • Contactar directamente con el concesionario
        @endslot
    @endcomponent
@endsection
```

## 🎨 Personalización de Colores

### Colores de Marca Hyundai

```css
/* Azul Primario */
#003468

/* Azul Secundario */
#0080ff

/* Gradiente Principal */
linear-gradient(135deg, #003468 0%, #0080ff 100%)
```

### Colores del Sistema

| Propósito | Color | Uso |
|-----------|-------|-----|
| Texto Principal | #374151 | Contenido general |
| Texto Secundario | #6b7280 | Información adicional |
| Texto Muted | #9ca3af | Notas pequeñas |
| Éxito | #22c55e | Confirmaciones |
| Advertencia | #f59e0b | Alertas |
| Error | #dc2626 | Errores |
| Info | #0080ff | Información |

## 📱 Compatibilidad

Los componentes están optimizados para:

- ✅ Gmail (Web, iOS, Android)
- ✅ Outlook (Web, Desktop, Mobile)
- ✅ Apple Mail (macOS, iOS)
- ✅ Yahoo Mail
- ✅ Thunderbird
- ✅ Clientes móviles nativos

## 🛠️ Mejores Prácticas

### Estructura del Email

1. **Mantén el título claro y conciso**
2. **Usa el nombre del usuario cuando esté disponible**
3. **Mensaje introductorio breve pero informativo**
4. **Un solo call-to-action principal por email**
5. **Información adicional en la sección correspondiente**

### Accesibilidad

1. **Usa texto descriptivo en enlaces**
2. **Mantén buen contraste de colores**
3. **Proporciona URLs de respaldo**
4. **Usa estructuras semánticas**

### Testing

```bash
# Probar envío de email
php artisan tinker

# Enviar email de prueba
Mail::to('test@example.com')->send(new \App\Mail\PasswordResetOtpMail(
    clientName: 'Usuario Prueba',
    otpCode: '123456',
    expiresIn: '15 minutos'
));
```

---

*Guía de Componentes de Email v1.0 - Hyundai de Rexville* 
