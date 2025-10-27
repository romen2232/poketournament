<?php

namespace spec\App\Infrastructure\Persistence\Mongo;

use App\Infrastructure\Persistence\Mongo\MatchRepository;
use MongoDB\Client;
use MongoDB\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

final class MatchRepositorySpec extends ObjectBehavior
{
    public function let(Client $client, Collection $collection): void
    {
        $client->selectCollection('db', 'matches')->willReturn($collection);
        $this->beConstructedWith($client, 'db');
    }

    public function it_reports_result_and_upserts(Collection $collection): void
    {
        $collection->updateOne(
            [
                'tournamentId' => 'T1',
                'round' => 1,
                'p1' => 'A',
                'p2' => 'B',
            ],
            Argument::that(function ($set) {
                return isset($set['$set'])
                    && $set['$set']['tournamentId'] === 'T1'
                    && $set['$set']['round'] === 1
                    && $set['$set']['p1'] === 'A'
                    && $set['$set']['p2'] === 'B'
                    && $set['$set']['status'] === 'REPORTED'
                    && isset($set['$set']['updatedAt']);
            }),
            ['upsert' => true]
        )->shouldBeCalled();

        $this->report('T1', 1, 'A', 'B', 'A')->shouldBeArray();
    }

    public function it_overrides_result_and_marks_status(Collection $collection): void
    {
        // First call from report()
        $collection->updateOne(
            [
                'tournamentId' => 'T1',
                'round' => 2,
                'p1' => 'C',
                'p2' => 'D',
            ],
            Argument::that(function ($set) {
                return isset($set['$set']) && $set['$set']['status'] === 'REPORTED';
            }),
            ['upsert' => true]
        )->shouldBeCalled();
        // Second call to set OVERRIDDEN
        $collection->updateOne(
            [
                'tournamentId' => 'T1',
                'round' => 2,
                'p1' => 'C',
                'p2' => 'D',
            ],
            Argument::that(function ($set) {
                return isset($set['$set']) && $set['$set']['status'] === 'OVERRIDDEN' && isset($set['$set']['updatedAt']);
            })
        )->shouldBeCalled();

        $this->override('T1', 2, 'C', 'D', 'D')->shouldBeArray();
    }
}
