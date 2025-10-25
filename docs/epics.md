### ✅ **MESSAGE 1/4 — EPICS + USER STORIES + ACCEPTANCE CRITERERIA (BDD)**

This message defines *what* we are building — not *how*. It is your **product backlog foundation**, Jira-ready and fully aligned with the MVP you locked in.

Messages 2–4 will follow after you confirm receipt.

---

## 📌 **EPICS (MVP)**

1. **EPIC – Authentication & User Management**
2. **EPIC – Tournament Core (Swiss System Base + Invitations)**
3. **EPIC – Swiss Pairing Engine & Standings**
4. **EPIC – Match Reporting (Results + BYE handling)**
5. **EPIC – Pick & Ban (PB-2 + Timer + BO1)**
6. **EPIC – Team Builder (Singles + Poképaste)**
7. **EPIC – Nuzlocke System**
8. **EPIC – Pokémon Data Layer (PokeAPI Integration)**
9. **EPIC – UI/Design (DS-Style + Navigation)**
10. **EPIC – DevOps / Deployment / CI**

---

## ✅ **1. EPIC: Authentication & User Management**

### **User Stories**

| ID      | Story                                                                       |
| ------- | --------------------------------------------------------------------------- |
| AUTH-01 | As a user, I want to register via email so I can access the platform.       |
| AUTH-02 | As a user, I want to log in with Google so I can sign in quickly.           |
| AUTH-03 | As a logged user, I want to stay signed in so I don't re-enter credentials. |
| AUTH-04 | As a SuperAdmin, I want to impersonate a user to resolve issues.            |

### **Acceptance Criteria (BDD)**

```
Scenario: Successful email registration
Given I provide a valid email and password
When I submit the registration form
Then my account is created
And I receive an authenticated session
```

```
Scenario: Google login
Given I click "Login with Google"
When Google returns a valid OAuth profile
Then I am signed in
```

---

## ✅ **2. EPIC: Tournament Core (Creation + Invitations)**

### **User Stories**

| ID   | Story                                                                         |
| ---- | ----------------------------------------------------------------------------- |
| T-01 | As a user, I want to create a Swiss tournament so I can organize competition. |
| T-02 | As a tournament admin, I want to send invite links so players can join.       |
| T-03 | As a player, I want to join via invitation so I can participate.              |
| T-04 | As a tournament admin, I want to configure number of Swiss rounds.            |
| T-05 | As a tournament admin, I want to see a list of players in the tournament.     |

### **BDD Acceptance**

```
Scenario: Player joins via invitation
Given I receive a valid invite link
When I open it and log in
Then I am added to the tournament as a player
```

---

## ✅ **3. EPIC: Swiss Pairing Engine & Standings**

### **User Stories**

| ID       | Story                                                                      |
| -------- | -------------------------------------------------------------------------- |
| SWISS-01 | As an admin, I want the system to auto-generate Swiss pairings each round. |
| SWISS-02 | As a player, I want to see my opponent each round.                         |
| SWISS-03 | As a spectator/player, I want standings with tiebreakers.                  |
| SWISS-04 | As a player, I want BYE handled automatically if odd number of players.    |

### **BDD Acceptance**

```
Scenario: Generate Swiss Round
Given a tournament with N players
When the admin starts a round
Then the system pairs players with equal or closest score
And avoids repeat pairings
And assigns a BYE to the lowest-score eligible player if needed
```

---

## ✅ **4. EPIC: Match Reporting**

### **User Stories**

| ID    | Story                                                        |
| ----- | ------------------------------------------------------------ |
| MR-01 | As a player, I want to report my match result.               |
| MR-02 | As staff/admin, I want to approve or override match results. |
| MR-03 | As a player, I want to see round results in the standings.   |

### **BDD Acceptance**

```
Scenario: Match result submission
Given two players have completed their match
When one submits a winner
Then the match is marked as completed
And standings update
```

---

## ✅ **5. EPIC: Pick & Ban (PB-2, Timer, BO1)**

### **User Stories**

| ID    | Story                                                                    |
| ----- | ------------------------------------------------------------------------ |
| PB-01 | As a player, I want to view my opponent’s team and mine before drafting. |
| PB-02 | As a player, I want to ban one opposing Pokémon.                         |
| PB-03 | As a player, I want to pick one opposing Pokémon I force them to bring.  |
| PB-04 | As a player, I want a timer so the phase ends if someone is AFK.         |
| PB-05 | As both players, I want to confirm before the draft ends.                |

### **BDD Acceptance**

```
Scenario: Pick & Ban phase
Given both players enter the draft room
When player A bans a Pokémon and player B bans a Pokémon
And each selects their forced pick
And both press Confirm
Then the final BO1 team composition is locked
```

---

## ✅ **6. EPIC: Team Builder (Singles)**

### **User Stories**

| ID    | Story                                                                |
| ----- | -------------------------------------------------------------------- |
| TB-01 | As a player, I want to create teams with 6 Pokémon.                  |
| TB-02 | As a player, I want to import/export Poképaste.                      |
| TB-03 | As a player, I want to edit EV/IV, nature, item, ability, and moves. |
| TB-04 | As a player, I want drag-and-drop for team slot order.               |

### **BDD Acceptance**

```
Scenario: Import Poképaste
Given I paste a valid Poképaste
When I click Import
Then all 6 Pokémon are created
And displayed in my team builder
```

---

## ✅ **7. EPIC: Nuzlocke System**

### **User Stories**

| ID    | Story                                                               |
| ----- | ------------------------------------------------------------------- |
| NL-01 | As a player, I want to register captures per route.                 |
| NL-02 | As a player, I want to assign status (Captured/Dead/Missed/Traded). |
| NL-03 | As a player, I want a Box view and a Graveyard view.                |
| NL-04 | As a player, I want to see type coverage of my living team.         |
| NL-05 | As a player, I want filters and sorting like in the sheet.          |

### **BDD**

```
Scenario: Mark Pokémon as dead
Given I have a Pokémon in Box or Team
When I set its status to Dead
Then it moves to the Graveyard
```

---

## ✅ **8. EPIC: Pokémon Data Layer**

Stories: PokeAPI fetch, caching, type chart, move typing, item refs.

---

## ✅ **9. EPIC: UI / Design**

Stories: Navigation, pixel theme, responsive rules, layout templates.

---

## ✅ **10. EPIC: DevOps**

Stories: Deployment, SSL, CI, error logging.

---

### ✅ End of **Message 1/4**

Reply with:

```
NEXT
```

and I will send **Message 2/4: Technical Breakdown (Tasks, DDD, DB, GraphQL)**.


Understood — before moving to Message **2/4**, I will **finish EPICS 8, 9, and 10 properly with full User Stories and BDD**, so *Message 1/4* becomes a **complete functional backlog**.

Here is the **continuation and completion of Message 1/4**.

---

## ✅ **8. EPIC: Pokémon Data Layer (PokeAPI Integration)**

This epic ensures your app has all Pokémon data required for:
Team Builder, Pick/Ban, Nuzlocke, and UI displays.

