/**
 * Database Utility Functions
 * Helper functions for common database operations
 */

import { db } from "./index";
import type { PgColumn } from "drizzle-orm/pg-core";

/**
 * Paginate query results
 * @param query - Drizzle query object
 * @param page - Page number (1-indexed)
 * @param limit - Items per page
 * @returns Object with items, total, page, and totalPages
 */
export async function paginate<T>(
  query: any,
  page: number = 1,
  limit: number = 10
) {
  const offset = (page - 1) * limit;

  // Note: In production, you'd count total items separately
  const items = await query.limit(limit).offset(offset);

  return {
    items,
    page,
    limit,
    hasMore: items.length === limit,
  };
}

/**
 * Soft delete pattern - add a deletedAt timestamp
 * For hard deletes, use standard db.delete()
 */
export async function softDelete<T extends { deletedAt: any }>(
  table: any,
  whereCondition: any
) {
  return db
    .update(table)
    .set({ deletedAt: new Date() })
    .where(whereCondition);
}

/**
 * Bulk insert with error handling
 */
export async function bulkInsert<T>(
  table: any,
  values: T[]
): Promise<{ success: number; failed: number; errors: Error[] }> {
  const errors: Error[] = [];
  let successCount = 0;

  for (const value of values) {
    try {
      await db.insert(table).values(value);
      successCount++;
    } catch (error) {
      errors.push(error as Error);
    }
  }

  return {
    success: successCount,
    failed: errors.length,
    errors,
  };
}

/**
 * Transaction wrapper for atomic operations
 */
export async function withTransaction<T>(
  callback: (trx: typeof db) => Promise<T>
): Promise<T> {
  // Drizzle automatically handles transactions via db.transaction()
  return db.transaction(async (tx) => {
    return callback(tx as typeof db);
  });
}
