# StudyFlow draft Entity-Relationship Diagram

The draft has six core entities. Each user owns their courses, assignments, sessions, notes and categories. Course, assignment, session and note records are associated with their owning user to support authorization and data isolation. Category links are optional so an item can exist before categorization.

```mermaid
erDiagram
    USERS ||--o{ COURSES : owns
    USERS ||--o{ ASSIGNMENTS : creates
    USERS ||--o{ STUDY_SESSIONS : records
    USERS ||--o{ NOTES : writes
    USERS ||--o{ CATEGORIES : defines
    COURSES ||--o{ ASSIGNMENTS : contains
    COURSES ||--o{ STUDY_SESSIONS : organizes
    COURSES ||--o{ NOTES : contextualizes
    CATEGORIES o|--o{ ASSIGNMENTS : classifies
    CATEGORIES o|--o{ NOTES : classifies

    USERS {
      int user_id PK
      varchar full_name
      varchar email UK
      varchar password_hash
      enum role
      datetime created_at
    }
    COURSES {
      int course_id PK
      int user_id FK
      varchar course_name
      varchar course_code
      varchar instructor
      varchar semester
      int progress
      datetime created_at
    }
    ASSIGNMENTS {
      int assignment_id PK
      int user_id FK
      int course_id FK
      int category_id FK
      varchar title
      text description
      date due_date
      enum priority
      enum status
      datetime created_at
    }
    STUDY_SESSIONS {
      int session_id PK
      int user_id FK
      int course_id FK
      varchar title
      datetime start_at
      datetime end_at
      int duration_minutes
      text reflection
      datetime created_at
    }
    NOTES {
      int note_id PK
      int user_id FK
      int course_id FK
      int category_id FK
      varchar title
      longtext body
      datetime created_at
      datetime updated_at
    }
    CATEGORIES {
      int category_id PK
      int user_id FK
      varchar name
      varchar color
      datetime created_at
    }
```

## Relationship and integrity notes
- One user owns many courses, assignments, study sessions, notes and categories.
- One course can be associated with many assignments, study sessions and notes.
- One category can classify many assignments and notes; category references are nullable.
- Use `ON DELETE CASCADE` for user-owned records only if the intended account-deletion policy is confirmed. For course deletion, decide whether to restrict deletion or set dependent course references to `NULL`.
- Enforce unique email addresses and validate that referenced records belong to the same user. Cross-owner consistency is an application-level service rule (or can be strengthened with composite keys).
- This is a draft for Milestone 1; physical SQL implementation belongs to Milestone 2.
