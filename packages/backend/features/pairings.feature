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


