# Sunucuya Yükleme Rehberi

Bu doküman, Laravel projesini sunucuya yüklerken değiştirilmesi gereken ayarları açıklar.

## 1. .env Dosyası Oluşturma

Sunucuda proje kök dizininde `.env` dosyası oluşturun ve aşağıdaki ayarları yapın:

### Temel Uygulama Ayarları

```env
APP_NAME="Öğrenci Sistemi"
APP_ENV=production
APP_KEY=                    # php artisan key:generate komutu ile oluşturun
APP_DEBUG=false             # Üretimde MUTLAKA false olmalı
APP_URL=https://yourdomain.com  # Sunucunuzun gerçek domain adresi
```

**ÖNEMLİ:** `APP_DEBUG=false` olmalı ve `APP_KEY` mutlaka oluşturulmalıdır.

### Veritabanı Ayarları

**SQLite kullanıyorsanız:**
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

**MySQL/MariaDB kullanıyorsanız (Önerilen):**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1          # veya sunucunuzun DB host adresi
DB_PORT=3306
DB_DATABASE=veritabani_adi
DB_USERNAME=veritabani_kullanici
DB_PASSWORD=guclu_sifre
```

**PostgreSQL kullanıyorsanız:**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=veritabani_adi
DB_USERNAME=veritabani_kullanici
DB_PASSWORD=guclu_sifre
```

### Oturum (Session) Ayarları

```env
SESSION_DRIVER=database     # veya file, redis
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true  # HTTPS kullanıyorsanız true olmalı
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

### E-posta Ayarları

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourdomain.com    # E-posta sunucunuzun adresi
MAIL_PORT=587                     # veya 465 (SSL için)
MAIL_USERNAME=your_email@domain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls              # veya ssl
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Dosya Sistemi Ayarları

Eğer dosyaları S3'te saklayacaksanız:
```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket_name
```

## 2. Sunucuda Yapılması Gereken İşlemler

### Adım 1: Dosyaları Yükleme
- Proje dosyalarını sunucuya yükleyin (FTP, SFTP veya Git ile)

### Adım 2: Bağımlılıkları Yükleme
```bash
composer install --optimize-autoloader --no-dev
```

### Adım 3: .env Dosyası Oluşturma
```bash
cp .env.example .env
# Sonra .env dosyasını düzenleyin
```

### Adım 4: Uygulama Anahtarı Oluşturma
```bash
php artisan key:generate
```

### Adım 5: Veritabanı Migrasyonları
```bash
php artisan migrate --force
```

### Adım 6: Storage Link Oluşturma
```bash
php artisan storage:link
```

### Adım 7: Cache Temizleme ve Optimizasyon
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Adım 8: İzinleri Ayarlama
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
# www-data yerine sunucunuzun web sunucusu kullanıcısını yazın
```

## 3. Web Sunucusu Yapılandırması

### Apache (.htaccess)
`public` klasörü web root olarak ayarlanmalıdır. `.htaccess` dosyası zaten mevcut olmalı.

### Nginx
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/project/public;

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

## 4. Güvenlik Kontrol Listesi

- [ ] `APP_DEBUG=false` olarak ayarlandı
- [ ] `APP_KEY` oluşturuldu
- [ ] Veritabanı şifreleri güçlü ve güvenli
- [ ] `.env` dosyası web'den erişilemez (`.htaccess` veya Nginx ile korunmalı)
- [ ] `storage` ve `bootstrap/cache` klasörleri yazılabilir
- [ ] PHP versiyonu 8.1 veya üzeri
- [ ] Gerekli PHP eklentileri yüklü (pdo, mbstring, openssl, tokenizer, xml, ctype, json, bcmath)
- [ ] HTTPS kullanılıyorsa `SESSION_SECURE_COOKIE=true`

## 5. Performans Optimizasyonları

### Composer Optimizasyonu
```bash
composer install --optimize-autoloader --no-dev
```

### Laravel Cache
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### OPcache
PHP OPcache'in aktif olduğundan emin olun (php.ini'de)

## 6. Sorun Giderme

### 500 Hata
- `storage/logs/laravel.log` dosyasını kontrol edin
- İzinleri kontrol edin: `chmod -R 775 storage bootstrap/cache`
- `.env` dosyasının doğru yapılandırıldığından emin olun

### Veritabanı Bağlantı Hatası
- Veritabanı bilgilerinin doğru olduğundan emin olun
- Veritabanı sunucusunun erişilebilir olduğunu kontrol edin
- Firewall ayarlarını kontrol edin

### Dosya Yükleme Sorunları
- `storage/app/public` klasörünün yazılabilir olduğundan emin olun
- `php artisan storage:link` komutunu çalıştırın
- PHP `upload_max_filesize` ve `post_max_size` ayarlarını kontrol edin

