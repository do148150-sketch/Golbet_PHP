# GolBet

Simulador académico de una casa de apuestas deportivas dedicada al fútbol.
Los usuarios consultan la cartelera de partidos, revisan las cuotas y apuestan
con una moneda virtual llamada **FutCoins**. No se maneja dinero real en ningún momento.

Proyecto de asignatura desarrollado en PHP con Laravel.

## Equipo

| Integrante | GitHub |
|---|---|
| Diego Andrés Ospina Mesa | [@do148150-sketch](https://github.com/do148150-sketch) |
| Santiago Mendoza | [@santimendoza15](https://github.com/santimendoza15) |
| Nicolás | [@Nicolas-2307](https://github.com/Nicolas-2307) |

## Tecnologías

- PHP 8.3
- Laravel 13
- MySQL 8.4 sobre motor InnoDB
- Blade como motor de plantillas
- Bootstrap 5 con la plantilla NiceAdmin
- SweetAlert2 para mensajes y confirmaciones

## Requisitos

- PHP 8.3 o superior
- Composer
- MySQL 8 (por ejemplo con WampServer)

## Instalación

Clonar el repositorio e instalar las dependencias:

```bash
git clone https://github.com/do148150-sketch/Golbet_PHP.git
cd Golbet_PHP
composer install
```

Copiar `.env.example` a `.env` y generar la llave de la aplicación:

```bash
php artisan key:generate
```

Crear la base de datos:

```sql
CREATE DATABASE golbetdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Ejecutar las migraciones y cargar los datos iniciales:

```bash
php artisan migrate --seed
```

Levantar el servidor:

```bash
php artisan serve
```

La aplicación queda en `http://127.0.0.1:8000`.

> MySQL tiene que estar encendido incluso para abrir la página de inicio:
> Laravel guarda las sesiones en base de datos (`SESSION_DRIVER=database`).

## Funcionalidad actual

- Dashboard de bienvenida
- CRUD de equipos: listar, crear, editar y eliminar
- Carga inicial de 8 equipos de la liga colombiana mediante seeder

## Modelo de datos

### teams

| Columna | Tipo | Notas |
|---|---|---|
| `id` | bigint unsigned | Clave primaria |
| `name` | varchar(80) | Único |
| `city` | varchar(60) | Obligatorio |
| `crest_url` | varchar(300) | Opcional, URL del escudo |
| `is_active` | boolean | Por defecto `true` |
| `created_at` / `updated_at` | timestamp | |

Las tablas `matches`, `bets`, `roles` y `role_user` están contempladas en el
modelo relacional del proyecto y se implementan en las siguientes entregas.

## Decisiones de diseño

**Borrado lógico.** Eliminar un equipo no destruye la fila: pone `is_active`
en `false` y el listado filtra por esa columna. Así se conserva el histórico,
algo necesario cuando los partidos y las apuestas empiecen a referenciar equipos.

**InnoDB forzado.** WampServer trae MyISAM como motor por defecto, que ignora
las llaves foráneas y limita los índices a 1000 bytes. En `config/database.php`
se fija `'engine' => 'InnoDB'` para que la configuración viaje en el repositorio
y no dependa de la máquina de cada integrante.

**Validación en el controlador.** Las reglas se declaran con `Validator::make()`
dentro de cada acción, con los mensajes en español. Los errores se muestran con
SweetAlert2 desde `resources/views/layouts/scripts.blade.php`.

**Un archivo de rutas por entidad.** `routes/web.php` incluye los archivos de
`routes/web/`, uno por cada recurso.

## Estructura

```
app/
├── Http/Controllers/
│   ├── HomeController.php
│   └── TeamsController.php
└── Models/
    └── Team.php

database/
├── migrations/      Esquema de la base de datos
└── seeders/         Datos iniciales

resources/views/
├── layouts/         Plantilla base dividida en parciales
├── home/
└── teams/           index, create y edit

routes/
├── web.php
└── web/teams.php

public/
├── assets/          Plantilla NiceAdmin
└── libs/            jQuery y SweetAlert2
```
