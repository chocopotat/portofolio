# Database Setup Verification Checklist ✅

Use this checklist to verify all components are properly installed and configured.

## Pre-Installation Checklist

- [ ] Node.js 18+ installed (`node --version`)
- [ ] npm installed (`npm --version`)
- [ ] PostgreSQL 13+ available
- [ ] Code editor with TypeScript support (VS Code, etc.)

## Installation Verification

```bash
# Step 1: Install all dependencies
npm install

# Expected output: ✓ packages installed
```

- [ ] All packages installed without errors
- [ ] `node_modules/` directory created
- [ ] `package-lock.json` updated

## Configuration Verification

```bash
# Step 2: Create .env.local
cp .env.example .env.local
```

**Required in .env.local:**
```env
DATABASE_URL=postgresql://user:password@localhost:5432/vibe_coding_db
NODE_ENV=development
```

- [ ] `.env.local` file created
- [ ] `DATABASE_URL` points to valid PostgreSQL database
- [ ] Database exists and is accessible

## Database Connection Test

```bash
# Step 3: Test connection using Drizzle Studio
npm run db:studio
```

Expected: Web browser opens to `http://localhost:3000` showing database UI

- [ ] Drizzle Studio launches successfully
- [ ] Can connect to PostgreSQL database
- [ ] No connection timeout errors

## Schema Generation & Migration

```bash
# Step 4: Generate initial migrations
npm run db:generate

# Step 5: Apply migrations to database
npm run db:migrate
```

- [ ] `src/lib/db/migrations/` folder contains migration file(s)
- [ ] Migration file contains valid SQL
- [ ] Migrations applied without errors
- [ ] PostgreSQL tables created:
  - [ ] `users` table exists
  - [ ] `sessions` table exists
  - [ ] `projects` table exists
  - [ ] `agent_executions` table exists

## Table Structure Verification

In PostgreSQL or Drizzle Studio, verify each table:

### Users Table
```sql
SELECT * FROM users LIMIT 1;
```
- [ ] Columns: id, email, name, avatar_url, is_active, created_at, updated_at
- [ ] Primary key: id (UUID)
- [ ] Constraint: email UNIQUE

### Sessions Table
```sql
SELECT * FROM sessions LIMIT 1;
```
- [ ] Columns: id, user_id, session_token, provider, expires_at, created_at
- [ ] Foreign key: user_id → users(id)
- [ ] Constraint: session_token UNIQUE

### Projects Table
```sql
SELECT * FROM projects LIMIT 1;
```
- [ ] Columns: id, user_id, name, description, repository, created_at, updated_at
- [ ] Foreign key: user_id → users(id)

### Agent Executions Table
```sql
SELECT * FROM agent_executions LIMIT 1;
```
- [ ] Columns: id, project_id, agent_type, prompt, output, status, tokens_used, created_at
- [ ] Foreign key: project_id → projects(id)
- [ ] ENUM types: agent_type, execution_status

## Seed Data Verification

```bash
# Step 6: Populate sample data
npm run db:seed
```

Expected output:
```
🌱 Starting database seed...
✅ Seed completed successfully
   - Created user: [UUID]
   - Created project: [UUID]
```

- [ ] Seed completed without errors
- [ ] Sample user created in database
- [ ] Sample project created and linked to user
- [ ] Sample session created with expiration time

Verify data:
```sql
SELECT COUNT(*) FROM users;      -- Should be >= 1
SELECT COUNT(*) FROM projects;   -- Should be >= 1
SELECT COUNT(*) FROM sessions;   -- Should be >= 1
```

- [ ] User count: >= 1
- [ ] Project count: >= 1
- [ ] Session count: >= 1

## TypeScript & Type Safety Verification

```bash
# Step 7: Type check
npx tsc --noEmit
```

- [ ] No TypeScript compilation errors
- [ ] Path aliases working (`@/lib/db`, `@/types`)
- [ ] All imports resolve correctly

## Code Pattern Verification

