<?php

namespace Lyvaris\Crates;

use Lyvaris\Crates\Manager\CrateManager;
use Lyvaris\Crates\Session\SessionFactory;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use Symfony\Component\Filesystem\Path;

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
        new Loader($this);
        
        

        CrateManager::getInstance()->loadAllCratesIntoCache();

        Server::getInstance()->getLogger()->debug("NzCrates enabling");



        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);

        if ($executionTime < 1) {
            $execution = $executionTime * 1_000;
            Server::getInstance()
                ->getLogger()
                ->info("NzCrates enabled in " . round($execution, 2) . " ms.");
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

        SessionFactory::getInstance()->removeAllSessions();
    }
}
