<?php

namespace Nozell\Crates\Entity;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Location;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use Nozell\Crates\Main;
use Nozell\Crates\Meetings\MeetingManager;
use pocketmine\entity\Living;
use Nozell\Crates\Manager\ParticleManager;
use Nozell\Crates\Manager\LangManager;

class PegasusBoxEntity extends Living
{

    private ParticleManager $particleManager;

    public function __construct(Location $location, ?CompoundTag $nbt = null)
    {
        parent::__construct($location, $nbt);
        $this->particleManager = new ParticleManager();
        $this->setNameTagAlwaysVisible(true);
        $this->setHasGravity(false);
        $this->spawnToAll();
    }

    public function canBeMovedByCurrents(): bool
    {
        return false;
    }

    public static function getNetworkTypeId(): string
    {
        return "crates:golden_pegasus";
    }

    protected function getInitialSizeInfo(): EntitySizeInfo
    {
        return new EntitySizeInfo(1.8, 0.8, 1.62);
    }

    public function getName(): string
    {
        return "PegasusBoxEntity";
    }

    public function onUpdate(int $currentTick): bool
    {
        $pos = $this->getPosition();
        $world = $this->getWorld();

        $this->particleManager->sendParticles($world, $pos, 'villager');

        $floatingText = LangManager::getInstance()->generateMsg("pegasus-floating-text", [], []);
        $this->setNameTag($floatingText);

        return parent::onUpdate($currentTick);
    }


    public function attack(EntityDamageEvent $source): void
    {
        $source->cancel();
        if (!$source instanceof EntityDamageByEntityEvent) return;

        $damager = $source->getDamager();

        if (!$damager instanceof Player) return;

        if ($damager->getInventory()->getItemInHand()->getTypeId() === VanillaItems::DIAMOND_SWORD()->getTypeId()) {

            if (!$damager->hasPermission("box.dell")) return;
            $this->flagForDespawn();

            return;
        } else {
            $meeting = MeetingManager::getInstance()->getMeeting($damager)->getCratesData();

            if ($meeting->getKeyPegasus() > 0) {

                $meeting->reduceKeyPegasus();
                Main::getInstance()->getCrateManager()->getRandomItemFromCrate("pegasus", $damager->getName(), $this);
            } else {

                $msg = LangManager::getInstance()->generateMsg("no-keys", [], []);
                $damager->sendMessage($msg);
            }
        }
    }

    protected function getInitialDragMultiplier(): float
    {
        return 0.0;
    }

    protected function getInitialGravity(): float
    {
        return 0.0;
    }
}
