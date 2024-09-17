<?php

namespace Nozell\Crates;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Nozell\Crates\Command\CratesCommand;
use Nozell\Crates\Entity\MageBoxEntity;
use Nozell\Crates\Entity\PegasusBoxEntity;
use Nozell\Crates\libs\muqsit\invmenu\InvMenuHandler;
use Nozell\Crates\Entity\EnderBoxEntity;
use Nozell\Crates\Entity\IceBoxEntity;
use Nozell\Crates\Entity\MagmaBoxEntity;
use Nozell\Crates\Manager\CrateManager;
use Nozell\Crates\Listeners\EventListener; 
use customiesdevs\customies\entity\CustomiesEntityFactory;
use Nozell\Crates\Utils\CratesUtils;
use pocketmine\player\Player;
use pocketmine\resourcepacks\ZippedResourcePack;
use Symfony\Component\Filesystem\Path;
use function array_merge;

class Main extends PluginBase implements Listener {
    
    private CrateManager $crateManager;
    public Config $config;
    
    const RET_INVALID = 0;
    const RET_SUCCESS = 1;

    private static Main $instance;

    public function onEnable(): void {
        
        if (!InvMenuHandler::isRegistered()) {     
            InvMenuHandler::register($this);
        }

        $this->saveDefaultConfig();
        $this->saveResource("Crates.mcpack");    
        $rpManager = $this->getServer()->getResourcePackManager();
        $rpManager->setResourceStack(array_merge($rpManager->getResourceStack(), [new ZippedResourcePack(Path::join($this->getDataFolder(), "Crates.mcpack"))]));
        (new \ReflectionProperty($rpManager, "serverForceResources"))->setValue($rpManager, true);

        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        
        self::$instance = $this;
        
        $this->crateManager = new CrateManager($this);

        $this->getServer()->getCommandMap()->register("crates", new CratesCommand("crates", "Abre el menú principal de crates", "/crates"));

        CustomiesEntityFactory::getInstance()->registerEntity(MageBoxEntity::class, "crates:mage_chest", null, "minecraft:humanoid");
        CustomiesEntityFactory::getInstance()->registerEntity(IceBoxEntity::class, "crates:ice_chest", null, "minecraft:humanoid");
        CustomiesEntityFactory::getInstance()->registerEntity(EnderBoxEntity::class, "crates:grand_ender_chest", null, "minecraft:humanoid");
        CustomiesEntityFactory::getInstance()->registerEntity(MagmaBoxEntity::class, "crates:dark_magma", null, "minecraft:humanoid");
        CustomiesEntityFactory::getInstance()->registerEntity(PegasusBoxEntity::class, "crates:golden_pegasus", null, "minecraft:humanoid");

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this); 
    }

    public static function getInstance(): self {
        return self::$instance;
    }
    
    public function getCrateManager(): CrateManager {
        return $this->crateManager;
    }
}
