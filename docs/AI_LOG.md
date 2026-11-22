# AI usage log

## Milestone 1 — Initial setup, static frontend and database planning

### AI tools and assisted work
- ChatGPT (GPT-6): assisted with project scope, initial folder structure, static SPA implementation, draft ERD and README content.
- The generated prototype includes frontend navigation and browser-local demo CRUD interactions; no backend integration was generated for this milestone.

### Important prompts / tasks
- Analyze the university project requirements and identify the Milestone 1 deliverables.
- Recommend a project topic that supports at least five relational entities, CRUD, roles and a meaningful external API feature.
- Create the StudyFlow Milestone 1 scaffold, static SPA, draft ERD and README.

### AI errors / limitations to verify and document
The course requires at least two examples per milestone of cases where AI was wrong or produced poor code, how the issue was detected, and how it was fixed. The student must complete this section with two genuine examples discovered during code review/testing. Do not invent examples. Suggested review checks (not claims that errors have already occurred):
1. Test whether every form validates required fields and whether malformed input can break rendering; record any actual defect and correction.
2. Verify entity relationships and ownership rules in the ERD against the intended application behavior; record any actual inconsistency and correction.

### Student verification record
- [ ] I reviewed and can explain the HTML entry point and SPA navigation.
- [ ] I tested the CRUD flows and responsive layout on desktop and mobile.
- [ ] I reviewed the ERD relationships and key/foreign-key choices.
- [ ] I completed two genuine AI-error examples with detection and fixes.
- [ ] I updated this log before submitting the milestone.

## Milestone 2 — Database and DAO layer

### AI tools and assistance
- ChatGPT (GPT-6): reviewed the existing project structure and milestone specification; generated the MySQL schema/export, sample data, PDO connection factory, reusable BaseDAO, and six entity DAO classes; updated schema documentation and this repository's AGENTS.md context file.
- Important prompt: “Continue developing my StudyFlow university Web Programming project. First help me test and verify the complete Milestone 1 implementation, identify and fix any defects, and then begin Milestone 2 according to the original university PDF requirements: implement the MySQL database with at least five entities, primary and foreign keys, sample data, SQL export, and PHP PDO DAO classes with prepared-statement CRUD operations and a reusable base DAO.”

### Verification and corrections
- The existing JavaScript files pass Node.js syntax checking. PHP 8.4 is available, but this environment has PDO without the `pdo_mysql` driver and has no MySQL client/server, so live database import and integration tests cannot be run here. PHP syntax checks are run on all DAO/config files; SQL must be imported and exercised in the student's MySQL environment before submission.
- The first Milestone 1 draft did not include the required root `AGENTS.md`; it has now been added based on the AI-usage instructions on page 5 of the project specification.
- The original draft ERD used generic category labels in the frontend and did not enforce same-owner foreign-key consistency in the physical schema. The Milestone 2 schema now uses composite owner foreign keys for courses/categories referenced by assignments, notes and sessions.
- Error/correction 1: The first generated SQL seed reused a commonly seen bcrypt-looking string, but `password_verify('password', $hash)` returned `false`. This was detected with PHP's password verification function; the hash was replaced with a fresh `password_hash('password', PASSWORD_DEFAULT)` result, and `password_verify` then returned `true`.
- Error/correction 2: The initial composite foreign-key draft used `ON DELETE SET NULL` for `(user_id, category_id)` and `(user_id, course_id)`. MySQL would attempt to null both columns, but `user_id` is `NOT NULL`. This was detected while reviewing the composite FK semantics; the affected relationships now use `ON DELETE RESTRICT`, and the schema documentation explains that dependent references must be cleared/reassigned before deletion.

### Student verification checklist
- [ ] Import `database/studyflow.sql` into a local MySQL 8+ instance.
- [ ] Copy `backend/config/config.example.php` to `backend/config/config.php` and enter local credentials.
- [ ] Verify the sample account password hash and change demo credentials before any deployment.
- [ ] Run create/read/update/delete tests for all six DAOs against local MySQL.
- [ ] Add at least two real AI-output issue/correction examples from your own review.
