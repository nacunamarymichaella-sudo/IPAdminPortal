import { NextRequest, NextResponse } from "next/server";
import { SESSION_COOKIE, verifySessionToken } from "@/lib/auth";
import { getBrowser, getClientIp, getDevice, getOperatingSystem } from "@/lib/request-info";
import { writeLog } from "@/lib/sheets";

export const runtime = "nodejs";

export async function POST(request: NextRequest) {
  const token = request.cookies.get(SESSION_COOKIE)?.value;
  const session = verifySessionToken(token);
  let destination = "/";

  if (session) {
    const userAgent = request.headers.get("user-agent") ?? "";
    try {
      await writeLog({
        username: session.username,
        status: "SUCCESS",
        action: "LOGOUT",
        ip: getClientIp(request.headers),
        userAgent,
        browser: getBrowser(userAgent),
        os: getOperatingSystem(userAgent),
        device: getDevice(userAgent),
      });
    } catch (error) {
      console.error("Unable to record logout:", error);
      destination = `/?warning=${encodeURIComponent("Logout succeeded, but the activity log could not be saved.")}`;
    }
  }

  const response = NextResponse.redirect(new URL(destination, request.url), 303);
  response.cookies.set(SESSION_COOKIE, "", {
    httpOnly: true,
    secure: process.env.NODE_ENV === "production",
    sameSite: "strict",
    path: "/",
    maxAge: 0,
  });

  return response;
}
