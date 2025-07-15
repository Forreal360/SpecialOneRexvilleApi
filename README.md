# SpecialOneRexvilleApi

<p align="center">
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<p align="center">
<a href="https://github.com/Forreal360/SpecialOneRexvilleApi"><img src="https://img.shields.io/github/stars/Forreal360/SpecialOneRexvilleApi" alt="GitHub Stars"></a>
<a href="https://github.com/Forreal360/SpecialOneRexvilleApi/fork"><img src="https://img.shields.io/github/forks/Forreal360/SpecialOneRexvilleApi" alt="GitHub Forks"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Laravel Version"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-yellow.svg" alt="License"></a>
</p>

## 🚗 Acerca del Proyecto

**SpecialOneRexvilleApi** es una **API REST moderna** para la gestión integral de servicios automotrices desarrollada con **Laravel 12.20** y arquitectura **Action-Service**. La aplicación permite a los clientes gestionar sus vehículos, acceder a servicios especializados, recibir promociones personalizadas y mantener un historial completo de mantenimiento con autenticación multi-plataforma.

### 🎯 Características Principales

- **🔐 Autenticación Multi-Plataforma**: Login con email, Google, Facebook y Apple
- **👤 Gestión Completa de Clientes**: Perfiles detallados con información personal y contacto
- **🚙 Registro de Vehículos**: Gestión completa con VIN, modelo, año, fecha de compra e información de seguros
- **🔧 Servicios Automotrices**: Historial completo de servicios por vehículo con fechas y detalles
- **🏷️ Sistema de Promociones**: Promociones con vigencia, imágenes y URLs de redirección
- **🔔 Notificaciones Push**: Sistema completo de notificaciones con estado de lectura
- **🌐 Cuentas Sociales**: Gestión de múltiples cuentas de redes sociales vinculadas
- **📱 Tokens FCM**: Manejo de tokens para notificaciones push móviles

### 🏗️ Arquitectura del Sistema

- **🏛️ Patrón Action-Service**: Separación clara entre lógica de negocio y operaciones de dominio
- **⚡ ActionResult Pattern**: Respuestas consistentes y estructuradas
- **🛡️ Manejo Centralizado de Excepciones**: Template Method Pattern para gestión de errores
- **🔄 Versionado de API**: Estructura V1 preparada para escalabilidad futura
- **🧪 Testing Integrado**: Suite completa de pruebas para Actions y Services

## 🔧 Stack Tecnológico

- **🚀 Laravel 12.20**: Framework PHP moderno con las últimas características
- **🎨 Tailwind CSS v4**: Framework CSS utility-first de nueva generación
- **⚡ Vite v6**: Build tool ultrarrápido con HMR
- **🔐 Laravel Sanctum**: Autenticación API segura con tokens personales
- **🌐 Laravel Socialite**: Integración OAuth con múltiples proveedores
- **📱 Livewire 3.6**: Componentes reactivos full-stack
- **🧪 PHPUnit 11.5**: Framework de testing moderno
- **📊 SQLite/MySQL**: Base de datos flexible y escalable

## 📡 Endpoints de la API

### 🔐 Autenticación
```http
POST   /api/v1/login-with-email          # Login con email y contraseña
POST   /api/v1/login-with-social         # Login con OAuth (Google, Facebook, Apple)
GET    /api/v1/logout                    # Cerrar sesión y revocar token
POST   /api/v1/refresh-token             # Renovar token de autenticación
POST   /api/v1/refresh-fcm-token         # Actualizar token FCM para push
```

### 👤 Gestión de Perfil
```http
GET    /api/v1/profile                   # Obtener perfil completo del cliente
PUT    /api/v1/profile                   # Actualizar información del perfil
```

### 🌐 Cuentas Sociales
```http
POST   /api/v1/connect-social-account    # Conectar cuenta social (Google, Facebook, Apple)
DELETE /api/v1/disconnect-social-account # Desconectar cuenta social específica
GET    /api/v1/social-accounts           # Obtener todas las cuentas conectadas
```

### 🚙 Vehículos
```http
GET    /api/v1/vehicles                  # Obtener todos los vehículos del cliente
```

### 🔧 Servicios
```http
GET    /api/v1/services                           # Obtener todos los servicios del cliente
GET    /api/v1/vehicles/{vehicle_id}/services     # Obtener servicios específicos de un vehículo
```

