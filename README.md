
# Texsun 📦
Nuestro proyecto de ingenieria de software 1 y 2.
Para este proyecto utilizamos laravel como back-end framework

# Requisitos front end
- VueJs
- Yarn

### Instalar Yarn
Mac Os
```npm
brew install yarn
```

Windows y Choco
```npm
choco install yarn
```

Para mas información https://classic.yarnpkg.com/en/docs/install/#windows-stable

### Instalar VueJs
```npm
yarn global add @vue/cli
```

# Requisitos Laravel
- Mysql
- Php >= 7
- Composer
- Laravel
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

# Como instalar Laravel
Link: https://laravel.com/docs/8.x

# Montar la aplicación
#### env
```
Copiar el .env.example y dejar unicamente .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=(poner el nombre de la base de datos)
DB_USERNAME=(usuario)
DB_PASSWORD=(contraseña opcional)
DB_TEST_DATABASE=(base de datos para testing)
DB_TEST_USERNAME=(usuario testing)
DB_TEST_PASSWORD=(contraseña testing opcional)
```

#### Pasos
__Paso 1__ Generar el app key
```
php artisan key:generate
```
__Paso 2__ Instalar las dependencias de composer
```cmd
php artisan composer install
```

__Paso 3__ Migrations
```cmd
php artisan migrate
```
__Paso 4__ Seeders
```cmd
php artisan db:seed
```
__Paso 5__ Instalar passport (Para esto se debe de configurar un par de cosas luego)
```cmd
php artisan passport:install
```

__Paso 6__ (Para android) Buscar el la tabla `oauth_clients` el cliente que tenga name
"Laravel Password Grant Client" y cambiar el client_secret por el nuevo valor y el id por el nuevo valor tambien si es necesario

__Paso 7__ Instalar Dusk
```cmd
php artisan dusk:install
```


# Montar la aplicacion web
Los comandos se ejecutan a nivel del folder
__Paso 1__
```cmd
php artisan serve
```

__Paso 2__ En otra tab de la consola
```cmd
yarn watch
```

__Paso 3__ Ingresar al link que `php artisan serve` haya otorgado

# Testing 
Asegurarse que database.php contenga el siguiente array bajo 'connections'
```php
'connections' => [
        'testing' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_TEST_DATABASE', 'forge'),
            'username' => env('DB_TEST_USERNAME', 'forge'),
            'password' => env('DB_TEST_PASSWORD', ''),
            'unix_socket' => env('DB_TEST_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
]
```
Se debe de configurar el phpunit.xml tanto de unit tests como el de dusk con este valor:
```xml 
<server name="DB_CONNECTION" value="testing"/>
```
## Unit
Para correr los unit tests se debe de correr el siguiente comando
```
vendor/bin/phpunit
```