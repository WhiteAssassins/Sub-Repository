# Sub-Repository

Repositorio simple de subtitulos hecho en PHP, MySQL/MariaDB y Materialize CSS.

Version actual: `v1.0.0`

## Requisitos

- Apache con PHP 7.4+ u 8.x
- MySQL o MariaDB
- Extension PHP `mysqli`
- XAMPP sirve para correrlo localmente

Probado localmente con PHP 8.2. El codigo evita patrones incompatibles conocidos con PHP 8.5 y solo depende de funciones/extensiones estables (`mysqli`, uploads, filesystem y salida HTTP).

## Instalacion en XAMPP

1. Copia el proyecto dentro de `C:\xampp\htdocs\Sub-Repository`.
2. Inicia Apache y MySQL/MariaDB desde el panel de XAMPP.
3. Importa la base de datos:

```bash
mysql -u root < subt.sql
```

Si no tienes el cliente `mysql` disponible, importa `subt.sql` desde phpMyAdmin.

4. Revisa la configuracion en `config.php`:

```php
$servername = getenv("SUB_REPOSITORY_DB_HOST") ?: "127.0.0.1";
$username = getenv("SUB_REPOSITORY_DB_USER") ?: "root";
$password = getenv("SUB_REPOSITORY_DB_PASS") ?: "";
$dbname = getenv("SUB_REPOSITORY_DB_NAME") ?: "subt";
$urlsite = getenv("SUB_REPOSITORY_SITE_URL") ?: "http://127.0.0.1/Sub-Repository/";
```

5. Abre la app:

```text
http://127.0.0.1/Sub-Repository/
```

## Funcionamiento

- `index.php`: buscador de subtitulos.
- `add.php`: formulario para subir archivos `.srt`.
- `download.php`: valida y descarga subtitulos, incrementando el contador.
- `upload.php`: endpoint legado para incrementar el contador.
- `srt/`: carpeta donde se guardan los subtitulos subidos.
- `subt.sql`: esquema inicial de la base de datos.

## Configuracion

El proyecto funciona con defaults de XAMPP, pero tambien puede configurarse con variables de entorno:

```text
SUB_REPOSITORY_DB_HOST=127.0.0.1
SUB_REPOSITORY_DB_USER=root
SUB_REPOSITORY_DB_PASS=
SUB_REPOSITORY_DB_NAME=subt
SUB_REPOSITORY_SITE_URL=http://127.0.0.1/Sub-Repository/
```

Usa `.env.example` como referencia. No publiques credenciales reales.

## Base de datos

La base se llama `subt` y contiene:

- `upload`: subtitulos registrados.
- `cont`: contador global de descargas.

`subt.sql` crea la base, las tablas, las claves necesarias y la fila inicial del contador.

## Mejoras aplicadas

- Consultas preparadas para evitar SQL injection.
- Escape de salida HTML con `htmlspecialchars`.
- Validacion y normalizacion de nombres de archivos `.srt`.
- Descargas mediante `download.php` en vez de exponer rutas directas.
- Contador incrementado durante la descarga real.
- Esquema SQL actualizado con claves y columnas esperadas por el codigo.
- Proteccion basica de la carpeta `srt/` con `.htaccess`.
- README actualizado para la instalacion actual.
- Limpieza de rastros internos para publicar el repositorio.

## Publicacion del repositorio

Antes de publicar:

- Revisa que `config.php` no tenga credenciales reales.
- No subas archivos `.srt` privados; `srt/*` esta ignorado por Git.
- Mantén `srt/.htaccess`, `SECURITY.md`, `CONTRIBUTING.md`, `NOTICE`, `LICENSE` y `.env.example`.
- Si expones la app en internet, considera proteger `add.php` con login o restringirlo por IP.
- Revisa los assets legacy (`Materialize`, `jQuery`, fuentes) antes de usarlo en produccion.
- GitHub Actions ejecuta lint de PHP en pushes y pull requests.

## Ideas pendientes

- Agregar panel de administracion para borrar o renombrar subtitulos.
- Guardar idioma, categoria, temporada, episodio y autor.
- Mostrar subtitulos recientes sin necesidad de buscar.
- Agregar contador por archivo.
- Migrar estilos inline a un CSS propio.
- Agregar tests basicos de subida, busqueda y descarga.

## Licencia

Este proyecto esta disponible bajo la Apache License 2.0. Ver `LICENSE`.
