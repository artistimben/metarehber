# cPanel Üzerinden Laravel Projesi Kurulum Rehberi

## Mevcut Durum
- Domain: https://anitmezarlik.com/
- Hosting: cPanel
- Proje: Laravel Öğrenci Sistemi

## ÖNEMLİ: Laravel'de Public Klasörü Ayarı

Laravel projelerinde **sadece `public` klasörünün** web'den erişilebilir olması gerekir. Diğer klasörler (app, config, database, vb.) güvenlik nedeniyle web'den erişilmemeli.

## Adım 1: Dosya Yapısını Düzenleme

### Seçenek A: Alt Domain veya Addon Domain (ÖNERİLEN)

cPanel'de domain ayarlarını şu şekilde yapın:

```
anitmezarlik.com klasör yapısı:
├── public_html/              (Laravel'in public klasörü buraya)
├── laravel/                  (Laravel'in diğer dosyaları buraya)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   ├── artisan
│   └── composer.json
```

**İşlem Adımları:**

1. **cPanel File Manager'da:**
   - Tüm Laravel dosyalarını önce `laravel` adında bir klasöre taşıyın
   - `laravel/public` klasörünün içindeki dosyaları `public_html` klasörüne kopyalayın
   - `public_html/index.php` dosyasını düzenleyin (aşağıda anlatılıyor)

2. **index.php dosyasını düzenleyin:**

`public_html/index.php` dosyasını açın ve şu satırları bulun:

```php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```

Şu şekilde değiştirin:

```php
require __DIR__.'/../laravel/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel/bootstrap/app.php';
```

### Seçenek B: Alt Klasörde Çalıştırma (Tavsiye Edilmez)

Eğer domain'in tamamını kullanamıyorsanız:
- `public_html/ogrenci/` gibi bir alt klasör oluşturun
- Ama bu durumda URL'ler `anitmezarlik.com/ogrenci/` şeklinde olur

## Adım 2: PHP Versiyonunu Ayarlama

1. cPanel'e giriş yapın
2. "MultiPHP Manager" veya "Select PHP Version" bölümünü bulun
3. **PHP 8.1 veya üzeri** seçin (tercihen PHP 8.2)
4. Domain için uygulanmasını sağlayın

### Gerekli PHP Eklentileri

Bu eklentilerin aktif olduğundan emin olun:
- ✅ pdo_mysql (veya pdo_sqlite)
- ✅ mbstring
- ✅ openssl
- ✅ tokenizer
- ✅ xml
- ✅ ctype
- ✅ json
- ✅ bcmath
- ✅ fileinfo

cPanel'de "Select PHP Version" > "Extensions" bölümünden kontrol edin.

## Adım 3: MySQL Veritabanı Oluşturma

1. cPanel'de **"MySQL Databases"** bölümüne gidin

2. **Yeni Veritabanı Oluştur:**
   - Database Name: `ogrenci_db` (veya istediğiniz isim)
   - Create Database butonuna tıklayın

3. **Yeni Kullanıcı Oluştur:**
   - Username: `ogrenci_user`
   - Password: Güçlü bir şifre oluşturun (kaydedin!)
   - Create User butonuna tıklayın

4. **Kullanıcıyı Veritabanına Ekle:**
   - User: `ogrenci_user`
   - Database: `ogrenci_db`
   - "Add" butonuna tıklayın
   - "ALL PRIVILEGES" seçin
   - "Make Changes" butonuna tıklayın

5. **Veritabanı bilgilerini not alın:**
   ```
   DB_HOST: localhost
   DB_DATABASE: kullaniciadi_ogrenci_db  (cPanel otomatik prefix ekler)
   DB_USERNAME: kullaniciadi_ogrenci_user
   DB_PASSWORD: az önce oluşturduğunuz şifre
   ```

## Adım 4: .env Dosyası Oluşturma

1. **File Manager'da** `laravel` klasörüne gidin (veya dosyaları nereye yüklediyseniz)

2. `.env.example` dosyasını kopyalayın ve `.env` olarak kaydedin
   - Sağ tık > Copy
   - Yeni isim: `.env`

3. `.env` dosyasını düzenleyin:

```env
APP_NAME="Öğrenci Sistemi"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://anitmezarlik.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=kullaniciadi_ogrenci_db
DB_USERNAME=kullaniciadi_ogrenci_user
DB_PASSWORD=veritabani_sifreniz

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true

MAIL_MAILER=smtp
MAIL_HOST=mail.anitmezarlik.com
MAIL_PORT=587
MAIL_USERNAME=noreply@anitmezarlik.com
MAIL_PASSWORD=email_sifreniz
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@anitmezarlik.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**ÖNEMLİ:** 
- `APP_DEBUG=false` olmalı (güvenlik)
- Veritabanı bilgilerini doğru girin
- cPanel otomatik olarak veritabanı ve kullanıcı adına prefix ekler (genellikle cPanel kullanıcı adınız)

## Adım 5: Terminal Komutlarını Çalıştırma

### 5.1 Terminal Erişimi

cPanel'de **"Terminal"** veya **"SSH Access"** bölümünü bulun ve açın.

### 5.2 Proje Klasörüne Gidin

```bash
cd laravel  # veya dosyalarınızın olduğu klasör
```

### 5.3 Composer Kurulumu

Composer yüklü değilse:

```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar composer
```

Veya sunucuda zaten varsa doğrudan kullanın.

### 5.4 Bağımlılıkları Yükleyin

```bash
# Composer yüklü değilse:
php composer install --optimize-autoloader --no-dev

