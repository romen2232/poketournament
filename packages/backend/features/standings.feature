Feature: GraphQL standings endpoint
    Scenario: Compute standings with bye and winner
        Given the following matches:
            | p1 | p2 | winner | bye |
            | A  | B  | A      |     |
            |    |    |        | C   |
        And the following random key:
            | player | key |
            | A      | 1   |
            | B      | 2   |
            | C      | 3   |
        When I query standings
        Then the response code should be 200
        And the JSON array at path "data.standings" should contain players with points:
            | player | points |
            | A      | 3      |
            | C      | 3      |
            | B      | 0      |
        And the JSON array at path "data.standings" should have players in order:
            | player |
            | A      |
            | C      |


