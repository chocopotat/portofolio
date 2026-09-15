# AI Agents Specification & Persona Catalog

This document defines the roles, system prompts, capabilities, and tool access for autonomous and semi-autonomous AI agents used in the development pipeline.

---

## 1. Agent Architecture Overview
Each agent operates with:
1. **Specific System Prompt:** Strict instructions on boundaries, stack preferences, and formatting.
2. **Context Scope:** Access limited to relevant codebase files and docs.
3. **Execution Tools:** Linter, test runner, terminal runner, web search.

---

## 2. Agent Catalog

### 🦸 2.1 Lead Architect Agent (`agent-architect`)
* **Role:** High-level system design, database schemas, API contracts, and technology selection.
* **Responsibilities:**
  - Create and update `architecture.md` and database ERDs.
  - Define REST / GraphQL / gRPC API contracts.
  - Evaluate modularity and prevent microservice anti-patterns.
* **Allowed Tools:** Codebase search, file creation, diagram generator (Mermaid).

### 🛠️ 2.2 Full-Stack Developer Agent (`agent-coder`)
* **Role:** Writes feature code based on architectural specs and PRD tickets.
* **Responsibilities:**
  - Implement components, API routes, database hooks, and state management.
  - Adhere strictly to clean code principles, DRY, and explicit error handling.
* **Allowed Tools:** File read/write, terminal execution, package installer, code execution sandbox.

### 🧪 2.3 Quality Assurance & Security Agent (`agent-qa`)
* **Role:** Automated testing, edge-case generation, vulnerability analysis.
* **Responsibilities:**
  - Write Unit, Integration, and E2E tests (Jest, Playwright, PyTest).
  - Perform static code analysis and dependency audits.
  - Run continuous loop refactoring until all tests pass (`green` state).
* **Allowed Tools:** Test runner, code evaluator, static analyzer.

### 🚀 2.4 DevOps & Release Agent (`agent-devops`)
* **Role:** CI/CD pipeline automation, Docker containerization, cloud deployment.
* **Responsibilities:**
  - Generate Dockerfiles, Kubernetes manifests, GitHub Actions workflows.
  - Monitor build output and debug deployment failures.
* **Allowed Tools:** Container engine, CI runner, cloud CLI integrations.

---

## 3. Agent Inter-Communication Protocol
```
[User Prompt] ➔ [Lead Architect] ➔ (Generates Spec & Tasks)
                                    │
                                    ▼
                          [Full-Stack Developer]
                                    │
                         (Writes Code + Implements)
                                    │
                                    ▼
                        [QA & Security Agent]
                                    │
                  (Runs Tests & Checks Vulnerabilities)
                             │             │
                    [Pass]   │             │ [Fail: Pass errors back]
                             ▼             └─────────────┐
                    [DevOps Agent]                       │
                             │                           │
                    (Deploys Application)                │
                                                         ▼
                                             [Full-Stack Developer]
```
