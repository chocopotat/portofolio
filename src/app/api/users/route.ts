/**
 * Example API Route: User Creation
 * Demonstrates proper database usage patterns
 *
 * Endpoint: POST /api/users
 * Body: { email: string, name: string }
 * Response: { id: string, email: string, name: string, ... }
 */

import { db } from "@/lib/db";
import { users } from "@/lib/db/schema";
import { userInsertSchema } from "@/types/database";
import { eq } from "drizzle-orm";

/**
 * Validates schema and returns appropriate HTTP response
 */
function createResponse(status: number, data: any) {
  return new Response(JSON.stringify(data), {
    status,
    headers: { "Content-Type": "application/json" },
  });
}

export async function POST(request: Request) {
  try {
    // Parse request body
    const body = await request.json();

    // Validate with Zod schema
    const validData = userInsertSchema.parse(body);

    // Check if user already exists
    const existing = await db.query.users.findFirst({
      where: eq(users.email, validData.email),
    });

    if (existing) {
      return createResponse(409, {
        error: "User with this email already exists",
      });
    }

    // Insert new user
    const result = await db.insert(users).values(validData).returning();

    return createResponse(201, result[0]);
  } catch (error) {
    if (error instanceof Error) {
      return createResponse(400, {
        error: error.message,
      });
    }

    return createResponse(500, {
      error: "Internal server error",
    });
  }
}

/**
 * GET /api/users
 * Fetch all users (with pagination in production)
 */
export async function GET() {
  try {
    const allUsers = await db.query.users.findMany();
    return createResponse(200, allUsers);
  } catch (error) {
    return createResponse(500, {
      error: "Failed to fetch users",
    });
  }
}
