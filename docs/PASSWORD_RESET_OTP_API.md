# API de Recuperación de Contraseña con OTP

Este documento describe la implementación del sistema de recuperación de contraseña utilizando códigos OTP (One-Time Password) enviados por email.

## 🏗️ Arquitectura del Sistema

### Componentes Principales

1. **Migración**: `create_client_password_otp_table.php`
2. **Modelo**: `ClientPasswordOtp.php`
3. **Actions**:
   - `SendPasswordResetOtpAction.php`
   - `VerifyPasswordResetOtpAction.php`
   - `ResetPasswordWithOtpAction.php`
4. **Controlador**: `PasswordResetController.php`
5. **Email**: `PasswordResetOtpMail.php`
6. **Comando**: `CleanupExpiredOtpCommand.php`

### Base de Datos

**Tabla: `client_password_otp`**

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | BigInteger | ID único del registro |
| `email` | String | Email del cliente (FK a clients.email) |
| `otp_code` | String(6) | Código OTP de 6 dígitos |
| `expires_at` | DateTime | Fecha y hora de expiración |
| `is_used` | Boolean | Si el código ya fue usado |
| `attempts` | Integer | Número de intentos de verificación |
| `created_at` | DateTime | Fecha de creación |
| `updated_at` | DateTime | Fecha de actualización |

**Índices:**
- `email`
- `email + otp_code`
- `expires_at`

## 🔗 Endpoints de la API

### Base URL
```
/api/v1/password-reset/
```

### 1. Enviar Código OTP

**POST** `/send-otp`

Genera y envía un código OTP de 6 dígitos al email del cliente.

**Request Body:**
```json
{
    "email": "cliente@email.com"
}
```

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "message": "Código OTP enviado correctamente a tu email.",
        "expires_at": "2025-07-25 16:30:00"
    }
}
```

**Response Error (422):**
```json
{
    "success": false,
    "errors": {
        "email": [
            "El email no está registrado o la cuenta no está activa."
        ]
    }
}
```

**Límites:**
- Máximo 3 códigos por hora por email
- Código expira en 15 minutos

---

### 2. Verificar Código OTP

**POST** `/verify-otp`

Verifica el código OTP ingresado por el usuario.

**Request Body:**
```json
{
    "email": "cliente@email.com",
    "otp_code": "123456"
}
```

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "message": "Código verificado correctamente.",
        "otp_token": "a1b2c3d4e5f6...",
        "expires_at": "2025-07-25 16:30:00"
    }
}
```

**Response Error (422):**
```json
{
    "success": false,
    "errors": {
        "otp_code": [
            "Código OTP inválido."
        ]
    }
}
```

**Límites:**
- Máximo 5 intentos por código
- Token temporal válido por 10 minutos

---

### 3. Resetear Contraseña

**POST** `/reset-password`

Actualiza la contraseña del cliente usando el token OTP verificado.

**Request Body:**
```json
{
    "otp_token": "a1b2c3d4e5f6...",
    "new_password": "nueva_contraseña_segura",
    "new_password_confirmation": "nueva_contraseña_segura"
}
```

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "message": "Contraseña actualizada correctamente.",
        "email": "cliente@email.com"
    }
}
```

**Response Error (422):**
```json
{
    "success": false,
    "errors": {
        "otp_token": [
            "Token OTP inválido o expirado."
        ]
    }
}
```

**Efectos:**
- Actualiza la contraseña del cliente
- Marca el código OTP como usado
- Revoca todos los tokens de acceso existentes

## 🔒 Medidas de Seguridad

### Límites y Throttling
- **3 códigos máximo por hora** por email
- **5 intentos máximo** de verificación por código
- **15 minutos** de expiración para códigos OTP
- **10 minutos** de expiración para tokens de verificación

### Validaciones
- Email debe existir en la tabla `clients`
- Cliente debe estar activo (`status = 'A'`)
- Contraseña debe tener mínimo 8 caracteres
- Confirmación de contraseña requerida

### Logging
- Todos los eventos se registran en logs
- Intentos fallidos se monitorean
- Estadísticas de uso se mantienen

### Tokens Seguros
- Tokens incluyen hash SHA-256 para integridad
- Payload codificado en Base64
- Incluyen timestamps de expiración
- Validación de integridad en cada uso

## 📧 Notificación por Email

### Plantilla
- **Asunto**: "Código de Recuperación de Contraseña - Hyundai Special One"
- **Contenido**: Código OTP destacado, instrucciones claras
- **Formato**: Markdown responsive

### Configuración
```php
// En SendPasswordResetOtpAction.php
Mail::to($client->email)->send(new PasswordResetOtpMail(
    clientName: $client->name,
    otpCode: $otpCode,
    expiresIn: '15 minutos'
));
```

## 🛠️ Comandos de Mantenimiento

### Limpiar Códigos Expirados

```bash
# Modo dry-run (solo mostrar qué se eliminaría)
php artisan otp:cleanup --dry-run

