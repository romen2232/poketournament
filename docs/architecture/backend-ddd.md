# Backend Architecture (DDD + CQRS + Symfony)

The backend is a Symfony application that implements application and infrastructure layers. The business domain is extracted into an external `core` package (pure PHP), which the backend consumes. CQRS is used to separate write commands from read queries and to enable read-model projections optimized for the UI.

## Layers

- Domain (packages/core): aggregates, entities, value objects, policies, domain events, domain services. No framework dependencies.
- Application (packages/backend/src/Application): commands, queries, handlers, DTOs, transactions.
- Infrastructure (packages/backend/src/Infrastructure): repositories (Mongo), GraphQL resolvers, security, buses, configuration.
- Interface (GraphQL): schema and resolvers that call application handlers.

## Domain Modules (Aggregates)

- User — registration, roles, impersonation policy.
- Tournament — creation, settings, invitations, player roster.
- Swiss — round lifecycle, pairing engine, BYE policy.
- Match — match creation, result reporting, admin override.
- Draft (Pick & Ban) — PB-2 flow with timer and confirmation.
- Team — sets of six Pokémon, Poképaste import/export.
- Nuzlocke — runs, encounters, status changes, coverage.
- PokeData — imported catalogs: species, moves, items, abilities, types.

## CQRS Contracts (examples)

Commands:

- RegisterUser, LinkGoogleAccount, ImpersonateUser
- CreateTournament, InvitePlayer, AcceptInvitation, UpdateTournament
- StartSwissRound, CloseSwissRound
- ReportMatchResult, AdminOverrideMatchResult
- StartPickBan, SubmitBan, SubmitPick, ConfirmDraft
- CreateTeam, ImportPokepaste, UpdatePokemonSet, DeleteTeam
- AddEncounter, UpdateEncounter, SetEncounterStatus

Queries:

- Me, TournamentList, TournamentById
- CurrentStandings, RoundByNumber, MatchesByRound
- DraftState, MyTeams, TeamById, MyNuzlocke
- Catalog searches: pokemonSearch, moveSearch, itemSearch

## Persistence

- MongoDB is used for both write storage and read projections where appropriate.
- Collections include: users, tournaments, tournamentPlayers, invites, rounds, matches, drafts, teams, nuzlockeRuns, and catalogs (pokemon, moves, items, abilities, types).
- Projections: standings with precomputed tiebreakers.
- Indexing on common access patterns (see GraphQL/Data Model doc).

## Pairing & Tiebreakers

- Pair by current score groups, float when odd, avoid rematches (track opponents).
- BYE assigned to the lowest-score eligible player who has not received a BYE.
- Tiebreakers: Opponents’ Win %, Direct Encounter, Random (stable key).

## Draft (Pick & Ban) rules

- Server-authoritative timer; late or invalid actions are ignored.
- State machine: IDLE → BANNING → PICKING → CONFIRM → LOCKED.
- Both players must confirm; auto-lock on timeout.

## Security & Validation

- Roles: SUPER_ADMIN, TOURNAMENT_ADMIN, STAFF, PLAYER. GraphQL resolvers enforce RBAC.
- Input validation via Symfony Validators; consistent error mapping for the frontend.
- Rate limiting on auth, invitations, result submission.

## Testing

- Unit tests: domain services (pairing engine, tiebreakers, draft state machine).
- Integration: command/handler interactions; GraphQL mutations and queries.
- E2E: critical flows (create tournament → invite → join → start round → report result).


