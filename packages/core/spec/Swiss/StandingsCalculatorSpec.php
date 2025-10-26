<?php

namespace spec\Core\Swiss;

use Core\Swiss\StandingsCalculator;
use PhpSpec\ObjectBehavior;

final class StandingsCalculatorSpec extends ObjectBehavior
{
    public function it_computes_standings_with_owp_direct_and_rand(): void
    {
        $this->beConstructedWith();

        $matches = [
            // Round 1
            ['p1' => 'A', 'p2' => 'B', 'winner' => 'A'],
            ['bye' => 'C'],
            // Round 2
            ['p1' => 'A', 'p2' => 'C', 'winner' => 'C'],
            ['bye' => 'B'],
        ];

        $randKey = ['A' => 10, 'B' => 5, 'C' => 7];

        $rows = $this->compute($matches, $randKey);
        $rows->shouldBeArray();
        // Expect C at top by points; A and B tie broken by OWP/direct/rand.
        $rows[0]['player']->shouldBe('C');
        $rows->shouldHaveCount(3);
    }
}