### Check Query Builders
- [ ] File exists: `src/lib/db/query-builders.ts`
- [ ] Contains: `userQueries`, `projectQueries`, `sessionQueries`, `agentExecutionQueries`
- [ ] Each query is exported and typed

### Check Schemas
- [ ] File exists: `src/lib/db/schema/index.ts`
- [ ] Central exports for: users, sessions, projects, agents
- [ ] Type inference: `User`, `NewUser`, `Session`, etc.

### Check Types
- [ ] File exists: `src/types/database.ts`
- [ ] Contains Zod schemas: `userInsertSchema`, `projectSelectSchema`, etc.
- [ ] All schemas have validation rules

### Check Example API
- [ ] File exists: `src/app/api/users/route.ts`
- [ ] Contains POST and GET handlers
- [ ] Uses Zod validation
- [ ] Proper error handling

## Documentation Verification

- [ ] File exists: `DATABASE_SETUP.md` (complete guide)
- [ ] File exists: `src/lib/db/README.md` (quick reference)
- [ ] File exists: `DATABASE_FOUNDATION.md` (implementation summary)

## Running Example Operations

```typescript
// Test import
import { db } from "@/lib/db";
import { userQueries } from "@/lib/db/query-builders";

// Test fetch (should work if seed data exists)
const user = await userQueries.findByEmail("dev@vibecoding.ai");
console.log(user?.id);  // Should print UUID
```

- [ ] Imports work without errors
- [ ] Query execution successful
- [ ] Data retrieved correctly

## Environment Integration

- [ ] TypeScript path aliases configured
- [ ] Drizzle ORM types installed
- [ ] Zod validation available
- [ ] Can run `npm run build` without errors
- [ ] Can run `npm run dev` without database errors

## Advanced Checks (Optional)

```bash
# View database schema
npm run db:studio

# Generate new migration
npm run db:generate

# Check for schema drift
npm run db:push --dry
```

- [ ] Drizzle Studio shows all 4 tables
- [ ] Migration generation works
- [ ] No schema drift detected

---

## Troubleshooting Guide

### Issue: "Cannot find module 'drizzle-orm'"
- [ ] Run `npm install`
- [ ] Run `npm install --save-dev drizzle-kit`
- [ ] Clear node_modules: `rm -rf node_modules && npm install`

### Issue: "DATABASE_URL is not set"
- [ ] Create `.env.local` file
- [ ] Verify `DATABASE_URL` is set correctly
- [ ] Check PostgreSQL is running

### Issue: "Connection refused" to PostgreSQL
- [ ] Start PostgreSQL service
- [ ] Verify DATABASE_URL matches PostgreSQL credentials
- [ ] Test with `psql` command-line tool

### Issue: "Tables don't exist"
- [ ] Run `npm run db:generate`
- [ ] Run `npm run db:migrate`
- [ ] Check migration file in `src/lib/db/migrations/`

### Issue: TypeScript errors
- [ ] Run `npx tsc --noEmit` to see all errors
- [ ] Check path aliases in `tsconfig.json`
- [ ] Verify imports use correct paths

---

## Final Verification Command

Run this complete verification:

```bash
#!/bin/bash
set -e

echo "🔍 Database Setup Verification"
echo "================================"

echo "1️⃣  Checking Node.js..."
node --version

echo "2️⃣  Checking npm packages..."
npm list drizzle-orm postgres zod | head -5

echo "3️⃣  Checking TypeScript..."
npx tsc --noEmit

echo "4️⃣  Checking migrations..."
ls -la src/lib/db/migrations/ | wc -l

echo "5️⃣  Testing database connection..."
npm run db:studio &
sleep 3
kill %1

echo ""
echo "✅ All checks passed! Database foundation is ready."
```

---

## Success Criteria ✅

Once ALL items in this checklist are verified:

✅ Database is properly configured  
✅ All tables created with correct schema  
✅ Sample data seeded  
✅ TypeScript types are strict  
✅ Query builders are functional  
✅ Example API route works  
✅ Documentation is comprehensive  

**Status**: Ready for Phase 2 - Authentication & Agent Engine

---

**Last Updated**: September 2026  
**Maintainer**: Vibe Development Team
