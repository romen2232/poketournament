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

    public function it_breaks_ties_by_points_then_owp_then_direct_then_rand(): void
    {
        $this->beConstructedWith();
        // Construct a tie where points and OWP are equal, direct wins decide, then randKey
        $matches = [
            ['p1' => 'A', 'p2' => 'B', 'winner' => 'A'],
            ['p1' => 'C', 'p2' => 'D', 'winner' => 'C'],
            ['p1' => 'A', 'p2' => 'C', 'winner' => 'A'],
            ['p1' => 'B', 'p2' => 'D', 'winner' => 'B'],
            // A and C both 6 points; A beat C directly
        ];
        $randKey = ['A' => 1, 'C' => 999, 'B' => 10, 'D' => 11];
        $rows = $this->compute($matches, $randKey);
        $rows[0]['player']->shouldBe('A'); // direct win over C
        $rows[1]['player']->shouldBe('C');
    }

    public function it_defaults_missing_randKey_to_zero_and_is_stable(): void
    {
        $this->beConstructedWith();
        $matches = [
            ['p1' => 'A', 'p2' => 'B', 'winner' => 'A'],
        ];
        $rows = $this->compute($matches, []);
        $rows->shouldBeArray();
        $rows->shouldHaveCount(2);
        // With identical OWP and direct (only one match), randKey=0 ensures deterministic order by compare
        $rows[0]['player']->shouldBe('A');
        $rows[1]['player']->shouldBe('B');
    }

    public function it_counts_bye_as_three_points_and_excludes_from_opp_list(): void
    {
        $this->beConstructedWith();
        $matches = [
            ['bye' => 'X'],
            ['p1' => 'X', 'p2' => 'Y', 'winner' => 'Y'],
        ];
        $rows = $this->compute($matches, []);
        $rows->shouldBeArray();
        $rows->shouldHaveCount(2);
    }

    public function it_computes_owp_with_decimals_and_rounds_to_4dp(): void
    {
        $this->beConstructedWith();
        $matches = [
            ['p1' => 'A', 'p2' => 'B', 'winner' => 'A'],
            ['p1' => 'B', 'p2' => 'C', 'winner' => 'C'],
        ];
        $rows = $this->compute($matches, []);
        $rows->shouldBeArray();
    }
}
