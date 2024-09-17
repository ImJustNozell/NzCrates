<?php

namespace Nozell\Crates\Entity;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Location;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use Nozell\Crates\Main;
use pocketmine\item\VanillaItems;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\player\Player;
use Nozell\Crates\Meetings\MeetingManager;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Server;
use pocketmine\entity\Living;
use Nozell\Crates\Manager\ParticleManager;

class EnderBoxEntity extends Living {

    private ParticleManager $particleManager;

    public function __construct(Location $location, ?CompoundTag $nbt = null){
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
        return "crates:grand_ender_chest";
    }

    protected function getInitialSizeInfo(): EntitySizeInfo {
        return new EntitySizeInfo(1.8, 0.8, 1.62);
    }
    
    public function getName(): string {
        return "EnderBoxEntity";
    }

    public function onUpdate(int $currentTick): bool {
        $config = Main::getInstance()->getConfig();
        $pos = $this->getPosition();
        $world = $this->getWorld();

        $this->particleManager->sendParticles($world, $pos, 'enderman_teleport', $currentTick);

        $floatingText = $config->get("enderfloatingtext");
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
                    $meeting = MeetingManager::getInstance()->getMeeting($damager)->getCratesData();

                    if ($meeting->getKeyEnder() > 0) {
                        $meeting->reduceKeyEnder();
                        Main::getInstance()->getCrateManager()->getRandomItemFromCrate("ender", $damager->getName(), $this);
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