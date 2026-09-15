# Vibe Coding Standard Operating Procedure (Workflow)

This document outlines the step-by-step operational workflow for developing features using AI-native vibe coding techniques.

---

## 1. Operational Workflow Diagram

```
[Idea / Task]
     │
     ▼
[Step 1: Context Assembly] ─── (Select relevant prd, arch, & code files)
     │
     ▼
[Step 2: Spec & Prompting] ─── (Feed intent to Architect Agent for plan)
     │
     ▼
[Step 3: Code Generation]  ─── (Execute task via Coder Agent)
     │
     ▼
[Step 4: Automated Audit]  ─── (Run linter, typescript check, unit tests)
     │
     ├─── [Errors Found] ──────► [Feed errors to Agent for auto-fix]
     │                                     ▲
     └─── [All Pass] ──────────────────────┘
             │
             ▼
[Step 5: Code Review & Commit] (Developer reviews diff & commits code)
```

---

## 2. Step-by-Step Execution Guide

### Step 1: Context Assembly
Before prompting the AI assistant:
1. Identify the files directly impacted by the change.
2. Provide references to `prd.md` for business goals and `architecture.md` for design constraints.
3. Keep total context tokens focused and concise.

### Step 2: Architecture & Specification Review
For any non-trivial feature (>50 lines of code):
- Prompt: *"Review `@architecture.md` and `@prd.md`. Create a implementation plan for [Feature X]. List files to edit/create and step-by-step logic."*
- Review and refine the AI's proposed plan before generating code.

### Step 3: Incremental Code Generation
- Execute coding in small, modular chunks.
- Generate one component or API handler at a time.
- Avoid multi-file bulk edits in a single prompt block.

### Step 4: Verification Loop (The "Vibe Verification" Phase)
Run local automation tools immediately after code output:
```bash
# Example verification script
npm run lint && npm run type-check && npm test
```
- If errors occur: Copy the terminal error output directly back to the AI assistant with: *"Fix these lint/type errors without changing the business logic."*

### Step 5: Final Review & Git Commit
- Inspect the visual output and git diff.
- Commit using Conventional Commits format:
  `feat(scope): add user authentication flow via vibe workflow`