# Composer global olarak yüklüyse:
composer install --optimize-autoloader --no-dev
```

**Not:** Bu işlem birkaç dakika sürebilir.

### 5.5 Uygulama Anahtarı Oluşturun

```bash
php artisan key:generate
```

Bu komut `.env` dosyasındaki `APP_KEY` değerini otomatik olarak doldurur.

### 5.6 Veritabanı Migrasyonlarını Çalıştırın

```bash
php artisan migrate --force
```

**Uyarı alırsanız:** `yes` yazıp Enter'a basın.

### 5.7 Storage Link Oluşturun

```bash
php artisan storage:link
```

### 5.8 Cache İşlemleri

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5.9 Dosya İzinlerini Ayarlayın

```bash
chmod -R 775 storage bootstrap/cache
```

Eğer yetki hatası alırsanız:
```bash
find storage -type f -exec chmod 664 {} \;
find storage -type d -exec chmod 775 {} \;
find bootstrap/cache -type f -exec chmod 664 {} \;
find bootstrap/cache -type d -exec chmod 775 {} \;
```

## Adım 6: Frontend Assets (CSS/JS) Derleme

Eğer projenizde Vite kullanılıyorsa (Laravel'in yeni versiyonlarında):

### 6.1 Node.js Kontrolü

```bash
node --version
npm --version
```

Yoksa, cPanel'in Node.js menüsünden yükleyin.

### 6.2 NPM Paketlerini Yükleyin

```bash
npm install
```

### 6.3 Production Build

```bash
npm run build
```

Bu komut `public/build` klasöründe optimize edilmiş CSS ve JS dosyaları oluşturur.

## Adım 7: .htaccess Kontrolü

`public_html/.htaccess` dosyasının mevcut ve doğru olduğundan emin olun:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

## Adım 8: İlk Admin Kullanıcısı Oluşturma

Eğer projenizde seeder varsa:

```bash
php artisan db:seed
```

Manuel olarak oluşturmak için Tinker kullanın:

```bash
php artisan tinker
```

Ardından:

```php
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@anitmezarlik.com';
$user->password = bcrypt('guvenli_sifre_123');
$user->save();

// Admin rolü vermek için (eğer role sisteminiz varsa)
$user->assignRole('admin');
```

Çıkmak için `exit` yazın.

## Adım 9: SSL Sertifikası (HTTPS)

cPanel'de ücretsiz SSL sertifikası yükleyin:

1. "SSL/TLS Status" bölümüne gidin
2. Domain'i seçin
3. "Run AutoSSL" butonuna tıklayın

Veya "Let's Encrypt" ücretsiz SSL kullanabilirsiniz.

## Adım 10: Test ve Kontrol

1. Tarayıcıda https://anitmezarlik.com adresini açın
2. Giriş ekranı görünmeli
3. Oluşturduğunuz admin bilgileriyle giriş yapın

### Hata Alırsanız:

**500 Internal Server Error:**
```bash
cd laravel
tail -n 50 storage/logs/laravel.log
```

**Blank Page (Beyaz Sayfa):**
- PHP hata loglarını kontrol edin (cPanel > Error Log)
- `.env` dosyasını kontrol edin
- `APP_KEY` oluşturulmuş mu kontrol edin

**Database Connection Error:**
- `.env` dosyasındaki veritabanı bilgilerini kontrol edin
- cPanel prefix'ini eklemeyi unutmayın

## Güvenlik Kontrol Listesi

- [x] `APP_DEBUG=false` olarak ayarlandı
- [x] `APP_KEY` oluşturuldu
- [x] `.env` dosyası web'den erişilemez konumda
- [x] Veritabanı şifreleri güçlü
- [x] HTTPS (SSL) aktif
- [x] `SESSION_SECURE_COOKIE=true` (HTTPS için)
- [x] `storage` ve `bootstrap/cache` yazılabilir

## Performans Optimizasyonu

```bash
# Cache'leri temizle ve yeniden oluştur
php artisan optimize:clear
php artisan optimize

# Config, route ve view cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Önemli Notlar

1. **Dosya yapısı:** Laravel dosyalarını web root'un dışında tutun
2. **Güncellemeler:** Kod güncellemelerinden sonra cache'leri temizleyin
3. **Yedekleme:** Düzenli olarak veritabanı ve dosya yedekleri alın (cPanel Backup)
4. **Log Takibi:** `storage/logs/laravel.log` dosyasını periyodik kontrol edin

## Sorun Giderme Komutları

```bash
# Cache temizleme
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Yeniden optimize etme
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage link yeniden oluşturma
php artisan storage:link

# Logları görüntüleme
tail -f storage/logs/laravel.log
```

## Yardım ve Destek

Herhangi bir sorun yaşarsanız:
1. `storage/logs/laravel.log` dosyasını kontrol edin
2. cPanel Error Log'u kontrol edin
3. Terminal'de `php artisan` komutlarının çalışıp çalışmadığını test edin

---

**Kurulum tamamlandıktan sonra test için:**
- Giriş yapma ✓
- Öğrenci ekleme ✓
- Kaynak yükleme ✓
- Rapor görüntüleme ✓
