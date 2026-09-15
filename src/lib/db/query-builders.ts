/**
 * Database Query Builders
 * Typed, reusable query patterns following DRY principle
 *
 * Purpose: Centralize complex queries to prevent duplication and ensure consistency
 */

import { db } from "./index";
import { users, projects, sessions, agentExecutions } from "./schema";
import { eq } from "drizzle-orm";

/**
 * User Queries
 */
export const userQueries = {
  /**
   * Find user by email
   * @param email - User email
   * @returns User record or undefined
   */
  findByEmail: async (email: string) => {
    return db.query.users.findFirst({
      where: eq(users.email, email),
    });
  },

  /**
   * Find user by ID
   * @param id - User UUID
   * @returns User record or undefined
   */
  findById: async (id: string) => {
    return db.query.users.findFirst({
      where: eq(users.id, id),
    });
  },

  /**
   * Create new user
   * @param userData - User data
   * @returns Created user record
   */
  create: async (userData: typeof users.$inferInsert) => {
    const result = await db.insert(users).values(userData).returning();
    return result[0];
  },
};

/**
 * Project Queries
 */
export const projectQueries = {
  /**
   * Find all projects for a user
   * @param userId - User UUID
   * @returns Array of project records
   */
  findByUserId: async (userId: string) => {
    return db.query.projects.findMany({
      where: eq(projects.userId, userId),
    });
  },

  /**
   * Find project by ID
   * @param id - Project UUID
   * @returns Project record or undefined
   */
  findById: async (id: string) => {
    return db.query.projects.findFirst({
      where: eq(projects.id, id),
    });
  },

  /**
   * Create new project
   * @param projectData - Project data
   * @returns Created project record
   */
  create: async (projectData: typeof projects.$inferInsert) => {
    const result = await db.insert(projects).values(projectData).returning();
    return result[0];
  },
};

/**
 * Session Queries
 */
export const sessionQueries = {
  /**
   * Find session by token
   * @param token - Session token
   * @returns Session record or undefined
   */
  findByToken: async (token: string) => {
    return db.query.sessions.findFirst({
      where: eq(sessions.sessionToken, token),
    });
  },

  /**
   * Create new session
   * @param sessionData - Session data
   * @returns Created session record
   */
  create: async (sessionData: typeof sessions.$inferInsert) => {
    const result = await db.insert(sessions).values(sessionData).returning();
    return result[0];
  },
};

/**
 * Agent Execution Queries
 */
export const agentExecutionQueries = {
  /**
   * Find executions by project ID
   * @param projectId - Project UUID
   * @returns Array of execution records
   */
  findByProjectId: async (projectId: string) => {
    return db.query.agentExecutions.findMany({
      where: eq(agentExecutions.projectId, projectId),
    });
  },

  /**
   * Create new execution record
   * @param executionData - Execution data
   * @returns Created execution record
   */
  create: async (executionData: typeof agentExecutions.$inferInsert) => {
    const result = await db
      .insert(agentExecutions)
      .values(executionData)
      .returning();
    return result[0];
  },
};
