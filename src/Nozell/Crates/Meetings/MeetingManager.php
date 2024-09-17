<?php

declare(strict_types=1);

namespace Nozell\Crates\Meetings;

use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

final class MeetingManager {
    use SingletonTrait;

    private array $meetings = [];
    
    public function getMeetings(): array {
        return $this->meetings;
    }

    public function getMeeting(Player|string $player): ?Meeting {
        $xuid = $player instanceof Player ? $player->getXuid() : $player;
        return $this->meetings[$xuid] ?? null;
    }

    public function createMeeting(Player $player): Meeting {
        $this->meetings[$player->getXuid()] = $meeting = new Meeting($player);
        return $meeting;
    }

    public function removeMeeting(Player $player): void {
        if ($this->getMeeting($player) === null) {
            return;
        }
        unset($this->meetings[$player->getXuid()]);
    }
}