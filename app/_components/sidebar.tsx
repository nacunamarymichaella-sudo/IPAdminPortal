import Link from "next/link";

export default function Sidebar({
  active,
}: {
  active: "dashboard" | "logs";
}) {
  return (
    <aside className="sidebar">
      <div className="sidebar-brand">
        <div className="sidebar-logo">IP</div>
        <div>
          <strong>IP Admin</strong>
          <small>Portal</small>
        </div>
      </div>

      <nav aria-label="Main navigation">
        <Link className={active === "dashboard" ? "active" : ""} href="/dashboard">
          Dashboard
        </Link>
        <Link className={active === "logs" ? "active" : ""} href="/logs">
          Login Logs
        </Link>
      </nav>

      <div className="sidebar-bottom">
        <form action="/api/logout" method="post">
          <button className="logout-link" type="submit">
            Logout
          </button>
        </form>
      </div>
    </aside>
  );
}
