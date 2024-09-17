<?php

namespace Nozell\Crates\Entity;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Location;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\entity\Living;
use Nozell\Crates\Main;
use Nozell\Crates\Meetings\MeetingManager;
use Nozell\Crates\Manager\ParticleManager;
use pocketmine\Server;

class MageBoxEntity extends Living {

    private ParticleManager $particleManager;

    public function __construct(Location $location, ?CompoundTag $nbt = null) {
        parent::__construct($location, $nbt);
        $this->particleManager = new ParticleManager();
        $this->setNameTagAlwaysVisible(true);
        $this->setHasGravity(false);
        $this->spawnToAll();
    }
    
    public function canBeMovedByCurrents(): bool {
        return false;
    }
    
    public static function getNetworkTypeId(): string {
        return "crates:mage_chest";
    }

    protected function getInitialSizeInfo(): EntitySizeInfo {
        return new EntitySizeInfo(1.8, 0.8, 1.62);
    }
    
    public function getName(): string {
        return "MageBoxEntity";
    }

    public function onUpdate(int $currentTick): bool {
        $config = Main::getInstance()->getConfig();
        $pos = $this->getPosition();
        $world = $this->getWorld();

        $this->particleManager->sendParticles($world, $pos, 'enchantment');
        
        $floatingText = $config->get("magefloatingtext");
        $this->setNameTag($floatingText);
    
        return parent::onUpdate($currentTick);
    }

    public function attack(EntityDamageEvent $source): void {
        $source->cancel();
        if ($source instanceof EntityDamageByEntityEvent) {
            $damager = $source->getDamager();
            if ($damager instanceof Player) {
                if ($damager->getInventory()->getItemInHand()->getTypeId() === VanillaItems::DIAMOND_SWORD()->getTypeId()) {
                    if ($damager->hasPermission("box.dell")) {
                        $this->flagForDespawn();
                        return;
                    }
                } else {
                    $randomItem = Main::getInstance()->getCrateManager()->getRandomItemFromCrate("mage", $damager->getName(), $this);

                    $meeting = MeetingManager::getInstance()->getMeeting($damager)->getCratesData();

                    if ($meeting->getKeyMage() > 0) {
                        $meeting->reduceKeyMage();
                        Main::getInstance()->getCrateManager()->getRandomItemFromCrate("mage", $damager->getName(), $this);
                    } else {
                        $damager->sendMessage("§cAl parecer no tienes keys!");
                    }
                }
            }
        }
    }

    protected function getInitialDragMultiplier(): float {
        return 0.0;
    }

    protected function getInitialGravity(): float {
        return 0.0;
    }
}
