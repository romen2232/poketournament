# Test-Driven Development (TDD) Guide

This guide defines how we apply TDD across backend (Symfony + PHP), frontend (Next.js + TypeScript), and E2E (Playwright) in PokeTournament.

## Principles

- Red → Green → Refactor: write a failing test, make it pass with the smallest change, then improve the design safely.
- Push logic to the domain: keep business rules framework-agnostic and easy to test.
- Fast feedback: unit tests first, e2e as guard rails for critical flows.
- Small steps, frequent commits using Conventional Commits.

## Test Pyramid (what to test)

- Unit tests (fast, many)
  - Backend: phpspec for domain behavior in `backend/spec` vs `backend/src`.
  - Frontend: Jest for components/hooks in `frontend/__tests__`.
- Integration tests (selective)
  - Backend: Behat scenarios (high-level acceptance) and service boundaries (when needed).
- End-to-End tests (few, critical paths)
  - Frontend Playwright specs in `frontend/tests/e2e` for happy-path flows.

## Workflow: Red → Green → Refactor

1) Red (write a failing test)
- Backend: create a new `*Spec.php` in `backend/spec/...` that describes desired behavior.
- Frontend: create a `*.test.ts(x)` in `frontend/__tests__/` for a component/hook.
- E2E (only after unit tests): add Playwright spec for a new user-visible flow.

2) Green (write minimal code)
- Implement the simplest change to satisfy the failing unit test.
- Avoid premature abstractions. Leave refactor for the next step.

3) Refactor (improve design)
- Remove duplication, clarify names, simplify conditionals.
- Keep tests green after each refactor.

## Conventions and Commands

### Backend (PHP)

- Unit specs: `backend/spec/**/FooSpec.php` for `App\\**\\Foo`.
- Run specs:
```bash
make test-backend
# or
docker compose exec -T php php vendor/bin/phpspec run -f dot
```
- Acceptance (BDD): Behat features in `backend/features`. Run with:
```bash
make behat
# or
docker compose exec -T php php vendor/bin/behat
```
- Guidelines:
  - Favor pure domain services and value objects; mock infrastructure (repositories, buses).
  - Do not hit MongoDB in unit specs; use stubs/fakes.

### Frontend (Next.js)

- Unit tests: `frontend/__tests__/**/*.test.tsx`.
- Run unit tests:
```bash
make test-frontend
# or
docker compose exec -T node npm test --silent -- --runInBand
```
- Guidelines:
  - Prefer testing visible behavior over implementation details.
  - Mock network calls; keep components small and predictable.

### End-to-End (Playwright)

- Specs live in `frontend/tests/e2e` (e.g., `home.spec.ts`).
- Base URL (in-container) is configured in `frontend/playwright.config.ts`.
- Run E2E in the official Playwright Docker image:
```bash
make test-e2e
```
- Guidelines:
  - Keep e2e quick; cover critical user journeys end-to-end.
  - Avoid flakiness: prefer test IDs, wait for UI states, don’t rely on timing alone.

## Turning Acceptance Criteria into Tests

- Start with a Behat scenario mirroring user acceptance (Given/When/Then).
- Backfill unit specs to formalize domain rules.
- Add a Playwright spec for the visible UX flow.

## Naming & Structure

- Backend domain: `App\\DomainModule\\*` in `backend/src`; specs mirror folder structure under `backend/spec`.
- Frontend features follow Screaming Architecture; tests co-located in `__tests__` at the app root or under feature dirs as needed.
- E2E: `frontend/tests/e2e/**.spec.ts`.

## Commit & CI Hooks

- Use Conventional Commits (e.g., `feat:`, `fix:`, `chore:`, `test:`).
- `commit-msg` hook enforces the format.
- `pre-push` hook runs unit tests (`make test-backend` and `make test-frontend`). Keep unit tests fast.

## Definition of Done (TDD)

- A failing unit test exists for the behavior (Red) → passes (Green) → code is refactored (Refactor).
- Tests cover typical and edge cases; names describe behavior (not implementation).
- E2E added/updated for critical user-facing flows.
- All hooks pass locally (`commitlint`, unit tests on pre-push).

## Examples (quick starts)

- New domain rule (backend):
  - Add spec in `backend/spec/...Spec.php` → run `make test-backend` → implement in `backend/src/...` → refactor.
- New UI component (frontend):
  - Add test in `frontend/__tests__/...` → run `make test-frontend` → implement component → refactor.
- New flow (e2e):
  - Add spec in `frontend/tests/e2e/...` → run `make test-e2e` → adjust UI/API as needed.

## Anti-Patterns to Avoid

- Writing code first, tests later.
- Over-mocking: mock behavior at boundaries, not the language/runtime.
- Slow tests in pre-push: keep unit tests under a few seconds.
- Huge commits: prefer small, cohesive changes with clear messages.
