# One Club, Two Teams — PHP + MySQL

A responsive dark/black + red sports club website with separate cricket and football squads, public player profiles and stats, managers/officials, match fixtures/results, announcements, photo gallery, and a password-protected admin area.

## Requirements
- PHP 8.1+ (8.2+ recommended) with `pdo_mysql`, `fileinfo`, and `mbstring`
- MySQL 8.0+ or MariaDB 10.5+
- Apache/Nginx or PHP's built-in development server
- HTTPS for any public production deployment

## Install locally (XAMPP on Windows)
1. Install XAMPP and start **Apache** and **MySQL**.
2. Copy the `one-club-two-teams` folder into `C:\xampp\htdocs\`.
3. Open `http://localhost/phpmyadmin`, choose **Import**, and import `database.sql`.
   - The SQL creates the `one_club_two_teams` database and tables.
4. Edit `config.php` and set `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASS` to your MySQL credentials.
   - XAMPP default is often `root` with an empty password on a local machine only. Do not use that setup on a public server.
5. Ensure `uploads/` is writable by PHP. The folder is created automatically, but your hosting account may need permissions adjusted.
6. Open `http://localhost/one-club-two-teams/setup.php`.
7. Create an admin username and a unique password of at least 12 characters.
8. **Immediately delete or rename `setup.php`** after the first admin is created.
9. Open `http://localhost/one-club-two-teams/admin.php`, sign in, and add your players, officials, matches, news, and photos.
10. Visit `http://localhost/one-club-two-teams/` to see the public site.

## Deploy to shared hosting / cPanel
1. Create a MySQL database and a dedicated database user in cPanel. Grant that user permissions on the database.
2. Import `database.sql` using phpMyAdmin. If the hosting provider does not allow `CREATE DATABASE`, remove the first two lines (`CREATE DATABASE...` and `USE...`) and import into the database you created.
3. Update `config.php` with the hosting provider's DB host, database name, username, and password. Do not upload real credentials to a public repository.
4. Upload the project files to your domain's document root (commonly `public_html/`). Ensure `assets/` and PHP files are present.
5. Enable PHP 8.1+ and extensions `pdo_mysql`, `fileinfo`, and `mbstring`.
6. Enable HTTPS in cPanel and force HTTPS for the site.
7. Make `uploads/` writable by the PHP process (often 755 or 775 depending on hosting; avoid 777 unless your host explicitly requires it).
8. Visit `https://your-domain.example/setup.php`, create the first admin, then delete or rename `setup.php`.
9. Sign in at `/admin.php`. Change any default/sample content before sharing the site publicly.

## Admin workflow
- **Players & stats:** select a sport; add/edit players; set active/inactive; enter jersey number, role, photo, matches, runs/wickets or goals/assists. Inactive players are hidden from the public squad.
- **Managers & officials:** choose Whole club, Cricket, or Football.
- **Matches & results:** add upcoming fixtures, then edit them to Completed and enter scores/result.
- **Announcements:** uncheck “Publish publicly” to save a draft.
- **Photo gallery:** upload a JPG, PNG, or WebP image (max 5 MB).

## Important security notes before production
- This is a practical starter implementation, not a substitute for a security review.
- Use HTTPS and a strong unique admin password. Use a least-privilege database account; do not use MySQL root in production.
- Delete `setup.php` immediately after setup. Restrict access to backups and database exports.
- Keep PHP and MySQL patched. Back up the database and `uploads/` regularly.
- Public player names, photos, roles, and stats are displayed publicly. Avoid collecting or publishing personal phone numbers unless you have permission. Player phone numbers are only shown in the admin interface.
- Configure your web server so PHP is executed and uploads cannot be executed as scripts. The upload handler accepts only JPG, PNG, and WebP based on detected MIME type and uses random filenames.
- Admin operations use prepared statements, password hashing, session ID regeneration, and CSRF tokens. For a higher-risk public deployment, add rate limiting, audit logging, stricter session cookie settings, and a tested backup/restore process.

## Project structure
- `index.php` — public homepage
- `team.php` — cricket / football squads and stats
- `officials.php`, `matches.php`, `announcements.php`, `gallery.php` — public pages
- `admin.php`, `setup.php`, `dashboard.php`, `admin_manage.php`, `logout.php` — admin area
- `config.php` — database connection and shared security helpers
- `database.sql` — schema
- `assets/style.css` — responsive theme
- `uploads/` — uploaded photos

## Troubleshooting
- **Database connection failed:** verify DB values in `config.php`, database import, and the hosting DB hostname.
- **PDO driver not found:** enable/install PHP's `pdo_mysql` extension.
- **Images do not upload:** check `fileinfo`, `upload_max_filesize`, `post_max_size`, and directory write permission. Keep PHP upload limits at least 6 MB for a 5 MB image.
- **Blank/500 error:** inspect your PHP/Apache error log. Keep `display_errors=Off` on production.
- **No public content:** sign in and add records in the dashboard. The public pages start empty by design.

## Notes
- This build does not include player self-registration, public phone numbers, attendance, automated email/WhatsApp alerts, or automated scoring integrations. It can be extended later.