### **User Stories**

| ID      | Story                                                                                                           |
| ------- | --------------------------------------------------------------------------------------------------------------- |
| DATA-01 | As a system, I want to pull species, types, moves, abilities, and items from PokeAPI so the app has valid data. |
| DATA-02 | As a system, I want to cache Pokémon data in the database for fast access.                                      |
| DATA-03 | As a player, I want to see move types with color labels so I can recognize danger quickly.                      |
| DATA-04 | As a player, I want autocomplete when searching Pokémon, moves, or items.                                       |
| DATA-05 | As a system, I want to expose Pokémon data through GraphQL so the frontend doesn’t hit PokeAPI.                 |

### **BDD Acceptance**

```
Scenario: Cached Pokémon data
Given the system has fetched Pokémon from PokeAPI
When the team builder queries a Pokémon name
Then data is served from the local database
Without calling PokeAPI again
```

```
Scenario: Move type color
Given a move has a type (e.g., Fire)
When it is displayed in the UI
Then it shows the corresponding color palette for that element
```

---

## ✅ **9. EPIC: UI / Design (DS-Style + Navigation)**

This epic controls your **consistent pixel-art UX** across all features.

### **User Stories**

| ID    | Story                                                                                                                |
| ----- | -------------------------------------------------------------------------------------------------------------------- |
| UI-01 | As a user, I want a DS-style UI with pixel borders and buttons so the app feels like a Pokémon experience.           |
| UI-02 | As a mobile player, I want a responsive layout so I can play on my phone comfortably.                                |
| UI-03 | As a user, I want a left-side “Pokémon-style navigation menu” for fast access (desktop), and a bottom bar on mobile. |
| UI-04 | As a player, I want screens to animate gently (slide/fade) to mimic DS transitions.                                  |
| UI-05 | As any user, I want a dark theme (Gen5 tone) to reduce eye strain.                                                   |
| UI-06 | As a player, I want consistent UI components for lists, tables, badges, and buttons.                                 |
| UI-07 | As a player, I want color-coded type chips in all views (Builder, P&B, Nuzlocke).                                    |

### **BDD Acceptance**

```
Scenario: Mobile-first layout
Given I open the app on a smartphone
When I navigate between tournament screens
Then all layouts and buttons remain visible and usable
Without horizontal scrolling
```

```
Scenario: DS-style consistency
Given I open different screens (Builder, Swiss Standings, Nuzlocke)
Then buttons, tables, fonts, and borders share a unified pixel-art theme
```

---

## ✅ **10. EPIC: DevOps, Security & Deployment**

This defines how the MVP **runs, updates, and stays secure**.

### **User Stories**

| ID        | Story                                                                        |
| --------- | ---------------------------------------------------------------------------- |
| DEVOPS-01 | As a developer, I want CI so the app builds and tests on each commit.        |
| DEVOPS-02 | As a maintainer, I want staging & production environments for safe releases. |
| DEVOPS-03 | As a user, I want HTTPS so my data and login are secure.                     |
| DEVOPS-04 | As a developer, I want logs and error tracking to debug failures.            |
| DEVOPS-05 | As a system, I want rate-limiting to prevent abuse on auth routes.           |

### **BDD Acceptance**

```
Scenario: Successful deploy
Given a new update is pushed to main
When CI passes
Then the app builds and deploys to production automatically
```

```
Scenario: Security requirements
Given a user logs in
When credentials are transmitted
Then the connection must use HTTPS
```

---

## ✅ **MVP EPICS (now 100% complete)**

| EPIC                             | Status |
| -------------------------------- | ------ |
| Authentication & User Management | ✅      |
| Tournament Core                  | ✅      |
| Swiss Pairing Engine & Standings | ✅      |
| Match Reporting                  | ✅      |
| Pick & Ban                       | ✅      |
| Team Builder                     | ✅      |
| Nuzlocke System                  | ✅      |
| Pokémon Data Layer               | ✅      |
| UI / Design                      | ✅      |
| DevOps / Deployment              | ✅      |

All **User Stories** and **Acceptance Criteria** for MVP are now covered.

---

### 📌 If everything in this message is approved, reply with:

```
NEXT
```

And I will send **Message 2/4: Technical Breakdown**:

It will include:

✅ Tasks per Epic (FE/BE/DB)
✅ DDD Bounded Contexts
✅ Mongo Collections (schemas)
✅ GraphQL Schema Outline

This is where we go from **“What”** → **“How we build it.”**

Waiting for your **NEXT**.


### ✅ **MESSAGE 2/4 — TECHNICAL BREAKDOWN (Tasks, DDD/CQRS, Mongo Schemas, GraphQL Outline)**

This is the **how-to-build** blueprint: tasks per epic (FE/BE/DB), suggested DDD/CQRS boundaries, Mongo collections, and a GraphQL API outline. Copy-pasteable into Jira/Linear.

---

# 0) Monorepo & Project Layout

```
/app
  /frontend   (Next.js 14, App Router)
  /backend    (Symfony + API Platform or custom kernel)
  /packages
    /ui       (shared DS-style components)
    /domain   (TS/JS models for FE; PHP domain for BE lives in backend/src/Domain)
    /graphql  (schema SDL + codegen)
  /infra
    /docker
    /terraform (optional)
```

**Backend (Symfony)**

```
backend/
  src/
    Domain/
      User/            (Aggregates + ValueObjects + Policies)
      Tournament/
      Swiss/
      Match/
      Team/
      Nuzlocke/
      PokeData/
      Shared/          (ID, DateTime, Events)
    Application/
      Command/         (CQRS Commands)
      Query/           (CQRS Queries)
      Handler/         (CommandHandlers, QueryHandlers)
      DTO/
    Infrastructure/
      Persistence/     (Mongo repositories)
      GraphQL/         (resolvers)
      Security/
      Bus/             (command+query bus)
  config/
  public/
```

**Frontend (Next.js)**

```
frontend/
  app/
    (routes)/login
    (routes)/t/:tournamentId/dashboard
    (routes)/t/:tournamentId/standings
    (routes)/t/:tournamentId/round/:roundId
    (routes)/t/:tournamentId/pickban/:matchId
    (routes)/builder
    (routes)/nuzlocke
  lib/graphql/
  lib/state/
  components/pixel/
  components/forms/
  styles/
```

---

# 1) Tasks per EPIC (FE / BE / DB)

## EPIC: Authentication & User Management

**Backend**

* [BE] Install Symfony Security + JWT (or session) auth.
* [BE] Google OAuth via Symfony UX/OAuth2 client; link GoogleId to User.
* [BE] Role system: `SUPER_ADMIN`, `TOURNAMENT_ADMIN`, `STAFF`, `PLAYER`.
* [BE] Impersonation guard for SUPER_ADMIN.
* [BE] GraphQL resolvers: `login`, `register`, `loginWithGoogle`, `me`.

**Frontend**

* [FE] Auth pages (email register/login + Google button).
* [FE] Token/session storage (httpOnly cookie preferred).
* [FE] Route guards (server components + middleware).
* [FE] “Impersonating as …” banner.

