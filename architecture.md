# System Architecture & Design Guidelines

## 1. System Overview
The architecture is structured around **Modular Monolith / Domain-Driven Design (DDD)** principles to maximize AI code navigation while ensuring high scalability and maintenance.

---

## 2. Architectural Layers

```
+-------------------------------------------------------+
|                 Presentation Layer                    |
|       (Next.js App Router, React Components, UI)     |
+-------------------------------------------------------+
                           │
                           ▼
+-------------------------------------------------------+
|                  Application Layer                    |
|        (API Routes, Use Cases, Controller Layer)      |
+-------------------------------------------------------+
                           │
                           ▼
+-------------------------------------------------------+
|                    Domain Layer                       |
|         (Core Business Logic, Entities, Rules)        |
+-------------------------------------------------------+
                           │
                           ▼
+-------------------------------------------------------+
|                 Infrastructure Layer                  |
|     (ORM, Database, External APIs, Auth, Storage)     |
+-------------------------------------------------------+
```

---

## 3. Key Design Principles for AI-Native Coding

1. **Explicit Typing:** Strict TypeScript / Python Pydantic models across all boundaries. Avoid `any` or `unknown`.
2. **File & Folder Structure Standard:**
   ```
   src/
   ├── app/                # Next.js App Router pages & API handlers
   ├── components/         # Reusable UI components (Atomic design)
   │   ├── ui/             # Base components (Shadcn UI)
   │   └── features/       # Feature-specific components
   ├── lib/                # Shared utilities, DB client instantiations
   ├── modules/            # Business domain modules (DDD)
   │   ├── auth/
   │   ├── billing/
   │   └── project/
   └── types/              # Global TypeScript interfaces
   ```
3. **Single Responsibility Files:** Keep files under **200 lines of code**. Large files increase LLM context rot and hallucination rates.
4. **Pure Functions & Immutability:** Prefer pure functions with predictable outputs to simplify agent automated testing.

---

## 4. Data Flow & State Management
- **Server State:** React Server Components (RSC) + TanStack Query (React Query) for client caching.
- **Client State:** Zustand for lightweight client state management.
- **Database Access:** Prisma / Drizzle ORM with strict schema migration scripts.

---

## 5. Security Architecture
- **Authentication:** OAuth2 / OIDC via NextAuth.js or Supabase Auth.
- **Authorization:** Role-Based Access Control (RBAC) enforced at middleware and application service levels.
- **API Security:** Rate limiting via Redis sliding window, input sanitization via Zod schemas.
