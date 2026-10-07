import { redirect } from "next/navigation";
import Sidebar from "@/app/_components/sidebar";
import { getSession } from "@/lib/auth";
import { getLogs } from "@/lib/sheets";

export default async function LogsPage() {
  const session = await getSession();
  if (!session) redirect("/");

  let logs: Awaited<ReturnType<typeof getLogs>> = [];
  let errorMessage = "";
  try {
    logs = await getLogs();
  } catch (error) {
    errorMessage =
      error instanceof Error ? error.message : "Unable to load login logs.";
    console.error("Login logs request failed:", error);
  }

  return (
    <div className="layout">
      <Sidebar active="logs" />
      <main className="main">
        <header className="topbar">
          <div>
            <h1>Login Logs</h1>
            <p>User login and logout activity</p>
          </div>
        </header>

        <section className="logs-panel">
          {errorMessage && <div className="log-warning">{errorMessage}</div>}
          <div className="table-scroll">
            <table className="logs-table">
              <thead>
                <tr>
                  <th>Timestamp</th>
                  <th>Username</th>
                  <th>IP Address</th>
                  <th>Status</th>
                  <th>Action</th>
                  <th>Browser</th>
                  <th>OS</th>
                  <th>Device</th>
                </tr>
              </thead>
              <tbody>
                {logs.map((log, index) => {
                  const status = (log.status ?? "").toUpperCase();
                  return (
                    <tr key={`${log.timestamp ?? "log"}-${index}`}>
                      <td>{log.timestamp ?? ""}</td>
                      <td>{log.username ?? ""}</td>
                      <td className="ip">{log.ip ?? ""}</td>
                      <td>
                        <span
                          className={`badge ${
                            status === "SUCCESS" ? "success" : "failed"
                          }`}
                        >
                          {status}
                        </span>
                      </td>
                      <td>{log.action ?? ""}</td>
                      <td>{log.browser ?? ""}</td>
                      <td>{log.os ?? ""}</td>
                      <td>{log.device ?? ""}</td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>
          {!logs.length && !errorMessage && <p>No logs found yet.</p>}
        </section>
      </main>
    </div>
  );
}