### 🏷️ Promociones
```http
GET    /api/v1/promotions                # Obtener promociones activas y vigentes
```

### 🔔 Notificaciones
```http
GET    /api/v1/notifications                        # Obtener todas las notificaciones
GET    /api/v1/notifications/{id}/mark-as-read      # Marcar notificación como leída
PUT    /api/v1/notifications/mark-all-as-read       # Marcar todas las notificaciones como leídas
DELETE /api/v1/notifications/{id}                   # Eliminar notificación específica
```

## 🛠️ Instalación y Configuración

### 📋 Requisitos del Sistema
- **PHP 8.2+** con extensiones requeridas
- **Composer 2.0+** para gestión de dependencias
- **Node.js 18+** y **npm 9+**
- **MySQL 8.0+** o **PostgreSQL 13+** (o SQLite para desarrollo)
- **Redis 6.0+** (opcional, para caché y colas)

### 🚀 Instalación Paso a Paso

1. **Clonar el repositorio**
```bash
git clone https://github.com/Forreal360/SpecialOneRexvilleApi.git
cd SpecialOneRexvilleApi
```

2. **Instalar dependencias del backend**
```bash
composer install --optimize-autoloader
```

3. **Instalar dependencias del frontend**
```bash
npm install
```

4. **Configurar variables de entorno**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configurar base de datos**
```env
# Para MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=specialone_rexville
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

# Para SQLite (desarrollo)
DB_CONNECTION=sqlite
DB_DATABASE=/ruta/absoluta/database/database.sqlite
```

6. **Configurar autenticación OAuth**
```env
# Google OAuth
GOOGLE_CLIENT_ID=tu_google_client_id
GOOGLE_CLIENT_SECRET=tu_google_client_secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

# Facebook OAuth
FACEBOOK_CLIENT_ID=tu_facebook_client_id
FACEBOOK_CLIENT_SECRET=tu_facebook_client_secret
FACEBOOK_REDIRECT_URI="${APP_URL}/auth/facebook/callback"

# Apple OAuth
APPLE_CLIENT_ID=tu_apple_client_id
APPLE_CLIENT_SECRET=tu_apple_client_secret
APPLE_REDIRECT_URI="${APP_URL}/auth/apple/callback"
```

7. **Configurar notificaciones push**
```env
# Firebase Cloud Messaging
FIREBASE_SERVER_KEY=tu_firebase_server_key
FIREBASE_SENDER_ID=tu_firebase_sender_id
```

8. **Ejecutar migraciones y seeders**
```bash
php artisan migrate --seed
```

9. **Compilar assets para desarrollo**
```bash
npm run dev
```

10. **Iniciar servidor de desarrollo**
```bash
php artisan serve
```

### 🔧 Configuración Adicional

#### Almacenamiento de Archivos
```bash
# Crear enlace simbólico para archivos públicos
php artisan storage:link

# Configurar permisos (Linux/Mac)
chmod -R 755 storage bootstrap/cache
```

#### Configuración de Cola de Trabajos
```bash
# Iniciar worker de colas
php artisan queue:work

# O usar supervisor para producción
sudo supervisorctl start laravel-worker:*
```

## 📊 Esquema de Base de Datos

### 👤 Clients (Clientes)
```sql
- id (bigint, primary key)
- name (string, 255)
- email (string, unique)
- email_verified_at (timestamp, nullable)
- password (string, hashed)
- phone_code (string, 5) -- Código de país
- phone (string, 15) -- Número de teléfono
- license_number (string, nullable) -- Número de licencia
- profile_photo (string, nullable) -- URL de foto de perfil
- status (enum: A, I, T) -- Activo, Inactivo, Temporal
- fcm_token (string, nullable) -- Token para push notifications
- created_at, updated_at (datetime)
```

### 🚙 Vehicles (Vehículos)
```sql
- id (bigint, primary key)
- client_id (bigint, foreign key -> clients.id)
- year (integer) -- Año del vehículo
- model (string) -- Modelo del vehículo
- vin (string) -- Vehicle Identification Number
- buy_date (date) -- Fecha de compra
- insurance (string) -- Información del seguro
- created_at, updated_at (datetime)
```

### 🔧 Services (Servicios)
```sql
- id (bigint, primary key)
- client_id (bigint, foreign key -> clients.id)
- vehicle_id (bigint, foreign key -> vehicles.id)
- date (date) -- Fecha del servicio
- name (string) -- Nombre/descripción del servicio
- created_at, updated_at (datetime)
```

