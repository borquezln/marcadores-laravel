# AGENTS.md

## Objetivo

Reconstruir este repositorio como una nueva aplicación Laravel moderna para gestionar y visualizar marcadores en un mapa.

El sistema dentro de `/legacy` queda archivado y debe usarse solo como referencia funcional: flujos, campos, reglas de negocio visibles y comportamiento esperado. No hay requisito de compatibilidad hacia atrás.

## Principios

- Laravel convencional por encima de arquitectura personalizada.
- Simplicidad antes que abstracción.
- Blade + Leaflet como interfaz principal.
- Código explícito, legible y fácil de cambiar.
- Evitar sobreingeniería hasta que exista una necesidad real.

## Stack

- Laravel 12.
- Blade.
- Tailwind CSS si el instalador o starter elegido lo incluye.
- Leaflet para mapas.
- MySQL o MariaDB.

## Uso Del Legacy

`/legacy` sirve para entender:

- tipos de marcadores existentes;
- campos usados en formularios y popups;
- roles de usuario;
- permisos esperados;
- estructura de datos original;
- capturas de pantalla y flujos de navegación.

No copiar desde `/legacy`:

- consultas SQL manuales;
- controladores PHP procedural/MVC antiguo;
- manejo de sesiones propio;
- estructura de carpetas;
- validaciones mezcladas con vistas;
- HTML legacy salvo como referencia visual mínima.

## Arquitectura Laravel

Preferir herramientas nativas de Laravel:

- rutas web en `routes/web.php`;
- controladores simples;
- modelos Eloquent;
- migraciones y seeders;
- Form Requests cuando la validación crece;
- Policies o middleware sencillo para autorización;
- Blade components solo cuando reduzcan duplicación real;
- almacenamiento de archivos con Laravel Storage.

Evitar salvo necesidad clara:

- repositories;
- service layers genéricos;
- DTOs;
- CQRS;
- DDD complejo;
- eventos/listeners para lógica directa;
- paquetes externos para problemas pequeños;
- APIs separadas si Blade resuelve el flujo.

## Frontend

- Usar Blade renderizado desde servidor.
- Usar JavaScript mínimo y localizado.
- Usar Leaflet para el mapa y sus marcadores.
- Evitar frameworks SPA.
- Evitar dashboards pesados o interfaces de marketing.
- Priorizar formularios claros, tablas simples, filtros útiles y navegación directa.

## Modelado Inicial

Preferir una tabla principal `markers` con relaciones simples:

- `users`;
- `roles`;
- `markers`;
- `marker_types`;
- `marker_statuses`;
- opcionalmente `marker_contacts` si los contactos de puestos lo justifican.

No replicar automáticamente las tablas legacy `carteles`, `locales` y `puestos` como tablas separadas. Solo separarlas si la nueva implementación demuestra una necesidad concreta.

## Roles

Mantener roles simples:

- `admin`: administra usuarios, marcadores y mapa.
- `editor`: crea y edita marcadores.
- `viewer`: consulta el mapa.

Implementar permisos de forma directa y comprensible. No incorporar un paquete de roles/permisos al inicio.

## Orden De Trabajo

1. Mantener `/legacy` intacto como archivo de referencia.
2. Crear una app Laravel limpia en la raíz del repositorio.
3. Configurar autenticación básica.
4. Crear migraciones, modelos y seeders mínimos.
5. Implementar roles simples.
6. Implementar CRUD de markers.
7. Implementar mapa con Leaflet y filtros básicos.
8. Implementar administración mínima de usuarios.
9. Agregar pruebas para autenticación, roles y CRUD principal.

## Estilo

- Nombres claros y convencionales.
- Métodos cortos.
- Validaciones cerca de la entrada HTTP.
- Consultas Eloquent legibles.
- Comentarios solo cuando aclaren una decisión no obvia.
- Cambios pequeños y verificables.
- Tipado explícito para PHP.
