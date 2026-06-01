# Marcadores - Laravel

## Origen Del Proyecto

El sistema original [`Marcadores`](https://github.com/borquezln/marcadores) fue desarrollado en PHP con una estructura MVC simple y tenía como objetivo:

- registrar marcadores en base de datos;
- visualizar esos marcadores sobre un mapa;
- diferenciar tipos de marcadores;
- administrar usuarios con permisos básicos.

Ese comportamiento sigue siendo la referencia funcional del nuevo sistema, pero su implementación fue reiniciada sobre Laravel para tener un sistema más moderno, seguro y funcional.

Este repositorio mantiene el código legacy archivado en [`/legacy`](./legacy), el cual se conserva como referencia funcional, visual y de modelado. No existe requisito de compatibilidad con la arquitectura ni con la base de código anterior.

---

## Objetivo De Esta Nueva Versión

La elección del stack fue parte del camino natural partiendo del proyecto construido únicamente con PHP y algunas implementaciones en Javascript para la carga del mapa y los marcadores.

La nueva aplicación busca reemplazar completamente al sistema original con una base técnica más clara y actual.

Las premisas de esta reimplementación son:

- Laravel convencional
- Blade como capa de interfaz
- Leaflet para mapas
- autenticación básica
- roles simples
- CRUD de marcadores
- código mantenible y fácil de extender

Objetivos principales:

- mantener una arquitectura simple;
- unificar criterios de desarrollo;
- evitar lógica procedural dispersa;
- centralizar autenticación, validación y persistencia con herramientas nativas de Laravel;
- facilitar futuras mejoras sin arrastrar deuda técnica del sistema anterior.

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

Actualmente el sistema incluye:

- autenticación con Laravel Breeze;
- roles simples (admin, editor, viewer);
- activación de usuarios mediante status;
- CRUD de marcadores;
- ownership básico de marcadores;
- pruebas automatizadas;
- seeders de desarrollo.

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
└── legacy/
```
