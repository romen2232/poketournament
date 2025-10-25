# Vision & Scope

PokeTournament is a web application to create and run Swiss-format Pokémon tournaments with a streamlined competitive flow: invite players, generate pairings, manage match results, run a BO1 Pick & Ban phase, and maintain teams with a builder integrated into the draft. A companion Nuzlocke module offers a structured way to track runs with coverage insights.

The MVP emphasizes a coherent player/admin experience, DS-style pixel theme, and solid technical foundations (DDD backend, Screaming Architecture frontend, GraphQL API, MongoDB read models for speed).

## Goals

- Support the full Swiss tournament loop from creation to final standings.
- Provide a fast, game-like UI that works on desktop and mobile.
- Enable BO1 Pick & Ban (PB-2) integrated with teams built in-app or imported via Poképaste.
- Offer a Nuzlocke system to track encounters and team coverage.
- Build on a maintainable architecture: DDD with a reusable core domain, GraphQL API, and feature-first frontend structure.

## Non-Goals (MVP)

- Multi-game series (BO3/Set-based) — out of scope for MVP.
- Ladder systems or leagues — focus is tournament play.
- Advanced anti-cheat or real-time spectating — keep scope pragmatic.

## Personas

- Tournament Admin: sets up tournaments, manages invites, starts/ends rounds, resolves disputes.
- Player: joins tournaments, participates in Pick & Ban, reports results, builds teams.
- Staff/Moderator: assists with moderation and resolving match results.
- Super Admin: platform-level operations and impersonation for support.

## Core Features (MVP)

1. Authentication & User Management (Email, Google)
2. Tournament Core (Creation, Invitations, Player list)
3. Swiss Pairings & Standings (BYE handling, tiebreakers: OWP → Direct → Random)
4. Match Reporting (player submission, admin override)
5. Pick & Ban (PB-2) with timer and confirmation
6. Team Builder (Singles) with Poképaste import/export
7. Nuzlocke System (runs, encounters, status, coverage)
8. Pokémon Data Layer (local cache of species, moves, items, abilities, types)
9. UI/Design (DS-style pixel theme, responsive)
10. DevOps & Security (Docker, HTTPS, CI basics)

## Success Criteria (MVP)

- A tournament can be created, players invited, rounds run end-to-end, and standings computed with tiebreakers.
- Pick & Ban is completed and locked before result reporting.
- Teams can be created/edited and imported from Poképaste.
- Nuzlocke runs can be created and managed with useful filtering and a coverage view.
- The UI is responsive, accessible, and consistent with the DS-style theme.

## Constraints & Assumptions

- Frontend: Next.js (App Router), TypeScript.
- Backend: PHP (Symfony), CQRS, MongoDB for persistence and projections.
- Domain: Extracted to an external `core` package (pure PHP, framework-agnostic) consumed by the backend.
- API: GraphQL for primary client communication; server authoritative for draft timers and state transitions.
- Infra: Docker-based development, HTTPS in production, CI for lint/test/build.


