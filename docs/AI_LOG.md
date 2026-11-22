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

## Milestone 3 — Business logic, REST API, OpenAPI and external API

### AI tools and assisted work
- ChatGPT (GPT-6): assisted with six entity services, service factory, FlightPHP route registration, Open Library service, OpenAPI 3.0 specification, Swagger UI page, local router and tests.

### Important prompts / tasks
- Implement the Milestone 3 requirements from the supplied course specification while preserving the Milestone 2 DAO architecture.
- Create one service per database entity; ensure routes call services and services call DAOs.
- Add CRUD routes with JSON responses and status codes, Swagger/OpenAPI documentation, and a fault-tolerant Open Library backend integration.

### AI errors / limitations found and corrected during review
1. **Service validation rule construction:** an automated edit initially generated malformed PHP associative-array entries for some `fieldRules()` definitions (the owner field was inserted as a numeric array item). PHP lint did not detect this semantic mistake. Reviewing the rule arrays showed the owner field would not be validated as intended; the definitions were corrected and service smoke tests were added.
2. **Password-hash input trust:** the first UserService draft accepted a caller-supplied `password_hash` field through the DAO allowlist. Code review identified that this would let a client choose a stored hash instead of supplying a password. The service now removes caller-supplied hashes and generates hashes only from a password field; a smoke test verifies the stored hash and confirms the plaintext is not passed to the DAO.

### Verification record
- [x] PHP syntax check passed for all backend PHP files.
- [x] `tests/service_smoke_test.php` passed (password hashing, email/password validation, and course progress validation).
- [x] OpenAPI YAML parsed successfully with the available YAML parser; 14 paths were found.
- [ ] Install Composer dependencies and test FlightPHP routes on the developer machine.
- [ ] Test SQL-backed CRUD against MySQL with `pdo_mysql` using `tests/dao_smoke_test.php`.
- [ ] Test live Open Library connectivity and the provider-timeout/error path.
- [ ] Student must review the implementation, understand it, and complete the local verification before submission.
