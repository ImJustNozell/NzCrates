<?php

declare(strict_types=1);

namespace Nozell\Crates\Listeners;

use Nozell\Crates\Meetings\MeetingManager;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventListener implements Listener {

    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        
MeetingManager::getInstance()->createMeeting($player);
        
MeetingManager::getInstance()->getMeeting($player)->join();
    }

    public function onPlayerQuit(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();
       
MeetingManager::getInstance()->getMeeting($player)->Close();
    }
}
