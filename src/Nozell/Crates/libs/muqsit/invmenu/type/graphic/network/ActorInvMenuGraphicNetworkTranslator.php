<?php

declare(strict_types=1);

namespace Nozell\Crates\libs\muqsit\invmenu\type\graphic\network;

use Nozell\Crates\libs\muqsit\invmenu\session\InvMenuInfo;
use Nozell\Crates\libs\muqsit\invmenu\session\PlayerSession;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\types\BlockPosition;

final class ActorInvMenuGraphicNetworkTranslator implements InvMenuGraphicNetworkTranslator{

	public function __construct(
		readonly private int $actor_runtime_id
	){}

	public function translate(PlayerSession $session, InvMenuInfo $current, ContainerOpenPacket $packet) : void{
		$packet->actorUniqueId = $this->actor_runtime_id;
		$packet->blockPosition = new BlockPosition(0, 0, 0);
	}
}