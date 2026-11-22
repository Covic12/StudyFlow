# StudyFlow relational schema (Milestone 2)

Target: MySQL 8.0+, InnoDB, `utf8mb4`. Import `database/studyflow.sql` to create the database, six tables, constraints, indexes and sample records.

## Tables

| Table | Primary key | Important foreign keys | Purpose |
|---|---|---|---|
| `users` | `user_id` | — | Student and administrator accounts |
| `courses` | `course_id` | `user_id → users` | Courses owned by a user |
| `categories` | `category_id` | `user_id → users` | Per-user labels for organizing work |
| `assignments` | `assignment_id` | `user_id → users`; `(user_id, course_id) → courses`; `(user_id, category_id) → categories` | Coursework and deadlines |
| `study_sessions` | `session_id` | `user_id → users`; `(user_id, course_id) → courses` | Time-bounded study records |
| `notes` | `note_id` | `user_id → users`; `(user_id, course_id) → courses`; `(user_id, category_id) → categories` | User-owned notes |

## Integrity decisions

- `users.email` is unique; passwords are stored only as password hashes.
- `courses` and `categories` have composite unique keys `(user_id, id)` so dependent records can use composite foreign keys to ensure that the course/category belongs to the same user as the dependent row.
- Course and category links on notes are nullable. Category links on assignments are nullable. User-owned data cascades when a user is deleted; deleting a course cascades its assignments and nulls optional course links on sessions/notes; deleting a course/category referenced by dependent rows is restricted; clear or reassign those links before deletion. This avoids an invalid composite `SET NULL` action that would also attempt to null the required owner ID.
- `CHECK` constraints validate progress, session time ordering and positive duration. Application-level validation remains necessary too.
- SQL uses InnoDB and utf8mb4. Import script drops and recreates the six tables, so it is intended for a fresh development database, not production data.

## Sample data

Two demo users are inserted (`alex@student.edu`, role `student`; `admin@studyflow.local`, role `admin`) plus sample courses, categories, assignments, sessions and notes. Both sample accounts use the development password `password` (hash generated with PHP `password_hash`). Replace sample credentials before any public deployment.

## ERD

See [`studyflow-erd.svg`](studyflow-erd.svg) and [`ERD.md`](ERD.md).
