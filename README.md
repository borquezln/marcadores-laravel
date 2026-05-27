# Marcadores Laravel

Este sistema ha sido diseñado para crear marcadores en una base de datos que luego serán mostrados sobre un mapa centrado en un punto en particular.

La aplicación original [`Marcadores`](https://github.com/borquezln/marcadores) ha sido reconstruida en Laravel para tener un sistema más moderno, seguro y funcional.

La elección del stack fue parte del camino natural partiendo del proyecto construido únicamente con PHP y algunas implementaciones en Javascript para la carga del mapa y los marcadores.

Este repositorio mantiene el código legacy archivado en [`/legacy`](./legacy), el cual se conserva como referencia funcional, visual y de modelado.

## Estado Del Proyecto

Esta es una reimplementación moderna del sistema original, implementando tecnologías nuevas y manteniendo funcionalidades:

- Laravel convencional
- Blade como capa de interfaz
- Leaflet para mapas
- autenticación básica
- roles simples
- CRUD de marcadores
- código mantenible y fácil de extender

No existe requisito de compatibilidad con la arquitectura ni con la base de código anterior.

## Origen Del Proyecto

El sistema original fue desarrollado en PHP con una estructura MVC simple y tenía como objetivo:

- registrar marcadores en base de datos;
- visualizar esos marcadores sobre un mapa;
- diferenciar tipos de marcadores;
- administrar usuarios con permisos básicos.

Ese comportamiento sigue siendo la referencia funcional del nuevo sistema, pero su implementación fue reiniciada sobre Laravel para simplificar mantenimiento, seguridad y evolución futura.

## Objetivo De Esta Nueva Versión

La nueva aplicación busca reemplazar completamente al sistema original con una base técnica más clara y actual.

Objetivos principales:

- mantener una arquitectura simple;
- unificar criterios de desarrollo;
- evitar lógica procedural dispersa;
- centralizar autenticación, validación y persistencia con herramientas nativas de Laravel;
- facilitar futuras mejoras sin arrastrar deuda técnica del sistema anterior.

## Stack Tecnológico

- PHP 8.x
- Laravel 13
- Blade
- Leaflet
- OpenStreetMap
- MySQL
- Vite
- Tailwind CSS incluido por Breeze Blade

## Alcance Funcional Previsto

La nueva versión está pensada para cubrir estas capacidades:

- inicio de sesión y autenticación básica;
- roles simples de usuario;
- gestión de marcadores;
- visualización de marcadores en mapa;
- formularios con carga de imágenes;
- administración básica de usuarios.

## Roles Previstos

El sistema trabajará con roles simples:

- `admin`: administra usuarios, marcadores y acceso general
- `editor`: crea y edita marcadores
- `viewer`: consulta el mapa y la información publicada

## Modelado General

A diferencia del sistema original, esta versión prioriza un modelo más simple y convencional.

Se planea trabajar principalmente con:

- `users`
- `roles`
- `markers`
- `marker_types`
- `marker_statuses`

Y, si el dominio finalmente lo necesita:

- `marker_contacts`

La intención es evitar replicar automáticamente las tablas legacy separadas (`carteles`, `locales`, `puestos`) salvo que una necesidad funcional concreta lo justifique.

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