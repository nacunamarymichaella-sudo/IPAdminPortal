export type SheetLog = {
  timestamp?: string;
  username?: string;
  ip?: string;
  status?: string;
  action?: string;
  browser?: string;
  os?: string;
  device?: string;
};

type SheetResponse = {
  success?: boolean;
  error?: string;
  users?: unknown[];
  logs?: SheetLog[];
};

function getSheetConfig() {
  const url = process.env.GOOGLE_SHEET_URL;
  const token = process.env.GOOGLE_SHEET_TOKEN;

  if (!url || !token) {
    throw new Error(
      "Google Sheets is not configured. Set GOOGLE_SHEET_URL and GOOGLE_SHEET_TOKEN.",
    );
  }

  return { url, token };
}

async function parseResponse(response: Response): Promise<SheetResponse> {
  if (!response.ok) {
    throw new Error(`Google Sheets returned HTTP ${response.status}.`);
  }

  let data: SheetResponse;
  try {
    data = (await response.json()) as SheetResponse;
  } catch {
    throw new Error("Google Sheets returned an invalid JSON response.");
  }

  if (data.success === false) {
    throw new Error(data.error || "Google Sheets reported an error.");
  }

  return data;
}

export async function getUsers(): Promise<unknown[]> {
  const { url, token } = getSheetConfig();
  const endpoint = new URL(url);
  endpoint.searchParams.set("action", "users");
  endpoint.searchParams.set("token", token);

  const response = await fetch(endpoint, { cache: "no-store" });
  const data = await parseResponse(response);
  return data.users ?? [];
}

export async function getLogs(): Promise<SheetLog[]> {
  const { url, token } = getSheetConfig();
  const endpoint = new URL(url);
  endpoint.searchParams.set("action", "logs");
  endpoint.searchParams.set("token", token);

  const response = await fetch(endpoint, { cache: "no-store" });
  const data = await parseResponse(response);
  return data.logs ?? [];
}

export async function writeLog(log: {
  username: string;
  status: "SUCCESS" | "FAILED";
  action: "LOGIN" | "LOGOUT";
  ip: string;
  userAgent: string;
  browser: string;
  os: string;
  device: string;
}): Promise<void> {
  const { url, token } = getSheetConfig();
  const response = await fetch(url, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      action: "log",
      token,
      timestamp: new Date().toISOString(),
      username: log.username,
      ip: log.ip,
      status: log.status,
      browser: log.browser,
      os: log.os,
      device: log.device,
      userAgent: log.userAgent,
      log_action: log.action,
    }),
    cache: "no-store",
  });

  await parseResponse(response);
}
