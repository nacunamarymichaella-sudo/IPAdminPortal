import { createHash, timingSafeEqual } from "node:crypto";
import { NextRequest, NextResponse } from "next/server";
import {
  createSessionToken,
  SESSION_COOKIE,
  SESSION_MAX_AGE,
} from "@/lib/auth";
import { getBrowser, getClientIp, getDevice, getOperatingSystem } from "@/lib/request-info";
import { writeLog } from "@/lib/sheets";

export const runtime = "nodejs";

function matchesSecret(input: string, expected: string): boolean {
  const inputHash = createHash("sha256").update(input).digest();
  const expectedHash = createHash("sha256").update(expected).digest();
  return timingSafeEqual(inputHash, expectedHash);
}

function loginRedirect(request: NextRequest, path: string) {
  return NextResponse.redirect(new URL(path, request.url), 303);
}

function loginFailureRedirect(request: NextRequest, path: string) {
  const response = loginRedirect(request, path);
  response.cookies.set(SESSION_COOKIE, "", {
    httpOnly: true,
    secure: process.env.NODE_ENV === "production",
    sameSite: "strict",
    path: "/",
    maxAge: 0,
  });
  return response;
}

export async function POST(request: NextRequest) {
  const form = await request.formData();
  const username = String(form.get("username") ?? "").trim();
  const password = String(form.get("password") ?? "");
  const adminUsername = process.env.ADMIN_USERNAME;
  const adminPassword = process.env.ADMIN_PASSWORD;

  if (!adminUsername || !adminPassword) {
    console.error("Login is not configured: set ADMIN_USERNAME and ADMIN_PASSWORD.");
    return loginFailureRedirect(
      request,
      "/?error=Login%20is%20not%20configured.%20Set%20the%20admin%20environment%20variables.",
    );
  }

  const userAgent = request.headers.get("user-agent") ?? "";
  const details = {
    username: username || "(empty)",
    ip: getClientIp(request.headers),
    userAgent,
    browser: getBrowser(userAgent),
    os: getOperatingSystem(userAgent),
    device: getDevice(userAgent),
    action: "LOGIN" as const,
  };

  const valid =
    username !== "" &&
    password !== "" &&
    matchesSecret(username, adminUsername) &&
    matchesSecret(password, adminPassword);

  let token: string | undefined;
  const loginTime = new Date().toLocaleString();
  if (valid) {
    try {
      token = createSessionToken(adminUsername, loginTime);
    } catch (error) {
      console.error("Unable to create login session:", error);
      return loginFailureRedirect(
        request,
        "/?error=Login%20session%20is%20not%20configured.%20Set%20a%20valid%20SESSION_SECRET.",
      );
    }
  }

  let logError = "";
  try {
    await writeLog({ ...details, status: valid ? "SUCCESS" : "FAILED" });
  } catch (error) {
    console.error("Unable to record login attempt:", error);
    logError = "This login attempt could not be recorded.";
  }

  if (!valid) {
    const params = new URLSearchParams({
      error:
        username === "" || password === ""
          ? "Please enter your username and password."
          : "Invalid username or password.",
    });
    if (logError) params.set("warning", logError);
    return loginFailureRedirect(request, `/?${params.toString()}`);
  }

  if (logError) {
    return loginFailureRedirect(
      request,
      `/?error=${encodeURIComponent(
        "Unable to verify the login service. Please try again later.",
      )}`,
    );
  }

  if (!token) {
    throw new Error("Authenticated login is missing its signed session token.");
  }

  const response = loginRedirect(request, "/dashboard");
  response.cookies.set(SESSION_COOKIE, token, {
    httpOnly: true,
    secure: process.env.NODE_ENV === "production",
    sameSite: "strict",
    path: "/",
    maxAge: SESSION_MAX_AGE,
  });

  return response;
}
