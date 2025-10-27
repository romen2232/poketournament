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
use Symfony\Component\HttpFoundation\Request;

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

        $roundType = new ObjectType([
            'name' => 'Round',
            'fields' => [
                'tournamentId' => Type::nonNull(Type::string()),
                'round' => Type::nonNull(Type::int()),
                'status' => Type::nonNull(Type::string()),
                'openedAt' => Type::string(),
                'closedAt' => Type::string(),
                'closedBy' => Type::string(),
            ],
        ]);

        $matchType = new ObjectType([
            'name' => 'Match',
            'fields' => [
                'tournamentId' => Type::nonNull(Type::string()),
                'round' => Type::nonNull(Type::int()),
                'p1' => Type::nonNull(Type::string()),
                'p2' => Type::nonNull(Type::string()),
                'winner' => Type::nonNull(Type::string()),
                'status' => Type::nonNull(Type::string()),
                'updatedAt' => Type::string(),
                'overriddenBy' => Type::string(),
            ],
        ]);

        $byeType = new ObjectType([
            'name' => 'Bye',
            'fields' => [
                'tournamentId' => Type::nonNull(Type::string()),
                'round' => Type::nonNull(Type::int()),
                'player' => Type::nonNull(Type::string()),
                'status' => Type::nonNull(Type::string()),
                'updatedAt' => Type::string(),
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

        $isAdmin = function ($context): bool {
            $req = $context['request'] ?? null;
            if (!$req instanceof Request) {
                return false;
            }
            return (string)$req->headers->get('X-Admin', '0') === '1';
        };
        $actorId = function ($context): ?string {
            $req = $context['request'] ?? null;
            if (!$req instanceof Request) {
                return null;
            }
            return $req->headers->get('X-Actor');
        };

        $mutation = new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'openRound' => [
                    'type' => $roundType,
                    'args' => [
                        'tournamentId' => Type::nonNull(Type::string()),
                        'round' => Type::nonNull(Type::int()),
                    ],
                    'resolve' => function ($root, array $args) use ($actorId): array {
                        $doc = $this->rounds->open($args['tournamentId'], (int)$args['round']);
                        return $doc;
                    },
                ],
                'closeRound' => [
                    'type' => $roundType,
                    'args' => [
                        'tournamentId' => Type::nonNull(Type::string()),
                        'round' => Type::nonNull(Type::int()),
                    ],
                    'resolve' => function ($root, array $args, $context) use ($isAdmin, $actorId): array {
                        if (!$isAdmin($context)) {
                            throw new \RuntimeException('Forbidden');
                        }
                        $doc = $this->rounds->close($args['tournamentId'], (int)$args['round']);
                        $doc['closedBy'] = $actorId($context);
                        return $doc;
                    },
                ],
                'reportResult' => [
                    'type' => $matchType,
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
                    'type' => $matchType,
                    'args' => [
                        'tournamentId' => Type::nonNull(Type::string()),
                        'round' => Type::nonNull(Type::int()),
                        'p1' => Type::nonNull(Type::string()),
                        'p2' => Type::nonNull(Type::string()),
                        'winner' => Type::nonNull(Type::string()),
                    ],
                    'resolve' => function ($root, array $args, $context) use ($isAdmin, $actorId): array {
                        if (!$isAdmin($context)) {
                            throw new \RuntimeException('Forbidden');
                        }
                        $doc = $this->matches->override(
                            $args['tournamentId'],
                            (int)$args['round'],
                            $args['p1'],
                            $args['p2'],
                            $args['winner']
                        );
                        $doc['overriddenBy'] = $actorId($context);
                        return $doc;
                    },
                ],
                'recordBye' => [
                    'type' => $byeType,
                    'args' => [
                        'tournamentId' => Type::nonNull(Type::string()),
                        'round' => Type::nonNull(Type::int()),
                        'player' => Type::nonNull(Type::string()),
                    ],
                    'resolve' => function ($root, array $args): array {
                        $doc = $this->matches->recordBye($args['tournamentId'], (int)$args['round'], $args['player']);
                        return [
                            'tournamentId' => $doc['tournamentId'],
                            'round' => $doc['round'],
                            'player' => $doc['bye'],
                            'status' => $doc['status'],
                            'updatedAt' => $doc['updatedAt'] ?? null,
                        ];
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
                        'tournamentId' => Type::string(),
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
                        $byes = $args['byes'] ?? [];
                        if (!empty($args['tournamentId'])) {
                            try {
                                $persisted = $this->matches->getByesByTournament($args['tournamentId']);
                            } catch (\Throwable $e) {
                                $persisted = [];
                            }
                            $byes = array_values(array_unique(array_merge($byes, $persisted)));
                        }
                        return $this->pairing->generate(
                            $args['players'],
                            $scoresMap,
                            ['byes' => $byes, 'opponents' => $opponentsMap]
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
                'listRounds' => [
                    'type' => Type::nonNull(Type::listOf($roundType)),
                    'args' => [
                        'tournamentId' => Type::nonNull(Type::string()),
                    ],
                    'resolve' => function ($root, array $args): array {
                        return $this->rounds->findByTournament($args['tournamentId']);
                    },
                ],
                'listMatches' => [
                    'type' => Type::nonNull(Type::listOf($matchType)),
                    'args' => [
                        'tournamentId' => Type::nonNull(Type::string()),
                        'round' => Type::int(),
                    ],
                    'resolve' => function ($root, array $args): array {
                        return $this->matches->list($args['tournamentId'], isset($args['round']) ? (int)$args['round'] : null);
                    },
                ],
            ],
        ]);

        return new Schema(['query' => $query, 'mutation' => $mutation]);
    }
}
