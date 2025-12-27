# Deploying STORE to a Live Website / نشر الموقع على الإنترنت

To publish your website online, follow these steps:
لنشر موقعك على الإنترنت، اتبع الخطوات التالية:

## 1. Requirements (المتطلبات)
You need a hosting provider that supports **PHP** and **MySQL** (e.g., Hostinger, Bluehost, Namecheap, or a VPS).
تحتاج إلى استضافة تدعم PHP و MySQL.

## 2. Database Setup (إعداد قاعدة البيانات)
1.  Go to your hosting control panel (e.g., cPanel).
    ادخل إلى لوحة تحكم الاستضافة.
2.  Create a new MySQL Database and User.
    أنشئ قاعدة بيانات جديدة ومستخدم.
3.  Open **phpMyAdmin** and import `app/sql/setup_database.sql`.
    افتح phpMyAdmin واستورد ملف قاعدة البيانات.

## 3. Configuration (الإعدادات)
1.  Open `app/config/env.php`.
2.  Update the database credentials with your real web host details:
    عدل بيانات الاتصال بقاعدة البيانات لتناسب الاستضافة الحقيقية:
    ```php
    return [
        'DB_HOST' => 'localhost', // Usually localhost
        'DB_NAME' => 'your_db_name',
        'DB_USER' => 'your_db_user',
        'DB_PASS' => 'your_db_password',
        'APP_URL' => 'https://yourdomain.com',
        'APP_ENV' => 'production',
        'JWT_SECRET' => 'a_secure_random_string',
    ];
    ```

## 4. Upload Files (رفع الملفات)
1.  Use an FTP client (like FileZilla) or the File Manager in cPanel.
    استخدم برنامج FTP أو مدير الملفات في لوحة التحكم.
2.  Upload the `app` and `web` folders to your `public_html` folder.
    ارفع مجلدات `app` و `web` إلى مجلد `public_html`.
3.  Upload `.htaccess` to the root `public_html`.
    ارفع ملف `.htaccess` إلى المجلد الرئيسي.

## 5. Frontend Connection (ربط الواجهة)
Make sure `web/js/ALL.js` points to the correct API path. Currently it is:
تأكد من أن ملف الجافاسكريبت يشير إلى المسار الصحيح للـ API:
```javascript
const API_BASE = '../app/api';
```
If you upload the folders exactly as they are inside `public_html`, this will work automatically.
إذا رفعت المجلدات كما هي داخل `public_html` بنفس الهيكلية، سيعمل النظام تلقائياً.

## 6. Access (الدخول)
Your site will be available at: `www.yourdomain.com/web/index.html`
سيكون موقعك متاحاً على هذا الرابط.
If you want it to open at `www.yourdomain.com` directly, you can move the content of the `web` folder to the root of `public_html` or use a redirect.
إذا كنت تريد أن يفتح الموقع مباشرة على رابط النطاق، يمكنك نقل محتويات مجلد `web` إلى المجلد الرئيسي.
