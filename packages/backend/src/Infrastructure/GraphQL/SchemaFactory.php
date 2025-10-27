<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL;

use App\Application\Swiss\PairingService;
use Core\Swiss\StandingsCalculator;
use App\Infrastructure\Persistence\Mongo\RoundRepository;
use App\Infrastructure\Persistence\Mongo\MatchRepository;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;

final class SchemaFactory
{
    public function __construct(
        private readonly PairingService $pairing,
        private readonly StandingsCalculator $standings,
        private readonly RoundRepository $rounds,
        private readonly MatchRepository $matches
    ) {
    }

    public function create(): Schema
    {
        $pairingType = new ObjectType([
            'name' => 'Pairing',
            'fields' => [
                'p1' => Type::string(),
                'p2' => Type::string(),
                'bye' => Type::string(),
            ],
        ]);

        $standingType = new ObjectType([
            'name' => 'Standing',
            'fields' => [
                'player' => Type::nonNull(Type::string()),
                'points' => Type::nonNull(Type::int()),
                'oppWinPct' => Type::nonNull(Type::float()),
                'directWins' => Type::nonNull(Type::int()),
                'randKey' => Type::nonNull(Type::int()),
            ],
        ]);

        // Input types
        $scoreInput = new \GraphQL\Type\Definition\InputObjectType([
            'name' => 'ScoreInput',
            'fields' => [
                'player' => Type::nonNull(Type::string()),
                'score' => Type::nonNull(Type::int()),
            ],
        ]);
        $opponentInput = new \GraphQL\Type\Definition\InputObjectType([
            'name' => 'OpponentInput',
            'fields' => [
                'player' => Type::nonNull(Type::string()),
                'opponents' => Type::nonNull(Type::listOf(Type::nonNull(Type::string()))),
            ],
        ]);
        $matchInput = new \GraphQL\Type\Definition\InputObjectType([
            'name' => 'MatchInput',
            'fields' => [
                'p1' => Type::string(),
                'p2' => Type::string(),
                'winner' => Type::string(),
                'bye' => Type::string(),
            ],
        ]);
        $randKeyInput = new \GraphQL\Type\Definition\InputObjectType([
            'name' => 'RandKeyInput',
            'fields' => [
                'player' => Type::nonNull(Type::string()),
                'key' => Type::nonNull(Type::int()),
            ],
        ]);

        $mutation = new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'openRound' => [
                    'type' => new ObjectType([
                        'name' => 'Round',
                        'fields' => [
                            'tournamentId' => Type::nonNull(Type::string()),
                            'round' => Type::nonNull(Type::int()),
                            'status' => Type::nonNull(Type::string()),
                        ],
                    ]),
                    'args' => [
                        'tournamentId' => Type::nonNull(Type::string()),
                        'round' => Type::nonNull(Type::int()),
                    ],
                    'resolve' => function ($root, array $args): array {
                        return $this->rounds->open($args['tournamentId'], (int)$args['round']);
                    },
                ],
                'closeRound' => [
                    'type' => new ObjectType([
                        'name' => 'ClosedRound',
                        'fields' => [
                            'tournamentId' => Type::nonNull(Type::string()),
                            'round' => Type::nonNull(Type::int()),
                            'status' => Type::nonNull(Type::string()),
                        ],
                    ]),
                    'args' => [
                        'tournamentId' => Type::nonNull(Type::string()),
                        'round' => Type::nonNull(Type::int()),
                    ],
                    'resolve' => function ($root, array $args): array {
                        return $this->rounds->close($args['tournamentId'], (int)$args['round']);
                    },
                ],
                'reportResult' => [
                    'type' => new ObjectType([
                        'name' => 'Match',
                        'fields' => [
                            'tournamentId' => Type::nonNull(Type::string()),
                            'round' => Type::nonNull(Type::int()),
                            'p1' => Type::nonNull(Type::string()),
                            'p2' => Type::nonNull(Type::string()),
                            'winner' => Type::nonNull(Type::string()),
                            'status' => Type::nonNull(Type::string()),
                        ],
                    ]),
                    'args' => [
                        'tournamentId' => Type::nonNull(Type::string()),
                        'round' => Type::nonNull(Type::int()),
                        'p1' => Type::nonNull(Type::string()),
                        'p2' => Type::nonNull(Type::string()),
                        'winner' => Type::nonNull(Type::string()),
                    ],
                    'resolve' => function ($root, array $args): array {
                        return $this->matches->report(
                            $args['tournamentId'],
                            (int)$args['round'],
                            $args['p1'],
                            $args['p2'],
                            $args['winner']
                        );
                    },
                ],
                'overrideResult' => [
                    'type' => new ObjectType([
                        'name' => 'OverriddenMatch',
                        'fields' => [
                            'tournamentId' => Type::nonNull(Type::string()),
                            'round' => Type::nonNull(Type::int()),
                            'p1' => Type::nonNull(Type::string()),
                            'p2' => Type::nonNull(Type::string()),
                            'winner' => Type::nonNull(Type::string()),
                            'status' => Type::nonNull(Type::string()),
                        ],
                    ]),
                    'args' => [
                        'tournamentId' => Type::nonNull(Type::string()),
                        'round' => Type::nonNull(Type::int()),
                        'p1' => Type::nonNull(Type::string()),
                        'p2' => Type::nonNull(Type::string()),
                        'winner' => Type::nonNull(Type::string()),
                    ],
                    'resolve' => function ($root, array $args): array {
                        return $this->matches->override(
                            $args['tournamentId'],
                            (int)$args['round'],
                            $args['p1'],
                            $args['p2'],
                            $args['winner']
                        );
                    },
                ],
            ],
        ]);

        $query = new ObjectType([
            'name' => 'Query',
            'fields' => [
                'pair' => [
                    'type' => Type::nonNull(Type::listOf($pairingType)),
                    'args' => [
                        'players' => Type::nonNull(Type::listOf(Type::nonNull(Type::string()))),
                        'scores' => Type::nonNull(Type::listOf(Type::nonNull($scoreInput))),
                        'byes' => Type::listOf(Type::nonNull(Type::string())),
                        'opponents' => Type::listOf(Type::nonNull($opponentInput)),
                    ],
                    'resolve' => function ($root, array $args): array {
                        $scoresMap = [];
                        foreach ($args['scores'] as $row) {
                            $scoresMap[$row['player']] = (int)$row['score'];
                        }
                        $opponentsMap = [];
                        if (isset($args['opponents'])) {
                            foreach ($args['opponents'] as $row) {
                                $opponentsMap[$row['player']] = $row['opponents'];
                            }
                        }
                        return $this->pairing->generate(
                            $args['players'],
                            $scoresMap,
                            ['byes' => $args['byes'] ?? [], 'opponents' => $opponentsMap]
                        );
                    },
                ],
                'standings' => [
                    'type' => Type::nonNull(Type::listOf($standingType)),
                    'args' => [
                        'matches' => Type::nonNull(Type::listOf(Type::nonNull($matchInput))),
                        'randKey' => Type::listOf(Type::nonNull($randKeyInput)),
                    ],
                    'resolve' => function ($root, array $args): array {
                        $rand = [];
                        if (isset($args['randKey'])) {
                            foreach ($args['randKey'] as $row) {
                                $rand[$row['player']] = (int)$row['key'];
                            }
                        }
                        return $this->standings->compute($args['matches'], $rand);
                    },
                ],
            ],
        ]);

        return new Schema(['query' => $query, 'mutation' => $mutation]);
    }
}
