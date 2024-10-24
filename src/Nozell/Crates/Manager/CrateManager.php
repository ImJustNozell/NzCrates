<?php

namespace Nozell\Crates\Manager;

use pocketmine\utils\Config;
use Nozell\Crates\Data\CrateData;
use Nozell\Crates\Rewards\Reward;
use Nozell\Crates\Utils\ItemSerializer;
use Nozell\Crates\Main;
use Nozell\Crates\Utils\RewardSelector;
use pocketmine\utils\SingletonTrait;

class CrateManager
{
    use SingletonTrait;

    private array $crates = [];
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

            $rewards = array_map(function ($rewardData) {
                return new Reward(
                    ItemSerializer::deserialize($rewardData['item']),
                    $rewardData['chance'],
                    $rewardData['slot']
                );
            }, $rewardsData);

            $this->crates[$crateLabel] = new CrateData($crateLabel, $rewards);
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
        if (isset($this->crates[$crateLabel])) {
            $crateData = $this->crates[$crateLabel];
            $rewards = $crateData->getRewards();

            $rewardsArray = array_map(function (Reward $reward) {
                return [
                    'item' => ItemSerializer::serialize($reward->getItem()),
                    'chance' => $reward->getChance(),
                    'slot' => $reward->getSlot()
                ];
            }, $rewards);

            $config = new Config($this->rewardsPath . $crateLabel . ".json", Config::JSON);
            $config->setAll($rewardsArray);
            $config->save();
        }
    }

    public function saveAllCratesFromCache(): void
    {
        foreach ($this->crates as $crateLabel => $crateData) {
            $this->saveCrateFromCache($crateLabel);
        }
    }

    public function getCrate(string $crateLabel): ?CrateData
    {
        return $this->crates[$crateLabel] ?? null;
    }

    public function crateExistsInCache(string $crateLabel): bool
    {
        return isset($this->crates[$crateLabel]);
    }

    public function getRandomItemFromCrate(string $crateLabel): ?Reward
    {
        $crateData = $this->getCrate($crateLabel);
        if ($crateData === null) {
            return null;
        }

        return RewardSelector::getInstance()->selectReward($crateData->getRewards());
    }
}