# Eliminar códigos expirados de más de 1 día (default)
php artisan otp:cleanup

# Eliminar códigos expirados de más de 7 días
php artisan otp:cleanup --days=7
```

**Recomendación**: Ejecutar diariamente en cron job.

## 📊 Uso del Modelo

### Métodos Útiles

```php
// Crear código OTP para un email
$otp = ClientPasswordOtp::createForEmail('cliente@email.com', 15);

// Buscar códigos válidos
$validOtp = ClientPasswordOtp::byEmail('cliente@email.com')
    ->valid()
    ->first();

// Verificar estado
$otp->isValid();   // true/false
$otp->isExpired(); // true/false
$otp->isUsed();    // true/false

// Gestionar estado
$otp->markAsUsed();
$otp->incrementAttempts();

// Generar código aleatorio
$code = ClientPasswordOtp::generateOtpCode(); // "123456"
```

### Scopes Disponibles

```php
// Códigos válidos (no usados y no expirados)
ClientPasswordOtp::valid()

// Códigos por email
ClientPasswordOtp::byEmail($email)

// Combinados
ClientPasswordOtp::byEmail($email)->valid()->first()
```

## 🔄 Flujo Completo

1. **Cliente solicita reset**
   - POST `/password-reset/send-otp`
   - Sistema valida email y cuenta activa
   - Genera código OTP de 6 dígitos
   - Envía email con código
   - Invalidates códigos anteriores

2. **Cliente ingresa código**
   - POST `/password-reset/verify-otp`
   - Sistema valida código y límites
   - Genera token temporal seguro
   - Devuelve token para siguiente paso

3. **Cliente establece nueva contraseña**
   - POST `/password-reset/reset-password`
   - Sistema valida token temporal
   - Actualiza contraseña con hash
   - Marca OTP como usado
   - Revoca todos los tokens existentes

## ⚠️ Consideraciones

### Producción
- Configurar envío de emails real en `config/mail.php`
- Establecer límites apropiados según volumen
- Monitorear logs por intentos sospechosos
- Programar limpieza automática de códigos

### Desarrollo
- Los emails se registran en logs para debugging
- Usar `--dry-run` para comandos de limpieza
- Verificar configuración de timezone

### Escalabilidad
- Considerar cache para límites si hay alto volumen
- Evaluar queue para envío de emails
- Implementar métricas de uso

## 🧪 Testing

### Casos de Prueba

```bash
# Enviar código OTP
curl -X POST http://localhost/api/v1/password-reset/send-otp \
  -H "Content-Type: application/json" \
  -d '{"email": "test@example.com"}'

# Verificar código
curl -X POST http://localhost/api/v1/password-reset/verify-otp \
  -H "Content-Type: application/json" \
  -d '{"email": "test@example.com", "otp_code": "123456"}'

# Resetear contraseña
curl -X POST http://localhost/api/v1/password-reset/reset-password \
  -H "Content-Type: application/json" \
  -d '{"otp_token": "token...", "new_password": "nueva123", "new_password_confirmation": "nueva123"}'
```

### Casos Edge

- Email inexistente
- Cuenta inactiva
- Código expirado
- Código ya usado
- Límites excedidos
- Token inválido
- Contraseñas que no coinciden

---

*Documentación del Sistema de Recuperación de Contraseña con OTP v1.0* 
