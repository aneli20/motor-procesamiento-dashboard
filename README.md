# Motor de Procesamiento y Dashboard de Pedidos

Sistema desarrollado en Laravel para procesar pedidos, clasificarlos por estado logistico y aplicar automaticamente un recargo de envio expres a los pedidos que cumplen reglas especificas de negocio.

## Resumen

- Framework: Laravel 13.
- PHP: 8.4 o superior dentro del contenedor Laravel Sail.
- Base de datos: MySQL 8.4.
- Entorno: Docker Compose con Laravel Sail.
- Autenticacion: OAuth 2.0 con GitHub mediante Laravel Socialite.
- Dashboard protegido con middleware `auth`.
- Motor de recargo ejecutable por comando Artisan y programado con Laravel Scheduler.
- Seeders con 100 clientes, 20 productos y 1,000 pedidos.
- Pruebas automatizadas para dashboard, scopes y comando.

## Requisitos Para Evaluar

El evaluador necesita tener instalado:

- Docker Desktop.
- Git.
- Una cuenta de GitHub para probar el acceso OAuth.

No es necesario tener PHP, Composer, Node ni MySQL instalados localmente si se usa Docker.

## Instalacion Rapida

Clonar el repositorio y entrar a la carpeta:

```powershell
git clone https://github.com/aneli20/motor-procesamiento-dashboard.git
cd motor-procesamiento-dashboard
```

Crear el archivo de entorno:

```powershell
copy .env.example .env
```

Levantar los contenedores:

```powershell
docker compose up -d
```

Instalar dependencias y preparar la aplicacion:

```powershell
docker compose exec laravel.test composer install
docker compose exec laravel.test php artisan key:generate
docker compose exec laravel.test php artisan migrate:fresh --seed
docker compose exec laravel.test npm install
docker compose exec laravel.test npm run build
```

La aplicacion queda disponible en:

```text
http://localhost
```

## Configuracion De Base De Datos

El archivo `.env` debe usar la base de datos del contenedor MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

## Configurar Acceso Con GitHub OAuth

El proyecto no tiene registro manual ni login por usuario y contrasena. El acceso al dashboard se hace exclusivamente con GitHub OAuth.

El evaluador debe crear su propia OAuth App en GitHub:

```text
GitHub > Settings > Developer settings > OAuth Apps > New OAuth App
```

Usar estos valores:

```text
Application name:
Motor de Pedidos

Homepage URL:
http://localhost

Authorization callback URL:
http://localhost/auth/github/callback
```

Despues de crear la OAuth App, copiar el `Client ID`, generar un `Client Secret` y pegarlos en `.env`:

```env
GITHUB_CLIENT_ID=PEGAR_CLIENT_ID_AQUI
GITHUB_CLIENT_SECRET=PEGAR_CLIENT_SECRET_AQUI
GITHUB_REDIRECT_URI=http://localhost/auth/github/callback
```

Si se cambia el puerto de la aplicacion, tambien se debe cambiar el callback en GitHub y en `.env`.

## Como Entrar Al Dashboard

1. Abrir:

```text
http://localhost
```

2. Dar clic en `Continuar con GitHub`.
3. Autorizar la OAuth App en GitHub.
4. GitHub redirige a:

```text
http://localhost/auth/github/callback
```

5. Laravel crea o actualiza el usuario local con `provider = github` y `provider_id`.
6. El usuario queda autenticado y entra a:

```text
http://localhost/dashboard
```

## Verificar Que Todo Funciona

Ejecutar pruebas:

```powershell
docker compose exec laravel.test php artisan test
```

Ver rutas:

```powershell
docker compose exec laravel.test php artisan route:list
```

Ver tareas programadas:

```powershell
docker compose exec laravel.test php artisan schedule:list
```

Probar el motor sin modificar datos:

```powershell
docker compose exec laravel.test php artisan pedidos:aplicar-recargo-express --dry-run
```

Ejecutar el motor aplicando recargos:

```powershell
docker compose exec laravel.test php artisan pedidos:aplicar-recargo-express
```

