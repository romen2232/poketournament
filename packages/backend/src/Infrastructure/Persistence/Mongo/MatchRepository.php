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
