# Frontend Architecture (Next.js + Screaming Architecture)

The frontend is a Next.js application structured so the directory names reflect business features (“scream” the domain). This improves discoverability, onboarding, and parallel work across features.

## Principles

- Feature-first folders at the top level (Auth, Tournament, Swiss, Match, Draft, Team, Nuzlocke).
- Co-locate UI components, hooks, and GraphQL operations within the feature.
- Shared design-system components and utilities live in dedicated shared folders.
- Server Components where suitable; Client Components for interactive parts.

## Suggested Layout

```
packages/frontend/
  app/
    (auth)/
      login/
      register/
    (tournament)/
      [tournamentId]/
        dashboard/
        standings/
        round/[roundNumber]/
        pickban/[matchId]/
    (team)/
      builder/
    (nuzlocke)/
      page.tsx

  features/
    auth/
      components/
      hooks/
      gql/
    tournament/
      components/
      hooks/
      gql/
    swiss/
      components/
      hooks/
      gql/
    match/
      components/
      hooks/
      gql/
    draft/
      components/
      hooks/
      gql/
    team/
      components/
      hooks/
      gql/
    nuzlocke/
      components/
      hooks/
      gql/

  components/
    pixel/           # DS-style shared components: Button, Panel, BadgeType, Table, Tabs, Modal, Toast, Timer
    forms/

  lib/
    graphql/
      client.ts      # GraphQL client
      fragments.ts   # Shared fragments
      generated.ts   # Codegen output
    state/
      store.ts       # lightweight global state if needed
    utils/
      typeColors.ts  # Type chip color mapping

  styles/
    tokens.css       # DS tokens (colors, spacing, borders)
    globals.css
```

Notes:

- Route groups `(auth)`, `(tournament)`, `(team)`, `(nuzlocke)` are organizational. Business features live in `features/*` with all local code (components, hooks, queries, tests) co-located.
- Pages import from their feature folders and from `components/pixel` for shared UI.

## Data Flow

- GraphQL queries/mutations per feature in `features/*/gql`. Shared fragments in `lib/graphql/fragments.ts`.
- Types are generated via GraphQL codegen and stored in `lib/graphql/generated.ts`.
- Client-side state kept minimal; prefer server data + SWR-style caching.

## Theming & Accessibility

- DS-style pixel theme defined in `styles/tokens.css`. Components consume CSS variables.
- Keyboard accessibility and focus management enforced in modals and forms.

## Testing

- Component tests in feature folders; integration tests for key flows (e.g., report result, start round, draft confirm).


