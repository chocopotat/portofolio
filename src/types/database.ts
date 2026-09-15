/**
 * Database Type Definitions
 * Central export for all database-related types (Zod schemas & TypeScript interfaces)
 *
 * Principle: Maintain explicit typing across all database operations
 */

import { z } from "zod";

// User Schemas
export const userSelectSchema = z.object({
  id: z.string().uuid(),
  email: z.string().email(),
  name: z.string().min(1),
  avatarUrl: z.string().nullable(),
  isActive: z.boolean(),
  createdAt: z.date(),
  updatedAt: z.date(),
});

export const userInsertSchema = z.object({
  id: z.string().uuid().optional(),
  email: z.string().email(),
  name: z.string().min(1),
  avatarUrl: z.string().url().nullable().optional(),
  isActive: z.boolean().optional(),
  createdAt: z.date().optional(),
  updatedAt: z.date().optional(),
});

// Project Schemas
export const projectSelectSchema = z.object({
  id: z.string().uuid(),
  userId: z.string().uuid(),
  name: z.string().min(1).max(255),
  description: z.string().nullable(),
  repository: z.string().url().nullable(),
  createdAt: z.date(),
  updatedAt: z.date(),
});

export const projectInsertSchema = z.object({
  id: z.string().uuid().optional(),
  userId: z.string().uuid(),
  name: z.string().min(1).max(255),
  description: z.string().optional(),
  repository: z.string().url().nullable().optional(),
  createdAt: z.date().optional(),
  updatedAt: z.date().optional(),
});

// Session Schemas
export const sessionSelectSchema = z.object({
  id: z.string().uuid(),
  userId: z.string().uuid(),
  sessionToken: z.string(),
  provider: z.string(),
  expiresAt: z.date(),
  createdAt: z.date(),
});

// Agent Execution Schemas
export const agentExecutionSelectSchema = z.object({
  id: z.string().uuid(),
  projectId: z.string().uuid(),
  agentType: z.enum(["architect", "coder", "qa", "devops"]),
  prompt: z.string(),
  output: z.string().nullable(),
  status: z.enum(["pending", "running", "completed", "failed"]),
  tokensUsed: z.number().nullable(),
  createdAt: z.date(),
});

export const agentExecutionInsertSchema = z.object({
  id: z.string().uuid().optional(),
  projectId: z.string().uuid(),
  agentType: z.enum(["architect", "coder", "qa", "devops"]),
  prompt: z.string(),
  output: z.string().nullable().optional(),
  status: z.enum(["pending", "running", "completed", "failed"]).optional(),
  tokensUsed: z.number().nullable().optional(),
  createdAt: z.date().optional(),
});

// TypeScript Type Inference
export type UserSelect = z.infer<typeof userSelectSchema>;
export type UserInsert = z.infer<typeof userInsertSchema>;
export type ProjectSelect = z.infer<typeof projectSelectSchema>;
export type ProjectInsert = z.infer<typeof projectInsertSchema>;
export type SessionSelect = z.infer<typeof sessionSelectSchema>;
export type AgentExecutionSelect = z.infer<typeof agentExecutionSelectSchema>;
export type AgentExecutionInsert = z.infer<typeof agentExecutionInsertSchema>;
