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

**SpecialOneRexvilleApi** es una API REST completa para la gestión de servicios automotrices desarrollada con Laravel 12. La aplicación permite a los clientes gestionar sus vehículos, acceder a servicios automotrices, recibir promociones y mantener un historial completo de mantenimiento.

### 🎯 Funcionalidades Principales

- **🔐 Autenticación Completa**: Login con email y redes sociales (OAuth)
- **👤 Gestión de Clientes**: Perfiles de usuario con información personal y contacto
- **🚙 Gestión de Vehículos**: Registro de vehículos con información detallada (año, modelo, VIN, etc.)
- **🔧 Servicios Automotrices**: Historial de servicios por vehículo con fechas y detalles
- **🏷️ Promociones**: Sistema de promociones con fechas de vigencia
- **🔔 Notificaciones**: Sistema de notificaciones push para clientes
- **🌐 Cuentas Sociales**: Conectar/desconectar cuentas de redes sociales

### 🏗️ Arquitectura del Sistema

- **Patrón Action-Service**: Separación clara entre lógica de negocio y operaciones de dominio
- **Manejo Centralizado de Excepciones**: Template Method Pattern para manejo consistente de errores
- **Versionado de API**: Estructura V1 para escalabilidad futura
- **ActionResult Pattern**: Respuestas consistentes en toda la aplicación

## 📡 Endpoints de la API

### Autenticación
```
POST /api/v1/login-with-email          # Login con email
POST /api/v1/login-with-social         # Login con redes sociales
GET  /api/v1/logout                    # Cerrar sesión
POST /api/v1/refresh-token             # Renovar token
POST /api/v1/refresh-fcm-token         # Actualizar token FCM
```

### Gestión de Perfil
```
GET  /api/v1/profile                   # Obtener perfil del cliente
PUT  /api/v1/profile                   # Actualizar perfil del cliente
```

### Cuentas Sociales
```
POST   /api/v1/connect-social-account    # Conectar cuenta social
DELETE /api/v1/disconnect-social-account # Desconectar cuenta social
GET    /api/v1/social-accounts           # Obtener cuentas conectadas
```

### Vehículos
```
GET  /api/v1/vehicles                  # Obtener vehículos del cliente
```

### Servicios
```
GET  /api/v1/services                           # Obtener todos los servicios
GET  /api/v1/vehicles/{vehicle_id}/services     # Obtener servicios por vehículo
```

### Promociones
```
GET  /api/v1/promotions                # Obtener promociones activas
```

### Notificaciones
```
GET    /api/v1/notifications                        # Obtener notificaciones
GET    /api/v1/notifications/{id}/mark-as-read      # Marcar como leída
PUT    /api/v1/notifications/mark-all-as-read       # Marcar todas como leídas
DELETE /api/v1/notifications/{id}                   # Eliminar notificación
```

## 🛠️ Instalación

### Requisitos
- PHP 8.2 o superior
- Composer
- Node.js y npm
- MySQL/PostgreSQL
- Redis (opcional, para caché)

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/Forreal360/SpecialOneRexvilleApi.git
cd SpecialOneRexvilleApi
```

2. **Instalar dependencias**
```bash
composer install
npm install
```

3. **Configurar variables de entorno**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurar base de datos**
```bash
# Editar .env con tu configuración de base de datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=specialone_rexville
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

5. **Configurar autenticación social (opcional)**
```bash
# Configurar en .env las credenciales de OAuth
GOOGLE_CLIENT_ID=tu_google_client_id
GOOGLE_CLIENT_SECRET=tu_google_client_secret
FACEBOOK_CLIENT_ID=tu_facebook_client_id
FACEBOOK_CLIENT_SECRET=tu_facebook_client_secret
```

6. **Ejecutar migraciones**
```bash
php artisan migrate
php artisan db:seed
```

7. **Compilar assets**
```bash
npm run dev
```

8. **Iniciar servidor**
```bash
php artisan serve
```

## 🔧 Configuración Adicional

### Almacenamiento de Archivos
```bash
# Crear enlace simbólico para almacenamiento público
php artisan storage:link
```

