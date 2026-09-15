import { pgTable, text, timestamp, uuid, boolean, varchar } from "drizzle-orm/pg-core";

/**
 * Users Table
 * Core user entity for authentication and profile management
 *
 * Fields:
 * - id: Unique identifier (UUID)
 * - email: User email (unique)
 * - name: User display name
 * - avatarUrl: Profile picture URL (nullable)
 * - isActive: Account status flag
 * - createdAt: Account creation timestamp
 * - updatedAt: Last update timestamp
 */

export const users = pgTable("users", {
  id: uuid("id").primaryKey().defaultRandom(),
  email: varchar("email", { length: 255 }).notNull().unique(),
  name: varchar("name", { length: 255 }).notNull(),
  avatarUrl: text("avatar_url"),
  isActive: boolean("is_active").default(true).notNull(),
  createdAt: timestamp("created_at", { withTimezone: true }).defaultNow().notNull(),
  updatedAt: timestamp("updated_at", { withTimezone: true })
    .defaultNow()
    .$onUpdate(() => new Date())
    .notNull(),
});

export type User = typeof users.$inferSelect;
export type NewUser = typeof users.$inferInsert;