**Database**

* [DB] `users` collection with indexes (email unique, googleId).
* [DB] Refresh token store (if using refresh tokens).

---

## EPIC: Tournament Core (Creation + Invitations)

**Backend**

* [BE] Command: `CreateTournament { name, format: "Swiss", rounds }`.
* [BE] Mutation: `createTournament`, `updateTournament`, `deleteTournament`.
* [BE] Invitation tokens: `InvitePlayer { email | username }` → signed URL.
* [BE] Mutation: `acceptInvitation`.
* [BE] Policy: only Admin can manage tournament.

**Frontend**

* [FE] Tournament create wizard (name, rounds, visibility).
* [FE] Players list + “Invite via link” (copy-to-clipboard).
* [FE] Accept invite page.

**Database**

* [DB] `tournaments` collection (config, round count, ownerId).
* [DB] `tournamentPlayers` subdoc or separate collection; unique index (tournamentId, userId).
* [DB] `invites` collection (token, expiresAt, role, tournamentId).

---

## EPIC: Swiss Pairing Engine & Standings

**Backend**

* [BE] Command: `StartSwissRound { tournamentId, roundNumber }`.
* [BE] Service: Swiss pairing algorithm:

  * Sort by points → groups by score.
  * Pair within groups; float if odd.
  * Avoid rematches (track previous opponents).
  * BYE: lowest score eligible, one-time only.
* [BE] Tiebreakers service (OW% → Direct → Random).
* [BE] Query: `currentStandings(tournamentId)`.
* [BE] Events: `SwissRoundStarted`, `PairingsGenerated`.

**Frontend**

* [FE] Round page: show pairings, BYE indicator.
* [FE] Standings table with columns: Pts | OW% | TB2 (Direct) | TB3 (Rand key).
* [FE] Admin “Generate Round” button (disabled until previous round completed).

**Database**

* [DB] `rounds` collection (tournamentId, number, status, pairings[]).
* [DB] `matches` collection (pairings normalized, status, result).
* [DB] `standings` projection (read model updated on results).

---

## EPIC: Match Reporting (Results + BYE handling)

**Backend**

* [BE] Command: `ReportMatchResult { matchId, winnerId }`.
* [BE] Command: `AdminOverrideMatchResult { matchId, winnerId, reason }`.
* [BE] Points policy: Win=3, Loss=0 (configure if needed).
* [BE] BYE handling: auto-win (3 pts) + no TB% change or configurable.
* [BE] Event handlers to recompute standings incrementally.

**Frontend**

* [FE] Match card → “Report result” dialog.
* [FE] Admin override UI with reason.
* [FE] Toasts + optimistic updates.

**Database**

* [DB] `matchResults` (or fields on `matches`).
* [DB] Audit log for overrides.

---

## EPIC: Pick & Ban (PB-2 + Timer + BO1)

**Backend**

* [BE] Command: `StartPickBan { matchId }`.
* [BE] Command: `SubmitBan { matchId, pokemonId }`.
* [BE] Command: `SubmitPick { matchId, pokemonId }`.
* [BE] Command: `ConfirmDraft { matchId, playerId }`.
* [BE] Timer engine (server clock); auto-timeout → “no action”.
* [BE] Read model for draft state.
* [BE] Event: `DraftLocked { forcedPicks, bans }`.

**Frontend**

* [FE] PB-2 dual panel: left=You, right=Opponent; center=timer + log.
* [FE] INFO-B layout: compact tiles; click → modal shows moveset/ability/item/EV/IV.
* [FE] Live draft updates via GraphQL subscriptions or polling.
* [FE] “Confirm” CTA per player; lock state once both confirm or timeout.

**Database**

* [DB] `drafts` (per match): state machine, picks[], bans[], confirm flags, timestamps.

---

## EPIC: Team Builder (Singles + Poképaste)

**Backend**

* [BE] Mutation: `importPokepaste(teamText)` → parser → team doc.
* [BE] Mutation: `createTeam`, `updatePokemonSet`, `deleteTeam`.
* [BE] Validation against PokeData (species/moves/items/abilities).
* [BE] Query: `myTeams`, `team(id)`.

**Frontend**

* [FE] Builder screen: Box (catalog) → Team slots (6), drag & drop.
* [FE] Poképaste import modal (textarea + preview).
* [FE] Pokémon edit modal (species search, moves, EV/IV sliders, item, ability, nature).
* [FE] Export to Poképaste.

**Database**

* [DB] `teams` collection with embedded `pokemonSets` (6 max).
* Index: userId, tournamentId optional, name.

---

## EPIC: Nuzlocke System

**Backend**

* [BE] Entities: `Encounter`, `Status` (Captured/Dead/Missed/Traded), `Box`, `Graveyard`.
* [BE] Mutations: `addEncounter`, `updateEncounter`, `setStatus`, `moveToBox`, `moveToGraveyard`.
* [BE] Query: `myNuzlocke`, filters (type, status).
* [BE] Coverage calculation service (type charts).

**Frontend**

* [FE] Nuzlocke routes table with filters.
* [FE] Box & Graveyard views (grid + pixel cards).
* [FE] Quick actions: evolve, mark dead, mark shiny.
* [FE] Coverage panel chips (missing resistances, STAB coverage).

**Database**

* [DB] `nuzlockeRuns` (userId, game/seed optional), `encounters` embedded or separate.

---

## EPIC: Pokémon Data Layer (PokeAPI)

**Backend**

* [BE] One-time importer jobs: species, types, moves, abilities, items.
* [BE] Local cache + normalizers (Gen5 visuals if needed).
* [BE] Queries: `pokemonByName`, `movesByName`, `itemsByName` (from local DB).
* [BE] Color map per type.

**Frontend**

* [FE] Autocomplete hooks for species/moves/items.
* [FE] Type chip components (consistent palette).

**Database**

* [DB] `pokemon`, `moves`, `items`, `abilities`, `types`.

---

## EPIC: UI/Design (DS-Style + Navigation)

**Frontend**

* [FE] Pixel DS theme tokens: spacing, borders, bevel shadows, fonts.
* [FE] Common components: `PixelButton`, `Panel`, `BadgeType`, `Table`, `Toolbar`, `Tabs`.
* [FE] Mobile nav bar, desktop side nav.
* [FE] Page templates: `DashboardLayout`, `BuilderLayout`, `DraftLayout`.

**Backend**

* [BE] None (style-only), but enforce GraphQL error shape for consistent toasts.

---

## EPIC: DevOps / Security

**Infra**

* [INF] Docker compose (Mongo, backend, frontend).
* [INF] Caddy/NGINX with HTTPS.
* [INF] CI: lint, test, build; env secrets (Google OAuth, JWT secret).
* [INF] Sentry or similar for FE/BE.
* [INF] Backups for Mongo.

**Security**

* [SEC] Rate limit: auth + invites.
* [SEC] RBAC checks per resolver.
* [SEC] Input validation (Zod on FE, Symfony Validators on BE).
* [SEC] CORS, CSRF (use same-site cookies).

