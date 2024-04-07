# Formación Laravel II

## Instalación
`````
git clone https://github.com/JuanraCharco/formacion-laravel-II.git

cd formacion-laravel-II
`````
### Configuración de variables de entorno
````
cp .env.example .env
nano .env
````
Modificar las variables de acceso a la base de datos:
````
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=laraveldb
DB_USERNAME=postgres
DB_PASSWORD="Pass#12345"
````
### Despliegue
````
docker compose up -d
````
### Crear alias para el contenedor ````formacion-laravel-ii-php-1````
````
alias dr="docker exec -it formacion-laravel-ii-php-1"
````
### Instalar paquetes de Laravel
````
dr composer install
````
### Asignar permisos para la caché del framework
````
sudo chgrp -R www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
````
### Ejecutar migraciones para generar la estructura de tablas en la base de datos
````
dr php artisan migrate
````
### Generación de clave Laravel y limpieza de cache
````
dr php artisan key:generate
dr php artisan optimize:clear
````
### Instalación de dependencias Nodejs
````
dr npm install
dr npm run dev
````

## Integración de la plantilla [AdminLTE](https://adminlte.io/themes/v3/) usando el repositorio [jeroennoten/laravel-adminlte](https://github.com/jeroennoten/Laravel-AdminLTE)
### Instalación
````
dr composer require jeroennoten/laravel-adminlte
dr composer require laravel/ui
dr php artisan ui bootstrap –-auth
dr php artisan adminlte:install --type=full --with=main_views --force
````
### Configuración
