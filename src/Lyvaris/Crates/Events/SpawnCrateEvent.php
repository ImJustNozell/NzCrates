<?php

namespace Lyvaris\Crates\Events;

use pocketmine\event\Event;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\player\Player;

class SpawnCrateEvent extends Event implements Cancellable
{
    use CancellableTrait;

    private Player $player;
    private string $crateType;

    public function __construct(Player $player, string $crateType)
    {
        $this->player = $player;
        $this->crateType = $crateType;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getCrateType(): string
    {
        return $this->crateType;
    }
}
