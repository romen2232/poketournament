<?php

namespace spec\Core\Swiss;

use Core\Swiss\StandingsCalculator;
use PhpSpec\ObjectBehavior;

final class StandingsCalculatorSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beAnInstanceOf(StandingsCalculator::class);
    }

    public function it_ranks_by_points_then_owp_then_direct_then_rand(): void
    {
        $matches = [
            ['p1' => 'A', 'p2' => 'B', 'winner' => 'A'],
            ['bye' => 'C'],
            ['p1' => 'A', 'p2' => 'C', 'winner' => 'C'],
            ['bye' => 'B'],
        ];
        $randKey = ['A' => 10, 'B' => 5, 'C' => 7];

        $rows = (new StandingsCalculator())->compute($matches, $randKey);
        $rows->shouldBeArray();
        $rows->shouldHaveCount(3);
        $rows[0]['player']->shouldBe('C');
        $rows[1]['player']->shouldBe('A');
        $rows[2]['player']->shouldBe('B');
    }
}