---

# 2) DDD / CQRS Bounded Contexts

| Context        | Aggregates                   | Commands (examples)                                                | Events (examples)                                      | Read Models                  |
| -------------- | ---------------------------- | ------------------------------------------------------------------ | ------------------------------------------------------ | ---------------------------- |
| **User**       | User                         | RegisterUser, LinkGoogleAccount, ImpersonateUser                   | UserRegistered                                         | Me                           |
| **Tournament** | Tournament, TournamentPlayer | CreateTournament, InvitePlayer, AcceptInvitation, UpdateTournament | TournamentCreated, PlayerJoined                        | TournamentList, PlayerRoster |
| **Swiss**      | SwissRound                   | StartSwissRound, CloseSwissRound                                   | SwissRoundStarted, PairingsGenerated                   | Standings, RoundPairings     |
| **Match**      | Match                        | ReportMatchResult, AdminOverrideResult                             | MatchReported, ResultOverridden                        | MatchList, ResultsLog        |
| **Draft**      | PickBanDraft                 | StartPickBan, SubmitBan, SubmitPick, ConfirmDraft                  | DraftStarted, BanSubmitted, PickSubmitted, DraftLocked | DraftState                   |
| **Team**       | Team                         | CreateTeam, ImportPokepaste, UpdatePokemonSet                      | TeamCreated, PokepasteImported                         | TeamSummary                  |
| **Nuzlocke**   | NuzlockeRun, Encounter       | AddEncounter, UpdateEncounter, SetStatus                           | EncounterAdded, EncounterStatusChanged                 | NuzlockeOverview             |
| **PokeData**   | (catalog)                    | ImportPokeData                                                     | PokeDataImported                                       | Catalogs                     |

**CQRS**

* Commands mutate aggregates, emit domain events.
* Projectors update read models in Mongo (often separate collections optimized for UI).
* Queries read from projections (fast tables: standings, pairings, draft state).

---

# 3) MongoDB Collections (MVP)

```js
// users
{
  _id, email, passwordHash?, googleId?, roles: ["PLAYER"|"STAFF"|"TOURNAMENT_ADMIN"|"SUPER_ADMIN"],
  displayName, createdAt
}

// tournaments
{
  _id, ownerId, name, format: "SWISS",
  settings: { rounds: Number, tiebreakers: ["OWP","DIRECT","RANDOM"] },
  createdAt
}

// tournamentPlayers
{ _id, tournamentId, userId, nickname, hasBye: false, score: 0, opponents: [userId] }

// invites
{ _id, tournamentId, role: "PLAYER", token, expiresAt, email? }

// rounds
{
  _id, tournamentId, number: 1, status: "PENDING"|"ONGOING"|"CLOSED",
  pairings: [{ matchId, p1Id, p2Id, tableNo }],
  bye: { playerId? }
}

// matches
{
  _id, tournamentId, roundNumber, p1Id, p2Id, status: "PENDING"|"REPORTED"|"CONFIRMED",
  winnerId?, reportedBy?, reportedAt, draftId?
}

// drafts (Pick & Ban)
{
  _id, matchId, state: "IDLE"|"BANNING"|"PICKING"|"CONFIRM"|"LOCKED",
  timer: { endsAt, durationSec: 180 },
  p1: { teamId, bans: [pokemonUid], picks: [pokemonUid], confirmed: false },
  p2: { teamId, bans: [pokemonUid], picks: [pokemonUid], confirmed: false },
  log: [{ at, actorId, action, payload }]
}

// teams
{
  _id, userId, tournamentId?, name,
  sets: [{
    uid, species, level, nature, ability, item,
    evs: { hp, atk, def, spa, spd, spe },
    ivs: { hp, atk, def, spa, spd, spe },
    moves: [moveId],
    types: [typeId]
  }]
}

// nuzlockeRuns
{ _id, userId, title, createdAt, encounters: [Encounter] }

/// Encounter
// { route, species, nickname, status: "Captured"|"Dead"|"Missed"|"Traded", shiny: bool,
//   ability, moves: [moveId], nature, evs, ivs, item, inTeam: bool, notes? }

// pokeData (catalogs) – each has its own collection with indexes
// pokemon { _id: speciesId, name, types:[], baseStats:{...}, abilities:[], sprites:{...} }
// moves   { _id: moveId, name, type, category, power, accuracy, pp }
// items   { _id: itemId, name, effect }
// abilities { _id: abilityId, name, desc }
// types   { _id: typeId, name, colorBg, colorFg }

// standings (projection)
{
  _id, tournamentId, rows: [
    { userId, score, oppWinPct, directWins: Number, randKey }
  ],
  updatedAt
}
```

**Indexes**

* `tournamentPlayers` → `{ tournamentId:1, userId:1 } unique`
* `matches` → `{ tournamentId:1, roundNumber:1 }`
* `rounds` → `{ tournamentId:1, number:1 } unique`
* `drafts` → `{ matchId:1 } unique`
* `teams` → `{ userId:1, tournamentId:1 }`
* Catalogs: name text indexes + `_id` strict.

---

# 4) GraphQL Schema Outline (SDL)

