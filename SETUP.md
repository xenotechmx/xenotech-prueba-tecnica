# Gestión de Pedidos
Desarrollo de una API Rest usando Laravel 12, PHP 8.4 y MySQL.

## Instalación y ejecución

- **Clonar el repositorio** 
```bash
git clone https://github.com/xenotechmx/xenotech-prueba-tecnica.git
cd tu-repositorio
```

- **Instalar dependencias PHP**
```bash
composer install
```

- **Copiar env y generar app key**
```bash
cp .env.example .env
php artisan key:generate
```

- **Crear la base de datos en MySQL**
```sql
// Ejecutar en MySQL:

CREATE DATABASE your_database CHARACTER SET utf8mb4;
```

- **Ejecutar migraciones y seeders**
```bash
php artisan migrate --seed
```

- **Ejecutar para tests**
```bash
php artisan migrate --env=testing
php artisan test
```

- **Ejecutar servidor local**
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

## Variables obligatorias
```bash
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

WEBHOOK_URL=https://webhook.site/{your_id}
```

## Ejemplos de los Endpoints

### - Listar ordenes `GET api/orders`
```bash
GET /api/orders HTTP/1.1
Host: 127.0.0.1:8000
Accept: application/json
```

### - Listar orden `GET api/orders/{order}`
```bash
GET /api/orders/1 HTTP/1.1
Host: 127.0.0.1:8000
Accept: application/json
```

### - Crear orden `POST api/orders`
```bash
POST /api/orders HTTP/1.1
Host: 127.0.0.1:8000
Content-Type: application/json
Accept: application/json

{
  "customer_name": "Josue Aguilar",
  "customer_type": "VIP", 
  "items": [
    { "product_name": "Producto 1", "quantity": 1, "price": 25.00 },
    { "product_name": "Producto 2", "quantity": 3, "price": 1.00 }
  ]
}
```

### - Actualizar orden `PUT api/orders/{order}`
```bash
PUT /api/orders/1 HTTP/1.1
Host: 127.0.0.1:8000
Content-Type: application/json
Accept: application/json

{
  "status": "PROCESSING",
  "items": [
    { "product_name": "Nuevo Producto", "quantity": 2, "price": 30 }
  ]
}
```

## Decisiones importantes

- **customer_type:** Se agrego esta columna a la tabla Order para distinguir entre clientes y saber que medio de notificación usar basado en el Strategy Pattern requerido.
- **Factorys:** Integración de factorys para acompañar a los seeders en el llenado inicial en BD.
