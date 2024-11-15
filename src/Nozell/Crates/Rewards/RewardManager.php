<?php

namespace Nozell\Crates\Rewards;

use pocketmine\utils\SingletonTrait;

class RewardManager
{
    use SingletonTrait;
    private array $cratesRewards = [];

    public function addRewardToCrate(string $crateType, Reward $reward): void
    {
        if (!isset($this->cratesRewards[$crateType])) {
            $this->cratesRewards[$crateType] = [];
        }
        $this->cratesRewards[$crateType][$reward->getSlot()] = $reward;
    }

    public function removeRewardFromCrate(string $crateType, int $slot): void
    {
        if (isset($this->cratesRewards[$crateType][$slot])) {
            unset($this->cratesRewards[$crateType][$slot]);
        }
    }

    public function getRewardsForCrate(string $crateType): array
    {
        return $this->cratesRewards[$crateType] ?? [];
    }

    public function getRewardForSlot(string $crateType, int $slot): ?Reward
    {
        return $this->cratesRewards[$crateType][$slot] ?? null;
    }

    public function clearRewardsForCrate(string $crateType): void
    {
        $this->cratesRewards[$crateType] = [];
    }

    public function getCratesRewards(): array
    {
        return $this->cratesRewards;
    }
}
