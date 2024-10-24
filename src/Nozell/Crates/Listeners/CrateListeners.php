<?php

declare(strict_types=1);

namespace Nozell\Crates\Listeners;

use Nozell\Crates\Entity\EnderBoxEntity;
use Nozell\Crates\Entity\IceBoxEntity;
use Nozell\Crates\Entity\MageBoxEntity;
use Nozell\Crates\Entity\MagmaBoxEntity;
use Nozell\Crates\Entity\PegasusBoxEntity;

use Nozell\Crates\Events\GiveAllKeysEvent;
use Nozell\Crates\Events\GiveKeyEvent;
use Nozell\Crates\Events\OpenCrateEvent;
use Nozell\Crates\Events\SpawnCrateEvent;

use Nozell\Crates\Main;

use Nozell\Crates\Manager\CrateManager;
use Nozell\Crates\Manager\LangManager;

use Nozell\Crates\Session\SessionFactory;

use Nozell\Crates\tags\Names;
use Nozell\Crates\tags\Perms;

use Nozell\Crates\Utils\CooldownTask;
use Nozell\Crates\Utils\LavaParticleEffect;
use Nozell\Crates\Utils\SoundEffect;

use pocketmine\event\Listener;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class CrateListeners implements Listener
{
    use SoundEffect, LavaParticleEffect;

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
            $player->sendMessage("Esta crate no tiene premios.");
            $ev->cancel();
            return;
        }

        $item = $reward->getItem();

        if ($item === null) {
            $player->sendMessage("No se pudo obtener un ítem de esta crate.");
            $ev->cancel();
            return;
        }
        $playerInventory = $player->getInventory();

        if (!$playerInventory->canAddItem($item)) {
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

                        $item->setLore(["Obtenido de " . $crate]);

                        $playerInventory->addItem($item);


                        $session->reduceKey($crate);

                        self::playSound($player, "firework.twinkle", 100, 500);

                        self::addLavaParticles($entity->getWorld(), $entity->getPosition());

                        $onlinePlayers = Server::getInstance()->getOnlinePlayers();
                        foreach ($onlinePlayers as $onlinePlayer) {
                            $wonAlertMsg = LangManager::getInstance()->generateMsg(
                                "won-alert",
                                ["{userName}", "{itemName}", "{crateName}"],
                                [$player->getName(), $item->getName(), $crate]
                            );
                            $onlinePlayer->sendTip(TextFormat::colorize($wonAlertMsg));
                        }
                    },
                ],
            ],
            [
                "actions" => [
                    function () use ($player) {
                        $player->sendTitle(TextFormat::colorize("&e1"), "", 5, 20, 5);
                        self::playSound($player, "note.harp", 100, 500);
                    },
                ],
            ],
            [
                "actions" => [
                    function () use ($player) {
                        $player->sendTitle(TextFormat::colorize("&g2"), "", 5, 20, 5);
                        self::playSound($player, "note.harp", 100, 500);
                    },
                ],
            ],
            [
                "actions" => [
                    function () use ($player) {
                        $player->sendTitle(TextFormat::colorize("&63"), "", 5, 20, 5);
                        self::playSound($player, "note.harp", 100, 500);
                    },
                ],
            ],
        ];

        $scheduler = Main::getInstance()->getScheduler();
        $scheduler->scheduleRepeatingTask(new CooldownTask($player, $actionsQueue), 20);
    }


    public function onGiveAllKeys(GiveAllKeysEvent $event): void
    {
        $player = $event->getPlayer();
        $keyType = $event->getKeyType();
        $amount = $event->getAmount();

        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            $session = SessionFactory::getInstance()
                ->getSession($onlinePlayer);

            $session->addKey($keyType, $amount);

            $msg = LangManager::getInstance()
                ->generateMsg("received-keys", ["{amount}", "{keyType}"], [$amount, $keyType]);
            $onlinePlayer->sendMessage($msg);
        }
    }

    public function onGiveKey(GiveKeyEvent $event): void
    {
        $sender = $event->getSender();
        $receiver = $event->getReceiver();
        $keyType = $event->getKeyType();
        $amount = $event->getAmount();

        $session = SessionFactory::getInstance()
            ->getSession($receiver);

        $session->addKey($keyType, $amount);

        $msg = LangManager::getInstance()
            ->generateMsg("received-keys", ["{amount}", "{keyType}"], [$amount, $keyType]);
        $receiver->sendMessage($msg);
    }

    public function onSpawnCrate(SpawnCrateEvent $event): void
    {
        $player = $event->getPlayer();
        $crateType = $event->getCrateType();

        if (!$player->hasPermission(Perms::Admin)) {
            $player->sendMessage(TextFormat::RED . "You do not have permission to spawn crates.");
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

        $player->sendMessage(TextFormat::GREEN . "Crate of type " . $crateType . " has been spawned!");
    }
}