```graphql
scalar DateTime
scalar ObjectID

enum Role { SUPER_ADMIN TOURNAMENT_ADMIN STAFF PLAYER }
enum RoundStatus { PENDING ONGOING CLOSED }
enum DraftState { IDLE BANNING PICKING CONFIRM LOCKED }
enum NuzStatus { CAPTURED DEAD MISSED TRADED }

type User { id: ID!, displayName: String!, roles: [Role!]!, email: String }
type Tournament {
  id: ID!, name: String!, format: String!, createdAt: DateTime!,
  settings: TournamentSettings!, owner: User!, players: [TournamentPlayer!]!
}
type TournamentSettings { rounds: Int!, tiebreakers: [String!]! }
type TournamentPlayer { user: User!, nickname: String, hasBye: Boolean!, score: Int! }

type Round {
  id: ID!, number: Int!, status: RoundStatus!,
  pairings: [Pairing!]!, bye: Bye
}
type Pairing { matchId: ID!, p1: User!, p2: User!, tableNo: Int }
type Bye { player: User! }

type Match {
  id: ID!, tournament: Tournament!, roundNumber: Int!,
  p1: User!, p2: User!, status: String!, winner: User, draft: Draft
}

type Draft {
  id: ID!, state: DraftState!, timerEndsAt: DateTime,
  p1: DraftSide!, p2: DraftSide!, log: [DraftLog!]!
}
type DraftSide {
  player: User!, team: Team!, bans: [PokemonSetUID!]!, picks: [PokemonSetUID!]!, confirmed: Boolean!
}
type DraftLog { at: DateTime!, actor: User!, action: String!, payload: String! }

type Team { id: ID!, name: String!, sets: [PokemonSet!]! }
type PokemonSet {
  uid: ID!, species: String!, types: [String!]!,
  ability: String, item: String, nature: String,
  evs: Stats, ivs: Stats, moves: [String!]!
}
type Stats { hp: Int, atk: Int, def: Int, spa: Int, spd: Int, spe: Int }

type StandingsRow { player: User!, score: Int!, oppWinPct: Float!, directWins: Int!, randKey: Int! }
type Standings { tournament: Tournament!, rows: [StandingsRow!]!, updatedAt: DateTime! }

type NuzlockeRun { id: ID!, title: String!, encounters: [Encounter!]! }
type Encounter {
  id: ID!, route: String!, species: String!, nickname: String,
  status: NuzStatus!, shiny: Boolean!, inTeam: Boolean!,
  ability: String, item: String, nature: String,
  evs: Stats, ivs: Stats, moves: [String!]!, notes: String
}

# Catalogs
type Pokemon { id: ID!, name: String!, types: [String!]!, baseStats: Stats!, abilities: [String!]!, sprite: String }
type Move { id: ID!, name: String!, type: String!, power: Int, accuracy: Int, pp: Int }
type Item { id: ID!, name: String!, effect: String }
type Ability { id: ID!, name: String!, description: String }
type TypeRef { id: ID!, name: String!, colorBg: String!, colorFg: String! }

type Query {
  me: User

  tournaments: [Tournament!]!
  tournament(id: ID!): Tournament

  rounds(tournamentId: ID!): [Round!]!
  round(tournamentId: ID!, number: Int!): Round

  matches(tournamentId: ID!, roundNumber: Int): [Match!]!
  standings(tournamentId: ID!): Standings!

  myTeams: [Team!]!
  team(id: ID!): Team

  myNuzlocke: NuzlockeRun

  # Catalogs
  pokemonSearch(query: String!): [Pokemon!]!
  moveSearch(query: String!): [Move!]!
  itemSearch(query: String!): [Item!]!
}

type Mutation {
  # Auth
  register(email: String!, password: String!, displayName: String!): User!
  login(email: String!, password: String!): String! # jwt
  loginWithGoogle(idToken: String!): String!

  # Tournament
  createTournament(name: String!, rounds: Int!): Tournament!
  invitePlayer(tournamentId: ID!, email: String!): String! # invite URL
  acceptInvitation(token: String!): Tournament!

  # Swiss
  startSwissRound(tournamentId: ID!, number: Int!): Round!
  closeSwissRound(tournamentId: ID!, number: Int!): Round!

  # Match Results
  reportMatchResult(matchId: ID!, winnerUserId: ID!): Match!
  adminOverrideMatchResult(matchId: ID!, winnerUserId: ID!, reason: String!): Match!

  # Draft (Pick & Ban)
  startPickBan(matchId: ID!): Draft!
  submitBan(matchId: ID!, pokemonUid: ID!): Draft!
  submitPick(matchId: ID!, pokemonUid: ID!): Draft!
  confirmDraft(matchId: ID!): Draft!

  # Teams
  createTeam(name: String!): Team!
  importPokepaste(text: String!): Team!
  updatePokemonSet(teamId: ID!, set: PokemonSetInput!): Team!
  deleteTeam(teamId: ID!): Boolean!

  # Nuzlocke
  addEncounter(runId: ID!, input: EncounterInput!): NuzlockeRun!
  updateEncounter(runId: ID!, encounterId: ID!, input: EncounterInput!): NuzlockeRun!
  setEncounterStatus(runId: ID!, encounterId: ID!, status: NuzStatus!): NuzlockeRun!
}

input PokemonSetInput {
  uid: ID
  species: String!
  ability: String
  item: String
  nature: String
  evs: StatsInput
  ivs: StatsInput
  moves: [String!]!
}
input StatsInput { hp: Int, atk: Int, def: Int, spa: Int, spd: Int, spe: Int }

input EncounterInput {
  route: String!, species: String!, nickname: String, status: NuzStatus!,
  shiny: Boolean, inTeam: Boolean, ability: String, item: String, nature: String,
  evs: StatsInput, ivs: StatsInput, moves: [String!], notes: String
}

type Subscription {
  draftUpdated(matchId: ID!): Draft!
  roundStatusChanged(tournamentId: ID!): Round!
}
```

---

# 5) Swiss Pairing Algorithm (Implementation Notes)

* **Score Groups**: group players by current score.
* **Pair Within Group**: greedy pairing; if odd, “float” one to the adjacent group.
* **Avoid Rematches**: maintain `opponents[]` per player; skip pairs already played.
* **BYE**: select from lowest score group among eligible (hasBye=false). Mark `hasBye=true`, grant win points, do not affect OWP negatively (treat as neutral opponent with 0% or exclude from OW%—choose and keep consistent).
* **Opponents’ Win % (OWP)**: For each player, average of opponents’ win % (exclude BYE). Recompute after each round closure.
* **Direct Encounter**: if tied and exactly one beat the other, that one ranks higher.
* **Random**: persist a `randKey` at tournament join time for deterministic tie resolution.

---

# 6) Security & Validation

* Use **httpOnly secure cookies** for session/JWT.
* **RBAC decorators** on resolvers: `@Authorized([TOURNAMENT_ADMIN])`.
* **Input validation**: Symfony Validators + FE Zod schemas.
* **Rate limiting**: login, invite, result submit.
* **Anti-cheat** for P&B**: server-side authoritative state; ignore late/invalid actions; all transitions go through command handler; timer stored server-side.

---

# 7) Testing Strategy

* **Unit**: pairing service (edge cases: odd players, rematch avoidance).
* **Integration**: GraphQL mutations for round start, result report update standings.
* **E2E**: Cypress/Playwright for flows: create tournament → invite → join → start round → P&B → report result → standings.

---

# 8) Performance & Caching

* **Catalogs**: preloaded and cached in Mongo; FE caches species/moves/items with SWR.
* **Standings**: projection updated on match result event; O(1) query on UI.
* **Draft**: use subscriptions or short polling (3–5s) with ETag to reduce load.

---

# 9) Definition of Done (technical)

* GraphQL types have nullability aligned with UI.
* All mutations authorized + validated; errors mapped to UI toasts.
* Read models (standings, pairings, draft) exist and are used by UI; no heavy joins at query-time.
* Dark theme & DS-style components applied to all new pages.

---

That’s the **technical backbone** of the MVP: tasks, domains, storage, and API.

If this looks good, reply with:

```
NEXT
```

and I’ll send **Message 3/4 — UI/UX Mockups (DS-Style), Navigation, and Responsive Rules**, including the **PB-2 + INFO-B** draft screen, Team Builder, Nuzlocke, Standings, and more.


### 🎨 **MESSAGE 3/4 — UI/UX MOCKUPS (DS-STYLE), NAVIGATION, SCREEN-BY-SCREEN, RESPONSIVE RULES**

Mobile-first, DS-style pixel aesthetic (Option A). PB-2 layout with INFO-B (compact tiles + modal for details). This is a **textual mockup spec** you can hand to design/FE directly.

---

# 0) Design System — DS-Style Pixel UI

## 0.1 Tokens

