# AI_JOURNEY

## 1. Contexto del uso de IA

Utilicé la IA como herramienta de apoyo durante el desarrollo del proyecto. Principalmente la empleé para resolver dudas específicas sobre Laravel, generar algunos seeders, mejorar consultas de Eloquent y revisar la documentación. La implementación, integración de los componentes, configuración del entorno y validación del funcionamiento fueron realizadas y verificadas manualmente.

## 2. Prompts principales

Durante el desarrollo utilicé la IA para realizar consultas como:

- Generar ejemplos de seeders para poblar la base de datos.
- Resolver dudas sobre relaciones muchos a muchos en Eloquent.
- Optimizar consultas utilizando `with()` y `whereHas()`.
- Revisar la estructura del README.
- Resolver dudas relacionadas con Docker, Laravel Sail y OAuth con GitHub.
- Obtener ejemplos de pruebas y buenas prácticas de Laravel.

La IA fue utilizada únicamente como apoyo para acelerar algunas tareas, mientras que las decisiones finales fueron tomadas durante el desarrollo del proyecto.

## 3. Decisiones técnicas tomadas

Durante la implementación se tomaron las siguientes decisiones:

- Se actualizo el proyecto a Laravel 13 para cumplir con el requisito de usar la version estable mas reciente disponible.
- Se utilizó Docker con Laravel Sail y MySQL como base de datos.
- El dashboard fue implementado utilizando Blade y Tailwind CSS.
- Se utilizaron Local Scopes para clasificar los pedidos por estado.
- Se implementó Eager Loading para evitar el problema N+1.
- Se desarrolló un comando Artisan para aplicar automáticamente el recargo por envío exprés.
- El comando fue programado mediante el Scheduler para ejecutarse diariamente.

## 4. Correcciones y revisión como Tech Lead

Después de utilizar las sugerencias proporcionadas por la IA, revisé manualmente el proyecto para asegurar que cumpliera con los requisitos del reto.

Durante esta revisión:

- Verifiqué que las consultas utilizaran Eloquent correctamente.
- Revisé las relaciones entre clientes, pedidos y productos.
- Validé el funcionamiento de las migraciones y los seeders.
- Revisé la configuración de Docker y la conexión con MySQL.
- Corregí la documentación del README para que coincidiera con la implementación real.
- Comprobé el funcionamiento general del dashboard y del comando Artisan antes de la entrega.

## 5. Trabajo realizado manualmente

Además del apoyo recibido por la IA, realicé manualmente las siguientes actividades:

- Configuración del proyecto en Docker.
- Configuración de Laravel Sail.
- Configuración de la base de datos MySQL.
- Configuración de GitHub OAuth.
- Revisión de migraciones.
- Ejecución de migraciones.
- Verificación del funcionamiento del dashboard.
- Revisión del comando Artisan.
- Corrección de la documentación.
- Validación general del proyecto antes de la entrega.

## 6. Limitaciones del uso de IA

La IA fue utilizada únicamente como una herramienta de apoyo para agilizar algunas tareas y resolver dudas durante el desarrollo. No se utilizó para generar automáticamente todo el proyecto.

Cada sugerencia fue revisada, adaptada y validada manualmente antes de incorporarla al código final.

## 7. Pruebas ejecutadas

Durante el desarrollo realicé las siguientes pruebas:

- Levanté correctamente los contenedores utilizando:

```bash
docker compose up -d
```

- Verifiqué que los contenedores de Laravel y MySQL estuvieran ejecutándose con:

```bash
docker ps
```

- Ejecuté las migraciones dentro del contenedor mediante:

```bash
docker compose exec laravel.test php artisan migrate
```

- Confirmé que las migraciones se ejecutaran correctamente y que las tablas fueran creadas sin errores.

- Detuve y volví a levantar los contenedores para comprobar que el entorno funcionara correctamente utilizando:

```bash
docker compose down
docker compose up -d
```

- Revisé manualmente la configuración de Docker, Laravel Sail y MySQL para asegurar la correcta comunicación entre los servicios.

## 8. Reflexión personal

La IA me ayudó principalmente a resolver dudas y acelerar algunas tareas repetitivas, como la generación de ejemplos de seeders y la revisión de consultas. Sin embargo, fue necesario revisar manualmente el código, adaptar varias sugerencias a los requisitos del proyecto y comprobar que todo funcionara correctamente antes de la entrega.

Esta experiencia me permitió aprovechar la IA como una herramienta de apoyo sin dejar de comprender el funcionamiento del proyecto ni perder el control sobre la implementación final.
