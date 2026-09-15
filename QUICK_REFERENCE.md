# 🚀 QUICK REFERENCE - Database Commands

## Instant Setup (Copy & Paste)

### Step 1: Install Dependencies
```bash
npm install
```

### Step 2: Create Environment File
```bash
cp .env.example .env.local
```

Edit `.env.local`:
```env
DATABASE_URL=postgresql://user:password@localhost:5432/vibe_coding_db
NODE_ENV=development
```

### Step 3: Generate Migrations
```bash
npm run db:generate
```

### Step 4: Apply Migrations
```bash
npm run db:migrate
```

### Step 5: Seed Sample Data
```bash
npm run db:seed
```

### Step 6: View Database
```bash
npm run db:studio
```

---

## Available npm Scripts

### Database Commands
```bash
npm run db:generate    # Generate migration files
npm run db:migrate     # Run migrations
npm run db:push        # Push schema directly (alternative to migrate)
npm run db:studio      # Open Drizzle Studio UI
```

### Application Commands
```bash
npm run build          # Build for production
npm run dev            # Start development server
```

---

## Common Database Operations

### Insert Data
```typescript
import { db } from "@/lib/db";
import { users } from "@/lib/db/schema";

await db.insert(users).values({
  email: "user@example.com",
  name: "John Doe"
});
```

### Query Data
```typescript
import { db, users } from "@/lib/db";
import { eq } from "drizzle-orm";

const user = await db.query.users.findFirst({
  where: eq(users.email, "user@example.com")
});
```

### Using Query Builders (Recommended)
```typescript
import { userQueries } from "@/lib/db/query-builders";

const user = await userQueries.findByEmail("user@example.com");
```

### Update Data
```typescript
import { db, users } from "@/lib/db";
import { eq } from "drizzle-orm";

await db.update(users)
  .set({ name: "Jane Doe" })
  .where(eq(users.id, userId));
```

### Delete Data
```typescript
import { db, users } from "@/lib/db";
import { eq } from "drizzle-orm";

await db.delete(users).where(eq(users.id, userId));
```

---

## Database Schema Reference

### Users Table
| Field | Type | Notes |
|-------|------|-------|
| id | UUID | Primary Key |
| email | VARCHAR(255) | UNIQUE |
| name | VARCHAR(255) | Required |
| avatarUrl | TEXT | Nullable |
| isActive | BOOLEAN | Default: true |
| createdAt | TIMESTAMP | Auto-set |
| updatedAt | TIMESTAMP | Auto-update |

### Sessions Table
| Field | Type | Notes |
|-------|------|-------|
| id | UUID | Primary Key |
| userId | UUID | FK → users |
| sessionToken | VARCHAR(255) | UNIQUE |
| provider | VARCHAR(50) | Default: 'oauth' |
| expiresAt | TIMESTAMP | Session expiry |
| createdAt | TIMESTAMP | Auto-set |

### Projects Table
| Field | Type | Notes |
|-------|------|-------|
| id | UUID | Primary Key |
| userId | UUID | FK → users |
| name | VARCHAR(255) | Required |
| description | TEXT | Nullable |
| repository | VARCHAR(512) | Nullable |
| createdAt | TIMESTAMP | Auto-set |
| updatedAt | TIMESTAMP | Auto-update |

### Agent Executions Table
| Field | Type | Notes |
|-------|------|-------|
| id | UUID | Primary Key |
| projectId | UUID | FK → projects |
| agentType | ENUM | architect\|coder\|qa\|devops |
| prompt | TEXT | Required |
| output | TEXT | Nullable |
| status | ENUM | pending\|running\|completed\|failed |
| tokensUsed | INTEGER | Nullable |
| createdAt | TIMESTAMP | Auto-set |

---

## Import Statements Reference

### Database Client
```typescript
import { db } from "@/lib/db";
```

### Schema Tables
```typescript
import { users, sessions, projects, agentExecutions } from "@/lib/db/schema";
```

### Query Builders
```typescript
import { userQueries, projectQueries, sessionQueries, agentExecutionQueries } from "@/lib/db/query-builders";
```

### Type Definitions
```typescript
import type { User, NewUser, Project, NewProject, Session, AgentExecution, NewAgentExecution } from "@/lib/db/schema";
```

### Zod Schemas
```typescript
import { userInsertSchema, userSelectSchema, projectInsertSchema, projectSelectSchema } from "@/types/database";
```

### Drizzle Operators
```typescript
import { eq, and, or, gt, lt, inArray, like } from "drizzle-orm";
```

---

## Type Safety Examples

