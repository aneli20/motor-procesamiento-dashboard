# AI_JOURNEY

## 1. Contexto del uso de IA

Utilicé la IA como  una herramienta de apoyo durante el desarrollo del proyecto. Principalmente la empleé para resolver dudas específicas sobre Laravel, generar ejemplos de seeders, optimizar consultas con Eloquent y revisar la documentación. La implementación, integración de los componentes, configuración del entorno y validación del funcionamiento fueron realizadas y verificadas manualmente.

## 2. Prompts principales

Durante el desarrollo utilicé la IA para realizar consultas como:

- Generar ejemplos de seeders para poblar la base de datos.
- Resolver dudas sobre relaciones muchos a muchos en Eloquent.
- Optimizar consultas utilizando `with()` y `whereHas()`.
- Revisar y mejorar la estructura del README.
- Resolver dudas relacionadas con Docker, Laravel Sail y la autenticación OAuth con GitHub.
- Obtener ejemplos de pruebas y buenas prácticas en Laravel.

La IA fue utilizada únicamente como apoyo para acelerar algunas tareas, mientras que las decisiones finales fueron tomadas durante el desarrollo del proyecto.

## 3. Decisiones técnicas tomadas

Durante la implementación se tomaron las siguientes decisiones:

- Actualizar el proyecto a Laravel 13 para cumplir con el requisito de utilizar la versión estable más reciente.
- Utilizar Docker con Laravel Sail y MySQL como entorno de desarrollo.
- Implementar el dashboard utilizando Blade y Tailwind CSS.
- Utilizar Local Scopes para encapsular y reutilizar la lógica de filtrado de pedidos.
- Implementar Eager Loading con `with()` para evitar el problema N+1.
- Desarrollar un comando Artisan para aplicar automáticamente el recargo por envío exprés.
- Programar la ejecución automática del comando mediante Laravel Scheduler.

## 4. Correcciones y revisión como Tech Lead

Después de utilizar las sugerencias proporcionadas por la IA, revisé manualmente el proyecto para asegurar que cumpliera con todos los requisitos del reto.

Durante esta revisión:

- Verifiqué que las consultas utilizaran correctamente Eloquent.
- Revisé las relaciones entre clientes, pedidos y productos.
- Validé el funcionamiento de las migraciones y los seeders.
- Revisé la configuración de Docker y la conexión con MySQL.
- Corregí la documentación del README para que reflejara la implementación final.
- Comprobé el funcionamiento del dashboard y del comando Artisan antes de la entrega.

## 5. Trabajo realizado manualmente

Además del apoyo recibido por la IA, realicé manualmente las siguientes actividades:

- Configuración del proyecto en Docker.
- Configuración de Laravel Sail.
- Configuración de la base de datos MySQL.
- Configuración de GitHub OAuth.
- Revisión y ejecución de las migraciones.
- Verificación del funcionamiento del dashboard.
- Revisión y validación del comando Artisan.
- Corrección de la documentación.
- Validación integral del proyecto antes de la entrega.

## 6. Limitaciones del uso de IA

La IA fue utilizada únicamente como una herramienta de apoyo para agilizar algunas tareas y resolver dudas durante el desarrollo. No se utilizó para generar automáticamente todo el proyecto.

Cada sugerencia fue revisada, adaptada y validada manualmente antes de incorporarla al código final.

## 7. Código sugerido por la IA que fue ajustado o descartado

Durante el desarrollo, algunas sugerencias generadas por la IA no se incorporaron directamente al proyecto porque no cumplían completamente con los requisitos o podían afectar el funcionamiento esperado.

Entre los casos más relevantes se encuentran:

- Algunas consultas propuestas cargaban relaciones en memoria para verificar si un pedido contenía un producto específico. Finalmente se implementó `whereHas()` para que el filtro se resolviera directamente en SQL mediante la relación muchos a muchos, evitando cargar datos innecesarios en memoria.

- En algunas respuestas se propusieron implementaciones que agregaban validaciones adicionales a la lógica del recargo. Después de revisar los requisitos del reto, se decidió mantener la implementación alineada con las condiciones solicitadas y descartar aquellas modificaciones que alteraban el comportamiento esperado.

- Algunas versiones iniciales del README y de la documentación requerían ajustes para reflejar con precisión la implementación final del proyecto, por lo que fueron corregidas manualmente.

Todas las sugerencias fueron revisadas y, cuando fue necesario, adaptadas o descartadas antes de integrarlas al código definitivo.

## 8. Reflexión personal

La IA me ayudó principalmente a resolver dudas y agilizar tareas repetitivas, como la generación de ejemplos de seeders, la revisión de consultas y la organización de la documentación. Sin embargo, fue necesario revisar manualmente el código, adaptar varias sugerencias a los requisitos del proyecto y comprobar que todo funcionara correctamente antes de la entrega.

Esta experiencia me permitió aprovechar la IA como una herramienta de apoyo, manteniendo siempre el criterio técnico sobre las decisiones de implementación y asegurando que la solución final cumpliera con los requisitos del reto.