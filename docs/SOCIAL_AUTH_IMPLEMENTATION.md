# Implementación de Login con Redes Sociales

## Descripción

Esta implementación permite a los usuarios autenticarse usando redes sociales (Facebook, Google, Apple) y conectar múltiples cuentas sociales a su perfil.

## Estructura

### Tabla `social_accounts`
- `id`: Identificador único
- `client_id`: Referencia al cliente
- `provider`: Proveedor (facebook, google, apple)
- `provider_user_id`: ID del usuario en la red social
- `email`: Email de la cuenta social
- `name`: Nombre en la red social
- `avatar`: URL del avatar
- `provider_data`: Datos adicionales del proveedor (JSON)
- `created_at`, `updated_at`: Timestamps

### Modelos

#### `SocialAccount`
- Relación con `Client`
- Cast automático de `provider_data` a array

#### `Client` (actualizado)
- Relación `socialAccounts()` con `SocialAccount`

### Actions

#### `LoginWithSocialMediaAction`
- Valida token de red social
- Busca usuario existente por cuenta social o email
- Crea nuevo cliente si no existe
- Genera token de autenticación
- Retorna cliente y token

#### `ConnectSocialAccountAction`
- Conecta nueva cuenta social al perfil autenticado
- Valida que no esté ya conectada
- Previene duplicados

#### `DisconnectSocialAccountAction`
- Desconecta cuenta social del perfil
- Valida que tenga al menos un método de autenticación

#### `GetSocialAccountsAction`
- Lista todas las cuentas sociales conectadas

### Controlador

#### `SocialAuthController`
- `loginWithSocial()`: Login con red social
- `connectSocialAccount()`: Conectar cuenta
- `disconnectSocialAccount()`: Desconectar cuenta
- `getSocialAccounts()`: Listar cuentas

## Rutas API

### Públicas
```
POST /api/v1/login-with-social
```

### Protegidas (requieren autenticación)
```
POST /api/v1/connect-social-account
DELETE /api/v1/disconnect-social-account
GET /api/v1/social-accounts
```

## Uso

### Login con Red Social
```json
POST /api/v1/login-with-social
{
    "token": "token_from_social_provider",
    "social_auth": "google|facebook|apple",
    "fcm_token": "fcm_token",
    "os": "android|ios|web"
}
```

### Conectar Cuenta Social
```json
POST /api/v1/connect-social-account
{
    "token": "token_from_social_provider",
    "social_auth": "google|facebook|apple"
}
```

### Desconectar Cuenta Social
```json
DELETE /api/v1/disconnect-social-account
{
    "social_auth": "google|facebook|apple"
}
```

### Obtener Cuentas Conectadas
```
GET /api/v1/social-accounts
```

## Configuración Requerida

### Laravel Socialite
Instalar y configurar Laravel Socialite:

```bash
composer require laravel/socialite
```

### Configuración en `config/services.php`
```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],

'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => env('FACEBOOK_REDIRECT_URI'),
],

'apple' => [
    'client_id' => env('APPLE_CLIENT_ID'),
    'client_secret' => env('APPLE_CLIENT_SECRET'),
    'redirect' => env('APPLE_REDIRECT_URI'),
],
```

### Variables de Entorno
```env
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=your_google_redirect_uri

FACEBOOK_CLIENT_ID=your_facebook_client_id
FACEBOOK_CLIENT_SECRET=your_facebook_client_secret
FACEBOOK_REDIRECT_URI=your_facebook_redirect_uri

APPLE_CLIENT_ID=your_apple_client_id
APPLE_CLIENT_SECRET=your_apple_client_secret
APPLE_REDIRECT_URI=your_apple_redirect_uri
```

## Características

- ✅ Login con múltiples proveedores (Google, Facebook, Apple)
- ✅ Conexión de múltiples cuentas al mismo perfil
- ✅ Prevención de duplicados
- ✅ Validación de métodos de autenticación
- ✅ Manejo de errores robusto
- ✅ Logging de operaciones
- ✅ Transacciones de base de datos
- ✅ Seguimiento del patrón Action-Service

## Flujo de Autenticación

1. Cliente obtiene token de red social
2. Envía token + proveedor a `/login-with-social`
3. Sistema valida token con proveedor
4. Busca usuario existente por cuenta social o email
5. Crea nuevo usuario si no existe
6. Genera token de autenticación
7. Retorna cliente y token

## Flujo de Conexión

1. Usuario autenticado obtiene token de nueva red social
2. Envía token + proveedor a `/connect-social-account`
3. Sistema valida que no esté ya conectada
4. Crea nueva cuenta social vinculada al perfil
5. Retorna confirmación 
