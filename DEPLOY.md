# Despliegue en VPS

Guia rapida para desplegar BuscaTusFotos en un VPS Ubuntu con Nginx, PHP-FPM y MySQL.

## 1. Requisitos del servidor

- Ubuntu 22.04 o 24.04
- Nginx
- PHP 8.1 o superior con extensiones comunes de Laravel
- MySQL o MariaDB
- Composer
- Node.js 18 o superior
- Git

Instalacion base:

```bash
sudo apt update
sudo apt install -y nginx mysql-server git unzip curl
sudo apt install -y php8.2-fpm php8.2-cli php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath
```

Composer:

```bash
cd /tmp
curl -sS https://getcomposer.org/installer -o composer-setup.php
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer
```

Node.js:

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

## 2. Base de datos

```bash
sudo mysql
```

Dentro de MySQL:

```sql
CREATE DATABASE buscatusfotos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'buscatusfotos'@'localhost' IDENTIFIED BY 'CAMBIA_ESTA_PASSWORD';
GRANT ALL PRIVILEGES ON buscatusfotos.* TO 'buscatusfotos'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## 3. Descargar la app

```bash
cd /var/www
sudo git clone https://github.com/andresokei/buscatusfotos.git
sudo chown -R $USER:www-data buscatusfotos
cd buscatusfotos
```

## 4. Instalar dependencias y compilar assets

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

## 5. Configurar `.env`

```bash
cp .env.example .env
php artisan key:generate
nano .env
```

Valores importantes:

```env
APP_NAME=BuscaTusFotos
APP_ENV=production
APP_DEBUG=false
APP_URL=https://TU-DOMINIO.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=buscatusfotos
DB_USERNAME=buscatusfotos
DB_PASSWORD=CAMBIA_ESTA_PASSWORD

FILESYSTEM_DISK=public

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@TU-DOMINIO.com
MAIL_FROM_NAME="${APP_NAME}"

STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=

ADMIN_USERNAME=
ADMIN_PASSWORD_HASH=
```

Para generar el hash del admin:

```bash
php artisan tinker
```

Dentro de Tinker:

```php
Hash::make('TU_PASSWORD_ADMIN')
```

Copia el resultado en `ADMIN_PASSWORD_HASH`.

## 6. Migraciones, storage y cache

```bash
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R ug+rw storage bootstrap/cache
```

## 7. Nginx

Crea `/etc/nginx/sites-available/buscatusfotos`:

```nginx
server {
    listen 80;
    server_name TU-DOMINIO.com www.TU-DOMINIO.com;
    root /var/www/buscatusfotos/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Activa el sitio:

```bash
sudo ln -s /etc/nginx/sites-available/buscatusfotos /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## 8. HTTPS

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d TU-DOMINIO.com -d www.TU-DOMINIO.com
```

## 9. Scheduler de Laravel

La app limpia tokens expirados con el scheduler. Edita cron:

```bash
sudo crontab -e
```

Anade:

```cron
* * * * * cd /var/www/buscatusfotos && php artisan schedule:run >> /dev/null 2>&1
```

## 10. Stripe webhook

En Stripe configura el endpoint:

```text
https://TU-DOMINIO.com/webhooks/stripe
```

Evento minimo:

```text
checkout.session.completed
```

Copia el signing secret `whsec_...` en `STRIPE_WEBHOOK_SECRET`.

## 11. Despliegues posteriores

```bash
cd /var/www/buscatusfotos
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo chown -R www-data:www-data storage bootstrap/cache
```