### 🏷️ Promotions (Promociones)
```sql
- id (bigint, primary key)
- title (string) -- Título de la promoción
- start_date (datetime) -- Fecha de inicio
- end_date (datetime) -- Fecha de finalización
- image_url (string, nullable) -- URL de imagen promocional
- redirect_url (string, nullable) -- URL de redirección
- status (enum: A, I) -- Activo, Inactivo
- created_at, updated_at (datetime)
```

### 🔔 Client Notifications (Notificaciones)
```sql
- id (bigint, primary key)
- client_id (bigint, foreign key -> clients.id)
- title (string) -- Título de la notificación
- message (text) -- Mensaje de la notificación
- payload (string, nullable) -- Datos adicionales JSON
- read (enum: Y, N) -- Leído/No leído
- status (enum: A, I, T) -- Activo, Inactivo, Temporal
- read_at (date, nullable) -- Fecha de lectura
- created_at, updated_at (datetime)
```

### 🌐 Social Accounts (Cuentas Sociales)
```sql
- id (bigint, primary key)
- client_id (bigint, foreign key -> clients.id)
- provider (string) -- google, facebook, apple
- provider_user_id (string) -- ID del usuario en el proveedor
- email (string, nullable) -- Email del proveedor
- name (string, nullable) -- Nombre del proveedor
- avatar (string, nullable) -- URL del avatar
- provider_data (json, nullable) -- Datos adicionales
- created_at, updated_at (datetime)
```

## 🎨 Uso de la API

### 🔐 Ejemplo de Autenticación con Email
```bash
curl -X POST https://api.specialonerexville.com/api/v1/login-with-email \
  -H "Content-Type: application/json" \
  -d '{
    "email": "cliente@example.com",
    "password": "password123"
  }'
```

### 🌐 Ejemplo de Login Social
```bash
curl -X POST https://api.specialonerexville.com/api/v1/login-with-social \
  -H "Content-Type: application/json" \
  -d '{
    "provider": "google",
    "provider_token": "google_access_token_aqui"
  }'
```

### 📱 Ejemplo de Respuesta Exitosa
```json
{
  "success": true,
  "data": {
    "client": {
      "id": 1,
      "name": "Juan Pérez",
      "email": "juan@example.com",
      "phone": "+57 300 123 4567",
      "license_number": "12345678",
      "profile_photo": "https://example.com/photos/user1.jpg",
      "status": "A"
    },
    "token": "1|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "expires_at": "2024-08-14T10:00:00.000000Z"
  },
  "message": "Login exitoso",
  "errors": []
}
```

### 🚙 Ejemplo de Obtener Vehículos
```bash
curl -X GET https://api.specialonerexville.com/api/v1/vehicles \
  -H "Authorization: Bearer 1|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
```

### 🔧 Ejemplo de Obtener Servicios por Vehículo
```bash
curl -X GET https://api.specialonerexville.com/api/v1/vehicles/1/services \
  -H "Authorization: Bearer tu_token_aqui"
```

## 🏛️ Arquitectura del Proyecto

