# StudyFlow — Student Academic & Study Management System

StudyFlow is a responsive single-page web application concept for university students to organize courses, assignments, study sessions, notes and academic categories. It includes student and administrator experiences and a planned learning-resources feature powered by the Open Library API.

> **Milestone 1 prototype:** The frontend is static and uses browser localStorage for demonstration data. It is not connected to a backend or database. Authentication and role switching are demonstration-only and are not security controls.

## Technology stack
- Frontend: HTML5, CSS3, JavaScript, Bootstrap 5
- Planned backend: PHP with FlightPHP REST framework
- Planned database: MySQL accessed through PHP PDO
- Planned authentication: JWT
- Planned API documentation: OpenAPI / Swagger UI
- Planned external API: Open Library Search API

## Main features
- Dashboard with academic summary and upcoming assignments
- Course management
- Assignment management with deadlines, priorities and statuses
- Study session planner and history
- Notes and categories
- Learning resources search (Open Library feature placement and prototype integration)
- Profile, settings and administrator panel

## Core database entities
1. Users
2. Courses
3. Assignments
4. Study Sessions
5. Notes
6. Categories

See [`database/ERD.md`](database/ERD.md) for the draft ERD and [`database/SCHEMA.md`](database/SCHEMA.md) for the physical MySQL schema. The Milestone 2 export and sample data are in [`database/studyflow.sql`](database/studyflow.sql).

## Project structure
```text
studyflow/
├── index.html
├── frontend/
│   ├── css/styles.css
│   ├── js/{app.js,data.js}
│   ├── views/                 # reserved for reusable HTML templates
│   └── assets/images/
├── backend/
│   ├── routes/
│   ├── services/
│   ├── dao/
│   ├── middleware/
│   ├── config/config.example.php
│   └── logs/                  # runtime logs are ignored by Git
├── database/ERD.md
├── docs/AI_LOG.md
├── .gitignore
└── README.md
```

## Run the Milestone 1 prototype
1. Download or clone the repository.
2. Open the project folder in a terminal.
3. Start a local static server, for example:
   ```bash
   php -S localhost:8000
   ```
4. Open `http://localhost:8000` in a browser.

A static server is recommended because it avoids browser restrictions on local files. Bootstrap and Google Fonts are loaded from CDNs, so those assets require an internet connection. The Open Library search feature also requires internet access.

## Demo behavior
- Navigation changes views without a full-page reload.
- Course, assignment, session, note and category forms demonstrate CRUD interactions.
- Prototype data is saved in browser localStorage. Use Settings → Reset sample data to restore the seed data.
- Settings allows switching between Student and Administrator navigation for demonstration.
- Learning Resources searches Open Library directly from the browser as a Milestone 1 feature preview. In the final architecture, the external API call will be moved behind a backend service/endpoint as required by the project specification.

## Milestone 2: database and DAO layer
1. Install MySQL 8.0+ and create a local database user.
2. Import `database/studyflow.sql` using MySQL Workbench or `mysql -u YOUR_USER -p < database/studyflow.sql` (the script creates/selects `studyflow` and recreates its six development tables).
3. Copy `backend/config/config.example.php` to `backend/config/config.php` and set the local host, port, database name, username and password.
4. PHP 8+ with PDO and the `pdo_mysql` extension is required.
5. DAO classes are in `backend/dao/`; load `DAOFactory.php` and call `DAOFactory::courses()` etc. The base class provides prepared-statement create/read/update/delete operations. Pass an authenticated owner ID to `findById`, `findAll`, `update` and `delete` for user-scoped access. Authentication middleware is a later milestone; do not expose DAO access directly to public requests.

The real `config.php` is excluded from Git. Do not commit secrets. Run the DAO integration smoke test after importing the schema and configuring MySQL: `php tests/dao_smoke_test.php`. It creates temporary rows inside a transaction and rolls them back.

## Roles
- Student: manages their own academic records.
- Administrator: has administrative user-management privileges.

## External API
Planned integration: [Open Library Search API](https://openlibrary.org/developers/api). It will support book discovery in the Learning Resources page. The final backend implementation must set timeouts, handle API errors, log failures and return a user-friendly response if the provider is unavailable.

## API documentation and deployment
Swagger/OpenAPI documentation and public deployment are not available yet; they are planned for later milestones. The course requires these links in the final README once the documentation and deployment exist.

## Development sample accounts
The SQL seed creates `alex@student.edu` (student) and `admin@studyflow.local` (admin). Both seed rows currently use a development password hash intended for the password `password`; verify it with PHP `password_verify()` after import and change these credentials before any public deployment. These SQL sample users are not yet connected to the Milestone 1 demo login. No production authentication exists until Milestone 4.

## Git milestone workflow
Create and submit a separate branch for each milestone (`milestone1`, `milestone2`, etc.). For Milestone 1, create a pull request from `milestone1`, add the required reviewer `ibu-web-programming2001`, and do **not** merge the milestone branch into `main` yourself. Follow the course LMS submission instructions.

## AI usage documentation
See [`AGENTS.md`](AGENTS.md) for architecture context and [`docs/AI_LOG.md`](docs/AI_LOG.md) for per-milestone AI usage, prompts, verification and correction notes. Review, test and understand every generated file before submission.


## Milestone 3 — REST API, services and API documentation

The backend now uses FlightPHP 3. Install dependencies from the project root:

```bash
composer install
```

Copy `backend/config/config.example.php` to `backend/config/config.php` and configure the MySQL connection. Import `database/studyflow.sql` into MySQL 8+ first. Then start the local development server from the project root:

```bash
php -S 127.0.0.1:8000 -t . backend/router.php
```

API health check: `http://127.0.0.1:8000/api/health`

Swagger UI: `http://127.0.0.1:8000/docs/api/swagger.html`

OpenAPI source: `docs/api/openapi.yaml`

### REST endpoints

All entity endpoints use the `/api/v1` prefix and support `GET collection`, `GET /{id}`, `POST`, `PUT`, `PATCH`, and `DELETE`:

- `/users`
- `/courses`
- `/assignments`
- `/study-sessions`
- `/notes`
- `/categories`

Learning resources: `GET /api/v1/resources/search?q=database&limit=10` proxies Open Library Search API and returns normalized book results. The backend applies a request timeout, validates the query, limits results, and returns a friendly `502` response if the provider is unavailable. Failures are written to `backend/logs/error.log` (the logs directory is ignored by Git).

### Layering

`backend/routes/api.php` → `backend/services/*Service.php` → `backend/dao/*DAO.php` → PDO/MySQL. Routes do not call DAOs. Each entity has its own service and validation rules. User passwords supplied during user creation/update are hashed with `password_hash`; password hashes are never included in API read responses.

**Milestone 3 security boundary:** JWT authentication, role authorization and request middleware are Milestone 4 requirements and are not yet active. Run this API only in a local development environment; do not expose it publicly until Milestone 4 is complete. The current entity API is a development CRUD surface, not a production-ready authenticated API.

### Milestone 3 verification

Run the service-level checks:

```bash
php tests/service_smoke_test.php
```

Run the DAO integration test after configuring MySQL and enabling `pdo_mysql`:

```bash
php tests/dao_smoke_test.php
```

FlightPHP routes require Composer dependencies (`vendor/`). The current development container did not have Composer or `pdo_mysql`, so route execution against MySQL and live external-network testing must be completed on the developer machine.
