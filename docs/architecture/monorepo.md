# Monorepo Layout (packages/)

The project is structured as a monorepo to keep frontend, backend, and the reusable domain in one place, while enabling independent versioning and CI per package.

## Top-Level Structure

```
packages/
  backend/     # Symfony application (GraphQL, application + infrastructure layers)
  frontend/    # Next.js application (Screaming Architecture)
  core/        # External domain package (pure PHP, framework-agnostic)

infra/         # Reverse proxy, certificates, deployment assets
docker/        # Dockerfiles and local compose fragments
docs/          # Human-written documentation
```

Notes:

- `packages/core` contains the business domain (aggregates, value objects, policies, domain events). It has no knowledge of Symfony or transport concerns.
- `packages/backend` wires the domain to adapters: persistence (Mongo), GraphQL resolvers, security, and messaging.
- `packages/frontend` consumes the GraphQL API and organizes code by domain feature.

## Tooling & Workspaces

- Node: Yarn/PNPM workspaces for `packages/frontend`.
- PHP: Composer manages `packages/backend` and `packages/core` dependencies (backend depends on `core`).
- Codegen: GraphQL schema/codegen lives with the backend; TypeScript types are generated for the frontend.

## Environments & Configuration

- `.env` per package with safe defaults; secrets via environment variables in CI/CD.
- Local development via Docker Compose: MongoDB, backend, frontend, reverse proxy.
- Production via NGINX (or Caddy) with HTTPS.

## CI Outline

1. Lint + typecheck per package (ESLint/TS, PHP-CS-Fixer/PHPStan).
2. Unit tests (domain, backend, frontend).
3. Build artifacts (Next.js static/server), backend container.
4. Deploy to staging on main merges; promote to prod after approval.

## Migration Note (from current layout)

The repository currently has `backend/` and `frontend/` at the root. During the migration to `packages/`:

1. Create `packages/backend` and move the backend application code there.
2. Create `packages/frontend` and move the Next.js code there.
3. Extract the pure domain into `packages/core`; add Composer package reference from backend.
4. Update Docker and CI paths to point to `packages/*`.

This documentation assumes the target monorepo structure even if the physical move is staged.


