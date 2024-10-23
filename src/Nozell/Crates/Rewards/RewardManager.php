<?php

namespace Nozell\Crates\Rewards;

class RewardManager
{
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

    public function saveAllRewards(): array
    {
        $data = [];
        foreach ($this->cratesRewards as $crateType => $rewards) {
            foreach ($rewards as $slot => $reward) {
                $data[$crateType][$slot] = [
                    'item' => $reward->getItem(),
                    'chance' => $reward->getChance(),
                ];
            }
        }
        return $data;
    }

    public function loadAllRewards(array $data): void
    {
        foreach ($data as $crateType => $rewards) {
            foreach ($rewards as $slot => $rewardData) {
                $item = $rewardData['item'];
                $chance = $rewardData['chance'];
                $this->addRewardToCrate($crateType, new Reward($item, $chance, $slot));
            }
        }
    }
}
