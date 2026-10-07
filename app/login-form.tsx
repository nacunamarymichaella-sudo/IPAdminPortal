"use client";

import { useState } from "react";

export default function LoginForm() {
  const [showPassword, setShowPassword] = useState(false);

  return (
    <form action="/api/login" method="post">
      <label htmlFor="username">Username</label>
      <input
        id="username"
        type="text"
        name="username"
        placeholder="Enter your username"
        required
        autoComplete="username"
      />

      <label htmlFor="password">Password</label>
      <div className="password-box">
        <input
          id="password"
          type={showPassword ? "text" : "password"}
          name="password"
          placeholder="Enter your password"
          required
          autoComplete="current-password"
        />
        <button
          type="button"
          onClick={() => setShowPassword((shown) => !shown)}
          aria-label={showPassword ? "Hide password" : "Show password"}
        >
          {showPassword ? "Hide" : "Show"}
        </button>
      </div>

      <button className="login-button" type="submit">
        SIGN IN
      </button>
    </form>
  );
}