```
SpecialOneRexvilleApi/
├── app/
│   ├── Actions/V1/                     # 🎯 Lógica de negocio
│   │   ├── Auth/                       # Autenticación y autorización
│   │   │   ├── LoginWithEmailAction.php
│   │   │   ├── LoginWithSocialMediaAction.php
│   │   │   ├── LogoutAction.php
│   │   │   ├── RefreshTokenAction.php
│   │   │   ├── RefreshFcmTokenAction.php
│   │   │   ├── ConnectSocialAccountAction.php
│   │   │   ├── DisconnectSocialAccountAction.php
│   │   │   └── GetSocialAccountsAction.php
│   │   ├── Client/                     # Gestión de clientes
│   │   │   ├── GetClientAction.php
│   │   │   └── UpdateClientAction.php
│   │   ├── Vehicle/                    # Gestión de vehículos
│   │   │   └── ListVehicleAction.php
│   │   ├── Service/                    # Servicios automotrices
│   │   │   ├── ListClientServicesAction.php
│   │   │   └── ListVehicleServicesAction.php
│   │   ├── Promotion/                  # Promociones
│   │   └── Notification/               # Notificaciones
│   │   ├── Action.php                  # Clase base con manejo centralizado
│   │   └── ExampleAction.php           # Ejemplo de implementación
│   │
│   ├── Services/V1/                    # 🔧 Servicios de dominio
│   │   ├── ClientService.php           # Operaciones de cliente
│   │   ├── VehicleService.php          # Operaciones de vehículo
│   │   ├── ServiceService.php          # Operaciones de servicio
│   │   ├── PromotionService.php        # Operaciones de promoción
│   │   ├── ClientNotificationService.php # Operaciones de notificación
│   │   └── Service.php                 # Clase base de servicios
│   │
│   ├── Models/                         # 📊 Modelos Eloquent
│   │   ├── Client.php                  # Modelo de cliente
│   │   ├── Vehicle.php                 # Modelo de vehículo
│   │   ├── Service.php                 # Modelo de servicio
│   │   ├── Promotion.php               # Modelo de promoción
│   │   ├── ClientNotification.php      # Modelo de notificación
│   │   └── SocialAccount.php           # Modelo de cuenta social
│   │
│   ├── Http/Controllers/V1/Api/        # 🌐 Controladores API
│   │   ├── Auth/                       # Controladores de autenticación
│   │   │   ├── LoginController.php
│   │   │   └── SocialAuthController.php
│   │   ├── ClientController.php        # Controlador de clientes
│   │   ├── VehicleController.php       # Controlador de vehículos
│   │   ├── ServiceController.php       # Controlador de servicios
│   │   ├── PromotionController.php     # Controlador de promociones
│   │   └── ClientNotificationController.php
│   │
│   ├── Support/                        # 🛠️ Clases de soporte
│   │   └── ActionResult.php            # Respuestas consistentes
│   │
│   └── Console/Commands/               # 🖥️ Comandos Artisan personalizados
│       └── MakeModuleCommand.php       # Generador de módulos
│
├── database/
│   ├── migrations/                     # 📋 Migraciones de BD
│   ├── seeders/                        # 🌱 Seeders de datos
│   └── factories/                      # 🏭 Factories para testing
│
├── resources/
│   ├── css/                           # 🎨 Estilos CSS
│   ├── js/                            # ⚡ JavaScript
│   └── views/                         # 👀 Vistas Blade
│
├── routes/
│   ├── api.php                        # 🛤️ Rutas API principales
│   ├── Api/api_v1.php                 # 🛤️ Rutas API V1
│   └── web.php                        # 🌐 Rutas web
│
├── tests/                             # 🧪 Suite de pruebas
│   ├── Feature/                       # Pruebas de funcionalidad
│   └── Unit/                          # Pruebas unitarias
│
└── docs/                              # 📚 Documentación
    └── ACTION_RESULT_PATTERN.md       # Patrón ActionResult
```

## 🧪 Testing

### 🔍 Ejecutar Pruebas
```bash
# Ejecutar todas las pruebas
php artisan test

# Ejecutar con cobertura de código
php artisan test --coverage

# Ejecutar pruebas específicas
php artisan test --filter=AuthTest
php artisan test --filter=ClientTest
php artisan test --filter=VehicleTest

# Ejecutar pruebas de funcionalidad
php artisan test tests/Feature/

# Ejecutar pruebas unitarias
php artisan test tests/Unit/
```

### 📊 Cobertura de Código
```bash
# Generar reporte HTML de cobertura
php artisan test --coverage-html coverage-report

# Ver reporte en navegador
open coverage-report/index.html
```

## 🚀 Deployment

### 🏗️ Build para Producción
```bash
# Usar el script de build automatizado
chmod +x build.sh
./build.sh
```

### 🔧 Configuración Manual para Producción
```bash
# Instalar dependencias sin dev
composer install --no-dev --optimize-autoloader

# Compilar assets para producción
npm run build

# Optimizar configuración
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Ejecutar migraciones
php artisan migrate --force

# Limpiar cachés
php artisan optimize:clear
```

### 🐳 Docker (Opcional)
```bash
# Construir imagen Docker
docker build -t specialone-rexville-api .

# Ejecutar contenedor
docker run -p 8000:8000 \
  -e DB_CONNECTION=mysql \
  -e DB_HOST=mysql \
  -e DB_DATABASE=specialone_rexville \
  specialone-rexville-api
```

