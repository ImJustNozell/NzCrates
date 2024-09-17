<?php

namespace Nozell\Crates\Utils;

use pocketmine\player\Player;
use pocketmine\utils\Config;
use Nozell\Crates\Main;
use JsonException;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

final class CratesUtils {

    private static function getConfig(string $type): Config {
        return new Config(Main::getInstance()->getDataFolder() . "$type.yml", Config::YAML);
    }

    public static function getKeyBox(Player $player, string $type): int {
        
        return (int) self::getConfig($type)->get($player->getName(), 0);
        return Main::RET_SUCCESS;
    }
  
    public static function setKeyBox(Player $player, string $type, int $amount): void {
        $config = self::getConfig($type);
        $currentAmount = $config->get($player->getName(), 0);
        $config->set($player->getName(), $currentAmount + $amount);
        $config->save();
    }
}
