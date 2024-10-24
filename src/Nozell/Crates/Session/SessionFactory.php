<?php

declare(strict_types=1);

namespace Nozell\Crates\Session;

use Nozell\Crates\Manager\LangManager;
use Nozell\Crates\tags\Names;
use Nozell\Crates\Utils\CratesUtils;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;

class SessionFactory
{
    use SingletonTrait;

    private static array $session = [];


    public static function isSession(Player $player): bool
    {
        return isset(self::$session[$player->getName()]);
    }

    public static function addSession(Player $player): void
    {
        $name = $player->getName();
        if (self::isSession($player))
            return;
        self::$session[$name] = new Profile($name);

        $loadingMessage = LangManager::getInstance()->generateMsg(
            "data-loading",
            [],
            []
        );
        $player->sendMessage(TextFormat::colorize($loadingMessage));

        self::getSession($player)->setKeyMage(
            CratesUtils::getKeyBox($player, Names::Mage)
        );

        self::getSession($player)->setKeyIce(
            CratesUtils::getKeyBox($player, Names::Ice)
        );

        self::getSession($player)->setKeyEnder(
            CratesUtils::getKeyBox($player, Names::Ender)
        );

        self::getSession($player)->setKeyMagma(
            CratesUtils::getKeyBox($player, Names::Magma)
        );

        self::getSession($player)->setKeyPegasus(
            CratesUtils::getKeyBox($player, Names::Pegasus)
        );

        $loadedMessage = LangManager::getInstance()->generateMsg(
            "data-loaded",
            [],
            []
        );
        $player->sendMessage(TextFormat::colorize($loadedMessage));
    }

    public static function removeSession(Player $player): void
    {
        if (!self::isSession($player))
            return;
        CratesUtils::setKeyBox(
            $player,
            Names::Ice,
            self::getSession($player)->getKeyMage()
        );

        CratesUtils::setKeyBox(
            $player,
            Names::Ice,
            self::getSession($player)->getKeyIce()
        );

        CratesUtils::setKeyBox(
            $player,
            Names::Ender,
            self::getSession($player)->getKeyEnder()
        );

        CratesUtils::setKeyBox(
            $player,
            Names::Magma,
            self::getSession($player)->getKeyMagma()
        );

        CratesUtils::setKeyBox(
            $player,
            Names::Pegasus,
            self::getSession($player)->getKeyPegasus()
        );

        unset(self::$session[$player->getName()]);
    }

    public static function getSession(Player $player): ?Profile
    {
        if (!self::isSession($player))
            return null;
        return self::$session[$player->getName()];
    }
}