### ☁️ Deploy en Servidor
```bash
# Configurar supervisor para colas
sudo supervisorctl start laravel-worker:*

# Configurar nginx/apache
# Configurar SSL con Let's Encrypt
# Configurar backups automáticos
```

## 📈 Monitoreo y Logs

### 📊 Logs de Sistema
```bash
# Ver logs en tiempo real
php artisan pail

# Ver logs específicos
php artisan pail --filter=auth
php artisan pail --filter=error

# Logs de aplicación
tail -f storage/logs/laravel.log
```

### 🔍 Debugging
```bash
# Modo debug activado
APP_DEBUG=true
APP_ENV=local

# Logs detallados
LOG_LEVEL=debug
```

## 🔒 Seguridad

### 🛡️ Medidas de Seguridad Implementadas
- **Autenticación con Sanctum**: Tokens seguros y revocables
- **Validación de entrada**: Todas las entradas son validadas
- **Protección CSRF**: Activada para formularios web
- **Rate Limiting**: Límites de requests por IP
- **Encriptación de contraseñas**: Hashing con bcrypt
- **Validación de OAuth**: Verificación de tokens de terceros

### 🔐 Recomendaciones de Seguridad
```bash
# Configurar rate limiting
php artisan route:cache

# Configurar HTTPS en producción
FORCE_HTTPS=true

# Configurar CORS apropiadamente
php artisan config:cache
```

## 🤝 Contribución

### 📝 Guía de Contribución
1. **Fork** el repositorio
2. **Crea** una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. **Commit** tus cambios (`git commit -m 'feat: agregar nueva funcionalidad'`)
4. **Push** a la rama (`git push origin feature/nueva-funcionalidad`)
5. **Abre** un Pull Request

### 📋 Estándares de Código
- **PSR-12**: Estándar de codificación PHP
- **Laravel Pint**: Herramienta de formateo automático
- **PHPStan**: Análisis estático de código
- **Conventional Commits**: Formato de commits

### 🧪 Antes de Contribuir
```bash
# Formatear código
./vendor/bin/pint

# Ejecutar análisis estático
./vendor/bin/phpstan analyse

# Ejecutar todas las pruebas
php artisan test
```

## 📝 Changelog

### 🔄 V1.0.0 (2024-07-14)
- ✅ **Sistema de autenticación completo** con email y OAuth
- ✅ **Gestión de clientes** con perfiles detallados
- ✅ **Gestión de vehículos** con información completa
- ✅ **Servicios automotrices** con historial por vehículo
- ✅ **Sistema de promociones** con fechas de vigencia
- ✅ **Notificaciones push** con estado de lectura
- ✅ **Integración con redes sociales** (Google, Facebook, Apple)
- ✅ **Arquitectura Action-Service** para escalabilidad
- ✅ **ActionResult Pattern** para respuestas consistentes
- ✅ **Stack tecnológico moderno** (Laravel 12.20, Tailwind v4, Vite v6)

## 📄 Licencia

Este proyecto está bajo la licencia **MIT**. Ver el archivo [LICENSE](LICENSE) para más detalles.

## 📞 Soporte

### 🔗 Enlaces Útiles
- **Documentación**: [Wiki del proyecto](https://github.com/Forreal360/SpecialOneRexvilleApi/wiki)
- **Issues**: [GitHub Issues](https://github.com/Forreal360/SpecialOneRexvilleApi/issues)
- **Discussions**: [GitHub Discussions](https://github.com/Forreal360/SpecialOneRexvilleApi/discussions)

### 📧 Contacto
- **Email**: support@specialonerexville.com
- **Website**: https://specialonerexville.com

## 🙏 Créditos

- **Desarrollado por**: [Forreal360](https://github.com/Forreal360)
- **Construido sobre**: [Laravel](https://laravel.com)
- **Autenticación**: [Laravel Sanctum](https://laravel.com/docs/sanctum)
- **OAuth**: [Laravel Socialite](https://laravel.com/docs/socialite)
- **Componentes reactivos**: [Livewire](https://laravel-livewire.com)
- **Estilos**: [Tailwind CSS](https://tailwindcss.com)
- **Build tool**: [Vite](https://vitejs.dev)

---

<p align="center">
🚗 **Hecho con ❤️ para SpecialOne Rexville** 🚗
</p>

<p align="center">
<strong>Transformando la experiencia automotriz, una API a la vez.</strong>
</p>
