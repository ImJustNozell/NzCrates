<?php

namespace Nozell\Crates;

use customiesdevs\customies\entity\CustomiesEntityFactory;
use muqsit\invmenu\InvMenuHandler;
use Nozell\Crates\Command\CratesCommand;
use Nozell\Crates\Entity\EnderBoxEntity;
use Nozell\Crates\Entity\IceBoxEntity;
use Nozell\Crates\Entity\MageBoxEntity;
use Nozell\Crates\Entity\MagmaBoxEntity;
use Nozell\Crates\Entity\PegasusBoxEntity;
use Nozell\Crates\Listeners\CrateListeners;
use Nozell\Crates\Listeners\EventListener;
use Nozell\Crates\Manager\LangManager;
use Nozell\Crates\tags\EntityIds;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\Server;
use Symfony\Component\Filesystem\Path;

final class Loader
{
    public const Humanoid = "minecraft:humanoid";

    public static function LoadAll(): void
    {
        self::LoadInvmenu();
        self::LoadLangs();
        self::RegisterEntities();

        Main::getInstance()->getServer()->getPluginManager()
            ->registerEvents(new EventListener(), Main::getInstance());

        Main::getInstance()->getServer()->getPluginManager()
            ->registerEvents(new CrateListeners(), Main::getInstance());

        Server::getInstance()
            ->getCommandMap()
            ->register(
                "crates",
                new CratesCommand(
                    "crates",
                    "Abre el menú principal de crates",
                    "/crates"
                )
            );
    }

    public static function LoadInvmenu(): void
    {
        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register(Main::getInstance());
        }
    }
    public static function LoadLangs(): void
    {
        $main = Main::getInstance();
        $main->saveResource("lang/chinese.json");
        $main->saveResource("lang/english.json");
        $main->saveResource("lang/french.json");
        $main->saveResource("lang/japanese.json");
        $main->saveResource("lang/spanish.json");
        $main->saveResource("lang/turkish.json");

        LangManager::getInstance()->loadLangs();
    }

    public static function RegisterEntities(): void
    {
        CustomiesEntityFactory::getInstance()->registerEntity(
            MageBoxEntity::class,
            EntityIds::Mage,
            null
        );

        CustomiesEntityFactory::getInstance()->registerEntity(
            IceBoxEntity::class,
            EntityIds::Ice,
            null,
            self::Humanoid
        );

        CustomiesEntityFactory::getInstance()->registerEntity(
            EnderBoxEntity::class,
            EntityIds::Ender,
            null,
            self::Humanoid
        );

        CustomiesEntityFactory::getInstance()->registerEntity(
            MagmaBoxEntity::class,
            EntityIds::Magma,
            null,
            self::Humanoid
        );

        CustomiesEntityFactory::getInstance()->registerEntity(
            PegasusBoxEntity::class,
            EntityIds::Pegasus,
            null,
            self::Humanoid
        );
    }

    public static function LoadResourcepack()
    {
        
    }
}
