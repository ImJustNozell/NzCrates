<?php

declare(strict_types=1);

namespace Nozell\Crates\libs\muqsit\invmenu\type\graphic\network;

use Nozell\Crates\libs\muqsit\invmenu\session\InvMenuInfo;
use Nozell\Crates\libs\muqsit\invmenu\session\PlayerSession;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;

interface InvMenuGraphicNetworkTranslator{

	public function translate(PlayerSession $session, InvMenuInfo $current, ContainerOpenPacket $packet) : void;
}