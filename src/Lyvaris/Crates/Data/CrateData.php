<?php

namespace Lyvaris\Crates\Data;

use Lyvaris\Crates\Rewards\Reward;

class CrateData
{
    private string $crateLabel;
    private array $rewards = [];

    public function __construct(string $crateLabel, array $rewards = [])
    {
        $this->crateLabel = $crateLabel;
        $this->rewards = $rewards;
    }

    public function getCrateLabel(): string
    {
        return $this->crateLabel;
    }

    public function getRewards(): array
    {
        return $this->rewards;
    }

    public function setRewards(array $rewards): void
    {
        $this->rewards = $rewards;
    }

    public function getRewardForSlot(int $slot): ?Reward
    {
        return $this->rewards[$slot] ?? null;
    }

    public function addReward(Reward $reward): void
    {
        $this->rewards[$reward->getSlot()] = $reward;
    }

    public function removeReward(int $slot): void
    {
        unset($this->rewards[$slot]);
    }
}
