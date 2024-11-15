<?php

namespace Nozell\Crates\Utils;

use pocketmine\player\Player;
use pocketmine\utils\Config;
use Nozell\Crates\Main;
use Nozell\Crates\tags\Names;

final class CratesUtils
{
    private static function getPlayerConfig(Player $player): Config
    {
        $folderPath = Main::getInstance()->getDataFolder() . "players/";

        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        $filePath = $folderPath . $player->getName() . ".json";

        if (!file_exists($filePath)) {
            file_put_contents($filePath, json_encode([
                Names::Mage => 0,
                Names::Ice => 0,
                Names::Ender => 0,
                Names::Magma => 0,
                Names::Pegasus => 0
            ], JSON_PRETTY_PRINT));
        }

        return new Config($filePath, Config::JSON);
    }

    public static function getKeyBox(Player $player, string $type): int
    {
        $config = self::getPlayerConfig($player);

        return (int) $config->get($type, 0);
    }

    public static function setKeyBox(Player $player, string $type, int $amount): void
    {
        $config = self::getPlayerConfig($player);

        $config->set($type, $amount);

        $config->save();
    }
}
