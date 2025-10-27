<?php

namespace spec\App\Infrastructure\Persistence\Mongo;

use App\Infrastructure\Persistence\Mongo\RoundRepository;
use MongoDB\Client;
use MongoDB\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

final class RoundRepositorySpec extends ObjectBehavior
{
    public function let(Client $client, Collection $collection): void
    {
        $client->selectCollection('db', 'rounds')->willReturn($collection);
        $this->beConstructedWith($client, 'db');
    }

    public function it_opens_round_and_upserts(Collection $collection): void
    {
        $collection->updateOne(
            ['tournamentId' => 'T1', 'round' => 1],
            Argument::that(function ($set) {
                return isset($set['$set'])
                    && $set['$set']['tournamentId'] === 'T1'
                    && $set['$set']['round'] === 1
                    && $set['$set']['status'] === 'OPEN'
                    && isset($set['$set']['openedAt']);
            }),
            ['upsert' => true]
        )->shouldBeCalled();

        $this->open('T1', 1)->shouldBeArray();
    }

    public function it_closes_round_and_upserts(Collection $collection): void
    {
        $collection->updateOne(
            ['tournamentId' => 'T1', 'round' => 2],
            Argument::that(function ($set) {
                return isset($set['$set'])
                    && $set['$set']['tournamentId'] === 'T1'
                    && $set['$set']['round'] === 2
                    && $set['$set']['status'] === 'CLOSED'
                    && isset($set['$set']['closedAt']);
            }),
            ['upsert' => true]
        )->shouldBeCalled();

        $this->close('T1', 2)->shouldBeArray();
    }
}
