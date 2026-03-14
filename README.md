# St. Anthony's Church Green Park Website

This is the website for St. Anthony's Church located in Green Park. The website provides information about the church, its administration, events, prayer times, and more.

## Project Structure


### Contact form and admin inbox

- The page `contact.html` now posts to `contact_submit.php`.
- Submissions are stored in MySQL table `contact_messages` (auto-created) in DB `church_project`.
- Admin can review messages in `admin-inbox.php` (requires login via `admin-login.php`).
- By default, the site attempts to email the admin address defined in `config.php` via PHP `mail()`.

Email options:
- For local XAMPP, `mail()` may need configuration; if email fails, messages are still saved and visible in the inbox.
- Optional SMTP: fill out values in `config.php` and switch to SMTP by setting `ENABLE_SMTP` to true, then install PHPMailer under `vendor/PHPMailer/` and update the handler as needed.

Database:
- Defaults to MySQL on `127.0.0.1` with user `root`/empty password. Adjust in `config.php`.
- The database and table are created on first request.

#### Enabling email sending on Windows (XAMPP)

PHP `mail()` on Windows relies on an external SMTP relay. With XAMPP, configure `sendmail`:

1) Edit `C:\xampp\sendmail\sendmail.ini`

Set these values (example for Gmail with an app password):

```
smtp_server=smtp.gmail.com
smtp_port=587
smtp_ssl=auto
auth_username=YOUR_GMAIL_ADDRESS@gmail.com
auth_password=YOUR_APP_PASSWORD
force_sender=YOUR_GMAIL_ADDRESS@gmail.com
```

2) Edit `C:\xampp\php\php.ini`

Uncomment and set the sendmail path:

```
; For Win32 only.
sendmail_path = "C:\xampp\sendmail\sendmail.exe -t"
```

3) Restart Apache from XAMPP Control Panel.

4) Test: Visit `contact.html`, submit a message, and check the Gmail inbox/spam.

Notes:
- Gmail requires an App Password (enable 2FA, then create an app password). Using your main Gmail password will not work.
- Our handler now uses `From: Church Website <ADMIN_EMAIL>` and sets `Reply-To` to the sender to improve deliverability.

## Features

1. **Enhanced Responsive Design** - Website optimized for mobile phones, tablets, and desktop screens
2. **Dark Mode** - Toggle between light and dark mode
3. **Social Media Integration** - Links to Facebook, YouTube, Instagram, X, and Threads
4. **Interactive Gallery** - Lightbox image viewer with zoom functionality and "Show All" option
4. **Navigation Menu** - Easy navigation to different sections
5. **Parish Administration** - Detailed profiles for church administrators
6. **Council Members** - Information about parish council members
7. **Upcoming Events** - Calendar of upcoming church events
8. **Gallery** - Photo gallery of church events
9. **Contact Form** - Form to contact the church administration
10. **Download Forms** - Section to download various church forms

## Profile Pages

The website includes detailed profile pages for church administrators and council members. These pages provide:

- Personal information about the individual
- Contact details
- Current and past appointments/roles
- Biographical information

Each profile page maintains a consistent design with the main website and includes a back link to the home page.

## Social Media

The church has a presence on the following social media platforms:

- Facebook: https://www.facebook.com/share/19cjbzWyGW/
- YouTube: https://youtube.com/@stanthonyschurchgreenpark?feature=shared
- Instagram: https://www.instagram.com/stanthonyschurch_greenpark?igsh=cjNhcGR3ZGthZjdv
- X (Twitter): (Link to be added)
- Threads: https://www.threads.com/@stanthonyschurch_greenpark

## Updates and Maintenance

To add new administrators or council members:
1. Create a new HTML file based on the existing profile templates
2. Update the content with the individual's details and photo
3. Link the profile from the appropriate card in the main index.html file

To update the website content:
1. Edit the corresponding sections in index.html
2. For more extensive changes, modify style.css for styling adjustments
