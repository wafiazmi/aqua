# Dr. Aquatic - Penjualan ikan hias dan koral 


## Donwload

Clone Projek

```bash
  git clone https://github.com/wafiazmi/aqua.git nama_projek
```

Masuk ke folder dengan perintah

```bash
  cd nama_projek
```

-   Copy .env.example menjadi .env kemudia edit database dan api key nya
```bash
    set COMPOSER_PROCESS_TIMEOUT=900
```
```bash
    composer install
```

```bash
    php artisan key:generate
```

```bash
    php artisan artisan migrate --seed
```

```bash
    php artisan storage:link
```

#### Login

-   email = admin@admin.com
-   password = 123
