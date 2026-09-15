# Database & Drizzle ORM Implementation Guide

## 📚 Table of Contents

1. [Overview](#overview)
2. [Architecture & Design](#architecture--design)
3. [File Structure](#file-structure)
4. [Installation & Setup](#installation--setup)
5. [Schema Definitions](#schema-definitions)
6. [Query Patterns](#query-patterns)
7. [Type Safety](#type-safety)
8. [Common Operations](#common-operations)
9. [Best Practices](#best-practices)
10. [Troubleshooting](#troubleshooting)

---

## Overview

This foundation establishes a **production-ready database layer** using:

- **Drizzle ORM**: Type-safe, zero-runtime query builder for TypeScript/JavaScript
- **PostgreSQL**: Robust, ACID-compliant relational database
- **Zod**: Runtime schema validation for type safety at boundaries
- **TypeScript**: Full type inference without manual declarations

### Why Drizzle ORM?

✅ **Zero Runtime**: Compiles to raw SQL queries  
✅ **Type-Safe**: Full IntelliSense and compile-time checking  
✅ **Minimal Abstraction**: SQL is always visible and controllable  
✅ **Migrations**: Automated schema versioning via `drizzle-kit`  
✅ **Relations**: Built-in support for joins and relationships  

---

## Architecture & Design

### Layered Database Architecture

```
┌─────────────────────────────┐
│   API Routes / Controllers  │  ← src/app/api/
├─────────────────────────────┤
│   Query Builders (DRY)      │  ← src/lib/db/query-builders.ts
├─────────────────────────────┤
│   Database Client Instance  │  ← src/lib/db/index.ts
├─────────────────────────────┤
│   Schema Definitions        │  ← src/lib/db/schema/
├─────────────────────────────┤
│   PostgreSQL Database       │  ← Local or Cloud
└─────────────────────────────┘
```

### Design Principles

1. **Single Responsibility**: Each schema/query file has ONE domain
2. **Type Safety**: Zod schemas + TypeScript inference
3. **DRY Pattern**: Reusable query builders prevent duplication
4. **Explicit Errors**: No silent failures, typed error handling
5. **Migrations**: All schema changes tracked in version control

---

## File Structure

```
src/
├── lib/db/
│   ├── index.ts                    # Database client singleton
│   ├── migrate.ts                  # Migration runner
│   ├── query-builders.ts           # Domain-specific queries (DRY)
│   ├── utils.ts                    # Helper functions
│   ├── seed.ts                     # Sample data
│   ├── README.md                   # Database documentation
│   ├── schema/
│   │   ├── index.ts                # Central exports
│   │   ├── users.ts                # User entity & types
│   │   ├── sessions.ts             # Auth sessions
│   │   ├── projects.ts             # User projects
│   │   └── agents.ts               # Agent logs
│   └── migrations/
│       └── [auto-generated]        # Drizzle-kit output
├── types/
│   └── database.ts                 # Zod schemas & type aliases
└── app/api/
    └── users/
        └── route.ts                # Example API route
```

---

## Installation & Setup

### Step 1: Install Dependencies

```bash
npm install
```

### Step 2: Environment Configuration

Create `.env.local`:

```env
DATABASE_URL=postgresql://user:password@localhost:5432/vibe_coding_db
NODE_ENV=development
```

### Step 3: Generate Initial Schema

```bash
npm run db:generate
```

This creates the first migration in `src/lib/db/migrations/`.

### Step 4: Apply Migrations

```bash
npm run db:migrate
```

Or use Drizzle Kit directly:

```bash
npm run db:push
```

### Step 5: Seed Sample Data (Optional)

```bash
npm run db:seed
```

---

## Schema Definitions

### Core Entities Overview

#### Users Table
- **Purpose**: User accounts and profiles
- **Fields**:
  - `id` (UUID): Primary key
  - `email` (VARCHAR): Unique email
  - `name` (VARCHAR): Display name
  - `avatarUrl` (TEXT, nullable): Profile picture
  - `isActive` (BOOLEAN): Account status
  - `createdAt`, `updatedAt` (TIMESTAMP): Audit fields

#### Sessions Table
- **Purpose**: Authentication session management
- **Relationships**: FK → users.id (cascade delete)
- **Fields**:
  - `sessionToken`: Unique session identifier
  - `provider`: Auth provider (oauth, email, etc.)
  - `expiresAt`: Session expiration

#### Projects Table
- **Purpose**: User workspaces/projects
- **Relationships**: FK → users.id (cascade delete)
- **Fields**:
  - `name`, `description`, `repository`: Project metadata
  - `createdAt`, `updatedAt`: Timestamps

#### AgentExecutions Table
- **Purpose**: Track autonomous agent runs
- **Relationships**: FK → projects.id (cascade delete)
- **Fields**:
  - `agentType`: ENUM (architect, coder, qa, devops)
  - `prompt`: Input to agent
  - `output`: Agent response (nullable)
  - `status`: ENUM (pending, running, completed, failed)
  - `tokensUsed`: API token metrics

---

## Query Patterns

### Direct Database Access

```typescript
import { db } from "@/lib/db";
import { users, eq } from "@/lib/db/schema";

// SELECT
const user = await db.query.users.findFirst({
  where: eq(users.email, "user@example.com"),
});

// INSERT
await db.insert(users).values({
  email: "new@example.com",
  name: "New User",
});

// UPDATE
await db.update(users)
  .set({ name: "Updated Name" })
  .where(eq(users.id, userId));

// DELETE
await db.delete(users).where(eq(users.id, userId));
```

### Using Query Builders (Recommended)

Query builders in `src/lib/db/query-builders.ts` provide type-safe, 
reusable patterns:

```typescript
import { userQueries, projectQueries } from "@/lib/db/query-builders";

// These functions handle the query logic internally
const user = await userQueries.findByEmail("user@example.com");
const projects = await projectQueries.findByUserId(userId);
const newProject = await projectQueries.create({
  userId,
  name: "My Project",
});
```

### Advanced: Joins & Relations

```typescript
// Fetch user with their projects
const userWithProjects = await db.query.users.findFirst({
  where: eq(users.id, userId),
  with: {
    projects: true,  // Automatically joins projects table
  },
});
```

---

## Type Safety

### Zod Runtime Validation

Always validate external input:

```typescript
import { userInsertSchema } from "@/types/database";

// This validates and infers the type
const validatedData = userInsertSchema.parse(requestBody);
// Type: { id?: string, email: string, name: string, ... }
```

### Type Inference from Drizzle

Never manually type database records:

```typescript
import { users } from "@/lib/db/schema";

// Automatic type inference
type User = typeof users.$inferSelect;      // For SELECT queries
type NewUser = typeof users.$inferInsert;   // For INSERT queries
```

### API Response Types

```typescript
import type { UserSelect } from "@/types/database";

// API response is fully typed
async function getUser(id: string): Promise<UserSelect> {
  return userQueries.findById(id);
}
```

---

## Common Operations

### Creating a User

```typescript
import { userInsertSchema } from "@/types/database";
import { userQueries } from "@/lib/db/query-builders";

async function createUser(data: unknown) {
  // Validate input
  const validated = userInsertSchema.parse(data);

  // Create in database
  const user = await userQueries.create(validated);

  return user; // Fully typed as UserSelect
}
```

### Fetching with Filters

```typescript
import { db, users } from "@/lib/db";
import { and, or, gt } from "drizzle-orm";

// Complex WHERE clause
const results = await db.query.users.findMany({
  where: and(
    eq(users.isActive, true),
    gt(users.createdAt, new Date("2024-01-01"))
  ),
});
```

### Pagination

```typescript
import { db, users } from "@/lib/db";

const page = 1;
const limit = 20;
const offset = (page - 1) * limit;

const users = await db.query.users.findMany({
  limit,
  offset,
});
```

### Transactions (Atomic Operations)

```typescript
import { withTransaction } from "@/lib/db/utils";

await withTransaction(async (tx) => {
  // All operations here are atomic
  await tx.insert(users).values({ ... });
  await tx.update(projects).set({ ... }).where(...);
  // If any operation fails, all are rolled back
});
```

---

## Best Practices

### 1. Always Use Query Builders

❌ **Avoid**: Repeating the same query in multiple files

```typescript
// DON'T DO THIS IN MULTIPLE FILES
const user = await db.query.users.findFirst({
  where: eq(users.id, id),
});
```

✅ **Do This**: Create a query builder once

```typescript
// src/lib/db/query-builders.ts
export const userQueries = {
  findById: (id: string) => db.query.users.findFirst({
    where: eq(users.id, id),
  }),
};

// Use everywhere
import { userQueries } from "@/lib/db/query-builders";
const user = await userQueries.findById(id);
```

### 2. Validate at Boundaries

All external input must be validated:

```typescript
// API Route
export async function POST(request: Request) {
  const body = await request.json();
  const validated = userInsertSchema.parse(body); // Throws if invalid
  await userQueries.create(validated);
}
```

### 3. Explicit Error Handling

```typescript
export async function getUser(id: string) {
  try {
    const user = await userQueries.findById(id);
    if (!user) {
      return { error: "User not found", status: 404 };
    }
    return { data: user, status: 200 };
  } catch (error) {
    return { error: "Database error", status: 500 };
  }
}
```

### 4. Keep Schema Files Small

Each schema file should handle ONE domain (~100 lines max):

```typescript
// ✅ Good: Focused schema
// src/lib/db/schema/users.ts - Only user-related
export const users = pgTable("users", { ... });

// ✅ Good: Separate concerns
// src/lib/db/schema/sessions.ts - Only session-related
export const sessions = pgTable("sessions", { ... });
```

### 5. Use Timestamps Consistently

All entities should track creation and updates:

```typescript
export const users = pgTable("users", {
  id: uuid("id").primaryKey().defaultRandom(),
  // ... other fields ...
  createdAt: timestamp("created_at", { withTimezone: true })
    .defaultNow()
    .notNull(),
  updatedAt: timestamp("updated_at", { withTimezone: true })
    .defaultNow()
    .$onUpdate(() => new Date())
    .notNull(),
});
```

---

## Troubleshooting

### Issue: `Cannot find module 'drizzle-orm'`

**Solution**: Ensure dependencies are installed

```bash
npm install
npm install --save-dev drizzle-kit tsx
```

### Issue: Migrations not applying

**Ensure DATABASE_URL is correct:**

```bash
# Test connection
npx drizzle-kit studio
```

### Issue: Type errors in schema files

**Ensure TypeScript is strict:**

```bash
# Run type checker
npx tsc --noEmit
```

### Issue: Circular imports

**Pattern**: Always import from `@/lib/db/schema`, not individual files

```typescript
// ❌ WRONG
import { users } from "@/lib/db/schema/users";

// ✅ CORRECT
import { users } from "@/lib/db/schema";
```

---

## Next Steps

1. ✅ Foundation created - Move to Phase 2
2. 📖 Read [/src/lib/db/README.md](/src/lib/db/README.md) for implementation details
3. 🚀 Run `npm run db:generate && npm run db:migrate` to initialize
4. 🧪 Execute `npm run db:seed` for sample data
5. 💻 Create API routes using patterns from `src/app/api/users/route.ts`

---

**Status**: Foundation Ready ✅  
**Last Updated**: September 2026  
**Maintainer**: Vibe Development Team
