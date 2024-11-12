<?php

declare(strict_types=1);

namespace Lyvaris\Crates\Listeners;

use Lyvaris\Crates\Session\SessionFactory;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventListener implements Listener
{

    public function onPlayerJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();

        SessionFactory::getInstance()->addSession($player);
    }

    public function onPlayerQuit(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();

        SessionFactory::getInstance()->removeSession($player);
    }
}
