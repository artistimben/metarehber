# cPanel Terminal ile Kurulum - Adım Adım

## ŞU ANDA YAPMANIZ GEREKENLER

### ADIM 1: Mevcut Durumu Kontrol Edin

cPanel Terminal'i açın ve şu komutları sırayla çalıştırın:

```bash
# Ana dizine gidin
cd ~

# Mevcut klasör yapısını görün
ls -la

# public_html içeriğini görün
ls -la public_html/
```

**Sonucu buraya not edin, devam etmek için bana gösterin.**

---

## ADIM 2: Dosya Yapısını Düzenleyin

Laravel güvenliği için sadece `public` klasörü web'den erişilebilir olmalı.

### 2.1 Laravel Klasörü Oluşturun

```bash
# Ana dizinde Laravel klasörü oluşturun
mkdir -p ~/laravel_app

# public_html içindeki BÜTÜN dosyaları laravel_app'e taşıyın
mv ~/public_html/* ~/laravel_app/
mv ~/public_html/.[^.]* ~/laravel_app/ 2>/dev/null

# Kontrol edin
ls -la ~/public_html/
ls -la ~/laravel_app/
```

### 2.2 Public Klasörünü Web Root'a Taşıyın

```bash
# Laravel'in public klasörünü public_html'e taşıyın
mv ~/laravel_app/public/* ~/public_html/
mv ~/laravel_app/public/.[^.]* ~/public_html/ 2>/dev/null

# public klasörünü silin (artık gerekli değil)
rm -rf ~/laravel_app/public

# Kontrol
ls -la ~/public_html/
```

### 2.3 index.php'yi Düzenleyin

```bash
# index.php'yi düzenlemek için nano editör ile açın
nano ~/public_html/index.php
```

**Şu satırları bulun:**
```php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```

**Şu şekilde değiştirin:**
```php
require __DIR__.'/../laravel_app/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel_app/bootstrap/app.php';
```

**Kaydetmek için:**
- `Ctrl + O` (kaydet)
- `Enter` (onayla)
- `Ctrl + X` (çık)

---

## ADIM 3: PHP Versiyonunu Kontrol Edin

```bash
# PHP versiyonunu kontrol edin
php -v
```

**PHP 8.1 veya üzeri olmalı!** Değilse cPanel'den "MultiPHP Manager" ile PHP 8.2 seçin.

---

## ADIM 4: Composer Kurulumu

```bash
# Laravel klasörüne gidin
cd ~/laravel_app

# Composer var mı kontrol edin
which composer

# Yoksa Composer'ı indirin
curl -sS https://getcomposer.org/installer | php
mv composer.phar composer
chmod +x composer

# Bağımlılıkları yükleyin (BU BİRAZ SÜRER - 2-3 dakika)
./composer install --optimize-autoloader --no-dev

# Veya composer global ise:
composer install --optimize-autoloader --no-dev
```

**ÖNEMLİ:** Bu komut çalışırken bekleyin, birkaç dakika sürebilir.

---

## ADIM 5: .env Dosyasını Oluşturun

```bash
cd ~/laravel_app

# .env.example'ı kopyalayın
cp .env.example .env

# .env dosyasını düzenleyin
nano .env
```

**Şu ayarları yapın:**

```env
APP_NAME="Öğrenci Sistemi"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://anitmezarlik.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=BURAYA_VERİTABANI_ADI
DB_USERNAME=BURAYA_VERİTABANI_KULLANICI
DB_PASSWORD=BURAYA_VERİTABANI_ŞİFRESİ

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
```

**Kaydetmek için:** `Ctrl+O`, `Enter`, `Ctrl+X`

---

## ADIM 6: MySQL Veritabanı Oluşturun

cPanel'de "MySQL Databases" bölümüne gidin:

1. **Veritabanı Oluştur:** `ogrenci_db`
2. **Kullanıcı Oluştur:** `ogrenci_user` + güçlü şifre
3. **Kullanıcıyı Veritabanına Ekle** (ALL PRIVILEGES)
4. **Not alın:** cPanel otomatik prefix ekler (örn: `cpanel_kullanici_ogrenci_db`)

**Sonra .env dosyasına bu bilgileri yazın.**

---

## ADIM 7: Laravel Kurulum Komutları

```bash
cd ~/laravel_app

# Uygulama anahtarı oluşturun
php artisan key:generate

# Veritabanı tablolarını oluşturun
php artisan migrate --force

# Storage link oluşturun
php artisan storage:link

# Cache oluşturun
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Dosya izinlerini düzenleyin
chmod -R 775 storage bootstrap/cache
```

---

## ADIM 8: Node.js ve NPM (Frontend için)

```bash
cd ~/laravel_app

# Node.js var mı kontrol edin
node -v
npm -v

# Varsa paketleri yükleyin
npm install

# Production build oluşturun
npm run build
```

**Not:** Node.js yoksa cPanel'den "Node.js" menüsünden yükleyin.

---

## ADIM 9: İlk Admin Kullanıcısı Oluşturun

```bash
cd ~/laravel_app
php artisan tinker
```

Tinker içinde şunu yazın:

```php
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@anitmezarlik.com';
$user->password = bcrypt('Sifre123!');
$user->save();
```

Çıkmak için: `exit`

---

## ADIM 10: Test Edin

Tarayıcıda https://anitmezarlik.com adresini açın.

**Beklenen:** Giriş ekranı görünmeli.

---

## HATA ALIRSAN

### 500 Hatası

```bash
cd ~/laravel_app
tail -50 storage/logs/laravel.log
```

### Beyaz Sayfa

```bash
# Cache'leri temizle
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Yeniden cache oluştur
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Veritabanı Hatası

.env dosyasındaki veritabanı bilgilerini kontrol edin:

```bash
nano ~/laravel_app/.env
```

---

## ÖNEMLI NOTLAR

1. **Güvenlik:** `APP_DEBUG=false` olmalı
2. **HTTPS:** cPanel'de SSL/TLS kurmalısınız
3. **Yedekleme:** Düzenli veritabanı yedeği alın
4. **Log Kontrolü:** Hataları `storage/logs/laravel.log` dosyasından takip edin

---

## HIZLI BAŞVURU - Sık Kullanılan Komutlar

```bash
# Laravel klasörüne git
cd ~/laravel_app

# Cache temizle
php artisan optimize:clear

# Cache oluştur
php artisan optimize

# Log göster
tail -50 storage/logs/laravel.log

# Veritabanı yenile
php artisan migrate:fresh --force
```