* **Font**: “Press Start 2P” (titles, badges), “Inter/Roboto” (body) — fallbacks safe.
* **Base size**: 8px grid. DS look uses chunky 2–3px borders and 1–2px inner bevels.
* **Radii**: 0 (squares) and 4px for “soft” panels only.
* **Borders**: `2px #1B1B1B` outer; inner highlight `1px rgba(255,255,255,0.18)` for bevel; drop-shadow `0 2px 0 #0E0E0E`.
* **Elevation**: Panels stack via double borders (outer dark + inner light).
* **Spacing**: XS=4, S=8, M=12, L=16, XL=24, XXL=32.

## 0.2 Palette (Gen-5 dark vibe)

* **Surface 1**: `#0F1320`
* **Surface 2 (Panel)**: `#171B2E`
* **Surface 3 (Input)**: `#1F2540`
* **Borders**: `#0B0D16`
* **Text Primary**: `#F2F4FF`
* **Text Secondary**: `#9AA2C6`
* **Accent**: `#6AA9FF`
* **Danger**: `#FF5C7A`
* **Success**: `#56E39F`
* **Warning**: `#FFC857`
* **Type chips**: standard Pokémon color mapping (Fire `#EE8130`, Water `#6390F0`, Grass `#7AC74C`, …). Text color automatically `#0B0B0B` or `#FFFFFF` based on contrast.

## 0.3 Components (shared)

* **PixelButton**: sizes S/M/L; default, primary, danger; disabled state reduces contrast by 40%.
* **PixelInput**: inset bevel, caret thick; validation states with left icon.
* **PixelSelect / Autocomplete**: list with type chips on options.
* **BadgeType**: rounded 2px, uppercase type name, auto color.
* **Panel**: header bar (darker strip), body grid; optional footer with actions.
* **Table**: thick header, zebra rows, fixed columns on mobile.
* **Tabs**: big DS tabs with notch highlight.
* **Toast**: slide-up from bottom, DS ping sound optional (toggleable).
* **Modal**: panel with dimmed overlay, pixel corners, focus trap, ESC to close.
* **Timer**: segmented digits `MM:SS` with blinking colon.

## 0.4 Interactions & Animations

* **Page transition**: 150ms slide-in + 100ms fade.
* **Button press**: down-state shifts content by 1px.
* **Focus ring**: 2px `#6AA9FF` outer glow for accessibility.
* **Drag & drop**: pixel “ghost” placeholder (dashed border).

## 0.5 Accessibility

* All interactive elements have a11y labels.
* Color contrast AA+.
* Keyboard nav: Tab sequences, Enter/Space, ESC to dismiss modals.
* Live regions for timer warnings (Pick & Ban).

---

# 1) Navigation & IA

## 1.1 Top-level flows

* **Login** → **Tournament Dashboard** → (Standings | Round Pairings | Pick & Ban | Match Result)
* **Builder** (global) → create/edit teams.
* **Nuzlocke** (global) → runs, encounters, views.
* **Tournament Settings** (admin only).

## 1.2 Nav components

* **Desktop**: left side menu with icons + labels.

  * Dashboard
  * Standings
  * Round
  * Pick & Ban (contextual: only open if in draft)
  * Teams
  * Nuzlocke
  * Settings (admin)
* **Mobile**: bottom bar with 4 primary tabs: Dashboard, Standings, Round, Teams. Settings via kebab menu.

---

# 2) Screen-by-Screen Mockups (Textual)

> Legend:
> `[]` container/panel, `()` label, `•` item, `→` action, `{}` meta.

## 2.1 Login / Auth

```
[ Panel: Sign In ]
  (Logo PokeTournament – pixel logo)
  [ Tabs ]  (Email) | (Google)
  (Email)  [ Input: email ]
  (Password)[ Input: •••••• ]
  [ Primary Button: Sign in ]
  [ Link: Create account ]
  [ Divider: or ]
  [ Button: Continue with Google (G icon) ]
  (Footer) "By signing in you accept ..."
```

**Mobile**: Full-height panel, center; keyboard pushes panel above.

---

## 2.2 Tournament Dashboard

```
[ Header: Tournament Name ]  [ Button: Invite Players ] {Admin only}
[ Panel: Quick Actions ]
  • Rounds: 1 / N       • Players: X
  • Next: Start Round (if previous closed) {Admin}
  [ Button: View Standings ] [ Button: View Current Round ]

[ Panel: Announcements / Activity ]
  • Round 2 started by Admin
  • Match result reported: A def. B
```

**Empty states**: “No rounds yet. Start round 1.”

---

## 2.3 Standings (Swiss)

```
[ Header: Standings ]
[ Table ]
  | # | Player     | Score | OWP% | Direct | Rand |
  | 1 | Red        |   9   | 61.5 |  -     | 4217 |
  | 2 | Leaf       |   9   | 57.1 |  W vs1 | 0382 |
  ...
[ Info Bar ] Tiebreakers: Opponents’ Win % → Direct Encounter → Random
```

**Row interaction**: click to open player detail (modal): opponents faced, results, OWP components.

**Mobile**: collapse to 2 columns + expand:

* Primary: `#`, `Player`, `Score`
* Secondary below: `OWP%`, `Direct`, `Rand`

---

## 2.4 Round Pairings

```
[ Header: Round 3 Pairings ]   [ Button: Start Round / Close Round (Admin) ]
[ Grid cards (2 columns desktop, 1 mobile) ]
  [ Card: Table 1 ]
    (P1) Red  vs  (P2) Leaf
    [ Button: Enter Draft (Pick & Ban) ] [ Button: Report Result ]
  [ Card: Table 2 ] ...
  [ Card: BYE ]
    (Assigned to: Blue)
    (Auto-win 3 pts)  [ Info: BYE given to lowest score eligible ]
```

**States**: PENDING/ONGOING/CLOSED tags on header.

---

## 2.5 Match Result Form

```
[ Modal: Report Result ]
  (Match) Red vs Leaf
  [ Radio ] Red won
  [ Radio ] Leaf won
  [ Textarea ] Notes (optional)
  [ Primary ] Submit     [ Secondary ] Cancel
```

**Admin override**: additional select + “reason” required.

---

## 2.6 Pick & Ban — PB-2 Layout (INFO-B)

**Top summary bar**

```
[ Panel: Draft — Round 3, Table 1 ]
  (Timer) [ 02:59 ]  (Phase) BANNING → PICKING → CONFIRM
  (You) Red  |  (Opp) Leaf
  [ Log icon ] Recent actions...
```

**Main dual panels**

```
[ Panel Left: Your Team (6 slots + bench if more) ]
  [ Grid of tiles ]
    • Tile: Species sprite + name
      [ Icons row ] (Type chip x2) (Item icon) (Ability glyph) (Moves icon 4 dots)
      [ Action ] Click → Modal with full moveset, EV/IV, nature (read-only)
      [ State badges ] PICKED / BANNED when applied

[ Panel Right: Opponent Team ]
  (Same tiles; click to view details in modal)
```

**Center column**