### Validate on Insert
```typescript
import { userInsertSchema } from "@/types/database";

// This validates AND infers type
const validated = userInsertSchema.parse(requestBody);

await db.insert(users).values(validated);
```

### Infer Select Type
```typescript
import { users } from "@/lib/db/schema";

// Type is automatically User
type User = typeof users.$inferSelect;

const user: User = await db.query.users.findFirst({...});
```

### Query with Types
```typescript
import { userQueries } from "@/lib/db/query-builders";
import type { User } from "@/lib/db/schema";

const user: User | undefined = await userQueries.findById(id);
```

---

## Error Handling Pattern

```typescript
export async function getUser(id: string) {
  try {
    const user = await userQueries.findById(id);
    
    if (!user) {
      return { error: "User not found", status: 404 };
    }
    
    return { data: user, status: 200 };
  } catch (error) {
    console.error("Database error:", error);
    return { error: "Failed to fetch user", status: 500 };
  }
}
```

---

## API Route Example

```typescript
// src/app/api/users/route.ts
import { db } from "@/lib/db";
import { users } from "@/lib/db/schema";
import { userInsertSchema } from "@/types/database";
import { eq } from "drizzle-orm";

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const validated = userInsertSchema.parse(body);
    
    const result = await db.insert(users).values(validated).returning();
    
    return new Response(JSON.stringify(result[0]), { 
      status: 201,
      headers: { "Content-Type": "application/json" }
    });
  } catch (error) {
    return new Response(JSON.stringify({ error: "Invalid request" }), { 
      status: 400,
      headers: { "Content-Type": "application/json" }
    });
  }
}

export async function GET() {
  const allUsers = await db.query.users.findMany();
  return new Response(JSON.stringify(allUsers), { 
    headers: { "Content-Type": "application/json" }
  });
}
```

---

## Transactions Example

```typescript
import { db } from "@/lib/db";
import { users, projects } from "@/lib/db/schema";

// Atomic operations
await db.transaction(async (tx) => {
  // If any fails, all rollback
  const user = await tx.insert(users).values({ 
    email: "new@example.com", 
    name: "New User" 
  }).returning();
  
  await tx.insert(projects).values({
    userId: user[0].id,
    name: "First Project"
  });
});
```

---

## Pagination Example

```typescript
const page = 1;
const limit = 20;
const offset = (page - 1) * limit;

const users = await db.query.users.findMany({
  limit,
  offset
});
```

---

## Complex Query Example

```typescript
import { and, or, gt, lt } from "drizzle-orm";

const results = await db.query.users.findMany({
  where: and(
    eq(users.isActive, true),
    or(
      eq(users.name, "John"),
      eq(users.name, "Jane")
    ),
    gt(users.createdAt, new Date("2024-01-01"))
  )
});
```

---

## Troubleshooting Commands

### Check TypeScript Errors
```bash
npx tsc --noEmit
```

### Reset Database (Dangerous!)
```bash
# Delete all migrations
rm -rf src/lib/db/migrations/*

# Start fresh
npm run db:generate
npm run db:migrate
```

### View Database Schema
```bash
npm run db:studio
```

### Check Dependencies
```bash
npm list drizzle-orm postgres zod typescript
```

---

## Environment Variables

Required in `.env.local`:
```env
DATABASE_URL=postgresql://user:password@localhost:5432/vibe_coding_db
NODE_ENV=development
```

Optional:
```env
OPENAI_API_KEY=sk_...
ANTHROPIC_API_KEY=...
DEEPSEEK_API_KEY=...
```

---

## Documentation Files

| File | Purpose |
|------|---------|
| `DATABASE_SETUP.md` | Complete guide |
| `src/lib/db/README.md` | Quick reference |
| `DATABASE_VERIFICATION.md` | Verification checklist |
| `README_DATABASE.md` | Overview |
| `INSTALLATION_GUIDE.md` | Setup guide |

---

## File Locations

```
Database Layer:     src/lib/db/
Schema Files:       src/lib/db/schema/
Type Definitions:   src/types/database.ts
Example API:        src/app/api/users/route.ts
Migrations:         src/lib/db/migrations/
Config:             drizzle.config.ts
```

---

## Quick Help

```bash
# I need to...

# Start from scratch
npm install && npm run db:generate && npm run db:migrate

# View my database
npm run db:studio

# Add a new table
# 1. Create file in src/lib/db/schema/
# 2. Add export to src/lib/db/schema/index.ts
# 3. Run: npm run db:generate && npm run db:migrate

# Check for errors
npx tsc --noEmit

# See all database records
npm run db:studio  # Then use the UI
```

---

**Last Updated**: September 2026  
**Status**: ✅ Ready to Use  
**Support**: See documentation files for detailed help
