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
}
