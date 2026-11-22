# StudyFlow — AI coding assistant context

## Project
StudyFlow is a student academic and study management SPA with a PHP/MySQL backend. Current work follows the university Web Programming milestone specification.

## Stack
- Frontend: HTML5, CSS3, vanilla JavaScript, Bootstrap 5.
- Backend: PHP 8+, PDO, FlightPHP 3 (REST presentation layer), Composer.
- Database: MySQL 8+, InnoDB, utf8mb4.
- API docs: OpenAPI 3.0 in `docs/api/openapi.yaml`, rendered by Swagger UI in `docs/api/swagger.html`. External provider: Open Library Search API.

## Architecture rules
- Keep frontend assets under `frontend/`; backend PHP under `backend/`; SQL and ERD documentation under `database/`.
- Use a layered backend: routes → services → DAO → PDO database. Services will be the only layer calling DAOs when Milestone 3 is implemented.
- DAO SQL values must use PDO prepared statements. Dynamic table/column identifiers must come only from fixed DAO definitions, never request input.
- Scope user-owned reads/updates/deletes by authenticated user ID when authentication middleware is added. Never trust a user ID supplied by the browser as an authorization identity.
- Keep credentials in ignored `backend/config/config.php`; commit only `config.example.php`.
- Do not commit logs, secrets, vendor packages, node_modules, or local environment files.
- Preserve existing frontend behavior and responsive design when extending the SPA.

## Naming and style
- PHP classes: PascalCase; methods and variables: camelCase; database tables/columns: snake_case.
- Use strict types in PHP files, descriptive names, small cohesive methods, and comments for non-obvious decisions.
- Keep SQL schema, ERD, README, and AI_LOG synchronized when changing data structures.

## Current folders
- `frontend/css`, `frontend/js`, `frontend/views`, `frontend/assets`
- `backend/routes`, `backend/services`, `backend/dao`, `backend/middleware`, `backend/config`, `backend/logs`
- `database`, `docs`

## Milestone boundaries
- Milestone 1: static SPA and draft ERD only; no frontend-to-backend integration.
- Milestone 2: MySQL schema/export/sample data and PDO DAO CRUD for at least five entities.
- Milestone 3: services, FlightPHP routes, JSON status codes, OpenAPI/Swagger and backend external API. Routes call services only; services own validation and business rules; services are the only layer that calls DAOs.
- Milestone 4: authentication, authorization, middleware, logging and connected frontend.
