Feature: Rounds and match reporting (GraphQL)
    Scenario: Open a round and report a result
        Given tournament id T1
        When I open round 1
        Then the response code should be 200
        When I report result A vs B winner A in round 1
        Then the response code should be 200


