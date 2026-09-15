import { pgTable, text, timestamp, uuid, varchar } from "drizzle-orm/pg-core";
import { users } from "./users";
import { sql } from "drizzle-orm";

/**
 * Sessions Table
 * OAuth/Auth session management for user authentication
 *
 * Fields:
 * - id: Unique session identifier
 * - userId: Foreign key reference to users table
 * - sessionToken: Unique token for session validation
 * - provider: Auth provider (e.g., "oauth", "email")
 * - expiresAt: Session expiration timestamp
 * - createdAt: Session creation timestamp
 */

export const sessions = pgTable("sessions", {
  id: uuid("id").primaryKey().defaultRandom(),
  userId: uuid("user_id")
    .notNull()
    .references(() => users.id, { onDelete: "cascade" }),
  sessionToken: varchar("session_token", { length: 255 }).notNull().unique(),
  provider: varchar("provider", { length: 50 }).default("oauth").notNull(),
  expiresAt: timestamp("expires_at", { withTimezone: true }).notNull(),
  createdAt: timestamp("created_at", { withTimezone: true }).defaultNow().notNull(),
});

export type Session = typeof sessions.$inferSelect;
export type NewSession = typeof sessions.$inferInsert;
