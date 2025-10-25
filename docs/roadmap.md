# Roadmap & Milestones (MVP)

This roadmap sequences delivery to maximize visible progress and reduce rework. It mirrors dependencies across features and architecture.

## Milestones

- M1 — Core Platform & Auth
  - Auth (Email + Google), sessions, role guards
  - Tournament creation, invitations, player list
  - PokeAPI importers and local catalogs

- M2 — Swiss Engine & Match Flow
  - Pairing engine (groups by score, rematch avoidance, BYE policy)
  - Round lifecycle (start/close)
  - Match reporting + admin override
  - Standings projection with tiebreakers (OWP → Direct → Random)

- M3 — Pick & Ban + Team Builder
  - PB-2 draft with timer, confirmation, server-authoritative state
  - Team Builder UI + Poképaste import/export
  - Validation against local catalogs

- M4 — Nuzlocke & Polish
  - Nuzlocke runs, encounters, status changes, coverage view
  - UI polish, accessibility, error/empty states
  - Deployment, HTTPS, monitoring

## Development Order (Dependencies)

Auth → Tournament Core → Swiss Engine → Match Flow → Team Builder → Pick & Ban → Nuzlocke → Polish

## Definition of Done (Global)

- Backend: command/handler implemented, domain tests for critical logic, projections ready, validated/resolver-authorized.
- Frontend: responsive, accessible, loading/error states, shared components, no console errors.
- Integration: key mutations verified end-to-end; state persists on refresh.
- QA: flow complete, matches UX intent on desktop and mobile.

## Environments

- Local: Docker Compose (Mongo, backend, frontend, proxy)
- Staging: HTTPS, seed data, CI auto-deploy
- Production: HTTPS, backups, monitoring


