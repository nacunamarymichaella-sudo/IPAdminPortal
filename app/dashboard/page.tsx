import { headers } from "next/headers";
import { redirect } from "next/navigation";
import Sidebar from "@/app/_components/sidebar";
import { getSession } from "@/lib/auth";
import { getBrowser, getClientIp, getOperatingSystem, getDevice } from "@/lib/request-info";
import { getUsers } from "@/lib/sheets";

export default async function Dashboard({
  searchParams,
}: {
  searchParams: Promise<{ warning?: string }>;
}) {
  const session = await getSession();
  if (!session) redirect("/");

  const requestHeaders = await headers();
  const userAgent = requestHeaders.get("user-agent") ?? "";
  const clientIp = getClientIp(requestHeaders);
  const browser = getBrowser(userAgent);
  const device = getDevice(userAgent);
  const operatingSystem = getOperatingSystem(userAgent);
  const params = await searchParams;

  let userCount: number | null = null;
  let dataWarning = "";
  try {
    const users = await getUsers();
    userCount = users.length;
  } catch (error) {
    dataWarning =
      error instanceof Error ? error.message : "Unable to load dashboard data.";
    console.error("Dashboard data request failed:", error);
  }

  return (
    <div className="layout">
      <Sidebar active="dashboard" />
      <main className="main">
        {(params.warning || dataWarning) && (
          <div className="log-warning">
            {[params.warning, dataWarning].filter(Boolean).join(" ")}
          </div>
        )}

        <header className="topbar">
          <div>
            <h1>Dashboard</h1>
            <p>
              Welcome back, <strong>{session.username}</strong>
            </p>
          </div>
          <div className="profile">
            <div className="avatar">{session.username.slice(0, 1).toUpperCase()}</div>
            <div>
              <strong>{session.username}</strong>
              <small>{session.role}</small>
            </div>
          </div>
        </header>

        <section className="cards" aria-label="Account overview">
          <div className="stat-card">
            <span>Total Users</span>
            <strong>{userCount ?? "Unavailable"}</strong>
            <small>Registered accounts</small>
          </div>
          <div className="stat-card">
            <span>Your IP</span>
            <strong className="ip-text">{clientIp}</strong>
            <small>Current connection</small>
          </div>
          <div className="stat-card">
            <span>Device</span>
            <strong>{device}</strong>
            <small>Current device</small>
          </div>
          <div className="stat-card">
            <span>Browser</span>
            <strong>{browser}</strong>
            <small>Current browser</small>
          </div>
        </section>

        <section className="panel">
          <div className="panel-header">
            <div>
              <h2>System Overview</h2>
              <p>Administration portal status</p>
            </div>
            <a href="/logs" className="primary-button">
              View Login Logs
            </a>
          </div>

          <div className="info-grid">
            <div>
              <span>Logged-in User</span>
              <strong>{session.username}</strong>
            </div>
            <div>
              <span>Role</span>
              <strong>{session.role}</strong>
            </div>
            <div>
              <span>Login Time</span>
              <strong>{session.loginTime}</strong>
            </div>
            <div>
              <span>Operating System</span>
              <strong>{operatingSystem}</strong>
            </div>
          </div>
        </section>
      </main>
    </div>
  );
}
