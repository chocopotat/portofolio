import { migrate } from "drizzle-orm/postgres-js/migrator";
import postgres from "postgres";
import { drizzle } from "drizzle-orm/postgres-js";

/**
 * Database Migration Runner
 * Executes pending migrations in order from /migrations folder
 *
 * Usage: npm run db:migrate
 */

const runMigrate = async () => {
  if (!process.env.DATABASE_URL) {
    throw new Error("DATABASE_URL environment variable is not set");
  }

  const connection = postgres(process.env.DATABASE_URL, { max: 1 });
  const db = drizzle(connection);

  console.log("🚀 Running migrations...");

  await migrate(db, { migrationsFolder: "./src/lib/db/migrations" });

  console.log("✅ Migrations completed successfully");

  await connection.end();
};

runMigrate().catch((err) => {
  console.error("❌ Migration failed:", err);
  process.exit(1);
});
