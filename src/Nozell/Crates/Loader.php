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
use Nozell\Crates\Listeners\EventListener;
use Nozell\Crates\Manager\LangManager;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\Server;
use Symfony\Component\Filesystem\Path;

final class Loader
{
    public static function LoadAll(): void
    {
        self::LoadInvmenu();
        self::LoadLangs();
        self::RegisterEntities();

        Main::getInstance()->getServer()->getPluginManager()->registerEvents(
            new EventListener(),
            Main::getInstance()
        );

        Server::getInstance()->getCommandMap()->register(
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
        LangManager::getInstance()->loadLangs();
        $main = Main::getInstance();
        $main->saveResource("lang\chinese.yml");
        $main->saveResource('lang\english.yml');
        $main->saveResource('lang\french.yml');
        $main->saveResource("lang\japanese.yml");
        $main->saveResource("lang\spanish.yml");
        $main->saveResource('lang\turkish.yml');
    }

    public static function RegisterEntities(): void
    {
        CustomiesEntityFactory::getInstance()->registerEntity(
            MageBoxEntity::class,
            "crates:mage_chest",
            null,
            "minecraft:humanoid"
        );

        CustomiesEntityFactory::getInstance()->registerEntity(
            IceBoxEntity::class,
            "crates:ice_chest",
            null,
            "minecraft:humanoid"
        );

        CustomiesEntityFactory::getInstance()->registerEntity(
            EnderBoxEntity::class,
            "crates:grand_ender_chest",
            null,
            "minecraft:humanoid"
        );

        CustomiesEntityFactory::getInstance()->registerEntity(
            MagmaBoxEntity::class,
            "crates:dark_magma",
            null,
            "minecraft:humanoid"
        );

        CustomiesEntityFactory::getInstance()->registerEntity(
            PegasusBoxEntity::class,
            "crates:golden_pegasus",
            null,
            "minecraft:humanoid"
        );
    }

    public static function LoadResourcepack()
    {
        Main::getInstance()->saveResource("Crates.mcpack");
        $rpManager = Server::getInstance()->getResourcePackManager();

        $rpManager->setResourceStack(array_merge(
            $rpManager->getResourceStack(),
            [new ZippedResourcePack(Path::join(Main::getInstance()->getDataFolder(), "Crates.mcpack"))]
        ));

        (new \ReflectionProperty($rpManager, "serverForceResources"))->setValue($rpManager, true);
    }
}
