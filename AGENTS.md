# AGENTS.md

## Objetivo

Reconstruir el repositorio legacy como una nueva aplicación Laravel moderna para gestionar y visualizar marcadores en un mapa.

## Principios

- Laravel convencional por encima de arquitectura personalizada.
- Simplicidad antes que abstracción.
- Blade + Leaflet como interfaz principal.
- Código explícito, legible y fácil de cambiar.
- Evitar sobreingeniería hasta que exista una necesidad real.

## Stack

- Laravel 13.
- Blade.
- Tailwind CSS si el instalador o starter elegido lo incluye.
- Leaflet para mapas.
- MySQL.

## Uso Del Legacy

El sistema dentro de `/legacy` queda archivado y debe usarse solo como referencia funcional. No hay requisito de compatibilidad hacia atrás.

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
- relaciones Eloquent simples y convencionales;
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
- Leaflet debe integrarse mediante JavaScript localizado y simple.
- Evitar capas frontend complejas salvo necesidad real.
- Evitar frameworks SPA.
- Evitar dashboards pesados o interfaces de marketing.
- Priorizar formularios claros, tablas simples, filtros útiles y navegación directa.

## Modelado Inicial

El dominio inicial se limita deliberadamente a `users` y `markers`.

No agregar nuevas tablas, catálogos o relaciones salvo que resuelvan un problema funcional concreto ya validado.

## Roles

Mantener roles simples implementados como strings en users.

## Orden De Trabajo

1. Mantener `/legacy` intacto como archivo de referencia.
2. Crear una app Laravel limpia en la raíz del repositorio.
3. Crear migraciones, modelos y seeders mínimos.
4. Configurar autenticación básica.
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
