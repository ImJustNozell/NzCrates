<?php

namespace Lyvaris\Crates\Entity;

use Lyvaris\Crates\Events\OpenCrateEvent;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Location;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\entity\Living;
use Lyvaris\Crates\Manager\ParticleManager;
use Lyvaris\Crates\Manager\LangManager;
use Lyvaris\Crates\tags\EntityIds;
use Lyvaris\Crates\tags\Names;
use Lyvaris\Crates\tags\ParticleIds;
use Lyvaris\Crates\tags\Perms;
use pocketmine\item\Sword;

class MagmaBoxEntity extends Living
{

    public function __construct(Location $location, ?CompoundTag $nbt = null)
    {
        parent::__construct($location, $nbt);
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
        return EntityIds::Magma;
    }

    protected function getInitialSizeInfo(): EntitySizeInfo
    {
        return new EntitySizeInfo(1.2, 1.3, 1.3);
    }

    public function getName(): string
    {
        return Names::Magma;
    }

    public function onUpdate(int $currentTick): bool
    {
        $pos = $this->getPosition();
        $world = $this->getWorld();

        ParticleManager::getInstance()->sendParticles($world, $pos, ParticleIds::Magma);

        $floatingText = LangManager::getInstance()->generateMsg(
            "magma-floating-text",
            [],
            []
        );
        $this->setNameTag($floatingText);

        return parent::onUpdate($currentTick);
    }

    public function attack(EntityDamageEvent $source): void
    {
        $source->cancel();
        if (!$source instanceof EntityDamageByEntityEvent) return;

        $damager = $source->getDamager();

        if (!$damager instanceof Player) return;

        if ($damager->getInventory()->getItemInHand() instanceof Sword) {
            if (!$damager->hasPermission(Perms::Admin)) return;
            $this->flagForDespawn();
            return;
        } else {

            $ev = new OpenCrateEvent($damager, $this, $this->getName());
            $ev->call();

            if ($ev->isCancelled()) return;
        }
    }
}
