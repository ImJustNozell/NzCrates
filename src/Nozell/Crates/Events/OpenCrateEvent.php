<?php

namespace Nozell\Crates\Events;

use pocketmine\event\Event;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\player\Player;
use pocketmine\entity\Entity;

class OpenCrateEvent extends Event implements Cancellable
{
    use CancellableTrait;

    private Player $player;
    private Entity $entity;
    private string $crateLabel;

    public function __construct(Player $player, Entity $entity, string $crateLabel)
    {
        $this->player = $player;
        $this->entity = $entity;
        $this->crateLabel = $crateLabel;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getEntity(): Entity
    {
        return $this->entity;
    }

    public function getCrateLabel(): string
    {
        return $this->crateLabel;
    }
}