## Dashboard De Logistica

El dashboard clasifica los pedidos en cuatro categorias:

- `Por Enviar`: pedidos con estado `pendiente` y fecha de entrega entre hoy y los proximos 3 dias.
- `Retrasados`: pedidos con estado `pendiente` y fecha de entrega anterior a hoy.
- `Entregados`: pedidos con estado `entregado`.
- `Cancelados`: pedidos con estado `cancelado`.

Cada tabla muestra:

- Cliente.
- Total.
- Fecha de entrega.
- Productos asociados.

La consulta usa local scopes, paginacion real desde base de datos y eager loading con `with(['cliente', 'productos'])` para evitar N+1.

## Motor De Recargo Expres

El comando:

```bash
php artisan pedidos:aplicar-recargo-express
```

procesa unicamente pedidos que cumplen estas condiciones al mismo tiempo:

- Estado igual a `pendiente`.
- Fecha de entrega exactamente manana.
- El pedido tiene asociado el producto con `id = 5` (`Manejo Especial`).
- `express_charge_applied_at` es `NULL`.

Cuando un pedido cumple las condiciones:

- Suma 10% al campo `total`.
- Redondea a dos decimales.
- Guarda `express_charge_applied_at` para evitar aplicar el recargo dos veces.

El filtro del producto se hace con la relacion de Eloquent mediante SQL, no cargando todos los productos en memoria.

## Scheduler

El comando esta programado para ejecutarse diariamente a medianoche:

```php
Schedule::command('pedidos:aplicar-recargo-express')
    ->dailyAt('00:00')
    ->withoutOverlapping();
```

Para probar el scheduler en desarrollo:

```powershell
docker compose exec laravel.test php artisan schedule:work
```

En produccion se debe configurar cron para ejecutar cada minuto:

```bash
php artisan schedule:run
```

## Datos De Prueba

Los seeders crean automaticamente:

- 100 clientes.
- 20 productos.
- 1,000 pedidos.
- Entre 1 y 5 productos por pedido usando la tabla pivote `pedido_producto`.
- Fechas de entrega pasadas, actuales y futuras.
- Estados variados: `pendiente`, `entregado`, `cancelado`.
- Casos positivos y negativos para validar el motor de recargo.

El producto con `id = 5` es `Manejo Especial`.

## URLs Principales

| Recurso | URL |
| --- | --- |
| Login OAuth | http://localhost |
| Dashboard | http://localhost/dashboard |
| Callback OAuth | http://localhost/auth/github/callback |

## Comandos Utiles

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

## Detener Docker

Detener contenedores:

```powershell
docker compose down
```

Detener contenedores y eliminar el volumen de MySQL:

```powershell
docker compose down -v
```

## Errores Comunes

### Docker no responde

Error posible:

```text
permission denied while trying to connect to docker_engine
```

Solucion:

- Abrir Docker Desktop.
- Verificar que Docker este iniciado.
- Ejecutar la terminal con permisos suficientes.

### Vite manifest not found

Ejecutar:

```powershell
docker compose exec laravel.test npm run build
```

### Error De OAuth

Verificar que estos valores coincidan exactamente:

- Callback configurado en GitHub.
- `GITHUB_REDIRECT_URI` en `.env`.
- URL real donde esta corriendo la aplicacion.

Para este proyecto local deben ser:

```text
http://localhost/auth/github/callback
```

### Error Al Ejecutar Artisan Fuera De Docker

Si se ejecuta `php artisan` directamente en Windows, puede fallar por la version local de PHP.

Recomendacion para evaluacion:

```powershell
docker compose exec laravel.test php artisan test
```

El proyecto esta preparado para ejecutarse dentro del contenedor de Laravel Sail.

## Nota Para El Evaluador

Las credenciales de GitHub OAuth no se incluyen en el repositorio por seguridad. Cada evaluador debe crear su propia OAuth App y colocar sus valores en `.env`.

Una vez configurado OAuth, no se necesita ningun usuario o contrasena de prueba: se entra con la cuenta de GitHub del evaluador.
