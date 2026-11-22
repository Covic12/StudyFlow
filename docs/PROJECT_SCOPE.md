# StudyFlow project scope — Milestone 1

## Purpose
A student-focused workspace for organizing courses, coursework, study sessions, notes and learning resources.

## Roles
- Student: create and manage own academic records.
- Administrator: manage platform users and oversee the platform. Backend authorization must enforce role permissions.

## Included modules
Dashboard; courses; assignments; study planner; notes; categories; learning resources; profile; settings; admin panel; login and registration screens.

## External API placement
Learning Resources page. Planned provider: Open Library Search API. In the final architecture the backend service calls the provider, applies timeout/error handling and exposes a documented endpoint to the frontend.

## Explicitly out of scope for Milestone 1
PHP routes, services, DAOs, real MySQL schema creation, JWT, real authentication, middleware, logs, Swagger, deployment and frontend-to-backend AJAX integration. These are later milestone work.
