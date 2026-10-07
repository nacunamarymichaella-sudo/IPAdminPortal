# IP Admin Portal

This project is a Next.js application that can be deployed to Vercel. The PHP
files are the previous XAMPP version; Vercel uses the Next.js routes in `app/`.

## Configure locally

1. Install Node.js 20.9 or newer.
2. Copy `.env.example` to `.env.local`.
3. Set `ADMIN_USERNAME` and `ADMIN_PASSWORD` to the credentials you want to use.
   To retain the old local credentials, use `admin` and `admin123`; choose a
   strong password before exposing the portal publicly.
4. Set `SESSION_SECRET` to a randomly generated value of at least 32 characters.
5. Set `GOOGLE_SHEET_URL` and `GOOGLE_SHEET_TOKEN` to the Apps Script endpoint and
   token used by the existing spreadsheet.
6. Run `npm install`, then `npm run dev`.

The spreadsheet integration expects the Apps Script to support `action=users`,
`action=logs`, and POST requests with `action=log`, as the PHP application did.

## Deploy to Vercel

Import this project into Vercel and add the five environment variables above in
the project's Settings → Environment Variables. Keep them server-only (do not
prefix them with `NEXT_PUBLIC_`). Deploy after setting the variables.

The session is held in an HTTP-only, signed cookie. Set a unique strong
`SESSION_SECRET`; changing it invalidates existing sessions. The application
records usernames and login/logout outcomes, but never passwords.

The previously committed Google Sheets token should be rotated before deploying
because it was stored in source code. Put only the replacement token in Vercel's
environment variables.
