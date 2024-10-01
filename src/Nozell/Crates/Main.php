<?php

namespace Nozell\Crates;

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

        Loader::LoadAll();

        Server::getInstance()
            ->getLogger()
            ->debug("NzCrates enabling");
    }
}
