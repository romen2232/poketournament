# Behat testing guide (GraphQL + human-readable Gherkin)

This project favors human-readable Gherkin. Instead of pasting raw GraphQL JSON into features, describe inputs and actions with tables and natural language. Custom Behat contexts build and post the GraphQL queries for you.

## Principles

- Prefer Given/When/Then in domain terms, not transport details
- Use tables to declare inputs (players, scores, opponents, matches)
- Let contexts construct GraphQL payloads and call the API
- Keep assertions focused on observable outcomes (pairs formed, standings order)

## Provided steps (Context\\GraphQLContext)

- Players and scores
  - Given the following players:
    | player |
  - And the following scores:
    | player | score |
  - And the following previous opponents:
    | player | opponents |  (comma-separated list)

- Pairings query
  - When I query pairings

- Standings query
  - Given the following matches:
    | p1 | p2 | winner | bye |
  - And the following random key:
    | player | key |
  - When I query standings

- Rounds and reporting
  - Given tournament id T1
  - When I open round 1
  - When I report result A vs B winner A in round 1
  - When I close round 1
  - When I override result A vs B winner B in round 1

## Assertions (Context\\JsonContentContext)

- Then the response code should be 200
- And the JSON array at path "data.pair" should contain pairs:
  | p1 | p2 |
- And the JSON array at path "data.pair" should contain a bye for "X"
- And the JSON array at path "data.standings" should contain players with points:
  | player | points |
- And the JSON array at path "data.standings" should have players in order:
  | player |

## Example: pairings

Feature: GraphQL Swiss pairing
  Scenario: Pair players with floating and no rematch
    Given the following players:
      | player |
      | A      |
      | B      |
      | C      |
      | D      |
      | E      |
    And the following scores:
      | player | score |
      | A      | 3     |
      | B      | 3     |
      | C      | 3     |
      | D      | 0     |
      | E      | 0     |
    And the following previous opponents:
      | player | opponents |
      | A      | B         |
      | B      | A         |
    When I query pairings
    Then the response code should be 200
    And the JSON array at path "data.pair" should contain pairs:
      | p1 | p2 |
      | A  | C  |
      | B  | E  |
    And the JSON array at path "data.pair" should contain a bye for "D"

## Example: rounds

Feature: Rounds and match reporting (GraphQL)
  Scenario: Open a round and report a result
    Given tournament id T1
    When I open round 1
    Then the response code should be 200
    When I report result A vs B winner A in round 1
    Then the response code should be 200

  Scenario: Close a round and override a result
    Given tournament id T2
    When I open round 2
    Then the response code should be 200
    When I report result C vs D winner D in round 2
    Then the response code should be 200
    When I close round 2
    Then the response code should be 200
    When I override result C vs D winner C in round 2
    Then the response code should be 200

## Running

- make behat
- or: docker compose exec -T php php vendor/bin/behat

Contexts post to https://nginx/graphql with Host: api.poketournament.local so tests work inside Docker.
