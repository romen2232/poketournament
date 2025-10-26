<?php

namespace spec\Core\Draft;

use Core\Draft\PickBanDraft;
use PhpSpec\ObjectBehavior;

final class PickBanDraftSpec extends ObjectBehavior
{
    public function it_starts_in_banning_and_records_bans(): void
    {
        $this->beConstructedWith('P1', 'P2');
        $this->state()->shouldBe('BANNING');

        $this->ban('P1', 'X');
        $this->ban('P2', 'Y');

        $this->state()->shouldBe('PICKING');
        $this->bans()->shouldBeLike(['P1' => ['X'], 'P2' => ['Y']]);
    }

    public function it_moves_to_confirm_after_picks_and_locks_on_both_confirm(): void
    {
        $this->beConstructedWith('P1', 'P2');
        $this->ban('P1', 'X');
        $this->ban('P2', 'Y');
        $this->pick('P1', 'A');
        $this->pick('P2', 'B');
        $this->state()->shouldBe('CONFIRM');
        $this->confirm('P1');
        $this->state()->shouldBe('CONFIRM');
        $this->confirm('P2');
        $this->state()->shouldBe('LOCKED');
        $this->picks()->shouldBeLike(['P1' => ['A'], 'P2' => ['B']]);
    }

    public function it_rejects_invalid_actor_and_duplicates(): void
    {
        $this->beConstructedWith('P1', 'P2');
        $this->shouldThrow(\InvalidArgumentException::class)->during('ban', ['PX', 'X']);
        $this->ban('P1', 'X');
        $this->shouldThrow(\LogicException::class)->during('ban', ['P1', 'X']);
    }
}
