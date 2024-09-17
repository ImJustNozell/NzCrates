<?php

declare(strict_types=1);

namespace Nozell\Crates\Manager;

use pocketmine\world\World;
use pocketmine\math\Vector3;
use pocketmine\world\particle\DustParticle;
use pocketmine\world\particle\EndermanTeleportParticle;
use pocketmine\color\Color;

final class ParticleManager
{
    private int $particleCounter = 0;
    private int $lastParticleTime = 0;

    public function sendParticles(World $w, Vector3 $p, string $type = 'fire', int $tick = 0): void
    {
        switch ($type) {
            case 'fire':
                $this->setHorario($w, $p, new DustParticle(new Color(255, 165, 0)));
                $this->setAntiHorario($w, $p, new DustParticle(new Color(255, 255, 0)));
                break;
            case 'enchantment':
                $this->setHorario($w, $p, new DustParticle(new Color(128, 0, 128)));
                $this->setAntiHorario($w, $p, new DustParticle(new Color(128, 128, 128)));
                break;
            case 'villager':
                $this->setHorario($w, $p, new DustParticle(new Color(255, 255, 0)));
                $this->setAntiHorario($w, $p, new DustParticle(new Color(255, 255, 255)));
                break;
            case 'enderman_teleport':
                $this->sendEndermanTeleportParticles($w, $p, $tick);
                break;
            case 'ice':
                $this->setHorario($w, $p, new DustParticle(new Color(255, 255, 255)));
                $this->setAntiHorario($w, $p, new DustParticle(new Color(173, 216, 230))); // Celeste
                break;
            default:
                break;
        }
    }

    public function setHorario(World $w, Vector3 $p, DustParticle $particle): void
    {
        $size = 0.6;
        $x = $p->getX() + cos(deg2rad($this->particleCounter / 0.1)) * $size;
        $y = $p->getY() + 1.5;
        $z = $p->getZ() + sin(deg2rad($this->particleCounter / 0.1)) * $size;

        $pos = new Vector3($x, $y, $z);
        $w->addParticle($pos, $particle);

        $this->incrementParticleCounter();
    }

    public function setAntiHorario(World $w, Vector3 $p, DustParticle $particle): void
    {
        $size = 0.6;
        $x = $p->getX() - cos(deg2rad($this->particleCounter / 0.1)) * $size;
        $y = $p->getY() + 1.5;
        $z = $p->getZ() - sin(deg2rad($this->particleCounter / 0.1)) * $size;

        $pos = new Vector3($x, $y, $z);
        $w->addParticle($pos, $particle);

        $this->incrementParticleCounter();
    }

    private function sendEndermanTeleportParticles(World $w, Vector3 $p, int $tick): void
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
