<?php

namespace Lyvaris\Crates\Manager;

use pocketmine\utils\Config;
use Lyvaris\Crates\Main;
use Lyvaris\Crates\Rewards\Reward;
use Lyvaris\Crates\Rewards\RewardManager;
use Lyvaris\Crates\Utils\ItemSerializer;
use Lyvaris\Crates\Utils\RewardSelector;
use pocketmine\utils\SingletonTrait;

class CrateManager
{
    use SingletonTrait;
    private string $rewardsPath;

    public function __construct()
    {
        $this->rewardsPath = Main::getInstance()->getDataFolder() . "rewards/";

        if (!is_dir($this->rewardsPath)) {
            mkdir($this->rewardsPath, 0755, true);
        }
    }

    public function loadCrateIntoCache(string $crateLabel): void
    {
        $filePath = $this->rewardsPath . $crateLabel . ".json";
        if (file_exists($filePath)) {
            $config = new Config($filePath, Config::JSON);
            $rewardsData = $config->getAll();

            $rewardManager = RewardManager::getInstance();
            $rewardManager->clearRewardsForCrate($crateLabel);

            foreach ($rewardsData as $rewardData) {
                $reward = new Reward(
                    ItemSerializer::deserialize($rewardData['item']),
                    $rewardData['chance'],
                    $rewardData['slot']
                );
                $rewardManager->addRewardToCrate($crateLabel, $reward);
            }
        }
    }

    public function loadAllCratesIntoCache(): void
    {
        foreach (glob($this->rewardsPath . "*.json") as $file) {
            $crateLabel = basename($file, ".json");
            $this->loadCrateIntoCache($crateLabel);
        }
    }

    public function saveCrateFromCache(string $crateLabel): void
    {
        $rewardManager = RewardManager::getInstance();
        $rewards = $rewardManager->getRewardsForCrate($crateLabel);

        $rewardsArray = array_map(function (Reward $reward) {
            return [
                'item' => ItemSerializer::serialize($reward->getItem()),
                'chance' => $reward->getChance(),
                'slot' => $reward->getSlot(),
            ];
        }, $rewards);

        $config = new Config($this->rewardsPath . $crateLabel . ".json", Config::JSON);
        $config->setAll($rewardsArray);
        $config->save();
    }

    public function saveAllCratesFromCache(): void
    {
        foreach (RewardManager::getInstance()->getCratesRewards() as $crateLabel => $rewards) {
            $this->saveCrateFromCache($crateLabel);
        }
    }

    public function getRandomItemFromCrate(string $crateLabel): ?Reward
    {
        $rewards = RewardManager::getInstance()->getRewardsForCrate($crateLabel);

        if (empty($rewards)) {
            return null;
        }

        return RewardSelector::getInstance()->selectReward($rewards);
    }
}
