# Roadmap & Milestones (MVP)

This roadmap sequences delivery to maximize visible progress and reduce rework. It mirrors dependencies across features and architecture.

## Milestones

- M1 — Core Platform & Auth
  - Auth (Email + Google), sessions, role guards
  - Tournament creation, invitations, player list
  - PokeAPI importers and local catalogs
  - Monorepo baseline (backend, frontend, core), GraphQL-only API, Nginx proxy
  - Tooling: phpspec, Behat (Gherkin), Jest, Playwright, Husky/Commitlint

- M2 — Swiss Engine & Match Flow
  - Pairing engine (groups by score, rematch avoidance, BYE policy) — in core
  - GraphQL: pair(players,scores,byes,opponents) and standings(matches,randKey)
  - Round lifecycle (start/close) with invariants and idempotency
  - Match reporting + admin override (audit trail)
  - Standings projection with tiebreakers (OWP → Direct → Random)

- M3 — Pick & Ban + Team Builder
  - PB-2 draft with timer, confirmation, server-authoritative state
  - Team Builder UI + Poképaste import/export
  - Validation against local catalogs
  - Team constraints (species/item clause, banned list), format presets

- M4 — Nuzlocke & Polish
  - Nuzlocke runs, encounters, status changes, coverage view
  - UI polish, accessibility, error/empty states
  - Deployment, HTTPS, monitoring
  - Observability: request/resolve timings, domain events log, tracing

## Feature Backlog (next up)

- Backend (GraphQL, Symfony, Core)
  - Input validation and error codes for pair/standings
  - Deterministic seeding for tie randomization in standings
  - Round service: open/close round mutation; guard against overlaps
  - Match report mutation (reportResult), admin override (setResult)
  - Rematch prevention persisted across rounds
  - BYE memory and eligibility enforcement in core
  - MongoDB projections for tournaments, rounds, matches, standings snapshots
  - AuthZ on mutations/queries (TD: owner/admin)

- Frontend (Next.js 16, React 19, Screaming Architecture)
  - GraphQL client setup (urql/apollo) with typed hooks
  - Tournament UI: list/create/invite; player management
  - Swiss round screen: pairings table, lock/unlock, report results
  - Standings page with live updates
  - Pick & Ban UI (PB-2) with timer and confirmations
  - Team Builder with Poképaste import/export, validation badges
  - DS-style pixel UI components and theme

- QA & Tooling
  - Behat E2E scenarios for GraphQL flows (pair, standings, round open/close, report)
  - Playwright E2E smoke for critical UX paths
  - phpspec coverage for core: standings tiebreak edge cases, rematch avoidance greediness, BYE fallbacks
  - CI: run unit/spec, Behat (headless), Playwright (containers)

## Development Order (Dependencies)

## Development Order (Dependencies)

Auth → Tournament Core → Swiss Engine → Match Flow → Team Builder → Pick & Ban → Nuzlocke → Polish

## Definition of Done (Global)

- Backend: command/handler implemented, domain tests for critical logic, projections ready, validated/resolver-authorized.
- Frontend: responsive, accessible, loading/error states, shared components, no console errors.
- Integration: key mutations verified end-to-end; state persists on refresh.
- QA: flow complete, matches UX intent on desktop and mobile.

## GraphQL-only policy

- All new capabilities must be exposed via GraphQL schema and resolvers.
- Remove/avoid REST endpoints; tests and docs reflect GraphQL-only usage.

## Environments

- Local: Docker Compose (Mongo, backend, frontend, proxy)
- Staging: HTTPS, seed data, CI auto-deploy
- Production: HTTPS, backups, monitoring


