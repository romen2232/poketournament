<?php

namespace spec\App\Utils;

use App\Utils\Health;
use PhpSpec\ObjectBehavior;

final class HealthSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Health::class);
    }

    public function it_returns_ok_status(): void
    {
        $this->status()->shouldReturn('ok');
    }
}
