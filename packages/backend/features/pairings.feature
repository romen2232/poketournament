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

    Scenario: Do not reassign BYE in subsequent rounds
        Given tournament id T100
        And the following players:
            | player |
            | A      |
            | B      |
            | C      |
        And the following scores:
            | player | score |
            | A      | 0     |
            | B      | 0     |
            | C      | 0     |
        When I record a bye for A in round 1
        Then the response code should be 200
        When I query pairings
        Then the response code should be 200
        And the JSON array at path "data.pair" should contain a bye for "B"


