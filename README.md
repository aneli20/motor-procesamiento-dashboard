# Motor de Procesamiento y Dashboard de Pedidos

Sistema desarrollado en Laravel para procesar pedidos, clasificarlos por estado logístico y aplicar automáticamente un recargo de envío exprés a los pedidos que cumplen determinadas reglas de negocio.

## Características

- Autenticación exclusiva mediante OAuth 2.0 utilizando GitHub y Laravel Socialite.
- Dashboard protegido con cuatro categorías: **Por Enviar**, **Retrasados**, **Entregados** y **Cancelados**.
- Paginación real desde base de datos.
- Uso de Eloquent con relaciones entre `Cliente`, `Pedido` y `Producto`.
- Eager Loading para evitar el problema N+1 al cargar clientes y productos.
- Producto fijo `id = 5` (**Manejo Especial**) utilizado por el motor de recargo.
- Comando Artisan `pedidos:aplicar-recargo-express` con soporte para `--dry-run`.
- Proceso programado diariamente mediante Scheduler.
- Seeders con datos de prueba para clientes, productos y pedidos.
- Pruebas automatizadas para dashboard, scopes y comando.

---

# Tecnologías

- Laravel 13
- PHP 8.2+
- MySQL 8.4
- Docker con Laravel Sail
- Blade
- Tailwind CSS 4
- Vite
- PHPUnit

---

# Instalación en Windows (PowerShell)

Antes de comenzar asegúrate de tener instalado y ejecutándose Docker Desktop.

```powershell
copy .env.example .env
docker compose up -d
docker compose exec laravel.test composer install
docker compose exec laravel.test php artisan key:generate
docker compose exec laravel.test php artisan migrate:fresh --seed
docker compose exec laravel.test npm install
docker compose exec laravel.test npm run build
```

La aplicación quedará disponible en:

```
http://localhost
```

## Configuración de Base de Datos

El archivo `.env` debe utilizar la configuración de MySQL del contenedor Docker:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

---

# Configurar GitHub OAuth

1. En GitHub entra a:

```
Settings
Developer settings
OAuth Apps
New OAuth App
```

2. Configura los siguientes datos:

- **Application name**

```
Motor de Pedidos
```

- **Homepage URL**

```
http://localhost
```

- **Authorization callback URL**

```
http://localhost/auth/github/callback
```

3. Copia el **Client ID** y genera un **Client Secret**.

4. Agrega las credenciales al archivo `.env`:

```env
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
GITHUB_REDIRECT_URI=http://localhost/auth/github/callback
```

No existe registro manual ni autenticación mediante usuario y contraseña. Todo el acceso al sistema se realiza utilizando GitHub OAuth.

---

# Comandos principales

```powershell
docker compose exec laravel.test php artisan migrate:fresh --seed
docker compose exec laravel.test php artisan test
docker compose exec laravel.test php artisan route:list
docker compose exec laravel.test php artisan schedule:list
docker compose exec laravel.test php artisan pedidos:aplicar-recargo-express --dry-run
docker compose exec laravel.test php artisan pedidos:aplicar-recargo-express
docker compose exec laravel.test php artisan optimize:clear
docker compose exec laravel.test ./vendor/bin/pint
```

Para ejecutar el Scheduler durante el desarrollo:

```powershell
docker compose exec laravel.test php artisan schedule:work
```

En producción debe configurarse un cron (o el mecanismo equivalente del servidor) para ejecutar:

```bash
php artisan schedule:run
```

cada minuto.

---

# Categorías del Dashboard

El panel clasifica automáticamente los pedidos en cuatro categorías:

### Por Enviar

Pedidos con estado `pendiente` cuya fecha de entrega está comprendida entre hoy y los próximos tres días (incluyendo el día actual).

### Retrasados

Pedidos con estado `pendiente` cuya fecha de entrega es anterior al día de hoy.

### Entregados

Pedidos con estado:

```
entregado
```

### Cancelados

Pedidos con estado:

```
cancelado
```

Cada tabla muestra:

- Cliente
- Total
- Fecha de entrega
- Productos asociados

---

# Motor de Recargo Exprés

El comando:

```bash
php artisan pedidos:aplicar-recargo-express
```

procesa únicamente los pedidos que cumplen todas las siguientes condiciones:

- Estado igual a `pendiente`.
- Fecha de entrega exactamente el día de mañana.
- El pedido contiene el producto con `id = 5` (**Manejo Especial**).
- El campo `express_charge_applied_at` es `NULL`.

Cuando un pedido cumple dichas condiciones:

- Incrementa el total en un **10%**.
- Redondea el resultado a dos decimales.
- Guarda la fecha de aplicación en `express_charge_applied_at` para evitar aplicar nuevamente el recargo.

El parámetro:

```bash
--dry-run
```

permite visualizar los pedidos elegibles sin modificar la base de datos.

---

# Datos de prueba

Los Seeders generan automáticamente:

- 100 clientes.
- 20 productos.
- 1,000 pedidos.
- Entre 1 y 5 productos por pedido.
- Fechas de entrega pasadas, actuales y futuras.
- Casos positivos y negativos para validar el motor de recargo.

---

# Pruebas

Ejecutar todas las pruebas:

```powershell
docker compose exec laravel.test php artisan test
```

Si se ejecuta fuera de Docker en Windows y aparece un error relacionado con Symfony Process, puede utilizarse:

```powershell
vendor\bin\phpunit.bat --do-not-cache-result
```

---

# URLs principales

| Recurso | URL |
|---------|-----|
| Login OAuth | http://localhost |
| Dashboard | http://localhost/dashboard |
| Callback OAuth | http://localhost/auth/github/callback |

---

# Docker

Detener los contenedores:

```powershell
docker compose down
```

Detener los contenedores y eliminar el volumen de MySQL:

```powershell
docker compose down -v
```

---

# Errores comunes

### Error

```
permission denied while trying to connect to docker_engine
```

**Solución**

- Abrir Docker Desktop.
- Verificar que Docker esté iniciado.
- Ejecutar la terminal con permisos suficientes.

---

### Error

```
Vite manifest not found
```

**Solución**

```powershell
docker compose exec laravel.test npm run build
```

---

### Error de OAuth

Verificar que el valor de:

```env
GITHUB_REDIRECT_URI
```

coincida exactamente con la URL configurada en GitHub.

---

### Error con `schedule:list`

Si el comando intenta conectarse a MySQL y falla:

- Verificar que los contenedores estén ejecutándose.
- Ejecutar el comando dentro del contenedor Docker.

---

# Publicar en GitHub

```powershell
git init
git add .
git commit -m "Implement motor de pedidos"
git branch -M main
git remote add origin https://github.com/TU_USUARIO/TU_REPO.git
git push -u origin main
```

Si el repositorio es privado, invita al evaluador desde:

```
Settings
Collaborators and teams
```