### Configuración de Firebase (Notificaciones Push)
```bash
# Agregar a .env
FIREBASE_SERVER_KEY=tu_firebase_server_key
```

## 📊 Modelos de Datos

### Client (Cliente)
```php
- id
- name
- email
- password
- phone_code
- phone
- license_number
- profile_photo
- fcm_token
- created_at
- updated_at
```

### Vehicle (Vehículo)
```php
- id
- client_id
- year
- model
- vin
- buy_date
- insurance
- created_at
- updated_at
```

### Service (Servicio)
```php
- id
- client_id
- vehicle_id
- date
- name
- created_at
- updated_at
```

### Promotion (Promoción)
```php
- id
- title
- start_date
- end_date
- image_url
- redirect_url
- status
- created_at
- updated_at
```

## 🎨 Uso de la API

### Ejemplo de Autenticación
```bash
# Login con email
curl -X POST https://your-api.com/api/v1/login-with-email \
  -H "Content-Type: application/json" \
  -d '{
    "email": "cliente@example.com",
    "password": "password123"
  }'
```

### Ejemplo de Respuesta
```json
{
  "success": true,
  "data": {
    "client": {
      "id": 1,
      "name": "Juan Pérez",
      "email": "juan@example.com"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
  },
  "message": "Login exitoso",
  "errors": []
}
```

### Ejemplo de Obtener Vehículos
```bash
curl -X GET https://your-api.com/api/v1/vehicles \
  -H "Authorization: Bearer tu_token_aqui"
```

## 🏛️ Estructura del Proyecto

```
app/
├── Actions/V1/                    # Lógica de negocio
│   ├── Auth/                      # Autenticación
│   ├── Client/                    # Gestión de clientes
│   ├── Vehicle/                   # Gestión de vehículos
│   ├── Service/                   # Servicios automotrices
│   ├── Promotion/                 # Promociones
│   └── Notification/              # Notificaciones
├── Services/V1/                   # Servicios de dominio
│   ├── ClientService.php          # Operaciones de cliente
│   ├── VehicleService.php         # Operaciones de vehículo
│   ├── ServiceService.php         # Operaciones de servicio
│   └── PromotionService.php       # Operaciones de promoción
├── Models/                        # Modelos Eloquent
│   ├── Client.php
│   ├── Vehicle.php
│   ├── Service.php
│   └── Promotion.php
├── Http/Controllers/V1/Api/       # Controladores API
└── Support/                       # Clases de soporte
    └── ActionResult.php           # Respuestas consistentes
```

## 🧪 Testing

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests con cobertura
php artisan test --coverage

# Ejecutar tests específicos
php artisan test --filter=AuthTest
```

## 📱 Características Técnicas

- **Laravel 12**: Framework PHP moderno
- **Laravel Sanctum**: Autenticación API segura
- **Laravel Socialite**: Integración con OAuth
- **Livewire 3.6**: Componentes reactivos
- **Action-Service Pattern**: Arquitectura limpia
- **Versionado de API**: Preparado para evolución
- **Manejo Centralizado de Excepciones**: Consistencia en respuestas

## 🚀 Deployment

### Producción
```bash
# Optimizar para producción
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

### Docker (Opcional)
```bash
# Construir imagen
docker build -t specialone-rexville-api .

# Ejecutar contenedor
docker run -p 8000:8000 specialone-rexville-api
```

## 🤝 Contribuir

Las contribuciones son bienvenidas. Para contribuir:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -m 'feat: agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

## 📝 Changelog

### V1.0.0
- ✅ Sistema de autenticación completo
- ✅ Gestión de clientes y vehículos
- ✅ Servicios automotrices
- ✅ Sistema de promociones
- ✅ Notificaciones push
- ✅ Integración con redes sociales

## 📄 Licencia

Este proyecto está bajo la licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

## 🙏 Créditos

- Desarrollado por el equipo de **Forreal360**
- Construido sobre [Laravel](https://laravel.com)
- Usa [Laravel Sanctum](https://laravel.com/docs/sanctum) para autenticación
- Integra [Laravel Socialite](https://laravel.com/docs/socialite) para OAuth

---

<p align="center">
🚗 Hecho con ❤️ para SpecialOne Rexville
</p>
