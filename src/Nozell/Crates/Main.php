<?php

namespace Nozell\Crates;

use Nozell\Crates\Manager\CrateManager;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;

class Main extends PluginBase implements Listener
{
    use SingletonTrait {
        setInstance as private;
        reset as private;
    }


    public function onEnable(): void
    {
        $startTime = microtime(true);

        self::setInstance($this);

        CrateManager::getInstance()->loadAllCratesIntoCache();

        Server::getInstance()
            ->getLogger()
            ->debug("NzCrates enabling");

        Loader::LoadAll();

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1_000_000;

        Server::getInstance()
            ->getLogger()
            ->info("NzCrates enabled in " . round($executionTime, 2) . " ms.");
    }

    public function onDisable(): void
    {
        CrateManager::getInstance()->saveAllCratesFromCache();

        Server::getInstance()
            ->getLogger()
            ->debug("NzCrates disabling");
    }
}
