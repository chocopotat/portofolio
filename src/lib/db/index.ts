import { drizzle } from "drizzle-orm/postgres-js";
import postgres from "postgres";
import * as schema from "./schema";

/**
 * Database client initialization
 * Singleton pattern ensures only one connection pool per application runtime
 */

if (!process.env.DATABASE_URL) {
  throw new Error("DATABASE_URL environment variable is not set");
}

const client = postgres(process.env.DATABASE_URL, {
  prepare: false,
});

export const db = drizzle(client, {
  schema,
  logger: process.env.NODE_ENV === "development",
});

export type Database = typeof db;
export * from "./schema";
