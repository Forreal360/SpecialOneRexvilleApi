# API de Citas (Appointments)

Esta documentación describe los endpoints disponibles para gestionar las citas de los clientes.

## Autenticación

Todos los endpoints requieren autenticación mediante token Bearer. Incluye el header:
```
Authorization: Bearer {token}
```

## Endpoints

### 1. Obtener Timezones Disponibles

**GET** `/api/v1/timezones`

Obtiene la lista de timezones disponibles para agendar citas.

#### Respuesta exitosa (200):

```json
{
    "success": true,
    "message": "Timezones obtenidos exitosamente.",
    "data": {
        "America/Mexico_City": "Ciudad de México (UTC-6)",
        "America/Tijuana": "Tijuana (UTC-8)",
        "America/Monterrey": "Monterrey (UTC-6)",
        "America/Guadalajara": "Guadalajara (UTC-6)",
        "UTC": "UTC (UTC+0)"
    }
}
```

### 2. Crear Cita

**POST** `/api/v1/appointments`

Crea una nueva cita para el cliente autenticado.

#### Parámetros del Body (JSON):

```json
{
    "vehicle_id": 1,
    "service_id": 2,
    "appointment_datetime": "2025-07-26 14:30:00",
    "timezone": "America/Mexico_City",
    "notes": "Cambio de aceite y revisión general"
}
```

#### Parámetros requeridos:
- `vehicle_id` (integer): ID del vehículo del cliente
- `service_id` (integer): ID del servicio a realizar
- `appointment_datetime` (datetime): Fecha y hora de la cita (formato: YYYY-MM-DD HH:MM:SS)
- `timezone` (string): Huso horario de la cita (ej: "America/Mexico_City", "UTC", "Europe/Madrid")

#### Parámetros opcionales:
- `notes` (string): Notas adicionales sobre la cita (máximo 500 caracteres)

#### Respuesta exitosa (201):

```json
{
    "success": true,
    "message": "Cita agendada exitosamente.",
    "data": {
        "id": 1,
        "client_id": 1,
        "vehicle_id": 1,
        "service_id": 2,
        "appointment_datetime": "2025-07-26 14:30:00",
        "timezone": "America/Mexico_City",
        "status": "pending",
        "notes": "Cambio de aceite y revisión general",
        "created_at": "2025-07-24 23:30:00",
        "updated_at": "2025-07-24 23:30:00",
        "vehicle": {
            "id": 1,
            "make": "Hyundai",
            "model": "Tucson",
            "year": 2022,
            "color": "Blanco",
            "license_plate": "ABC123"
        },
        "service": {
            "id": 2,
            "name": "Cambio de Aceite",
            "description": "Cambio de aceite y filtros",
            "price": 45.00
        }
    }
}
```

#### Respuesta de error (400):

```json
{
    "success": false,
    "message": "Validation error.",
                "errors": {
                "vehicle_id": ["El ID del vehículo es requerido."],
                "appointment_datetime": ["La fecha y hora de la cita debe ser futura."]
            }
}
```

### 3. Listar Citas

**GET** `/api/v1/appointments`

Obtiene todas las citas del cliente autenticado.

#### Respuesta exitosa (200):

```json
{
    "success": true,
    "message": "Citas obtenidas exitosamente.",
    "data": [
        {
            "id": 1,
            "client_id": 1,
            "vehicle_id": 1,
            "service_id": 2,
            "appointment_datetime": "2025-07-26 14:30:00",
            "timezone": "America/Mexico_City",
            "status": "pending",
            "notes": "Cambio de aceite y revisión general",
            "created_at": "2025-07-24 23:30:00",
            "updated_at": "2025-07-24 23:30:00",
            "vehicle": {
                "id": 1,
                "make": "Hyundai",
                "model": "Tucson",
                "year": 2022,
                "color": "Blanco",
                "license_plate": "ABC123"
            },
            "service": {
                "id": 2,
                "name": "Cambio de Aceite",
                "description": "Cambio de aceite y filtros",
                "price": 45.00
            }
        }
    ]
}
```

## Estados de Cita

Las citas pueden tener los siguientes estados:

- `pending`: Pendiente de confirmación
- `confirmed`: Confirmada
- `cancelled`: Cancelada
- `completed`: Completada

## Validaciones

### Crear Cita:
- El vehículo debe pertenecer al cliente autenticado
- El servicio debe existir en el sistema
- La fecha y hora debe ser futura
- El formato de fecha y hora debe ser YYYY-MM-DD HH:MM:SS
- El timezone debe ser válido
- Las notas no pueden exceder 500 caracteres

### Listar Citas:
- Solo muestra las citas del cliente autenticado
- Las citas se ordenan por fecha y hora (más recientes primero)
- Se incluye el timezone original de la cita

## Códigos de Error

- `400`: Error de validación
- `401`: No autenticado
- `403`: No autorizado
- `404`: Recurso no encontrado
- `500`: Error interno del servidor 
