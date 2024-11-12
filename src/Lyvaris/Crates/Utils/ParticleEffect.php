<?php

namespace Lyvaris\Crates\Utils;

use pocketmine\world\World;
use pocketmine\math\Vector3;
use pocketmine\world\particle\CriticalParticle;
use pocketmine\world\particle\LavaParticle;

trait ParticleEffect
{
    public static function addLavaParticles(World $world, Vector3 $position): void
    {
        $adds = [[0, 0, 0], [1, 0, 0], [0, 1, 0], [0, 0, 1]];
        array_walk($adds, fn($add) => $world->addParticle(
            $position->add($add[0], $add[1], $add[2]),
            new LavaParticle()
        ));
    }

    public static function SecondParticles(World $world, Vector3 $pos): void
    {
        $adds = [[0, 0, 0], [1, 0, 0], [0, 1, 0], [0, 0, 1]];
        array_walk($adds, fn($add) => $world->addParticle(
            $pos->add($add[0], $add[1], $add[2]),
            new CriticalParticle()
        ));
    }
}
