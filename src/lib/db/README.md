# Database Setup & Drizzle ORM Foundation

## 📋 Overview

This directory contains the database layer setup using **Drizzle ORM** with PostgreSQL.

### Directory Structure

```
src/lib/db/
├── index.ts                    # Database client singleton & exports
├── migrate.ts                  # Migration runner script
├── query-builders.ts           # Typed query patterns
├── seed.ts                     # Database seeder for development
├── schema/
│   ├── index.ts                # Central schema exports
│   ├── users.ts                # User entity
│   ├── sessions.ts             # Authentication sessions
│   ├── projects.ts             # User projects/workspaces
│   └── agents.ts               # Agent execution logs
└── migrations/                 # Auto-generated migration files
```

---

## 🚀 Quick Start

### 1. Install Dependencies

```bash
npm install
```

Required packages:
- `drizzle-orm` - ORM library
- `drizzle-kit` - CLI for migrations
- `postgres` - PostgreSQL driver
- `zod` - Schema validation

### 2. Configure Environment

Copy and update `.env.local`:

```bash
cp .env.example .env.local
```

**Required variables:**

```env
DATABASE_URL=postgresql://user:password@localhost:5432/vibe_coding_db
NODE_ENV=development
```

### 3. Generate & Run Migrations

```bash
# Generate migration files from schema changes
npm run db:generate

# Push migrations to database
npm run db:push

# Or run migrations programmatically
npm run db:migrate

# Seed database with sample data
npm run db:seed
```

---

## 📊 Schema Design

### Core Tables

#### **users**
- Primary entity for user accounts
- Fields: `id`, `email`, `name`, `avatarUrl`, `isActive`, timestamps

#### **sessions**
- OAuth/auth session management
- Foreign key: `userId` → users
- Fields: `id`, `sessionToken`, `provider`, `expiresAt`

#### **projects**
- User workspaces/projects
- Foreign key: `userId` → users
- Fields: `id`, `name`, `description`, `repository`

#### **agent_executions**
- Tracks autonomous agent runs
- Foreign key: `projectId` → projects
- Fields: `id`, `agentType`, `prompt`, `output`, `status`, `tokensUsed`

---

## 🔧 Usage Patterns

### Using the Database Client

```typescript
import { db } from "@/lib/db";

// Insert
const user = await db.insert(users).values({
  email: "user@example.com",
  name: "John Doe",
}).returning();

// Query
const fetchedUser = await db.query.users.findFirst({
  where: eq(users.email, "user@example.com"),
});

// Update
await db.update(users)
  .set({ name: "Jane Doe" })
  .where(eq(users.id, userId));

// Delete
await db.delete(users).where(eq(users.id, userId));
```

### Using Query Builders

Pre-built, typed query patterns are available in `query-builders.ts`:

```typescript
import { userQueries, projectQueries } from "@/lib/db/query-builders";

// Fetch user by email
const user = await userQueries.findByEmail("user@example.com");

// Fetch user's projects
const projects = await projectQueries.findByUserId(userId);

// Create new project
const newProject = await projectQueries.create({
  userId,
  name: "My Project",
  description: "Project description",
});
```

### Type Safety with Zod

All database operations use Zod schemas for validation:

```typescript
import { userInsertSchema, projectSelectSchema } from "@/types/database";

// Validate incoming data
const validUser = userInsertSchema.parse(userData);
const validProject = projectSelectSchema.parse(dbRecord);
```

---

## 🛡️ Best Practices

### 1. **Explicit Typing**
- Always use Drizzle's `$inferSelect` and `$inferInsert` types
- Export types from schema files for reuse

### 2. **Schema Validation**
- Use Zod schemas for all input validation
- Validate before database operations

### 3. **Query Builders**
- Keep complex queries in `query-builders.ts`
- Follow DRY principle to prevent duplication

### 4. **File Size**
- Keep individual schema files under 100 lines
- Keep query builders focused on single domain

### 5. **Error Handling**
- Wrap database calls in try-catch blocks
- Use typed error responses in API routes

---

## 📝 Migration Workflow

### Creating New Schemas

1. Create schema file in `src/lib/db/schema/`
2. Export table and types
3. Add to `src/lib/db/schema/index.ts`

Example:

```typescript
// src/lib/db/schema/new-table.ts
import { pgTable, varchar, timestamp, uuid } from "drizzle-orm/pg-core";

export const newTable = pgTable("new_table", {
  id: uuid("id").primaryKey().defaultRandom(),
  name: varchar("name", { length: 255 }).notNull(),
  createdAt: timestamp("created_at").defaultNow().notNull(),
});

export type NewTableSelect = typeof newTable.$inferSelect;
export type NewTableInsert = typeof newTable.$inferInsert;
```

### Auto-generating Migrations

```bash
# After schema changes
npm run db:generate

# Review generated migration
cat src/lib/db/migrations/TIMESTAMP_migration.sql

# Push to database
npm run db:push
```

---

## 🧪 Seeding Development Data

Run seeder to populate test data:

```bash
npm run db:seed
```

Modify `src/lib/db/seed.ts` to customize sample data.

---

## 🔗 References

- [Drizzle ORM Docs](https://orm.drizzle.team/)
- [PostgreSQL Docs](https://www.postgresql.org/docs/)
- [TypeScript Strict Mode](https://www.typescriptlang.org/tsconfig#strict)
