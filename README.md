# Tiflis — Georgian Restaurant

Laravel 13 + Livewire + Tailwind site for **Tiflis**, a Georgian restaurant in Nice, France. One-page marketing site (hero, story, menu highlights, gallery, hours/location) plus a real booking system: a two-step, capacity-aware reservation flow (live seat availability, no overbooking) with an optional "Continue with Google" shortcut, self-service manage/cancel via a link in the confirmation email, and a premium admin dashboard (sidebar shell, KPIs, searchable/filterable reservations with a detail panel, menu CRUD grouped by category) restricted to actual staff accounts — there's no public sign-up. Bilingual — English and French.

## Running locally (Docker / Sail)

```bash
cp .env.example .env          # if .env doesn't already exist
composer install
./vendor/bin/sail up -d       # app, mysql, mailpit
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev # Vite with hot reload, or `npm run build` for production
```

- Site: **http://localhost:8120**
- Mailpit UI (catches reservation confirmation emails): **http://localhost:8145**
- Admin dashboard at `/admin` — log in at `/login`, seeded from `ADMIN_EMAIL` / `ADMIN_PASSWORD` in `.env` (defaults: `admin@tiflis-restaurant.example` / `password`). There's no `/register` — staff accounts only come from the seeder or `artisan tinker`.

`docker compose up -d` also starts a dedicated `queue` container (`php artisan queue:work`) — reservation confirmation emails are queued, so this is what actually sends them to Mailpit.

To turn on the "Continue with Google" button on the booking form (hidden by default), set `GOOGLE_CLIENT_ID` / `GOOGLE_CLIENT_SECRET` in `.env` from a Google Cloud Console OAuth client — see `CLAUDE.md` for the exact steps.

Ports (`APP_PORT`, `VITE_PORT`, `FORWARD_DB_PORT`, `FORWARD_MAILPIT_PORT`, `FORWARD_MAILPIT_DASHBOARD_PORT`) are all set in `.env` to values that don't collide with the other Sail-based projects on this machine.

To run app server + queue worker + log tailing + Vite together instead:

```bash
./vendor/bin/sail composer run dev
```

## Tests

```bash
./vendor/bin/sail artisan test
./vendor/bin/sail pint          # code style
```

57 tests covering the booking flow, capacity/overbooking enforcement (including the race-safe atomic check and the Google sign-in path), the manage/cancel link, admin role gating (a logged-in non-admin is 403'd), and the admin dashboard, on top of the stock Breeze auth tests.

See `CLAUDE.md` for architecture notes.
