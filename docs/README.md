# Project Documentation

This folder contains human-written documentation describing the product vision and the technical architecture for the PokeTournament MVP.

Use this as the single source of truth for planning, onboarding, and implementation.

## Contents

- Vision and Scope — see `docs/vision.md`
- Monorepo Layout (packages/) — see `docs/architecture/monorepo.md`
- Backend Architecture (DDD + Core package) — see `docs/architecture/backend-ddd.md`
- Frontend Architecture (Next.js + Screaming Architecture) — see `docs/architecture/frontend-screaming.md`
- GraphQL API and Data Model (Mongo) — see `docs/architecture/graphql-and-data-model.md`
- Roadmap & Definition of Done — see `docs/roadmap.md`

## Quick Notes

- The repository will be organized as a monorepo under `packages/` with `backend`, `frontend`, and an external `core` package for backend domain logic.
- Backend follows Domain-Driven Design (DDD) with CQRS; the pure domain lives in `packages/core` and the Symfony application integrates it.
- Frontend (Next.js) follows Screaming Architecture: high-level directories that scream business features (Auth, Tournament, Swiss, Match, Draft, Team, Nuzlocke).


