import { db } from "./index";
import { users, projects, sessions } from "./schema";
import { randomUUID } from "crypto";

/**
 * Database Seeder
 * Populates database with sample data for development/testing
 *
 * Usage: npm run db:seed
 */

const seed = async () => {
  try {
    console.log("🌱 Starting database seed...");

    // Create sample user
    const userId = randomUUID();
    await db.insert(users).values({
      id: userId,
      email: "dev@vibecoding.ai",
      name: "Vibe Developer",
      isActive: true,
    });

    // Create sample project
    const projectId = randomUUID();
    await db.insert(projects).values({
      id: projectId,
      userId,
      name: "AI Agent Platform",
      description: "Multi-agent orchestration platform for code generation",
      repository: "https://github.com/user/ai-agent-platform",
    });

    // Create sample session
    await db.insert(sessions).values({
      userId,
      sessionToken: `session_${randomUUID()}`,
      provider: "oauth",
      expiresAt: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000), // 30 days
    });

    console.log("✅ Seed completed successfully");
    console.log(`   - Created user: ${userId}`);
    console.log(`   - Created project: ${projectId}`);
  } catch (error) {
    console.error("❌ Seed failed:", error);
    process.exit(1);
  }
};

seed();
