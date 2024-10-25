<?php

namespace Nozell\Crates\Manager;

use pocketmine\world\World;
use pocketmine\math\Vector3;
use pocketmine\world\particle\FlameParticle;
use pocketmine\world\particle\WaterDripParticle;
use pocketmine\world\particle\PortalParticle;
use pocketmine\world\particle\LavaParticle;
use pocketmine\world\particle\HappyVillagerParticle;
use pocketmine\world\particle\EndermanTeleportParticle;
use Nozell\Crates\tags\ParticleIds;
use pocketmine\utils\SingletonTrait;

final class ParticleManager
{
    use SingletonTrait;
    private int $particleCounter = 0;
    private int $lastParticleTime = 0;

    public function sendParticles(
        World $w,
        Vector3 $p,
        string $type = ParticleIds::Magma,
        int $tick = 0
    ): void {
        switch ($type) {
            case ParticleIds::Magma:
                $this->setHorario($w, $p, new FlameParticle());
                $this->setAntiHorario($w, $p, new LavaParticle());
                break;
            case ParticleIds::Ice:
                $this->setHorario($w, $p, new WaterDripParticle());
                $this->setAntiHorario($w, $p, new WaterDripParticle());
                break;
            case ParticleIds::Mage:
                $this->setHorario($w, $p, new PortalParticle());
                $this->setAntiHorario($w, $p, new HappyVillagerParticle());
                break;
            case ParticleIds::Pegasus:
                $this->setHorario($w, $p, new HappyVillagerParticle());
                $this->setAntiHorario($w, $p, new HappyVillagerParticle());
                break;
            case ParticleIds::Ender:
                $this->EndermanParticles($w, $p, $tick);
                break;
            default:
                break;
        }
    }

    public function setHorario(World $w, Vector3 $p, $particle): void
    {
        $size = 0.8;
        $angle = deg2rad($this->particleCounter * 7);
        $heightIncrement = $this->particleCounter * 0.03;

        $x = $p->getX() + cos($angle) * $size;
        $y = $p->getY() + $heightIncrement;
        $z = $p->getZ() + sin($angle) * $size;

        $pos = new Vector3($x, $y, $z);
        $w->addParticle($pos, $particle);

        $this->incrementParticleCounter();

        if ($heightIncrement > 1.55) {
            $this->particleCounter = 0;
        }
    }

    public function setAntiHorario(World $w, Vector3 $p, $particle): void
    {
        $size = 0.8;
        $angle = deg2rad($this->particleCounter * 7);
        $heightIncrement = $this->particleCounter * 0.03;

        $x = $p->getX() - cos($angle) * $size;
        $y = $p->getY() + $heightIncrement;
        $z = $p->getZ() - sin($angle) * $size;

        $pos = new Vector3($x, $y, $z);
        $w->addParticle($pos, $particle);

        $this->incrementParticleCounter();

        if ($heightIncrement > 1.55) {
            $this->particleCounter = 0;
        }
    }

    private function EndermanParticles(World $w, Vector3 $p, int $tick): void
    {
        if ($tick > $this->lastParticleTime + 25) {
            $pos = $p->add(0, 1, 0);
            $w->addParticle($pos, new EndermanTeleportParticle());
            $this->lastParticleTime = $tick;
        }
    }

    private function incrementParticleCounter(): void
    {
        $this->particleCounter++;

        if ($this->particleCounter > 200) {
            $this->particleCounter = 0;
        }
    }
}
