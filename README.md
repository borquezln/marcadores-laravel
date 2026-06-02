# Marcadores - Laravel

## Origen Del Proyecto

Marcadores surge como una reimplementación moderna de un sistema previo desarrollado en PHP [`Marcadores`](https://github.com/borquezln/marcadores). 

Marcadores surge como una reimplementación moderna de un sistema previo desarrollado en PHP.

---

## Objetivo De Esta Versión

Marcadores es una aplicación Laravel para administrar usuarios y marcadores georreferenciados sobre un mapa interactivo.

Permite:
- gestionar usuarios;
- administrar marcadores;
- visualizar información geográfica mediante Leaflet y OpenStreetMap.

---

## Stack Tecnológico

- PHP 8.3+
- Laravel 13
- Blade
- Leaflet
- OpenStreetMap
- MySQL
- Vite
- Tailwind CSS incluido por Breeze Blade

---

## Modelado General

A diferencia del sistema original, esta versión prioriza un modelo más simple y convencional.

El dominio inicial queda definido con solo dos entidades principales:

- `users`
- `markers`

La regla general del modelo es que una tabla nueva solo debe existir cuando resuelve un problema concreto del sistema actual. Por ese motivo, roles, tipos de marcador y estados se modelan inicialmente como columnas simples, no como tablas independientes.

### `users`

Representa a las personas que pueden autenticarse y operar el sistema.

Responsabilidades principales:

- permitir autenticación;
- identificar el rol funcional del usuario;
- controlar si el usuario puede usar el sistema;
- mantener trazabilidad básica de acceso.

### `markers`

Representa cualquier punto georreferenciado que se carga, administra o muestra en el mapa.

Esta entidad reemplaza conceptualmente las tablas legacy separadas para `carteles`, `locales` y `puestos`, pero sin copiar automáticamente esa estructura.

Responsabilidades principales:

- guardar la ubicación del marcador;
- distinguir el tipo funcional del marcador;
- controlar si el marcador debe mostrarse o no;
- conservar datos descriptivos básicos;
- vincular el marcador con el usuario que lo cargó;
- permitir su visualización en Leaflet.

La relación inicial del dominio es:

```txt
User 1 ─── N Marker
```

Un usuario puede cargar muchos marcadores. Cada marcador pertenece a un usuario mediante `user_id`, que funciona como trazabilidad directa.

Las coordenadas deben guardarse como valores decimales, no como `string` ni `float`, para preservar precisión y evitar errores de representación.

---

## Estado Actual

Versión: v1.0.0

### Funcionalidades

- autenticación;
- roles (admin, editor, viewer);
- estados de usuario (pending, active, disabled);
- administración de usuarios;
- CRUD de markers;
- mapa interactivo;
- autorización mediante Policies;
- seeders de desarrollo;
- tests automatizados.

### Roadmap:

- filtros de markers;
- ordenamientos;
- colores por tipo;
- iconos por tipo;
- geolocalización desde navegador;
- despliegue público.

---

## Usuarios De Desarrollo

Seeder incluido:

- admin@example.com
- editor@example.com
- viewer@example.com

Contraseña:
`password`

---

## Estructura Del Repositorio

```txt
/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
```
