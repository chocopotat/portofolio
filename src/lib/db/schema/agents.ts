import { pgTable, text, timestamp, uuid, varchar, integer, pgEnum } from "drizzle-orm/pg-core";
import { projects } from "./projects";

/**
 * Agent Execution Logs Table
 * Tracks autonomous agent executions, outputs, and metadata
 *
 * Fields:
 * - id: Unique execution identifier
 * - projectId: Foreign key reference to projects table
 * - agentType: Type of agent (architect, coder, qa, devops)
 * - prompt: Input prompt provided to agent
 * - output: Agent-generated output/response
 * - status: Execution status (pending, completed, failed)
 * - tokensUsed: Token consumption metrics
 * - createdAt: Execution timestamp
 */

export const agentTypeEnum = pgEnum("agent_type", [
  "architect",
  "coder",
  "qa",
  "devops",
]);

export const executionStatusEnum = pgEnum("execution_status", [
  "pending",
  "running",
  "completed",
  "failed",
]);

export const agentExecutions = pgTable("agent_executions", {
  id: uuid("id").primaryKey().defaultRandom(),
  projectId: uuid("project_id")
    .notNull()
    .references(() => projects.id, { onDelete: "cascade" }),
  agentType: agentTypeEnum("agent_type").notNull(),
  prompt: text("prompt").notNull(),
  output: text("output"),
  status: executionStatusEnum("status").default("pending").notNull(),
  tokensUsed: integer("tokens_used"),
  createdAt: timestamp("created_at", { withTimezone: true }).defaultNow().notNull(),
});

export type AgentExecution = typeof agentExecutions.$inferSelect;
export type NewAgentExecution = typeof agentExecutions.$inferInsert;
