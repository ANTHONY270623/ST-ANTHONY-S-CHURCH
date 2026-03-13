# ✅ Quick Deployment Checklist
## St. Anthony's Church Green Park Website

---

## 🎯 Before Upload (Required!)

### 1. Update config.php
```php
// Update these 4 crucial lines:
define('DB_HOST', 'localhost');           // ← Your hosting MySQL server
define('DB_USER', 'your_db_username');    // ← Your database username
define('DB_PASS', 'your_db_password');    // ← Your database password
define('SMTP_PASSWORD', 'your_app_pwd');  // ← Gmail app password (16 chars)
```

### 2. Get Your Database Info
- Login to **cPanel**
- Go to **MySQL Databases**
- Create database: `church_project`
- Create user and assign to database
- Note: username, password, host

### 3. Get Gmail App Password
- Go to: https://myaccount.google.com/security
- Enable **2-Step Verification**
- Create **App Password** for Mail
- Copy 16-character password
- Paste in `config.php` → `SMTP_PASSWORD`

---

## 📤 Upload Process

### Method 1: FTP (FileZilla)
1. Connect to your hosting FTP
2. Navigate to `/public_html/` or `/www/`
3. Upload **ALL files** from your project folder
4. Done!

### Method 2: cPanel File Manager
1. Login to cPanel
2. Open **File Manager**
3. Go to `public_html`
4. Click **Upload**
5. Select all files and upload

---

## 🗄️ Database Setup

1. cPanel → **phpMyAdmin**
2. Select your database (`church_project`)
3. Tables will be **auto-created** on first page visit!
4. No manual SQL import needed ✅

---

## 🔍 Test After Upload

Visit these URLs and test:

1. **Homepage**: `https://your-domain.com/`
2. **Contact Form**: Submit a test message
3. **Admin Login**: `https://your-domain.com/admin-login.php`
4. **Admin Inbox**: Check if message appears
5. **Email**: Check `greenparkchurch25@gmail.com` inbox

---

## ⚠️ Common Issues & Fixes

### ❌ "Database connection failed"
**Fix:** Check credentials in `config.php` → Verify DB exists in cPanel

### ❌ "500 Internal Server Error"  
**Fix:** Check file permissions (644 for files, 755 for folders)

### ❌ Email not sending
**Fix:** Verify Gmail app password → Check SMTP settings → Contact hosting support

### ❌ Blank page / white screen
**Fix:** Enable error display or check PHP error logs in cPanel

---

## 🔐 Security Reminders

- ✅ Changed default admin password?
- ✅ Gmail app password added?
- ✅ Removed test files? (already done!)
- ✅ SSL certificate enabled?

---

## 📞 Support Resources

- **Hosting Control Panel**: Check your hosting provider's cPanel/dashboard
- **Error Logs**: cPanel → Error Logs
- **PHP Version**: cPanel → Select PHP Version (use 7.4 or 8.x)
- **Documentation**: See `PRODUCTION-DEPLOYMENT-GUIDE.md` for details

---

## ✨ Files Cleaned & Ready

Your project is **optimized** with:
- ✅ 22 unnecessary files removed
- ✅ All CSS errors fixed
- ✅ Security files removed (info.php, phpinfo.php)
- ✅ Test & debug files removed
- ✅ Configuration properly commented

**You're ready to deploy! 🚀**

---

*Quick Reference - For full guide see: PRODUCTION-DEPLOYMENT-GUIDE.md*
