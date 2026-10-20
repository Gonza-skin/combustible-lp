# Sistema de Abastecimiento de Combustible

Aplicación Laravel para administrar estaciones, existencias, ventas, cisternas,
conductores, abastecimiento, incidencias y reportes ciudadanos.

## Requisitos

- PHP 8.3 o superior
- Composer
- Node.js y npm
- MySQL 8 (incluido en Laragon)

## Instalación local

Desde PowerShell, en la carpeta del proyecto:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
```

Inicia MySQL y crea la base de datos:

```powershell
mysql -u root -e "CREATE DATABASE IF NOT EXISTS combustible_lp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate --seed
```

Instala y compila los recursos del frontend, y levanta Laravel:

```powershell
npm install
npm run build
php artisan serve
```

Abre <http://127.0.0.1:8000>.

## Cuentas de demostración

El seeder crea cuentas de demostración con la misma contraseña, `25242322`.

- Administrador: `administrador@combustible.test`
- Encargado de estación: `encargado_estacion@combustible.test`

También crea una estación, existencias iniciales y un vehículo de demostración
para probar el flujo de ventas. Estos datos son ficticios y solo para desarrollo.

## Validación

```powershell
php artisan test
vendor\bin\pint --test
npm run build
```
