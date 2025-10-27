Feature: GraphQL Swiss pairing
    Scenario: Pair players with floating and no rematch
        When I POST to "/graphql" with JSON body:
            """
            {
                "query": "query P($players:[String!]!, $scores:[ScoreInput!]!, $opponents:[OpponentInput!]){ pair(players:$players, scores:$scores, opponents:$opponents){ p1 p2 bye } }",
                "variables": {
                    "players": [
                        "A",
                        "B",
                        "C",
                        "D",
                        "E"
                    ],
                    "scores": [
                        {
                            "player": "A",
                            "score": 3
                        },
                        {
                            "player": "B",
                            "score": 3
                        },
                        {
                            "player": "C",
                            "score": 3
                        },
                        {
                            "player": "D",
                            "score": 0
                        },
                        {
                            "player": "E",
                            "score": 0
                        }
                    ],
                    "opponents": [
                        {
                            "player": "A",
                            "opponents": [
                                "B"
                            ]
                        },
                        {
                            "player": "B",
                            "opponents": [
                                "A"
                            ]
                        }
                    ]
                }
            }
            """
        Then the response code should be 200
        And the JSON array at path "data.pair" should contain pairs:
            | p1 | p2 |
            | A  | C  |
            | B  | E  |
        And the JSON array at path "data.pair" should contain a bye for "D"