```
[ Panel Center: Action & Log ]
  [ Phase Tag: "Your BAN" or "Your PICK" or "Waiting"]
  [ Button grid ]
     • During BAN: Opponent tiles become selectable; confirm needed.
     • During PICK: Opponent tiles selectable; confirm needed.
  [ Button: Confirm action ] (disabled until a selection)
  [ Draft Log ]
     02:47 Red BANNED: Garchomp
     02:33 Leaf PICKED FOR Red: Rotom-W
```

**Modal (on tile click)**

```
[ Modal: Pokémon Details ]
  [ Sprite + name + types + base stats bars ]
  (Held Item) [ badge ]
  (Ability) [ badge ]  (Nature) [ badge ]
  (EV/IV summary) "252 Atk / 4 Def / 252 Spe" etc
  [ Moves list ]
    • Move name [Type chip] [Power/Acc]
  [ Close ]
```

**Timer behaviors**

* At 0:00, if no action from current actor: auto-skip with `“No action”` entry in log.
* Both must press Confirm during CONFIRM phase; if one doesn’t, countdown to force lock after 10s.

**Mobile**

* Vertical stacking: Your panel → Center Actions → Opponent panel.
* Sticky timer at top.
* Tile grid 2-per-row.

---

## 2.7 Team Builder (Singles)

**Layout**

```
[ Header: Team Builder ]
[ Toolbar ]
  [ Button: New Team ] [ Button: Import Poképaste ] [ Button: Export ]
  [ Search: species/items/moves ]
[ Panel: Team (6 slots, horizontal on desktop, vertical on mobile) ]
  [ Slot 1 ] [ Slot 2 ] ... [ Slot 6 ]
  Drag species from Box → Slot

[ Panel: Box / Catalog ]
  [ Filters ] Type chips, roles (Wall, Sweeper, Support), text search
  [ Grid: Pokémon species tiles ]
    • Sprite, name, types, base role tags
```

**Slot tile**

```
[ Tile ]
  Sprite + Name
  (Item) (Ability) (Nature)
  [ Mini moves row: move icons colored by type ]
  [ Edit ] opens modal
```

**Edit Modal**

```
[ Modal: Edit Pokémon ]
  [ Autocomplete: Species ]
  [ Row ] Ability | Item | Nature
  [ EV sliders ] HP/Atk/Def/SpA/SpD/Spe (sum cap hint)
  [ IV inputs ] 0–31 (quick 0/31 toggles)
  [ Moves ] 4 selectors with autocomplete + type chip color
  [ Save ] [ Cancel ]
```

**Poképaste Import**

```
[ Modal: Import ]
  [ Textarea: paste raw ]
  [ Preview panel ] Parsed sets with warnings
  [ Import ]
```

**Mobile**

* Accordion: Team first; Box below; edit modal full-screen.

---

## 2.8 Nuzlocke

**Routes View**

```
[ Header: Nuzlocke — Run “Kanto Ironman” ]
[ Toolbar ]
  [ Button: Add Encounter ] [ Filter: Status ] [ Filter: Type ] [ Search ]
[ Table ]
  | Route | Pokémon | Nick | Status | Shiny | Ability | Item | Moves | In Team |
  | Rt 1  | Pidgey  | ...  | Captured | [] | Keen Eye | ... | [••••]| [✓]
```

* Moves column shows four small color dots; click to view modal details.

**Box View**

```
[ Grid of cards ]
  [ Card ]
    Sprite + Nickname + Species
    [ Chips: Types ][ Shiny? ]
    [ Buttons ] Evolve | Mark Dead | Move to Team
```

**Graveyard**

* Desaturated sprites, memory card vibe.

**Coverage Panel**

```
[ Panel: Type Coverage (living team) ]
  | Type | Resistances | Weaknesses | STAB presence |
```

**Mobile**

* Tabs: Routes | Box | Graveyard | Coverage.

---

## 2.9 Tournament Settings (Admin)

```
[ Panel: Settings ]
  (Rounds) [ Stepper 1..N ]
  (Tiebreakers) [ Fixed: OWP → Direct → Random ] (info text)
  (Invites) [ Button: Generate Invite Link ] [ List of pending invites ]
  [ Danger ] Delete Tournament
```

---

# 3) System Overlays, States, and Messages

* **Success toast**: green, “Saved!”.
* **Error toast**: red, first GraphQL error message.
* **Blocking loader**: Pokéball spinner (8-bit).
* **Empty states**: DS art inline + “Press START to add…” vibe.
* **Error boundaries**: show retry button with small log id.

---

# 4) Responsive Rules (Mobile-First)

* **Min width breakpoints**: 0, 480, 768, 1024, 1440.
* **Tables**: collapse into cards <768; essential columns stay visible.
* **Grids**: 1 col on mobile, 2–3 on tablet, 4–6 on desktop.
* **Sticky elements**: Pick & Ban timer + phase bar; bottom action bar on mobile (Confirm).
* **Hit areas**: min 40x40px; spacing increases on mobile.

---

# 5) Microcopy (consistent, game-like)

* Start Swiss Round → “START ROUND”
* Report result → “DECLARE WINNER”
* Confirm draft → “LOCK IN”
* BYE note → “You sit out this round. +3 pts”

---

# 6) Iconography

* **Item**: bag icon
* **Ability**: star/spark
* **Moves**: 4 dots in type colors
* **Timer**: hourglass pixel
* **Status**: Captured ✓, Dead ✖, Missed 〜, Traded ⇄

---

# 7) Sounds (optional toggle)

* Button press click
* Draft “lock” thunk
* Error buzz
* Toast ping

---

# 8) Empty & Error Scenarios (per screen)

* **Standings**: “No results yet. Finish a round to see rankings.”
* **Round Pairings**: “Round not started. Admin must press START.”
* **Pick & Ban**: “Waiting for opponent to join…” + invite to refresh.
* **Team Builder**: “No team yet. Import a Poképaste or add from Box.”
* **Nuzlocke**: “No encounters recorded. Add your first one!”

---

# 9) Reusability & Theming Notes

* All screens use `Panel` + `Toolbar` + `Table/Grid` pattern.
* Theming via CSS variables: colors, border widths, pixel shadow helpers.
* Support light/dark later by overriding surfaces and text only (types stay same).

---

# 10) Handover Checklist for FE

* [ ] Implement DS tokens as CSS vars.
* [ ] Build core components: Button, Input, Select, Panel, Table, Tabs, BadgeType, Modal, Toast, Timer.
* [ ] Apply mobile layouts first; add desktop enhancements.
* [ ] Respect loading/skeleton states per screen.
* [ ] Ensure a11y and keyboard support on modals/forms.

---

**End of Message 3/4 — UI/UX Mockups.**

If you’re happy with this, reply:

```
NEXT
```

and I’ll send **Message 4/4 — Roadmap & Milestones (M1→M4), Delivery Order, and Definition of Done**.


### ✅ **MESSAGE 4/4 — ROADMAP, MILESTONES, DELIVERY ORDER, DEFINITION OF DONE**

