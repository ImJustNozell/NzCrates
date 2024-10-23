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
        self::setInstance($this);
        $startTime = microtime(true);

        CrateManager::getInstance()->loadAllCratesIntoCache();

        Server::getInstance()->getLogger()->debug("NzCrates enabling");

        Loader::LoadAll();

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);

        if ($executionTime < 1) {
            $executionTimeMilliseconds = $executionTime * 1_000;
            Server::getInstance()
                ->getLogger()
                ->info("NzCrates enabled in " . round($executionTimeMilliseconds, 2) . " ms.");
        } else {
            Server::getInstance()
                ->getLogger()
                ->info("NzCrates enabled in " . round($executionTime, 2) . " s.");
        }
    }



    public function onDisable(): void
    {
        CrateManager::getInstance()->saveAllCratesFromCache();

        Server::getInstance()
            ->getLogger()
            ->debug("NzCrates disabling");
    }
}
