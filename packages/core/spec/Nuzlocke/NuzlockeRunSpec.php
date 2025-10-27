<?php

namespace spec\Core\Nuzlocke;

use Core\Nuzlocke\Encounter;
use Core\Nuzlocke\NuzlockeRun;
use PhpSpec\ObjectBehavior;

final class NuzlockeRunSpec extends ObjectBehavior
{
    public function it_adds_and_updates_encounters_and_sets_status(): void
    {
        $this->beConstructedWith('Kanto Ironman');
        $this->title()->shouldBe('Kanto Ironman');
        $this->encounters()->shouldBeLike([]);

        $enc = new Encounter('Route 1', 'Pidgey');
        $this->addEncounter($enc);
        $this->encounters()->shouldHaveCount(1);

        $this->setStatus('Route 1', 'DEAD');
        $this->encounters()[0]->status()->shouldBe('DEAD');
    }

    public function it_throws_when_setting_status_for_unknown_route(): void
    {
        $this->beConstructedWith('Run');
        $this->shouldThrow(\InvalidArgumentException::class)->during('setStatus', ['Unknown', 'ALIVE']);
    }
}
