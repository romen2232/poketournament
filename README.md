# PokeTournament — Play Swiss Tournaments with Pick & Ban, Teams, and Nuzlocke

PokeTournament is a modern, game-like web app to create and run Swiss-format Pokémon tournaments. It features a BO1 Pick & Ban (PB-2) flow, an integrated Team Builder, and a companion Nuzlocke tracker — all wrapped in a DS-style pixel UI.

### Highlights
- Swiss tournaments with BYE handling and robust standings (OWP → Direct → Random)
- BO1 Pick & Ban (PB-2) with server-side timers and confirmations
- Team Builder (6 slots, Poképaste import/export)
- Nuzlocke runs with encounters, status, and coverage insights
- Clean architecture: Symfony (DDD/CQRS) backend, Next.js Screaming Architecture frontend
- GraphQL API and MongoDB read models for fast UI

---

### Quickstart (Local)
1) One-time setup
```bash
make setup        # generates local HTTPS certs
```
2) Map local domains (add to /etc/hosts)
```bash
sudo sh -c 'echo "127.0.0.1 poketournament.local api.poketournament.local" >> /etc/hosts'
```
3) Launch stack
```bash
make up           # or: docker compose up -d
make ps           # check containers
```
4) Open the app
- Frontend: https://poketournament.local/  (Next.js)
- Backend Health: https://api.poketournament.local/health  (Symfony)

Note: The certs in `infra/nginx/certs/` are self-signed. Trust them once on your machine if the browser warns.

---

### Project Structure
```
backend/          # Symfony app (DDD/CQRS, MongoDB)
frontend/         # Next.js app (Screaming Architecture)
docs/             # Vision, architecture, process (incl. TDD guide)
docker/           # Dockerfiles (php, node, nginx)
infra/nginx/      # NGINX config + local certs
```
Planned: Monorepo `packages/` layout with `packages/backend`, `packages/frontend`, and `packages/core` (framework-agnostic domain).

---

### Development Commands
- Bring services up/down
```bash
make up
make down
```
- Tail logs
```bash
make logs
```
- Exec into containers
```bash
make bash-backend   # PHP/Symfony container
make bash-frontend  # Node/Next.js container
```

---

### Testing
- Backend units/specs (phpspec)
```bash
make test-backend
```
- Frontend unit tests (Jest)
```bash
make test-frontend
```
- End-to-End (Playwright, official Docker image)
```bash
make test-e2e
```

Hooks
- Conventional Commits enforced via commitlint on commit-msg
- Unit tests run on pre-push (backend + frontend)

---

### Architecture at a Glance
- Backend (Symfony): DDD/CQRS with domain logic extracted to a reusable `core` (planned), MongoDB for persistence and read models, GraphQL API
- Frontend (Next.js): Screaming Architecture (feature-first), DS-style components, GraphQL client
- NGINX: HTTPS reverse proxy for local domains

Key docs:
- Vision: `docs/vision.md`
- Monorepo/Architecture: `docs/architecture/monorepo.md`, `docs/architecture/backend-ddd.md`, `docs/architecture/frontend-screaming.md`
- GraphQL & Data Model: `docs/architecture/graphql-and-data-model.md`
- TDD Guide: `docs/process/tdd.md`
- Roadmap & DoD: `docs/roadmap.md`

---

### Environment
- Frontend uses `NEXT_PUBLIC_API_BASE=https://api.poketournament.local` in Docker
- Backend uses `MONGODB_URL` from compose; MongoDB and Mongo Express are included for local dev

---

### Contributing
- Follow TDD: Red → Green → Refactor (see `docs/process/tdd.md`)
- Use Conventional Commits (e.g., `feat:`, `fix:`, `chore:`, `test:`)
- Keep PRs small and focused; include tests and docs updates when applicable

---

### License
This project is open-source. See `LICENSE` for details.
