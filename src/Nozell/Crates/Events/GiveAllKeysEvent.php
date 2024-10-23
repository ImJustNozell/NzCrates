<?php

namespace Nozell\Crates\Events;

use pocketmine\event\Event;
use pocketmine\player\Player;

class GiveAllKeysEvent extends Event
{
    private Player $player;
    private string $keyType;
    private int $amount;

    public function __construct(Player $player, string $keyType, int $amount)
    {
        $this->player = $player;
        $this->keyType = $keyType;
        $this->amount = $amount;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getKeyType(): string
    {
        return $this->keyType;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
