# 📧 Email Setup Guide for Church Contact Form

## Why Emails Might Not Work on Localhost

XAMPP by default **does not include a mail server**. The PHP `mail()` function needs a configured SMTP server to actually send emails. On localhost, this is the most common reason emails fail.

## 🚀 Quick Solutions (Choose One)

### Option 1: Gmail SMTP (Recommended)
**Most reliable for production use**

1. **Enable SMTP in config.php:**
```php
define('ENABLE_SMTP', true);
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'greenparkchurch25@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
define('SMTP_FROM_EMAIL', 'greenparkchurch25@gmail.com');
define('SMTP_FROM_NAME', 'St Anthony\'s Church');
```

2. **Get Gmail App Password:**
   - Go to Google Account settings
   - Enable 2-Step Verification
   - Generate an App Password
   - Use this password in SMTP_PASSWORD

3. **Install PHPMailer:**
   - Download PHPMailer from GitHub
   - Extract to project folder
   - Or use Composer: `composer require phpmailer/phpmailer`

### Option 2: Test Without Real Email (Quick Test)
**Good for immediate testing**

The current enhanced script saves messages to:
- `contact_messages.txt` - All form submissions
- `email_debug.log` - Detailed debugging info

Even if email fails, form submissions are saved and you can see them working.

### Option 3: Use Online Mail Testing Tools
- **Mailtrap.io** - Catches test emails
- **MailHog** - Local email testing
- **Papertrail** - Email debugging service

## 🔧 Debugging Steps

### Step 1: Check Current Status
Open: `http://localhost/Church_project/email-debug.php`
- This will show your current email configuration
- Test basic email functionality
- Show common issues

### Step 2: Test Enhanced Form
Open: `http://localhost/Church_project/`
- Submit the contact form
- Check browser console (F12) for errors
- Look for success/error messages

### Step 3: Check Debug Logs
After submitting forms, check these files:
- `email_debug.log` - Detailed processing log
- `contact_messages.txt` - Saved form submissions

## 🎯 Production Setup (For Live Website)

When you move to a live web hosting service:

1. **Most hosting providers support PHP mail()** out of the box
2. **Update config.php** with your hosting provider's SMTP settings
3. **Test email functionality** on the live server
4. **Consider using professional email services** like:
   - SendGrid
   - Mailgun
   - Amazon SES

## 📋 Current Status Check

To verify what's working:

1. **Form Submission**: ✅ Should work (saves to files)
2. **Data Validation**: ✅ Should work (client & server)
3. **Admin Panel**: ✅ Should work (view messages)
4. **Email Sending**: ❓ Depends on mail server config

## 🆘 Immediate Test

Run this in your browser:
1. `http://localhost/Church_project/email-debug.php` - Check email config
2. `http://localhost/Church_project/` - Test the actual form
3. Check `email_debug.log` file for detailed debugging

The form will work and save messages even if emails don't send!