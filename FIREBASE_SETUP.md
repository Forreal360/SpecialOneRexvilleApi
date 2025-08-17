# Configuración de Firebase para Notificaciones Admin

## Paquetes Requeridos

El sistema de notificaciones requiere el cliente de Google para interactuar con Firebase/Firestore.

### Instalación de Dependencias

```bash
composer install
```

El paquete `google/apiclient` ya está incluido en `composer.json`.

## Configuración de Firebase

### 1. Variables de Entorno

Agregar al archivo `.env`:

```env
# Firebase Configuration
FIREBASE_PROJECT_ID=tu-proyecto-firebase-id
FIREBASE_SERVICE_ACCOUNT=firebase-service-account.json
```

### 2. Archivo de Credenciales

1. Ve a la consola de Firebase (https://console.firebase.google.com/)
2. Selecciona tu proyecto
3. Ve a Configuración del proyecto > Cuentas de servicio
4. Genera una nueva clave privada (formato JSON)
5. Descarga el archivo y nómbralo `firebase-service-account.json`
6. Coloca el archivo en: `storage/app/firebase/firebase-service-account.json`

### 3. Permisos de Firestore

Asegúrate de que la cuenta de servicio tenga permisos para:
- Leer y escribir en Firestore
- Enviar mensajes FCM (si usas push notifications)

## Estructura de Directorios

```
storage/
  app/
    firebase/
      firebase-service-account.json
```

## Funcionamiento

Cuando se crea una cita o ticket:

1. Se ejecuta el `AdminNotificationJob`
2. Se crea la notificación en la base de datos local
3. Se actualiza Firestore con el trigger `admin-notification-trigger/{admin_id}`
4. El Dashboard detecta el cambio en Firestore y actualiza la UI

## Colecciones de Firestore

- `admin-notification-trigger`: Documentos con ID = admin_id
  - `notification_id`: ID de la notificación creada
  - `updated_at`: Timestamp del trigger

## Troubleshooting

### Error: "No se pudo obtener el token de acceso de Firebase"
- Verifica que el archivo de credenciales existe en la ruta correcta
- Verifica que las variables de entorno están configuradas

### Error: "Permission denied"
- Verifica que la cuenta de servicio tiene permisos de Firestore
- Verifica que el proyecto Firebase está configurado correctamente

### No se actualizan las notificaciones en el Dashboard
- Verifica que las queues están funcionando: `php artisan queue:work`
- Verifica los logs: `php artisan tail` o revisar `storage/logs/laravel.log`