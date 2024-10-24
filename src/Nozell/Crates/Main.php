<?php

namespace Nozell\Crates;

use Nozell\Crates\Manager\CrateManager;
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
        $this->saveResource("Crates.mcpack");
        $rpManager = Server::getInstance()->getResourcePackManager();

        $rpManager->setResourceStack(
            array_merge($rpManager->getResourceStack(), [
                new ZippedResourcePack(
                    Path::join(
                        $this->getDataFolder(),
                        "Crates.mcpack"
                    )
                ),
            ])
        );

        (new \ReflectionProperty($rpManager, "serverForceResources"))->setValue(
            $rpManager,
            true
        );

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
