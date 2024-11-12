<?php

namespace Lyvaris\Crates\Rewards;

use pocketmine\item\Item;

class Reward
{
    private Item $item;
    private float $chance;
    private int $slot;

    public function __construct(Item $item, float $chance, int $slot)
    {
        $this->item = $item;
        $this->chance = $chance;
        $this->slot = $slot;
    }

    public function getItem(): Item
    {
        return $this->item;
    }

    public function getChance(): float
    {
        return $this->chance;
    }

    public function getSlot(): int
    {
        return $this->slot;
    }

    public function setSlot(int $slot): void
    {
        $this->slot = $slot;
    }

    public function setChance(float $chance): void
    {
        $this->chance = $chance;
    }
}
