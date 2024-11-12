<?php

declare(strict_types=1);

namespace Lyvaris\Crates\Session;

use Lyvaris\Crates\Manager\LangManager;
use Lyvaris\Crates\tags\Names;
use Lyvaris\Crates\Utils\CratesUtils;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;

class SessionFactory
{
    use SingletonTrait;

    private static array $session = [];

    private static array $crateNames = [
        Names::Mage,
        Names::Ice,
        Names::Ender,
        Names::Magma,
        Names::Pegasus
    ];

    public static function isSession(Player $player): bool
    {
        return isset(self::$session[$player->getName()]);
    }

    public static function addSession(Player $player): void
    {
        $name = $player->getName();
        if (self::isSession($player)) {
            return;
        }

        self::$session[$name] = new Profile($name);

        $loadingMessage = LangManager::getInstance()->generateMsg("data-loading", [], []);
        $player->sendMessage(TextFormat::colorize($loadingMessage));

        foreach (self::$crateNames as $crateName) {
            $keyAmount = CratesUtils::getKeyBox($player, $crateName);
            self::getSession($player)->setKey($crateName, $keyAmount);
        }

        $loadedMessage = LangManager::getInstance()->generateMsg("data-loaded", [], []);
        $player->sendMessage(TextFormat::colorize($loadedMessage));
    }

    public static function removeSession(Player $player): void
    {
        if (!self::isSession($player)) {
            return;
        }

        foreach (self::$crateNames as $crateName) {
            $keyAmount = self::getSession($player)->getKey($crateName);
            CratesUtils::setKeyBox($player, $crateName, $keyAmount);
        }

        unset(self::$session[$player->getName()]);
    }

    public static function getSession(Player $player): ?Profile
    {
        return self::isSession($player) ? self::$session[$player->getName()] : null;
    }

    public static function removeAllSessions(): void
    {
        foreach (self::$session as $playerName => $session) {
            $player = Server::getInstance()->getPlayerExact($playerName);
            if ($player !== null) {
                self::removeSession($player);
            }
        }
    }
}
