Feature: GraphQL standings endpoint
    Scenario: Compute standings with bye and winner
        When I POST to "/graphql" with JSON body:
            """
            {
                "query": "query S($matches:[MatchInput!]!, $randKey:[RandKeyInput!]){ standings(matches:$matches, randKey:$randKey){ player points } }",
                "variables": {
                    "matches": [
                        {
                            "p1": "A",
                            "p2": "B",
                            "winner": "A"
                        },
                        {
                            "bye": "C"
                        }
                    ],
                    "randKey": [
                        {
                            "player": "A",
                            "key": 1
                        },
                        {
                            "player": "B",
                            "key": 2
                        },
                        {
                            "player": "C",
                            "key": 3
                        }
                    ]
                }
            }
            """
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


