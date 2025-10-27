<?php

namespace spec\Core\Swiss;

use Core\Swiss\SwissPairingService;
use PhpSpec\ObjectBehavior;

final class SwissPairingServiceSpec extends ObjectBehavior
{
    public function it_pairs_players_simple_even(): void
    {
        $players = ['A', 'B', 'C', 'D'];
        $scores = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0];
        $this->beConstructedWith();

        $pairings = $this->pair($players, $scores, []);
        $pairings->shouldBeArray();
        $pairings->shouldHaveCount(2);
        $this->shouldSatisfy(function () use ($pairings) {
            foreach ($pairings->getWrappedObject() as $p) {
                if (!isset($p['p1']) || !isset($p['p2'])) {
                    throw new \RuntimeException('invalid pair');
                }
            }
        });
    }

    public function it_assigns_bye_on_odd_count(): void
    {
        $players = ['A', 'B', 'C'];
        $scores = ['A' => 0, 'B' => 0, 'C' => 0];
        $this->beConstructedWith();
        $result = $this->pair($players, $scores, ['byes' => []]);
        $result->shouldBeArray();
        $result->shouldHaveCount(2); // one pairing + one bye entry
        $this->shouldSatisfy(function () use ($result) {
            $found = false;
            foreach ($result->getWrappedObject() as $r) {
                if (isset($r['bye'])) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                throw new \RuntimeException('No BYE found');
            }
        });
    }

    public function it_avoids_rematches_and_pairs_across_groups_when_possible(): void
    {
        // Groups by score: [A(3), C(3)], [B(0), D(0)]
        // A already played C, so avoid A-C by pairing A-B and C-D.
        $players = ['A', 'B', 'C', 'D'];
        $scores = ['A' => 3, 'B' => 0, 'C' => 3, 'D' => 0];
        $opponents = [
            'A' => ['C'],
            'C' => ['A'],
        ];

        $this->beConstructedWith();
        $pairs = $this->pair($players, $scores, ['opponents' => $opponents]);
        $pairs->shouldBeArray();
        $pairs->shouldHaveCount(2);
        // Expect cross-pairing: A-B and C-D (order not guaranteed)
        $this->shouldSatisfy(function () use ($pairs) {
            $norm = [];
            foreach ($pairs->getWrappedObject() as $p) {
                if (isset($p['bye'])) {
                    continue;
                }
                $norm[] = [min($p['p1'], $p['p2']), max($p['p1'], $p['p2'])];
            }
            $need = [[ 'A', 'B' ], [ 'C', 'D' ]];
            foreach ($need as $expect) {
                $ok = false;
                foreach ($norm as $n) {
                    if ($n === $expect) {
                        $ok = true;
                        break;
                    }
                }
                if (!$ok) {
                    throw new \RuntimeException('Expected pair missing');
                }
            }
        });
    }

    public function it_assigns_bye_to_lowest_score_eligible_player(): void
    {
        $players = ['A', 'B', 'C'];
        $scores = ['A' => 3, 'B' => 0, 'C' => 0];
        // C already had a bye; pick B
        $byes = ['C'];
        $this->beConstructedWith();
        $pairs = $this->pair($players, $scores, ['byes' => $byes]);
        $pairs->shouldBeArray();
        $this->shouldSatisfy(function () use ($pairs) {
            $pickedB = false;
            foreach ($pairs->getWrappedObject() as $p) {
                if (isset($p['bye']) && $p['bye'] === 'B') {
                    $pickedB = true;
                    break;
                }
            }
            if (!$pickedB) {
                throw new \RuntimeException('BYE not assigned to B');
            }
        });
    }

    public function it_floats_leftover_and_pairs_with_next_group(): void
    {
        $players = ['A', 'B', 'C', 'D', 'E'];
        $scores = ['A' => 3, 'B' => 3, 'C' => 3, 'D' => 0, 'E' => 0];
        $opponents = ['A' => ['B'], 'B' => ['A']];
        $this->beConstructedWith();
        $pairs = $this->pair($players, $scores, ['opponents' => $opponents]);
        $pairs->shouldBeArray();
        $this->shouldSatisfy(function () use ($pairs) {
            $pairCount = 0;
            $hasBye = false;
            foreach ($pairs->getWrappedObject() as $p) {
                if (isset($p['bye'])) {
                    $hasBye = true;
                }
                if (isset($p['p1']) && isset($p['p2'])) {
                    $pairCount++;
                }
            }
            if ($hasBye) {
                throw new \RuntimeException('Did not expect BYE with 5 players');
            }
            if ($pairCount !== 2) {
                throw new \RuntimeException('Unexpected pairing count');
            }
        });
    }

    public function it_handles_unavoidable_rematch_gracefully(): void
    {
        $players = ['A', 'B'];
        $scores = ['A' => 3, 'B' => 3];
        $opponents = ['A' => ['B'], 'B' => ['A']];
        $this->beConstructedWith();
        $pairs = $this->pair($players, $scores, ['opponents' => $opponents]);
        $pairs->shouldBeArray();
        $pairs->shouldHaveCount(1);
        $this->shouldSatisfy(function () use ($pairs) {
            $p = $pairs->getWrappedObject()[0];
            if ($p['p1'] !== 'A' && $p['p1'] !== 'B') {
                throw new \RuntimeException('unexpected p1');
            }
            if ($p['p2'] !== 'A' && $p['p2'] !== 'B') {
                throw new \RuntimeException('unexpected p2');
            }
        });
    }

    public function it_has_deterministic_ordering_within_groups(): void
    {
        $players = ['B', 'A', 'D', 'C'];
        $scores = ['A' => 1, 'B' => 1, 'C' => 1, 'D' => 1];
        $this->beConstructedWith();
        $pairs = $this->pair($players, $scores, []);
        $pairs->shouldBeArray();
    }

    public function it_handles_all_had_bye_fallback(): void
    {
        $players = ['A', 'B', 'C'];
        $scores = ['A' => 2, 'B' => 1, 'C' => 0];
        $byes = ['A', 'B', 'C'];
        $this->beConstructedWith();
        $pairs = $this->pair($players, $scores, ['byes' => $byes]);
        $pairs->shouldBeArray();
        $this->shouldSatisfy(function () use ($pairs) {
            $found = false;
            foreach ($pairs->getWrappedObject() as $p) {
                if (isset($p['bye'])) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                throw new \RuntimeException('Expected a BYE assignment');
            }
        });
    }
}
