Feature: Rounds and match reporting (GraphQL)
    Scenario: Open a round and report a result
        When I POST to "/graphql" with JSON body:
            """
            {
                "query": "mutation O($tid:String!, $r:Int!){ openRound(tournamentId:$tid, round:$r){ tournamentId round status } }",
                "variables": {
                    "tid": "T1",
                    "r": 1
                }
            }
            """
        Then the response code should be 200
        When I POST to "/graphql" with JSON body:
            """
            {
                "query": "mutation R($tid:String!, $r:Int!, $p1:String!, $p2:String!, $w:String!){ reportResult(tournamentId:$tid, round:$r, p1:$p1, p2:$p2, winner:$w){ tournamentId round p1 p2 winner status } }",
                "variables": {
                    "tid": "T1",
                    "r": 1,
                    "p1": "A",
                    "p2": "B",
                    "w": "A"
                }
            }
            """
        Then the response code should be 200


