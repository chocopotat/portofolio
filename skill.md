# Vibe Coder Capabilities & Skill Catalog

This file defines the domain capabilities, technical proficiencies, patterns, and guidelines required of any vibe coder or AI agent operating in this codebase.

---

## 1. Tech Stack Mastery Matrix

| Domain | Required Standard | Preferred Libraries / Tools |
| :--- | :--- | :--- |
| **Frontend** | React 19 / Next.js 15 (App Router) | Tailwind CSS, Shadcn UI, Framer Motion, Lucide Icons |
| **Backend** | Node.js / Python FastAPI | Express, Hono, Pydantic, Zod |
| **Database** | Relational / Document Store | PostgreSQL, Redis, Drizzle ORM, Prisma |
| **Testing** | Unit, Integration & E2E | Vitest, Jest, Playwright, Testing Library |
| **DevOps** | Containerization & Cloud | Docker, Vercel, GitHub Actions, Cloudflare Workers |

---

## 2. Core Vibe Coding Skills & Behavioral Patterns

### 🧠 2.1 Prompt Engineering & Steering
- **Context Priming:** Always pass small, target files instead of entire repositories.
- **Negative Constraints:** Express explicitly what *not* to do (e.g., "Do NOT use external UI libraries other than Shadcn").
- **Iterative Refinement:** Prompt in short 1-task bursts instead of massive multi-feature prompts.

### 🛡️ 2.2 Defensive Code Generation
- **Schema Validation:** Always validate incoming network requests using `Zod` or `Pydantic`.
- **Graceful Failures:** Enclose external API interactions in try-catch blocks with typed error responses.
- **No Magic Numbers:** Define constant files or environment variables for all configuration values.

### 🔍 2.3 Code Audit & Refactoring Protocol
- **Dead Code Elimination:** Regularly prune unused imports and orphaned code blocks.
- **Type Safety Checks:** Run `tsc --noEmit` after every generation step.
- **Performance Profiling:** Monitor bundle size impact when adding third-party packages.
