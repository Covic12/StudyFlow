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

See [`database/ERD.md`](database/ERD.md) for the draft ERD, attributes and relationship notes.

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

## Future backend configuration
Copy `backend/config/config.example.php` to `backend/config/config.php` when backend development begins, then enter local database credentials and a strong JWT secret. The real configuration file is excluded from Git. Do not commit secrets.

## Roles
- Student: manages their own academic records.
- Administrator: has administrative user-management privileges.

## External API
Planned integration: [Open Library Search API](https://openlibrary.org/developers/api). It will support book discovery in the Learning Resources page. The final backend implementation must set timeouts, handle API errors, log failures and return a user-friendly response if the provider is unavailable.

## API documentation and deployment
Not available in Milestone 1. The OpenAPI/Swagger documentation and public deployment will be added in later milestones.

## Test accounts
No real accounts exist in this static prototype. The interface uses demonstration identity data only. Test accounts will be created and documented when authentication is implemented.

## Git milestone workflow
Create and submit a separate branch for each milestone (`milestone1`, `milestone2`, etc.). For Milestone 1, create a pull request from `milestone1`, add the required reviewer `ibu-web-programming2001`, and do **not** merge the milestone branch into `main` yourself. Follow the course LMS submission instructions.

## AI usage documentation
See [`docs/AI_LOG.md`](docs/AI_LOG.md). Review, test and understand every generated file before submission; document the AI assistance and corrections in the milestone log.
