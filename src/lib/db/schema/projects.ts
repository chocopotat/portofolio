import { pgTable, text, timestamp, uuid, varchar } from "drizzle-orm/pg-core";
import { users } from "./users";

/**
 * Projects Table
 * User projects/workspaces within the vibe coding platform
 *
 * Fields:
 * - id: Unique project identifier
 * - userId: Foreign key reference to project owner
 * - name: Project name
 * - description: Project description (nullable)
 * - repository: Git repository URL (nullable)
 * - createdAt: Project creation timestamp
 * - updatedAt: Last modification timestamp
 */

export const projects = pgTable("projects", {
  id: uuid("id").primaryKey().defaultRandom(),
  userId: uuid("user_id")
    .notNull()
    .references(() => users.id, { onDelete: "cascade" }),
  name: varchar("name", { length: 255 }).notNull(),
  description: text("description"),
  repository: varchar("repository", { length: 512 }),
  createdAt: timestamp("created_at", { withTimezone: true }).defaultNow().notNull(),
  updatedAt: timestamp("updated_at", { withTimezone: true })
    .defaultNow()
    .$onUpdate(() => new Date())
    .notNull(),
});

export type Project = typeof projects.$inferSelect;
export type NewProject = typeof projects.$inferInsert;
