import { redirect } from "next/navigation";
import { getSession } from "@/lib/auth";
import LoginForm from "./login-form";

export default async function Home({
  searchParams,
}: {
  searchParams: Promise<{ error?: string; warning?: string }>;
}) {
  const session = await getSession();
  if (session) redirect("/dashboard");

  const params = await searchParams;

  return (
    <main className="login-background">
      <section className="login-card">
        <div className="brand">
          <div className="brand-icon">IP</div>
          <h1>IP Admin</h1>
          <p>Management Portal</p>
        </div>

        {params.error && <div className="error-message">{params.error}</div>}
        {params.warning && <div className="warning-message">{params.warning}</div>}

        <LoginForm />

        <div className="login-footer">Secure Administration Portal</div>
      </section>
    </main>
  );
}
