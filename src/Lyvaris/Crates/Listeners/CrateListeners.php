<?php

declare(strict_types=1);

namespace Lyvaris\Crates\Listeners;

use Lyvaris\Crates\Entity\EnderBoxEntity;
use Lyvaris\Crates\Entity\IceBoxEntity;
use Lyvaris\Crates\Entity\MageBoxEntity;
use Lyvaris\Crates\Entity\MagmaBoxEntity;
use Lyvaris\Crates\Entity\PegasusBoxEntity;

use Lyvaris\Crates\Events\GiveAllKeysEvent;
use Lyvaris\Crates\Events\GiveKeyEvent;
use Lyvaris\Crates\Events\OpenCrateEvent;
use Lyvaris\Crates\Events\SpawnCrateEvent;

use Lyvaris\Crates\Main;

use Lyvaris\Crates\Manager\CrateManager;
use Lyvaris\Crates\Manager\LangManager;

use Lyvaris\Crates\Session\SessionFactory;

use Lyvaris\Crates\tags\Names;
use Lyvaris\Crates\tags\Perms;

use Lyvaris\Crates\Utils\CooldownTask;
use Lyvaris\Crates\Utils\ParticleEffect;
use Lyvaris\Crates\Utils\SoundEffect;

use pocketmine\event\Listener;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class CrateListeners implements Listener
{
    use SoundEffect, ParticleEffect;

    public function OpenCrate(OpenCrateEvent $ev): void
    {
        $player = $ev->getPlayer();
        $crate = $ev->getCrateLabel();
        $entity = $ev->getEntity();
        $session = SessionFactory::getInstance()
            ->getSession($player);

        if ($session->getKey($crate) <= 0) {
            $msg = LangManager::getInstance()->generateMsg("no-keys", [], []);
            $ev->cancel();
            $player->sendMessage($msg);
            return;
        }

        $reward = CrateManager::getInstance()->getRandomItemFromCrate($crate);

        if ($reward === null) {
            $msg = LangManager::getInstance()->generateMsg("no-rewards", [], []);
            $player->sendMessage($msg);
            $ev->cancel();
            return;
        }

        $item = $reward->getItem();

        if ($item === null) {
            $msg = LangManager::getInstance()->generateMsg("no-item", [], []);
            $player->sendMessage($msg);
            $ev->cancel();
            return;
        }

        $playerInventory = $player->getInventory();

        if (!$playerInventory->canAddItem($item)) {
            $msg = LangManager::getInstance()->generateMsg("inventory-full", [], []);
            $player->sendMessage($msg);
            $ev->cancel();
            return;
        }

        $actionsQueue = [
            [
                "actions" => [
                    function (Player $player) use ($item, $playerInventory, $crate, $entity, $session) {
                        $msg = LangManager::getInstance()->generateMsg("won-item", ["{itemName}"], [$item->getName()]);
                        $player->sendMessage(TextFormat::colorize($msg));

                        $item->setLore([]);
                        $item->setLore([TextFormat::YELLOW . LangManager::getInstance()->generateMsg("crate-lore", ["{crate}"], [ucfirst($crate)])]);

                        $playerInventory->addItem($item);
                        $session->reduceKey($crate);

                        self::playSound($player, "firework.twinkle", 100, 500);
                        self::addLavaParticles($entity->getWorld(), $entity->getPosition());

                        $onlinePlayers = Server::getInstance()->getOnlinePlayers();
                        foreach ($onlinePlayers as $onlinePlayer) {
                            $wonAlertMsg = LangManager::getInstance()->generateMsg(
                                "won-alert",
                                ["{userName}", "{itemName}", "{crateName}"],
                                [$player->getName(), $item->getName(), ucfirst($crate)]
                            );
                            $onlinePlayer->sendTip(TextFormat::colorize($wonAlertMsg));
                        }
                    },
                ],
            ],
            [
                "actions" => [
                    function () use ($player, $entity, $crate) {
                        $msgTitle = LangManager::getInstance()->generateMsg("title-countdown-1", [], []);
                        $msgTip = LangManager::getInstance()->generateMsg("open-crate-tip", ["{crate}"], [ucfirst($crate)]);

                        $player->sendTitle(TextFormat::colorize($msgTitle), "", 5, 20, 5);
                        $player->sendTip($msgTip);

                        self::playSound($player, "note.harp", 100, 500);
                        self::SecondParticles($entity->getWorld(), $entity->getPosition());
                    },
                ],
            ],
            [
                "actions" => [
                    function () use ($player, $entity, $crate) {
                        $msgTitle = LangManager::getInstance()->generateMsg("title-countdown-2", [], []);
                        $msgTip = LangManager::getInstance()->generateMsg("open-crate-tip", ["{crate}"], [ucfirst($crate)]);

                        $player->sendTitle(TextFormat::colorize($msgTitle), "", 5, 20, 5);
                        $player->sendTip($msgTip);

                        self::playSound($player, "note.harp", 100, 500);
                        self::SecondParticles($entity->getWorld(), $entity->getPosition());
                    },
                ],
            ],
            [
                "actions" => [
                    function () use ($player, $entity, $crate) {
                        $msgTitle = LangManager::getInstance()->generateMsg("title-countdown-3", [], []);
                        $msgTip = LangManager::getInstance()->generateMsg("open-crate-tip", ["{crate}"], [ucfirst($crate)]);

                        $player->sendTitle(TextFormat::colorize($msgTitle), "", 5, 20, 5);
                        $player->sendTip($msgTip);

                        self::playSound($player, "note.harp", 100, 500);
                        self::SecondParticles($entity->getWorld(), $entity->getPosition());
                    },
                ],
            ],
        ];

        $scheduler = Main::getInstance()->getScheduler();
        $scheduler->scheduleRepeatingTask(new CooldownTask($player, $actionsQueue), 20);
    }

    public function onGiveAllKeys(GiveAllKeysEvent $event): void
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            $session = SessionFactory::getInstance()->getSession($onlinePlayer);
            $session->addKey($event->getKeyType(), $event->getAmount());

            $msg = LangManager::getInstance()->generateMsg("received-keys", ["{amount}", "{keyType}"], [$event->getAmount(), $event->getKeyType()]);
            $onlinePlayer->sendMessage($msg);
        }
    }

    public function onGiveKey(GiveKeyEvent $event): void
    {
        $session = SessionFactory::getInstance()->getSession($event->getReceiver());
        $session->addKey($event->getKeyType(), $event->getAmount());

        $msg = LangManager::getInstance()->generateMsg("received-keys", ["{amount}", "{keyType}"], [$event->getAmount(), $event->getKeyType()]);
        $event->getReceiver()->sendMessage($msg);
    }

    public function onSpawnCrate(SpawnCrateEvent $event): void
    {
        $player = $event->getPlayer();
        $crateType = $event->getCrateType();

        if (!$player->hasPermission(Perms::Admin)) {
            $msg = LangManager::getInstance()->generateMsg("no-permission", [], []);
            $player->sendMessage($msg);
            $event->cancel();
            return;
        }

        match ($crateType) {
            Names::Mage => new MageBoxEntity($player->getLocation(), new CompoundTag()),
            Names::Ice => new IceBoxEntity($player->getLocation(), new CompoundTag()),
            Names::Ender => new EnderBoxEntity($player->getLocation(), new CompoundTag()),
            Names::Magma => new MagmaBoxEntity($player->getLocation(), new CompoundTag()),
            Names::Pegasus => new PegasusBoxEntity($player->getLocation(), new CompoundTag()),
        };

        $msg = LangManager::getInstance()->generateMsg("crate-spawned", ["{crateType}"], [$crateType]);
        $player->sendMessage(TextFormat::GREEN . $msg);
    }
}