This final message tells you **how to execute development in phases**, in the most efficient order, with dependencies and a clean Definition of Done for each feature.

---

# 1) MVP ROADMAP OVERVIEW (4 Milestones)

| Milestone | Title                       | Goal                                     | Key Deliverables                                   |
| --------- | --------------------------- | ---------------------------------------- | -------------------------------------------------- |
| **M1**    | *Core Platform & Auth*      | App can create + join tournaments        | Auth, Invitations, Tournament creation, Navigation |
| **M2**    | *Swiss Engine + Match Flow* | Swiss rounds fully playable (no P&B yet) | Pairings, Standings, Match Result                  |
| **M3**    | *Pick & Ban + Team Builder* | Matches use BO1 Pick/Ban workflow        | PB-2, Team Builder, Draft locking                  |
| **M4**    | *Nuzlocke System + Polish*  | Final MVP parity with Sheet + UX polish  | Nuzlocke views, Coverage, cleanup                  |

---

# 2) MILESTONE DETAILS

---

### ✅ **M1 — Core Platform & Auth (Foundation)**

**Objective:** Users can log in and join tournaments.

**Backend**

* Auth (Email + Google)
* JWT or httpOnly cookie session
* CreateTournament / Invite / AcceptInvitation
* Tournament player list queries
* Role guards
* Basic error handling format
* PokeAPI catalog import (baseline: Pokémon, types, moves, items, abilities)

**Frontend**

* Login & Register pages
* Google OAuth button
* Tournament creation flow
* Invitation acceptance
* Basic navigation layout (DS-style)
* “My tournaments“ list
* Skeleton loading states

**Result:**
✅ You can log in, create a Swiss tournament, invite players, and view participant list.

---

### ✅ **M2 — Swiss Engine & Match Flow (Play a Swiss tournament)**

**Objective:** Swiss pairing works from R1 → Final Standing.

**Backend**

* Swiss pairing algorithm
* BYE rule (lowest score + never twice)
* Round lifecycle (start/close)
* Match creation
* ReportMatchResult + AdminOverride
* Standings projection (OWP → Direct → Random)

**Frontend**

* Standings UI (desktop + mobile collapse)
* Round Pairings screen
* Match result modal
* Admin round controls (Start / Close)
* BYE display
* Toasts, errors, loading states

**Result:**
✅ Tournaments can be fully played & completed **without Pick & Ban**.

---

### ✅ **M3 — Pick & Ban (PB-2) & Team Builder**

**Objective:** BO1 draft + team management integrated into match flow.

**Backend**

* Draft state machine
* SubmitPick / SubmitBan / ConfirmDraft
* Timer
* Team builder mutations (create/update/importPokepaste)
* Validation against PokeData

**Frontend**

* PB-2 Dual Panel screen (INFO-B modal)
* Timer + log + confirms
* Team Builder UI w/ drag & drop & modals
* Poképaste import/export
* Redirect Match → P&B before result

**Result:**
✅ Each match now has **Pick/Ban + locked BO1 roster**, then reports result.

---

### ✅ **M4 — Nuzlocke System + Polish**

**Objective:** Finalize MVP with full Sheet replacement and UI refinement.

**Backend**

* Nuzlocke mutations/queries
* Encounter storage + status changes
* Coverage service

**Frontend**

* Routes table, Box, Graveyard, Coverage tabs
* Pixel polish on remaining UI
* Error pages, empty states, audio toggle, theme refine

**Result:**
✅ Nuzlocke matches/exceeds spreadsheet usability, and the MVP is feature-complete.

---

# 3) WEEK-BY-WEEK EXECUTION (RECOMMENDED 6–8 weeks)

| Week | Focus                                   |
| ---- | --------------------------------------- |
| 1    | M1 backend + DB + PokeAPI importer      |
| 2    | M1 frontend + auth guard + navigation   |
| 3    | Swiss backend + standings calculations  |
| 4    | Swiss frontend + match reporting        |
| 5    | Pick/Ban backend + Team Builder backend |
| 6    | PB-2 UI + Team Builder UI               |
| 7    | Nuzlocke backend + UI                   |
| 8    | Polish, QA, deployment, bug fixing      |

---

# 4) DEVELOPMENT ORDER (MANDATORY DEPENDENCIES)

```
Auth → Tournament Core → Swiss Engine → Match Flow → Team Builder → Pick & Ban → Nuzlocke → Polish
```

Pick & Ban **depends on** Team Builder.
Match flow **depends on** Swiss engine.
Swiss engine **depends on** Tournament Core & Auth.

This order prevents rewrites and keeps morale high (visible progress each milestone).

---

# 5) DEFINITION OF DONE (GLOBAL)

A feature is considered **DONE** when:

✅ Backend:

* Command + Handler + Repository logic implemented
* Unit tests for domain logic (especially Swiss & Draft)
* Query returns correct projection
* Authz + validation errors are clean and typed

✅ Frontend:

* Mobile-first layout, no horizontal scroll
* Loading & error states handled
* Accessible (keyboard + focus-trap for modals)
* Uses shared pixel components
* Toast or inline error on failure

✅ Integration:

* GraphQL mutation tested end-to-end (UI → DB)
* Feature persists on refresh
* No console errors

✅ QA:

* UI matches mockup intent
* Flow complete without dead ends
* Works on Chrome + Mobile Safari

---

# 6) DEPLOYMENT PLAN

**Environments**

* `dev.local` (Docker + hot reload)
* `staging.domain.com` (auth + seed data)
* `app.domain.com` (prod + backups)

**Infra baseline**

* Caddy/NGINX reverse proxy
* HTTPS (Let’s Encrypt)
* CI build → deploy staging → promote to prod

---

# ✅ FINAL RESULT

With Messages **1–4**, you now have:

| Document      | Purpose                                            |
| ------------- | -------------------------------------------------- |
| **Message 1** | Functional backlog (Epics → Stories → BDD)         |
| **Message 2** | Technical blueprint (DDD, schemas, GraphQL, tasks) |
| **Message 3** | UI/UX mockups and navigation                       |
| **Message 4** | Roadmap, milestones, workflow, DoD                 |

This is a **production-grade MVP plan**, clear enough for a real team to start coding tomorrow.

---

### If you want, next I can additionally deliver (optional):

Reply with any of these:

| Reply     | I will deliver                                                                                |
| --------- | --------------------------------------------------------------------------------------------- |
| `BONUS-A` | Full **file-by-file backend scaffold** (Symfony commands, dirs, first aggregates, interfaces) |
| `BONUS-B` | Full **file-by-file React/Next folder with components + routes + SCSS**                       |
| `BONUS-C` | **GraphQL schema full SDL** + codegen instructions                                            |
| `BONUS-D` | **Task timeline with estimates** (hours / story points)                                       |
| `BONUS-E` | **Wireframe images (ASCII or Figma-style text)**                                              |
| `BONUS-F` | **Git branching strategy + CI config**                                                        |

Or reply:

```
DONE
```

if you want to end the backlog phase here.

Your move.
