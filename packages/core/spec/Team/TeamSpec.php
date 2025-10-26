<?php

namespace spec\Core\Team;

use Core\Team\Team;
use Core\Team\PokemonSet;
use PhpSpec\ObjectBehavior;

final class TeamSpec extends ObjectBehavior
{
    public function it_starts_empty_and_limits_to_six(): void
    {
        $this->beConstructedWith('My Team');
        $this->name()->shouldBe('My Team');
        $this->sets()->shouldBeLike([]);

        for ($i = 1; $i <= 6; $i++) {
            $this->addSet(new PokemonSet('P'.$i));
        }
        $this->sets()->shouldHaveCount(6);
        $this->shouldThrow(\LogicException::class)->during('addSet', [new PokemonSet('P7')]);
    }
}
