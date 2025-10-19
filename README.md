# SIMAS BERKAH v1.1.0 (Root-ready)

This package is ready to extract to /var/www/html/simas so the site will be accessible at http://your-domain/simas/

Quick install:
1. unzip simas-berkah-v1.1.0.zip -d /var/www/html/
2. Import DB: mysql -u root -p < /var/www/html/simas/database/database_simas_v1.sql
3. Ensure uploads writable: sudo chown -R www-data:www-data /var/www/html/simas/uploads && sudo chmod -R 755 /var/www/html/simas/uploads
4. Visit http://your-domain/simas/ and admin at /simas/admin/login.php
Default admin: admin / 123456 (change password after first login)

Auto-update:
- Place your release zips and manifest in your GitHub repo `hana-dev/simas-berkah` (we provided template earlier).
- Admin > Update System GUI will check manifest and install updates.

