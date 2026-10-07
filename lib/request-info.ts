export function getClientIp(requestHeaders: Headers): string {
  const forwarded = requestHeaders.get("x-forwarded-for");
  const address =
    requestHeaders.get("x-real-ip") ??
    forwarded?.split(",")[0]?.trim() ??
    "UNKNOWN";

  return address.startsWith("::ffff:") ? address.slice(7) : address;
}

export function getDevice(userAgent: string): string {
  return /mobile|android|iphone|ipad/i.test(userAgent) ? "Mobile" : "Desktop";
}

export function getBrowser(userAgent: string): string {
  if (userAgent.includes("Edg")) return "Microsoft Edge";
  if (userAgent.includes("Chrome")) return "Google Chrome";
  if (userAgent.includes("Firefox")) return "Mozilla Firefox";
  if (userAgent.includes("Safari")) return "Safari";
  return "Unknown";
}

export function getOperatingSystem(userAgent: string): string {
  if (/Windows/i.test(userAgent)) return "Windows";
  if (/Android/i.test(userAgent)) return "Android";
  if (/iPhone|iPad/i.test(userAgent)) return "iOS";
  if (/Mac/i.test(userAgent)) return "macOS";
  if (/Linux/i.test(userAgent)) return "Linux";
  return "Unknown";
}
