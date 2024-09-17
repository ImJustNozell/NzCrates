<?php

namespace Nozell\Crates\Utils;

use pocketmine\world\World;
use pocketmine\math\Vector3;
use pocketmine\world\particle\LavaParticle;

trait LavaParticleEffect {
    public static function addLavaParticles(World $world, Vector3 $position): void {
        $world->addParticle($position, new LavaParticle());
        $world->addParticle($position, new LavaParticle());
        $world->addParticle($position->add(1, 0, 0), new LavaParticle());
        $world->addParticle($position->add(0, 1, 0), new LavaParticle());
        $world->addParticle($position->add(0, 0, 1), new LavaParticle());
    }
}