# AGENTS.md

## Objetivo

Aplicación Laravel para administrar usuarios y marcadores georreferenciados sobre un mapa interactivo.

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

## Modelado

El dominio se limita deliberadamente a `users` y `markers`.

No agregar nuevas tablas, catálogos o relaciones salvo que resuelvan un problema funcional concreto ya validado.

### Ownership

- admin puede visualizar y administrar todos los markers;
- editor puede visualizar todos los markers y administrar únicamente los propios;
- viewer puede visualizar markers en el mapa, pero no administrarlos.

### Autorización:
- middleware para acceso general;
- Policies para recursos de dominio;
- MarkerPolicy es la fuente de verdad para autorización de markers.

## Roles

Mantener roles simples implementados como strings en users.

## Estado De Trabajo

Versión actual: v1.2.0

### Funcionalidades del MVP:
- autenticación;
- roles y estados de usuario;
- administración de usuarios;
- CRUD de markers;
- mapa interactivo con Leaflet;
- autorización mediante Policies;
- tests automatizados.

### Historial De Versiones

v1.0.0
- MVP funcional completo.

v1.1.0
- filtros de markers;
- ordenamientos;
- colores por tipo en mapa.

v1.2.0
- selector de tema (light, dark, system);
- persistencia de preferencia;
- cambio dinámico de branding;

### Mejoras futuras:
- adaptación visual completa para dark mode;
- revisión de contrastes;
- tablas y formularios en dark mode;
- dashboard consistente entre temas;
- revisión visual de componentes compartidos;
- geolocalización;
- filtros en mapa;
- API pública;
- autenticación por tokens;
- documentación de API;
- despliegue.

## Estilo

- Tipado explícito para PHP.
- Nombres claros y convencionales.
- Métodos cortos.
- Validaciones cerca de la entrada HTTP.
- Consultas Eloquent legibles.
- No agregar comentarios al código salvo pedido explícito.
- No agregar TODO, FIXME o comentarios explicativos.
- Cambios pequeños y verificables.
- Presentar plan antes de cambios grandes.
- No asumir implementaciones existentes.
- No afirmar que una funcionalidad está implementada sin verificar los archivos modificados.
