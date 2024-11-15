<?php

namespace Nozell\Crates\Utils;

use Nozell\Crates\Rewards\Reward;
use pocketmine\utils\SingletonTrait;

class RewardSelector
{
    use SingletonTrait;

    public function selectReward(array $rewards): ?Reward
    {
        if (empty($rewards)) {
            return null;
        }

        $totalChance = array_sum(array_map(function (Reward $reward) {
            return $reward->getChance();
        }, $rewards));

        $randomChance = mt_rand(0, $totalChance * 100) / 100;

        foreach ($rewards as $reward) {
            if ($randomChance <= $reward->getChance()) {
                return $reward;
            }
            $randomChance -= $reward->getChance();
        }

        return null;
    }
}
