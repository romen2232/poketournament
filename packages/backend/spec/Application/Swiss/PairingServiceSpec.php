<?php

namespace spec\App\Application\Swiss;

use App\Application\Swiss\PairingService;
use Core\Swiss\SwissPairingService as CoreSwissPairingService;
use PhpSpec\ObjectBehavior;

final class PairingServiceSpec extends ObjectBehavior
{
    public function let(CoreSwissPairingService $core): void
    {
        $this->beConstructedWith($core);
    }

    public function it_delegates_to_core_pairing(CoreSwissPairingService $core): void
    {
        $players = ['A', 'B'];
        $scores = ['A' => 0, 'B' => 0];
        $expected = [ ['p1' => 'A', 'p2' => 'B'] ];

        $core->pair($players, $scores, [])->willReturn($expected);

        $this->generate($players, $scores, [])->shouldReturn($expected);
    }
}

