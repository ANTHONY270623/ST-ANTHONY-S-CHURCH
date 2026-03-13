# 🚀 Production Deployment Guide
## St. Anthony's Church Green Park Website

---

## ✅ Pre-Deployment Checklist

Your website has been **optimized and cleaned** for production. Here's what was done:

### 🧹 Files Cleaned (22 files removed)
- ✅ Removed all test files (test-*.html, test-*.php)
- ✅ Removed all debug files (debug-*.php, email-debug.php)
- ✅ Removed security risks (info.php, phpinfo.php)
- ✅ Removed duplicate files (style copy.css, script.js.bak, etc.)
- ✅ Removed setup files no longer needed
- ✅ Fixed all inline CSS style warnings

### 🔧 Fixed Issues
- ✅ All CSS inline styles moved to external stylesheet
- ✅ Added proper comments to configuration files
- ✅ Website structure optimized

---

## 📋 Files to Upload

Upload **ALL remaining files** in your project folder to your web hosting server, including:

### Essential Files:
```
✓ index.html (main homepage)
✓ config.php (configuration - MUST UPDATE before upload)
✓ db.php (database connection)
✓ style.css (main styles)
✓ script.js (main JavaScript)
✓ navigation.js (navigation functionality)
✓ All .php files (contact forms, admin system, etc.)
✓ All .css files (styling)
✓ All .js files (functionality)
✓ All .html files (pages)
✓ All .md files (documentation - optional, but recommended)
```

### Upload Method:
Use **FTP/SFTP** client (FileZilla, WinSCP) or your hosting control panel's **File Manager**.

---

## ⚠️ CRITICAL: Before You Upload

### 1. Update Database Credentials in `config.php`

**Open `config.php` and update:**

```php
// Change these lines:
define('DB_HOST', '127.0.0.1');      // → Your host's MySQL server (ask your hosting provider)
define('DB_USER', 'root');            // → Your database username
define('DB_PASS', '');                // → Your database password
define('DB_NAME', 'church_project');  // → Your database name
```

**Where to get these credentials:**
- cPanel → MySQL Databases → Create Database & User
- Or check your hosting provider's documentation

---

### 2. Set Up Gmail SMTP (for contact form emails)

**Get Gmail App Password:**
1. Go to https://myaccount.google.com/security
2. Enable **2-Step Verification**
3. Go to **App passwords** → Select "Mail" → Generate
4. Copy the 16-character password (no spaces)

**Update `config.php`:**
```php
define('SMTP_PASSWORD', 'abcd efgh ijkl mnop');  // Paste your app password here
```

---

### 3. Create Database on Your Hosting

**Steps:**
1. Log into your hosting **cPanel** or control panel
2. Go to **MySQL Databases** or **phpMyAdmin**
3. Create a new database (name it `church_project` or use the name you set in config.php)
4. Create a database user and assign it to the database
5. Grant **ALL PRIVILEGES** to the user

**Note:** The website will auto-create the necessary tables on first run!

---

## 📤 Deployment Steps

### Step 1: Prepare Files
1. Update `config.php` with production credentials (see above)
2. Keep a backup of your local files

### Step 2: Upload Files
1. Connect to your hosting via **FTP/SFTP** (use FileZilla or similar)
2. Navigate to your website's root directory (usually `/public_html/` or `/www/`)
3. Upload **ALL files and folders** from your project

### Step 3: Set File Permissions
Set these permissions (via FTP or cPanel):
```
- PHP files (.php): 644
- HTML files (.html): 644
- CSS/JS files: 644
- Folders: 755
```

### Step 4: Test Your Website
1. Visit your website URL
2. Test the contact form (go to Contact section)
3. Try admin login at `your-domain.com/admin-login.php`
4. Check if database is working (submit a contact form)

---

## 🔐 Admin Access

**Admin Login URL:** `https://your-domain.com/admin-login.php`

**Default credentials:**
- Username: `admin`
- Password: Check your database or contact form setup files

**Change admin password:** Use phpMyAdmin or update via SQL query after first login.

---

## 🌐 Domain Setup

### If Using a New Domain:
1. Point your domain's **DNS** to your hosting provider's nameservers
2. Wait 24-48 hours for DNS propagation
3. Add the domain in your hosting control panel

### SSL Certificate (HTTPS):
- Most hosts offer **free SSL** via cPanel → SSL/TLS Status
- Enable "Let's Encrypt" SSL certificate
- Force HTTPS redirect (add to `.htaccess` if needed)

---

## 🔧 Post-Deployment Configuration

### Test Contact Form:
1. Go to Contact page
2. Fill and submit the form
3. Check admin inbox: `your-domain.com/admin-inbox.php`
4. Verify email arrives at `greenparkchurch25@gmail.com`

### Update Social Media Links:
Edit `index.html` and update social media URLs in the footer.

### Update Gallery Images:
Follow instructions in `GOOGLE-DRIVE-GALLERY-SETUP.md` to link your Google Drive gallery.

---

## 🛡️ Security Best Practices

### ✅ Already Implemented:
- ✅ Removed info.php and phpinfo.php (security risks)
- ✅ Removed test and debug files
- ✅ Database credentials in separate config file

### 🔒 Additional Security:
1. **Change default admin password immediately**
2. **Keep config.php out of public access** (hosting usually handles this)
3. **Regular backups** - Use cPanel backup or hosting backup service
4. **Update PHP version** - Use PHP 7.4+ or 8.x for better security
5. **Enable mod_security** in cPanel if available

---

## 📱 Testing Checklist

After deployment, test these:

- [ ] Homepage loads correctly
- [ ] Navigation menu works
- [ ] Dark mode toggle works
- [ ] Contact form submits successfully
- [ ] Admin login works
- [ ] Admin inbox shows submissions
- [ ] Email notifications arrive
- [ ] Gallery images load
- [ ] Mobile responsive design works
- [ ] All internal links work
- [ ] Social media links work

---

## 🆘 Troubleshooting

### Contact Form Not Working:
- Check database credentials in `config.php`
- Verify database and table exist
- Check PHP error logs in cPanel

### Email Not Sending:
- Verify Gmail app password is correct
- Check SMTP settings in `config.php`
- Some hosts block outgoing SMTP - contact support

### 500 Internal Server Error:
- Check file permissions (should be 644 for files, 755 for folders)
- Check PHP version (needs PHP 7.0+)
- Check error logs in cPanel

### Database Connection Failed:
- Verify DB credentials in `config.php`
- Ensure database exists and user has privileges
- Check if MySQL service is running

---

## 📞 Need Help?

1. **Check your hosting provider's documentation**
2. **Contact hosting support** for server-specific issues
3. **Check PHP error logs** in cPanel → Error Logs
4. **Review README.md files** in your project for specific features

---

## 🎉 You're Ready!

Your website is **production-ready**! Just:
1. ✅ Update `config.php` credentials
2. ✅ Upload all files to hosting
3. ✅ Create database
4. ✅ Test everything
5. ✅ Go live!

**Good luck with your church website! 🙏✨**

---

*Last Updated: February 10, 2026*
*Website: St. Anthony's Church Green Park*
