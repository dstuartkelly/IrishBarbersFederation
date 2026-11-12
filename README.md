# Irish Barbers Federation website

Website for the Irish Barbers Federation (IBF), live at https://barbersfederationofireland.com. A development copy is at https://dstuartkelly.github.io/IrishBarbersFederation.

It is a static HTML, CSS and JavaScript site with one PHP-backed registration form. There is no build step or package manager. Files are deployed as-is to an Apache + PHP + MySQL host managed through Plesk.

## Run it locally

You need Docker with Compose. The setup mirrors the live server: Apache (reading `.htaccess`), PHP 8.5-FPM and MySQL 8.0, plus phpMyAdmin.

```bash
docker compose up -d --build
```

- Site: https://localhost/ (self-signed certificate, so accept the browser warning once)
- phpMyAdmin: http://localhost:8081 (user `ibf`, password `ibf_local_password`)

The site is HTTPS-only because `.htaccess` redirects to HTTPS and the form's session cookie is Secure.

`create_database.sql` loads the first time the database starts. To reset the database:

```bash
docker compose down -v
```

If ports 80 or 443 are already in use, copy `.env.example` to `.env`, change the ports, and browse to the HTTPS port directly.

## Layout

| Path | Purpose |
|---|---|
| `*.html`, `join.php` | Pages. Each contains its own full copy of the head, nav and footer, so shared changes are made in every page |
| `styles/style.css` | The only stylesheet |
| `scripts/` | `site.js` (mobile menu, footer year) and `form-handler.js` (form submission) |
| `submit-form.php` | Handles the registration form and returns JSON |
| `generate-csrf-token.php` | Session and CSRF token for the form |
| `create_database.sql` | Database schema |
| `db-config.template.php` | Template for `db-config.php` |
| `.htaccess` | HTTPS redirect, security headers, blocked files, redirects |
| `compose.yaml`, `docker/` | Local Docker setup |
| `og-image.html` | Internal tool for generating `images/og-image.png` |
| `sitemap.xml`, `robots.txt` | SEO files |

## Database and configuration

`db-config.php` holds the database credentials and is gitignored. Create it from `db-config.template.php`. On the server it sits above the web root, and `submit-form.php` looks there first. The Docker setup supplies its own local copy in `docker/db-config.php`.

`create_database.sql` is the source of truth for the table structure. Run the matching `ALTER TABLE` on the live database when it changes.

## Deployment

Work happens on the `development` branch and is merged into `main`. The repository is deployed as-is to the Plesk host.

The development site on GitHub Pages is static only: PHP does not run there, so `join.php` and the registration form do not work, and `.htaccess` is ignored. Use the Docker setup to test those.
