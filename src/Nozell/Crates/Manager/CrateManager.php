<?php

namespace Nozell\Crates\Manager;

use pocketmine\utils\TextFormat;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\entity\Entity;
use Nozell\Crates\Utils\CooldownTask;
use Nozell\Crates\Utils\ItemSerializer;
use Nozell\Crates\Utils\LavaParticleEffect;
use Nozell\Crates\Utils\SoundEffect;
use pocketmine\Server;
use Nozell\Crates\Main;
use Nozell\Crates\Manager\LangManager;
use pocketmine\utils\SingletonTrait;

class CrateManager
{
    use LavaParticleEffect;
    use SoundEffect;
    use SingletonTrait;

    public Config $crateData;

    public function saveCrates(): void
    {
        Main::getInstance()
            ->getConfig()
            ->save();
    }

    public function addCrateItems(string $crateLabel, array $crateItems): void
    {
        $serializedItems = [];

        foreach ($crateItems as $crateItem) {
            $serializedItems[] = ItemSerializer::serialize($crateItem);
        }

        Main::getInstance()
            ->getConfig()
            ->set($crateLabel, serialize($serializedItems));
        $this->saveCrates();
    }

    public function crateExists(string $crateLabel): bool
    {
        return Main::getInstance()
            ->getConfig()
            ->exists($crateLabel);
    }

    public function getRandomItemFromCrate(
        string $crateLabel,
        string $name,
        Entity $entity
    ): void {
        $targetPlayer = Server::getInstance()->getPlayerExact($name);

        if (!$targetPlayer instanceof Player) {
            var_dump("Player Not Found");
            return;
        }

        if (
            !Main::getInstance()
                ->getConfig()
                ->exists($crateLabel)
        ) {
            var_dump("Crate not found");
            return;
        }

        $deserializedData = unserialize(
            Main::getInstance()
                ->getConfig()
                ->get($crateLabel)
        );
        $randomIndex = array_rand($deserializedData);
        $randomItem = ItemSerializer::deserialize(
            $deserializedData[$randomIndex]
        );
        $playerInventory = $targetPlayer->getInventory();
        $itemLabel = $randomItem->getName();

        if (!$playerInventory->canAddItem($randomItem)) {
            return;
        }

        $actionsQueue = [
            [
                "actions" => [
                    function (Player $targetPlayer) use (
                        $randomItem,
                        $itemLabel,
                        $playerInventory,
                        $crateLabel,
                        $entity
                    ) {
                        $msg = LangManager::getInstance()->generateMsg(
                            "won-item",
                            ["{itemName}"],
                            [$itemLabel]
                        );
                        $targetPlayer->sendMessage(TextFormat::colorize($msg));
                        $playerInventory->addItem($randomItem);
                        self::playSound(
                            $targetPlayer,
                            "firework.twinkle",
                            100,
                            500
                        );

                        self::addLavaParticles(
                            $entity->getWorld(),
                            $entity->getPosition()
                        );

                        $onlinePlayers = Server::getInstance()->getOnlinePlayers();
                        foreach ($onlinePlayers as $onlinePlayer) {
                            $wonAlertMsg = LangManager::getInstance()->generateMsg(
                                "won-alert",
                                ["{userName}", "{itemName}", "{crateName}"],
                                [
                                    $targetPlayer->getName(),
                                    $itemLabel,
                                    $crateLabel,
                                ]
                            );
                            $onlinePlayer->sendTip(
                                TextFormat::colorize($wonAlertMsg)
                            );
                        }
                    },
                ],
            ],
            [
                "actions" => [
                    function (Player $targetPlayer) use (
                        $randomItem,
                        $itemLabel,
                        $entity
                    ) {
                        $targetPlayer->sendTitle(
                            TextFormat::colorize("&e1"),
                            "",
                            5,
                            20,
                            5
                        );
                        self::playSound($targetPlayer, "note.harp", 100, 500);
                    },
                ],
            ],
            [
                "actions" => [
                    function (Player $targetPlayer) use (
                        $randomItem,
                        $itemLabel,
                        $entity
                    ) {
                        $targetPlayer->sendTitle(
                            TextFormat::colorize("&g2"),
                            "",
                            5,
                            20,
                            5
                        );
                        self::playSound($targetPlayer, "note.harp", 100, 500);
                    },
                ],
            ],
            [
                "actions" => [
                    function (Player $targetPlayer) use (
                        $randomItem,
                        $itemLabel,
                        $entity
                    ) {
                        $targetPlayer->sendTitle(
                            TextFormat::colorize("&63"),
                            "",
                            5,
                            20,
                            5
                        );
                        self::playSound($targetPlayer, "note.harp", 100, 500);
                    },
                ],
            ],
        ];

        $pluginScheduler = Main::getInstance()->getScheduler();
        $pluginScheduler->scheduleRepeatingTask(
            new CooldownTask($targetPlayer, $actionsQueue),
            20
        );
    }

    public function getCrateItems(string $crateLabel): array
    {
        if (
            !Main::getInstance()
                ->getConfig()
                ->exists($crateLabel)
        ) {
            var_dump("Crate not found");
            return [];
        }

        $deserializedData = unserialize(
            Main::getInstance()
                ->getConfig()
                ->get($crateLabel)
        );
        $itemsList = [];

        foreach ($deserializedData as $itemData) {
            $item = ItemSerializer::deserialize($itemData);
            $itemsList[] = $item;
        }

        return $itemsList;
    }
}
