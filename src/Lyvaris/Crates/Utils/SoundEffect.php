<?php

namespace Lyvaris\Crates\Utils;

use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;

trait SoundEffect
{
    public static function playSound(Player $player, string $sound, int $volume, float $pitch): void
    {
        $position = $player->getPosition();
        $packet = PlaySoundPacket::create($sound, $position->getX(), $position->getY(), $position->getZ(), $volume, $pitch);
        $player->getNetworkSession()->sendDataPacket($packet);
    }
}
