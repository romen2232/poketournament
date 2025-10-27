<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Mongo;

use MongoDB\Client;
use MongoDB\Collection;

final class RoundRepository
{
    private Collection $collection;

    public function __construct(Client $client, string $databaseName)
    {
        $this->collection = $client->selectCollection($databaseName, 'rounds');
    }

    public function open(string $tournamentId, int $round): array
    {
        $doc = [
            'tournamentId' => $tournamentId,
            'round' => $round,
            'status' => 'OPEN',
            'openedAt' => (new \DateTimeImmutable('now'))->__toString(),
        ];
        $this->collection->updateOne(
            ['tournamentId' => $tournamentId, 'round' => $round],
            ['$set' => $doc],
            ['upsert' => true]
        );
        return $doc;
    }

    public function close(string $tournamentId, int $round): array
    {
        $doc = [
            'tournamentId' => $tournamentId,
            'round' => $round,
            'status' => 'CLOSED',
            'closedAt' => (new \DateTimeImmutable('now'))->__toString(),
        ];
        $this->collection->updateOne(
            ['tournamentId' => $tournamentId, 'round' => $round],
            ['$set' => $doc],
            ['upsert' => true]
        );
        return $doc;
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    public function findByTournament(string $tournamentId): array
    {
        $cursor = $this->collection->find(['tournamentId' => $tournamentId], ['sort' => ['round' => 1]]);
        $rows = [];
        foreach ($cursor as $doc) {
            $row = $doc->getArrayCopy();
            unset($row['_id']);
            $rows[] = $row;
        }
        return $rows;
    }
}
