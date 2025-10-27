<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Mongo;

use MongoDB\Client;
use MongoDB\Collection;

final class MatchRepository
{
    private Collection $collection;

    public function __construct(Client $client, string $databaseName)
    {
        $this->collection = $client->selectCollection($databaseName, 'matches');
    }

    public function report(string $tournamentId, int $round, string $p1, string $p2, string $winner): array
    {
        $doc = [
            'tournamentId' => $tournamentId,
            'round' => $round,
            'p1' => $p1,
            'p2' => $p2,
            'winner' => $winner,
            'status' => 'REPORTED',
            'updatedAt' => (new \DateTimeImmutable('now'))->format(DATE_ATOM),
        ];
        $this->collection->updateOne(
            [
                'tournamentId' => $tournamentId,
                'round' => $round,
                'p1' => $p1,
                'p2' => $p2,
            ],
            ['$set' => $doc],
            ['upsert' => true]
        );
        return $doc;
    }

    public function override(string $tournamentId, int $round, string $p1, string $p2, string $winner): array
    {
        $doc = $this->report($tournamentId, $round, $p1, $p2, $winner);
        $doc['status'] = 'OVERRIDDEN';
        $this->collection->updateOne(
            [
                'tournamentId' => $tournamentId,
                'round' => $round,
                'p1' => $p1,
                'p2' => $p2,
            ],
            ['$set' => ['status' => 'OVERRIDDEN', 'updatedAt' => (new \DateTimeImmutable('now'))->format(DATE_ATOM)]]
        );
        return $doc;
    }

    /**
     * Record a BYE assignment as a match-like document
     */
    public function recordBye(string $tournamentId, int $round, string $player): array
    {
        $doc = [
            'tournamentId' => $tournamentId,
            'round' => $round,
            'bye' => $player,
            'status' => 'BYE',
            'updatedAt' => (new \DateTimeImmutable('now'))->format(DATE_ATOM),
        ];
        $this->collection->updateOne(
            [
                'tournamentId' => $tournamentId,
                'round' => $round,
                'bye' => $player,
            ],
            ['$set' => $doc],
            ['upsert' => true]
        );
        return $doc;
    }

    /**
     * Fast check for BYE eligibility
     */
    public function hasBye(string $tournamentId, string $player): bool
    {
        return $this->collection->countDocuments([
            'tournamentId' => $tournamentId,
            'bye' => $player,
        ]) > 0;
    }

    /**
     * @return string[] players who already had a BYE
     */
    public function getByesByTournament(string $tournamentId): array
    {
        $cursor = $this->collection->find([
            'tournamentId' => $tournamentId,
            'bye' => ['$exists' => true],
        ], ['projection' => ['bye' => 1]]);
        $players = [];
        foreach ($cursor as $doc) {
            $row = $doc->getArrayCopy();
            if (isset($row['bye']) && is_string($row['bye'])) {
                $players[$row['bye']] = true;
            }
        }
        return array_keys($players);
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    public function list(string $tournamentId, ?int $round = null): array
    {
        $filter = ['tournamentId' => $tournamentId];
        if ($round !== null) {
            $filter['round'] = $round;
        }
        $cursor = $this->collection->find($filter, ['sort' => ['round' => 1, 'updatedAt' => 1]]);
        $rows = [];
        foreach ($cursor as $doc) {
            $row = $doc->getArrayCopy();
            unset($row['_id']);
            $rows[] = $row;
        }
        return $rows;
    }
}
