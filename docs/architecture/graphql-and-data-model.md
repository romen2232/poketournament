# GraphQL API and Data Model (Mongo)

This document summarizes the primary GraphQL types and mutations and maps them to MongoDB collections. It is intentionally concise and focused on the MVP.

## High-Level GraphQL Schema

- Auth: `register`, `login`, `loginWithGoogle`, `me`.
- Tournament: `createTournament`, `invitePlayer`, `acceptInvitation`.
- Swiss: `startSwissRound`, `closeSwissRound`, `standings`, `rounds`.
- Matches: `reportMatchResult`, `adminOverrideMatchResult`, `matches`.
- Draft (Pick & Ban): `startPickBan`, `submitBan`, `submitPick`, `confirmDraft`, subscription `draftUpdated`.
- Teams: `createTeam`, `importPokepaste`, `updatePokemonSet`, `deleteTeam`, queries `myTeams`, `team`.
- Nuzlocke: `addEncounter`, `updateEncounter`, `setEncounterStatus`, query `myNuzlocke`.
- Catalogs: `pokemonSearch`, `moveSearch`, `itemSearch`.

Types include: `User`, `Tournament`, `Round`, `Match`, `Draft`, `Team`, `PokemonSet`, `Standings`, `NuzlockeRun`, `Encounter`, and catalog entities (`Pokemon`, `Move`, `Item`, `Ability`, `TypeRef`).

## MongoDB Collections (MVP)

- `users` — credentials, roles, profile.
- `tournaments` — owner, name, settings, round count.
- `tournamentPlayers` — roster per tournament, score, opponents, BYE flag.
- `invites` — signed tokens for player/admin invites.
- `rounds` — round number, status, pairings, BYE.
- `matches` — per pairing, status, winner, draft linkage.
- `drafts` — Pick & Ban state machine, timers, selections, confirmations, log.
- `teams` — per user team with six `pokemonSets`.
- `nuzlockeRuns` — encounters, statuses, notes.
- Catalogs — `pokemon`, `moves`, `items`, `abilities`, `types`.
- `standings` (projection) — precomputed rankings and tiebreakers.

### Indexes (examples)

- `tournamentPlayers` — `{ tournamentId:1, userId:1 }` unique.
- `matches` — `{ tournamentId:1, roundNumber:1 }`.
- `rounds` — `{ tournamentId:1, number:1 }` unique.
- `drafts` — `{ matchId:1 }` unique.
- `teams` — `{ userId:1, tournamentId:1 }`.
- Catalogs — name text indexes + strict `_id`.

## Read Models and Performance

- Standings are projected and updated on match events for O(1) UI queries.
- Draft state is optimized for frequent reads via subscription or short polling.
- Catalog data is locally cached to avoid PokeAPI calls during gameplay.

## Error Shape & Security

- Consistent GraphQL error mapping; typed errors for validation and authorization.
- RBAC enforced in resolvers: SUPER_ADMIN, TOURNAMENT_ADMIN, STAFF, PLAYER.
- Sessions via HTTP-only cookies or JWT; HTTPS required in production.


